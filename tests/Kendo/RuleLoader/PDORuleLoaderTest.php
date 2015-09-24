<?php
use LazyRecord\ConnectionManager;
use LazyRecord\Testing\ModelTestCase;

use SimpleApp\SimpleSecurityPolicy;
use SimpleApp\UserSpecificSecurityPolicy;
use SimpleApp\User\NormalUser;
use SimpleApp\User\AdminUser;

use Kendo\RuleLoader\RuleLoader;
use Kendo\RuleLoader\PDORuleLoader;
use Kendo\RuleLoader\SchemaRuleLoader;
use Kendo\RuleMatcher\AccessRuleMatcher;

use Kendo\Operation\GeneralOperation;
use Kendo\Authorizer\Authorizer;
use Kendo\SecurityPolicy\SecurityPolicyModule;

use Kendo\RuleImporter\DatabaseRuleImporter;

use Kendo\Model\AccessRuleCollection;
use Kendo\Model\ActorCollection;
use Kendo\Model\ResourceCollection;
use Kendo\Model\OperationCollection;
use Kendo\Model\RoleCollection;

use CLIFramework\Debug\ConsoleDebug;

class PDORuleLoaderTest extends ModelTestCase
{
    public $driver = 'sqlite';

    public function getModels()
    {
        return [
            new \Kendo\Model\ActorSchema,
            new \Kendo\Model\RoleSchema,
            new \Kendo\Model\ResourceSchema,
            new \Kendo\Model\OperationSchema,
            new \Kendo\Model\AccessRuleSchema,
            new \Kendo\Model\AccessControlSchema,
        ];
    }

    public function setUp()
    {
    }


    public function tearDown()
    {
        $resources = new ResourceCollection;
        $resources->delete();

        $rules = new AccessRuleCollection;
        $rules->delete();

        $actor = new ActorCollection;
        $actor->delete();

        $actors = new ActorCollection;
        $actors->delete();

        $roles = new RoleCollection;
        $roles->delete();

        $ops = new OperationCollection;
        $ops->delete();
    }

    public function testGetActorAccessRulesByResource()
    {
        $module = new SecurityPolicyModule;
        $module->add(new UserSpecificSecurityPolicy);

        $loader = new SchemaRuleLoader;
        $loader->load($module);

        $exporter = new DatabaseRuleImporter($loader);
        $exporter->import();

        $rules = new AccessRuleCollection;
        $this->assertCount(3, $rules);
        // print_r($rules->toArray());
        // echo ConsoleDebug::dumpCollection($rules);


        $connectionManager = ConnectionManager::getInstance();
        $conn = $connectionManager->getConnection($this->getDriverType());
        $this->assertNotNull($conn);


        $loader = new PDORuleLoader();
        $loader->load($conn);


        /*
        $stm = $conn->query('select * from access_rules where resource_record_id IS NULL');
        $rules = $stm->fetchAll();
        echo ConsoleDebug::dumpRows($rules), PHP_EOL;
         */

        // $rules = $loader->queryActorAccessRules('user', 1, 'books');
        $rules = $loader->queryActorAccessRules('user', 1, 'books');
        $this->assertCount(1, $rules);
        $this->assertEquals('create', $rules[0]['operation']);

        $rules = $loader->queryActorAccessRules('user', 2, 'books');
        $this->assertCount(1, $rules);
        $this->assertEquals('update', $rules[0]['operation']);

        $rules = $loader->queryActorAccessRules('user', 3, 'books');
        $this->assertCount(1, $rules);
        $this->assertEquals('delete', $rules[0]['operation']);

        $rules = $loader->queryActorAccessRules('user', 99, 'books');
        $this->assertEmpty($rules);





        // Load permission settings for an actor (with ID)
        // -----------------------------------------------------------------
        $actor = 'user';
        $actorRecordId = 1;
        $policy = new UserSpecificSecurityPolicy;

        // For each resource, load their operations and corresponding setting.
        $permissionSettings = [
            /* resource => [ op => permission, ... ] */
        ];
        $resDefs = $policy->getResourceDefinitions();
        foreach ($resDefs as $resDef) {

            // Get available operations
            if ($resourceOps = $resDef->operations) {
                foreach ($resourceOps as $opIdentifier) {
                    $opDef = $policy->findOperationByIdentifier($opIdentifier);
                    $permissionSettings[$resDef->identifier][$opDef->identifier] = false;
                }
            } else {
                $ops = $policy->getOperationDefinitions();
                foreach ($ops as $opIdentifier => $opDef) {
                    $permissionSettings[$resDef->identifier][$opDef->identifier] = false;
                }
            }

            $rules = $loader->queryActorAccessRules($actor, $actorRecordId, $resDef->identifier);
            foreach ($rules as $rule) {
                $permissionSettings[ $rule['resource'] ][ $rule['operation'] ] = $rule['allow'];
            }
        }

        var_dump( $permissionSettings ); 
        

        // echo ConsoleDebug::dumpRows($rules), PHP_EOL;
    }

    public function testLoad()
    {
        $module = new SecurityPolicyModule;
        $module->add(new SimpleSecurityPolicy);

        $loader = new SchemaRuleLoader;
        $loader->load($module);

        $exporter = new DatabaseRuleImporter($loader);
        $exporter->import();


        $connectionManager = ConnectionManager::getInstance();
        $conn = $connectionManager->getConnection($this->getDriverType());
        $this->assertNotNull($conn);

        $resources = new ResourceCollection;
        $this->assertCount(2, $resources);

        $loader = new PDORuleLoader();
        $loader->load($conn);

        $this->assertCount(2, $resources = $loader->getResourceDefinitions());
        $this->assertCount(3, $actors = $loader->getActorDefinitions());
        $this->assertCount(6, $ops = $loader->getOperationDefinitions());

        $rules = $loader->getActorAccessRules('user');
        $this->assertNotEmpty($rules);
        $this->assertCount(2, $rules, 'two resources');
        $this->assertCount(3, $rules['books'], '3 rules on books');
        $this->assertCount(7, $rules['products'], '7 rules on products');
    }

}

