<?php
namespace Kendo\Model;
use LazyRecord\Schema\DeclareSchema;

class ActorSchema extends DeclareSchema
{
    public function schema() 
    {
        $this->column('id')
            ->primary()
            ->autoIncrement()
            ->unsigned()
            ->isa('int')
            ->integer()
            ->notNull()
            ;

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
    }
}


