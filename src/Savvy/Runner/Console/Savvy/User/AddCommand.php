<?php

namespace Savvy\Runner\Console\Savvy\User;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command;

/**
 * Create a new user ("savvy user:add")
 *
 * @ignore
 * @package Savvy
 * @subpackage Runner\Console\Savvy
 */
class AddCommand extends Command
{
    /**
     * Command configuration
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('user:add');
        $this->setDescription('Create a new user.');
        $this->addArgument('username', InputArgument::REQUIRED, 'Username for new user account.');
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
        $username = $input->getArgument('username');

        if ($user = $em->getRepository('Savvy\Storage\Model\User')->findOneByUsername($username)) {
            $output->writeln(sprintf("User \"%s\" already exists!", $user->getUsername(), $user->getId()));
        } else {
            $user = new \Savvy\Storage\Model\User;
            $user->setUsername($username);

            $em->persist($user);
            $em->flush();

            $output->writeln(sprintf("Created new user \"%s\" with ID %d.", $user->getUsername(), $user->getId()));
        }
    }
}
