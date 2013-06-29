<?php

namespace Savvy\Runner\GUI;

use Savvy\Runner\GUI as GUI;

class RunnerTest extends \PHPUnit_Framework_TestCase
{
    private $testInstance;
    private $languages;

    public function setup()
    {
        $this->testInstance = new Runner();
        $this->languages = \Savvy\Base\Registry::getInstance()->get('languages');
    }

    public function teardown()
    {
        \Savvy\Base\Registry::getInstance()->set('languages', $this->languages);
    }

    public function testObjectIsInstanceOfRunner()
    {
        $this->assertInstanceOf('\Savvy\Runner\GUI\Runner', $this->testInstance);
        $this->assertInstanceOf('\Savvy\Runner\AbstractRunner', $this->testInstance);
    }

    public function testRunnerCreatesPresenter()
    {
        $this->assertInstanceOf('\Savvy\Runner\GUI\Presenter', $this->testInstance->getPresenter());
    }

    /**
     * @runInSeparateProcess
     */
    public function testRunnerViewLoginIndex()
    {
        $_GET['view'] = '/login/index';
        ob_start();
        $this->testInstance->run();
        $output = ob_get_contents();
        ob_end_clean();
        unset($_GET['view']);

        $this->assertTrue(strlen($output) > 0);
    }

    /**
     * @runInSeparateProcess
     */
    public function testRunnerViewTestIndex()
    {
        $_GET['view'] = '/test/index';
        ob_start();
        $this->testInstance->run();
        $output = ob_get_contents();
        ob_end_clean();
        unset($_GET['view']);

        $this->assertTrue(strlen($output) > 0);
    }

    /**
     * @runInSeparateProcess
     */
    public function testRunnerViewTestModal()
    {
        $_GET['view'] = '/test/modal';
        ob_start();
        $this->testInstance->run();
        $output = ob_get_contents();
        ob_end_clean();
        unset($_GET['view']);

        $this->assertTrue(strlen($output) > 0);
    }

    /**
     * @runInSeparateProcess
     */
    public function testRunnerActionRespondsWithJsonData()
    {
        $_POST['something'] = 'value';
        $_SERVER['REQUEST_URI'] = '/login/index?action=validate';
        $_SERVER['HTTP_APPLICATION_SESSION'] = sha1(uniqid('', true));

        ob_start();
        $this->testInstance->run();
        $output = ob_get_contents();
        ob_end_clean();

        unset($_POST['something']);
        unset($_SERVER['REQUEST_URI']);

        $this->assertInstanceOf('stdClass', json_decode($output));
    }

    public function testRunnerDetectsPreferredLanguageFromBrowser()
    {
        $_SERVER['HTTP_ACCEPT_LANGUAGE'] = 'de-ch,en-us;q=0.2,de-de;q=0.7,en;q=0.3,fr-fr;q=0.8';
        $this->assertEquals('de', $this->testInstance->getLanguage());
    }

    public function testRunnerUsesDefaultLanguageAsFallback()
    {
        $_SERVER['HTTP_ACCEPT_LANGUAGE'] = null;
        $languages = explode(',', \Savvy\Base\Registry::getInstance()->get('languages'));
        $this->assertEquals($languages[0], $this->testInstance->getLanguage());
    }

    public function testRunnerUsesEnglishAsLastFallback()
    {
        \Savvy\Base\Registry::getInstance()->set('languages');
        $this->assertEquals('en', $this->testInstance->getLanguage());
    }
}