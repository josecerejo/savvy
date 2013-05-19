<?php

namespace Savvy\Component\Logger;

/**
 * Exceptions for logging facilities
 *
 * @package Savvy
 * @subpackage Component\Logger
 */
class Exception extends \Savvy\Base\AbstractException
{
    /**
     * An unknown logging facility was specified
     */
    const E_COMPONENT_LOGGER_FACTORY_UNKNOWN_LOGGER = 31001;
}
