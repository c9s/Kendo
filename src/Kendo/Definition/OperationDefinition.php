<?php
namespace Kendo\Definition;
use Kendo\SecurityPolicy\RBACSecurityPolicySchema;
use Exception;

class OperationDefinition
{
    public $bitmask;

    public $label;

    public function __construct($bitmask, $label)
    {
        $this->bitmask = $bitmask;
        $this->label = $label;
    }
}



