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
                    'command' => 'modal',
                    'parameter' => array('nothing', 'index')
                )
            ),
            $response->commands
        );
    }
}
