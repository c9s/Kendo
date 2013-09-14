<?php
namespace Kendo\Model;

use LazyRecord;
use LazyRecord\Schema\RuntimeSchema;
use LazyRecord\Schema\Relationship;

class AccessResourceSchemaProxy extends RuntimeSchema
{

    public static $column_names = array (
  0 => 'rules_class',
  1 => 'name',
  2 => 'label',
  3 => 'description',
);
    public static $column_hash = array (
  'rules_class' => 1,
  'name' => 1,
  'label' => 1,
  'description' => 1,
);
    public static $mixin_classes = array (
);
    public static $column_names_include_virtual = array (
  0 => 'rules_class',
  1 => 'name',
  2 => 'label',
  3 => 'description',
);

    const schema_class = 'Kendo\\Model\\AccessResourceSchema';
    const collection_class = 'Kendo\\Model\\AccessResourceCollection';
    const model_class = 'Kendo\\Model\\AccessResource';
    const model_name = 'AccessResource';
    const model_namespace = 'Kendo\\Model';
    const primary_key = NULL;
    const table = 'access_resources';
    const label = 'AccessResource';

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
  'name' => array( 
      'name' => 'name',
      'attributes' => array( 
          'type' => 'varchar(64)',
          'isa' => 'str',
          'size' => 64,
          'required' => true,
        ),
    ),
  'label' => array( 
      'name' => 'label',
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
);
        $this->columnNames     = array( 
  'rules_class',
  'name',
  'label',
  'description',
);
        $this->primaryKey      = NULL;
        $this->table           = 'access_resources';
        $this->modelClass      = 'Kendo\\Model\\AccessResource';
        $this->collectionClass = 'Kendo\\Model\\AccessResourceCollection';
        $this->label           = 'AccessResource';
        $this->relations       = array( 
  'access_rules' => \LazyRecord\Schema\Relationship::__set_state(array( 
  'data' => array( 
      'type' => 2,
      'self_column' => 'name',
      'self_schema' => 'Kendo\\Model\\AccessResourceSchema',
      'foreign_column' => 'resource',
      'foreign_schema' => 'Kendo\\Model\\AccessRuleSchema',
    ),
)),
);
        $this->readSourceId    = 'default';
        $this->writeSourceId    = 'default';
        parent::__construct();
    }

}
