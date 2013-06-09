<?php

namespace Savvy\Component\Authenticator;

/**
 * Abstract class for authentication facilities
 *
 * @package Savvy
 * @subpackage Component\Authenticator
 */
abstract class AbstractAuthenticator
{
    /**
     * Username unknown, e.g. not yet validated
     */
    const AUTHENTICATION_UNKNOWN = -1;

    /**
     * Authentication failed, e.g. wrong password
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

        return ($result === self::AUTHENTICATION_SUCCESS);
    }

    /**
     * Actual authenticator implementation
     *
     * @return int internal authentication result
     */
    abstract protected function authenticate();
}
