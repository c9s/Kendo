<?php
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
use Kendo\RuleEditor\ActorRuleEditor;
use Kendo\RuleEditor\ActorRuleEditorView;

use Kendo\Model\AccessRuleCollection;
use Kendo\Model\ActorCollection;
use Kendo\Model\ResourceCollection;
use Kendo\Model\OperationCollection;
use Kendo\Model\RoleCollection;

use CLIFramework\Debug\ConsoleDebug;

class ActorRuleEditorViewTest extends DatabaseTestCase
{
    public function getPolicies()
    {
        return [
            new UserSpecificSecurityPolicy,
        ];
    }

    public function testActorEditorView()
    {
        $loader = new SchemaRuleLoader;
        $loader->load($this->policyModule);

        $exporter = new DatabaseRuleImporter($loader);
        $exporter->import();

        $connectionManager = ConnectionManager::getInstance();
        $conn = $connectionManager->getConnection($this->getDriverType());
        $this->assertNotNull($conn);

        $loader = new PDORuleLoader();
        $loader->load($conn);

        $editor = new ActorRuleEditor($loader);
        $editor->loadPermissionSettings(new UserSpecificSecurityPolicy, 'user', 1);

        $view = new ActorRuleEditorView($editor);
        $html = $view->render();
    }
}
