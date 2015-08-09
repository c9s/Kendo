<?php
namespace Kendo\Definition;

class ActorDefinition
{
    /**
     * Roles of an actor
     */
    protected $roles = array();

    /**
     * Specify target record identifiers
     */
    protected $recordIds = array();


    public function only($recordIds)
    {
        $this->recordIds = $recordIds;
    }

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

    public function getRoles()
    {
        return $this->roles;
    }

}



