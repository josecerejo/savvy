<?php

namespace Savvy\Runner\GUI;

/**
 * GUI response object
 *
 * @package Savvy
 * @subpackage Runner\GUI
 */
class Response
{
    /**
     * Whether call was successful or not
     *
     * @var bool
     */
    protected $success = false;

    /**
     * Response command list
     *
     * @internal
     */
    public $commands = array();

    /**
     * Set success property
     *
     * @param bool $success
     * @return \Savvy\Runner\GUI\Response
     */
    public function setSuccess($success)
    {
        $this->success = (bool)$success;
        return $this;
    }

    /**
     * Get success property
     *
     * @return bool
     */
    public function getSuccess()
    {
        return $this->success;
    }

    /**
     * Return ResponseCommand object used to add remote procedure calls
     *
     * @return \Savvy\Runner\GUI\ResponseCommand
     */
    public function addCommand()
    {
        return new ResponseCommand($this);
    }

    /**
     * Render response to JSON
     *
     * @return string
     */
    public function render()
    {
        $result = array(
            'success' => $this->getSuccess()
        );

        if (empty($this->commands) === false) {
            $result['rpc'] = $this->commands;
        }

        return json_encode($result);
    }
}
