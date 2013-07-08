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
     * Cron schedule, e.g. "30 23 * * *"
     *
     * @var string
     */
    protected $cron = '';

    /**
     * Start time of next run
     *
     * @var int
     */
    protected $start = null;

    /**
     * Result property
     *
     * @var int
     */
    protected $result;

    /**
     * Initialize task
     */
    public function __construct()
    {
        $this->setResult(self::RESULT_UNKNOWN);
    }

    /**
     * Set cron schedule
     *
     * @param string $cron
     * @return \Savvy\Component\Task\AbstractTask
     */
    public function setCron($cron)
    {
        $this->cron = (string)$cron;
        return $this;
    }

    /**
     * Calculate start time of next run from cron schedule definition
     *
     * @param int $timer
     * @return \Savvy\Component\Task\AbstractTask
     */
    public function calculateStart($timer)
    {
        $this->start = (int)$timer;
        return $this;
    }

    /**
     * Get start time of next run
     *
     * @return int
     */
    public function getStart()
    {
        return $this->start;
    }

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
     * Get result code
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
