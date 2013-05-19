<?php

namespace Savvy\Runner\GUI\HTML;

use Savvy\Runner\GUI as GUI;

class RequestTest extends \PHPUnit_Framework_TestCase
{
    private $testInstance;

    public function setup()
    {
        $this->testInstance = new GUI\Request();
    }

    public function testObjectIsInstanceOfRequest()
    {
        $this->assertInstanceOf('\Savvy\Runner\GUI\Request', $this->testInstance);
    }

    public function testRequestTypeSetterAndGetter()
    {
        $this->assertEquals(null, $this->testInstance->getType());
        $this->assertEquals(1, $this->testInstance->setType(1)->getType());
    }

    public function testRequestRouteSetterAndGetter()
    {
        $this->assertEquals(array('login', 'index'), $this->testInstance->getRoute());
        $this->assertEquals(array('test', 'index'), $this->testInstance->setRoute('/test/index')->getRoute());
    }

    public function testRequestGetForm()
    {
        $this->assertEquals(array(), $this->testInstance->getForm());
        $this->assertEquals(false, $this->testInstance->getForm('doesNotExistsForSureDude'));
    }
}
