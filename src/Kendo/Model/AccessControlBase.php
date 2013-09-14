<?php
namespace Kendo\Model;

class AccessControlBase  extends \LazyRecord\BaseModel {
const schema_proxy_class = 'Kendo\\Model\\AccessControlSchemaProxy';
const collection_class = 'Kendo\\Model\\AccessControlCollection';
const model_class = 'Kendo\\Model\\AccessControl';
const table = 'access_controls';

public static $column_names = array (
  0 => 'role',
  1 => 'rule_id',
  2 => 'allow',
);
public static $column_hash = array (
  'role' => 1,
  'rule_id' => 1,
  'allow' => 1,
);
public static $mixin_classes = array (
);

}
