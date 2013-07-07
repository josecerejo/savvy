<?php

namespace Savvy\Component\Task;

use Savvy\Base as Base;

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
     * @param \Savvy\Storage\Model\Schedule $task
     * @return \Savvy\Component\Task\AbstractTask
     */
    public static function getInstance(\Savvy\Storage\Model\Schedule $task = null)
    {
        $instance = null;

        $validationExpression =
            '/^((\*(\/[0-9]+)?)|[0-9\-\,\/]+)\s+((\*(\/[0-9]+)?)|[0-9\-\,\/]+)\s+((\*(\/[0-9]+)?)|' .
            '[0-9\-\,\/]+)\s+((\*(\/[0-9]+)?)|[0-9\-\,\/]+)\s+((\*(\/[0-9]+)?)|[0-9\-\,\/]+)$/i';

        if ($task !== null) {
            if ((bool)preg_match($validationExpression, trim($task->getCron())) === false) {
                throw new Exception($task->getCron(), Exception::E_COMPONENT_TASK_FACTORY_CRON_EXPRESSION_INVALID);
            }

            $taskClass = sprintf("Savvy\\Component\\Task\\%s", $task->getTask());

            if (\Doctrine\Common\ClassLoader::classExists($taskClass)) {
                $instance = new $taskClass;
                $instance->setCron(trim($task->getCron()));
            } else {
                throw new Exception($task->getTask(), Exception::E_COMPONENT_TASK_FACTORY_TASK_NOT_FOUND);
            }
        }

        return $instance;
    }
}
