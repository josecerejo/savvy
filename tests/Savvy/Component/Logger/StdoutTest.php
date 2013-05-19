<?php

namespace Savvy\Component\Logger;

class StdoutTest extends \PHPUnit_Framework_TestCase
{
    private $testInstance;

    public function setup()
    {
        $this->testInstance = Factory::getInstance('stdout');
    }

    public function testStdoutLoggerIsInstanceOfAbstractLogger()
    {
        $this->assertInstanceOf('\Savvy\Component\Logger\AbstractLogger', $this->testInstance);
    }

    public function testStdoutLoggerIsWorking()
    {
        ob_start();
        $this->testInstance->write("STDOUT logging is working");
        $output = ob_get_contents();
        ob_end_clean();

        $this->assertEquals("STDOUT logging is working\n", $output);
    }
}
