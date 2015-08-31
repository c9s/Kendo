<?php
use LazyRecord\ConnectionManager;
use LazyRecord\Testing\ModelTestCase;

use SimpleApp\SimpleSecurityPolicy;
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

        $exporter = new DatabaseRuleImporter($loader);
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
        $this->assertCount(3, $rules['products'], '3 rules on books');

        $rules = $loader->getAccessRulesByActorIdentifier('user');
        $this->assertNotEmpty($rules);


        $matcher = new AccessRuleMatcher($loader);
        $actor = new NormalUser;
        $rule = $matcher->match($actor, GeneralOperation::VIEW, 'products');
        $this->assertNotNull($rule, 'common user can view products');
        $this->assertTrue($rule->allow);
        $this->assertEquals(0, $rule->role);
        $this->assertEquals('products', $rule->resource);
    }
}

