<?php
namespace Kendo\Model;
use LazyRecord\BaseModel;
class OperationBase
    extends BaseModel
{
    const schema_proxy_class = 'Kendo\\Model\\OperationSchemaProxy';
    const collection_class = 'Kendo\\Model\\OperationCollection';
    const model_class = 'Kendo\\Model\\Operation';
    const table = 'access_operations';
    const read_source_id = 'default';
    const write_source_id = 'default';
    const primary_key = 'id';
    public static $column_names = array (
      0 => 'bitmask',
      1 => 'label',
      2 => 'description',
      3 => 'id',
    );
    public static $column_hash = array (
      'bitmask' => 1,
      'label' => 1,
      'description' => 1,
      'id' => 1,
    );
    public static $mixin_classes = array (
    );
    public function getSchema()
    {
        if ($this->_schema) {
           return $this->_schema;
        }
        return $this->_schema = \LazyRecord\Schema\SchemaLoader::load('Kendo\\Model\\OperationSchemaProxy');
    }
    public function getBitmask()
    {
            return $this->get('bitmask');
    }
    public function getLabel()
    {
            return $this->get('label');
    }
    public function getDescription()
    {
            return $this->get('description');
    }
    public function getId()
    {
            return $this->get('id');
    }
}
