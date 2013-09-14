<?php
namespace Kendo\Model;

class AccessRuleBase  extends \LazyRecord\BaseModel {
const schema_proxy_class = 'Kendo\\Model\\AccessRuleSchemaProxy';
const collection_class = 'Kendo\\Model\\AccessRuleCollection';
const model_class = 'Kendo\\Model\\AccessRule';
const table = 'access_rules';

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

}
