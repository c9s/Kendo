<?php
namespace Kendo\Definition;

class RoleDefinition
{
    protected $identifier;

    /**
     * Allowed operations
     *
     * A role can be strict with some operations (this rule is applied to all reousrces)
     */
    protected $operations = array();

    public function __construct($identifier)
    {
        $this->identifier = $identifier;
    }

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



