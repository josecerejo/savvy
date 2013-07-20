<?php

namespace Savvy\Runner\REST;

use Savvy\Base as Base;

/**
 * Runner for RESTful services
 *
 * @package Savvy
 * @subpackage Runner\REST
 */
class Runner extends \Savvy\Runner\AbstractRunner
{
    /**
     * Returns true if this runner is suitable for current mode of operation
     *
     * @return bool
     */
    public function isSuitable()
    {
        return isset($_REQUEST['api']);
    }

    /**
     * Send output to client
     *
     * @return int
     */
    public function run()
    {
        if (Base\Session::getInstance()->valid()) {
            $result = 0;
            header('HTTP/1.1 200 OK', true, 200);
        } else {
            $result = 1;
            header('HTTP/1.1 401 Unauthorized', true, 401);
        }

        return $result;
    }
}
