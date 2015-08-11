<?php
use Kendo\DefinitionStorage;
use SimpleApp\SimpleDefinition;
use SimpleApp\User\NormalUser;

use Kendo\RuleLoader\RuleLoader;
use Kendo\RuleLoader\DefinitionRuleLoader;
use Kendo\RuleMatcher\AccessRuleMatcher;
use Kendo\Operation\CommonOperation;
use Kendo\Authorizer\Authorizer;
use Kendo\IdentifierProvider\ActorIdentifierProvider;
use Kendo\Operation\CommonOperation as Op;

use Kendo\RuleExporter\DatabaseRuleExporter;

class DatabaseRuleExporterTest extends PHPUnit_Framework_TestCase
{
    public function test()
    {
        $storage = new DefinitionStorage;
        $storage->add(new SimpleDefinition);

        $loader = new DefinitionRuleLoader;
        $loader->load($storage);

        $exporter = new DatabaseRuleExporter($loader);
        $exporter->export();
    }
}

