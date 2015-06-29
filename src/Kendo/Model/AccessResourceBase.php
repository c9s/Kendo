<?php
namespace Kendo\Model;
use LazyRecord\BaseModel;
class AccessResourceBase
    extends BaseModel
{
    const schema_proxy_class = 'Kendo\\Model\\AccessResourceSchemaProxy';
    const collection_class = 'Kendo\\Model\\AccessResourceCollection';
    const model_class = 'Kendo\\Model\\AccessResource';
    const table = 'access_resources';
    const read_source_id = 'default';
    const write_source_id = 'default';
    const primary_key = 'id';
    public static $column_names = array (
      0 => 'rules_class',
      1 => 'name',
      2 => 'label',
      3 => 'description',
      4 => 'id',
    );
    public static $column_hash = array (
      'rules_class' => 1,
      'name' => 1,
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
        return $this->_schema = \LazyRecord\Schema\SchemaLoader::load('Kendo\\Model\\AccessResourceSchemaProxy');
    }
    public function getRulesClass()
    {
        if (isset($this->_data['rules_class'])) {
            return $this->_data['rules_class'];
        }
    }
    public function getName()
    {
        if (isset($this->_data['name'])) {
            return $this->_data['name'];
        }
    }
    public function getLabel()
    {
        if (isset($this->_data['label'])) {
            return $this->_data['label'];
        }
    }
    public function getDescription()
    {
        if (isset($this->_data['description'])) {
            return $this->_data['description'];
        }
    }
    public function getId()
    {
        if (isset($this->_data['id'])) {
            return $this->_data['id'];
        }
    }
}
