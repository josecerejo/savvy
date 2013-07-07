<?php

namespace Savvy\Base;

use Savvy\Component\Task as Task;

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
     * Initialize schedule
     *
     * @return bool
     */
    public function init()
    {
        $this->tasks = array();
        $schedule = Database::getInstance()->getEntityManager()->getRepository('Savvy\Storage\Model\Schedule');

        foreach ($schedule->findByActive(true) as $task) {
            $this->tasks[] = Task\Factory::getInstance($task);
        }

        return true;
    }

    /**
     * Get list of active tasks
     *
     * @return array
     */
    public function getTasks()
    {
        return $this->tasks;
    }
}
