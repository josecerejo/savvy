<?php

namespace Savvy\Base;

/**
 * Global configuration registry
 *
 * @package Savvy
 * @subpackage Base
 */
class Registry extends AbstractSingleton
{
    /**
     * Configuration storage
     *
     * @var array
     */
    private $configuration = array();

    /**
     * Initialize configuration registry
     *
     * @return void
     */
    public function init()
    {
        $rootPath = realpath(dirname(__FILE__) . '/../../..');

        $this->configuration = parse_ini_file($rootPath . '/config/application.ini', true);
        $this->set('root', $rootPath);

        if (defined('APPLICATION_MODE')) {
            $this->set('mode', APPLICATION_MODE);
        }
    }

    /**
     * Store value in configuration registry
     *
     * @param string $name camel-case key name
     * @param string $value key value
     * @return void
     */
    public function set($name, $value = false)
    {
        $this->setKey($this->getPath($name), $value);
    }

    /**
     * Get value of given key from configuration registry
     *
     * @param string $name key name
     * @param string $default default value
     * @return string key value, or false if key is undefined
     */
    public function get($name, $default = false)
    {
        $result = $this->getKey($this->getPath($name));

        if ($result === false) {
            $result = $default;
        }

        return $result;
    }

    /**
     * Get full path and filename from registry setting; if setting doesn't
     * have a trailing slash, precede application root path
     *
     * @param string $name camel-case key name
     * @return string or false if key is not defined
     */
    public function getFilename($name)
    {
        $result = false;

        if ($filename = $this->get($name)) {
            if (substr($filename, 0, 1) !== '/') {
                $filename = sprintf('%s/%s', $this->get('root'), $filename);
            }

            $result = $filename;
        }

        return $result;
    }

    /**
     * Set registry key
     *
     * @param array $path
     * @param string $value
     * @return void
     */
    private function setKey($path, $value)
    {
        $pointer = &$this->configuration;

        foreach ($path as $i => $pathSegment) {
            if ($i < count($path) - 1) {
                $pointer = &$pointer[$pathSegment];
            } else {
                if ($value === false && isset($pointer[$pathSegment])) {
                    unset($pointer[$pathSegment]);
                } else {
                    $pointer[$pathSegment] = $value;
                }
            }
        }
    }

    /**
     * Get key from registry
     *
     * @param array $path path to key
     * @return mixed key value, or false if key is undefined
     */
    private function getKey($path)
    {
        $result = false;
        $pointer = &$this->configuration;

        foreach ($path as $i => $pathSegment) {
            if ($i === count($path) - 1) {
                if (is_array($pointer) && isset($pointer[$pathSegment])) {
                    $result = $pointer[$pathSegment];
                }
            } elseif (isset($pointer[$pathSegment])) {
                $pointer = &$pointer[$pathSegment];
            }
        }

        return $result;
    }

    /**
     * Split configuration key name into path array
     *
     * @param string $name configuration key name
     * @return array path array
     */
    private function getPath($name)
    {
        $path = explode('.', $name);

        if (count($path) === 1 && isset($this->configuration[$path[0]]) === false) {
            array_unshift($path, 'default');
        }

        return $path;
    }
}
