<?php
use Kendo\SecurityPolicy\SecurityPolicyModule;
use SimpleApp\SimpleSecurityPolicy;
use Kendo\RuleLoader\RuleLoader;
use Kendo\RuleLoader\SecurityPolicyRuleLoader;

class SecurityPolicyRuleLoaderTest extends PHPUnit_Framework_TestCase
{
    public function testSimpleLoad()
    {
        $storage = new SecurityPolicyModule;
        $storage->add(new SimpleSecurityPolicy);

        $loader = new SecurityPolicyRuleLoader;
        $loader->load($storage);
    }

    public function testGetAccessControlListByActorIdentifier()
    {
        $storage = new SecurityPolicyModule;
        $storage->add(new SimpleSecurityPolicy);

        $loader = new SecurityPolicyRuleLoader;
        $loader->load($storage);
        $userAccessControlList = $loader->getAccessRulesByActorIdentifier('user');
        $this->assertNotEmpty($userAccessControlList);
    }
}

