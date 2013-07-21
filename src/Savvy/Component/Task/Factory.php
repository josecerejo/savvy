<?php

namespace Savvy\Component\Task;

use Savvy\Base as Base;
use Savvy\Runner\Daemon as Daemon;

/**
 * Factory for scheduler task instances
 *
 * @package Savvy
 * @subpackage Component\Task
 */
class Factory extends Base\AbstractFactory
{
    /**
     * Create task instance from given schedule entity
     *
     * @throws \Savvy\Component\Task\Exception
     * @param \Savvy\Storage\Model\Schedule $schedule
     * @return \Savvy\Component\Task\AbstractTask
     */
    public static function getInstance(\Savvy\Storage\Model\Schedule $schedule = null)
    {
        $taskInstance = null;

        if ($schedule !== null) {
            $taskClass = sprintf("Savvy\\Component\\Task\\%s", $schedule->getTask());

            if (\Doctrine\Common\ClassLoader::classExists($taskClass)) {
                $taskInstance = new $taskClass;
                $taskInstance->setCron(new Daemon\Cron($schedule->getCron()));
            }
        }

        if ($taskInstance instanceof AbstractTask === false) {
            throw new Exception($schedule->getTask(), Exception::E_COMPONENT_TASK_FACTORY_TASK_NOT_FOUND);
        }

        return $taskInstance;
    }
}
