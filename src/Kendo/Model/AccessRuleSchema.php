<?php
namespace Kendo\Model;
use LazyRecord\Schema\SchemaDeclare;

class AccessRuleSchema extends SchemaDeclare
{
    public function schema() 
    {
        $this->column('rules_class')
            ->varchar(64);

        $this->column('resource')
            ->varchar(64)
            ->required();

        $this->column('operation')
            ->varchar(64)
            ->required();

        $this->column('operation_label')
            ->varchar(128);

        $this->column('description')
            ->text();

        $this->belongsTo('control','Kendo\\Model\\AccessControlSchema','rule_id','id');

        $this->belongsTo('resource','Kendo\\Model\\AccessResourceSchema','name','resource');
    }
}


