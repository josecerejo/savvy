<?php

namespace Savvy\Base;

class ExceptionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \Savvy\Base\Exception
     */
    public function testExceptionSupportsUnknownErrors()
    {
        throw new Exception(null, -1);
    }
}
