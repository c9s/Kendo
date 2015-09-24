<?php
namespace Kendo\Authorizer;

class AuthorizationResult 
{

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
