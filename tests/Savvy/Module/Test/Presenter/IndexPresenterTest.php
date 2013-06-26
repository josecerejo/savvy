<?php

namespace Savvy\Module\Test\Presenter;

use Savvy\Base as Base;
use Savvy\Storage\Model as Model;
use Savvy\Runner\GUI as GUI;

class IndexPresenterTest extends \PHPUnit_Framework_TestCase
{
    private $testInstance;

    public function setup()
    {
        $this->testInstance = new IndexPresenter();
    }

    public function testIndexPresenter()
    {
        $request = new GUI\Request();
        $request->setRoute('test/index?action=modal');

        $this->testInstance->setRequest($request);
        $response = json_decode($this->testInstance->dispatch());

        $this->assertInstanceOf('stdClass', $response);
        $this->assertEquals(true, $response->success);
    }
}
