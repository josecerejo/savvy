<?php

namespace Savvy\Base;

use Savvy\Storage\Model as Model;

class SchedulerTest extends \PHPUnit_Framework_TestCase
{
    public function testObjectInheritance()
    {
        $this->assertInstanceOf('\Savvy\Base\Scheduler', Scheduler::getInstance());
        $this->assertInstanceOf('\Savvy\Base\AbstractSingleton', Scheduler::getInstance());
    }

    public function testSchedulerHasOneActiveTask()
    {
        $scheduler = Scheduler::getInstance();
        $this->assertEquals(0, count($scheduler->getTasks()));

        $em = Database::getInstance()->getEntityManager();

        $schedule = new Model\Schedule();
        $schedule->setCron('1 * * * *');
        $schedule->setTask('Maintenance');
        $schedule->setActive(true);

        $em->persist($schedule);
        $em->flush();

        $scheduler->init();
        $this->assertEquals(1, count($scheduler->getTasks()));

        $schedule = $em->getRepository('Savvy\Storage\Model\Schedule')->findOneByTask('Maintenance');
        $em->remove($schedule);
        $em->flush();
    }
}
