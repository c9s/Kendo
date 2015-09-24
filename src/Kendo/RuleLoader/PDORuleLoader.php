<?php
namespace Kendo\RuleLoader;
use Kendo\RuleLoader\RuleLoader;
use Kendo\Definition\RuleDefinition;
use Kendo\Definition\ActorDefinition;
use Kendo\Definition\RoleDefinition;
use Kendo\Definition\ResourceDefinition;
use Kendo\Definition\OperationDefinition;
use SQLBuilder\Universal\Syntax\Conditions;
use SQLBuilder\Bind;
use SQLBuilder\ArgumentArray;
use SQLBuilder\Driver\PDODriverFactory;
use PDO;
use PDOStatement;
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

    protected $stm;

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

    public function prepareAccessRuleStatement(ArgumentArray $queryArgs, $actorIdentifier)
    {
        $queryDriver =  PDODriverFactory::create($this->conn);
        $conditions = new Conditions;
        $conditions->equal('ar.actor', new Bind('actor', $actorIdentifier));
        // $conditions->equal('ar.actor_id', new Bind('actor_id', $requiredActor->id));

        // rules with record_id should be compared before other rules that are without record id.

        $conditionSQL = $conditions->toSql($queryDriver, $queryArgs);
        $sql = '
            SELECT 
                ar.id,
                ar.actor,
                ar.actor_id,
                ar.actor_record_id,
                ar.role,
                ar.role_id,
                ar.resource,
                ar.resource_id,
                ar.resource_record_id,
                ar.operation,
                ar.operation_id,
                ar.allow,
                res.identifier as resource_identifier,
                res.label as resource_label,
                ops.identifier as op_identifier
            FROM access_rules ar 
            LEFT JOIN access_resources res ON (ar.resource_id = res.id)
            LEFT JOIN access_roles roles ON (ar.role_id = roles.id)
            LEFT JOIN access_operations ops ON (ar.operation_id = ops.id)'
            . ' WHERE ' . $conditionSQL
            . ' ORDER BY ar.actor, ar.role, ar.actor_record_id, ar.resource, ar.resource_record_id DESC';
        return $this->conn->prepare($sql);
    }

    /**
     * getActorAccessRules returns access rules that belongs to one actor.
     *
     * If $roleIdentifier is given, it returns access rules with that $roleIdentifier.
     * If $roleIdentifier is not given, it returns access rules without role.
     *
     * @param string $actorIdentifier
     *
     * @return array
     */
    public function getActorAccessRules($actorIdentifier)
    {
        if (isset($this->accessRules[$actorIdentifier])) {
            return $this->accessRules[$actorIdentifier];
        }

        $requiredActor = $this->definedActors[$actorIdentifier];

        $queryArgs = new ArgumentArray([
            ':actor' => $actorIdentifier,
        ]);

        if (!$this->stm) {
            $this->stm = $this->prepareAccessRuleStatement($queryArgs, $actorIdentifier);
        }

        $this->stm->execute($queryArgs->toArray());

        $this->fetchActorAccessRules($this->stm);
        return $this->accessRules[$actorIdentifier];
    }

    protected function fetchActorAccessRules(PDOStatement $stm)
    {
        while ($row = $stm->fetch()) {
            $this->accessRules[ $row['actor'] ][ $row['resource'] ][] = [
                'op'                 => $row['operation'],
                'role'               => $row['role'],
                'resource'           => $row['resource'],
                'resource_record_id' => $row['resource_record_id'],
                'actor_record_id'    => $row['actor_record_id'],
                'allow'              => boolval($row['allow']),
            ];
        }
    }




    public function getAccessRules()
    {
        return $this->accessRules;
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

