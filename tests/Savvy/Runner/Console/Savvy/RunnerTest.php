<?php

namespace Savvy\Runner\Console\Savvy;

class RunnerTest extends \PHPUnit_Framework_TestCase
{
    private $testInstance;

    public function setup()
    {
        $this->testInstance = new Runner();
    }

    public function testObjectIsInstanceOfRunner()
    {
        $this->assertInstanceOf('\Savvy\Runner\Console\Savvy\Runner', $this->testInstance);
        $this->assertInstanceOf('\Savvy\Runner\Console\AbstractRunner', $this->testInstance);
    }

    /**
     * @runInSeparateProcess
     */
    public function testApplication()
    {
        $input = new \Symfony\Component\Console\Input\ArgvInput(array('savvy', '--quiet'));

        ob_start();
        $this->assertEquals(0, $this->testInstance->run($input));
        $output = ob_get_contents();
        ob_end_clean();
    }
}
