<?php
namespace Kendo\Model;
use LazyRecord\Schema\SchemaDeclare;

class AccessResourceSchema extends SchemaDeclare
{
    public function schema() {
        $this->column('rules_class')
            ->varchar(64);

        $this->column('name')
            ->varchar(64)
            ->unique()
            ->required();

        $this->column('label')
            ->varchar(128);

        $this->column('description')
            ->text();

        $this->many('access_rules','Kendo\\Model\\AccessRuleSchema','resource','name');
    }


}


