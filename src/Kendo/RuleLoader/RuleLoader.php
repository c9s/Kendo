<?php
namespace Kendo\RuleLoader;
use Kendo\DefinitionStorage;
use SplObjectStorage;

interface RuleLoader
{
    public function getAccessRulesByActorIdentifier($actorIdentifier, $roleIdentifier = 0);

    public function getActorDefinitions();

    public function getResourceDefinitions();

}
