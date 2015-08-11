<?php
namespace Kendo\Definition;
use Kendo\Definition\Definition;
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



