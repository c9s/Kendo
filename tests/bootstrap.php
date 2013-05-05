<?php
$loader = require 'vendor/autoload.php';
$configLoader = new LazyRecord\ConfigLoader;
$configLoader->load( 'config/database.yml');
$configLoader->init();
