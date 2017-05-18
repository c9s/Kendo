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
use CLIFramework\Logger;
use Exception;

/**
 * DatabaseRuleImporter imports AccessRule from schema to database
 */
class DatabaseRuleImporter
{
    protected $importedResources = [];

    public function __construct($logger = null)
    {
        $this->logger = $logger;
    }

    public function info($message)
    {
        if ($this->logger) {
            $this->logger->info($message);
        }
    }

    public function debug($message)
    {
        if ($this->logger) {
            $this->logger->debug($message);
        }
    }


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

    protected function importResource(ResourceDefinition $definition)
    {
        if (isset($this->importedResources[$definition->identifier])) {
            return $this->importedResources[$definition->identifier];
        }
        $this->info("Importing resource: " . $definition->identifier);
        $resourceRecord = new ResourceRecord;
        $ret = $resourceRecord->createOrUpdate([
            'identifier' => $definition->identifier,
            'label'      => $definition->label,
            'group_id'   => $definition->group ? $definition->group->id : null,
        ], 'identifier');
        $this->assertResultSuccess($ret);
        $definition->setRecord($resourceRecord);
        $definition->id = $resourceRecord->id;
        return $this->importedResources[$definition->identifier] = $resourceRecord;
    }

    protected function importResourceRecords(array $definitions)
    {
        foreach ($definitions as $definition) {
            if ($definition->group) {
                $this->importResource($definition->group);
            }
            $this->importResource($definition);
            if ($childResources = $definition->getChildResources()) {
                if (!empty($childResources)) {
                    foreach ($childResources as $childResource) {
                        $this->importResource($childResource);
                    }
                }
            }
        }
        return $this->importedResources;
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

                    if (!isset($operationRecords[$opIdentifier])) {
                        throw new Exception("Undefined operation '$opIdentifier'.");
                    }

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



