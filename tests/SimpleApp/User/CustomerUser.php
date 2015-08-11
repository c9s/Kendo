<?php
namespace SimpleApp\User;
use Kendo\DefinitionStorage;
use SimpleApp\SimpleDefinition;
use Kendo\RuleLoader\RuleLoader;
use Kendo\RuleLoader\DefinitionRuleLoader;
use Kendo\RuleMatcher\AccessRuleMatcher;
use Kendo\Operation\CommonOperation;
use Kendo\IdentifierProvider\ActorIdentifierProvider;

class CustomerUser implements ActorIdentifierProvider
{
    public function getActorIdentifier()
    {
        return 'customer';
    }
}
