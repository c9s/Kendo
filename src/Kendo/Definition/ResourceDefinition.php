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
                foreach ($arg as $bitmask) {
                    $this->operations[] = $bitmask;
                }
            } else if ($arg instanceof OperationDefinition) {

                $this->operations[] = $arg->bitmask;

            } else if (method_exists($arg,'export')) {

                $maps = $arg->export();
                foreach ($maps as $label => $bitmask) {
                    $this->operations[] = $bitmask;
                }

            } else {

                $exporter = new OperationConstantExporter;
                $constants = $exporter->export($arg);

                foreach ($constants as $name => $bitmask) {
                    $this->operations[] = $bitmask;
                }

            }
        }
        return $this;
    }

    public function group(ResourceGroupDefinition $group)
    {
        $this->group = $group;
        return $this;
    }
}




