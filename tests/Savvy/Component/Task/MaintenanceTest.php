<?php

namespace Savvy\Component\Task;

class MaintenanceTest extends \PHPUnit_Framework_TestCase
{
    protected $testInstance;

    public function setup()
    {
        $this->testInstance = new Maintenance();
    }

    public function testRun()
    {
        $this->testInstance->run();
        $this->assertEquals(AbstractTask::RESULT_SUCCESS, $this->testInstance->getResult());
    }
}
