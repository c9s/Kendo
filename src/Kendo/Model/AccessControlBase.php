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
      0 => 'rule_id',
      1 => 'actor_record_id',
      2 => 'resource_record_id',
      3 => 'allow',
      4 => 'id',
    );
    public static $column_hash = array (
      'rule_id' => 1,
      'actor_record_id' => 1,
      'resource_record_id' => 1,
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
    public function getRuleId()
    {
            return $this->get('rule_id');
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
    public function getId()
    {
            return $this->get('id');
    }
}
