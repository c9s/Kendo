<?php
namespace Kendo\Model;
use LazyRecord\BaseModel;
class ResourceBase
    extends BaseModel
{
    const schema_proxy_class = 'Kendo\\Model\\ResourceSchemaProxy';
    const collection_class = 'Kendo\\Model\\ResourceCollection';
    const model_class = 'Kendo\\Model\\Resource';
    const table = 'access_resources';
    const read_source_id = 'default';
    const write_source_id = 'default';
    const primary_key = 'id';
    public static $column_names = array (
      0 => 'identifier',
      1 => 'label',
      2 => 'description',
      3 => 'group_id',
      4 => 'id',
    );
    public static $column_hash = array (
      'identifier' => 1,
      'label' => 1,
      'description' => 1,
      'group_id' => 1,
      'id' => 1,
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
    public function getGroupId()
    {
            return $this->get('group_id');
    }
    public function getId()
    {
            return $this->get('id');
    }
}
