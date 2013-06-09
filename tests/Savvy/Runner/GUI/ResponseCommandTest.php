<?php

namespace Savvy\Runner\GUI;

class ResponseCommandTest extends \PHPUnit_Framework_TestCase
{
    public function testModal()
    {
        $response = new Response;
        $response->addCommand()->modal('nothing');

        $this->assertEquals(
            array(
                0 => array(
                    'command'   => 'modal',
                    'parameter' => array('nothing', 'index')
                )
            ),
            $response->commands
        );
    }

    public function testOpen()
    {
        $response = new Response;
        $response->addCommand()->open('thisisamodule', 'thisisaview');

        $this->assertEquals(
            array(
                0 => array(
                    'command'   => 'open',
                    'parameter' => array('thisisamodule', 'thisisaview')
                )
            ),
            $response->commands
        );
    }

    public function testClose()
    {
        $response = new Response;
        $response->addCommand()->close('thisisawindowid');

        $this->assertEquals(
            array(
                0 => array(
                    'command'   => 'close',
                    'parameter' => array('thisisawindowid')
                )
            ),
            $response->commands
        );
    }

}
