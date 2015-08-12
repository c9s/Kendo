<?php
namespace Kendo\Definition;
use Kendo\SecurityPolicy\RBACSecurityPolicySchema;
use Exception;

class OperationDefinition
{
    /**
     * @var integer id is used for fetched object from PDO
     */
    public $id;

    public $bitmask;

    public $label;

    public $description;

    public function __construct($bitmask = null, $label = null)
    {
        if ($bitmask) {
            $this->bitmask = $bitmask;
        }
        if ($label) {
            $this->label = $label;
        }
        if ($this->id) {
            $this->id = intval($this->id);
        }
        if ($this->bitmask) {
            $this->bitmask = intval($this->bitmask);
        }
    }
}



