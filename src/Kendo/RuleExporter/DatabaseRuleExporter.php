<?php
namespace Kendo\RuleExporter;
use Kendo\RuleLoader\DefinitionRuleLoader;
use Kendo\RuleLoader\RuleLoader;
use Kendo\Model\AccessRule;
use Kendo\Model\AccessRuleCollection;
use Kendo\Model\Actor as ActorRecord;
use Kendo\Model\Role as RoleRecord;
use Kendo\Model\Resource as ResourceRecord;

class DatabaseRuleExporter
{
    protected $loader;

    public function __construct(RuleLoader $loader)
    {
        $this->loader = $loader;
    }

    public function export()
    {
        $actorDefinitions    = $this->loader->getActorDefinitions();
        $resourceDefinitions = $this->loader->getResourceDefinitions();
        $operationDefinitions = $this->loader->getOperationDefinitions();

        foreach ($actorDefinitions as $actorDefinition) {
            if ($roles = $actorDefinition->getRoles()) {
                foreach ($roles as $roleIdentifier) {
                    $accessRules = $this->loader->getAccessRulesByActorIdentifier($actorDefinition->identifier, $roleIdentifier);
                    foreach ($accessRules as $resourceIdentifier => $permissions) {
                        foreach ($permissions as list($opBit, $allow)) {
                            $op = $operationDefinitions[$opBit];

                            // echo "Actor '", $actorDefinition->identifier, "' with role '", $roleIdentifier, "' " , $op->label, " " , $resourceIdentifier, ($allow ? " is" : " is not"), " allowed.\n";
                            // var_dump( $op ); 
                            // var_dump( $allow ); 
                        }
                    }
                }
            } else if ($accessRules = $this->loader->getAccessRulesByActorIdentifier($actorDefinition->identifier)) {
                foreach ($accessRules as $resource => $permissions) {
                    var_dump( $permissions ); 
                }
            } else {
                // this actor doesn't have any rule to import
            }
        }
    }
}



