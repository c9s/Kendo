<?php
namespace Kendo\Model;
use Kendo\Model\Actor;
use Kendo\Model\ActorCollection;
use Kendo\Model\AccessRuleCollection;

class AccessRuleRepository
{

    public static function queryAccessRuleCollectionByResource(Resource $resource, $recordId = null)
    {
        $accessRules = new AccessRuleCollection;
        if ($recordId) {
            $accessRules->loadQuery('SELECT * FROM access_rules 
                    WHERE resource_id        = :resource_id 
                      AND resource_record_id = :resource_record_id', [ 
                ':resource_id' => $resource->id,
                ':resource_record_id' => $recordId,
            ]);
        } else {
            $accessRules->loadQuery('SELECT * FROM access_rules WHERE resource_id = :resource_id', [ 
                ':resource_id' => $resource->id,
            ]);
        }
        return $accessRules;

    }

    public static function queryAccessRuleCollectionByActor(Actor $actor, $recordId = null)
    {
        $accessRules = new AccessRuleCollection;
        if ($recordId) {
            $accessRules->loadQuery('SELECT * FROM access_rules 
                    WHERE actor_id = :actor_id 
                      AND actor_record_id = :actor_record_id', [ 
                ':actor_id' => $actor->id,
                ':actor_record_id' => $recordId,
            ]);
        } else {
            $accessRules->loadQuery('SELECT * FROM access_rules WHERE actor_id = :actor_id', [ 
                ':actor_id' => $actor->id,
            ]);
        }
        return $accessRules;
    }
}




