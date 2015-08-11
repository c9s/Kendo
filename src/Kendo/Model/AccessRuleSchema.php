<?php
namespace Kendo\Model;
use LazyRecord\Schema\SchemaDeclare;

class AccessRuleSchema extends SchemaDeclare
{
    public function schema() 
    {
        // actor identifier
        $this->column('actor_id')
            ->refer('Kendo\\Model\\ActorSchema')
            ->integer();

        $this->column('role_id')
            ->refer('Kendo\\Model\\RoleSchema')
            ->integer()
            ->null()
            ;

        $this->column('actor_record_id')
            ->integer();

        $this->column('resource_id')
            ->integer()
            ->required();

        $this->column('resource_record_id')
            ->integer()
            ->null()
            ;

        $this->column('operation')
            ->varchar(64)
            ->required();

        $this->column('operation_label')
            ->varchar(128);

        $this->belongsTo('resource','Kendo\\Model\\ResourceSchema','id','resource_id');
        $this->belongsTo('actor','Kendo\\Model\\ActorSchema','id','actor_id');
    }
}


