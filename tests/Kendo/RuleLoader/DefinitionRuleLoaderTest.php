<?php
use Kendo\SecurityPolicyModule;
use SimpleApp\SimpleSecurityPolicy;
use Kendo\RuleLoader\RuleLoader;
use Kendo\RuleLoader\DefinitionRuleLoader;

class DefinitionRuleLoaderTest extends PHPUnit_Framework_TestCase
{
    public function testSimpleLoad()
    {
        $storage = new SecurityPolicyModule;
        $storage->add(new SimpleSecurityPolicy);

        $loader = new DefinitionRuleLoader;
        $loader->load($storage);
    }

    public function testGetAccessControlListByActorIdentifier()
    {
        $storage = new SecurityPolicyModule;
        $storage->add(new SimpleSecurityPolicy);

        $loader = new DefinitionRuleLoader;
        $loader->load($storage);
        $userAccessControlList = $loader->getAccessRulesByActorIdentifier('user');
        $this->assertNotEmpty($userAccessControlList);
    }
}

