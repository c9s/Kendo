<?php
use Kendo\SecurityPolicy\SecurityPolicyModule;
use SimpleApp\SimpleSecurityPolicy;
use SimpleApp\User\NormalUser;

use Kendo\RuleLoader\RuleLoader;
use Kendo\RuleLoader\SecurityPolicyRuleLoader;
use Kendo\RuleMatcher\AccessRuleMatcher;

use Kendo\Operation\GeneralOperation;
use Kendo\Authorizer\Authorizer;
use Kendo\IdentifierProvider\ActorIdentifierProvider;

use Kendo\RuleExporter\DatabaseRuleExporter;
use LazyRecord\Testing\ModelTestCase;

use Kendo\Model\AccessRuleCollection;
use Kendo\Model\ActorCollection;
use Kendo\Model\ResourceCollection;

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
            ];
    }


    public function testExport()
    {
        $storage = new SecurityPolicyModule;
        $storage->add(new SimpleSecurityPolicy);

        $loader = new SecurityPolicyRuleLoader;
        $loader->load($storage);

        $exporter = new DatabaseRuleExporter($loader);
        $exporter->export();


        $rules = new AccessRuleCollection;
        $this->assertCount(4, $rules);

        $actors = new ActorCollection;
        $this->assertCount(3, $actors);

        $resources = new ResourceCollection;
        $this->assertCount(2, $resources);
    }
}

