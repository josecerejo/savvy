<?php

namespace Savvy\Base;

class CacheTest extends \PHPUnit_Framework_TestCase
{
    private $testInstance;
    private $cacheDriver;

    public function setup()
    {
        $this->testInstance = new Cache;
        $this->cacheDriver = Registry::getInstance()->get('cache.driver');
    }

    public function teardown()
    {
        Registry::getInstance()->set('cache.driver', $this->cacheDriver);
    }

    public function testCacheProviderNull()
    {
        Registry::getInstance()->set('cache.driver');
        $this->testInstance->init();
        $this->assertEquals(null, $this->testInstance->getCacheProvider());
    }

    public function testCacheProviderArray()
    {
        Registry::getInstance()->set('cache.driver', 'array');
        $this->testInstance->init();
        $this->assertInstanceOf('\Doctrine\Common\Cache\CacheProvider', $this->testInstance->getCacheProvider());
    }

    public function testCacheProviderConfigured()
    {
        $this->testInstance->init();

        if (Registry::getInstance()->get('cache.driver')) {
            $this->assertInstanceOf('\Doctrine\Common\Cache\CacheProvider', $this->testInstance->getCacheProvider());
        } else {
            $this->assertEquals(null, $this->testInstance->getCacheProvider());
        }
    }
}
