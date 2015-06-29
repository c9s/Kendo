<?php
namespace Kendo\Model;
use LazyRecord\BaseCollection;
class AccessControlCollectionBase
    extends BaseCollection
{
    const schema_proxy_class = 'Kendo\\Model\\AccessControlSchemaProxy';
    const model_class = 'Kendo\\Model\\AccessControl';
    const table = 'access_controls';
    const read_source_id = 'default';
    const write_source_id = 'default';
}
