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
    const primary_key = 'id';
    public static $column_names = array (
      0 => 'actor_id',
      1 => 'role_id',
      2 => 'resource_id',
      3 => 'actor_record_id',
      4 => 'resource_record_id',
      5 => 'allow',
      6 => 'operation_bitmask',
      7 => 'operation_id',
      8 => 'id',
    );
    public static $column_hash = array (
      'actor_id' => 1,
      'role_id' => 1,
      'resource_id' => 1,
      'actor_record_id' => 1,
      'resource_record_id' => 1,
      'allow' => 1,
      'operation_bitmask' => 1,
      'operation_id' => 1,
      'id' => 1,
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
    public function getActorId()
    {
            return $this->get('actor_id');
    }
    public function getRoleId()
    {
            return $this->get('role_id');
    }
    public function getResourceId()
    {
            return $this->get('resource_id');
    }
    public function getActorRecordId()
    {
            return $this->get('actor_record_id');
    }
    public function getResourceRecordId()
    {
            return $this->get('resource_record_id');
    }
    public function getAllow()
    {
            return $this->get('allow');
    }
    public function getOperationBitmask()
    {
            return $this->get('operation_bitmask');
    }
    public function getOperationId()
    {
            return $this->get('operation_id');
    }
    public function getId()
    {
            return $this->get('id');
    }
}
