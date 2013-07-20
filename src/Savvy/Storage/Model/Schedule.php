<?php

namespace Savvy\Storage\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * Schedule
 */
class Schedule
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $cron;

    /**
     * @var string
     */
    private $task;

    /**
     * @var boolean
     */
    private $enabled;

    /**
     * @var \DateTime
     */
    private $lastRun;

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
     * Set cron
     *
     * @param string $cron
     * @return Schedule
     */
    public function setCron($cron)
    {
        $this->cron = $cron;
        return $this;
    }

    /**
     * Get cron
     *
     * @return string 
     */
    public function getCron()
    {
        return $this->cron;
    }

    /**
     * Set task
     *
     * @param string $task
     * @return Schedule
     */
    public function setTask($task)
    {
        $this->task = $task;
        return $this;
    }

    /**
     * Get task
     *
     * @return string 
     */
    public function getTask()
    {
        return $this->task;
    }

    /**
     * Set enabled
     *
     * @param boolean $enabled
     * @return Schedule
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
    
        return $this;
    }

    /**
     * Get enabled
     *
     * @return boolean 
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * Set lastRun
     *
     * @param \DateTime $lastRun
     * @return Schedule
     */
    public function setLastRun($lastRun)
    {
        $this->lastRun = $lastRun;
    
        return $this;
    }

    /**
     * Get lastRun
     *
     * @return \DateTime 
     */
    public function getLastRun()
    {
        return $this->lastRun;
    }
}
