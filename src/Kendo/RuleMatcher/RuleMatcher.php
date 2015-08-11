<?php
namespace Kendo\RuleMatcher;
use Kendo\RuleLoader\RuleLoader;
use Kendo\IdentifierProvider\ActorIdentifierProvider;
use Kendo\IdentifierProvider\RoleIdentifierProvider;
use Kendo\Context;
use LogicException;

class RuleMatcher
{

    protected $loader;

    protected $context;

    public function __construct(RuleLoader $loader, Contenxt $context = null)
    {
        $this->loader = $loader;
    }

    public function match($actor, $operation, $resourceIdentifier, Context $context = null)
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

        $actorIdentifier = null;

        if ($matchedActorDefinition) {
            
            $actorIdentifier = $matchedActorDefinition->identifier;

        } else if ($actor instanceof ActorIdentifierProvider) {

            $actorIdentifier = $actor->getActorIdentifier();

        } else {

            throw new LogicException("Actor identifier is required. please implement ActorIdentifierProvider interface.");

        }

        $accessRules = $this->loader->getAccessRulesByActorIdentifier($actorIdentifier);


        $role = 0;
        if ($actor instanceof RoleIdentifierProvider) {
            $role = $actor->getRoleIdentifier();
        }

        if (empty($accessRules)) {
            return null;
        }

        // If rules defined for the actor (with or without role identifier)
        if (!isset($accessRules[$role])) {
            return null;
        }

        $rule = $accessRules[$role];
        if (!isset($rule[$resourceIdentifier])) {
            return null;
        }

        // TODO: pre-compute the operation mask to improve the performance
        $opControlList = $rule[$resourceIdentifier];
        foreach ($opControlList as $opControl) {
            if ($opControl[0] & $operation) {
                return [
                    'actor' => $actor,
                    'role' => $role,
                    'res' => $resourceIdentifier,
                    'op' => $operation,
                    'allow' => $opControl[1],
                    'mask' => $opControl[0],
                ];
            }
        }
        return null;
    }
}



