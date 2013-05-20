<?php

require_once(realpath(dirname(__FILE__) . '/../src/Savvy') . '/Base/Bootstrap.php');

$bootstrap = new \Savvy\Base\Bootstrap();

\Savvy\Base\Registry::getInstance()->set('default.log', 'null');
\Savvy\Base\Registry::getInstance()->set('daemon.log', 'null');
