<?php

namespace Savvy\Runner\Daemon;

use Savvy\Base as Base;
use Savvy\Storage\Model as Model;

class SchedulerTest extends \PHPUnit_Framework_TestCase
{
    public function testObjectInheritance()
    {
        $this->assertInstanceOf('\Savvy\Runner\Daemon\Scheduler', Scheduler::getInstance());
        $this->assertInstanceOf('\Savvy\Base\AbstractSingleton', Scheduler::getInstance());
    }

    public function testSchedulerHasOneActiveTask()
    {
        $em = Base\Database::getInstance()->getEntityManager();

        if ($schedule = $em->getRepository('Savvy\Storage\Model\Schedule')->findByTask('Maintenance')) {
        } else {
            $schedule = new Model\Schedule();
            $schedule->setCron('1 * * * *');
            $schedule->setTask('Maintenance');
            $schedule->setEnabled(true);

            $em->persist($schedule);
            $em->flush();
        }

        $scheduler = new Scheduler();
        $scheduler->init();

        $this->assertEquals(1, count($scheduler->getTasks()));
    }
}
