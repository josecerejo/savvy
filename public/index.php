<?php
set_include_path(get_include_path() . PATH_SEPARATOR . realpath(dirname(__FILE__) . '/../src'));

require_once 'Savvy/Base/Bootstrap.php';

$bootstrap = new \Savvy\Base\Bootstrap();
$bootstrap->run();
