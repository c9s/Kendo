<?php
namespace Kendo\Model;
use LazyRecord\Schema\DeclareSchema;

class AccessControlSchema extends DeclareSchema
{
    public function schema()
    {
        $this->column('rule_id')->unsigned()->integer();

        $this->column('allow')
            ->boolean()
            ->notNull()
            ->required();

        $this->belongsTo('rule','Kendo\\Model\\AccessRuleSchema','id','rule_id');
    }


}
