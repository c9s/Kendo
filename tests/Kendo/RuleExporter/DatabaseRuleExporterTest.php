<?php
use SimpleApp\SimpleSecurityPolicy;
use SimpleApp\User\NormalUser;

use Kendo\RuleLoader\RuleLoader;
use Kendo\RuleLoader\SchemaRuleLoader;
use Kendo\RuleMatcher\AccessRuleMatcher;

use Kendo\Operation\GeneralOperation;
use Kendo\Authorizer\Authorizer;
use Kendo\SecurityPolicy\SecurityPolicyModule;

use Kendo\RuleExporter\DatabaseRuleExporter;

use Kendo\Model\AccessRuleCollection;
use Kendo\Model\ActorCollection;
use Kendo\Model\ResourceCollection;

use LazyRecord\Testing\ModelTestCase;

class DatabaseRuleExporterTest extends ModelTestCase
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


    public function testExport()
    {
        $storage = new SecurityPolicyModule;
        $storage->add(new SimpleSecurityPolicy);
        $loader = new SchemaRuleLoader;
        $loader->load($storage);
        $exporter = new DatabaseRuleExporter($loader);
        $exporter->export();


        $rules = new AccessRuleCollection;
        $this->assertCount(10, $rules);

        $actors = new ActorCollection;
        $this->assertCount(3, $actors);
        foreach ($actors as $actor) {
            $this->assertNotEmpty($actor);
            $this->assertNotNull($actor->id);

            // $this->assertNotEmpty($actor->roles);
        }

        $resources = new ResourceCollection;
        $this->assertCount(2, $resources);
    }
}

