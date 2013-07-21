<?php

namespace Savvy\Module\Login\Presenter;

use Savvy\Base as Base;
use Savvy\Storage\Model as Model;
use Savvy\Runner\GUI as GUI;

class IndexPresenterTest extends \PHPUnit_Framework_TestCase
{
    public function setup()
    {
        Base\Registry::getInstance()->set('authentication', 'database');

        $em = Base\Database::getInstance()->getEntityManager();

        if ($user = $em->getRepository('Savvy\Storage\Model\User')->findByUsername('testuser')) {
        } else {
            $user = new Model\User();
            $user->setUsername('testuser');
            $user->setPassword(md5('testuser'));

            $em->persist($user);
        }

        foreach ($em->getRepository('Savvy\Storage\Model\Session')->findAll() as $session) {
            $em->remove($session);
        }

        $em->flush();
    }

    public function testWhetherIndexPresenterIsInstanceOfGUIPresenter()
    {
        $testInstance = new IndexPresenter();
        $this->assertInstanceOf('\Savvy\Runner\GUI\Presenter', $testInstance);
    }

    public function testIndexPresenterValidateActionSucceeds()
    {
        $password = md5(md5('testuser') . Base\Session::getInstance()->getApplicationSessionId());

        $request = new GUI\Request();
        $request->setRoute('login/index?action=validate');
        $request->setForm(array('username' => 'testuser', 'password' => $password));

        $testInstance = new IndexPresenter();
        $testInstance->setRequest($request);

        $response = json_decode($testInstance->dispatch());

        $this->assertInstanceOf('stdClass', $response);
        $this->assertEquals(true, $response->success);
    }

    public function testIndexPresenterValidateActionFails()
    {
        $request = new GUI\Request();
        $request->setRoute('login/index?action=validate');
        $request->setForm(array('username' => 'fakeuser', 'password' => 'totallywrong'));

        $testInstance = new IndexPresenter();
        $testInstance->setRequest($request);

        $response = json_decode($testInstance->dispatch());

        $this->assertInstanceOf('stdClass', $response);
        $this->assertEquals(false, $response->success);
    }
}
