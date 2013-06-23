<?php

namespace Savvy\Runner\REST;

use Savvy\Runner\REST as REST;

class RunnerTest extends \PHPUnit_Framework_TestCase
{
    private $testInstance;

    public function setup()
    {
        $this->testInstance = new Runner();
    }

    public function testObjectIsInstanceOfRunner()
    {
        $this->assertInstanceOf('\Savvy\Runner\REST\Runner', $this->testInstance);
        $this->assertInstanceOf('\Savvy\Runner\AbstractRunner', $this->testInstance);
    }

    /**
     * @runInSeparateProcess
     */
    public function testRunner()
    {
        $this->assertEquals(1, $this->testInstance->run());
    }
}
