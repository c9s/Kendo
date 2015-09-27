<?php
namespace Kendo\Model;
use LazyRecord\Schema\DeclareSchema;

class ResourceSchema extends DeclareSchema
{
    public function schema() 
    {
        $this->table('access_resources');

        $this->column('identifier')
            ->varchar(64)
            ->unique()
            ->required();

        $this->column('label')
            ->varchar(128);

        $this->column('description')
            ->text();


        $this->column('group_id')
            ->integer()
            ->default(NULL)
            ;

        $this->belongsTo('group', 'Kendo\\Model\\ResourceSchema', 'id', 'group_id');
    }


}


