<?php
namespace Kendo;
use Kendo\SecurityPolicy\RBACSecurityPolicySchema;
use SplObjectStorage;

class SecurityPolicyModule extends SplObjectStorage
{
    public function __construct(array $defs = array())
    {
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
}



