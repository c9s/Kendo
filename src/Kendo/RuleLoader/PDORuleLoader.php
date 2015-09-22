<?php
namespace Kendo\RuleLoader;
use Kendo\RuleLoader\RuleLoader;
use PDO;
use Kendo\Definition\RuleDefinition;
use Kendo\Definition\ActorDefinition;
use Kendo\Definition\RoleDefinition;
use Kendo\Definition\ResourceDefinition;
use Kendo\Definition\OperationDefinition;
use Exception;

class PDORuleLoader implements RuleLoader
{

    /**
     * @var PDO
     */
    protected $conn;

    /**
     * @var ActorDefinition[identifier]
     */
    protected $definedActors = array();


    /**
     * @var RoleDefinition[identifier]
     */
    protected $definedRoles = array();

    /**
     * @var OperationDefinition[identifier]
     */
    protected $definedOperations = array();

    /**
     * @var ResourceDefinition[identifier]
     */
    protected $definedResources = array();


    /**
     * @var ResourceGroupDefinition[identifier]
     */
    protected $defineResourceGroups = array();


    public function __construct()
    {
    }


    /**
     * Load pre-required data from the database connection .
     *
     * @param PDO $conn
     */
    public function load(PDO $conn)
    {
        $this->conn = $conn;


        // pre-fetch actor records
        $stm = $conn->prepare('select * from access_actors');
        $stm->execute();
        while ($actor = $stm->fetchObject('Kendo\\Definition\\ActorDefinition')) {
            $this->definedActors[$actor->identifier] = $actor;
        }

        $stm = $conn->prepare('select * from access_resources');
        $stm->execute();
        while ($res = $stm->fetchObject('Kendo\\Definition\\ResourceDefinition')) {
            $this->definedResources[$res->identifier] = $res;
        }

        $stm = $conn->prepare('select * from access_resource_groups');
        $stm->execute();
        while ($res = $stm->fetchObject('Kendo\\Definition\\ResourceGroupDefinition')) {
            $this->definedResourceGroups[$res->identifier] = $res;
        }

        $stm = $conn->prepare('select * from access_operations');
        $stm->execute();
        while ($op = $stm->fetchObject('Kendo\\Definition\\OperationDefinition', [null, null])) {
            $this->definedOperations[$op->identifier] = $op;
        }

        $stm = $conn->prepare('select * from access_roles');
        $stm->execute();
        while ($role = $stm->fetchObject('Kendo\\Definition\\RoleDefinition', [null, null])) {
            $this->definedRoles[$role->identifier] = $role;
        }
    }


    /**
     * getResourceRulesByActorIdentifier returns access rules that belongs to one actor.
     *
     * If $roleIdentifier is given, it returns access rules with that $roleIdentifier.
     * If $roleIdentifier is not given, it returns access rules without role.
     *
     * @param string $actorIdentifier
     * @param string $roleIdentifier
     *
     * @return array
     */
    public function getResourceRulesByActorIdentifier($actorIdentifier, $roleIdentifier = 0)
    {
        $requiredActor = $this->definedActors[$actorIdentifier];

        $requiredRole = null;
        if ($roleIdentifier) {
            if (!isset($this->definedRoles[$roleIdentifier])) {
                throw new Exception("Role '$roleIdentifier' not found, undefined role identifier '$roleIdentifier' or inexisted role record.");
            }
            $requiredRole = $this->definedRoles[$roleIdentifier];
        }

        if ($requiredRole) {
            // echo "Query with required role {$requiredRole->id}\n";
            $stm = $this->conn->prepare('
                SELECT ar.id, ar.actor_id, ar.role_id, ar.resource_id, ar.operation_id, ar.allow
                        , res.identifier as resource_identifier, res.label as resource_label, ops.identifier as op_identifier
                FROM access_rules ar 
                LEFT JOIN access_resources res ON (ar.resource_id = res.id)
                LEFT JOIN access_roles roles ON (ar.role_id = roles.id)
                LEFT JOIN access_operations ops ON (ar.operation_id = ops.id)
                WHERE ar.actor_id = ? AND ar.role_id = ?
            ');
            $stm->execute([$requiredActor->id, $requiredRole->id]);
        } else {
            $stm = $this->conn->prepare('
                SELECT ar.id, ar.actor_id, ar.resource_id, ar.operation_id , ar.allow
                        , res.identifier as resource_identifier, res.label as resource_label, ops.identifier as op_identifier
                FROM access_rules ar 
                LEFT JOIN access_resources res ON (ar.resource_id = res.id)
                LEFT JOIN access_roles roles ON (ar.role_id = roles.id)
                LEFT JOIN access_operations ops ON (ar.operation_id = ops.id)
                WHERE ar.actor_id = ? AND ar.role_id IS NULL
            ');
            $stm->execute([$requiredActor->id]);
        }

        $rules = [];
        while ($rule = $stm->fetchObject()) {
            $rules[$rule->resource_identifier][$rule->op_identifier] = boolval($rule->allow);
        }
        return $rules;
    }

    public function getActorDefinitions()
    {
        return $this->definedActors;
    }

    public function getResourceDefinitions()
    {
        return $this->definedResources;
    }

    public function getOperationDefinitions()
    {
        return $this->definedOperations;
    }

    public function getResourceGroupDefinitions()
    {

        return $this->definedResourceGroupDefinitions;
    }

}

