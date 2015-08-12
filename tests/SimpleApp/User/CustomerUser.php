<?php
namespace SimpleApp\User;
use Kendo\SecurityPolicy\SecurityPolicyModule;
use SimpleApp\SimpleSecurityPolicy;
use Kendo\RuleLoader\RuleLoader;
use Kendo\RuleLoader\SecurityPolicyRuleLoader;
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
