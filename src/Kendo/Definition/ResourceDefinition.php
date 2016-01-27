<?php
namespace Kendo\Definition;
use Kendo\Definition\BaseDefinition;
use Kendo\Definition\OperationDefinition;
use Kendo\Operation\OperationConstantExporter;

class ResourceDefinition extends BaseDefinition
{
    public $operations = array();


    /**
     * @var Kendo\Definition\ResourceDefinition
     */
    public $group;

    public $group_id;


    /**
     * @var Kendo\Definition\ResourceDefinition
     */
    protected $childResources = [];


    public $label;

    public $description;


    public function operation($identifier, $label = null)
    {
        if (isset($this->operations[$identifier])) {
            throw new Exception("Operation $label ($identifier) is already defined.");
        }
        $def = new OperationDefinition($this->policy, $identifier, $label);
        $this->operations[$identifier] = $def;
        return $this;
    }



    private function inheritsOperationOrCreate($identifier, $label = null)
    {
        if ($def = $this->policy->findOperationByIdentifier($identifier)) {
            $this->operations[$identifier] = $def;
        } else {
            $this->operations[$identifier] = new OperationDefinition($this->policy, $identifier, $label);
        }
    }


    private function importOperationsFromMap(array $identifierToLabel)
    {
        foreach ($identifierToLabel as $identifier => $label) {
            $this->inheritsOperationOrCreate($identifier, $label);
        }
    }


    public function useGlobalOperations()
    {
        $this->operations = $this->policy->getOperationDefinitions();
        return $this;
    }

    /**
     * Operations method defines available operations of this resource
     *
     * @param OperationDefinition
     */
    public function operations($opertions)
    {
        $args = func_get_args();
        foreach ($args as $arg) {
            if (is_array($arg)) {
                // supports the following formats:
                //
                //     operations([ 'view', 'create' ])
                //
                //     operations([
                //          'view' => 'View',
                //          'create' => 'Create',
                //     ])
                //
                //     operations(new OperationDefinition('foo','Handle Foo'))
                //
                foreach ($arg as $op => $val) {
                    if ($val instanceof OperationDefinition) {
                        $this->operations[$val->identifier] = $val;
                    } else if (is_numeric($op)) {
                        $this->inheritsOperationOrCreate($val, ucfirst($val));
                    } else if ($def = $this->policy->findOperationByIdentifier($op)) {
                        $this->operations[$op] = $def;
                    } else {
                        $this->operations[$op] = new OperationDefinition($this->policy, $op, $val);
                    }
                }
            } else if ($arg instanceof OperationDefinition) {

                $this->operations[$arg->identifier] = $arg;

            } else {
                $exporter = new OperationConstantExporter;
                $constants = $exporter->export($arg);
                $this->importOperationsFromMap($constants);
            }
        }
        return $this;
    }

    public function group(ResourceDefinition $group)
    {
        $this->group = $group;
        $group->addChildResource($this);
        return $this;
    }

    /**
     * Create a new child resource group
     */
    public function resourceGroup($identifier, $label = null)
    {
        $childGroup = new ResourceDefinition($this->policy, $identifier, $label);
        $childGroup->group = $this;
        $this->addChildResource($childGroup);
        return $childGroup;
    }


    /**
     * A resource definition is a group when there are childResources under it.
     */
    public function isGroup()
    {
        return !empty($this->childResources);
    }

    public function removeChildResource($identifier)
    {
        if (isset($this->childResources[$identifier])) {
            unset($this->childResources[$identifier]);
        } else {
            foreach ($this->childResources as $resId => $resource) {
                $resource->removeChildResource($identifier);

                // prune the child resource nodes
                if (empty($resource->getChildResources()) && empty($resource->operations)) {
                    $this->removeChildResource($resId);
                }
            }
        }
    }

    public function addChildResource(ResourceDefinition $resource)
    {
        $this->childResources[$resource->identifier] = $resource;
    }

    public function getChildResources()
    {
        return $this->childResources;
    }


}




