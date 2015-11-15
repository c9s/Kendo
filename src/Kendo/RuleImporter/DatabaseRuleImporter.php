<?php
namespace Kendo\RuleImporter;
use Kendo\RuleLoader\SchemaRuleLoader;
use Kendo\SecurityPolicy\SecurityPolicySchema;
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
use Kendo\Definition\ResourceDefinition;

/**
 * DatabaseRuleImporter imports AccessRule from schema to database
 */
class DatabaseRuleImporter
{
    /**
     * Result
     */
    private function assertResultSuccess($ret)
    {
        if ($ret->error) {
            throw new \Exception($ret->message);
        }
    }

    protected function importActorRecords(array $definitions)
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

    protected function importRoleRecords(array $definitions)
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

    protected function importResource(ResourceDefinition $definition, array & $resourceRecords)
    {
        if ($definition->group) {
            $parentResourceRecord = $this->importResource($definition->group, $resourceRecords);
        }
        $resourceRecord = new ResourceRecord;
        $ret = $resourceRecord->createOrUpdate([
            'identifier' => $definition->identifier,
            'label'      => $definition->label,
            'group_id'   => $definition->group ? $definition->group->id : null,
        ], 'identifier');
        $this->assertResultSuccess($ret);
        $definition->setRecord($resourceRecord);
        $definition->id = $resourceRecord->id;
        return $resourceRecords[$definition->identifier] = $resourceRecord;
    }

    protected function importResourceRecords(array $definitions)
    {
        $resourceRecords = [];
        foreach ($definitions as $definition) {
            $this->importResource($definition, $resourceRecords);
        }
        return $resourceRecords;
    }

    protected function importOperationRecords(array $definitions)
    {
        $operationRecords = [];
        foreach ($definitions as $operationDefinition) {
            $operationRecord = new OperationRecord;
            $ret = $operationRecord->createOrUpdate([
                'description' => $operationDefinition->description,
                'label'       => $operationDefinition->label,
                'identifier'       => $operationDefinition->identifier,
            ], ['identifier']);
            $this->assertResultSuccess($ret);

            if (isset($operationRecords[$operationRecord->identifier])) {
                throw new DefinitionRedefinedException("Opeartion redefined.");
            }
            $operationRecords[$operationRecord->identifier] = $operationRecord;
        }
        return $operationRecords;
    }

    public function import(RuleLoader $loader)
    {
        // TODO: clean up removed actor records...
        $actorDefinitions    = $loader->getActorDefinitions();
        $actorRecords = $this->importActorRecords($actorDefinitions);

        $actorDefinitions    = $loader->getActorDefinitions();
        $roleRecords = $this->importRoleRecords($actorDefinitions);

        $resourceGroupDefinitions = $loader->getResourceGroupDefinitions();
        $resourceGroupRecords = $this->importResourceRecords($resourceGroupDefinitions);

        $resourceDefinitions = $loader->getResourceDefinitions();
        $resourceRecords = $this->importResourceRecords($resourceDefinitions);

        $operationDefinitions = $loader->getOperationDefinitions();
        $operationRecords = $this->importOperationRecords($operationDefinitions);

        // get all access rules from loader
        $accessRules = $loader->getAccessRules();
        foreach ($accessRules as $actorIdentifier =>  $actorRules) {
            foreach ($actorRules as $resourceIdentifier => $rules) {
                foreach ($rules as $rule) {
                    $opIdentifier = $rule['op'];
                    $allow = $rule['allow'];
                    $roleIdentifier = $rule['role'];

                    $ruleRecord = new AccessRuleRecord;
                    $ret = $ruleRecord->createOrUpdate([
                        'actor'    => $actorIdentifier,
                        'actor_id' => $actorRecords[$actorIdentifier]->id,
                        'actor_record_id' => $rule['actor_record_id'],
                        'role'     => $roleIdentifier,
                        'role_id' => $roleIdentifier ? $roleRecords[$roleIdentifier]->id : null,

                        'operation'    => $opIdentifier,
                        'operation_id' => $operationRecords[$opIdentifier]->id,

                        'resource' => $resourceIdentifier ,
                        'resource_id' => $resourceRecords[ $resourceIdentifier ]->id,
                        'resource_record_id' => $rule['resource_record_id'],

                        'allow'   => $allow,

                    ], [ 'actor', 'role', 'resource', 'operation', 'actor_record_id', 'resource_record_id' ]);

                    $this->assertResultSuccess($ret);
                }
            }
        }
    }
}



