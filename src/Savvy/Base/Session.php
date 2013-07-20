<?php

namespace Savvy\Base;

/**
 * Session and authentication management
 *
 * @package Savvy
 * @subpackage Base
 */
class Session extends AbstractSingleton
{
    /**
     * Application session ID
     *
     * @var string
     */
    private $applicationSessionId = null;

    /**
     * Start or resume existing session
     *
     * @return bool true if an existing application session was picked up
     */
    public function init()
    {
        $result = false;

        @session_start();

        if (isset($_SERVER['HTTP_APPLICATION_SESSION'])) {
            $this->setApplicationSessionId($_SERVER['HTTP_APPLICATION_SESSION']);

            if (isset($_SESSION[$_SERVER['HTTP_APPLICATION_SESSION']])) {
                $result = true;
            }
        }

        return $result;
    }

    /**
     * Start session for given user
     *
     * @param string $username
     * @return void
     */
    public function start($username)
    {
        $_SESSION[$this->getApplicationSessionId()] = array(
            'username' => (string)$username
        );

        $em = Database::getInstance()->getEntityManager();

        $user = $em->getRepository('Savvy\Storage\Model\User')->findOneByUsername($username);
        $user->setLastLogin(new \DateTime('now', new \DateTimeZone(Registry::getInstance()->get('timezone'))));

        $em->persist($user);
        $em->flush();
    }

    /**
     * Set application session ID
     *
     * @param string $applicationSessionId
     * @return \Savvy\Base\Session
     */
    private function setApplicationSessionId($applicationSessionId)
    {
        $this->applicationSessionId = (string)$applicationSessionId;
        return $this;
    }

    /**
     * Get current (or create new) application session ID
     *
     * @return string application session ID (SHA1)
     */
    public function getApplicationSessionId()
    {
        if ($this->applicationSessionId === null) {
            $applicationSessionId = null;

            while ($applicationSessionId === null || isset($_SESSION[(string)$applicationSessionId])) {
                $applicationSessionId = sha1(uniqid('', true));
            }

            $this->setApplicationSessionId($applicationSessionId);
        }

        return $this->applicationSessionId;
    }
}
