<?php

namespace Savvy\Base;

class RegistryTest extends \PHPUnit_Framework_TestCase
{
    public function testRegistryInitializationHonorsTestMode()
    {
        $registry = new Registry;
        $registry->init();

        $this->assertEquals('test', $registry->get('mode'));
    }

    public function testRegistryKeyUndefinedReturnsFalse()
    {
        $this->assertFalse(Registry::getInstance()->get('testRegistryKeyUndefined'));
    }

    public function testRegistryKeyUnsetting()
    {
        Registry::getInstance()->set('testRegistryKeyUnsetting', 'phpunit');
        $this->assertEquals('phpunit', Registry::getInstance()->get('testRegistryKeyUnsetting'));

        Registry::getInstance()->set('testRegistryKeyUnsetting');
        $this->assertEmpty(Registry::getInstance()->get('testRegistryKeyUnsetting'));
    }

    public function testRegistryGetFilenameWithPath()
    {
        Registry::getInstance()->set('phpunitTestConfiguration', 'tests/phpunit.xml');
        $this->assertTrue(file_exists(Registry::getInstance()->getFilename('phpunitTestConfiguration')));
    }
}
