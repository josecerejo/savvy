<?php

namespace Savvy\Base;

class SessionTest extends \PHPUnit_Framework_TestCase
{
    private $testInstance;

    public function setup()
    {
        $this->testInstance = Session::getInstance();
    }

    public function testObjectIsInstanceOfSession()
    {
        $this->assertInstanceOf('\Savvy\Base\Session', $this->testInstance);
    }

    public function testSessionInitializationSucceeds()
    {
        $this->assertEquals(true, $this->testInstance->init());
    }

    public function testGeneratingApplicationSessionId()
    {
        $this->assertEquals(40, strlen($this->testInstance->getApplicationSessionId()));
    }
}
