<?php

namespace Savvy\Component\Logger;

use Savvy\Base as Base;

/**
 * Abstract class for logging facilities
 *
 * @package Savvy
 * @subpackage Component\Logger
 */
abstract class AbstractLogger
{
    /**
     * Get date/time string prepended to log outputs
     *
     * @return string
     */
    protected function getDateTimeString()
    {
        $dt = new \DateTime('now', new \DateTimeZone(Base\Registry::getInstance()->get('timezone')));
        return $dt->format('Y-m-d H:i:s');
    }

    /**
     * Write line to logging facility
     *
     * @param string $msg a message to log
     * @param int $priority level of importance
     * @return bool true on success, or false on error
     */
    abstract public function write($msg, $priority = LOG_INFO);
}
