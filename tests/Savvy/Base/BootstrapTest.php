<?php

namespace Savvy\Base;

class BootstrapTest extends \PHPUnit_Framework_TestCase
{
    private $testInstance;
    private $argv;

    public function setup()
    {
        $this->testInstance = new Bootstrap();
        $this->server = $_SERVER;
    }

    public function teardown()
    {
        $_SERVER = $this->server;
    }

    public function testObjectIsInstanceOfApplication()
    {
        $this->assertInstanceOf('\Savvy\Base\Bootstrap', $this->testInstance);
    }

    public function testRunMethod()
    {
        unset($_SERVER['argv']);
        $_SERVER['REQUEST_URI'] = '/index.php';

        ob_start();
        $this->testInstance->run();
        $output = ob_get_contents();
        ob_end_clean();

        $this->assertTrue(strlen($output) > 0);
    }
}
