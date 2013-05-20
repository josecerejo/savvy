<?php

namespace Savvy\Runner\Daemon;

class RunnerTest extends \PHPUnit_Framework_TestCase
{
    private $testInstance;

    public function setup()
    {
        $this->testInstance = new Runner();

        $this->daemonPidfile = \Savvy\Base\Registry::getInstance()->get('daemon.pid');
        $this->testPidfile = sprintf('%s/phpunit.%s.daemon.pid', sys_get_temp_dir(), uniqid());
        \Savvy\Base\Registry::getInstance()->set('daemon.pid', $this->testPidfile);

        $this->daemonPipefile = \Savvy\Base\Registry::getInstance()->get('daemon.pipe');
        $this->testPipefile = sprintf('%s/phpunit.%s.daemon.pipe', sys_get_temp_dir(), uniqid());
        \Savvy\Base\Registry::getInstance()->set('daemon.pipe', $this->testPipefile);
    }

    public function teardown()
    {
        if (file_exists($this->testPidfile)) {
            unlink($this->testPidfile);
        }

        if (file_exists($this->testPipefile)) {
            unlink($this->testPipefile);
        }

        \Savvy\Base\Registry::getInstance()->set('daemon.pid', $this->daemonPidfile);
        \Savvy\Base\Registry::getInstance()->set('daemon.pipe', $this->daemonPipefile);
    }

    public function testInstanceOfDaemonRunner()
    {
        $this->assertInstanceOf('\Savvy\Runner\Daemon\Runner', $this->testInstance);
        $this->assertInstanceOf('\Savvy\Runner\AbstractRunner', $this->testInstance);
    }

    public function testDaemonHasLoggingDisabled()
    {
        $this->assertInstanceOf('\Savvy\Component\Logger\Null', $this->testInstance->getLogger());
        $this->assertInstanceOf('\Savvy\Component\Logger\AbstractLogger', $this->testInstance->getLogger());
    }

    public function testCreatingPidFileWorks()
    {
        $_SERVER['argv'][] = sprintf('--pidfile=%s', \Savvy\Base\Registry::getInstance()->get('daemon.pid'));
        $this->assertEquals(posix_getpid(), $this->testInstance->getPid());
        $this->assertFileExists(\Savvy\Base\Registry::getInstance()->get('daemon.pid'));
    }

    public function testDaemonQuitsIfCreatingPipeFileFails()
    {
        \Savvy\Base\Registry::getInstance()->set('daemon.pipe', false);
        $this->assertEquals(1, $this->testInstance->run());
    }

    public function testDaemonQuitsIfCreatingPidFileFails()
    {
        \Savvy\Base\Registry::getInstance()->set('daemon.pid');
        $this->testInstance->setStatus(Runner::STATUS_TEST);
        $this->assertEquals(1, $this->testInstance->run());
    }

    public function testDaemonGetsCommandsFromNamedPipe()
    {
        $r = fopen($this->testPipefile, 'x+');
        fwrite($r, "foo\nreload\nquit\n");
        fseek($r, 0);

        $this->testInstance->setPipe($r);
        $this->assertEquals(0, $this->testInstance->run());
    }

    public function testMainMethod()
    {
        $this->testInstance->setStatus(Runner::STATUS_TEST);
        $this->assertEquals(0, $this->testInstance->run());
    }
}
