<?php

namespace Savvy\Base;

class DaemonTest extends \PHPUnit_Framework_TestCase
{
    private $testInstance;

    public function setup()
    {
        $this->testInstance = \Savvy\Base\Daemon::getInstance();

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

    public function testObjectIsInstanceOfAbstractSingleton()
    {
        $this->assertInstanceOf('\Savvy\Base\AbstractSingleton', $this->testInstance);
    }

    public function testDaemonStarting()
    {
        $this->testInstance->start();

        $this->assertGreaterThan(0, $this->testInstance->getPid());
        $this->assertFileExists($this->testPidfile);
        $this->assertFileExists($this->testPipefile);
    }

    /**
     * @depends testDaemonStarting
     */
    public function testDaemonReloading()
    {
        $this->assertTrue($this->testInstance->reload());
    }

    /**
     * @depends testDaemonStarting
     */
    public function testDaemonStopping()
    {
        $this->testInstance->stop();
        $this->assertEquals(false, $this->testInstance->getPid());
    }

    public function testDaemonCleansUpStalePidFile()
    {
        file_put_contents($this->testPidfile, "0\n");
        $this->assertFileExists($this->testPidfile);
        $this->assertFalse($this->testInstance->getPid());
        $this->assertFalse(file_exists($this->testPidfile));
    }
}
