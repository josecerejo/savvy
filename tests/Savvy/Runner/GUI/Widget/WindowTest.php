<?php

namespace Savvy\Runner\GUI\Widget;

class WindowTest extends \PHPUnit_Framework_TestCase
{
    public function testWindowResizableTrue()
    {
        $xml = '<window resizable="true" />';
        $widgetInstance = new Window(new \SimpleXMLElement($xml));
        $this->assertTrue(strpos($widgetInstance->render(), 'resizable:true') !== false);
    }

    public function testWindowResizableVertical()
    {
        $xml = '<window resizable="vertical" />';
        $widgetInstance = new Window(new \SimpleXMLElement($xml));
        $this->assertTrue(strpos($widgetInstance->render(), "resizable:true") !== false);
        $this->assertTrue(strpos($widgetInstance->render(), "resizeHandles:'n s'") !== false);
    }
}
