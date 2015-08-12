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
use Kendo\Operation\GeneralOperation as Op;

use Kendo\RuleExporter\DatabaseRuleExporter;
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
    }
}

