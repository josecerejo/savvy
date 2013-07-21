<?php

namespace Savvy\Runner\GUI;

use Savvy\Runner\GUI as GUI;

class RunnerTest extends \PHPUnit_Framework_TestCase
{
    public function testObjectIsInstanceOfRunner()
    {
        $testInstance = new Runner();

        $this->assertInstanceOf('\Savvy\Runner\GUI\Runner', $testInstance);
        $this->assertInstanceOf('\Savvy\Runner\AbstractRunner', $testInstance);
    }

    public function testRunnerCreatesPresenter()
    {
        $testInstance = new Runner();
        $this->assertInstanceOf('\Savvy\Runner\GUI\Presenter', $testInstance->getPresenter());
    }

    /**
     * @runInSeparateProcess
     */
    public function testRunnerViewLoginIndex()
    {
        $_GET['view'] = '/login/index';
        ob_start();

        $testInstance = new Runner();
        $testInstance->run();

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

        $testInstance = new Runner();
        $testInstance->run();

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
        $testInstance = new Runner();
        $testInstance->run();
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
        $testInstance = new Runner();
        $testInstance->run();
        $output = ob_get_contents();
        ob_end_clean();

        unset($_POST['something']);
        unset($_SERVER['REQUEST_URI']);
        unset($_SERVER['HTTP_APPLICATION_SESSION']);

        $this->assertInstanceOf('stdClass', json_decode($output));
    }

    public function testRunnerDetectsPreferredLanguageFromBrowser()
    {
        $_SERVER['HTTP_ACCEPT_LANGUAGE'] = 'de-ch,en-us;q=0.2,de-de;q=0.7,en;q=0.3,fr-fr;q=0.8';

        $testInstance = new Runner();
        $this->assertEquals('de', $testInstance->getLanguage());

        unset($_SERVER['HTTP_ACCEPT_LANGUAGE']);
    }

    public function testRunnerUsesDefaultLanguageAsFallback()
    {
        unset($_SERVER['HTTP_ACCEPT_LANGUAGE']);

        $languages = explode(',', \Savvy\Base\Registry::getInstance()->get('languages'));
        $testInstance = new Runner();
        $this->assertEquals($languages[0], $testInstance->getLanguage());
    }

    public function testRunnerUsesEnglishAsLastFallback()
    {
        \Savvy\Base\Registry::getInstance()->set('languages');
        $testInstance = new Runner();
        $this->assertEquals('en', $testInstance->getLanguage());
    }
}
