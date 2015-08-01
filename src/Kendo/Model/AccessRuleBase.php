<?php
namespace Kendo\Model;
use LazyRecord\BaseModel;
class AccessRuleBase
    extends BaseModel
{
    const schema_proxy_class = 'Kendo\\Model\\AccessRuleSchemaProxy';
    const collection_class = 'Kendo\\Model\\AccessRuleCollection';
    const model_class = 'Kendo\\Model\\AccessRule';
    const table = 'access_rules';
    const read_source_id = 'default';
    const write_source_id = 'default';
    const primary_key = NULL;
    public static $column_names = array (
      0 => 'rules_class',
      1 => 'resource',
      2 => 'operation',
      3 => 'operation_label',
      4 => 'description',
    );
    public static $column_hash = array (
      'rules_class' => 1,
      'resource' => 1,
      'operation' => 1,
      'operation_label' => 1,
      'description' => 1,
    );
    public static $mixin_classes = array (
    );
    public function getSchema()
    {
        if ($this->_schema) {
           return $this->_schema;
        }
        return $this->_schema = \LazyRecord\Schema\SchemaLoader::load('Kendo\\Model\\AccessRuleSchemaProxy');
    }
    public function getRulesClass()
    {
        if (isset($this->_data['rules_class'])) {
            return $this->_data['rules_class'];
        }
    }
    public function getResource()
    {
        if (isset($this->_data['resource'])) {
            return $this->_data['resource'];
        }
    }
    public function getOperation()
    {
        if (isset($this->_data['operation'])) {
            return $this->_data['operation'];
        }
    }
    public function getOperationLabel()
    {
        if (isset($this->_data['operation_label'])) {
            return $this->_data['operation_label'];
        }
    }
    public function getDescription()
    {
        if (isset($this->_data['description'])) {
            return $this->_data['description'];
        }
    }
}
