
New API Proposal
======================


Authorize
--------------

```php
$user = new User;


$authorizor = new Authenticator;
$authorizor->load(new FooAuthModule);
$authorizor->load(new BarAuthModule);
if ($authorizor->can($user,'create','book')) {

}
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
            ->byUserId(1);

        $this->rule(CAN_NOT, 'create', 'book')
            ->byUserId(1)
            ->byRole('admin');


        // define some default rules
        $this->ruleByRole('admin', CAN, 'create', 'book');
        $this->ruleByRole('admin', CAN, 'update', 'book');
        $this->ruleByRole('admin', CAN_NOT, 'delete', 'book');

        $this->ruleByUser('user-identifier', CAN, 'create', 'book');
        $this->ruleByUser('c9s', CAN, 'update', 'book');
        $this->ruleByUser('azole', CAN_NOT, 'delete', 'book');

        // We can use user id primary key if we don't have identifiers
        $this->ruleByUser('1', CAN_NOT, 'delete', 'book');
        $this->ruleByUser('2', CAN_NOT, 'delete', 'book');
        $this->ruleByUser('3', CAN_NOT, 'delete', 'book');
    }

}
```



