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
class SchemaRuleLoader extends BaseRuleLoader implements RuleLoader
{
    protected $policyModule;

    protected $accessRules = array();

    public function __construct(SecurityPolicyModule $storage = null)
    {
        $this->policyModule = $storage ?: new SecurityPolicyModule;
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



            // Populate rule definitions into access rule array structure
            $ruleDefinitions = $definition->getRuleDefinitions();
            foreach ($ruleDefinitions as $ruleDef) {

                $actor = $ruleDef->getActor();
                $actorIdentifier = $actor->getIdentifier();
                $actorRecordId = $ruleDef->getActorRecordId();
                $permissions = $ruleDef->getPermissions();


                if ($roles = $ruleDef->getRoles()) { ; // roles = string[]
                    foreach ($roles as $roleIdentifier) {
                        $roleDef = $actor->getRoleDefinition($roleIdentifier);
                        foreach ($permissions as $resourceIdentifier => $permissionControls) {
                            foreach ($permissionControls as $op => $allow) {
                                $this->accessRules[$actorIdentifier][$resourceIdentifier][] = [ 
                                    'op' => $op,
                                    'role' => $roleIdentifier,
                                    'actor_record_id' => $actorRecordId,
                                    'resource_record_id' => null,
                                    'allow' => $allow,
                                ];
                            }
                        }
                    }
                } else {
                    foreach ($permissions as $resourceIdentifier => $permissionControls) {
                        foreach ($permissionControls as $op => $allow) {
                            $this->accessRules[$actorIdentifier][$resourceIdentifier][] = [ 
                                'op' => $op,
                                'role' => null,
                                'actor_record_id' => $actorRecordId,
                                'resource_record_id' => null,
                                'allow' => $allow,
                            ];
                        }
                    }
                }

            }
        }
    }

    /**
     * getActorAccessRules returns access rules foreach resource in
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
    public function getActorAccessRules($actorIdentifier)
    {
        if (isset($this->accessRules[$actorIdentifier])) {
            return $this->accessRules[$actorIdentifier];
        }
    }

}



