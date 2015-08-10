<?php
use Kendo\DefinitionStorage;
use SimpleApp\SimpleDefinition;
use Kendo\RuleLoader\RuleLoader;
use Kendo\RuleLoader\DefinitionRuleLoader;
use Kendo\RuleMatcher\RuleMatcher;
use Kendo\Operation\CommonOperation;
use Kendo\IdentifierProvider\ActorIdentifierProvider;

class ActorIdentifierProviderUser implements ActorIdentifierProvider
{
    public function getActorIdentifier()
    {
        return 'user';
    }
}

class RuleMatcherTest extends PHPUnit_Framework_TestCase
{
    public function testMatcherByActorIdentifierProvider()
    {
        $storage = new DefinitionStorage;
        $storage->add(new SimpleDefinition);

        $loader = new DefinitionRuleLoader;
        $accessControlList = $loader->load($storage);
        $this->assertNotEmpty($accessControlList);

        $actor = new ActorIdentifierProviderUser;
        $matcher = new RuleMatcher($loader);
        $rule = $matcher->match($actor, CommonOperation::CREATE, 'books');
    }
}

