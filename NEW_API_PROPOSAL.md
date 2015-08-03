
New API Proposal
======================

SchemaAuthLoader
----------------
SchemaAuthLoader loads results from authentication schema modules:

```php
use Kendo\AuthLoader\SchemaAuthLoader;
$loader = new SchemaAuthLoader;
$loader->load(new FooAuthModule);
$loader->load(new BarAuthModule);
```

PDOAuthLoader
------------------
use Kendo\AuthLoader\PDOAuthLoader;

AuthPDOLoader loads results from database:

```php
$loader = new AuthPDOLoader($dbh);
```


DatabaseDumpper
----------------

```php
use Kendo\AuthLoader\DatabaseDumper;
$dumpper = new DatabaseDumper($loader, $config);
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











AuthModule
--------------

```php
class FooAuthModule
{
    public function resource()
    {
        return [
            'user' => _('user'),
            'book' => _('book'),
            'author' => _('author'),
            'settings' => _('settings'),
        ];
    }

    public function roles()
    {
        return [
            'admin',
            'user',
        ];
    }

    public function rules()
    {


    }

    public function schema()
    {
        // define roles
        $this->role('admin');
        $this->role('user');

        $this->op('create', 'create resource');
        $this->op('print', 'print resource');
        $this->op('export', 'export resource');

        $this->rule(CAN, 'create', 'book')
            ->byRole('admin');

        $this->rule(CAN_NOT, 'create', 'book')
            ->byRole('admin');

        $this->rule(CAN_NOT, 'create', 'book')
            ->byUserId(1)
            ->byIdentifier(1)
            ;

        // Expand the same rule to differnet user and role
        $this->rule(CAN_NOT, 'create', 'book')
            ->byIdentifier(1)
            ->byRole('admin');

        // Expand the same rule to differnet user and role
        $this->rule(CAN_NOT, 'create', 'book')
            ->byIdentifier('c9s');

        $this->rule(CAN_NOT, 'create', 'book')
            ->byIdentifiers(['c9s', 'azole', '....']);

        $this->rule(CAN_NOT, 'create', 'book')
            ->byIdentifiers('c9s', 'azole', '....');
    }
}
```



