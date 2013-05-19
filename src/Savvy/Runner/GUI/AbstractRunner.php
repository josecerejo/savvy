<?php

namespace Savvy\Runner\GUI;

use Savvy\Base as Base;

/**
 * Abstract class for GUI runners
 *
 * @package Savvy
 * @subpackage Runner\GUI
 */
abstract class AbstractRunner extends \Savvy\Runner\AbstractRunner
{
    /**
     * Presenter object for current view or action
     *
     * @var \Savvy\Runner\GUI\AbstractPresenter
     */
    protected $presenter = null;

    /**
     * Request object for current view or action
     *
     * @var \Savvy\Runner\GUI\Request
     */
    protected $request = null;

    /**
     * Get presenter for current view or action
     *
     * @return \Savvy\Runner\GUI\AbstractPresenter presenter
     */
    public function getPresenter()
    {
        if ($this->presenter === null) {
            $route = $this->getRequest()->getRoute();

            $presenterClass = sprintf(
                "\\Savvy\\Module\\%s\\Presenter\\%sPresenter",
                ucfirst($route[0]),
                ucfirst($route[1])
            );

            $this->presenter = new $presenterClass;
        }

        return $this->presenter->setRequest($this->getRequest());
    }

    /**
     * Set request object
     *
     * @param \Savvy\Runner\GUI\Request
     * @return \Savvy\Runner\GUI\AbstractRunner
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
    abstract protected function getRequest();
}
