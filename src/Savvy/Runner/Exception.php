<?php

namespace Savvy\Runner;

/**
 * Exceptions for namespace \Savvy\Runner
 *
 * @package Savvy
 * @subpackage Runner
 */
class Exception extends \Savvy\Base\AbstractException
{
    /**
     * Failed to create apropriate runner instance
     */
    const E_RUNNER_FACTORY_UNKNOWN_RUNNER = 20001;
}
