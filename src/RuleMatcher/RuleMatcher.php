<?php
namespace Kendo\RuleMatcher;
use Kendo\Context;


/**
 * A RuleMatcher should provide a match method to match a rule array and return
 * the matched rule array
 */
interface RuleMatcher 
{
    const RESOURCE_RULE_UNDEFINED = 1;
    const RESOURCE_RULE_EMPTY     = 2;
    const ACTOR_RULE_UNDEFINED    = 3;
    const NO_RULE_MATCHED         = 4;


    /**
     *
     * @param string|GeneralIdentifierProvider|ActorIdentifierProvider An object that implemented with
     *                                         ActorIdentifierProvider or the actor string identifier.
     *
     * @param string $opeation operation identifier.
     * @param string|ResourceIdentifierProvider|GeneralIdentifierProvider
     */
    public function match($actor, $operation, $resource, Context $context = null);

}


