<?php
namespace Kendo;

class Credential
{

    /**
     * @var boolean valid credential
     */
    protected $valid;

    /**
     * @var string $message
     */
    protected $message;

    public function __construct($valid, $message)
    {
        $this->valid = $valid;
        $this->message = $message;
    }

    public function isValid()
    {
        return $this->valid;
    }

}



