<?php
namespace Kendo\RuleEditor;
use Kendo\RuleLoader\RuleLoader;
use Kendo\SecurityPolicy\SecurityPolicySchema;
use Exception;
use LogicException;
use ArrayIterator;
use IteratorAggregate;
use Kendo\Model\AccessRuleCollection;
use Kendo\Model\AccessRule;
use Kendo\Definition\ResourceDefinition;

class ActorRuleEditor implements IteratorAggregate
{
    protected $permissionSettings = array();

    protected $loader;

    protected $policy;

    public function __construct(SecurityPolicySchema $policy, RuleLoader $loader)
    {
        $this->policy = $policy;
        $this->loader = $loader;
    }


    public function getLoader()
    {
        return $this->loader;
    }

    /**
     * Check if we've defined the resource.
     *
     * @param string $resource
     */
    public function hasResource($resource)
    {
        return isset($this->permissionSettings[$resource]);
    }


    /**
     * @param string $resource
     * @param string $operation
     *
     * @return null|boolean
     */
    public function setAllow($resource, $operation)
    {
        if (!$this->hasResource($resource)) {
            throw new Exception("Undefined resource $resource.");
        }
        $this->permissionSettings[ $resource ][ $operation ] = true;
    }


    /**
     * Set disallow permission on specific resource and operation
     *
     * @param string $resource
     * @param string $operation
     *
     * @return null|boolean
     */
    public function setDisallow($resource, $operation)
    {
        if (!$this->hasResource($resource)) {
            throw new Exception("Undefined resource $resource.");
        }
        $this->permissionSettings[ $resource ][ $operation ] = false;
    }


    /**
     * Get permission value for specific resource and operation
     *
     * @param string $resource
     * @param string $operation
     *
     * @return null|boolean
     */
    public function getPermission($resource, $operation)
    {
        if (!$this->hasResource($resource)) {
            throw new Exception("Undefined resource $resource.");
        }
        if (!isset($this->permissionSettings[ $resource ][ $operation ])) {
            return null;
        }
        return $this->permissionSettings[$resource][$operation];
    }

    public function getPermissionSettings()
    {
        return $this->permissionSettings;
    }


    public function setPermissionSettings(array $settings)
    {
        $this->permissionSettings = $settings;
    }


    /**
     * Save permissions for specific actor.
     *
     * @param SecurityPolicySchema $policy
     * @param string $actor
     * @param integer $actorRecordId
     */
    public function savePermissionSettings($actor, $actorRecordId)
    {
        $actorDef = $this->loader->findActorByIdentifier($actor);
        foreach ($this->permissionSettings as $resource => $ops) {
            $resDef = $this->loader->findResourceByIdentifier($resource);
            foreach ($ops as $op => $allow) {
                $opDef = $this->loader->findOperationByIdentifier($op);
                if (!$opDef) {
                    throw new LogicException("Operation '$op' isn't defined.");
                }

                $rule = new AccessRule;
                $rule->createOrUpdate([
                    'actor_id'        => $actorDef->id,
                    'actor_record_id' => $actorRecordId,
                    'actor'           => $actorDef->identifier,
                    'resource_id'     => $resDef->id,
                    'resource'        => $resDef->identifier,
                    'operation'       => $opDef->identifier,
                    'operation_id'    => $opDef->id,
                    'allow' => $allow,
                ],['actor', 'actor_record_id', 'resource', 'operation']);
            }
        }
    }


    private function loadResourcePermissions($actor, $actorRecordId, ResourceDefinition $resDef)
    {
        $rules = $this->loader->queryActorAccessRules($actor, $actorRecordId, $resDef->identifier);
        foreach ($rules as $rule) {
            $this->permissionSettings[ $rule['resource'] ][ $rule['operation'] ] = $rule['allow'];
        }
    }


    public function loadPermissionSettings($actor, $actorRecordId = 0)
    {
        // For each resource, load their operations and corresponding setting.
        $this->permissionSettings = [
            /* resource => [ op => permission, ... ] */
        ];
        $resDefs = $this->policy->getResourceDefinitions();
        foreach ($resDefs as $resDef) {

            // Get available operations
            if ($resourceOps = $resDef->operations) {
                foreach ($resourceOps as $opIdentifier => $opDef) {
                    // echo get_class($opDef) , ':' , $opDef->identifier, PHP_EOL;
                    $this->permissionSettings[$resDef->identifier][$opDef->identifier] = false;
                }
            } else {
                $ops = $this->policy->getOperationDefinitions();
                foreach ($ops as $opIdentifier => $opDef) {
                    $this->permissionSettings[$resDef->identifier][$opDef->identifier] = false;
                }
            }

            // XXX: reconcile this method into the rule loader interface
            if ($actorRecordId) {
                $this->loadResourcePermissions($actor, $actorRecordId, $resDef->identifier);
            }
        }
        return $this->permissionSettings;
    }


    /**
     * Implements IteratorAggregate interface
     */
    public function getIterator()
    {
        return new ArrayIterator($this->permissionSettings);
    }


    public function getPolicy()
    {
        return $this->policy;
    }
}





