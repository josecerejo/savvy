<?php

namespace Savvy\Runner\Console\Doctrine;

class RunnerTest extends \PHPUnit_Framework_TestCase
{
    private $testInstance;

    public function setup()
    {
        $this->testInstance = new Runner();
        $this->server = $_SERVER;
    }

    public function teardown()
    {
        $_SERVER = $this->server;
    }

    public function testObjectIsInstanceOfRunner()
    {
        $this->assertInstanceOf('\Savvy\Runner\Console\Doctrine\Runner', $this->testInstance);
        $this->assertInstanceOf('\Savvy\Runner\Console\AbstractRunner', $this->testInstance);
    }

    public function testApplication()
    {
        $argv = array('doctrine', '--quiet');
        $input = new \Symfony\Component\Console\Input\ArgvInput($argv);
        $_SERVER['argv'] = $argv;

        ob_start();
        $this->assertEquals(0, $this->testInstance->run($input));
        $output = ob_get_contents();
        ob_end_clean();
    }
}
