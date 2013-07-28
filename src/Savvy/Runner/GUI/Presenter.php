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
    protected $response = null;

    /**
     * Whether a valid session is required to generate a response
     *
     * @var bool
     */
    protected $validateSession = true;

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
        if ($this->response === null) {
            $this->setResponse(new Response());
        }

        return $this->response;
    }

    /**
     * Set validateSession property
     *
     * @param bool $validateSession true if session validation is required
     * @return \Savvy\Runner\GUI\Presenter
     */
    public function setValidateSession($validateSession)
    {
        $this->validateSession = (bool)$validateSession;
        return $this;
    }

    /**
     * Get validateSession property
     *
     * @return bool
     */
    protected function getValidateSession()
    {
        return $this->validateSession;
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

                foreach ($route = $this->getRequest()->getRoute() as $i => $segment) {
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
                    throw new Exception(implode('/', $route), Exception::E_RUNNER_GUI_VIEW_NOT_FOUND);
                }
                break;
            case Request::TYPE_ACTION:
                if ($this->getValidateSession() && Base\Session::getInstance()->valid() === false) {
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
