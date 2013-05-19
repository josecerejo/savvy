<?php

namespace Savvy\Runner\GUI;

/**
 * GUI request object
 *
 * @package Savvy
 * @subpackage Runner\GUI
 */
class Request
{
    /**
     * View request
     */
    const TYPE_VIEW = 1;

    /**
     * Action request
     */
    const TYPE_ACTION = 2;

    /**
     * Request type, e.g. send view or perform action
     *
     * @var int
     */
    protected $type = 0;

    /**
     * Route to current view
     *
     * @var array
     */
    protected $route = array('login', 'index');

    /**
     * Form contents
     *
     * @var array
     */
    protected $form = array();

    /**
     * Action name
     *
     * @var string
     */
    protected $action = 'index';

    /**
     * Set route to current view
     *
     * @param mixed $route view route as string or array
     * @return \Savvy\Runner\GUI\Request
     */
    public function setRoute($route)
    {
        if (is_array($route)) {
            $this->route = $route;
        } else {
            if (($pos = strpos($route, '?')) !== false) {
                $parameters = explode('&', substr($route, $pos + 1));
                $route = substr($route, 0, $pos);

                foreach ($parameters as $parameter) {
                    list($name, $value) = explode('=', $parameter);

                    switch(strtolower($name)) {
                        case 'action':
                            $this->setType(Request::TYPE_ACTION);
                            $this->setAction($value);
                            break;
                    }
                }
            }

            $this->route = preg_split('/\//', trim(strtolower($route), '/'));
        }

        return $this;
    }

    /**
     * Get route to current view
     *
     * @return array route to module presenter/view
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * Set type of request
     *
     * @param int $type
     * @return \Savvy\Runner\GUI\Request
     */
    public function setType($type)
    {
        $this->type = (int)$type;
        return $this;
    }

    /**
     * Get type of action
     *
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set form
     *
     * @param array $form
     * @return \Savvy\Runner\GUI\Request
     */
    public function setForm($form)
    {
        $this->form = (array)$form;
        return $this;
    }

    /**
     * Get form or value of specified form parameter
     *
     * @param string $parameter
     * @return array form or value of specified form parameter
     */
    public function getForm($parameter = null)
    {
        $result = false;

        if ($parameter === null) {
            $result = $this->form;
        } elseif (isset($this->form[$parameter])) {
            $result = $this->form[$parameter];
        }

        return $result;
    }

    /**
     * Set name of action method for "action" request
     *
     * @param string $action
     * @return \Savvy\Runner\GUI\Presenter
     */
    protected function setAction($action)
    {
        $this->action = (string)$action;
        return $this;
    }

    /**
     * Get name of action method for "action" request
     *
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }
}
