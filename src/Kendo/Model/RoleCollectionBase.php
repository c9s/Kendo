<?php
namespace Kendo\Model;
use LazyRecord\BaseCollection;
class RoleCollectionBase
    extends BaseCollection
{
    const schema_proxy_class = 'Kendo\\Model\\RoleSchemaProxy';
    const model_class = 'Kendo\\Model\\Role';
    const table = 'roles';
    const read_source_id = 'default';
    const write_source_id = 'default';
}
