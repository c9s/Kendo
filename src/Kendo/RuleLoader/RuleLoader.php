<?php
namespace Kendo\RuleLoader;
use Kendo\SecurityPolicy\SecurityPolicyModule;
use SplObjectStorage;


/**
 * RuleLoader interface defines what a RuleLoader should have
 */
interface RuleLoader
{
    public function getActorAccessRules($actorIdentifier, $roleIdentifier = 0);

    public function getActorDefinitions();

    public function getResourceDefinitions();

    public function getResourceGroupDefinitions();

    public function getOperationDefinitions();

}
