<?php

namespace Savvy\Component\Authenticator;

/**
 * Exceptions for authenticator facilities
 *
 * @package Savvy
 * @subpackage Component\Authenticator
 */
class Exception extends \Savvy\Base\AbstractException
{
    /**
     * No authentication facility available
     */
    const E_COMPONENT_AUTHENTICATOR_FACTORY_NO_FACILITY = 33001;

    /**
     * Unknown authentication facility
     */
    const E_COMPONENT_AUTHENTICATOR_FACTORY_UNKNOWN_FACILITY = 33002;
}
