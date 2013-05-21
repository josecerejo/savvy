<?php

namespace Savvy\Component\Logger;

use Savvy\Base as Base;

/**
 * Send logging output to syslog
 *
 * @package Savvy
 * @subpackage Component\Logger
 */
class Syslog extends AbstractLogger
{
    /**
     * Name prefixed to log messages
     *
     * @var string
     */
    protected $name = null;

    /**
     * Set name property
     *
     * @param string $name
     * @return \Savvy\Component\Logger\Syslog
     */
    public function setName($name)
    {
        $this->name = (string)$name;
        return $this;
    }

    /**
     * Get name property
     *
     * @return string
     */
    protected function getName()
    {
        if ($this->name === null) {
            $this->name = Base\Registry::getInstance()->get('name');
        }

        return $this->name;
    }

    /**
     * Write line to logging facility
     *
     * @param string $msg a message to log
     * @param int $priority level of importance
     * @return bool true on success, or false on error
     */
    public function write($msg, $priority = LOG_INFO)
    {
        return syslog($priority, sprintf('%s %s', $this->getName(), $msg));
    }
}
