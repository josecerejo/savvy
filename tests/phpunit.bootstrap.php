<?php

use Savvy\Base as Base;
use Doctrine\ORM\Tools\SchemaTool;

require_once(realpath(dirname(__FILE__) . '/../src/Savvy') . '/Base/Bootstrap.php');

$bootstrap = new \Savvy\Base\Bootstrap();

Base\Cache::getInstance()->getCacheProvider()->deleteAll();
