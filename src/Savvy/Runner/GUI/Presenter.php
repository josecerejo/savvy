<?php

namespace Savvy\Runner\GUI;

use Savvy\Base as Base;

/**
 * GUI presenter object
 *
 * @package Savvy
 * @subpackage Runner\GUI
 */
class Presenter
{
    /**
     * Request object
     *
     * @var \Savvy\Runner\GUI\Request
     */
    protected $request = null;

    /**
     * Response object
     *
     * @var \Savvy\Runner\GUI\Response
     */
    protected $response;

    /**
     * Class constructor creates empty (unsuccessful) default response object
     */
    public function __construct()
    {
        $this->setResponse(new Response);
    }

    /**
     * Set request object
     *
     * @param \Savvy\Runner\GUI\Request $request request object
     * @return \Savvy\Runner\GUI\Presenter
     */
    public function setRequest(\Savvy\Runner\GUI\Request $request)
    {
        $this->request = $request;
        return $this;
    }

    /**
     * Get request object
     *
     * @return \Savvy\Runner\GUI\Request
     */
    protected function getRequest()
    {
        return $this->request;
    }

    /**
     * Set response object
     *
     * @param \Savvy\Runner\GUI\Response $response
     * @return \Savvy\Runner\GUI\Presenter
     */
    protected function setResponse(\Savvy\Runner\GUI\Response $response)
    {
        $this->response = $response;
        return $this;
    }

    /**
     * Get response object
     *
     * @return \Savvy\Runner\GUI\Response response object
     */
    protected function getResponse()
    {
        return $this->response;
    }

    /**
     * Dispatch request
     *
     * @return string response to be sent to client, or false on error
     */
    public function dispatch()
    {
        $result = false;

        switch($this->getRequest()->getType()) {
            case Request::TYPE_VIEW:
                $method = '';
                $filename = Base\Registry::getInstance()->get('root') . '/src/Savvy/Module';

                foreach ($this->getRequest()->getRoute() as $i => $segment) {
                    if ($i === 0) {
                        $filename .= sprintf('/%s/View', ucfirst($segment));
                    } else {
                        $method .= ucfirst($segment);

                        if ($i < count($this->getRequest()->getRoute()) - 1) {
                            $filename .= sprintf('/%s', ucfirst($segment));
                        } else {
                            $filename .= sprintf('/%s.xml', ucfirst($segment));
                        }
                    }
                }

                if (is_readable($filename)) {
                    $result = file_get_contents($filename);
                } else {
                    throw new Exception(
                        implode('/', $this->getRequest()->getRoute()),
                        Exception::E_RUNNER_GUI_VIEW_NOT_FOUND
                    );
                }
                break;
            case Request::TYPE_ACTION:
                if ($this->getRequest()->getRoute()[0] !== 'login' && Base\Session::getInstance()->valid() === false) {
                    $this->setResponse(new Response());

                    $responseCommand = new ResponseCommand($this->getResponse());
                    $responseCommand->quit();
                } else {
                    $method = sprintf('%sAction', $action = $this->getRequest()->getAction());

                    if (method_exists($this, $method)) {
                        $this->{$method}();
                    } else {
                        throw new Exception($action, Exception::E_RUNNER_GUI_ACTION_NOT_FOUND);
                    }
                }
                $result = $this->getResponse()->render();
                break;
        }

        return $result;
    }
}
