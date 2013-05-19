<?php

namespace Savvy\Base;

use Doctrine;

/**
 * Daemon interaction and process management
 *
 * @package Savvy
 * @subpackage Base
 */
class Daemon extends AbstractSingleton
{
    /**
     * Daemon's process ID
     *
     * @var int
     */
    protected $pid = false;

    /**
     * Start service daemon
     *
     * @return bool true if daemon was started
     */
    public function start()
    {
        $success = false;

        if ($this->getPid() === false) {
            $parameters = sprintf(
                '--daemon --pidfile="%s" --pipefile="%s"%s',
                Registry::getInstance()->getFilename('daemon.pid'),
                Registry::getInstance()->getFilename('daemon.pipe'),
                constant('APPLICATION_MODE') === 'test' ? ' --test' : ''
            );
            @exec(Registry::getInstance()->get('root') . '/bin/savvy ' . $parameters . ' >/dev/null 2>&1 &');
            sleep(1);
            $success = (bool)$this->getPid();
        }

        return $success;
    }

    /**
     * Stop service daemon
     *
     * @return bool true if daemon was stopped
     */
    public function stop()
    {
        $success = false;

        if (($pid = $this->getPid()) !== false) {
            if ($success = posix_kill($pid, SIGQUIT)) {
                $this->pid = false;
            }
        }

        return $success;
    }

    /**
     * Send SIGHUP command to service daemon to reload schedules
     *
     * @return bool true if reload command was successful
     */
    public function reload()
    {
        $success = false;

        if (($pid = $this->getPid()) !== false) {
            $success = posix_kill($pid, SIGHUP);
        }

        return $success;
    }

    /**
     * Get daemon's process ID
     *
     * @return int process ID or false if daemon is not running
     */
    public function getPid()
    {
        if ($this->pid === false && file_exists(Registry::getInstance()->getFilename('daemon.pid'))) {
            if ($pid = file_get_contents(Registry::getInstance()->getFilename('daemon.pid'))) {
                @exec(sprintf('ps -p %d >/dev/null 2>&1', $pid), $dummy, $result);

                if (intval($result) === 0) {
                    $this->pid = $pid;
                } else {
                    @unlink(Registry::getInstance()->getFilename('daemon.pid'));
                }
            }
        }

        return $this->pid;
    }
}
