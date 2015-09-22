<?php
namespace Kendo\RuleLoader;
use Kendo\SecurityPolicy\SecurityPolicyModule;
use Kendo\SecurityPolicy\SecurityPolicySchema;
use Kendo\RuleLoader\RuleLoader;
use Kendo\Definition\RuleDefinition;
use SplObjectStorage;
use LogicException;
use Exception;


/**
 * SchemaRuleLoader loads definitions from schema and build up the access rules.
 */
class SchemaRuleLoader implements RuleLoader
{
    protected $policyModule;

    /**
     * @var ActorDefinition[identifier]
     */
    protected $definedActors = array();

    /**
     * @var OperationDefinition[identifier]
     */
    protected $definedOperations = array();

    /**
     * @var ResourceDefinition[identifier]
     */
    protected $definedResources = array();

    /**
     * @var array accessRules[actor][role][resource] = [ CREATE, UPDATE, DELETE ];
     *
     * role == 0   -- without role restriction
     */
    protected $accessRules = array();

    public function __construct(SecurityPolicyModule $storage = null)
    {
        $this->policyModule = $storage ?: new SecurityPolicyModule;
    }


    /**
     * getResourceRulesByActorIdentifier returns access rules foreach resource in
     * the following structure:
     *
     * [
     *    {resource} => [
     *       [ GeneralOperation::CREATE, true ],
     *       [ GeneralOperation::UPDATE, false ],
     *    ],
     * ]
     *
     * $this->accessRules[ $actor->getIdentifier() ][0][$resource][] = [$op, $allow];
     *
     * @return array
     */
    public function getResourceRulesByActorIdentifier($actorIdentifier, $roleIdentifier = null)
    {
        if ($roleIdentifier && isset($this->accessRules[ $actorIdentifier ][ $roleIdentifier ])) {
            return $this->accessRules[ $actorIdentifier ][ $roleIdentifier ];
        } else if (isset($this->accessRules[ $actorIdentifier ][0])) {
            return $this->accessRules[ $actorIdentifier ][0];
        }
    }


    /**
     * getAccessRulesByActorIdentifier returns an array that contains access rules
     *
     * Each access rule contains [ actor, role, resource, bitmask, allow]
     *
     * TODO: need more details about the opeartion (currently we only have
     * bitmask and allow/disallow flag), so that we can describe the reason why
     * we accept/reject the operation.
     */
    public function getAccessRulesByActorIdentifier($actorIdentifier)
    {
        $rules = [];
        foreach ($this->accessRules[$actorIdentifier] as $roleIdentifier => $resourceOperations ) {
            // $roleIdentifer can be zero (means rules without role constraint)
            foreach ($resourceOperations as $resource => $operations) {
                foreach ($operations as $operation) {
                    list($opIdentifier, $allow) = $operation;
                    $rules[] = [$actorIdentifier, $roleIdentifier, $resource, $opIdentifier, $allow];
                }
            }
        }
        return $rules;
    }



    public function getResourceDefinitions()
    {
        return $this->definedResources;
    }

    public function getResourceGroupDefinitions()
    {
        return $this->definedResourceGroups;
    }

    public function getActorDefinitions()
    {
        return $this->definedActors;
    }

    public function getOperationDefinitions()
    {
        return $this->definedOperations;
    }

    protected function expandRulePermissions(RuleDefinition $rule)
    {
        // the actor definition
        $actor = $rule->getActor();
        $permissions = $rule->getPermissions();
        foreach ($permissions as $resource => $permissionControls) {

            foreach ($permissionControls as $opIdentifier => $allow) {
                $op = $this->definedOperations[$opIdentifier];
                if (!$op) {
                    throw new Exception("Undefined operation");
                }

                if ($roles = $rule->getRoles()) {
                    foreach ($roles as $role) {
                        $this->accessRules[$actor->getIdentifier()][$role][$resource][$op->identifier] = $allow;
                    }
                } else {
                    $this->accessRules[$actor->getIdentifier()][0][$resource][$op->identifier] = $allow;
                }
            }

        }
    }

    public function load(SecurityPolicyModule $module)
    {
        // merge definition objects
        $this->policyModule->addAll($module);
        foreach ($module as $definition) {

            if ($actors = $definition->getActorDefinitions()) {
                foreach ($actors as $actor) {
                    if (isset($this->definedActors[$actor->identifier])) {
                        throw new LogicException("Actor {$actor->identifier} is already defined.");
                    }
                    $this->definedActors[$actor->identifier] = $actor;
                }
            }

            if ($groups = $definition->getResourceGroupDefinitions()) {
                foreach ($groups as $group) {
                    if (isset($this->definedResourceGroups[$group->identifier])) {
                        throw new LogicException("Resource Group {$group->identifier} is already defined.");
                    }
                    $this->definedResourceGroups[$group->identifier] = $group;
                }

            }

            if ($resources = $definition->getResourceDefinitions()) {
                foreach ($resources as $resource) {
                    if (isset($this->definedResources[$resource->identifier])) {
                        throw new LogicException("Resource {$resource->identifier} is already defined.");
                    }
                    $this->definedResources[$resource->identifier] = $resource;
                }
            }

            if ($ops = $definition->getOperationDefinitions()) {
                foreach ($ops as $opIdentifier => $op) {
                    if (isset($this->definedOperations[$opIdentifier])) {
                        throw new LogicException("Operation {$opIdentifier} is already defined.");
                    }
                    $this->definedOperations[$opIdentifier] = $op;
                }
            }

            $rules = $definition->getRuleDefinitions();
            foreach ($rules as $rule) {
                $this->expandRulePermissions($rule);
            }

        }
    }
}



