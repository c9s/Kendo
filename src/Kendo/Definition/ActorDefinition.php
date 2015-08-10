<?php
namespace Kendo\Definition;


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
                $this->roles = array_merge($this->roles, $arg);
            } else {
                $this->roles[] = $arg;
            }
        }
        return $this;
    }


    public function instanceBy($instanceClass)
    {
        return $instanceClass;
    }

    /**
     * @return string[] Return role identifiers
     */
    public function getRoles()
    {
        return $this->roles;
    }

}



