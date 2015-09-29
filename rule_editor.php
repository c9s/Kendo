<?php
// Add fallback directory for looking up class files
$loader = require __DIR__ . '/vendor/autoload.php';
$loader->add(null, 'tests');

use LazyRecord\ConnectionManager;
use LazyRecord\ConfigLoader;

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


$config = ConfigLoader::getInstance();
$config->loadFromSymbol(true);
$config->init();

$connectionManager = ConnectionManager::getInstance();
$conn = $connectionManager->getConnection('default');

$module = new SecurityPolicyModule;
$module->add(new UserSpecificSecurityPolicy);

$loader = new SchemaRuleLoader;
$loader->load($module);

$exporter = new DatabaseRuleImporter($loader);
$exporter->import();

$loader = new PDORuleLoader();
$loader->load($conn);

$editor = new ActorRuleEditor(new UserSpecificSecurityPolicy, $loader);
$editor->loadPermissionSettings('user', 1);

$view = new ActorRuleEditorView($editor);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
      <!-- Meta, title, CSS, favicons, etc. -->
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <!-- Latest compiled and minified CSS -->
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">

      <!-- Optional theme -->
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">

      <!-- Latest compiled and minified JavaScript -->
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
      <style>

      .resource-group { 
        
      }
      .resource-group .resource-label {
        font-weight: bold;
        font-size: large;
        display: block;
        padding: 3px 8px;
        background: #e0e0e0;
        color: #000;
        text-shadow: 1px 0 1px #fff;
      }
      .resource-group .resource-group .resource-label {
        font-size: small;
        background: #f0f0f0;
        color: #505050;
        text-shadow: 1px 0 1px #fff;
      }
      .resource-operations {
        color: #666;
        padding-bottom: 12px;
        padding-left: 10px;
        padding-right: 10px;
        box-sizing: border-box;
      }
      .resource-operations .checkbox-inline { }

      </style>
    </head>
    <body>
      <div class="container">
        <form class="form-horizontal">
          <?php echo $view->render(); ?>
        </form>
      </div>
    </body>
</html>
