<?php

namespace Savvy\Component\Task;

use Savvy\Runner\Daemon as Daemon;

class TaskTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \Savvy\Component\Task\Exception
     * @expectedExceptionCode \Savvy\Component\Task\Exception::E_COMPONENT_TASK_FACTORY_CRON_EXPRESSION_INVALID
     */
    public function testFactoryThrowsExceptionOnInvalidCronExpressions()
    {
        $schedule = new \Savvy\Storage\Model\Schedule;
        $schedule->setCron('wrong');
        $schedule->setTask('Maintenance');

        $task = Factory::getInstance($schedule);
        $task->setCron(new Daemon\Cron($schedule->getCron()));
    }

    /**
     * @expectedException \Savvy\Component\Task\Exception
     * @expectedExceptionCode \Savvy\Component\Task\Exception::E_COMPONENT_TASK_FACTORY_TASK_NOT_FOUND
     */
    public function testFactoryThrowsExceptionOnUnknownTasks()
    {
        $schedule = new \Savvy\Storage\Model\Schedule;
        $schedule->setCron('* * * * *');
        $schedule->setTask('foobar');

        $task = Factory::getInstance($schedule);
    }

    public function testFactoryTaskDefaultsToUnknownStatus()
    {
        $schedule = new \Savvy\Storage\Model\Schedule;
        $schedule->setCron('* * * * *');
        $schedule->setTask('Maintenance');

        $task = Factory::getInstance($schedule);

        $this->assertInstanceof('\Savvy\Component\Task\AbstractTask', $task);
        $this->assertEquals(AbstractTask::RESULT_UNKNOWN, $task->getResult());
    }
}
