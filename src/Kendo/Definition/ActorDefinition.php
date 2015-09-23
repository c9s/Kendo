<?php
namespace Kendo\Definition;
use Kendo\Definition\RoleDefinition;

/**
 * Data object for actor definition
 */
class ActorDefinition extends BaseDefinition
{
    /**
     * @var string the instance class name. used for detecting actors
     */
    public $instanceClass;

    /**
     * Roles of an actor
     */
    public $roles = array();

    /**
     * Define roles of an actor
     */
    public function roles()
    {
        $args = func_get_args();
        foreach ($args as $arg) {
            if (is_array($arg)) {
                foreach ($arg as $roleIdentifier) {
                    if (is_array($roleIdentifier)) {
                        $this->roles[$roleIdentifier] = new RoleDefinition($roleIdentifier[0], $roleIdentifier[1]);
                    } else {
                        $this->roles[$roleIdentifier] = new RoleDefinition($roleIdentifier, $roleIdentifier);
                    }
                }
            } else {
                $this->roles[$arg] = new RoleDefinition($arg);
            }
        }
        return $this;
    }

    public function role($roleIdentifier, $roleLabel)
    {
        $this->roles[$roleIdentifier] = new RoleDefinition($roleIdentifier, $roleLabel);
        return $this;
    }

    public function instanceBy($instanceClass)
    {
        return $instanceClass;
    }

    public function getRoleDefinition($roleIdentifier)
    {
        if (isset($this->roles[ $roleIdentifier ])) {
            return $this->roles[ $roleIdentifier ];
        }
    }


    /**
     * @return RoleDefinition[string identifier] Return role identifiers
     */
    public function getRoleDefinitions()
    {
        return $this->roles;
    }

}



