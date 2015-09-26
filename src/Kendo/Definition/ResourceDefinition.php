<?php
namespace Kendo\Definition;
use Kendo\Definition\BaseDefinition;
use Kendo\Definition\OperationDefinition;
use Kendo\Definition\ResourceGroupDefinition;
use Kendo\Operation\OperationConstantExporter;

class ResourceDefinition extends BaseDefinition
{
    public $operations = array();

    public $group;


    /**
     * operations method defines available operations of this resource
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
                        $this->operations[$val->identifier] = true;
                    } else {
                        $this->operations[$op] = $val;
                    }
                }
            } else if ($arg instanceof OperationDefinition) {

                $this->operations[$op->identifier] = $op;

            } else if (method_exists($arg,'export')) {

                $maps = $arg->export();
                foreach ($maps as $identifier => $val) {
                    $this->operations[$identifier] = true;
                }

            } else {

                $exporter = new OperationConstantExporter;
                $constants = $exporter->export($arg);

                foreach ($constants as $identifier => $val) {
                    $this->operations[$identifier] = $val;
                }

            }
        }
        return $this;
    }

    public function group(ResourceGroupDefinition $group)
    {
        $this->group = $group;
        $group->addChildResource($this);
        return $this;
    }
}




