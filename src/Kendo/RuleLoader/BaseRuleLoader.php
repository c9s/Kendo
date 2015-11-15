<?php
namespace Kendo\RuleLoader;

abstract class BaseRuleLoader
{

    /**
     * @var ActorDefinition[identifier]
     */
    protected $definedActors = array();


    /**
     * @var RoleDefinition[identifier]
     */
    protected $definedRoles = array();

    /**
     * @var OperationDefinition[identifier]
     */
    protected $definedOperations = array();

    /**
     * @var ResourceDefinition[identifier]
     */
    protected $definedResources = array();


    /**
     * @var ResourceGroupDefinition[identifier]
     */
    protected $definedResourceGroups = array();



    /**
     * @var array accessRules[actor][role][resource] = [ CREATE, UPDATE, DELETE ];
     *
     * role == 0   -- without role restriction
     */
    protected $accessRules = array();




    public function findActorByIdentifier($identifier)
    {
        if (isset($this->definedActors[ $identifier ])) {
            return $this->definedActors[ $identifier ];
        }
    }

    public function findResourceGroupByIdentifier($identifier)
    {
        if (isset($this->definedResourceGroups[ $identifier ])) {
            return $this->definedResourceGroups[ $identifier ];
        }
    }

    public function findResourceByIdentifier($identifier)
    {
        if (isset($this->definedResources[ $identifier ])) {
            return $this->definedResources[ $identifier ];
        }
    }

    public function findOperationByIdentifier($identifier)
    {
        if (isset($this->definedOperations[$identifier])) {
            return $this->definedOperations[$identifier];
        }
    }


    public function getAccessRules()
    {
        return $this->accessRules;
    }


    public function getActorDefinitions()
    {
        return $this->definedActors;
    }

    public function getResourceDefinitions()
    {
        return $this->definedResources;
    }

    public function getOperationDefinitions()
    {
        return $this->definedOperations;
    }

    public function getResourceGroupDefinitions()
    {
        return $this->definedResourceGroups;
    }



}

