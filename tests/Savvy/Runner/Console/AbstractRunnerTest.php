<?php

namespace Savvy\Runner\Console;

class AbstractRunnerTest extends \PHPUnit_Framework_TestCase
{
    private $testInstance;

    public function setup()
    {
        $this->testInstance = $this->getMockForAbstractClass('\Savvy\Runner\Console\AbstractRunner');
    }

    /**
     * @runInSeparateProcess
     */
    public function testBasicConsoleRunnerExecution()
    {
        $input = new \Symfony\Component\Console\Input\ArgvInput(array('savvy', '--quiet'));

        $this->testInstance->expects($this->any())->method('run')->will($this->returnValue(0));
        $this->assertEquals(0, $this->testInstance->run($input));
    }
}
