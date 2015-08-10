<?php
use Kendo\DefinitionStorage;
use SimpleApp\SimpleDefinition;
use Kendo\RuleLoader\RuleLoader;

class RuleLoaderTest extends PHPUnit_Framework_TestCase
{
    public function testSimpleLoad()
    {
        $storage = new DefinitionStorage;
        $storage->add(new SimpleDefinition);

        $loader = new RuleLoader;
        $accessControlList = $loader->load($storage);
        $this->assertNotEmpty($accessControlList);
    }

    public function testGetAllAccessControlList()
    {
        $storage = new DefinitionStorage;
        $storage->add(new SimpleDefinition);

        $loader = new RuleLoader;
        $loader->load($storage);
        $listAll = $loader->getAllAccessControlList();
        $this->assertNotEmpty($listAll);
    }

    public function testGetAccessControlListByActorIdentifier()
    {
        $storage = new DefinitionStorage;
        $storage->add(new SimpleDefinition);

        $loader = new RuleLoader;
        $loader->load($storage);
        $userAccessControlList = $loader->getAccessControlListByActorIdentifier('user');
        $this->assertNotEmpty($userAccessControlList);
    }
}

