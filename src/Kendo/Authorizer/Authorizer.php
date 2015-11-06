<?php
namespace Kendo\Authorizer;
use Kendo\RuleMatcher\RuleMatcher;

/**
 * Credential is used for granted operation.
 *
 */
class Credential
{
    public $expiry; // timestamp or DateTime

    public $actor;
}

class Authorizer
{
    protected $matchers = array();

    /**
     * @var int $ttl time to live.
     */
    protected $ttl = 30;

    protected $disallowNoRuleMatches = true;

    public function __construct()
    {

    }

    public function addMatcher(RuleMatcher $matcher)
    {
        $this->matchers[] = $matcher;
    }



    /**
     * Translate matching result code into reason string.
     *
     * @return string reason in string type.
     */
    protected function translateCode($code)
    {
        switch ($code) {
        case RuleMatcher::NO_RULE_MATCHED:
            return 'No rule matched.';
        case RuleMatcher::RESOURCE_RULE_EMPTY:
            return 'Resource rule empty.';
        case RuleMatcher::ACTOR_RULE_UNDEFINED:
            return 'Actor rule undefined.';
        case RuleMatcher::RESOURCE_RULE_UNDEFINED:
            return 'Resource rule undefined.';
        }
    }


    /**
     *
     *
     * @param string|RuleIdentifierProvider|ActorIdentifierProvider $actor
     * @param string $operation
     * @param string|ResourceIdentifierProvider $resource
     */
    public function authorize($actor, $operation, $resource)
    {
        foreach ($this->matchers as $matcher) {
            $retval = $matcher->match($actor, $operation, $resource);
            if (is_integer($retval)) {
                $reason = $this->translateCode($retval);
                return new AuthorizationResult(false, $reason);
            }
            return new AuthorizationResult($matched->allow, "Rule matched");
        }
        if ($this->disallowNoRuleMatches) {
            return new AuthorizationResult(false, "Disallowed.");
        }
        return new AuthorizationResult(true, "Allowed by default.");
    }
}

