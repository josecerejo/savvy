<?php

namespace Savvy\Component\Task;

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
    }
}
