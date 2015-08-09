<?php
use Kendo\Definition\ActorDefinition;

class ActorDefinitionTest extends PHPUnit_Framework_TestCase
{
    public function testUserActor()
    {
        $actor = new ActorDefinition('user', 'User Actor');
        $actor->roles('admin', 'user', 'customer');
    }
}

