<?php

namespace Savvy\Runner\Console;

use Savvy\Base as Base;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Helper\HelperSet;

/**
 * Abstract class for console runners
 *
 * @package Savvy
 * @subpackage Runner\Console
 */
abstract class AbstractRunner extends \Savvy\Runner\AbstractRunner
{
    /**
     * helperSet instance
     *
     * @var \Symfony\Component\Console\Helper\HelperSet
     */
    protected $helperSetInstance = null;

    /**
     * Returns true if this runner is suitable for current mode of operation
     *
     * @return bool
     */
    public function isSuitable()
    {
        return isset($_SERVER['argv']) && is_array($_SERVER['argv']);
    }

    /**
     * Get helperSet instance
     *
     * @return \Symfony\Component\Console\Helper\HelperSet
     */
    protected function getHelperSetInstance()
    {
        if ($this->helperSetInstance === null) {
            $entityManager = Base\Database::getInstance()->getEntityManager();

            $this->helperSetInstance = new \Symfony\Component\Console\Helper\HelperSet(
                array(
                  'em' => new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($entityManager)
                )
            );
        }

        return $this->helperSetInstance;
    }

    /**
     * Get commands
     *
     * @return array
     */
    protected function getCommands()
    {
        return array();
    }

    /**
     * Run CLI
     *
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @return int
     */
    final public function run(\Symfony\Component\Console\Input\InputInterface $input = null)
    {
        $cliName = basename($_SERVER['argv'][0]);

        $cli = new Application(
            ucfirst($cliName) . ' Command Line Interface',
            $cliName === 'doctrine' ? \Doctrine\ORM\Version::VERSION : Base\Registry::getInstance()->get('version')
        );

        if (Base\Registry::getInstance()->get('mode') === 'test') {
            $cli->setAutoExit(false);
        }

        $cli->setHelperSet($this->getHelperSetInstance());

        if ($cliName === 'doctrine') {
            \Doctrine\ORM\Tools\Console\ConsoleRunner::addCommands($cli);
        } else {
            $cli->addCommands($this->getCommands());
        }

        return $cli->run($input);
    }
}
