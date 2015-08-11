<?php
namespace Kendo\RuleMatcher;
use Kendo\Context;

interface RuleMatcher 
{
    public function match($actor, $operation, $resource, Context $context = null);
}


