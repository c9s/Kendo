<?php
namespace Kendo\Definition;
// use Kendo\Definition\RoleDefinition;
use Kendo\Definition\ActorDefinition;
use Kendo\Definition\ResourceDefinition;
use Kendo\Definition\RuleDefinition;

abstract class Definition
{
    abstract public function schema();

    /**
     * @var 
     */
    protected $actors = array();

    /**
     * @var array provided resources
     */
    protected $resources = array();

    /**
     * The default rules of a definition
     */
    protected $rules = array();

    protected $rulesByIdentifier = array();


    public function __construct()
    {
        // call schema method to build up rules
        $this->schema();
    }

    public function actor($identifier, $label = null)
    {
        if (isset($this->actors[ $identifier ])) {
            return $this->actors[ $identifier ];
        }
        return $this->actors[$identifier] = new ActorDefinition($identifier, $label);
    }

    public function findActorByIdentifier($identifier)
    {
        if (isset($this->actors[ $identifier ])) {
            return $this->actors[ $identifier ];
        }
    }

    public function findResourceByIdentifier($identifier)
    {
        if (isset($this->resources[ $identifier ])) {
            return $this->resources[ $identifier ];
        }
    }



    public function resource($identifier, $label = null)
    {
        if (isset($this->resources[ $identifier ])) {
            return $this->resources[ $identifier ];
        }
        return $this->resources[ $identifier ] = new ResourceDefinition($identifier, $label);
    }


    /**
     * Returns a newly created Rule object
     *
     * @return RuleDefinition
     */
    public function rule($identifier = null)
    {
        if ($identifier && isset($this->rulesByIdentifier[$identifier] )) {
            return  $this->rulesByIdentifier[$identifier];
        }

        $rule = new RuleDefinition($this);
        if ($identifier) {
            $this->rulesByIdentifier[ $identifier ] = $rule;
        }
        return $this->rules[] = $rule;
    }

    public function getActorDefinitions()
    {
        return $this->actors;
    }

    public function getResourceDefinitions()
    {
        return $this->resources;
    }

    /**
     * Returns rule definition objects
     *
     * @return RuleDefinition[]
     */
    public function getRuleDefinitions()
    {
        return $this->rules;
    }
}

