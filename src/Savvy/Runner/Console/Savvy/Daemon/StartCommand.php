<?php

namespace Savvy\Runner\Console\Savvy\Daemon;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command;

/**
 * Start service daemon ("savvy daemon:start")
 *
 * @ignore
 * @package Savvy
 * @subpackage Runner\Console\Savvy
 */
class StartCommand extends Command
{
    /**
     * Command configuration
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('daemon:start');
        $this->setDescription('Start service daemon');
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

        if ($daemon->getPid() !== false) {
            $output->writeln(
                sprintf(
                    "Savvy service daemon is already running (PID %d)",
                    $daemon->getPid()
                )
            );
        } else {
            $output->write("Starting Savvy service daemon... ");
            $daemon->start();

            if ($daemon->getPid() !== false) {
                $output->writeln(sprintf("done (PID %d)", $daemon->getPid()));
            } else {
                $output->writeln("FAILED");
            }
        }
    }
}
