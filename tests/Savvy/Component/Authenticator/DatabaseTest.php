<?php

namespace Savvy\Component\Authenticator;

use Savvy\Base as Base;
use Savvy\Storage\Model as Model;

class DatabaseTest extends \PHPUnit_Framework_TestCase
{
    private $authentication;
    private $testInstance;
    private $testUser;

    public function setup()
    {
        $this->authentication = Base\Registry::getInstance()->get('authentication');
        Base\Registry::getInstance()->set('authentication', 'database,database');
        $this->testInstance = Factory::getInstance();
        $this->testUser = new Model\User();
        $this->testUser->setUsername('testuser');
        $this->testUser->setPassword(md5('correctpassword'));
        Base\Database::getInstance()->getEntityManager()->persist($this->testUser);
        Base\Database::getInstance()->getEntityManager()->flush();
    }

    public function teardown()
    {
        Base\Registry::getInstance()->set('authentication', $this->authentication);
        Base\Database::getInstance()->getEntityManager()->remove($this->testUser);
        Base\Database::getInstance()->getEntityManager()->flush();
    }

    public function testFactoryReturnsDatabaseAuthenticator()
    {
        $this->assertInstanceOf("Savvy\\Component\\Authenticator\\Database", $this->testInstance);
    }

    public function testDatabaseAuthenticatorWithNonExistingUser()
    {
        $this->assertEquals(false, $this->testInstance->validate('unknownuser', 'somepassword'));
    }

    public function testDatabaseAuthenticatorWithWrongPassword()
    {
        $this->assertEquals(false, $this->testInstance->validate('testuser', 'wrongpassword'));
    }

    public function testDatabaseAuthenticatorWithCorrectPassword()
    {
        $password = md5(md5('correctpassword') . Base\Session::getInstance()->getApplicationSessionId());
        $this->assertEquals(true, $this->testInstance->validate('testuser', $password));
    }
}
