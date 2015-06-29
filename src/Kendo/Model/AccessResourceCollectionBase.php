<?php
namespace Kendo\Model;
use LazyRecord\BaseCollection;
class AccessResourceCollectionBase
    extends BaseCollection
{
    const schema_proxy_class = 'Kendo\\Model\\AccessResourceSchemaProxy';
    const model_class = 'Kendo\\Model\\AccessResource';
    const table = 'access_resources';
    const read_source_id = 'default';
    const write_source_id = 'default';
}
