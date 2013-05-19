<?php

namespace Savvy\Component\Logger;

/**
 * Null logger creates no output
 *
 * @package Savvy
 * @subpackage Component\Logger
 */
class Null extends AbstractLogger
{
    /**
     * Write line to logging facility
     *
     * @param string $msg a message to log
     * @param int $priority level of importance
     * @return bool true on success, or false on error
     */
    public function write($msg, $priority = LOG_INFO)
    {
        return true;
    }
}
