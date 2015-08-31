<?php
namespace Kendo\Model;
use LazyRecord\BaseCollection;
class ResourceGroupCollectionBase
    extends BaseCollection
{
    const schema_proxy_class = 'Kendo\\Model\\ResourceGroupSchemaProxy';
    const model_class = 'Kendo\\Model\\ResourceGroup';
    const table = 'access_resources_group';
    const read_source_id = 'default';
    const write_source_id = 'default';
}
