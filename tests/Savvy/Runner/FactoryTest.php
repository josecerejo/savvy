<?php

namespace Savvy\Runner;

class FactoryTest extends \PHPUnit_Framework_TestCase
{
    private $testInstance;
    private $argv;

    public function setup()
    {
        $this->testInstance = new \Savvy\Runner\Factory();
        $this->server = $_SERVER;
    }

    public function teardown()
    {
        $_SERVER = $this->server;
    }

    /**
     * @expectedException \Savvy\Runner\Exception
     * @expectedExceptionCode \Savvy\Runner\Exception::E_RUNNER_FACTORY_UNKNOWN_RUNNER
     */
    public function testFactoryException()
    {
        unset($_SERVER['argv']);
        $this->testInstance->getInstance();
    }

    public function testFactoryGuiHtmlRunner()
    {
        unset($_SERVER['argv']);
        $_SERVER['REQUEST_URI'] = '/index.php';
        $runner = $this->testInstance->getInstance();
        $this->assertInstanceOf('\Savvy\Runner\GUI\HTML\Runner', $runner);
    }

    public function testFactoryConsoleSavvyRunner()
    {
        $_SERVER['argv'] = array('savvy');
        $runner = $this->testInstance->getInstance();
        $this->assertInstanceOf('\Savvy\Runner\Console\Savvy\Runner', $runner);
    }

    public function testFactoryConsoleDoctrineRunner()
    {
        $_SERVER['argv'] = array('doctrine');
        $runner = $this->testInstance->getInstance();
        $this->assertInstanceOf('\Savvy\Runner\Console\Doctrine\Runner', $runner);
    }

    public function testFactoryDaemonRunner()
    {
        $_SERVER['argv'] = array('savvy', '--daemon');
        $runner = $this->testInstance->getInstance();
        $this->assertInstanceOf('\Savvy\Runner\Daemon\Runner', $runner);
        $this->assertTrue($runner->isSuitable());
    }
}
