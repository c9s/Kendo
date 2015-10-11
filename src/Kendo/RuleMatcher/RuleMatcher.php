<?php
namespace Kendo\RuleMatcher;
use Kendo\Context;

interface RuleMatcher 
{
    const RESOURCE_RULE_UNDEFINED = 1;
    const RESOURCE_RULE_EMPTY     = 2;
    const ACTOR_RULE_UNDEFINED    = 3;
    const NO_RULE_MATCHED         = 4;

    public function match($actor, $operation, $resource, Context $context = null);

}


