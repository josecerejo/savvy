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

        if (isset($_SERVER['HTTP_APPLICATION_SESSION'])) {
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
        $dtime = new \DateTime('now', new \DateTimeZone(Registry::getInstance()->get('timezone')));

        $_SESSION[$this->getApplicationSessionId()] = array(
            'username'  => (string)$username,
            'keepalive' => (int)$dtime->getTimestamp()
        );

        $em = Database::getInstance()->getEntityManager();

        $user = $em->getRepository('Savvy\Storage\Model\User')->findOneByUsername($username);
        $user->setLastLogin($dtime);
        $em->persist($user);

        $session = new \Savvy\Storage\Model\Session();
        $session->setUser($user);
        $session->setApplicationSessionId($this->getApplicationSessionId());
        $session->setLastKeepalive($dtime);
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
            $applicationSession = &$_SESSION[$this->getApplicationSessionId()];

            if (is_array($applicationSession) && isset($applicationSession['username'])) {
                $result = true;
                $keepalive = new \DateTime('now', new \DateTimeZone(Registry::getInstance()->get('timezone')));
                $timeout = Registry::getInstance()->get('session.timeout');

                if ($timeout > 0 && $keepalive->getTimestamp() - $applicationSession['keepalive'] > $timeout) {
                    $this->quit();
                    $result = false;
                } else {
                    if ($keepalive->getTimestamp() - $applicationSession['keepalive'] > 60) {
                        $em = Database::getInstance()->getEntityManager();
                        $sessions = $em->getRepository('Savvy\Storage\Model\Session');

                        if ($session = $sessions->findOneByApplicationSessionId($this->getApplicationSessionId())) {
                            $session->setLastKeepalive($keepalive);
                            $em->persist($session);
                            $em->flush();
                        } else {
                            $this->quit();
                            $result = false;
                        }
                    }
                }

                if ($result === true) {
                    $applicationSession['keepalive'] = $keepalive->getTimestamp();
                }
            }
        }

        return $result;
    }

    /**
     * Quit session
     *
     * @return void
     */
    public function quit()
    {
        $em = Database::getInstance()->getEntityManager();
        $sessions = $em->getRepository('Savvy\Storage\Model\Session');

        if ($session = $sessions->findOneByApplicationSessionId($this->getApplicationSessionId())) {
            $em->remove($session);
            $em->flush();
        }

        unset($_SESSION[$this->getApplicationSessionId()]);
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
