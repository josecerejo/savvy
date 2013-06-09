<?php

namespace Savvy\Base;

use Doctrine;

/**
 * Application bootstrapper
 *
 * @package Savvy
 * @subpackage Base
 */
class Bootstrap
{
    /**
     * Namespaces and corresponding class paths
     *
     * @var array
     */
    private $classLoaders = array(
        'Savvy'    => '/src',
        'Storage'  => '/src/Savvy',
        'Proxy'    => '/tmp'
    );

    /**
     * Initialize class loader
     */
    public function __construct()
    {
        require_once(($rootPath = realpath(dirname(__FILE__) . '/../../..')) . '/vendor/autoload.php');

        foreach ($this->classLoaders as $namespace => $classPath) {
            $classLoader = new \Doctrine\Common\ClassLoader($namespace, $rootPath . $classPath);
            $classLoader->register();
        }
    }

    /**
     * Create appropriate runner instance and start main program
     *
     * @return int exit code
     */
    public function run()
    {
        return \Savvy\Runner\Factory::getInstance()->run();
    }
}
