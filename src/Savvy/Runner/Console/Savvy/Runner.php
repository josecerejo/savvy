<?php

namespace Savvy\Runner\Console\Savvy;

use Savvy\Runner\Console as Console;

/**
 * Savvy command-line utility
 *
 * @package Savvy
 * @subpackage Runner\Console\Savvy
 */
class Runner extends Console\AbstractRunner
{
    /**
     * Returns true if this runner is suitable for current mode of operation
     *
     * @return bool
     */
    public function isSuitable()
    {
        $result = false;

        if (parent::isSuitable() === true) {
            $result = substr($_SERVER['argv'][0], -5) === 'savvy';
        }

        return $result;
    }

    /**
     * Get commands
     *
     * @return array
     */
    protected function getCommands()
    {
        $commands = array(
            new Daemon\ReloadCommand(),
            new Daemon\StartCommand(),
            new Daemon\StatusCommand(),
            new Daemon\StopCommand(),
            new User\ListCommand(),
            new User\AddCommand()
        );

        return $commands;
    }
}
