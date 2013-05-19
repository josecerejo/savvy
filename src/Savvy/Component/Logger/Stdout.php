<?php

namespace Savvy\Component\Logger;

/**
 * Send logging output to STDOUT
 *
 * @package Savvy
 * @subpackage Component\Logger
 */
class Stdout extends AbstractLogger
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
        echo $msg . "\n";
        return true;
    }
}
