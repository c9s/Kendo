<?php
use SimpleApp\SimpleSecurityPolicy;
use SimpleApp\User\NormalUser;

use Kendo\RuleLoader\RuleLoader;
use Kendo\RuleLoader\SchemaRuleLoader;
use Kendo\RuleMatcher\AccessRuleMatcher;

use Kendo\Operation\GeneralOperation;
use Kendo\Authorizer\Authorizer;
use Kendo\SecurityPolicy\SecurityPolicyModule;

use Kendo\RuleImporter\DatabaseRuleImporter;

use Kendo\Model\AccessRuleCollection;
use Kendo\Model\ActorCollection;
use Kendo\Model\ResourceCollection;

use LazyRecord\Testing\ModelTestCase;

use CLIFramework\Debug\ConsoleDebug;
use CLIFramework\Debug\LineIndicator;
use Kendo\Testing\DatabaseTestCase;

class DatabaseRuleImporterTest extends DatabaseTestCase
{
    public $driver = 'sqlite';

    public function testImport()
    {
        $module = new SecurityPolicyModule;
        $module->add(new SimpleSecurityPolicy);

        $loader = new SchemaRuleLoader;
        $loader->load($module);

        $rules = $loader->getAccessRules();

        $importer = new DatabaseRuleImporter();
        $importer->import($loader);

        $rules = new AccessRuleCollection;
        $this->assertCount(10, $rules);

        $actors = new ActorCollection;
        $this->assertCount(3, $actors);
        foreach ($actors as $actor) {
            $this->assertNotEmpty($actor);
            $this->assertNotNull($actor->id);

            // $this->assertNotEmpty($actor->roles);
        }

        $resources = new ResourceCollection;
        $this->assertCount(3, $resources);
    }
}

