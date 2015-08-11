<?php
namespace Kendo\Model;
use LazyRecord\BaseModel;
class RoleBase
    extends BaseModel
{
    const schema_proxy_class = 'Kendo\\Model\\RoleSchemaProxy';
    const collection_class = 'Kendo\\Model\\RoleCollection';
    const model_class = 'Kendo\\Model\\Role';
    const table = 'roles';
    const read_source_id = 'default';
    const write_source_id = 'default';
    const primary_key = 'id';
    public static $column_names = array (
      0 => 'id',
      1 => 'actor_id',
      2 => 'identifier',
      3 => 'label',
      4 => 'description',
    );
    public static $column_hash = array (
      'id' => 1,
      'actor_id' => 1,
      'identifier' => 1,
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
        return $this->_schema = \LazyRecord\Schema\SchemaLoader::load('Kendo\\Model\\RoleSchemaProxy');
    }
    public function getId()
    {
            return $this->get('id');
    }
    public function getActorId()
    {
            return $this->get('actor_id');
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
}
