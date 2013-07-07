<?php

namespace Savvy\Runner\Console\Savvy\User;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command;

/**
 * List all users ("savvy user:list")
 *
 * @ignore
 * @package Savvy
 * @subpackage Runner\Console\Savvy
 */
class ListCommand extends Command
{
    /**
     * Command configuration
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('user:list');
        $this->setDescription('List all users');
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
        $em = \Savvy\Base\Database::getInstance()->getEntityManager();

        foreach ($em->getRepository('Savvy\Storage\Model\User')->findAll() as $user) {
            $output->writeln(sprintf("%d\t%s", $user->getId(), $user->getUsername()));
        }
    }
}
