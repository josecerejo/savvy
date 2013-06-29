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
        if (Registry::getInstance()->get('cache.driver') !== false) {
            $this->assertInstanceOf('\Doctrine\Common\Cache\CacheProvider', $this->testInstance->getCacheProvider());
        } else {
            $this->assertEquals(null, $this->testInstance->getCacheProvider());
        }
    }
}
