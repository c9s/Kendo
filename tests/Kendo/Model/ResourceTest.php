<?php
use LazyRecord\Testing\ModelTestCase;
use Kendo\Model\ActorSchema;
use Kendo\Model\Actor;
use Kendo\Model\RoleSchema;
use Kendo\Model\ResourceSchema;
use Kendo\Model\Resource;

class ResourceTest extends ModelTestCase
{
    public $driver = 'sqlite';

    public function getModels()
    {
        return [
            new ResourceSchema,
        ];
    }

    public function testResourceCreate()
    {
        $resource = new Resource;
        $ret = $resource->create([
            'identifier' => 'user',
            'label' => 'User',
        ]);
        $this->assertResultSuccess($ret);
        $this->assertNotNull($resource->id);
    }

    /**
     * @depends testResourceCreate
     */
    public function testResourceUpdate()
    {
        $resource = new Resource;
        $ret = $resource->create([
            'identifier' => 'product',
            'label' => 'Product',
        ]);
        $this->assertResultSuccess($ret);
        $this->assertNotNull($resource->id);

        $ret = $resource->update([
            'identifier' => 'books',
        ]);
        $this->assertResultSuccess($ret);

        $ret = $resource->reload();
        $this->assertResultSuccess($ret);
        $this->assertEquals('books', $resource->identifier);

        $ret = $resource->delete();
        $this->assertResultSuccess($ret);
    }

}

