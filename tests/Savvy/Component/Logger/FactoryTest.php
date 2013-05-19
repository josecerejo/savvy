<?php

namespace Savvy\Component\Logger;

class FactoryTest extends \PHPUnit_Framework_TestCase
{
    private $testInstance;

    public function setup()
    {
        $this->testInstance = new Factory();
    }

    public function testFactoryReturnsNullLoggerAsDefault()
    {
        $this->assertInstanceOf('\Savvy\Component\Logger\Null', $this->testInstance->getInstance());
    }

    /**
     * @expectedException \Savvy\Component\Logger\Exception
     * @expectedExceptionCode \Savvy\Component\Logger\Exception::E_COMPONENT_LOGGER_FACTORY_UNKNOWN_LOGGER
     */
    public function testFactoryThrowsExceptionWhenUnknownLoggerIsRequested()
    {
        $this->assertInstanceOf('\Savvy\Component\Logger\AbstractLogger', $this->testInstance->getInstance(serialize(array('type' => 'foobar'))));
    }
}
