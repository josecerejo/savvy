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
     * @param string $taskName
     * @return \Savvy\Component\Task\AbstractTask
     */
    public static function getInstance($taskName = null)
    {
        $taskInstance = null;

        if ($taskName !== null) {
            $taskClass = sprintf("Savvy\\Component\\Task\\%s", $taskName);

            if (\Doctrine\Common\ClassLoader::classExists($taskClass)) {
                $taskInstance = new $taskClass;
            }
        }

        if ($taskInstance instanceof AbstractTask === false) {
            throw new Exception($taskName, Exception::E_COMPONENT_TASK_FACTORY_TASK_NOT_FOUND);
        }

        return $taskInstance;
    }
}
