
New API Proposal
======================

DefinitionStorage
----------------
DefinitionStorage loads results from authentication schema modules:

```php
use Kendo\Definition\DefinitionStorage;
$loader = new DefinitionStorage;
$loader->add(new FooAuthDefinition);
$loader->add(new BarAuthDefinition);

// Return flat array that contains rules.
$rules = $loader->expandRules();
```

RuleExporter
------------------
RuleExporter export definitions into database:

- FileCacheRuleExporter: Import rules into cache file.
- PDORuleExporter: Import rules into database
- APCRuleExporter: Import rules into apc

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











