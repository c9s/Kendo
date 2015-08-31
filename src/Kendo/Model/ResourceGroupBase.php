<?php
namespace Kendo\Model;
use LazyRecord\BaseModel;
class ResourceGroupBase
    extends BaseModel
{
    const schema_proxy_class = 'Kendo\\Model\\ResourceGroupSchemaProxy';
    const collection_class = 'Kendo\\Model\\ResourceGroupCollection';
    const model_class = 'Kendo\\Model\\ResourceGroup';
    const table = 'access_resources_group';
    const read_source_id = 'default';
    const write_source_id = 'default';
    const primary_key = 'id';
    public static $column_names = array (
      0 => 'identifier',
      1 => 'label',
      2 => 'description',
      3 => 'id',
    );
    public static $column_hash = array (
      'identifier' => 1,
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
        return $this->_schema = \LazyRecord\Schema\SchemaLoader::load('Kendo\\Model\\ResourceGroupSchemaProxy');
    }
    public function getIdentifier()
    {
            return $this->get('identifier');
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
