<?php
namespace Kendo\Model;

use LazyRecord;
use LazyRecord\Schema\RuntimeSchema;
use LazyRecord\Schema\Relationship;

class AccessControlSchemaProxy extends RuntimeSchema
{

    public static $column_names = array (
  0 => 'role',
  1 => 'rule_id',
  2 => 'allow',
);
    public static $column_hash = array (
  'role' => 1,
  'rule_id' => 1,
  'allow' => 1,
);
    public static $mixin_classes = array (
);
    public static $column_names_include_virtual = array (
  0 => 'role',
  1 => 'rule_id',
  2 => 'allow',
);

    const schema_class = 'Kendo\\Model\\AccessControlSchema';
    const collection_class = 'Kendo\\Model\\AccessControlCollection';
    const model_class = 'Kendo\\Model\\AccessControl';
    const model_name = 'AccessControl';
    const model_namespace = 'Kendo\\Model';
    const primary_key = NULL;
    const table = 'access_controls';
    const label = 'AccessControl';

    public function __construct()
    {
        /** columns might have closure, so it can not be const */
        $this->columnData      = array( 
  'role' => array( 
      'name' => 'role',
      'attributes' => array( 
          'type' => 'varchar(32)',
          'isa' => 'str',
          'size' => 32,
        ),
    ),
  'rule_id' => array( 
      'name' => 'rule_id',
      'attributes' => array( 
          'type' => 'integer',
          'isa' => 'int',
          'required' => true,
        ),
    ),
  'allow' => array( 
      'name' => 'allow',
      'attributes' => array( 
          'type' => 'boolean',
          'isa' => 'bool',
          'default' => false,
        ),
    ),
);
        $this->columnNames     = array( 
  'role',
  'rule_id',
  'allow',
);
        $this->primaryKey      = NULL;
        $this->table           = 'access_controls';
        $this->modelClass      = 'Kendo\\Model\\AccessControl';
        $this->collectionClass = 'Kendo\\Model\\AccessControlCollection';
        $this->label           = 'AccessControl';
        $this->relations       = array( 
  'rule' => \LazyRecord\Schema\Relationship::__set_state(array( 
  'data' => array( 
      'type' => 4,
      'self_schema' => 'Kendo\\Model\\AccessControlSchema',
      'self_column' => 'rule_id',
      'foreign_schema' => 'Kendo\\Model\\AccessRuleSchema',
      'foreign_column' => 'id',
    ),
)),
);
        $this->readSourceId    = 'default';
        $this->writeSourceId    = 'default';
        parent::__construct();
    }

}
