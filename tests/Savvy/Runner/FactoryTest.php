<?php

namespace Savvy\Runner;

class FactoryTest extends \PHPUnit_Framework_TestCase
{
    private $testInstance;
    private $argv;

    public function setup()
    {
        $this->server = $_SERVER;
    }

    public function teardown()
    {
        $_SERVER = $this->server;
    }

    /**
     * @expectedException \Savvy\Runner\Exception
     * @expectedExceptionCode \Savvy\Runner\Exception::E_RUNNER_FACTORY_UNKNOWN_RUNNER
     * @runInSeparateProcess
     */
    public function testFactoryException()
    {
        unset($_SERVER['argv']);
        $runner = \Savvy\Runner\Factory::getInstance();
    }

    /**
     * @runInSeparateProcess
     */
    public function testFactoryGuiHtmlRunner()
    {
        unset($_SERVER['argv']);
        $_SERVER['REQUEST_URI'] = '/index.php';
        $runner = \Savvy\Runner\Factory::getInstance();
        $this->assertInstanceOf('\Savvy\Runner\GUI\HTML\Runner', $runner);
    }

    /**
     * @runInSeparateProcess
     */
    public function testFactoryConsoleSavvyRunner()
    {
        $_SERVER['argv'] = array('savvy');
        $runner = \Savvy\Runner\Factory::getInstance();
        $this->assertInstanceOf('\Savvy\Runner\Console\Savvy\Runner', $runner);
    }

    /**
     * @runInSeparateProcess
     */
    public function testFactoryConsoleDoctrineRunner()
    {
        $_SERVER['argv'] = array('doctrine');
        $runner = \Savvy\Runner\Factory::getInstance();
        $this->assertInstanceOf('\Savvy\Runner\Console\Doctrine\Runner', $runner);
    }

    /**
     * @runInSeparateProcess
     */
    public function testFactoryDaemonRunner()
    {
        $_SERVER['argv'] = array('savvy', '--daemon');
        $runner = \Savvy\Runner\Factory::getInstance();
        $this->assertInstanceOf('\Savvy\Runner\Daemon\Runner', $runner);
        $this->assertTrue($runner->isSuitable());
    }
}
