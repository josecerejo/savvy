<?php

namespace Savvy\Base;

/**
 * Abstract exception
 *
 * @package Savvy
 * @subpackage Base
 */
class AbstractException extends \Exception
{
    /**
     * Error code messages
     *
     * @param array
     */
    protected $messages = array();

    /**
     * Generate human-readable message from error code
     *
     * @param string $message error message
     * @param int $code error code
     * @param \Exception $previous previous exception
     */
    public function __construct($message = null, $code = null, \Exception $previous = null)
    {
        if ($code !== null) {
            $reflection = new \ReflectionClass($this);
            $reflectionConstants = array_flip($reflection->getConstants());

            if (isset($reflectionConstants[$code])) {
                $message = sprintf($reflectionConstants[$code], $message);
            }
        }

        parent::__construct($message, $code, $previous);
    }
}
