<?php
namespace Kendo\Model;
use LazyRecord\BaseCollection;
class ResourceCollectionBase
    extends BaseCollection
{
    const schema_proxy_class = 'Kendo\\Model\\ResourceSchemaProxy';
    const model_class = 'Kendo\\Model\\Resource';
    const table = 'resources';
    const read_source_id = 'default';
    const write_source_id = 'default';
}
