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
     * @return bool true if existing session was resumed
     */
    public function init()
    {
        $result = @session_start();

        if (empty($_SESSION) === false) {
            $sessions = array_keys($_SESSION);
            $this->setApplicationSessionId($sessions[0]);
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
            $_SESSION[(string)$applicationSessionId] = array();
        }

        return $this->applicationSessionId;
    }
}
