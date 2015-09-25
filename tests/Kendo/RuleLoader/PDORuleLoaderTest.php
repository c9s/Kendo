<?php
use LazyRecord\ConnectionManager;
use LazyRecord\Testing\ModelTestCase;
use Kendo\Testing\DatabaseTestCase;

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

class PDORuleLoaderTest extends DatabaseTestCase
{


    public function getPolicies()
    {
        return [
            new UserSpecificSecurityPolicy,
        ];
    }


    public function testGetActorAccessRulesByResource()
    {
        $loader = new SchemaRuleLoader;
        $loader->load($this->policyModule);

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

