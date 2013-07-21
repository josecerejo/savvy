<?php

namespace Savvy\Component\Task;

use Savvy\Runner\Daemon as Daemon;
use Savvy\Storage\Model as Model;

class TaskTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \Savvy\Component\Task\Exception
     * @expectedExceptionCode \Savvy\Component\Task\Exception::E_COMPONENT_TASK_FACTORY_TASK_NOT_FOUND
     */
    public function testFactoryThrowsExceptionOnUnknownTasks()
    {
        $schedule = new Model\Schedule();
        $schedule->setCron('* * * * *');
        $schedule->setTask('UnknownTask');
        $schedule->setEnabled(true);

        $task = Factory::getInstance($schedule);
    }

    public function testFactoryTaskDefaultsToUnknownStatus()
    {
        $schedule = new Model\Schedule();
        $schedule->setCron('* * * * *');
        $schedule->setTask('Maintenance');
        $schedule->setEnabled(true);

        $task = Factory::getInstance($schedule);

        $this->assertInstanceof('\Savvy\Component\Task\AbstractTask', $task);
        $this->assertEquals(AbstractTask::RESULT_UNKNOWN, $task->getResult());
    }

    /**
     * @expectedException \Savvy\Component\Task\Exception
     * @expectedExceptionCode \Savvy\Component\Task\Exception::E_COMPONENT_TASK_FACTORY_CRON_EXPRESSION_INVALID
     */
    public function testFactoryThrowsExceptionOnInvalidCronExpressions()
    {
        $schedule = new Model\Schedule();
        $schedule->setCron('garbage');
        $schedule->setTask('Maintenance');
        $schedule->setEnabled(true);

        $task = Factory::getInstance($schedule);
    }
}
