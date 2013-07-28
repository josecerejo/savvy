<?php

namespace Savvy\Runner\REST;

use Savvy\Base as Base;
use Savvy\Runner\REST as REST;

class RunnerTest extends \PHPUnit_Framework_TestCase
{
    public function testObjectIsInstanceOfRunner()
    {
        $testInstance = new Runner();

        $this->assertInstanceOf('\Savvy\Runner\REST\Runner', $testInstance);
        $this->assertInstanceOf('\Savvy\Runner\AbstractRunner', $testInstance);
    }

    /**
     * @runInSeparateProcess
     */
    public function testRunnerWithoutSession()
    {
        $testInstance = new Runner();

        $this->assertEquals(false, Base\Session::getInstance()->init());
        $this->assertEquals(1, $testInstance->run());
    }

    public function testRunner()
    {
        $testInstance = new Runner();

        $applicationSessionId = sha1(uniqid('', true));

        $_SERVER['HTTP_APPLICATION_SESSION'] = $applicationSessionId;
        $_SESSION[$applicationSessionId] = array('username' => 'foobar', 'keepalive' => time());

        $this->assertEquals(true, Base\Session::getInstance()->init());
        $this->assertEquals(0, $testInstance->run());
    }
}
