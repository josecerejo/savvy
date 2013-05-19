<?php

namespace Savvy\Base;

/**
 * Abstract singleton
 *
 * @package Savvy
 * @subpackage Base
 */
abstract class AbstractSingleton
{
    /**
     * Singleton instances
     *
     * @var array
     */
    private static $instances = array();

    /**
     * Return a static instance of this class
     *
     * @return \Savvy\Base\AbstractSingleton
     */
    public static function getInstance()
    {
        if (isset(self::$instances[$class = get_called_class()]) === false) {
            self::$instances[$class] = new $class;
            self::$instances[$class]->init();
        }

        return self::$instances[$class];
    }

    /**
     * Class constructor must not be used
     *
     * @ignore
     */
    final public function __construct()
    {
    }

    /**
     * Class must not be cloned
     *
     * @ignore
     * @throws \Savvy\Base\Exception
     */
    final public function __clone()
    {
        throw new Exception(get_called_class(), Exception::E_BASE_SINGLETON_CLONED);
    }

    /**
     * Initialization may throw an exception
     *
     * @throws \Savvy\Base\Exception
     * @return void
     */
    public function init()
    {
    }
}
