<?php
namespace Kendo\RuleMatcher;
use Kendo\RuleLoader\RuleLoader;
use Kendo\IdentifierProvider\ActorIdentifierProvider;
use Kendo\IdentifierProvider\RoleIdentifierProvider;
use Kendo\IdentifierProvider\ResourceIdentifierProvider;
use Kendo\IdentifierProvider\GeneralIdentifierProvider;
use Kendo\RuleMatcher\RuleMatcher;
use Kendo\Context;
use LogicException;

class AccessRuleMatcher implements RuleMatcher
{

    protected $loader;

    protected $context;

    public function __construct(RuleLoader $loader, Contenxt $context = null)
    {
        $this->loader = $loader;
    }

    public function match($actor, $operation, $resource, Context $context = null)
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

        } else if ($actor instanceof GeneralIdentifierProvider) {

            $actorIdentifier = $actor->getIdentifier();

        } else {

            throw new LogicException("Can't recognise actor, identifier provider interface is not implemented.");

        }

        $role = 0;
        if ($actor instanceof RoleIdentifierProvider) {
            $role = $actor->getRoleIdentifier();
        }

        $accessRules = $this->loader->getAccessRulesByActorIdentifier($actorIdentifier, $role);

        if ($accessRules === null || empty($accessRules)) {
            return null;
        }

        $resourceIdentifier = null;
        if (is_string($resource)) {

            $resourceIdentifier = $resource;

        } else if ($resource instanceof ResourceIdentifierProvider) {

            $resourceIdentifier = $resource->getResourceIdentifier();

        } else if ($resource instanceof GeneralIdentifierProvider) {

            $resourceIdentifier = $resource->getIdentifier();

        } else {

            throw new LogicException("Can't recognise resource, identifier provider interface is not implemented.");

        }

        if (!isset($accessRules[$resourceIdentifier])) {
            return null;
        }

        // TODO: pre-compute the operation mask to improve the performance
        $opControlList = $accessRules[$resourceIdentifier];
        foreach ($opControlList as $opControl) {
            if ($opControl[0] & $operation) {
                // XXX: should return Authentication object here
                return (object) [
                    'actor' => $actor,
                    'role'  => $role,
                    'resource'   => $resourceIdentifier,
                    'operation'    => $operation,
                    'allow' => $opControl[1],
                    'mask'  => $opControl[0],
                ];
            }
        }
        return null;
    }
}



