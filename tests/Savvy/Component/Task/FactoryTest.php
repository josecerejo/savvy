<?php

namespace Savvy\Component\Task;

use Savvy\Runner\Daemon as Daemon;

class TaskTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \Savvy\Component\Task\Exception
     * @expectedExceptionCode \Savvy\Component\Task\Exception::E_COMPONENT_TASK_FACTORY_TASK_NOT_FOUND
     */
    public function testFactoryThrowsExceptionOnUnknownTasks()
    {
        $task = Factory::getInstance('invalid');
    }

    public function testFactoryTaskDefaultsToUnknownStatus()
    {
        $task = Factory::getInstance('Maintenance');

        $this->assertInstanceof('\Savvy\Component\Task\AbstractTask', $task);
        $this->assertEquals(AbstractTask::RESULT_UNKNOWN, $task->getResult());
    }

    /**
     * @expectedException \Savvy\Component\Task\Exception
     * @expectedExceptionCode \Savvy\Component\Task\Exception::E_COMPONENT_TASK_FACTORY_CRON_EXPRESSION_INVALID
     */
    public function testFactoryThrowsExceptionOnInvalidCronExpressions()
    {
        $task = Factory::getInstance('Maintenance');
        $task->setCron(new Daemon\Cron('invalid'));
    }
}
