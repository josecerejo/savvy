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

        $scheduler = Scheduler::getInstance();
        $this->assertEquals(0, count($scheduler->getTasks()));

        $schedule = new Model\Schedule();
        $schedule->setCron('1 * * * *');
        $schedule->setTask('Maintenance');
        $schedule->setEnabled(true);

        $em->persist($schedule);
        $em->flush();

        $scheduler->init();
        $this->assertEquals(1, count($scheduler->getTasks()));

        $schedule = $em->getRepository('Savvy\Storage\Model\Schedule')->findOneByTask('Maintenance');
        $em->remove($schedule);
        $em->flush();
    }
}
