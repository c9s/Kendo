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
        $op = new OperationDefinition($this->policy, $identifier, $label);
        $this->operations[$identifier] = $op;
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
                foreach ($arg as $op => $val) {
                    if ($val instanceof OperationDefinition) {
                        $this->operations[$val->identifier] = $val;
                    } else if (is_numeric($op)) {
                        $this->operations[$val] = new OperationDefinition($this->policy, $val, ucfirst($val));
                    } else {
                        $this->operations[$op] = new OperationDefinition($this->policy, $op, $val);
                    }
                }
            } else if ($arg instanceof OperationDefinition) {

                $this->operations[$arg->identifier] = $arg;

            } else if (method_exists($arg,'export')) {

                $maps = $arg->export();
                foreach ($maps as $identifier => $label) {
                    $this->operations[$identifier] = new OperationDefinition($this->policy, $identifier, $label);
                }

            } else {

                $exporter = new OperationConstantExporter;
                $constants = $exporter->export($arg);

                foreach ($constants as $identifier => $label) {
                    $this->operations[$identifier] = new OperationDefinition($this->policy, $identifier, $label);
                }

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


    public function addChildResource(ResourceDefinition $resource)
    {
        $this->childResources[] = $resource;
    }

    public function getChildResources()
    {
        return $this->childResources;
    }


}




