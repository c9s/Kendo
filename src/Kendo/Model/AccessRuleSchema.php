<?php
namespace Kendo\Model;
use LazyRecord\Schema\DeclareSchema;

class AccessRuleSchema extends DeclareSchema
{
    public function schema() 
    {
        // Relationship Foreign Key
        // -----------------------------------------------------
        $this->column('actor_id')
            ->refer('Kendo\\Model\\ActorSchema')
            ->integer();

        $this->column('role_id')
            ->refer('Kendo\\Model\\RoleSchema')
            ->integer()
            ->null()
            ;

        $this->column('resource_id')
            ->integer()
            ->required();


        // the resource group id
        $this->column('resource_group_id')
            ->integer()
            ->null()
            ;


        // Attributes
        // -----------------------------------------------------
        // The actor record is and resource record are actually 
        // an attribute of a rule.

        // When managing operation permissions, we don't query the attributes
        $this->column('actor_record_id')
            ->integer();

        $this->column('resource_record_id')
            ->integer()
            ->null()
            ;


        // This is also defined in AccessControlSchema
        $this->column('allow')
            ->boolean()
            ->notNull()
            ->required();


        $this->column('operation_id')
            ->integer()
            ->required();


        /**
         * Saved identifiers for query & view
         */
        $this->column('actor')
            ->varchar(30) ;
        $this->column('role')
            ->varchar(30) ;
        $this->column('resource')
            ->varchar(30) ;
        $this->column('operation')
            ->varchar(30)
            ->required()
            ;


        $this->belongsTo('resource','Kendo\\Model\\ResourceSchema','id','resource_id');

        $this->belongsTo('resource_group','Kendo\\Model\\ResourceGroupSchema','id','resource_group_id');

        $this->belongsTo('operation','Kendo\\Model\\OperationSchema','id','operation_id');

        $this->belongsTo('actor','Kendo\\Model\\ActorSchema','id','actor_id');

        $this->many('controls', 'Kendo\\Model\\AccessControlSchema', 'rule_id', 'id');
    }
}


