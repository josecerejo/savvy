<?php

namespace Savvy\Component\Logger;

/**
 * Factory for logger instances
 *
 * @package Savvy
 * @subpackage Component\Logger
 */
class Factory
{
    /**
     * Singleton instances
     *
     * @var array
     */
    private static $instances = array();

    /**
     * Get logger instance
     *
     * @throws \Savvy\Component\Logger\Exception
     * @param string $configuration configuration string
     * @return \Savvy\Component\Logger\AbstractLogger logger instance
     */
    public static function getInstance($configuration = 'null')
    {
        if (isset(self::$instances[$configuration]) === false) {
            $config = explode(':', $configuration);

            $loggerClass = "Savvy\\Component\\Logger\\" . ucfirst(array_shift($config));
            $loggerInstance = null;

            if (\Doctrine\Common\ClassLoader::classExists($loggerClass)) {
                $loggerInstance = new $loggerClass;
            }

            if ($loggerInstance instanceof \Savvy\Component\Logger\AbstractLogger) {
                while ($parameter = array_shift($config)) {
                    list($p, $v) = explode('=', $parameter);

                    $setter = sprintf('set%s', ucfirst($p));
                    $loggerInstance->$setter($v);
                }

                self::$instances[$configuration] = $loggerInstance;
            } else {
                throw new Exception($loggerClass, Exception::E_COMPONENT_LOGGER_FACTORY_UNKNOWN_LOGGER);
            }
        }

        return self::$instances[$configuration];
    }
}
