<?php

namespace Savvy\Runner\Daemon;

use Savvy\Base as Base;
use Savvy\Component\Task as Task;

/**
 * Task scheduler
 *
 * @package Savvy
 * @subpackage Runner\Daemon
 */
class Scheduler extends Base\AbstractSingleton
{
    /**
     * Scheduled tasks
     *
     * @var array
     */
    private $tasks = array();

    /**
     * Internal timer
     *
     * @var int
     */
    private $timer = null;

    /**
     * Set list of active tasks
     *
     * @param array $tasks
     * @return \Savvy\Runner\Daemon\Scheduler
     */
    protected function setTasks($tasks)
    {
        $this->tasks = $tasks;
        return $this;
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

    /**
     * Set (or reset) internal timer
     *
     * @param int|null $timer
     * @return \Savvy\Runner\Daemon\Scheduler
     */
    protected function setTimer($timer = null)
    {
        $this->timer = $timer;
        return $this;
    }

    /**
     * Get internal timer
     *
     * @return int
     */
    protected function getTimer()
    {
        if ($this->timer === null) {
            $this->setTimer(time());
        }

        return $this->timer;
    }

    /**
     * Initialize scheduler
     *
     * @return void
     */
    public function init()
    {
        $em = Base\Database::getInstance()->getEntityManager();
        $em->clear('Savvy\Storage\Model\Schedule');

        $tasks = array();

        foreach ($em->getRepository('Savvy\Storage\Model\Schedule')->findByActive(true) as $task) {
            try {
                $tasks[] = Task\Factory::getInstance($task->getTask())->setCron(new Cron($task->getCron()));
            } catch (Task\Exception $e) {
            }
        }

        $this->setTimer();
        $this->setTasks($tasks);
    }

    /**
     * Start task(s)
     *
     * @return void
     */
    public function tick()
    {
        $timer = $this->getTimer();

        foreach ($this->getTasks() as $task) {
        }

        $this->setTimer(time());
    }
}
