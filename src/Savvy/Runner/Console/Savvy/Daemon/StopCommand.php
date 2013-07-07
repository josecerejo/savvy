<?php

namespace Savvy\Runner\Console\Savvy\Daemon;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command;

/**
 * Shut down service daemon ("savvy daemon:stop")
 *
 * @ignore
 * @package Savvy
 * @subpackage Runner\Console\Savvy
 */
class StopCommand extends Command
{
    /**
     * Command configuration
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('daemon:stop');
        $this->setDescription('Shut down service daemon');
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
            $output->write('Stopping Savvy service daemon... ');

            if ($daemon->stop() === true) {
                $output->writeln('done');
            } else {
                $output->writeln('FAILED');
            }
        }
    }
}
