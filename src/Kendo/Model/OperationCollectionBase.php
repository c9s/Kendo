<?php
namespace Kendo\Model;
use LazyRecord\BaseCollection;
class OperationCollectionBase
    extends BaseCollection
{
    const schema_proxy_class = 'Kendo\\Model\\OperationSchemaProxy';
    const model_class = 'Kendo\\Model\\Operation';
    const table = 'access_operations';
    const read_source_id = 'default';
    const write_source_id = 'default';
}
