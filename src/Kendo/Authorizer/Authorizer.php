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

class Result {

    public $allowed;

    public $reason;

    public $credential;

    public function __construct($allowed, $reason, Credential $credential = null)
    {
        $this->allowed = $allowed;
        $this->reason = $reason;
        $this->credential = $credential;
    }

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

    public function authorize($actor, $operation, $resource)
    {
        foreach ($this->matchers as $matcher) {
            if ($matched = $matcher->match($actor, $operation, $resource)) {
                return new Result($matched->allow, "Rule matched");
            }
        }
        if ($this->disallowNoRuleMatches) {
            return new Result(false, "Disallowed.");
        }
        return new Result(true, "Allowed by default.");
    }
}



