<?php

namespace Savvy\Base;

class LanguageTest extends \PHPUnit_Framework_TestCase
{
    private $testInstance;

    public function setup()
    {
        $this->testInstance = Language::getInstance();
    }

    public function testLanguageGetterReturnsKeyNameWhenKeyWasNotFound()
    {
        $this->assertEquals('DOES_NOT_EXIST', $this->testInstance->get('DOES_NOT_EXIST'));
    }
}
