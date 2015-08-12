<?php
namespace Kendo\Definition;
use Kendo\Definition\BaseDefinition;
use Kendo\Definition\OperationDefinitionSet;
use Kendo\Definition\OperationDefinition;

class ResourceDefinition extends BaseDefinition
{
    protected $operations = array();

    /**
     * operations method defines available operations of this resource
     *
     * @param OperationDefinitionSet|OperationDefinition
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

            } else if ($arg instanceof OperationDefinitionSet) {
                $maps = $arg->export();
                foreach ($maps as $bitmask => $label) {
                    $this->operations[] = $bitmask;
                }
            } else {
                $this->operations[] = $arg;
            }
        }
        return $this;
    }
}




