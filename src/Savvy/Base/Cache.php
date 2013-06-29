<?php

namespace Savvy\Base;

use Doctrine;

/**
 * Cache mechanism binding
 *
 * @package Savvy
 * @subpackage Base
 */
class Cache extends AbstractSingleton
{
    /**
     * Doctrine cache provider
     *
     * @var \Doctrine\Common\Cache\CacheProvider
     */
    private $cacheProvider = null;

    /**
     * Initialize caching mechanism
     *
     * @return void
     */
    public function init()
    {
        if ($this->cacheProvider === null) {
            switch ((string)Registry::getInstance()->get('cache.driver')) {
                case 'memcache':
                    $memcache = new \Memcache();
                    $memcache->connect(
                        Registry::getInstance()->get('cache.host'),
                        Registry::getInstance()->get('cache.port')
                    );

                    $cacheProvider = new \Doctrine\Common\Cache\MemcacheCache();
                    $cacheProvider->setMemcache($memcache);
                    break;
                case 'array':
                    $cacheProvider = new \Doctrine\Common\Cache\ArrayCache();
                    break;
                default:
                    $cacheProvider = null;
            }

            $this->setCacheProvider($cacheProvider);
        }
    }

    /**
     * Set cache provider instance
     *
     * @param \Doctrine\Common\Cache\CacheProvider $cacheProvider
     * @return \Savvy\Base\Cache
     */
    public function setCacheProvider(\Doctrine\Common\Cache\CacheProvider $cacheProvider = null)
    {
        $this->cacheProvider = $cacheProvider;
        return $this;
    }

    /**
     * Get cache provider instance
     *
     * @return \Doctrine\Common\Cache\CacheProvider
     */
    public function getCacheProvider()
    {
        return $this->cacheProvider;
    }
}
