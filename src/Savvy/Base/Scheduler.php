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

        $em = Database::getInstance()->getEntityManager();
        $em->clear('Savvy\Storage\Model\Schedule');

        $schedules = $em->getRepository('Savvy\Storage\Model\Schedule');

        foreach ($schedules->findByActive(true) as $task) {
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
