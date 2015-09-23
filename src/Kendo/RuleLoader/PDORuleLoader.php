<?php
namespace Kendo\RuleLoader;
use Kendo\RuleLoader\RuleLoader;
use PDO;
use Kendo\Definition\RuleDefinition;
use Kendo\Definition\ActorDefinition;
use Kendo\Definition\RoleDefinition;
use Kendo\Definition\ResourceDefinition;
use Kendo\Definition\OperationDefinition;
use SQLBuilder\Universal\Syntax\Conditions;
use SQLBuilder\Bind;
use SQLBuilder\ArgumentArray;
use SQLBuilder\Driver\PDODriverFactory;
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
     * getActorAccessRules returns access rules that belongs to one actor.
     *
     * If $roleIdentifier is given, it returns access rules with that $roleIdentifier.
     * If $roleIdentifier is not given, it returns access rules without role.
     *
     * @param string $actorIdentifier
     * @param string $roleIdentifier
     *
     * @return array
     */
    public function getActorAccessRules($actorIdentifier, $roleIdentifier = 0)
    {
        $requiredActor = $this->definedActors[$actorIdentifier];

        $requiredRole = null;
        if ($roleIdentifier) {
            if (!isset($this->definedRoles[$roleIdentifier])) {
                throw new Exception("Role '$roleIdentifier' not found, undefined role identifier '$roleIdentifier' or inexisted role record.");
            }
            $requiredRole = $this->definedRoles[$roleIdentifier];
        }


        $conditions = new Conditions;
        $queryDriver =  PDODriverFactory::create($this->conn);
        $conditions->equal('ar.actor_id', new Bind('actor_id', $requiredActor->id));
        $queryArgs = new ArgumentArray;

        // rules with record_id should be compared before other rules that are without record id.
        if ($requiredRole && $requiredRole->id) {
            $conditions->equal('ar.role_id', new Bind('role_id', $requiredRole->id));
        } else {
            $conditions->is('ar.role_id', null);
        }

        $conditionSQL = $conditions->toSql($queryDriver, $queryArgs);
        $sql = '
            SELECT 
                ar.id, ar.actor_id,
                ar.actor_record_id AS actorRecordId,
                ar.role_id,
                ar.resource_id,
                ar.resource_record_id AS resourceRecordId,
                ar.operation_id,
                ar.allow,
                res.identifier as resource_identifier,
                res.label as resource_label,
                ops.identifier as op_identifier,

                ar.actor,
                ar.role,
                ar.resource

            FROM access_rules ar 
            LEFT JOIN access_resources res ON (ar.resource_id = res.id)
            LEFT JOIN access_roles roles ON (ar.role_id = roles.id)
            LEFT JOIN access_operations ops ON (ar.operation_id = ops.id)'
            . ' WHERE ' . $conditionSQL
            . ' ORDER BY ar.role, ar.actor_record_id, ar.resource_record_id DESC';
        $stm = $this->conn->prepare($sql);
        $stm->execute($queryArgs->toArray(true));

        $rules = [];
        while ($rule = $stm->fetchObject()) {
            // $rules[] = $rule;
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

