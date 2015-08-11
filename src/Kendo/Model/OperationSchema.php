<?php
namespace Kendo\Model;
use LazyRecord\Schema\DeclareSchema;

class OperationSchema extends DeclareSchema
{
    public function schema()
    {
        $this->table('access_operations');

        $this->column('bitmask')
            ->integer()
            ->unique()
            ->required();

        $this->column('label')
            ->varchar(128);

        $this->column('description')
            ->text();
    }


}





