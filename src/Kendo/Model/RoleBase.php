<?php
namespace Kendo\Model;
use LazyRecord\BaseModel;
class RoleBase
    extends BaseModel
{
    const schema_proxy_class = 'Kendo\\Model\\RoleSchemaProxy';
    const collection_class = 'Kendo\\Model\\RoleCollection';
    const model_class = 'Kendo\\Model\\Role';
    const table = 'access_roles';
    const read_source_id = 'default';
    const write_source_id = 'default';
    const primary_key = 'id';
    public static $column_names = array (
      0 => 'actor_id',
      1 => 'identifier',
      2 => 'label',
      3 => 'description',
      4 => 'id',
    );
    public static $column_hash = array (
      'actor_id' => 1,
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
        return $this->_schema = \LazyRecord\Schema\SchemaLoader::load('Kendo\\Model\\RoleSchemaProxy');
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
    public function getId()
    {
            return $this->get('id');
    }
}
