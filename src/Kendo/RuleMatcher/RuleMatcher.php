<?php
namespace Kendo\RuleMatcher;
use Kendo\RuleLoader\RuleLoader;
use Kendo\Provider\ActorIdentifierProvider;
use Kendo\Provider\RoleIdentifierProvider;
use LogicException;

class RuleMatcher
{

    protected $loader;

    public function __construct(RuleLoader $loader)
    {
        $this->loader = $loader;
    }

    public function match($actor, $operation, $context)
    {
        $actorDefinitions = $this->loader->getActorDefinitions();


        $matchedActorDefinition = null;
        foreach ($actorDefinitions as $actorDefinition) {
            if (!$actorDefinition->instanceClass) {
                continue;
            }
            if ($actor instanceof $actorDefinition->instanceClass) {
                $matchedActorDefinition = $actorDefinition;
            }
        }


        $accessRules = null;

        if ($matchedActorDefinition) {

            $accessRules = $this->loader->getAccessRulesByActorIdentifier($matchedActorDefinition->identifier);

        } else if ($actor instanceof ActorIdentifierProvider) {

            $accessRules = $this->loader->getAccessRulesByActorIdentifier($actor->getActorIdentifier());

        }

        if ($accessRules) {

            foreach ($accessRules as $rule) {

                foreach ($rule as $role => $resources) {

                    if (isset($resources[$resource])) {
                        $allowedOperations = $resources[$resource];

                        foreach ($allowedOperations as $op) {

                        }
                    }

                }

                var_dump($rule);
            }


            return;
        }

            // } else {
            // throw new LogicException('Actor identifier is required for the rules');
            // provide an option to raise error if the actor identifer is not found.
        $allAccessRules = $this->loader->getAllAccessRules();

        foreach ($allAccessRules as $rule) {
            var_dump($rule);
        }
    }
}



