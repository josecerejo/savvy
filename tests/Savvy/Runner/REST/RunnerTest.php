<?php

namespace Savvy\Runner\REST;

use Savvy\Base as Base;
use Savvy\Runner\REST as REST;

class RunnerTest extends \PHPUnit_Framework_TestCase
{
    private $testInstance;

    public function setup()
    {
        $this->testInstance = new Runner();
    }

    public function testObjectIsInstanceOfRunner()
    {
        $this->assertInstanceOf('\Savvy\Runner\REST\Runner', $this->testInstance);
        $this->assertInstanceOf('\Savvy\Runner\AbstractRunner', $this->testInstance);
    }

    /**
     * @runInSeparateProcess
     */
    public function testRunnerWithoutSession()
    {
        $this->assertEquals(false, Base\Session::getInstance()->init());
        $this->assertEquals(1, $this->testInstance->run());
    }

    /**
     * @runInSeparateProcess
     */
    public function testRunner()
    {
        $applicationSessionId = sha1(uniqid('', true));

        $_SERVER['HTTP_APPLICATION_SESSION'] = $applicationSessionId;
        $_SESSION[$applicationSessionId] = array('username' => 'foobar');

        $this->assertEquals(true, Base\Session::getInstance()->init());
        $this->assertEquals(0, $this->testInstance->run());
    }
}
