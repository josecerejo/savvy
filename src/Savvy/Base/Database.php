<?php

namespace Savvy\Base;

use Doctrine;

/**
 * Doctrine ORM binding
 *
 * @package Savvy
 * @subpackage Base
 */
class Database extends AbstractSingleton
{
    /**
     * Doctrine ORM entity manager
     *
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager = null;

    /**
     * Initialize ORM
     *
     * @return void
     */
    public function init()
    {
        if ($this->entityManager === null) {
            $database = array(
                'driver'   => Registry::getInstance()->get('database.driver'),
                'host'     => Registry::getInstance()->get('database.host'),
                'user'     => Registry::getInstance()->get('database.user'),
                'password' => Registry::getInstance()->get('database.password'),
                'dbname'   => Registry::getInstance()->get('database.database')
            );

            $metadataCache = \Savvy\Base\Cache::getInstance()->getCacheProvider();
            $metadataDriver = new \Doctrine\ORM\Mapping\Driver\XmlDriver($this->getSchemaDirectories());

            $configuration = new \Doctrine\ORM\Configuration();
            $configuration->setMetadataCacheImpl($metadataCache);
            $configuration->setMetadataDriverImpl($metadataDriver);
            $configuration->setProxyDir(Registry::getInstance()->get('root') .'/tmp/Proxy');
            $configuration->setProxyNamespace('Proxy');
            $configuration->setAutoGenerateProxyClasses(false);
            $configuration->setQueryCacheImpl(\Savvy\Base\Cache::getInstance()->getCacheProvider());

            $this->setEntityManager(\Doctrine\ORM\EntityManager::create($database, $configuration));
        }
    }

    /**
     * Set Doctrine ORM entity manager
     *
     * @param \Doctrine\ORM\EntityManager $entityManager
     * @return \Savvy\Base\Database
     */
    public function setEntityManager(\Doctrine\ORM\EntityManager $entityManager = null)
    {
        $this->entityManager = $entityManager;
        return $this;
    }

    /**
     * Get Doctrine ORM entity manager
     *
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * Get list of schema directories
     *
     * @return array
     */
    private function getSchemaDirectories()
    {
        $schemaDirectories = array(
            Registry::getInstance()->get('root') . '/src/Savvy/Storage/Schema'
        );

        return $schemaDirectories;
    }
}
