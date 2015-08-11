<?php
use Kendo\DefinitionStorage;
use SimpleApp\SimpleDefinition;
use SimpleApp\User\NormalUser;
use Kendo\RuleLoader\RuleLoader;
use Kendo\RuleLoader\DefinitionRuleLoader;
use Kendo\RuleMatcher\AccessRuleMatcher;
use Kendo\Operation\CommonOperation;
use Kendo\IdentifierProvider\ActorIdentifierProvider;

class AccessRuleMatcherTest extends PHPUnit_Framework_TestCase
{
    public function testMatcherByActorIdentifierProvider()
    {
        $storage = new DefinitionStorage;
        $storage->add(new SimpleDefinition);

        $loader = new DefinitionRuleLoader;
        $loader->load($storage);

        $actor = new NormalUser;
        $matcher = new AccessRuleMatcher($loader);
        $rule = $matcher->match($actor, CommonOperation::VIEW, 'products');
        $this->assertNotNull($rule, 'common user can view products');
        $this->assertTrue($rule->allow);
        $this->assertEquals(0, $rule->role);
        $this->assertEquals('products', $rule->resource);
    }
}

