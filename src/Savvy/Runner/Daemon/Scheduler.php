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
     * Set list of enabled tasks
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
     * Get list of enabled tasks
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

        foreach ($em->getRepository('Savvy\Storage\Model\Schedule')->findByEnabled(true) as $schedule) {
            try {
                $tasks[] = Task\Factory::getInstance($schedule);
            } catch (Task\Exception $e) {
            }
        }

        $this->setTimer();
        $this->setTasks($tasks);
    }

    /**
     * Queue overdue tasks
     *
     * @param bool $responsive
     * @return void
     */
    public function tick($responsive = false)
    {
        if ($this->getTimer() === null || (time() - $this->getTimer()) > 59 || $responsive) {
            $this->setTimer(time());

            foreach ($this->getTasks() as $task) {
                if ($task->getCron()->matching($this->getTimer())) {
                    $em = Base\Database::getInstance()->getEntityManager();
                }
            }
        }
    }
}
