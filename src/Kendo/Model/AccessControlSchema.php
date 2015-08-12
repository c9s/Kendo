<?php
namespace Kendo\Model;
use LazyRecord\Schema\DeclareSchema;

class AccessControlSchema extends DeclareSchema
{
    public function schema()
    {
        $this->column('rule_id')->unsigned()->integer();

        // if the rule has a specific actor record
        $this->column('actor_record_id')
            ->integer();

        $this->column('resource_record_id')
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
