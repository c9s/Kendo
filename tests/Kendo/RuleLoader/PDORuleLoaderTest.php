<?php
use LazyRecord\ConnectionManager;
use LazyRecord\Testing\ModelTestCase;

use SimpleApp\SimpleSecurityPolicy;
use SimpleApp\User\NormalUser;

use Kendo\RuleLoader\RuleLoader;
use Kendo\RuleLoader\PDORuleLoader;
use Kendo\RuleLoader\SchemaRuleLoader;
use Kendo\RuleMatcher\AccessRuleMatcher;

use Kendo\Operation\GeneralOperation;
use Kendo\Authorizer\Authorizer;
use Kendo\SecurityPolicy\SecurityPolicyModule;

use Kendo\RuleExporter\DatabaseRuleExporter;

use Kendo\Model\AccessRuleCollection;
use Kendo\Model\ActorCollection;
use Kendo\Model\ResourceCollection;

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

    public function testLoad()
    {
        $storage = new SecurityPolicyModule;
        $storage->add(new SimpleSecurityPolicy);
        $loader = new SchemaRuleLoader;
        $loader->load($storage);

        $exporter = new DatabaseRuleExporter($loader);
        $exporter->export();

        $resources = new ResourceCollection;
        $this->assertCount(2, $resources);

        $connectionManager = ConnectionManager::getInstance();
        $this->assertNotNull($connectionManager);

        $conn = $connectionManager->getConnection($this->getDriverType());
        $this->assertNotNull($conn);

        $loader = new PDORuleLoader();
        $loader->load($conn);

        $this->assertCount(2, $resources = $loader->getResourceDefinitions());
        $this->assertCount(3, $actors = $loader->getActorDefinitions());
        $this->assertCount(6, $ops = $loader->getOperationDefinitions());

        $rules = $loader->getAccessRulesByActorIdentifier('user', 'admin');
        $this->assertNotEmpty($rules);
        $this->assertCount(2, $rules, 'two resources');
        $this->assertCount(3, $rules['books'], '3 rules on books');

        /*
        $rules = new AccessRuleCollection;
        $this->assertCount(4, $rules);

        $actors = new ActorCollection;
        $this->assertCount(3, $actors);
        foreach ($actors as $actor) {
            $this->assertNotEmpty($actor);
            $this->assertNotNull($actor->id);
        }
        */

    }
}

