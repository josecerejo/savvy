<?php

namespace Savvy\Component\Logger;

class NullTest extends \PHPUnit_Framework_TestCase
{
    private $testInstance;

    public function setup()
    {
        $this->testInstance = new Null();
    }

    public function testNullLoggerIsInstanceOfAbstractLogger()
    {
        $this->assertInstanceOf('\Savvy\Component\Logger\Null', $this->testInstance);
        $this->assertInstanceOf('\Savvy\Component\Logger\AbstractLogger', $this->testInstance);
    }

    public function testDummyLoggerIsWorking()
    {
        $this->assertTrue($this->testInstance->write('foo', LOG_INFO));
    }
}
