<?php
namespace Kendo\RuleLoader;
use Kendo\RuleLoader\RuleLoader;
use Kendo\Definition\RuleDefinition;
use Kendo\Definition\ActorDefinition;
use Kendo\Definition\RoleDefinition;
use Kendo\Definition\ResourceDefinition;
use Kendo\Definition\ResourceGroupDefinition;
use Kendo\Definition\OperationDefinition;
use SQLBuilder\Universal\Syntax\Conditions;
use SQLBuilder\Universal\Query\SelectQuery;
use SQLBuilder\Bind;
use SQLBuilder\ArgumentArray;
use SQLBuilder\Driver\PDODriverFactory;
use PDO;
use PDOStatement;
use Exception;

class PDORuleLoader extends BaseRuleLoader implements RuleLoader
{

    /**
     * @var PDO
     */
    protected $conn;


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
        $this->loadActorDefinitions();
        $this->loadResourceDefinitions();
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

    public function loadResourceDefinitions()
    {
        $stm = $this->conn->prepare('SELECT * FROM access_resources');
        $stm->execute();

        // resource map by record ID
        $resourceMap = [];
        while ($row = $stm->fetch()) {
            $res = new ResourceDefinition;
            $res->id = intval($row['id']);
            $res->identifier = $row['identifier'];
            $res->label = $row['label'];
            $res->description = $row['description'];
            $res->group_id = $row['group_id'] ? intval($row['group_id']) : NULL;
            $resourceMap[$res->id] = $res;
            $this->definedResources[$res->identifier] = $res;
        }

        // rebuild group relationship
        foreach ($resourceMap as $id => $res) {
            if ($res->group_id) {
                $parentRes = $resourceMap[$res->group_id];
                $res->group($parentRes);
                $this->definedResourceGroups[ $parentRes->identifier ] = $parentRes;
            }
        }
    }

    public function loadActorDefinitions()
    {
        $stm = $this->conn->prepare('select * from access_actors');
        $stm->execute();
        while ($actor = $stm->fetchObject('Kendo\\Definition\\ActorDefinition', [null, null])) {
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


    public function getConnection()
    {
        return $this->conn;
    }


}

