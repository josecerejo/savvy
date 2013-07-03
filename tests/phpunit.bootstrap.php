<?php

use Savvy\Base as Base;
use Doctrine\ORM\Tools\SchemaTool;

require_once(realpath(dirname(__FILE__) . '/../src/Savvy') . '/Base/Bootstrap.php');

$bootstrap = new \Savvy\Base\Bootstrap();

// disable logging facilities
Base\Registry::getInstance()->set('default.log', 'null');
Base\Registry::getInstance()->set('daemon.log', 'null');

// prepare SQLite in-memory database for testing
Base\Registry::getInstance()->set('database.driver', 'pdo_sqlite');
Base\Registry::getInstance()->set('database.memory', '1');
Base\Registry::getInstance()->set('doctrine.auto_generate_proxy_classes', '1');
Base\Registry::getInstance()->set('doctrine.auto_generate_schema', '1');

// reset possible cached contents
Base\Cache::getInstance()->getCacheProvider()->deleteAll();
