<?php

namespace Savvy\Runner\GUI\Native;

use Savvy\Runner\GUI as GUI;

/**
 * Runner for native GUI
 *
 * @package Savvy
 * @subpackage Runner\GUI\Native
 */
class Runner extends GUI\AbstractRunner
{
    /**
     * Returns true if this runner is suitable for current mode of operation
     *
     * @return bool
     */
    public function isSuitable()
    {
        return false;
    }

    /**
     * Get request object
     *
     * @return \Savvy\Runner\GUI\Request
     */
    public function getRequest()
    {
        if ($this->request === null) {
            $this->request = new GUI\Request;
        }

        return $this->request;
    }

    /**
     * Send output to client
     *
     * @return int
     */
    public function run()
    {
        echo $this->getPresenter()->dispatch();
        return 0;
    }
}
