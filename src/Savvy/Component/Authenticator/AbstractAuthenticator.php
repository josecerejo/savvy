<?php

namespace Savvy\Component\Authenticator;

use Savvy\Base as Base;

/**
 * Abstract class for authentication facilities
 *
 * @package Savvy
 * @subpackage Component\Authenticator
 */
abstract class AbstractAuthenticator
{
    /**
     * Username unknown
     */
    const AUTHENTICATION_UNKNOWN = -1;

    /**
     * Authentication failed
     */
    const AUTHENTICATION_FAILURE = 0;

    /**
     * Authentication succeeded
     */
    const AUTHENTICATION_SUCCESS = 1;

    /**
     * Next authenticator instance in chain
     *
     * @var \Savvy\Component\Authenticator\AbstractAuthenticator
     */
    private $successor = null;

    /**
     * Username property
     *
     * @var string
     */
    private $username;

    /**
     * Password property
     *
     * @var string
     */
    private $password;

    /**
     * First authenticator in chain
     *
     * @var bool
     */
    private $first = false;

    /**
     * Set next authenticator instance in chain
     *
     * @param \Savvy\Component\Authenticator\AbstractAuthenticator $successor
     * @return \Savvy\Component\Authenticator\AbstractAuthenticator
     */
    final public function setSuccessor(\Savvy\Component\Authenticator\AbstractAuthenticator $successor = null)
    {
        $this->successor = $successor;
        return $this;
    }

    /**
     * Get next authenticator instance in chain
     *
     * @return \Savvy\Component\Authenticator\AbstractAuthenticator
     */
    final protected function getSuccessor()
    {
        return $this->successor;
    }

    /**
     * Set username property
     *
     * @param string $username
     * @return \Savvy\Component\Authenticator\AbstractAuthenticator
     */
    protected function setUsername($username)
    {
        $this->username = (string)$username;
        return $this;
    }

    /**
     * Get username property
     *
     * @return string
     */
    protected function getUsername()
    {
        return $this->username;
    }

    /**
     * Set password property
     *
     * @param string $password
     * @return \Savvy\Component\Authenticator\AbstractAuthenticator
     */
    protected function setPassword($password)
    {
        $this->password = (string)$password;
        return $this;
    }

    /**
     * Get password property
     *
     * @return string
     */
    protected function getPassword()
    {
        return $this->password;
    }

    /**
     * Mark this authenticator as first in chain
     *
     * @param bool $first
     * @return \Savvy\Component\Authenticator\AbstractAuthenticator
     */
    public function setFirst($first)
    {
        $this->first = (bool)$first;
        return $this;
    }

    /**
     * Get first property
     *
     * @return bool
     */
    protected function getFirst()
    {
        return $this->first;
    }

    /**
     * Validate username and password using chain of authentication methods
     *
     * @param string $username username
     * @param string $password password
     * @return bool authentication result
     */
    final public function validate($username, $password)
    {
        $this->setUsername($username);
        $this->setPassword($password);

        if (($result = $this->authenticate()) === self::AUTHENTICATION_UNKNOWN) {
            if (($authenticator = $this->getSuccessor()) !== null) {
                $result = $authenticator->validate($username, $password);
            }
        }

        return $this->hook($result);
    }

    /**
     * Default post-login hook updates users last_login field after login
     *
     * @param int $result authentication result
     * @return bool
     */
    protected function hook($result)
    {
        if ($this->getFirst() && $result === self::AUTHENTICATION_SUCCESS) {
            $em = Base\Database::getInstance()->getEntityManager();

            $user = $em->getRepository('Savvy\Storage\Model\User')->findOneByUsername($this->getUsername());
            $user->setLastLogin(new \DateTime('now', new \DateTimeZone(Base\Registry::getInstance()->get('timezone'))));

            $em->persist($user);
            $em->flush();
        }

        return ($result === self::AUTHENTICATION_SUCCESS);
    }

    /**
     * Actual authenticator implementation
     *
     * @return int result
     */
    abstract protected function authenticate();
}
