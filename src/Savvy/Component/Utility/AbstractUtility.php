<?php

namespace Savvy\Component\Utility;

/**
 * Abstract class for command-line utility wrappers
 *
 * @package Savvy
 * @subpackage Component\Utility
 */
abstract class AbstractUtility
{
    /**
     * Name of command-line executable
     *
     * @var string
     */
    protected $executable = '';

    /**
     * Set name of command-line executable
     *
     * @param string $executable
     * @return \Savvy\Component\Utility\AbstractUtility
     */
    public function setExecutable($executable)
    {
        $this->executable = (string)$executable;
    }

    /**
     * Get name of command-line executable
     *
     * @return string
     */
    public function getExecutable()
    {
        return $this->executable;
    }

    /**
     * Constructor calls configure method
     */
    public function __construct()
    {
        $this->configure();
    }

    /**
     * Configure command-line utility
     *
     * @return void
     */
    abstract protected function configure();

    /**
     * Does executable file exist and can we use it?
     *
     * @return bool
     */
    public function isAvailable()
    {
        $result = false;

        if (@is_executable($this->getExecutable()) === true) {
            $result = true;
        } else {
            @exec("bash -c 'type -p \"" . basename($this->getExecutable()) . "\"'", $output, $error);

            if (($error === 0) && @is_executable($output[0])) {
                  $this->setExecutable($output[0]);
                  $result = true;
            }
        }

        return $result;
    }

    /**
     * Execute binary and return output as array
     *
     * @throws \Savvy\Component\Utility\Exception
     * @param string $parameters
     * @return array
     */
    public function execute($parameters = '')
    {
        $result = array();

        if ((bool)$this->getExecutable() === false) {
            throw new Exception(null, Exception::E_COMPONENT_UTILITY_EXECUTABLE_NOT_DEFINED);
        }

        if ($this->isAvailable() === false) {
            throw new Exception($this->getExecutable(), Exception::E_COMPONENT_UTILITY_EXECUTABLE_NOT_FOUND);
        }

        @exec(sprintf("%s %s", $this->getExecutable(), $parameters), $output, $error);

        if ($error === 0) {
            $result = $output;
        }

        return $result;
    }
}
