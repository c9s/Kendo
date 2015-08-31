<?php
namespace Kendo\SecurityPolicy;
use Kendo\Definition\RoleDefinition;
use Kendo\Definition\ActorDefinition;
use Kendo\Definition\ResourceDefinition;
use Kendo\Definition\ResourceGroupDefinition;
use Kendo\Definition\RuleDefinition;
use Kendo\Definition\OperationDefinition;
use Kendo\Operation\OperationList;
use Exception;
use ReflectionClass;

abstract class RBACSecurityPolicySchema
{
    abstract public function schema();

    /**
     * @var ActorDefinition[ identifier ]
     */
    protected $actors = array();


    /**
     * @var OperationDefinition[ bitmask ]
     *
     * Override this property to have a predefined operations, e.g.
     *
     *     protected $operations = [
     *          ExtraOp::Print => 'Print',
     *          ExtraOp::Export => 'Export',
     *     ];
     *
     */
    protected $operations = array();


    /**
     * @var ResourceDefinition[identifier] resources provided by this definition
     */
    protected $resources = array();


    /**
     * @var ResourceGroup[identifier]
     */
    protected $resourceGroups = array();

    /**
     * The default rules of a definition
     */
    protected $rules = array();


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

    public function globalOperation($bitmask, $label)
    {
        if (isset($this->operations[$bitmask])) {
            throw new Exception("operation $label ($bitmask) is already defined.");
        }
        $op = new OperationDefinition($bitmask, $label);
        $this->operations[ $bitmask ] = $op;
        return $this;
    }

    /**
     * Register an operation list.
     */
    public function globalOperations($operations)
    {
        if (method_exists($operations, 'export')) {
            $constants = $operations->export();
            foreach ($constants as $constantValue => $constantName) {
                $this->globalOperation($constantValue, $constantName);
            }
        } else {
            // Fetch constant information by using ReflectionClass
            $refClass = new ReflectionClass($operations);
            $constants = $refClass->getConstants();
            foreach ($constants as $constantName => $constantValue) {
                // TODO: split underscore into words
                $this->globalOperation($constantValue, ucfirst(strtolower($constantName)));
            }
        }
        return $this;
    }

    public function resourceGroup($identifier, $label = null)
    {
        if (isset($this->resourceGroups[ $identifier ])) {
            return $this->resourceGroups[ $identifier ];
        }
        return $this->resourceGroups[ $identifier ] = new ResourceGroupDefinition($identifier, $label);
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
    public function rule()
    {
        return $this->rules[] = new RuleDefinition($this);
    }

    public function getActorDefinitions()
    {
        return $this->actors;
    }

    public function getOperationDefinitions()
    {
        return $this->operations;
    }

    public function getResourceDefinitions()
    {
        return $this->resources;
    }

    public function getResourceGroupDefinitions()
    {
        return $this->resourceGroups;
    }

    /**
     * Returns allow rule definition objects
     *
     * @return RuleDefinition[]
     */
    public function getRuleDefinitions()
    {
        return $this->rules;
    }



}

