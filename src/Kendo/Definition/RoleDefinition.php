<?php
namespace Kendo\Definition;
use Kendo\Definition\BaseDefinition;

class RoleDefinition extends BaseDefinition
{
    /**
     * Allowed operations
     *
     * A role can be strict with some operations (this rule is applied to all reousrces)
     */
    protected $operations = array();

    public function operations($opertions)
    {
        $args = func_get_args();
        foreach ($args as $arg) {
            if (is_array($arg)) {
                $this->operations = array_merge($this->operations, $arg);
            } else {
                $this->operations[] = $arg;
            }
        }
        return $this;
    }

}



