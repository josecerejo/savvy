<?php

namespace Savvy\Runner\Console\Doctrine;

use Savvy\Runner\Console as Console;

/**
 * Doctrine command-line utility
 *
 * @package Savvy
 * @subpackage Runner\Console\Doctrine
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
            $result = substr($_SERVER['argv'][0], -8) === 'doctrine';
        }

        return $result;
    }
}
