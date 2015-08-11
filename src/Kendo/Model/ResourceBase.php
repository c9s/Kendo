<?php
namespace Kendo\Model;
use LazyRecord\BaseModel;
class ResourceBase
    extends BaseModel
{
    const schema_proxy_class = 'Kendo\\Model\\ResourceSchemaProxy';
    const collection_class = 'Kendo\\Model\\ResourceCollection';
    const model_class = 'Kendo\\Model\\Resource';
    const table = 'resources';
    const read_source_id = 'default';
    const write_source_id = 'default';
    const primary_key = NULL;
    public static $column_names = array (
      0 => 'name',
      1 => 'label',
      2 => 'description',
    );
    public static $column_hash = array (
      'name' => 1,
      'label' => 1,
      'description' => 1,
    );
    public static $mixin_classes = array (
    );
    public function getSchema()
    {
        if ($this->_schema) {
           return $this->_schema;
        }
        return $this->_schema = \LazyRecord\Schema\SchemaLoader::load('Kendo\\Model\\ResourceSchemaProxy');
    }
    public function getName()
    {
            return $this->get('name');
    }
    public function getLabel()
    {
            return $this->get('label');
    }
    public function getDescription()
    {
            return $this->get('description');
    }
}
