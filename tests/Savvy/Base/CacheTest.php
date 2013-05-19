<?php

namespace Savvy\Base;

class CacheTest extends \PHPUnit_Framework_TestCase
{
    private $testInstance;

    public function setup()
    {
        $this->testInstance = \Savvy\Base\Cache::getInstance();
    }

    public function testCacheProvider()
    {
        $this->assertInstanceOf('\Doctrine\Common\Cache\CacheProvider', $this->testInstance->getCacheProvider());
    }

    /**
     * @expectedException \Savvy\Base\Exception
     * @expectedExceptionCode \Savvy\Base\Exception::E_BASE_SINGLETON_CLONED
     */
    public function testWhetherCacheProviderCanBeCloned()
    {
        $clonedCacheProvider = clone \Savvy\Base\Cache::getInstance();
    }
}
