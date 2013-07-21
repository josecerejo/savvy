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
     * @var int
     */
    private $userId;

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
     * Set userId
     *
     * @param int $userId
     * @return Session
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
        return $this;
    }

    /**
     * Get userId
     *
     * @return int 
     */
    public function getUserId()
    {
        return $this->userId;
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
