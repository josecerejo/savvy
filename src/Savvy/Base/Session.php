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
     * @return bool true if we have picked up a valid application session
     */
    public function init()
    {
        @session_start();

        if (isset($_REQUEST['api']) && isset($_SERVER['PHP_AUTH_PW'])) {
            $this->setApplicationSessionId($_SERVER['PHP_AUTH_PW']);
        } elseif (isset($_SERVER['HTTP_APPLICATION_SESSION'])) {
            $this->setApplicationSessionId($_SERVER['HTTP_APPLICATION_SESSION']);
        }

        return $this->valid();
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

        $session = new \Savvy\Storage\Model\Session();
        $session->setUser($user);
        $session->setApplicationSessionId($this->getApplicationSessionId());
        $em->persist($session);

        $em->flush();
    }

    /**
     * Do we have a valid application session?
     *
     * @return bool
     */
    public function valid()
    {
        $result = false;

        if (isset($_SESSION[$this->getApplicationSessionId()])) {
            $applicationSession = $_SESSION[$this->getApplicationSessionId()];

            if (is_array($applicationSession) && isset($applicationSession['username'])) {
                $result = true;
            }
        } elseif (isset($_REQUEST['api'])) {
            $em = Database::getInstance()->getEntityManager();
            $sessions = $em->getRepository('Savvy\Storage\Model\Session');

            if ($session = $sessions->findOneByApplicationSessionId($this->getApplicationSessionId())) {
                if ($session->getUser()->getUsername() === $_SERVER['PHP_AUTH_USER']) {
                    $result = true;
                }
            }
        }

        return $result;
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
