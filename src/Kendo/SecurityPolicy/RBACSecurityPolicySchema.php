<?php
namespace Kendo\SecurityPolicy;
use Kendo\Definition\RoleDefinition;
use Kendo\Definition\ActorDefinition;
use Kendo\Definition\ResourceDefinition;
use Kendo\Definition\ResourceGroupDefinition;
use Kendo\Definition\RuleDefinition;
use Kendo\Definition\OperationDefinition;
use Kendo\Operation\OperationList;
use Kendo\Operation\OperationConstantExporter;
use Exception;
use ReflectionClass;



/**
 * RBACSecurityPolicySchema provides methods related to Role-based access control 
 *
 * To use this class, just extends your class from RBACSecurityPolicySchema
 * and define the roles, resources, actors in the abstract schema method.
 */
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
     *          'print' => OperationDefinition,
     *          'export' => OperationDefinition,
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

    public function findOperationByIdentifier($identifier)
    {
        if (isset($this->operations[$identifier])) {
            return $this->operations[$identifier];
        }
    }


    public function globalOperation($identifier, $label = null)
    {
        if (isset($this->operations[$identifier])) {
            throw new Exception("operation $label ($identifier) is already defined.");
        }
        $op = new OperationDefinition($identifier, $label);
        $this->operations[$identifier] = $op;
        return $this;
    }

    /**
     * Register an operation list.
     */
    public function globalOperations($operations)
    {
        if (method_exists($operations, 'export')) {
            $constants = $operations->export();
            foreach ($constants as $constantName => $constantValue) {
                $this->globalOperation($constantName, $constantValue);
            }
        } else {
            // Fetch constant information by using ReflectionClass
            $refClass = new ReflectionClass($operations);
            $constants = $refClass->getConstants();
            foreach ($constants as $constantName => $constantValue) {
                $this->globalOperation($constantValue);
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

