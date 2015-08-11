<?php
namespace Kendo\RuleExporter;
use Kendo\RuleLoader\DefinitionRuleLoader;
use Kendo\RuleLoader\RuleLoader;
use Kendo\Model\AccessRule as AccessRuleRecord;
use Kendo\Model\AccessRuleCollection;
use Kendo\Model\Actor as ActorRecord;
use Kendo\Model\Role as RoleRecord;
use Kendo\Model\Operation as OperationRecord;
use Kendo\Model\Resource as ResourceRecord;

class DatabaseRuleExporter
{
    protected $loader;

    public function __construct(RuleLoader $loader)
    {
        $this->loader = $loader;
    }

    private function assertResultSuccess($ret)
    {
        if ($ret->error) {
            throw new \Exception($ret->message);
        }
    }

    public function exportActorRecords($definitions)
    {
        $actorRecords = [];
        foreach ($definitions as $actorDefinition) {
            $actorRecord = new ActorRecord;
            $ret = $actorRecord->createOrUpdate([
                'identifier' => $actorDefinition->identifier,
                'label' => $actorDefinition->label,
            ], 'identifier');
            $this->assertResultSuccess($ret);
            $actorRecords[$actorRecord->identifier] = $actorRecord;
        }
        return $actorRecords;
    }

    public function exportRoleRecords($definitions)
    {
        $roleRecords = [];
        foreach ($definitions as $actorDefinition) {
            $actorRecord = new ActorRecord;
            $ret = $actorRecord->load([ 'identifier' => $actorDefinition->identifier ]);
            $this->assertResultSuccess($ret);

            if ($roles = $actorDefinition->getRoles()) {
                foreach ($roles as $role) {
                    $roleRecord = new ActorRecord;
                    $ret = $roleRecord->createOrUpdate([
                        'identifier' => $roleDefinition->identifier,
                        'label' => $roleDefinition->label,
                        'actor_id' => $actorRecord->id,
                    ], 'identifier');
                    $this->assertResultSuccess($ret);
                    $roleRecords[$roleRecord->identifier] = $roleRecord;
                }
            }
        }
        return $roleRecords;
    }

    public function exportResourceRecords($definitions)
    {
        $resourceRecords = [];
        foreach ($definitions as $resourceDefinition) {
            $resourceRecord = new ResourceRecord;
            $ret = $resourceRecord->createOrUpdate([
                'identifier' => $resourceDefinition->identifier,
                'label' => $resourceDefinition->label,
            ], 'identifier');
            $this->assertResultSuccess($ret);
            $resourceRecords[$resourceRecord->identifier] = $resourceRecord;
        }
        return $resourceRecords;
    }

    public function exportOperationRecords($definitions)
    {
        $operationRecords = [];
        foreach ($definitions as $operationDefinition) {
            $operationRecord = new OperationRecord;
            $ret = $operationRecord->createOrUpdate([
                'bitmask' => $operationDefinition->bitmask,
                'label' => $operationDefinition->label,
            ], 'identifier');
            $this->assertResultSuccess($ret);
            $operationRecords[$operationRecord->bitmask] = $operationRecord;
        }
        return $operationRecords;
    }

    public function export()
    {
        // TODO: clean up removed actor records...

        $actorDefinitions    = $this->loader->getActorDefinitions();
        $actorRecords = $this->exportActorRecords($actorDefinitions);

        $resourceDefinitions = $this->loader->getResourceDefinitions();
        $resourceRecords = $this->exportResourceRecords($resourceDefinitions);

        $operationDefinitions = $this->loader->getOperationDefinitions();
        $operationRecords = $this->exportOperationRecords($operationDefinitions);

        foreach ($actorDefinitions as $actorDefinition) {
            if ($roles = $actorDefinition->getRoles()) {
                foreach ($roles as $roleIdentifier) {
                    $accessRules = $this->loader->getAccessRulesByActorIdentifier($actorDefinition->identifier, $roleIdentifier);
                    foreach ($accessRules as $resourceIdentifier => $permissions) {
                        foreach ($permissions as list($opBit, $allow)) {
                            $op = $operationDefinitions[$opBit];

                            $ruleRecord = new AccessRuleRecord;
                            $ret = $ruleRecord->createOrUpdate([
                                'actor_id'          => $actorRecords[ $actorDefinition->identifier ]->id,
                                'resource_id'       => $resourceRecords[ $resourceIdentifier ]->id,
                                'operation_bitmask' => $opBit,
                                'operation_id'      => $operationRecords[$opBit]->id,
                                'allow'             => $allow,
                            ], [ 'actor_id', 'resource_id', 'operation_id' ]);
                            $this->assertResultSuccess($ret);

                            // echo "Actor '", $actorDefinition->identifier, "' with role '", $roleIdentifier, "' " , $op->label, " " , $resourceIdentifier, ($allow ? " is" : " is not"), " allowed.\n";
                            // var_dump( $op );
                            // var_dump( $allow );
                        }
                    }
                }
            } else if ($accessRules = $this->loader->getAccessRulesByActorIdentifier($actorDefinition->identifier)) {
                foreach ($accessRules as $resource => $permissions) {
                    foreach ($permissions as list($opBit, $allow)) {

                    }
                }
            } else {
                // this actor doesn't have any rule to import
            }
        }
    }
}



