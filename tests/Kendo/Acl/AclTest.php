<?php
namespace KendoTest;
use Kendo\Acl\BaseRules;
use Kendo\Acl\RuleLoader;
use Kendo\Acl\MultiRoleInterface;
use Kendo\Acl\Acl;
use PHPUnit_Framework_TestCase;

class TweetRules extends BaseRules
{
    function build() {
        $this->resource('tweets');
        $this->rule('admin','tweets','delete',true);
        $this->rule('user','tweets','delete',false);
    }
}

class User
    implements MultiRoleInterface
{
    public $roles = array('user' => 1);

    function getRoles() {
        return array_keys($this->roles);
    }

    function addRole($role) {
        $this->roles[$role] = true;
    }

    function removeRole($role)
    {
        unset($this->roles[$role]);
    }

}

class AclTest extends PHPUnit_Framework_TestCase
{
    function test()
    {
        $loader = new \Kendo\Acl\RuleLoader;
        $rules = $loader->load('KendoTest::TweetRules');
        ok($rules);

        ok($rules instanceof \Kendo\Acl\BaseRules);

        $acl = new \Kendo\Acl\Acl($loader);
        ok($acl);

        ok( $acl->can('admin','tweets','delete'));
        ok( ! $acl->can('user','tweets','delete'));

        $user = new User;
        ok($user);
        ok(! $acl->can($user,'tweets','delete'));

        $user->addRole('admin');
        ok( $acl->can($user,'tweets','delete'));

        $user->removeRole('admin');
        ok( ! $acl->can($user,'tweets','delete'));

    }
}

