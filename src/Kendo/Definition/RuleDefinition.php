<?php
namespace Kendo\Definition;
use Kendo\Definition\Definition;
use Exception;

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

    /**
     * Define the actor of this rule.
     *
     * @param string $identifier
     */
    public function actor($identifier)
    {
        $this->actor = $this->definition->findActorByIdentifier($identifier);
        if (!$this->actor) {
            throw new Exception("Actor $identifier not found.");
        }
        return $this;
    }

    public function role($roleIdentifier)
    {
        $this->roles[] = $roleIdentifier;
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

    /**
     *
     * @param array $operations
     * @param array $resources
     *
     * @return RuleDefinition
     */
    public function can($operations, $resources)
    {
        if (is_array($resources)) {
            foreach ($resources as $resourceIdentifier) {
                $this->permissions[$resourceIdentifier][] = [ 'resource' => $resourceIdentifier, 'operations' => (array) $operations, 'allow' => true ];
            }
        } else {
            $this->permissions[$resources][] = [ 'operations' => (array) $operations, 'allow' => true ];
        }
        return $this;
    }

    /**
     *
     * @param array $operations
     * @param array $resources
     *
     * @return RuleDefinition
     */
    public function cant($operations, $resources)
    {
        if (is_array($resources)) {
            foreach ($resources as $resourceIdentifier) {
                $this->permissions[$resourceIdentifier][] = [ 'operations' => (array) $operations, 'allow' => false ];
            }
        } else {
            $this->permissions[$resourceIdentifier][] = [ 'operations' => (array) $operations, 'allow' => false ];
        }
        return $this;
    }



    /**
     * @return ActorDefinition
     */
    public function getActor()
    {
        return $this->actor;
    }

    /**
     * @return array[resource]operations[] Return the permissions of this rule.
     */
    public function getPermissions()
    {
        return $this->permissions;
    }

    /**
     * @var string[] Return rule identifiers
     */
    public function getRoles()
    {
        return $this->roles;
    }
}



