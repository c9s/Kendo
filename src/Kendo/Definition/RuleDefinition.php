<?php
namespace Kendo\Definition;
use Kendo\Definition\Definition;

class RuleDefinition
{
    protected $users = array();

    protected $roles = array();

    protected $permissions = array();

    protected $definition;

    public function __construct(Definition $definition)
    {
        $this->definition = $definition;
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

    public function users()
    {
        $args = func_get_args();
        foreach ($args as $arg) {
            if (is_array($arg)) {
                $this->users = array_merge($this->users, $arg);
            } else {
                $this->users[] = $arg;
            }
        }
        return $this;

    }

    public function can($operations, $resources)
    {
        $this->permissions[] = [ 'operations' => $operations, 'resources' => $resources ];
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



