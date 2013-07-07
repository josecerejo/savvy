<?php

namespace Savvy\Runner\Console\Savvy\User;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command;

/**
 * Set password for specified user ("savvy user:passwd")
 *
 * @ignore
 * @package Savvy
 * @subpackage Runner\Console\Savvy
 */
class PasswdCommand extends Command
{
    /**
     * Command configuration
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('user:passwd');
        $this->setDescription('Set password for specified user');
        $this->addArgument('username', InputArgument::REQUIRED, 'Username to set password for.');
        $this->addArgument('password', InputArgument::REQUIRED, 'Password.');
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
        $password = $input->getArgument('password');

        if ($user = $em->getRepository('Savvy\Storage\Model\User')->findOneByUsername($username)) {
            $user->setPassword(md5($password));

            $em->persist($user);
            $em->flush();

            $output->writeln(sprintf("Password for user \"%s\" updated.", $username));
        } else {
            $output->writeln(sprintf("User \"%s\" does not exist.", $username));
        }
    }
}
