<?php

namespace Savvy\Component\Task;

/**
 * Exceptions for scheduler tasks
 *
 * @package Savvy
 * @subpackage Component\Task
 */
class Exception extends \Savvy\Base\AbstractException
{
    /**
     * Invalid cron schedule expression
     */
    const E_COMPONENT_TASK_FACTORY_CRON_EXPRESSION_INVALID = 40001;
}
