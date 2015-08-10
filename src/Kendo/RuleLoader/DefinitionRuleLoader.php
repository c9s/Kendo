<?php
namespace Kendo\RuleLoader;
use Kendo\DefinitionStorage;
use Kendo\RuleLoader\RuleLoader;
use SplObjectStorage;

class DefinitionRuleLoader implements RuleLoader
{
    protected $definitionStorage;

    /**
     * @var array accessRules[actor][role][resource] = [ CREATE, UPDATE, DELETE ];
     *
     * role == 0   -- without role restriction
     */
    // protected $accessRules = array();

    protected $allowRules = array();

    protected $denyRules = array();

    public function __construct()
    {
        $this->definitionStorage = new DefinitionStorage;
    }


    public function getDenyRulesByActorIdentifier($actorIdentifier)
    {
        if ($this->denyRules[ $actorIdentifier ]) {
            return $this->denyRules[ $actorIdentifier ];
        }
    }

    public function getAllowRulesByActorIdentifier($actorIdentifier)
    {
        if ($this->allowRules[ $actorIdentifier ]) {
            return $this->allowRules[ $actorIdentifier ];
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

    public function load(DefinitionStorage $storage)
    {
        // merge definition objects
        $this->definitionStorage->addAll($storage);
        foreach ($storage as $definition) {

            $allowRules = $definition->getAllowRuleDefinitions();
            foreach ($allowRules as $rule) {
                $actor = $rule->getActor();
                $permissions = $rule->getPermissions();

                foreach ($permissions as $resource => $operations) {
                    if ($roles = $rule->getRoles()) {
                        foreach ($roles as $role) {
                            $this->allowRules[$actor->getIdentifier()][$role][$resource] = $operations;
                        }
                    } else {
                        $this->allowRules[ $actor->getIdentifier() ][0][$resource] = $operations;
                    }
                }
            }


            $denyRules = $definition->getAllowRuleDefinitions();
            foreach ($denyRules as $rule) {
                $actor = $rule->getActor();
                $permissions = $rule->getPermissions();

                foreach ($permissions as $resource => $operations) {
                    if ($roles = $rule->getRoles()) {
                        foreach ($roles as $role) {
                            $this->denyRules[$actor->getIdentifier()][$role][$resource] = $operations;
                        }
                    } else {
                        $this->denyRules[ $actor->getIdentifier() ][0][$resource] = $operations;
                    }
                }
            }

        }
        return [
            'allow' => $allow,
            'deny'  => $deny,
        ];
    }

}



