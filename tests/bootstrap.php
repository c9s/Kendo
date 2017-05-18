<?php
use LazyRecord\Schema\SchemaGenerator;
use Maghead\Runtime\Config\FileConfigLoader;
use Maghead\Runtime\Bootstrap;
use LazyRecord\ConnectionManager;
use CLIFramework\Logger;

$loader = require 'vendor/autoload.php';
// Add fallback directory for looking up class files
$loader->add(null, 'tests');

$config = FileConfigLoader::load('config/database.yml');
Bootstrap::setup($config);

$logger = new Logger;
$logger->info("Building schema class files...");

// build schema class files
$schemas = array(
    new \Kendo\Model\ActorSchema,
    new \Kendo\Model\RoleSchema,
    new \Kendo\Model\ResourceSchema,
    new \Kendo\Model\ResourceGroupSchema,
    new \Kendo\Model\OperationSchema,
    new \Kendo\Model\AccessRuleSchema,
);
$g = new SchemaGenerator($config, $logger);
// $g->setForceUpdate(true);
$g->generate($schemas);
