<?php

namespace Savvy\Component\Utility;

class AbstractUtilityTest extends \PHPUnit_Framework_TestCase
{
    private $testInstance;

    public function setup()
    {
        $this->testInstance = $this->getMockForAbstractClass('\Savvy\Component\Utility\AbstractUtility');
    }

    public function testExecutableIsAvailable()
    {
        $this->testInstance->expects($this->any())->method('isAvailable')->will($this->returnValue(false));
        $this->assertFalse($this->testInstance->isAvailable());
    }

    /**
     * @expectedException \Savvy\Component\Utility\Exception
     * @expectedExceptionCode \Savvy\Component\Utility\Exception::E_COMPONENT_UTILITY_EXECUTABLE_NOT_DEFINED
     */
    public function testExceptionExecutableNotDefined()
    {
        $this->testInstance->expects($this->any())->method('execute')->will($this->returnValue(array()));
        $this->testInstance->execute();
    }

    /**
     * @expectedException \Savvy\Component\Utility\Exception
     * @expectedExceptionCode \Savvy\Component\Utility\Exception::E_COMPONENT_UTILITY_EXECUTABLE_NOT_FOUND
     */
    public function testExceptionExecutableNotFound()
    {
        $this->testInstance->expects($this->any())->method('execute')->will($this->returnValue(array()));
        $this->testInstance->setExecutable('ThisBinaryDoesNotExistForSure');
        $this->testInstance->execute();
    }

    public function testSuccessfulExecution()
    {
        $this->testInstance->expects($this->any())->method('execute')->will($this->returnValue(array()));
        $this->testInstance->setExecutable('/bin/false');

        $this->assertTrue($this->testInstance->isAvailable());
        $this->assertEquals(array(), $this->testInstance->execute());
    }
}
