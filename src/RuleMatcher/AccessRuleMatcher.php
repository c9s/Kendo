<?php
namespace Kendo\RuleMatcher;
use Kendo\RuleLoader\RuleLoader;
use Kendo\IdentifierProvider\ActorIdentifierProvider;
use Kendo\IdentifierProvider\RoleIdentifierProvider;
use Kendo\IdentifierProvider\ResourceIdentifierProvider;
use Kendo\IdentifierProvider\GeneralIdentifierProvider;
use Kendo\IdentifierProvider\RecordIdentifierProvider;
use Kendo\RuleMatcher\RuleMatcher;
use Kendo\Context;
use LogicException;

class AccessRuleMatcher implements RuleMatcher
{
    protected $loader;

    protected $context;

    public function __construct(RuleLoader $loader, Context $context = null)
    {
        $this->loader = $loader;
        $this->context = $context;
    }

    protected function getResourceIdentifier($resource)
    {
        if (is_string($resource)) {

            return $resource;

        } else if ($resource instanceof ResourceIdentifierProvider) {

            return $resource->getResourceIdentifier();

        } else if ($resource instanceof GeneralIdentifierProvider) {

            return $resource->getIdentifier();

        }
    }

    protected function getActorIdentifier($actor, $matchedActorDefinition = null)
    {

        if ($matchedActorDefinition) {

            return $matchedActorDefinition->identifier;

        } else if ($actor instanceof ActorIdentifierProvider) {

            return $actor->getActorIdentifier();


        } else if ($actor instanceof GeneralIdentifierProvider) {

            return $actor->getIdentifier();

        }
        return null;
    }


    protected function getActorRules($actorIdentifier)
    {
        return $this->loader->getActorAccessRules($actorIdentifier);
    }

    /**
     *
     * @param string $actorIdentifier
     * @param mixed $role
     * @param integer $actorRecordId
     * @param string $operation operation identity
     */
    protected function matchRules($actorIdentifier, $role, $actorRecordId = null, $operation, $resourceIdentifier, $resourceRecordId = null)
    {
        $accessRules = $this->getActorRules($actorIdentifier);

        // TODO: return reason
        if ($accessRules === null || empty($accessRules)) {
            return RuleMatcher::ACTOR_RULE_UNDEFINED;
        }

        // resource undefined.
        // TODO: return reason
        if (!isset($accessRules[$resourceIdentifier])) {
            return RuleMatcher::RESOURCE_RULE_UNDEFINED;
        }

        // TODO: pre-compute the operation mask to improve the performance
        $rules = $accessRules[$resourceIdentifier];

        // TODO: return reason
        if (empty($rules)) {
            return RuleMatcher::RESOURCE_RULE_EMPTY;
        }

        foreach ($rules as $rule) {

            if (isset($rule['actor_record_id']) && $rule['actor_record_id'] !== $actorRecordId) {
                continue;
            }

            if (isset($rule['resource_record_id']) && $rule['resource_record_id'] !== $resourceRecordId) {
                continue;
            }

            if (isset($rule['role']) && $rule['role'] !== $role) {
                continue;
            }

            if ($rule['op'] !== $operation) {
                continue;
            }

            return (object) [
                'actor'     => $actorIdentifier,
                'role'      => $role,
                'resource'  => $resourceIdentifier,
                'operation' => $operation,
                'allow'     => $rule['allow'],
            ];
        }

        return RuleMatcher::NO_RULE_MATCHED;
    }




    /**
     *
     * @param mixed $actor
     * @param string $operation operation identifier
     * @param mixed $resource the resource object
     * @param Context $context authentication context.
     */
    public function match($actor, $operation, $resource, Context $context = null)
    {
        $actorDefinitions = $this->loader->getActorDefinitions();
        $currentContext = $context ?: $this->context;

        // for each actor definition, use instance class to find the matched actor definition.
        $matchedActorDefinition = null;
        foreach ($actorDefinitions as $actorDefinition) {
            if (!$actorDefinition->instanceClass) {
                continue;
            }
            if ($actor instanceof $actorDefinition->instanceClass) {
                $matchedActorDefinition = $actorDefinition;
            }
        }

        $actorIdentifier = $this->getActorIdentifier($actor, $matchedActorDefinition);
        $actorRecordId = null;
        if (!$actorIdentifier) {
            throw new LogicException("Can't recognise actor, identifier provider interface is not implemented.");
        }

        if ($actor instanceof RecordIdentifierProvider) {
            $actorRecordId = $actor->getRecordIdentifier();
        }

        // An actor may have different roles, if the actor doesn't have one, we will use '0' to find access_rules.
        $role = 0;
        if ($actor instanceof RoleIdentifierProvider) {
            $role = $actor->getRoleIdentifier();
        }

        $resourceIdentifier = null;
        $resourceRecordId   = null;
        $resourceIdentifier = $this->getResourceIdentifier($resource);
        if (!$resourceIdentifier) {
            throw new LogicException("Can't recognise resource, identifier provider interface is not implemented.");
        }

        if ($resource instanceof RecordIdentifierProvider) {
            $resourceRecordId = $resource->getResourceIdentifier();
        }

        return $this->matchRules($actorIdentifier, $role, $actorRecordId, $operation, $resourceIdentifier, $resourceRecordId);
    }
}



