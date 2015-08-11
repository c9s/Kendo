<?php
use Kendo\Model\Actor;
use LazyRecord\Testing\ModelTestCase;
use Kendo\Model\ActorSchema;
use Kendo\Model\RoleSchema;

class ActorTest extends ModelTestCase
{
    public $driver = 'sqlite';

    public function getModels()
    {
        return [
            new ActorSchema,
            new RoleSchema,
        ];
    }

    public function testActorCreate()
    {
        $actor = new Actor;
        $ret = $actor->create([
            'identifier' => 'user',
            'label' => 'User',
        ]);
        $this->assertResultSuccess($ret);
        $this->assertNotNull($actor->id);
    }

    /**
     * @depends testActorCreate
     */
    public function testActorUpdate()
    {
        $actor = new Actor;
        $ret = $actor->create([
            'identifier' => 'user',
            'label' => 'User',
        ]);
        $this->assertResultSuccess($ret);
        $this->assertNotNull($actor->id);

        $ret = $actor->update([
            'identifier' => 'store',
        ]);
        $this->assertResultSuccess($ret);

        $ret = $actor->reload();
        $this->assertResultSuccess($ret);
        $this->assertEquals('store', $actor->identifier);

        $ret = $actor->delete();
        $this->assertResultSuccess($ret);
    }

    public function testAddActorRole()
    {
        $actor = new Actor;
        $ret = $actor->create([
            'identifier' => 'user',
            'label' => 'User',
        ]);
        $this->assertResultSuccess($ret);
        $this->assertNotNull($actor->id);

        $actor->roles[] = array(
            'identifier' => 'member',
            'label' => 'Member',
        );

        $roles = $actor->roles;
        $this->assertCount(1, $roles);

        $actor->roles[] = array(
            'identifier' => 'member',
            'label' => 'Member',
        );

    }

}

