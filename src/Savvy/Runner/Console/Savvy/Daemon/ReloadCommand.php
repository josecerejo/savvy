<?php

namespace Savvy\Runner\Console\Savvy\Daemon;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command;

/**
 * Send SIGHUP/reload command to service daemon
 *
 * @ignore
 * @package Savvy
 * @subpackage Runner\Console\Savvy
 */
class ReloadCommand extends Command
{
    /**
     * Command configuration
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('daemon:reload');
        $this->setDescription('Reload schedules and configuration.');
    }

    /**
     * Main method
     *
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $daemon = \Savvy\Base\Daemon::getInstance();

        if ($daemon->getPid() === false) {
            $output->writeln('Savvy service daemon is NOT running!');
        } else {
            $output->write('Reloading Savvy service daemon... ');

            if ($daemon->reload() === true) {
                $output->writeln('done');
            } else {
                $output->writeln('FAILED');
            }
        }
    }
}
