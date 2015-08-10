<?php
use Kendo\DefinitionStorage;
use SimpleApp\SimpleDefinition;
use Kendo\RuleLoader\RuleLoader;
use Kendo\RuleLoader\DefinitionRuleLoader;

class DefinitionRuleLoaderTest extends PHPUnit_Framework_TestCase
{
    public function testSimpleLoad()
    {
        $storage = new DefinitionStorage;
        $storage->add(new SimpleDefinition);

        $loader = new DefinitionRuleLoader;
        $loader->load($storage);
    }

    public function testGetAccessControlListByActorIdentifier()
    {
        $storage = new DefinitionStorage;
        $storage->add(new SimpleDefinition);

        $loader = new DefinitionRuleLoader;
        $loader->load($storage);
        $userAccessControlList = $loader->getAccessRulesByActorIdentifier('user');
        $this->assertNotEmpty($userAccessControlList);
    }
}

