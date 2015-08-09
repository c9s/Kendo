
New API Proposal
======================

DefinitionLoader
----------------
DefinitionLoader loads results from authentication schema modules:

```php
use Kendo\Definition\DefinitionLoader;
$loader = new DefinitionLoader;
$loader->load(new FooAuthDefinition);
$loader->load(new BarAuthDefinition);
```

RuleImporter
------------------
RuleImporter imports definitions into database:





PDOAuthLoader
------------------
use Kendo\RuleLoader\PDORuleLoader;

AuthPDOLoader loads results from database:

```php
$loader = new AuthPDOLoader($dbh);
```


DatabaseDumpper
----------------

```php
use Kendo\DefinitionImporter\DatabaseImporter;
$dumpper = new DatabaseImporter($loader, $config);
$success = $dumpper->dump();
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











