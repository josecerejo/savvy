<?php

namespace Savvy\Base;

use Savvy\Storage\Model as Model;

class SchedulerTest extends \PHPUnit_Framework_TestCase
{
    private $testInstance;
    private $testSchedule;

    public function setup()
    {
        $this->testInstance = Scheduler::getInstance();

        $this->testSchedule = new Model\Schedule();
        $this->testSchedule->setCron('* * * * *');
        $this->testSchedule->setTask('Maintenance');
        $this->testSchedule->setActive(true);

        Database::getInstance()->getEntityManager()->persist($this->testSchedule);
        Database::getInstance()->getEntityManager()->flush();
    }

    public function teardown()
    {
        Database::getInstance()->getEntityManager()->remove($this->testSchedule);
        Database::getInstance()->getEntityManager()->flush();
    }

    public function testObjectInheritance()
    {
        $this->assertInstanceOf('\Savvy\Base\Scheduler', $this->testInstance);
        $this->assertInstanceOf('\Savvy\Base\AbstractSingleton', $this->testInstance);
    }

    public function testSchedulerHasOneActiveTask()
    {
        $this->testInstance->init();
        $this->assertEquals(1, count($this->testInstance->getTasks()));
    }
}
