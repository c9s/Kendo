<?php
namespace Kendo\Definition;
use Kendo\Definition\Definition;

class RuleDefinition
{
    /**
     * @var ActorDefinition The actor object specified for the rule
     */
    protected $actor;

    /**
     * @var RoleDefinition[ identifier ]
     */
    protected $roles = array();

    /**
     * @var array[  ]
     */
    protected $permissions = array();

    protected $definition;

    public function __construct(Definition $definition)
    {
        $this->definition = $definition;
    }

    public function actor($identifier)
    {
        $this->actor = $this->definition->findActorByIdentifier($identifier);
        return $this;
    }

    /**
     * Define roles used in the rule
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

    public function can($operations, $resources)
    {
        if (is_array($resources)) {
            foreach ($resources as $resourceIdentifier) {
                $this->permissions[$resourceIdentifier] = (array) $operations;
            }
        } else {
            $this->permissions[$resources] = (array) $operations;
        }
        return $this;
    }

    public function getPermissions()
    {
        return $this->permissions;
    }

    public function getUsers()
    {
        return $this->users;
    }

    public function getRoles()
    {
        return $this->roles;
    }


    public function byUsers()
    { 
        return !empty($this->users);
    }

    public function byRoles()
    { 
        return !empty($this->roles);
    }




}



