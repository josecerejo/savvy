<?php

namespace Savvy\Base;

/**
 * Exceptions for base classes
 *
 * @package Savvy
 * @subpackage Base
 */
class Exception extends AbstractException
{
    /**
     * It is not allowed to clone instances of \Savvy\Base\AbstractSingleton
     */
    const E_BASE_SINGLETON_CLONED = 10001;
}
