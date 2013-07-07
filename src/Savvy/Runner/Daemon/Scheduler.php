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
     * Initialize schedule
     *
     * @return bool
     */
    public function init()
    {
        $this->tasks = array();

        $em = Base\Database::getInstance()->getEntityManager();
        $em->clear('Savvy\Storage\Model\Schedule');

        foreach ($em->getRepository('Savvy\Storage\Model\Schedule')->findByActive(true) as $task) {
            try {
                $this->tasks[] = Task\Factory::getInstance($task);
            } catch (Task\Exception $e) {
            }
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
