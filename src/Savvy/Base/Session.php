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

        return $result;
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

            while ($applicationSessionId === null || isset($_SESSION[$applicationSessionId])) {
                $applicationSessionId = sha1(uniqid('', true));
            }

            $this->applicationSessionId = $applicationSessionId;
        }

        return $this->applicationSessionId;
    }
}
