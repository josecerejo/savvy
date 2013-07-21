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
            $databaseConfiguration = Registry::getInstance()->get('database');
            $metadataDriverInstance = new \Doctrine\ORM\Mapping\Driver\XmlDriver(self::getSchemaDirectories());
            $cacheDriverInstance = \Savvy\Base\Cache::getInstance()->getCacheProvider();

            $configuration = new \Doctrine\ORM\Configuration();
            $configuration->setMetadataDriverImpl($metadataDriverInstance);
            $configuration->setProxyDir(Registry::getInstance()->get('root') .'/tmp/Proxy');
            $configuration->setProxyNamespace('Proxy');

            if ($cacheDriverInstance !== null) {
                $configuration->setMetadataCacheImpl($cacheDriverInstance);
                $configuration->setQueryCacheImpl($cacheDriverInstance);
            }

            $configuration->setAutoGenerateProxyClasses(
                (bool)Registry::getInstance()->get('doctrine.auto_generate_proxy_classes', false)
            );

            $entityManager = \Doctrine\ORM\EntityManager::create($databaseConfiguration, $configuration);

            if ((bool)Registry::getInstance()->get('doctrine.auto_generate_schema', false)) {
                $schemaTool = new \Doctrine\ORM\Tools\SchemaTool($entityManager);
                $schemaTool->updateSchema($entityManager->getMetadataFactory()->getAllMetadata());
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
     * Get list of schema directories contained in core and modules
     *
     * @return array
     */
    private static function getSchemaDirectories()
    {
        return array_merge(
            array(Registry::getInstance()->get('root') . '/src/Savvy/Storage/Schema'),
            glob(Registry::getInstance()->get('root') . '/src/Savvy/Module/*/Storage/Schema', GLOB_ONLYDIR)
        );
    }
}
