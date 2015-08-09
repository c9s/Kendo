<?php
use Kendo\Definition\Definition;

class MockUser
{
    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }
}

class FooDefinition extends Definition
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

class DefinitionTest extends PHPUnit_Framework_TestCase
{
    public function testDefinitionSchema()
    {
        $definition = new FooDefinition();
        $definition->schema();
    }
}

