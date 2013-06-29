<?php

namespace Savvy\Component\Logger;

/**
 * Append log messages preceded by date and time to file
 *
 * @package Savvy
 * @subpackage Component\Logger
 */
class File extends AbstractLogger
{
    /**
     * Name of file where log messages get written to
     *
     * @var string
     */
    protected $filename = null;

    /**
     * Set filename property
     *
     * @param string $filename
     * @return \Savvy\Component\Logger\File
     */
    public function setFilename($filename)
    {
        $this->filename = (string)$filename;
        return $this;
    }

    /**
     * Get filename property
     *
     * @return string
     */
    protected function getFilename()
    {
        $filename = $this->filename;

        if (substr($filename, 0, 1) !== '/') {
            $filename = sprintf('%s/%s', \Savvy\Base\Registry::getInstance()->get('root'), $filename);
        }

        return $filename;
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
        $message = sprintf("%s %s\n", $this->getDateTimeString(), $msg);
        return (bool)file_put_contents($this->getFilename(), $message, FILE_APPEND);
    }
}
