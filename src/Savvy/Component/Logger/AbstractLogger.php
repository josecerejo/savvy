<?php

namespace Savvy\Component\Logger;

/**
 * Abstract class for logging facilities
 *
 * @package Savvy
 * @subpackage Component\Logger
 */
abstract class AbstractLogger
{
    /**
     * Write line to logging facility
     *
     * @param string $msg a message to log
     * @param int $priority level of importance
     * @return bool true on success, or false on error
     */
    abstract public function write($msg, $priority = LOG_INFO);
}
