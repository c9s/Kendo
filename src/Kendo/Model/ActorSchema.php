<?php
namespace Kendo\Model;
use LazyRecord\Schema\DeclareSchema;

class ActorSchema extends DeclareSchema
{
    public function schema() 
    {
        $this->table('access_actors');

        $this->column('identifier')
            ->varchar(32)
            ->unique()
            ->required()
            ;

        $this->column('label')
            ->varchar(32)
            ->null()
            ;

        $this->column('description')
            ->text()
            ;

        $this->many('roles', 'Kendo\\Model\\RoleSchema', 'actor_id', 'id');
    }
}


