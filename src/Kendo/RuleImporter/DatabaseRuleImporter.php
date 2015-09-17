<?php
namespace Kendo\RuleImporter;
use Kendo\RuleLoader\SchemaRuleLoader;
use Kendo\RuleLoader\RuleLoader;
use Kendo\Model\AccessRule as AccessRuleRecord;
use Kendo\Model\AccessControl as AccessControlRecord;
use Kendo\Model\AccessRuleCollection;
use Kendo\Model\Actor as ActorRecord;
use Kendo\Model\Role as RoleRecord;
use Kendo\Model\Operation as OperationRecord;
use Kendo\Model\Resource as ResourceRecord;
use Kendo\Model\ResourceGroup as ResourceGroupRecord;
use Kendo\Exception\DefinitionRedefinedException;

/**
 * DatabaseRuleImporter exports AccessRule from schema to database
 */
class DatabaseRuleImporter
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

    protected function exportActorRecords(array $definitions)
    {
        $actorRecords = [];
        foreach ($definitions as $actorDefinition) {
            $actorRecord = new ActorRecord;
            $ret = $actorRecord->createOrUpdate([
                'identifier' => $actorDefinition->identifier,
                'label' => $actorDefinition->label,
            ], ['identifier']);
            $this->assertResultSuccess($ret);
            $actorRecords[$actorRecord->identifier] = $actorRecord;
        }
        return $actorRecords;
    }

    protected function exportRoleRecords(array $definitions)
    {
        $roleRecords = [];
        foreach ($definitions as $actorDefinition) {
            $actorRecord = new ActorRecord;
            $ret = $actorRecord->load([ 'identifier' => $actorDefinition->identifier ]);
            $this->assertResultSuccess($ret);

            if ($roleDefinitions = $actorDefinition->getRoleDefinitions()) {
                foreach ($roleDefinitions as $roleDefinition) {
                    $roleRecord = new RoleRecord;
                    $ret = $roleRecord->createOrUpdate([
                        'identifier' => $roleDefinition->identifier,
                        'label'      => $roleDefinition->label,
                        'actor_id'   => $actorRecord->id,
                    ], ['identifier']);
                    $this->assertResultSuccess($ret);
                    $roleRecords[$roleRecord->identifier] = $roleRecord;
                }
            }
        }
        return $roleRecords;
    }

    protected function exportResourceGroupRecords(array $definitions)
    {
        $records = array();
        foreach ($definitions as $definition) {
            $record = new ResourceGroupRecord;
            $ret = $record->createOrUpdate([
                'identifier' => $definition->identifier,
                'label' => $definition->label,
            ], 'identifier');
            $this->assertResultSuccess($ret);
            $records[$record->identifier] = $record;
            $definition->setRecord($record);
        }
        return $records;
    }

    protected function exportResourceRecords(array $definitions)
    {
        $resourceRecords = [];
        foreach ($definitions as $definition) {
            $resourceRecord = new ResourceRecord;

            $groupId = null;

            if ($definition->group) {
                if ($groupRecord = $definition->group->getRecord()) {
                    $groupId = $groupRecord->id;
                }
            }

            $ret = $resourceRecord->createOrUpdate([
                'identifier' => $definition->identifier,
                'group_id'   => $groupId,
                'label'      => $definition->label,
            ], 'identifier');
            $this->assertResultSuccess($ret);
            $resourceRecords[$resourceRecord->identifier] = $resourceRecord;

            $definition->setRecord($resourceRecord);
        }
        return $resourceRecords;
    }

    protected function exportOperationRecords(array $definitions)
    {
        $operationRecords = [];
        foreach ($definitions as $operationDefinition) {
            $operationRecord = new OperationRecord;
            $ret = $operationRecord->createOrUpdate([
                'bitmask'     => $operationDefinition->bitmask,
                'description' => $operationDefinition->description,
                'label'       => $operationDefinition->label,
            ], ['bitmask']);
            $this->assertResultSuccess($ret);

            if (isset($operationRecords[$operationRecord->bitmask])) {
                throw new DefinitionRedefinedException("Opeartion redefined.");
            }
            $operationRecords[$operationRecord->bitmask] = $operationRecord;
        }
        return $operationRecords;
    }

    public function export()
    {
        // TODO: clean up removed actor records...

        $actorDefinitions    = $this->loader->getActorDefinitions();
        $actorRecords = $this->exportActorRecords($actorDefinitions);

        $actorDefinitions    = $this->loader->getActorDefinitions();
        $roleRecords = $this->exportRoleRecords($actorDefinitions);


        $resourceGroupDefinitions = $this->loader->getResourceGroupDefinitions();
        $resourceGroupRecords = $this->exportResourceGroupRecords($resourceGroupDefinitions);

        $resourceDefinitions = $this->loader->getResourceDefinitions();
        $resourceRecords = $this->exportResourceRecords($resourceDefinitions);

        $operationDefinitions = $this->loader->getOperationDefinitions();
        $operationRecords = $this->exportOperationRecords($operationDefinitions);

        foreach ($actorDefinitions as $actorDefinition) {
            $roleDefinitions = $actorDefinition->getRoleDefinitions();

            if (!empty($roleDefinitions)) {

                foreach ($roleDefinitions as $roleDefinition) {

                    $roleIdentifier = $roleDefinition->identifier;
                    $accessRules = $this->loader->getResourceRulesByActorIdentifier($actorDefinition->identifier, $roleIdentifier);

                    foreach ($accessRules as $resourceIdentifier => $permissions) {

                        foreach ($permissions as list($opBit, $opName, $allow)) {
                            $op = $operationDefinitions[$opBit];



                            $ruleRecord = new AccessRuleRecord;

                            $ret = $ruleRecord->createOrUpdate([
                                'actor_id'          => $actorRecords[ $actorDefinition->identifier ]->id,
                                // 'actor_record_id' => ...

                                'role_id' => $roleRecords[ $roleIdentifier ]->id,

                                'resource_id' => $resourceRecords[ $resourceIdentifier ]->id,

                                // 'resource_record_id' => ...
                                'operation_bitmask' => $opBit,
                                'operation_id'      => $operationRecords[$opBit]->id,

                                'allow'   => $allow,


                            ], [ 'actor_id', 'role_id', 'resource_id', 'operation_id' ]);

                            /*
                            printf("Created rule %d: %s Actor %s %s %s\n",
                                $ruleRecord->id,
                                $actorDefinition->identifier
                                , $allow ? 'can' : 'can not',
                                $op->label, $resourceIdentifier);
                             */

                            $this->assertResultSuccess($ret);

                            $controlRecord = new AccessControlRecord;
                            $ret = $controlRecord->createOrUpdate([
                                'rule_id' => $ruleRecord->id,
                                'allow' => $allow,

                                // TODO: support extra attributes later.
                                // 'actor_record_id' => ...
                                // 'resource_record_id' => ...
                            ],[ 'rule_id' ]);
                            $this->assertResultSuccess($ret);
                        }
                    }
                }
            }

            // Export rules without roles
            if ($accessRules = $this->loader->getResourceRulesByActorIdentifier($actorDefinition->identifier)) {
                foreach ($accessRules as $resourceIdentifier => $permissions) {
                    foreach ($permissions as list($opBit, $opName, $allow)) {

                        $op = $operationDefinitions[$opBit];

                        $ruleRecord = new AccessRuleRecord;

                        $ret = $ruleRecord->createOrUpdate([
                            'actor_id'          => $actorRecords[ $actorDefinition->identifier ]->id,

                            'resource_id'       => $resourceRecords[ $resourceIdentifier ]->id,

                            'operation_bitmask' => $opBit,
                            'operation_id'      => $operationRecords[$opBit]->id,

                            'allow'   => $allow,

                            'role_id' => null,

                        ], [ 'actor_id', 'role_id', 'resource_id', 'operation_id' ]);

                        $this->assertResultSuccess($ret);


                        $controlRecord = new AccessControlRecord;
                        $ret = $controlRecord->createOrUpdate([
                            'rule_id' => $ruleRecord->id,
                            'allow' => $allow,

                            // TODO: support extra attributes later.
                            // 'actor_record_id' => ...
                            // 'resource_record_id' => ...
                        ],[ 'rule_id' ]);
                        $this->assertResultSuccess($ret);

                    }
                }
            } else {
                // this actor doesn't have any rule to import
            }
        }
    }
}



