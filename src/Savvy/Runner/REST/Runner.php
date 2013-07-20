<?php

namespace Savvy\Runner\REST;

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
        header('HTTP/1.1 401 Unauthorized', true, 401);
        $result = 1;

        return $resutl;
    }
}
