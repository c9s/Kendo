<?php
namespace Kendo\RuleEditor;
use Kendo\RuleLoader\RuleLoader;
use Kendo\SecurityPolicy\SecurityPolicySchema;
use Exception;
use ArrayIterator;
use IteratorAggregate;

class ActorRuleEditor implements IteratorAggregate
{
    protected $permissionSettings = array();



    public function __construct(RuleLoader $loader)
    {
        $this->loader = $loader;
    }


    public function hasResource($resource)
    {
        return isset($this->permissionSettings[$resource]);
    }


    public function setAllow($resource, $operation)
    {
        if (!$this->hasResource($resource)) {
            throw new Exception("Undefined resource $resource.");
        }
        $this->permissionSettings[ $resource ][ $operation ] = true;
    }


    public function setDisallow($resource, $operation)
    {
        if (!$this->hasResource($resource)) {
            throw new Exception("Undefined resource $resource.");
        }
        $this->permissionSettings[ $resource ][ $operation ] = false;
    }

    public function savePermissions(SecurityPolicySchema $policy, $actor, $actorRecordId)
    {

    }


    public function loadPermissions(SecurityPolicySchema $policy, $actor, $actorRecordId)
    {
        $this->loader->queryActorAccessRules($actor);

        // For each resource, load their operations and corresponding setting.
        $this->permissionSettings = [
            /* resource => [ op => permission, ... ] */
        ];
        $resDefs = $policy->getResourceDefinitions();
        foreach ($resDefs as $resDef) {

            // Get available operations
            if ($resourceOps = $resDef->operations) {
                foreach ($resourceOps as $opIdentifier) {
                    $opDef = $policy->findOperationByIdentifier($opIdentifier);
                    $this->permissionSettings[$resDef->identifier][$opDef->identifier] = false;
                }
            } else {
                $ops = $policy->getOperationDefinitions();
                foreach ($ops as $opIdentifier => $opDef) {
                    $this->permissionSettings[$resDef->identifier][$opDef->identifier] = false;
                }
            }

            // XXX: reconcile this method into the rule loader interface
            $rules = $this->loader->queryActorAccessRules($actor, $actorRecordId, $resDef->identifier);
            foreach ($rules as $rule) {
                $this->permissionSettings[ $rule['resource'] ][ $rule['operation'] ] = $rule['allow'];
            }
        }

        var_dump( $this->permissionSettings ); 
        
        return $this->permissionSettings;
    }


    /**
     * Implements IteratorAggregate interface
     */
    public function getIterator()
    {
        return new ArrayIterator($this->permissionSettings);
    }


}





