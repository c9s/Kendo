<?php
namespace Kendo\Model;
use LazyRecord\Schema\DeclareSchema;

class ResourceSchema extends DeclareSchema
{
    public function schema() {

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


