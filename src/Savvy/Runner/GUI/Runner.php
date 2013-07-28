<?php

namespace Savvy\Runner\GUI;

use Savvy\Base as Base;

/**
 * Runner for web GUI
 *
 * @package Savvy
 * @subpackage Runner\GUI
 */
class Runner extends \Savvy\Runner\AbstractRunner
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
     * Returns true if this runner is suitable for current mode of operation
     *
     * @return bool
     */
    public function isSuitable()
    {
        return isset($_REQUEST['api']) === false && (isset($_SERVER['REQUEST_URI']) || empty($_GET) === false);
    }

    /**
     * Get preferred language from browser
     *
     * @return string language code (2-letter ISO code)
     */
    public function getLanguage()
    {
        $this->setLanguage(parent::getLanguage());

        if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            $pattern = '/([[:alpha:]]{2})(?:-[[:alpha:]]{2}|)(?:;q=([[:digit:]\.]*)|())/i';
            $preferred = array();
            $supported = explode(',', \Savvy\Base\Registry::getInstance()->get('languages'));

            preg_match_all($pattern, $_SERVER['HTTP_ACCEPT_LANGUAGE'], $languages, PREG_SET_ORDER);

            foreach ($languages as $language) {
                list($match, $l, $prio) = $language;

                if (in_array($l, $supported)) {
                    $prio = (float)($prio === '' ? 1 : $prio);
                    $preferred[$l] = isset($preferred[$l]) ? max($preferred[$l], $prio) : $prio;
                }
            }

            if (empty($preferred) === false) {
                arsort($preferred);
                $this->setLanguage(current(array_keys($preferred)));
            }
        }

        return $this->language;
    }

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
     * Get request object with parameters from HTML POST/GET request
     *
     * @return \Savvy\Runner\GUI\Request
     */
    protected function getRequest()
    {
        if ($this->request === null) {
            $request = new Request();

            if (isset($_GET['view'])) {
                $request->setType(Request::TYPE_VIEW);
                $request->setRoute($_GET['view']);
            } elseif (isset($_SERVER['HTTP_APPLICATION_SESSION'])) {
                $request->setType(Request::TYPE_ACTION);
                $request->setRoute($_SERVER['REQUEST_URI']);
                $request->setForm($_POST);
            }

            $this->setRequest($request);
        }

        return $this->request;
    }

    /**
     * Render XML view to browser output
     *
     * @param string $xml
     * @return string
     */
    protected function render($xml)
    {
        $result = '';
        $xmlElement = new \SimpleXMLElement($xml);

        try {
            $widgetClass = sprintf('\Savvy\Runner\GUI\Widget\%s', ucfirst($xmlElement->getName()));
            $widgetInstance = new $widgetClass($xmlElement, $this->getRequest()->getRoute());
            $result = $widgetInstance->render();
        } catch (Exception $e) {
        }

        return $result;
    }

    /**
     * Send output to client
     *
     * @return int
     */
    public function run()
    {
        $result = 0;

        if ($this->getRequest()->getRoute()[0] !== 'login' &&
            Base\Session::getInstance()->valid() === false &&
            $this->getRequest()->getType() !== Request::TYPE_VIEW) {

            header("Cache-Control: no-cache, must-revalidate");
            header("Expires: " . gmdate('D, d M Y H:i:s \G\M\T', time() + 365 * 24 * 60 * 60));

            $response = new Response();
            $responseCommand = new ResponseCommand($response);
            $responseCommand->quit();

            echo $response->render();
        } elseif ($response = $this->getPresenter()->dispatch()) {
            header("Cache-Control: no-cache, must-revalidate");
            header("Expires: " . gmdate('D, d M Y H:i:s \G\M\T', time() + 365 * 24 * 60 * 60));

            if ($this->getRequest()->getType() === Request::TYPE_VIEW) {
                echo $this->render($response);
            } else {
                echo $response;
            }
        } else {
            include('View/Index.phtml');
            $result = $response;
        }

        return $result;
    }
}
