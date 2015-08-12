<?php
namespace Kendo\Definition;
use Kendo\Definition\BaseDefinition;
use Kendo\Definition\OperationDefinitionSet;

class ResourceDefinition extends BaseDefinition
{
    protected $operations = array();

    /**
     * operations method defines available operations of this resource
     */
    public function operations($opertions)
    {
        $args = func_get_args();
        foreach ($args as $arg) {
            if (is_array($arg)) {
                $this->operations = array_merge($this->operations, $arg);
            } else if ($arg instanceof OperationDefinitionSet) {
                $maps = $arg->export();
                foreach ($maps as $value => $label) {
                    $this->operations[] = $value;
                }
            } else {
                $this->operations[] = $arg;
            }
        }
        return $this;
    }
}




