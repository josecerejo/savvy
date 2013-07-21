<?php

namespace Savvy\Runner;

class FactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @runInSeparateProcess
     * @expectedException \Savvy\Runner\Exception
     * @expectedExceptionCode \Savvy\Runner\Exception::E_RUNNER_FACTORY_UNKNOWN_RUNNER
     */
    public function testFactoryException()
    {
        unset($_SERVER['REQUEST_URI']);
        unset($_GET);

        $runnerFactory = new \Savvy\Runner\Factory();
        $runner = $runnerFactory->getInstance();
    }

    public function testFactoryGuiRunner()
    {
        $_SERVER['REQUEST_URI'] = '/index.php';

        $runner = \Savvy\Runner\Factory::getInstance();
        $this->assertInstanceOf('\Savvy\Runner\GUI\Runner', $runner);

        unset($_SERVER['REQUEST_URI']);
    }

    /**
     * @runInSeparateProcess
     */
    public function testFactoryConsoleSavvyRunner()
    {
        $_SERVER['argv'] = array('savvy');

        $runner = \Savvy\Runner\Factory::getInstance();
        $this->assertInstanceOf('\Savvy\Runner\Console\Savvy\Runner', $runner);

        unset($_SERVER['argv']);
    }

    /**
     * @runInSeparateProcess
     */
    public function testFactoryConsoleDoctrineRunner()
    {
        $_SERVER['argv'] = array('doctrine');

        $runner = \Savvy\Runner\Factory::getInstance();
        $this->assertInstanceOf('\Savvy\Runner\Console\Doctrine\Runner', $runner);

        unset($_SERVER['argv']);
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

        unset($_SERVER['argv']);
    }

    /**
     * @runInSeparateProcess
     */
    public function testFactoryRESTRunner()
    {
        $_REQUEST['api'] = '';

        $runner = \Savvy\Runner\Factory::getInstance();
        $this->assertInstanceOf('\Savvy\Runner\REST\Runner', $runner);
        $this->assertTrue($runner->isSuitable());

        unset($_REQUEST['api']);
    }
}
