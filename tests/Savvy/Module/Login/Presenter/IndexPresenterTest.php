<?php

namespace Savvy\Module\Login\Presenter;

use Savvy\Base as Base;
use Savvy\Runner\GUI as GUI;

class IndexPresenterTest extends \PHPUnit_Framework_TestCase
{
    private $testInstance;
    private $authentication;

    public function setup()
    {
        $this->authentication = Base\Registry::getInstance()->get('authentication');
        $this->testInstance = new IndexPresenter();
    }

    public function teardown()
    {
        Base\Registry::getInstance()->set('authentication', $this->authentication);
    }

    public function testWhetherIndexPresenterIsInstanceOfGUIPresenter()
    {
        $this->assertInstanceOf('\Savvy\Runner\GUI\Presenter', $this->testInstance);
    }

    public function testIndexPresenterValidateAction()
    {
        Base\Registry::getInstance()->set('authentication', APPLICATION_MODE);

        $request = new GUI\Request();
        $request->setRoute('login/index?action=validate');
        $request->setForm(array('username' => 'PHPUnit', 'password' => 'PHPUnit'));

        $this->testInstance->setRequest($request);
        $this->assertInstanceOf('stdClass', json_decode($this->testInstance->dispatch()));
    }
}
