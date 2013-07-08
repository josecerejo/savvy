<?php

namespace Savvy\Runner\Daemon;

use Savvy\Base as Base;

/**
 * Runner for service daemon
 *
 * @package Savvy
 * @subpackage Runner\Daemon
 */
class Runner extends \Savvy\Runner\AbstractRunner
{
    /**
     * Command "quit service daemon"
     */
    const CMD_QUIT = 'quit';

    /**
     * Command "reload all schedules"
     */
    const CMD_RELOAD = 'reload';

    /**
     * Command "trigger scheduler"
     */
    const CMD_TICK = 'tick';

    /**
     * Status "daemon is shutting down"
     */
    const STATUS_QUIT = 0;

    /**
     * Status "daemon is running"
     */
    const STATUS_RUNNING = 1;

    /**
     * Status "daemon is in testing mode"
     */
    const STATUS_TEST = 255;

    /**
     * Current status
     *
     * @var int
     */
    private $status = self::STATUS_RUNNING;

    /**
     * Resource for named pipe
     *
     * @var resource
     */
    private $pipe = null;

    /**
     * Named pipe filename
     *
     * @var string
     */
    private $pipeFilename = null;

    /**
     * Scheduler instance
     *
     * @var \Savvy\Runner\Daemon\Scheduler
     */
    private $scheduler = null;

    /**
     * Returns true if this runner is suitable for current mode of operation
     *
     * @return bool
     */
    public function isSuitable()
    {
        $result = false;

        if (isset($_SERVER['argv']) && is_array($_SERVER['argv'])) {
            if (basename($_SERVER['argv'][0]) === 'savvy' && in_array('--daemon', $_SERVER['argv'])) {
                $result = true;
            }
        }

        return $result;
    }

    /**
     * Get logger instance
     *
     * @return \Savvy\Component\Logger\AbstractLogger
     */
    public function getLogger()
    {
        $loggingFacility = \Savvy\Base\Registry::getInstance()->get('daemon.log');

        if (defined('APPLICATION_MODE') && APPLICATION_MODE === 'test' || in_array('--test', $_SERVER['argv'])) {
            $loggingFacility = 'null';
        }

        return \Savvy\Component\Logger\Factory::getInstance($loggingFacility);
    }

    /**
     * Set running status
     *
     * @param int $status running status
     * @return \Savvy\Runner\Daemon\Runner
     */
    public function setStatus($status)
    {
        if ($this->status !== self::STATUS_TEST) {
            $this->status = (int)$status;
        }

        return $this;
    }

    /**
     * Get running status
     *
     * @return int running status
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Get PID of current process
     *
     * @return int process ID or false if PID file already exists
     */
    public function getPid()
    {
        $result = false;

        if ($pid = (string)posix_getpid()) {
            if (($fr = @fopen($this->getPidFilename(), 'w')) !== false) {
                if (@fwrite($fr, $pid) !== false) {
                    $result = (int)$pid;
                }

                @fclose($fr);
            }
        }

        return $result;
    }

    /**
     * Set PID filename
     *
     * @param string $pidFilename
     * @return \Savvy\Runner\Daemon\Runner
     */
    public function setPidFilename($pidFilename)
    {
        $this->pidFilename = (string)$pidFilename;
        return $this;
    }

    /**
     * Get PID filename
     *
     * @return string
     */
    private function getPidFilename()
    {
        if ($this->pidFilename === null) {
            $this->setPidFilename(
                $this->getParameter('--pidfile', \Savvy\Base\Registry::getInstance()->getFilename('daemon.pid'))
            );
        }

        return $this->pidFilename;
    }

    /**
     * Set named pipe filename
     *
     * @param string $pipeFilename
     * @return \Savvy\Runner\Daemon\Runner
     */
    public function setPipeFilename($pipeFilename)
    {
        $this->pipeFilename = (string)$pipeFilename;
        return $this;
    }

    /**
     * Get named pipe filename
     *
     * @return string
     */
    private function getPipeFilename()
    {
        if ($this->pipeFilename === null) {
            $this->setPipeFilename(
                $this->getParameter('--pipefile', \Savvy\Base\Registry::getInstance()->getFilename('daemon.pipe'))
            );
        }

        return $this->pipeFilename;
    }

    /**
     * Set named pipe resource
     *
     * @param resource $pipe
     * @return \Savvy\Runner\Daemon\Runner
     */
    public function setPipe($pipe)
    {
        $this->pipe = $pipe;
        return $this;
    }

    /**
     * Get named pipe filename
     *
     * @return string
     */
    private function getPipe()
    {
        if ($this->pipe === null) {
            if (posix_mkfifo($this->getPipeFilename(), 0600)) {
                if ($pipe = @fopen($this->getPipeFilename(), 'r+')) {
                    stream_set_blocking($pipe, false);
                    $this->setPipe($pipe);
                }
            }
        }

        return $this->pipe;
    }

    /**
     * Get parameter from command line
     *
     * @param string $name parameter name
     * @param mixed $default default value if given parameter was not specified
     * @return string
     */
    private function getParameter($name, $default = null)
    {
        $result = $default;

        if (is_array($_SERVER['argv'])) {
            foreach ($_SERVER['argv'] as $parameter) {
                if (substr($parameter, 0, strlen($name)) === $name) {
                    $result = trim(substr($parameter, strlen($name) + 1), '"');
                    break;
                }
            }
        }

        return $result;
    }

    /**
     * Read input from named pipe
     *
     * @return string or null if no new input
     */
    private function read()
    {
        $result = null;

        if (is_resource($this->getPipe())) {
            while ($buffer = fread($this->getPipe(), 128)) {
                $result .= $buffer;
            }
        }

        return $result;
    }

    /**
     * Controlled shut down
     *
     * @param mixed $signal
     * @return int exit code / error level
     */
    private function quit($signal = null)
    {
        $this->setStatus(self::STATUS_QUIT);
        $this->getLogger()->write(Base\Language::getInstance()->get('DAEMON\TERMINATED'));

        switch ($signal) {
            case SIGTERM:
            case SIGINT:
            case SIGQUIT:
            case self::CMD_QUIT:
                $result = 0;
                break;
            default:
                $result = 1;
        }

        if (is_resource($this->pipe)) {
            fclose($this->pipe);
        }

        @unlink($this->getPidFilename());
        @unlink($this->getPipeFilename());

        return $result;
    }

    /**
     * Reload command (re-)initializes scheduler
     *
     * @return void
     */
    private function reload()
    {
        if ($this->scheduler === null) {
            $this->scheduler = Scheduler::getInstance();
            $message = Base\Language::getInstance()->get('DAEMON\SCHEDULER_INITIALIZED');
        } else {
            $this->scheduler->init();
            $message = Base\Language::getInstance()->get('DAEMON\SCHEDULER_RELOADED');
        }

        $this->getLogger()->write(sprintf($message, count($this->scheduler->getTasks())));
    }

    /**
     * Handle input received from named pipe
     *
     * @param string $input
     * @return int exit code / error level
     */
    private function handle($input)
    {
        static $commands = array(0 => self::CMD_TICK);
        static $buffer = '';

        $p = 0;
        $result = 0;

        while ($p < strlen($input)) {
            if ($input[$p] === "\n") {
                $commands[] = (string)$buffer;
                $buffer = '';
            } else {
                $buffer .= $input[$p];
            }

            $p++;
        }

        while (empty($commands) === false) {
            $command = array_shift($commands);

            switch (strtolower($command)) {
                case self::CMD_TICK:
                    Scheduler::getInstance()->tick();
                    break;
                case self::CMD_RELOAD:
                    $this->reload();
                    break;
                case self::CMD_QUIT:
                    $result = $this->quit(self::CMD_QUIT);
                    break;
                default:
                    $this->getLogger()->write(
                        sprintf(Base\Language::getInstance()->get('DAEMON\UNKNOWN_COMMAND'), $command),
                        LOG_WARNING
                    );
            }
        }

        return $result;
    }

    /**
     * Daemon main method
     *
     * @return int exit code / error level
     */
    public function run()
    {
        $result = 0;

        if ($pid = $this->getPid()) {
            if (is_resource($this->getPipe()) === false) {
                $this->getLogger()->write(
                    sprintf(Base\Language::getInstance()->get('DAEMON\PIPEFILE_WRITE_ERROR'), $this->getPipeFilename())
                );
                $result = $this->quit();
            } else {
                $this->getLogger()->write(sprintf(Base\Language::getInstance()->get('DAEMON\STARTED'), $pid));
            }
        } else {
            $this->getLogger()->write(
                sprintf(Base\Language::getInstance()->get('DAEMON\PIDFILE_WRITE_ERROR'), $this->getPidFilename())
            );
            $result = $this->quit();
        }

        pcntl_signal(SIGTERM, array(&$this, 'quit'));
        pcntl_signal(SIGQUIT, array(&$this, 'quit'));
        pcntl_signal(SIGINT, array(&$this, 'quit'));
        pcntl_signal(SIGHUP, array(&$this, 'reload'));

        $heartbeat = (60 / intval(\Savvy\Base\Registry::getInstance()->get('daemon.heartbeat'))) * 1000000;
        $this->reload();

        while ($this->getStatus() === self::STATUS_RUNNING) {
            $result = $this->handle($this->read());

            if ($result === 0) {
                usleep($heartbeat);
                pcntl_signal_dispatch();
            }
        }

        return $result;
    }
}
