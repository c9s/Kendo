<?php
namespace Kendo;
use Kendo\SecurityPolicy\RBACSecurityPolicySchema;
use SplObjectStorage;

class SecurityPolicyModule extends SplObjectStorage
{
    public $label;

    public function __construct($label = '', array $defs = array())
    {
        $this->label = $label;
        foreach ($defs as $def) {
            $this->attach($def);
        }
    }

    public function add(RBACSecurityPolicySchema $definition)
    {
        if ($this->contains($definition)) {
            return false;
        }
        $this->attach($definition);
    }

    public function getLabel()
    {
        return $this->label;
    }

    public function setLabel($label)
    {
        $this->label = $label;
    }
}



