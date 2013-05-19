<?php

namespace Savvy\Runner\GUI\Native;

use Savvy\Runner\GUI as GUI;

class RunnerTest extends \PHPUnit_Framework_TestCase
{
    private $testInstance;

    public function setup()
    {
        $this->testInstance = new Runner();
        $this->languages = explode(',', \Savvy\Base\Registry::getInstance()->get('languages'));
    }

    public function teardown()
    {
        \Savvy\Base\Registry::getInstance()->set('languages', implode(',', $this->languages));
    }

    public function testObjectIsInstanceOfRunner()
    {
        $this->assertInstanceOf('\Savvy\Runner\GUI\Native\Runner', $this->testInstance);
    }

    public function testRunnerCreatesPresenter()
    {
        $this->assertInstanceOf('\Savvy\Runner\GUI\Presenter', $this->testInstance->getPresenter());
    }

    public function testRunnerViewRequest()
    {
        $request = new GUI\Request();
        $request->setType(GUI\Request::TYPE_VIEW);
        $request->setRoute('login/index');

        $this->testInstance->setRequest($request);

        ob_start();
        $this->testInstance->run();
        $output = ob_get_contents();
        ob_end_clean();

        $this->assertTrue(strlen($output) > 0);
        $this->assertInstanceOf('SimpleXMLElement', simplexml_load_string($output));
    }

    public function testRunnerLanguage()
    {
        $this->assertTrue(in_array($this->testInstance->getLanguage(), $this->languages));
    }

    public function testRunnerLanguageFallback()
    {
        \Savvy\Base\Registry::getInstance()->set('languages');
        $this->assertEmpty(\Savvy\Base\Registry::getInstance()->get('languages'));
        $this->assertEquals('en', $this->testInstance->getLanguage());
    }
}
