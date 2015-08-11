<?php
namespace Kendo\Authorizer;
use Kendo\RuleMatcher\RuleMatcher;

class Authorizer
{
    protected $matchers = array();

    public function __construct()
    {

    }

    public function addMatcher(RuleMatcher $matcher) {
        $this->matchers[] = $matcher;
    }

    public function authorize() {

    }
}



