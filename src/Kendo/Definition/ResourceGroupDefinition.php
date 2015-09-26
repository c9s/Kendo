<?php
namespace Kendo\Definition;
use Kendo\Definition\BaseDefinition;
use Kendo\Definition\OperationDefinition;

class ResourceGroupDefinition extends ResourceDefinition
{
    protected $childResources = [];

    public function addChildResource(ResourceDefinition $resource)
    {
        $this->childResources[] = $resource;
    }

    public function getChildResources()
    {
        return $this->childResources;
    }


}

