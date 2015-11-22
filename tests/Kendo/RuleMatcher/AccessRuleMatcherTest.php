<?php
use Kendo\SecurityPolicy\SecurityPolicyModule;
use SimpleApp\SimpleSecurityPolicy;
use SimpleApp\User\NormalUser;
use Kendo\RuleLoader\RuleLoader;
use Kendo\RuleLoader\SchemaRuleLoader;
use Kendo\RuleMatcher\AccessRuleMatcher;
use Kendo\Operation\GeneralOperation;
use Kendo\IdentifierProvider\ActorIdentifierProvider;

class AccessRuleMatcherTest extends PHPUnit_Framework_TestCase
{
    public function testMatcherByActorIdentifierProvider()
    {
        $storage = new SecurityPolicyModule;
        $storage->add(new SimpleSecurityPolicy);

        $loader = new SchemaRuleLoader;
        $loader->load($storage);

        $actor = new NormalUser;
        $matcher = new AccessRuleMatcher($loader);
        $rule = $matcher->match($actor, 'view', 'products');
        $this->assertNotNull($rule, 'common user can view products');
        $this->assertTrue($rule->allow);
        $this->assertEquals(0, $rule->role);
        $this->assertEquals('products', $rule->resource);
    }
}

