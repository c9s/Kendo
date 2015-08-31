New API Proposal
======================

SecurityPolicyModule
----------------
SecurityPolicyModule loads results from authentication schema modules:

```php
use Kendo\SecurityPolicy\RBACSecurityPolicySchemaStorage;
$loader = new SecurityPolicyModule;
$loader->add(new FooSecurityPolicy);
$loader->add(new BarSecurityPolicy);

// Return flat array that contains rules.
$rules = $loader->expandRules();
```

RuleImporter
------------------
RuleImporter export definitions into database:

- FileCacheRuleImporter: Import rules into cache file.
- PDORuleImporter: Import rules into database
- APCRuleImporter: Import rules into apc

RuleLoader
------------------

- RuleLoader: the basic rule loader, load the rules from definition loader directly.
- PDORuleLoader: load the rules from database through PDO.
- APCRuleLoader: load the rules from APC cache.


RuleMatcher
------------------

- DenyFirstRuleMatcher: Find the first deny rule that matches the current if the rule matches.
- AllowFirstRuleMatcher: Find the first allow rule that matches the current criteria.


Authorizer
------------------

```php
$authorizer = new Authorizer($ruleLoader, $ruleMatcher);

// Pass $user as an actor. $op as the operation, $resource as the resource
if (false === $authorizer->authorize($user, $op, $resource, $ret)) {

}
```

```php
    public function authorize($actor, $op, $resource, & $ret) {
        $actorDefinitions = $this->getActorDefinitions();
        foreach ($actorDefinitions as $actorDefinition) {
            if ($actor instanceof $actorDefinition->class) {
                // get the access controll list for the actor
            }
        }

    }
```




Authenticator
--------------

```php
use Kendo\Authenticator\Authenticator;

$user = new User;

// Load AuthModule dynamically
$authorizor = new Authenticator($loader);

// Authorize
if ($authorizor->can($user,'create','book')) {

}
```


```php
use Kendo\Authenticator\APCAuthenticator;

$authorizor = new APCAuthenticator($loader, [ 'namespace' => 'myapp_' ]);

// we can cache the authentication result in APC
if ($authorizor->can($user, 'create', 'book')) {

}
```




AuthenticatorDumpper
---------------------

```php
$authorizor = new AuthenticatorDumpper($loader);
$authorizor->dump('CachedAuthenticator');

$authorizor = new CachedAuthenticator;
if ($authorizor->can($user, 'create', 'book')) {

}
```

DatabaseRuleAuthenticator
-------------------------

```php

```




Permission Management
--------------------------

When admin wants to manage permissions, the permissions should be rendered as a superset.


### Rendering Permission Items

1. For each security policy modules
    1. List the related resources
    2. For each related reosurces
    3. List the available operation

1. For the current actor, reads all rules related to the current actor with the current role. The role argument is optional if it's not defined.

2. For each rule, find the current access control that matches the current actor, the current actor record id, the current actor role

    - group the rules by modules
    - for each module, list the resources related to the module











