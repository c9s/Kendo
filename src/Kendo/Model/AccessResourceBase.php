<?php
namespace Kendo\Model;

class AccessResourceBase  extends \LazyRecord\BaseModel {
const schema_proxy_class = 'Kendo\\Model\\AccessResourceSchemaProxy';
const collection_class = 'Kendo\\Model\\AccessResourceCollection';
const model_class = 'Kendo\\Model\\AccessResource';
const table = 'access_resources';

public static $column_names = array (
  0 => 'rules_class',
  1 => 'name',
  2 => 'label',
  3 => 'description',
);
public static $column_hash = array (
  'rules_class' => 1,
  'name' => 1,
  'label' => 1,
  'description' => 1,
);
public static $mixin_classes = array (
);

}
