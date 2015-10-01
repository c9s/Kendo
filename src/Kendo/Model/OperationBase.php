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
      0 => 'identifier',
      1 => 'label',
      2 => 'reference_class',
      3 => 'description',
      4 => 'id',
    );
    public static $column_hash = array (
      'identifier' => 1,
      'label' => 1,
      'reference_class' => 1,
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
    public function getIdentifier()
    {
            return $this->get('identifier');
    }
    public function getLabel()
    {
            return $this->get('label');
    }
    public function getReferenceClass()
    {
            return $this->get('reference_class');
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
