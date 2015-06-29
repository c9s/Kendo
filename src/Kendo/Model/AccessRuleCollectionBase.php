<?php
namespace Kendo\Model;
use LazyRecord\BaseCollection;
class AccessRuleCollectionBase
    extends BaseCollection
{
    const schema_proxy_class = 'Kendo\\Model\\AccessRuleSchemaProxy';
    const model_class = 'Kendo\\Model\\AccessRule';
    const table = 'access_rules';
    const read_source_id = 'default';
    const write_source_id = 'default';
}
