<?php
namespace Kendo\Definition;
// use Kendo\Definition\RoleDefinition;
use Kendo\Definition\ActorDefinition;
use Kendo\Definition\ResourceDefinition;
use Kendo\Definition\RuleDefinition;
use Kendo\Operation\OperationList;
use Exception;
use ReflectionClass;

abstract class Definition
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

    public function operation($bitmask, $label)
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
    public function operations($operations)
    {
        if (method_exists($operations, 'export')) {
            $constants = $operations->export();
            foreach ($constants as $constantValue => $constantName) {
                $this->operation($constantValue, $constantName);
            }
        } else {
            // Fetch constant information by using ReflectionClass
            $refClass = new ReflectionClass($operations);
            $constants = $refClass->getConstants();
            foreach ($constants as $constantName => $constantValue) {
                // TODO: split underscore into words
                $this->operation($constantValue, ucfirst(strtolower($constantName)));
            }
        }
        return $this;
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

