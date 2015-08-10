<?php
namespace Kendo\RuleLoader;
use Kendo\DefinitionStorage;
use SplObjectStorage;

interface RuleLoader
{
    public function getAccessRulesByActorIdentifier($actorIdentifier);

    public function getDenyRulesByActorIdentifier($actorIdentifier);

    public function getActorDefinitions();

}
