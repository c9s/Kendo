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

class NativeSessionAccessRuleMatcher extends AccessRuleMatcher implements RuleMatcher
{

    protected function matchRules($actorIdentifier, $role, $actorRecordId = null, $operation, $resourceIdentifier, $resourceRecordId)
    {
        $actorSessionKey = '__kendo:' . $actorIdentifier;

        if (isset($_SESSION[$actorSessionKey])) {
            $accessRules = $_SESSION[$actorSessionKey];
        } else {
            $accessRules = $this->loader->getActorAccessRules($actorIdentifier);
            $_SESSION[$actorSessionKey] = $accessRules;
        }
        return parent::matchRules($actorIdentifier, $role, $actorRecordId, $oepration, $resourceIdentifier, $resourceRecordId);
    }
}
