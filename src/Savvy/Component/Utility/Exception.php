<?php

namespace Savvy\Component\Utility;

/**
 * Exceptions for command-line utility wrappers
 *
 * @package Savvy
 * @subpackage Component\Utility
 */
class Exception extends \Savvy\Base\AbstractException
{
    /**
     * No executable binary was specified
     */
    const E_COMPONENT_UTILITY_EXECUTABLE_NOT_DEFINED = 32001;

    /**
     * Specified executable does not exist
     */
    const E_COMPONENT_UTILITY_EXECUTABLE_NOT_FOUND = 32002;
}
