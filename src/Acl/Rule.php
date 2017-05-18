<?php
namespace Kendo\Acl;

class Rule 
{

    /**
     * @var string
     */
    public $role;

    /**
     * @var string
     */
    public $resource;

    /*
     * @var string 
     */
    public $operation;

    /**
     * @var string rule description
     */
    public $desc;

    /**
     * @var boolean allow or disallow
     */
    public $allow = false;



    /**
     *
     *
     * @param string $role Role identifier
     * @param string $resource  Resource identifier
     * @param string $operation Operation identifier
     */
    public function __construct($role, $resource, $operation, $allow) {
        $this->role = $role;
        $this->resource = $resource;
        $this->operation = array( 'id' => $operation );
        $this->allow = $allow;
    }

    public function label($label) {
        $this->operation['label'] = $label;
        return $this;
    }

    public function allow($allow)
    {
        $this->allow = $allow;
        return $this;
    }

    public function desc($desc) {
        $this->desc = $desc;
        return $this;
    }

    public function toArray() {
        return array(
            'role' => $this->role,
            'operation' => $this->operation,
            'resource' => $this->resource,
            'allow' => $this->allow,
            'desc' => $this->desc,
        );
    }
}
