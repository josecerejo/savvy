<?php

namespace Savvy\Runner\Console\Savvy\Daemon;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command;

/**
 * Show status of service daemon
 *
 * @ignore
 * @package Savvy
 * @subpackage Runner\Console\Savvy
 */
class StatusCommand extends Command
{
    /**
     * Command configuration
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('daemon:status');
        $this->setDescription('Show status of service daemon.');
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
            $output->writeln(sprintf("Savvy service daemon is up and alive (PID %d).", $daemon->getPid()));
        }
    }
}
