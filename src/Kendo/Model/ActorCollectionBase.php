<?php
namespace Kendo\Model;
use LazyRecord\BaseCollection;
class ActorCollectionBase
    extends BaseCollection
{
    const schema_proxy_class = 'Kendo\\Model\\ActorSchemaProxy';
    const model_class = 'Kendo\\Model\\Actor';
    const table = 'access_actors';
    const read_source_id = 'default';
    const write_source_id = 'default';
}
