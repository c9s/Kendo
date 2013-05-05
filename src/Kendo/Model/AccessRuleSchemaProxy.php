<?php
namespace Kendo\Model;

use LazyRecord;
use LazyRecord\Schema\RuntimeSchema;
use LazyRecord\Schema\Relationship;

class AccessRuleSchemaProxy extends RuntimeSchema
{

    public static $column_names = array (
  0 => 'rules_class',
  1 => 'resource',
  2 => 'operation',
  3 => 'operation_label',
  4 => 'description',
  5 => 'id',
);
    public static $column_hash = array (
  'rules_class' => 1,
  'resource' => 1,
  'operation' => 1,
  'operation_label' => 1,
  'description' => 1,
  'id' => 1,
);
    public static $mixin_classes = array (
);
    public static $column_names_include_virtual = array (
  0 => 'rules_class',
  1 => 'resource',
  2 => 'operation',
  3 => 'operation_label',
  4 => 'description',
  5 => 'id',
);

    const schema_class = 'Kendo\\Model\\AccessRuleSchema';
    const collection_class = 'Kendo\\Model\\AccessRuleCollection';
    const model_class = 'Kendo\\Model\\AccessRule';
    const model_name = 'AccessRule';
    const model_namespace = 'Kendo\\Model';
    const primary_key = 'id';
    const table = 'access_rules';
    const label = 'AccessRule';

    public function __construct()
    {
        /** columns might have closure, so it can not be const */
        $this->columnData      = array( 
  'rules_class' => array( 
      'name' => 'rules_class',
      'attributes' => array( 
          'type' => 'varchar(64)',
          'isa' => 'str',
          'size' => 64,
        ),
    ),
  'resource' => array( 
      'name' => 'resource',
      'attributes' => array( 
          'type' => 'varchar(64)',
          'isa' => 'str',
          'size' => 64,
          'required' => true,
        ),
    ),
  'operation' => array( 
      'name' => 'operation',
      'attributes' => array( 
          'type' => 'varchar(64)',
          'isa' => 'str',
          'size' => 64,
          'required' => true,
        ),
    ),
  'operation_label' => array( 
      'name' => 'operation_label',
      'attributes' => array( 
          'type' => 'varchar(128)',
          'isa' => 'str',
          'size' => 128,
        ),
    ),
  'description' => array( 
      'name' => 'description',
      'attributes' => array( 
          'type' => 'text',
          'isa' => 'str',
        ),
    ),
  'id' => array( 
      'name' => 'id',
      'attributes' => array( 
          'type' => 'integer',
          'isa' => 'int',
          'primary' => true,
          'autoIncrement' => true,
        ),
    ),
);
        $this->columnNames     = array( 
  'id',
  'rules_class',
  'resource',
  'operation',
  'operation_label',
  'description',
);
        $this->primaryKey      = 'id';
        $this->table           = 'access_rules';
        $this->modelClass      = 'Kendo\\Model\\AccessRule';
        $this->collectionClass = 'Kendo\\Model\\AccessRuleCollection';
        $this->label           = 'AccessRule';
        $this->relations       = array( 
  'control' => \LazyRecord\Schema\Relationship::__set_state(array( 
  'data' => array( 
      'type' => 4,
      'self_schema' => 'Kendo\\Model\\AccessRuleSchema',
      'self_column' => 'id',
      'foreign_schema' => 'Kendo\\Model\\AccessControlSchema',
      'foreign_column' => 'rule_id',
    ),
)),
);
        $this->readSourceId    = 'default';
        $this->writeSourceId    = 'default';
        parent::__construct();
    }

}
