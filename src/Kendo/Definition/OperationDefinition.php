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

    public $identifier;

    public $label;

    public $description;

    public function __construct($identifier = null, $label = null)
    {
        if ($identifier) {
            $this->identifier = $identifier;
        }
        if ($label) {
            $this->label = $label;
        }

        // Record Id
        if ($this->id) {
            $this->id = intval($this->id);
        }
        /*
        if ($this->bitmask) {
            $this->bitmask = intval($this->bitmask);
        }
        */
    }
}



