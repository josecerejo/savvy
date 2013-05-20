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
     * @param int $code error code for localized exceptions
     * @param \Exception $previous previous exception
     */
    public function __construct($message = null, $code = null, \Exception $previous = null)
    {
        $messageLocalized = false;

        if ($code !== null) {
            $reflection = new \ReflectionClass($this);
            $reflectionConstants = array_flip($reflection->getConstants());

            if (isset($reflectionConstants[$code])) {
                $messageLocalized = Language::getInstance()->get("EXCEPTION\\" . $reflectionConstants[$code]);
            }

            if ((bool)$messageLocalized) {
                $message = sprintf($messageLocalized, $message);
            } else {
                $message = sprintf('Unknown exception code %d', $code);
            }
        }

        $message .= sprintf(' (%s:%d)', $this->getFile(), $this->getLine());

        if ($loggerConfiguration = Registry::getInstance()->get('default.log')) {
            $logger = \Savvy\Component\Logger\Factory::getInstance($loggerConfiguration);
            $logger->write($message, LOG_ERR);
        }

        parent::__construct($message, $code, $previous);
    }
}
