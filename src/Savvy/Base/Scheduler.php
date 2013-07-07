<?php

namespace Savvy\Base;

/**
 * Task scheduler
 *
 * @package Savvy
 * @subpackage Base
 */
class Scheduler extends AbstractSingleton
{
    /**
     * Scheduled tasks
     *
     * @var array
     */
    private $tasks = array();

    /**
     * Initialize scheduler
     *
     * @return bool
     */
    public function init()
    {
        $this->reload();
        return true;
    }

    /**
     * Load tasks from schedule table
     * 
     * @return void
     */
    public function reload()
    {
        $this->tasks = array();
    }

    /**
     * Get schedules tasks
     *
     * @return array
     */
    public function getTasks()
    {
        return $this->tasks;
    }
}
