<?php
use Kendo\SecurityPolicy\RBACSecurityPolicySchema;
use SimpleApp\UserSpecificSecurityPolicy;

class MockUser
{
    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }
}

class FooSecurityPolicy extends RBACSecurityPolicySchema
{
    public function schema()
    {
        $this->actor('user', 'User')
            ->roles('admin', 'user', 'customer');

        /*
        $this->rule()
            ->roles(['admin', 'user'])
            ->can(['create', 'update'], 'book')
            ;

        $this->rule()
            ->roles('admin', 'user')
            ->can(['create', 'update'], 'book')
            ;

        $user1 = new MockUser(1);
        $user2 = new MockUser(2);

        $this->rule()
            ->users($user1, $user2)
            ->can(['create', 'update'], 'book')
            ;

        $this->rule()
            ->users([$user1])
            ->can(['create', 'update'], 'book')
            ;
         */
    }
}

class RBACSecurityPolicySchemaTest extends PHPUnit_Framework_TestCase
{
    public function testSchema()
    {
        $definition = new FooSecurityPolicy();
        $definition->schema();

    }

    public function testOperationDefinitions() 
    {
        $policy = new UserSpecificSecurityPolicy;
        $ops = $policy->getOperationDefinitions();

        $this->assertTrue(isset($ops['create']));
        $this->assertTrue(isset($ops['update']));
        $this->assertTrue(isset($ops['delete']));

        foreach ($ops as $identifier => $op) {
            $this->assertInstanceOf('Kendo\\Definition\\OperationDefinition', $op);
        }
    }
}

