<?php

namespace Savvy\Module\Login\Presenter;

use Savvy\Runner\GUI as GUI;

class IndexPresenterTest extends \PHPUnit_Framework_TestCase
{
    private $testInstance;

    public function setup()
    {
        $this->testInstance = new IndexPresenter();
    }

    public function testWhetherIndexPresenterIsInstanceOfGUIPresenter()
    {
        $this->assertInstanceOf('\Savvy\Runner\GUI\Presenter', $this->testInstance);
    }

    public function testIndexPresenterValidateAction()
    {
        $request = new GUI\Request();
        $request->setRoute('login/index?action=validate');
        $request->setForm(array('username' => 'admin'));

        $this->testInstance->setRequest($request);
        $this->assertInstanceOf('stdClass', json_decode($this->testInstance->dispatch()));
    }
}
