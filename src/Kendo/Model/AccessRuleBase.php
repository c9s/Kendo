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
      3 => 'resource_group_id',
      4 => 'actor_record_id',
      5 => 'resource_record_id',
      6 => 'allow',
      7 => 'operation_id',
      8 => 'actor',
      9 => 'role',
      10 => 'resource',
      11 => 'operation',
      12 => 'id',
    );
    public static $column_hash = array (
      'actor_id' => 1,
      'role_id' => 1,
      'resource_id' => 1,
      'resource_group_id' => 1,
      'actor_record_id' => 1,
      'resource_record_id' => 1,
      'allow' => 1,
      'operation_id' => 1,
      'actor' => 1,
      'role' => 1,
      'resource' => 1,
      'operation' => 1,
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
    public function getResourceGroupId()
    {
            return $this->get('resource_group_id');
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
    public function getOperationId()
    {
            return $this->get('operation_id');
    }
    public function getActor()
    {
            return $this->get('actor');
    }
    public function getRole()
    {
            return $this->get('role');
    }
    public function getResource()
    {
            return $this->get('resource');
    }
    public function getOperation()
    {
            return $this->get('operation');
    }
    public function getId()
    {
            return $this->get('id');
    }
}
