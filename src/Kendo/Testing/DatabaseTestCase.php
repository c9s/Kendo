<?php
namespace Kendo\Testing;

use LazyRecord\ConnectionManager;
use LazyRecord\Testing\ModelTestCase;
use Kendo\Testing\DatabaseTestCase;

use SimpleApp\SimpleSecurityPolicy;
use SimpleApp\UserSpecificSecurityPolicy;
use SimpleApp\User\NormalUser;
use SimpleApp\User\AdminUser;

use Kendo\RuleLoader\RuleLoader;
use Kendo\RuleLoader\PDORuleLoader;
use Kendo\RuleLoader\SchemaRuleLoader;
use Kendo\RuleMatcher\AccessRuleMatcher;

use Kendo\Operation\GeneralOperation;
use Kendo\Authorizer\Authorizer;
use Kendo\SecurityPolicy\SecurityPolicyModule;

use Kendo\RuleImporter\DatabaseRuleImporter;

use Kendo\Model\AccessRuleCollection;
use Kendo\Model\ActorCollection;
use Kendo\Model\ResourceCollection;
use Kendo\Model\OperationCollection;
use Kendo\Model\RoleCollection;

use CLIFramework\Debug\ConsoleDebug;

abstract class DatabaseTestCase extends ModelTestCase
{
    public $driver = 'sqlite';

    public $policyModule;


    public function getModels()
    {
        return [
            new \Kendo\Model\ActorSchema,
            new \Kendo\Model\RoleSchema,
            new \Kendo\Model\ResourceSchema,
            new \Kendo\Model\OperationSchema,
            new \Kendo\Model\AccessRuleSchema,
            new \Kendo\Model\AccessControlSchema,
        ];
    }

    public function getPolicies()
    {
    }

    public function setUp()
    {
        $this->policyModule = new SecurityPolicyModule;
        if ($policies = $this->getPolicies()) {
            foreach ($policies as $policy) {
                $this->policyModule->add($policy);
            }
        }
    }

    public function tearDown()
    {
        $resources = new ResourceCollection;
        $resources->delete();

        $rules = new AccessRuleCollection;
        $rules->delete();

        $actor = new ActorCollection;
        $actor->delete();

        $actors = new ActorCollection;
        $actors->delete();

        $roles = new RoleCollection;
        $roles->delete();

        $ops = new OperationCollection;
        $ops->delete();
    }


}
