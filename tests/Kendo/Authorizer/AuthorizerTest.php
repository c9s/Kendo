<?php
use Kendo\SecurityPolicy\SecurityPolicyModule;
use SimpleApp\SimpleSecurityPolicy;
use SimpleApp\SecondSecurityPolicy;
use SimpleApp\User\NormalUser;

use Kendo\RuleLoader\RuleLoader;
use Kendo\RuleLoader\SchemaRuleLoader;
use Kendo\RuleMatcher\AccessRuleMatcher;
use Kendo\Operation\GeneralOperation;
use Kendo\Authorizer\Authorizer;
use Kendo\IdentifierProvider\ActorIdentifierProvider;
use Kendo\Operation\GeneralOperation as Op;

class AuthorizerTest extends PHPUnit_Framework_TestCase
{
    public function testAuthorizeThroughAccessRuleMatcher()
    {
        $storage = new SecurityPolicyModule;
        $storage->add(new SimpleSecurityPolicy);
        $storage->add(new SecondSecurityPolicy);

        $loader = new SchemaRuleLoader;
        $loader->load($storage);

        $authorizer = new Authorizer;

        $accessRuleMatcher = new AccessRuleMatcher($loader);
        $authorizer->addMatcher($accessRuleMatcher);


        $actor = new NormalUser;
        $ret = $authorizer->authorize($actor, Op::CREATE, 'products');
        // var_dump( $ret ); 

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

