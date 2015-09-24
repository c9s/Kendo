<?php
namespace Kendo\Definition;
use Kendo\SecurityPolicy\RBACSecurityPolicySchema;
use Exception;

class RuleDefinition
{
    /**
     * @var ActorDefinition The actor object specified for the rule
     */
    protected $actor;

    /**
     * @var integer The id of the actor record
     */
    protected $actorRecordId;

    /**
     * @var RoleDefinition[ identifier ]
     */
    protected $roles = array();

    /**
     * @var array[  ]
     */
    protected $permissions = array();


    protected $policy;

    public function __construct(RBACSecurityPolicySchema $policy)
    {
        $this->policy = $policy;
    }

    /**
     * Define the actor of this rule.
     *
     * @param string $identifier
     */
    public function actor($identifier, $actorRecordId = null)
    {
        $this->actor = $this->policy->findActorByIdentifier($identifier);
        if (!$this->actor) {
            throw new Exception("Actor $identifier not found.");
        }
        $this->actorRecordId = $actorRecordId;
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

        // XXX: validate operation from resource and policy schema
        if (is_array($resources)) {
            foreach ($resources as $resourceIdentifier) {
                foreach ((array) $operations as $operation) {
                    /*
                    if (!$this->policy->findOperationByIdentifier($operation)) {
                        throw new LogicException("Operation $operation doesn't exist in " . get_class($this->policy));
                    }
                     */
                    $this->permissions[$resourceIdentifier][$operation] = true;
                }
            }
        } else if (is_string($resources)) {
            foreach ((array) $operations as $operation) {
                /*
                if (!$this->policy->findOperationByIdentifier($operation)) {
                    throw new LogicException("Operation $operation doesn't exist in " . get_class($this->policy));
                }
                 */
                $this->permissions[$resources][$operation] = true;
            }
        } else {
            throw new Exception('Unsupported type of resources.');
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
                foreach ((array) $operations as $operation) {
                    $this->permissions[$resourceIdentifier][$operation] = false;
                }
            }
        } else if (is_string($resources)) {
            foreach ((array) $operations as $operation) {
                $this->permissions[$resources][$operation] = false;
            }
        } else {
            throw new Exception('Unsupported type of resources.');
        }
        return $this;
    }


    /**
     * Return actor record id (if any)
     *
     * @return integer
     */
    public function getActorRecordId()
    {
        return $this->actorRecordId;
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



