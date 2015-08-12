<?php
namespace Kendo\RuleLoader;
use Kendo\SecurityPolicy\SecurityPolicyModule;
use SplObjectStorage;

interface RuleLoader
{
    public function getAccessRulesByActorIdentifier($actorIdentifier, $roleIdentifier = 0);

    public function getActorDefinitions();

    public function getResourceDefinitions();

    public function getOperationDefinitions();

}
