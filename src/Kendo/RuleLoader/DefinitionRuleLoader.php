<?php
namespace Kendo\RuleLoader;
use Kendo\DefinitionStorage;
use Kendo\RuleLoader\RuleLoader;
use Kendo\Definition\RuleDefinition;
use SplObjectStorage;
use LogicException;

class DefinitionRuleLoader implements RuleLoader
{
    protected $definitionStorage;

    protected $definedActors = array();

    protected $definedOperations = array();

    protected $definedResources = array();

    /**
     * @var array accessRules[actor][role][resource] = [ CREATE, UPDATE, DELETE ];
     *
     * role == 0   -- without role restriction
     */
    protected $accessRules = array();

    public function __construct()
    {
        $this->definitionStorage = new DefinitionStorage;
    }

    public function getAccessRulesByActorIdentifier($actorIdentifier)
    {
        if ($this->accessRules[ $actorIdentifier ]) {
            return $this->accessRules[ $actorIdentifier ];
        }
    }

    public function getActorDefinitions()
    {
        $all = array();
        foreach ($this->definitionStorage as $definition) {
            if ($actors = $definition->getActorDefinitions()) {
                if (!empty($actors)) {
                    $all = array_merge($all, $actors);
                }
            }
        }
        return $all;
    }

    protected function expandRulePermissions(RuleDefinition $rule)
    {
        // the actor definition
        $actor = $rule->getActor();
        $permissions = $rule->getPermissions();
        foreach ($permissions as $resource => $permissionControls) {

            foreach ($permissionControls as $permissionControl) {

                $allow = $permissionControl['allow'];

                foreach ($permissionControl['operations'] as $op) {

                    if ($roles = $rule->getRoles()) {
                        foreach ($roles as $role) {
                            $this->accessRules[$actor->getIdentifier()][$role][$resource][] = [$op, $allow];
                        }
                    } else {
                        $this->accessRules[ $actor->getIdentifier() ][0][$resource][] = [$op, $allow];
                    }

                }
            }

        }
    }

    public function load(DefinitionStorage $storage)
    {
        // merge definition objects
        $this->definitionStorage->addAll($storage);
        foreach ($storage as $definition) {

            if ($actors = $definition->getActorDefinitions()) {
                foreach ($actors as $actor) {
                    if (isset($this->definedActors[$actor->identifier])) {
                        throw new LogicException("Actor {$actor->identifier} is already defined.");
                    }
                    $this->definedActors[$actor->identifier] = $actor;
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
                foreach ($ops as $op) {
                    if (isset($this->definedOperations[$op->bitmask])) {
                        throw new LogicException("Operation {$op->label} is already defined.");
                    }
                    $this->definedOperations[$op->bitmask] = $op;
                }
            }

            $rules = $definition->getRuleDefinitions();
            foreach ($rules as $rule) {
                $this->expandRulePermissions($rule);
            }

        }
    }
}



