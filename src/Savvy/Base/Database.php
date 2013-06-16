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
            $database = Registry::getInstance()->get('database');

            $metadataCache = \Savvy\Base\Cache::getInstance()->getCacheProvider();
            $metadataDriver = new \Doctrine\ORM\Mapping\Driver\XmlDriver($this->getSchemaDirectories());

            $configuration = new \Doctrine\ORM\Configuration();
            $configuration->setMetadataCacheImpl($metadataCache);
            $configuration->setMetadataDriverImpl($metadataDriver);
            $configuration->setProxyDir(Registry::getInstance()->get('root') .'/tmp/Proxy');
            $configuration->setProxyNamespace('Proxy');
            $configuration->setQueryCacheImpl(\Savvy\Base\Cache::getInstance()->getCacheProvider());

            $configuration->setAutoGenerateProxyClasses(
                (bool)Registry::getInstance()->get('doctrine.auto_generate_proxy_classes', false)
            );

            $entityManager = \Doctrine\ORM\EntityManager::create($database, $configuration);

            if ((bool)Registry::getInstance()->get('doctrine.auto_generate_schema', false)) {
                $schemaTool = new \Doctrine\ORM\Tools\SchemaTool($entityManager);
                $schemaTool->createSchema($entityManager->getMetadataFactory()->getAllMetadata());
            }

            $this->setEntityManager($entityManager);
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
