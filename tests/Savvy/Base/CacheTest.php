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

    public function testCacheProviderDefault()
    {
        Registry::getInstance()->set('cache.driver');
        $this->testInstance->init();
        $this->assertInstanceOf('\Doctrine\Common\Cache\CacheProvider', $this->testInstance->getCacheProvider());
    }

    public function testCacheProviderConfigured()
    {
        $this->testInstance->init();
        $this->assertInstanceOf('\Doctrine\Common\Cache\CacheProvider', $this->testInstance->getCacheProvider());
    }
}
