<?php
use LazyRecord\Schema\SchemaGenerator;
use LazyRecord\ConfigLoader;
use CLIFramework\Logger;

$loader = require 'vendor/autoload.php';
$config = new LazyRecord\ConfigLoader;
$config->load('db/config/database.testing.yml');
$config->init();


$logger = new Logger;
$logger->info("Building schema class files...");

// build schema class files
/*
$schemas = array(
    new \Kendo\Model\AccessControlSchema,
    new \Kendo\Model\AccessResourceSchema,
    new \Kendo\Model\AccessRuleSchema,
);
*/
$schemas = array();
$g = new \LazyRecord\Schema\SchemaGenerator($config, $logger);
$g->setForceUpdate(true);
$g->generate($schemas);
