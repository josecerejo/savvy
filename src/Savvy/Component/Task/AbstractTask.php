<?php

namespace Savvy\Component\Task;

/**
 * Abstract class for scheduler tasks
 *
 * @package Savvy
 * @subpackage Component\Task
 */
abstract class AbstractTask
{
    /**
     * Task has not yet started
     */
    const RESULT_UNKNOWN = null;

    /**
     * Task finished without error
     */
    const RESULT_SUCCESS = 0;

    /**
     * Task was not successfully completed
     */
    const RESULT_ERROR_UNKNOWN = 255;

    /**
     * Result property
     *
     * @var int
     */
    protected $result = self::RESULT_UNKNOWN;

    /**
     * Set result code
     *
     * @param int $result
     * @return \Savvy\Component\Task\AbstractTask
     */
    protected function setResult($result)
    {
        $this->result = (int)$result;
        return $this;
    }

    /**
     * Get result
     *
     * @return int task result, see RESULT_* constants
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * Run task
     *
     * @throws \Savvy\Component\Task\Exception
     * @return void
     */
    abstract public function execute();
}
