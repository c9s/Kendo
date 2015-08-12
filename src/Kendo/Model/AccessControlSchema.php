<?php
namespace Kendo\Model;
use LazyRecord\Schema\DeclareSchema;

class AccessControlSchema extends DeclareSchema
{
    public function schema()
    {
        $this->column('rule_id')->unsigned()->integer();

        // actor identifier
        $this->column('actor_id')
            ->refer('Kendo\\Model\\ActorSchema')
            ->integer();

        $this->column('actor_record_id')
            ->integer();

        $this->column('role_id')
            ->refer('Kendo\\Model\\RoleSchema')
            ->integer()
            ->null()
            ;

        $this->column('allow')
            ->boolean()
            ->notNull()
            ->required();

        $this->belongsTo('rule','Kendo\\Model\\AccessRuleSchema','id','rule_id');
    }


}
