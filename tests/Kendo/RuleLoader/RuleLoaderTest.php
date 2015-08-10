<?php
use Kendo\DefinitionStorage;
use SimpleApp\SimpleDefinition;
use Kendo\RuleLoader\RuleLoader;

class RuleLoaderTest extends PHPUnit_Framework_TestCase
{
    public function test()
    {
        $loader = new DefinitionStorage;
        $loader->add(new SimpleDefinition);

        $ruleLoader = new RuleLoader($loader);
    }
}

