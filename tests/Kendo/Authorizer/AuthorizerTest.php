<?php
use Kendo\DefinitionStorage;
use SimpleApp\SimpleDefinition;
use SimpleApp\User\NormalUser;

use Kendo\RuleLoader\RuleLoader;
use Kendo\RuleLoader\DefinitionRuleLoader;
use Kendo\RuleMatcher\AccessRuleMatcher;
use Kendo\Operation\CommonOperation;
use Kendo\Authorizer\Authorizer;
use Kendo\IdentifierProvider\ActorIdentifierProvider;
use Kendo\Operation\CommonOperation as Op;

class AuthorizerTest extends PHPUnit_Framework_TestCase
{
    public function testAuthorizeThroughAccessRuleMatcher()
    {
        $storage = new DefinitionStorage;
        $storage->add(new SimpleDefinition);

        $loader = new DefinitionRuleLoader;
        $loader->load($storage);

        $authorizer = new Authorizer;

        $accessRuleMatcher = new AccessRuleMatcher($loader);
        $authorizer->addMatcher($accessRuleMatcher);

        /*
        $dynamicRuleMatcher = new DynamicRuleMatcher([
            new MyDynamicRule,
            new YourDynamicRule,
            new AnyDynamicRule,
            new AnotherDynamicRule,
            new TheOtherDynamicRule,
        ]);
        $authorizer->addMatcher($dynamicRuleMatcher);
        */

        /*
        $actor = new NormalUser;
        $authentication = $authorizer->authorize($actor, Op::CREATE, 'products');
        if ($authentication->isAllowed()) {

        } else {
            // throw new GrantAccessFailedException($authentication->getMessage(), $authentication->getReason());
        }
         */


        
    }
}

