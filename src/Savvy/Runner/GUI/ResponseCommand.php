<?php

namespace Savvy\Runner\GUI;

/**
 * GUI response command object
 *
 * @package Savvy
 * @subpackage Runner\GUI
 */
class ResponseCommand
{
    /**
     * Response object
     *
     * @var \Savvy\Runner\GUI\Response
     */
    private $response;

    /**
     * Close window
     *
     * @param string $id window id
     * @return \Savvy\Runner\GUI\ResponseCommand
     */
    public function close($id)
    {
        $this->response->commands[] = array(
            'command'   => 'close',
            'parameter' => array((string)$id)
        );

        return $this;
    }

    /**
     * Reset form contents (clear input)
     *
     * @param string $name form name
     * @return \Savvy\Runner\GUI\ResponseCommand
     */
    public function reset($name)
    {
        $this->response->commands[] = array(
            'command'   => 'reset',
            'parameter' => array((string)$name)
        );

        return $this;
    }

    /**
     * Set input focus
     *
     * @param string $id input field id
     * @return \Savvy\Runner\GUI\ResponseCommand
     */
    public function focus($id)
    {
        $this->response->commands[] = array(
            'command'   => 'focus',
            'parameter' => array((string)$id)
        );

        return $this;
    }

    /**
     * Shake window
     *
     * @param string $id window id
     * @return \Savvy\Runner\GUI\ResponseCommand
     */
    public function shake($id)
    {
        $this->response->commands[] = array(
            'command'   => 'shake',
            'parameter' => array((string)$id)
        );

        return $this;
    }

    /**
     * Open module view
     *
     * @param string $module module name
     * @param string $presenter optional presenter name, defaults to "index"
     * @return \Savvy\Runner\GUI\ResponseCommand
     */
    public function open($module, $presenter = 'index')
    {
        $this->response->commands[] = array(
            'command'   => 'open',
            'parameter' => array((string)$module, (string)$presenter)
        );

        return $this;
    }

    /**
     * Open modal module view
     *
     * @param string $module module name
     * @param string $presenter optional presenter name, defaults to "index"
     * @return \Savvy\Runner\GUI\ResponseCommand
     */
    public function modal($module, $presenter = 'index')
    {
        $this->response->commands[] = array(
            'command'   => 'modal',
            'parameter' => array((string)$module, (string)$presenter)
        );

        return $this;
    }

    /**
     * Quit current user session (logout)
     *
     * @return \Savvy\Runner\GUI\ResponseCommand
     */
    public function quit()
    {
        $this->response->commands[] = array(
            'command'   => 'quit',
            'parameter' => array()
        );

        return $this;
    }

    /**
     * Class constructor requires response object
     *
     * @ignore
     * @param \Savvy\Runner\GUI\Response $response
     */
    public function __construct(\Savvy\Runner\GUI\Response $response)
    {
        $this->response = $response;
    }
}
