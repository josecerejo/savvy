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
     * @var bool
     */
    private $active;

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
     * Set active
     *
     * @param boolean $active
     * @return Schedule
     */
    public function setActive($active)
    {
        $this->active = $active;
        return $this;
    }

    /**
     * Get active
     *
     * @return boolean 
     */
    public function getActive()
    {
        return $this->active;
    }
}
