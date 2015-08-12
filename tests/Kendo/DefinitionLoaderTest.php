<?php
use Kendo\SecurityPolicy\SecurityPolicyModule;
use SimpleApp\SimpleSecurityPolicy;

class SecurityPolicyModuleTest extends PHPUnit_Framework_TestCase
{
    public function testSecurityPolicyModule()
    {
        $loader = new SecurityPolicyModule;
        $loader->add($a = new SimpleSecurityPolicy);
        $loader->detach($a);
    }
}

