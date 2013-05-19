<?php

namespace Savvy\Runner\GUI;

/**
 * Exceptions for GUI runners
 *
 * @package Savvy
 * @subpackage Runner\GUI
 */
class Exception extends \Savvy\Base\AbstractException
{
    /**
     * Requested view not found
     */
    const E_RUNNER_GUI_VIEW_NOT_FOUND = 21001;

    /**
     * Requested action not found
     */
    const E_RUNNER_GUI_ACTION_NOT_FOUND = 21002;
}
