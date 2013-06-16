<?php

namespace Savvy\Module\Login\Presenter;

use Savvy\Base as Base;
use Savvy\Storage\Model as Model;
use Savvy\Runner\GUI as GUI;

class IndexPresenterTest extends \PHPUnit_Framework_TestCase
{
    private $authentication;
    private $testInstance;
    private $testUser;

    public function setup()
    {
        $this->authentication = Base\Registry::getInstance()->get('authentication');
        Base\Registry::getInstance()->set('authentication', 'database');
        $this->testInstance = new IndexPresenter();

        $this->testUser = new Model\User();
        $this->testUser->setUsername('testuser');
        $this->testUser->setPassword(null);

        Base\Database::getInstance()->getEntityManager()->persist($this->testUser);
        Base\Database::getInstance()->getEntityManager()->flush();
    }

    public function teardown()
    {
        Base\Registry::getInstance()->set('authentication', $this->authentication);

        Base\Database::getInstance()->getEntityManager()->remove($this->testUser);
        Base\Database::getInstance()->getEntityManager()->flush();
    }

    public function testWhetherIndexPresenterIsInstanceOfGUIPresenter()
    {
        $this->assertInstanceOf('\Savvy\Runner\GUI\Presenter', $this->testInstance);
    }

    public function testIndexPresenterValidateActionSucceeds()
    {
        $request = new GUI\Request();
        $request->setRoute('login/index?action=validate');
        $request->setForm(array('username' => 'testuser', 'password' => ''));

        $this->testInstance->setRequest($request);
        $response = json_decode($this->testInstance->dispatch());

        $this->assertInstanceOf('stdClass', $response);
        $this->assertEquals(true, $response->success);
    }

    public function testIndexPresenterValidateActionFails()
    {
        $request = new GUI\Request();
        $request->setRoute('login/index?action=validate');
        $request->setForm(array('username' => 'fakeuser', 'password' => 'totallywrong'));

        $this->testInstance->setRequest($request);
        $response = json_decode($this->testInstance->dispatch());

        $this->assertInstanceOf('stdClass', $response);
        $this->assertEquals(false, $response->success);
    }
}
