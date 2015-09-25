<?php
namespace Kendo\RuleLoader;
use Kendo\RuleLoader\RuleLoader;
use Kendo\Definition\RuleDefinition;
use Kendo\Definition\ActorDefinition;
use Kendo\Definition\RoleDefinition;
use Kendo\Definition\ResourceDefinition;
use Kendo\Definition\OperationDefinition;
use SQLBuilder\Universal\Syntax\Conditions;
use SQLBuilder\Universal\Query\SelectQuery;
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


    /**
     * @var PDOStatement $stm prepared statement object for general query.
     */
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
        $this->loadActorDefinitions();
        $this->loadResourceDefinitions();
        $this->loadResourceGroupDefinitions();
        $this->loadOperationDefinitions();
        $this->loadRoleDefinitions();
    }

    public function loadRoleDefinitions()
    {
        $stm = $this->conn->prepare('select * from access_roles');
        $stm->execute();
        while ($role = $stm->fetchObject('Kendo\\Definition\\RoleDefinition', [null, null])) {
            $this->definedRoles[$role->identifier] = $role;
        }
    }

    public function loadOperationDefinitions()
    {
        $stm = $this->conn->prepare('select * from access_operations');
        $stm->execute();
        while ($op = $stm->fetchObject('Kendo\\Definition\\OperationDefinition', [null, null])) {
            $this->definedOperations[$op->identifier] = $op;
        }
    }

    public function loadResourceGroupDefinitions()
    {
        $stm = $this->conn->prepare('select * from access_resource_groups');
        $stm->execute();
        while ($res = $stm->fetchObject('Kendo\\Definition\\ResourceGroupDefinition',[ null, null ])) {
            $this->definedResourceGroups[$res->identifier] = $res;
        }
    }

    public function loadResourceDefinitions()
    {
        $stm = $this->conn->prepare('select * from access_resources');
        $stm->execute();
        while ($res = $stm->fetchObject('Kendo\\Definition\\ResourceDefinition', [null, null])) {
            $this->definedResources[$res->identifier] = $res;
        }
    }

    public function loadActorDefinitions()
    {
        $stm = $this->conn->prepare('select * from access_actors');
        $stm->execute();
        while ($actor = $stm->fetchObject('Kendo\\Definition\\ActorDefinition')) {
            $this->definedActors[$actor->identifier] = $actor;
        }
    }



    /**
     * Query access rules from database.
     *
     * In order to improve the performance, this method doesn't use AccessRuleCollection.
     *
     * @param ArgumentArray $args
     * @param Conditions $conditions
     */
    public function prepareAccessRuleStatement(ArgumentArray $args, Conditions $conditions)
    {
        $queryDriver =  PDODriverFactory::create($this->conn);
        $conditionSQL = $conditions->toSql($queryDriver, $args);
        $sql = '
            SELECT 
                id,
                actor,
                actor_id,
                actor_record_id,
                role,
                role_id,
                resource,
                resource_id,
                resource_record_id,
                operation,
                operation_id,
                allow
            FROM access_rules '
            . ' WHERE ' . $conditionSQL
            . ' ORDER BY actor, actor_record_id, role, resource, resource_record_id DESC';
        // echo $sql , PHP_EOL;
        return $this->conn->prepare($sql);
    }

    public function queryActorAccessRules($actor, $actorRecordId = null, $resource = null)
    {
        $args = new ArgumentArray;

        $conditions = new Conditions;
        $conditions->equal('actor', new Bind('actor', $actor));

        // remove resource specific rules
        $conditions->is('resource_record_id', null);

        if ($actorRecordId) {
            $conditions->equal('actor_record_id', new Bind('actor_record_id', $actorRecordId));
        }

        if ($resource) {
            $conditions->equal('resource', new Bind('resource',$resource));
        }

        $stm = $this->prepareAccessRuleStatement($args, $conditions);
        $stm->execute($args->toArray());

        $rules = [];
        while ($row = $stm->fetch()) {
            $row['allow'] = boolval($row['allow']);

            if ($row['resource_record_id']) {
                $row['resource_record_id'] = intval($row['resource_record_id']);
            }
            if ($row['actor_record_id']) {
                $row['actor_record_id'] = intval($row['actor_record_id']);
            }
            $rules[] = $row;
        }
        return $rules;
    }



    /**
     * getActorAccessRules returns access rules that belongs to one actor.
     *
     * If $roleIdentifier is given, it returns access rules with that $roleIdentifier.
     * If $roleIdentifier is not given, it returns access rules without role.
     *
     * This method is usually used when controller needs to verify the permissions.
     *
     * @param string $actor
     *
     * @return array
     */
    public function getActorAccessRules($actor)
    {
        if (isset($this->accessRules[$actor])) {
            return $this->accessRules[$actor];
        }


        $args = new ArgumentArray([
            ':actor' => $actor,
        ]);

        if (!$this->stm) {
            $conditions = new Conditions;
            $conditions->equal('actor', new Bind('actor', $actor));

            // $requiredActor = $this->definedActors[$actor];
            // $conditions->equal('actor_id', new Bind('actor_id', $requiredActor->id));
            $this->stm = $this->prepareAccessRuleStatement($args, $conditions);
        }

        $this->stm->execute($args->toArray());

        $this->fetchActorAccessRules($this->stm);
        return $this->accessRules[$actor];
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



    public function getConnection()
    {
        return $this->conn;
    }


}

