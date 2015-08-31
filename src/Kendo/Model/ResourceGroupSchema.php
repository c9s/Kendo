<?php
namespace Kendo\Model;
use LazyRecord\Schema\DeclareSchema;

class ResourceGroupSchema extends DeclareSchema
{
    public function schema() 
    {
        $this->table('access_resources_group');

        $this->column('identifier')
            ->varchar(64)
            ->unique()
            ->required();

        $this->column('label')
            ->varchar(128);

        $this->column('description')
            ->text();
    }


}


