<?php

namespace Savvy\Component\Authenticator;

use Savvy\Base as Base;
use Savvy\Storage\Model as Model;

class DatabaseTest extends \PHPUnit_Framework_TestCase
{
    public static function setUpBeforeClass()
    {
        $em = Base\Database::getInstance()->getEntityManager();

        if ($user = $em->getRepository('Savvy\Storage\Model\User')->findByUsername('testuser')) {
        } else {
            $user = new Model\User();
            $user->setUsername('testuser');
            $user->setPassword(md5('testuser'));

            $em->persist($user);
            $em->flush();
        }
    }

    public function testFactoryReturnsDatabaseAuthenticator()
    {
        Base\Registry::getInstance()->set('authentication', 'database');
        $this->assertInstanceOf("Savvy\\Component\\Authenticator\\Database", Factory::getInstance());
    }

    public function testDatabaseAuthenticatorWithNonExistingUser()
    {
        Base\Registry::getInstance()->set('authentication', 'database,database');
        $this->assertEquals(false, Factory::getInstance()->validate('unknownuser', 'somepassword'));
    }

    public function testDatabaseAuthenticatorWithWrongPassword()
    {
        Base\Registry::getInstance()->set('authentication', 'database');
        $this->assertEquals(false, Factory::getInstance()->validate('testuser', 'wrongpassword'));
    }

    public function testDatabaseAuthenticatorWithCorrectPassword()
    {
        Base\Registry::getInstance()->set('authentication', 'database');
        $password = md5(md5('testuser') . Base\Session::getInstance()->getApplicationSessionId());
        $this->assertEquals(true, Factory::getInstance()->validate('testuser', $password));
    }
}
