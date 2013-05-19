<?php

namespace Savvy\Component\Logger;

class FileTest extends \PHPUnit_Framework_TestCase
{
    private $testInstance;

    public function setup()
    {
        $this->tmpFile = sprintf('tmp/phpunit_%s.tmp', uniqid());
        $this->testInstance = Factory::getInstance('file:filename=' . $this->tmpFile);
    }

    public function teardown()
    {
        if (file_exists(\Savvy\Base\Registry::getInstance()->get('root') . '/' . $this->tmpFile)) {
            @unlink(\Savvy\Base\Registry::getInstance()->get('root') . '/' . $this->tmpFile);
        }
    }

    public function testFileLoggerIsInstanceOfAbstractLogger()
    {
        $this->assertInstanceOf('\Savvy\Component\Logger\AbstractLogger', $this->testInstance);
    }

    public function testFileLoggerPrependsRootPath()
    {
        $this->testInstance->setFilename($this->tmpFile);
        $this->testInstance->write('unittest');
        $this->assertFileExists(\Savvy\Base\Registry::getInstance()->get('root') . '/' . $this->tmpFile);
    }

    public function testFileLoggerIsWorking()
    {
        $this->testInstance->setFilename('/dev/null');
        $this->assertTrue($this->testInstance->write('foo', LOG_INFO));
    }
}
