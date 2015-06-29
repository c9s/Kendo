<?php
namespace Kendo\Model;
use LazyRecord\BaseModel;
class AccessControlBase
    extends BaseModel
{
    const schema_proxy_class = 'Kendo\\Model\\AccessControlSchemaProxy';
    const collection_class = 'Kendo\\Model\\AccessControlCollection';
    const model_class = 'Kendo\\Model\\AccessControl';
    const table = 'access_controls';
    const read_source_id = 'default';
    const write_source_id = 'default';
    const primary_key = 'id';
    public static $column_names = array (
      0 => 'role',
      1 => 'rule_id',
      2 => 'allow',
      3 => 'id',
    );
    public static $column_hash = array (
      'role' => 1,
      'rule_id' => 1,
      'allow' => 1,
      'id' => 1,
    );
    public static $mixin_classes = array (
    );
    public function getSchema()
    {
        if ($this->_schema) {
           return $this->_schema;
        }
        return $this->_schema = \LazyRecord\Schema\SchemaLoader::load('Kendo\\Model\\AccessControlSchemaProxy');
    }
    public function getRole()
    {
        if (isset($this->_data['role'])) {
            return $this->_data['role'];
        }
    }
    public function getRuleId()
    {
        if (isset($this->_data['rule_id'])) {
            return $this->_data['rule_id'];
        }
    }
    public function getAllow()
    {
        if (isset($this->_data['allow'])) {
            return $this->_data['allow'];
        }
    }
    public function getId()
    {
        if (isset($this->_data['id'])) {
            return $this->_data['id'];
        }
    }
}
