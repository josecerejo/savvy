<?php

namespace Savvy\Runner\GUI;

class PresenterTest extends \PHPUnit_Framework_TestCase
{
    private $testInstance;

    public function setup()
    {
        $this->testInstance = new Presenter();
        $this->testInstance->setValidateSession(false);
    }

    public function testObjectIsInstanceOfPresenter()
    {
        $this->assertInstanceOf('\Savvy\Runner\GUI\Presenter', $this->testInstance);
    }

    /**
     * @expectedException \Savvy\Runner\GUI\Exception
     * @expectedExceptionCode \Savvy\Runner\GUI\Exception::E_RUNNER_GUI_VIEW_NOT_FOUND
     */
    public function testViewNotFoundException()
    {
        $request = new Request();
        $request->setType(Request::TYPE_VIEW);
        $request->setRoute(array('this', 'view', 'does', 'not', 'exist'));

        $this->testInstance->setRequest($request);
        $this->testInstance->dispatch();
    }

    /**
     * @expectedException \Savvy\Runner\GUI\Exception
     * @expectedExceptionCode \Savvy\Runner\GUI\Exception::E_RUNNER_GUI_ACTION_NOT_FOUND
     */
    public function testActionNotFoundException()
    {
        $request = new Request();
        $request->setType(Request::TYPE_ACTION);
        $request->setRoute(array('login', 'this', 'action', 'does', 'not', 'exist'));

        $this->testInstance->setRequest($request);
        $this->testInstance->dispatch();
    }
}
