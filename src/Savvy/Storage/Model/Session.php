<?php

namespace Savvy\Storage\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * Session
 */
class Session
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var \Savvy\Storage\Model\User
     */
    private $user;

    /**
     * @var string
     */
    private $applicationSessionId;

    /**
     * Get id
     *
     * @return int 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set user
     *
     * @param \Savvy\Storage\Model\User $user
     * @return Session
     */
    public function setUser(\Savvy\Storage\Model\User $user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Get user
     *
     * @return \Savvy\Storage\Model\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set applicationSessionId
     *
     * @param string $applicationSessionId
     * @return Session
     */
    public function setApplicationSessionId($applicationSessionId)
    {
        $this->applicationSessionId = $applicationSessionId;
        return $this;
    }

    /**
     * Get applicationSessionId
     *
     * @return string 
     */
    public function getApplicationSessionId()
    {
        return $this->applicationSessionId;
    }
}
