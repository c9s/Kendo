<?php
namespace Kendo\RuleMatcher;
use Kendo\RuleLoader\RuleLoader;
use Kendo\IdentifierProvider\ActorIdentifierProvider;
use Kendo\IdentifierProvider\RoleIdentifierProvider;
use Kendo\IdentifierProvider\ResourceIdentifierProvider;
use Kendo\IdentifierProvider\GeneralIdentifierProvider;
use Kendo\IdentifierProvider\RecordIdentifierProvider;
use Kendo\RuleMatcher\RuleMatcher;
use Kendo\Context;
use LogicException;

class NativeSessionAccessRuleMatcher 
    extends AccessRuleMatcher 
    implements RuleMatcher
{
    protected function getActorRules($actorIdentifier)
    {
        $actorSessionKey = '__kendo:' . $actorIdentifier;
        if (isset($_SESSION[$actorSessionKey])) {
            $accessRules = $_SESSION[$actorSessionKey];
        } else {
            $_SESSION[$actorSessionKey] = $accessRules = $this->loader->getActorAccessRules($actorIdentifier);
        }
        return $accessRules;
    }
}
