<?php
namespace Kendo\Model;
use Maghead\Schema\DeclareSchema;

class RoleSchema extends DeclareSchema
{
    public function schema() 
    {
        $this->table('access_roles');

        $this->column('actor_id')
            ->refer('Kendo\\Model\\ActorSchema')
            ->integer();

        // role identifier
        $this->column('identifier')
            ->varchar(32)
            ->required()
            ;

        $this->column('label')
            ->varchar(32)
            ->null()
            ;

        $this->column('description')
            ->text()
            ;

        $this->belongsTo('actor', 'Kendo\\Model\\RoleSchema', 'id', 'actor_id');
    }



}


