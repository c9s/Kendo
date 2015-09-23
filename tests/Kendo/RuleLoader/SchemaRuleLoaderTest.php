<?php
use Kendo\SecurityPolicy\SecurityPolicyModule;
use SimpleApp\SimpleSecurityPolicy;
use Kendo\RuleLoader\RuleLoader;
use Kendo\RuleLoader\SchemaRuleLoader;

class SchemaRuleLoaderTest extends PHPUnit_Framework_TestCase
{
    public function testSimpleLoad()
    {
        $storage = new SecurityPolicyModule;
        $storage->add(new SimpleSecurityPolicy);

        $loader = new SchemaRuleLoader;
        $loader->load($storage);
    }

    public function testGetAccessRulesByActorIdentifier()
    {
        $storage = new SecurityPolicyModule;
        $storage->add(new SimpleSecurityPolicy);

        $loader = new SchemaRuleLoader;
        $loader->load($storage);

        // get user actor related rules
        $userRules = $loader->getAccessRulesByActorIdentifier('user');
        $this->assertNotEmpty($userRules);
    }

    public function testGetAccessControlListByActorIdentifier()
    {
        $storage = new SecurityPolicyModule;
        $storage->add(new SimpleSecurityPolicy);

        $loader = new SchemaRuleLoader;
        $loader->load($storage);
        $userAccessControlList = $loader->getActorAccessRules('user');
        $this->assertNotEmpty($userAccessControlList);
    }
}

