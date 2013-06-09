<?php

namespace Savvy\Component\Authenticator;

use Savvy\Base as Base;

class FactoryTest extends \PHPUnit_Framework_TestCase
{
    private $authentication;

    public function setup()
    {
        $this->authentication = Base\Registry::getInstance()->get('authentication');
    }

    public function teardown()
    {
        Base\Registry::getInstance()->set('authentication', $this->authentication);
    }

    /**
     * @expectedException \Savvy\Component\Authenticator\Exception
     * @expectedExceptionCode \Savvy\Component\Authenticator\Exception::E_COMPONENT_AUTHENTICATOR_FACTORY_UNKNOWN_FACILITY
     */
    public function testFactoryThrowsExceptionWhenAuthenticatorWasNotFound()
    {
        Base\Registry::getInstance()->set('authentication', 'thisonedoesnotexist');
        Factory::getInstance();
    }

    /**
     * @expectedException \Savvy\Component\Authenticator\Exception
     * @expectedExceptionCode \Savvy\Component\Authenticator\Exception::E_COMPONENT_AUTHENTICATOR_FACTORY_NO_FACILITY
     */
    public function testFactoryThrowsExceptionWhenNoAuthenticatorIsConfigured()
    {
        Base\Registry::getInstance()->set('authentication');
        Factory::getInstance();
    }

    public function testFactoryReturnsInstanceOfAbstractAuthenticator()
    {
        Base\Registry::getInstance()->set('authentication', 'database');
        $this->assertInstanceOf("Savvy\\Component\\Authenticator\\AbstractAuthenticator", Factory::getInstance());
    }

    public function testFactorySupportsAuthenticatorChaining()
    {
        Base\Registry::getInstance()->set('authentication', 'database,database');
        $this->assertInstanceOf("Savvy\\Component\\Authenticator\\AbstractAuthenticator", Factory::getInstance());
    }
}
