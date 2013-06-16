<?php

namespace Savvy\Runner;

use Savvy\Base as Base;

/**
 * Factory for runner instances
 *
 * @package Savvy
 * @subpackage Runner
 */
class Factory extends Base\AbstractFactory
{
    /**
     * Runner instance
     *
     * @var \Savvy\Runner\AbstractRunner
     */
    private static $instance = null;

    /**
     * Create apropriate runner instance for current mode of operation
     *
     * @throws \Savvy\Runner\Exception
     * @return \Savvy\Runner\AbstractRunner runner instance containing main program
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            foreach (Base\Registry::getInstance()->get('runner') as $name => $runnerClass) {
                $result = false;

                if (\Doctrine\Common\ClassLoader::classExists($runnerClass)) {
                    try {
                        $runner = new $runnerClass;

                        if ($runner instanceof \Savvy\Runner\AbstractRunner) {
                            if ($result = $runner->isSuitable()) {
                                Base\Registry::getInstance()->set('locale', $runner->getLanguage());
                            }
                        }
                    } catch (\Exception $e) {
                    }
                }

                if ($result === true) {
                    break;
                }
            }

            if ($result === false) {
                throw new Exception($runnerClass, Exception::E_RUNNER_FACTORY_UNKNOWN_RUNNER);
            }

            self::$instance = $runner;
        }

        return self::$instance;
    }
}
