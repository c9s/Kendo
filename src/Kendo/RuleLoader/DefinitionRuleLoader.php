<?php
namespace Kendo\RuleLoader;
use Kendo\DefinitionStorage;
use Kendo\RuleLoader\RuleLoader;
use Kendo\Definition\RuleDefinition;
use SplObjectStorage;

class DefinitionRuleLoader implements RuleLoader
{
    protected $definitionStorage;

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

            $rules = $definition->getRuleDefinitions();
            foreach ($rules as $rule) {
                $this->expandRulePermissions($rule);
            }
        }
    }
}



