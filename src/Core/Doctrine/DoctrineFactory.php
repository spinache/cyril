<?php

namespace Core\Doctrine;

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Doctrine\Common\Cache\ApcCache;
use Symfony\Component\Stopwatch\Stopwatch;

class DoctrineFactory {

    protected $debug = false;
    protected $cacheDir;
    protected $entityDir;
    protected $dbParams;
    

    public function __construct($debug, $cacheDir, $entityDir, $dbParams) {
        $this->debug = $debug;
        //prod or dev?
        $this->cacheDir = $cacheDir;
        $this->entityDir = $entityDir;
        $this->dbParams = $dbParams;
    }

    public function get() {
        $config = Setup::createAnnotationMetadataConfiguration(array($this->entityDir), $this->debug);

        $proxyDir = $this->cacheDir.'/../Doctrine';
        $config->setProxyDir($proxyDir);
        $config->setProxyNamespace('dc2_proxy');

        if (!$this->debug) {
            $config->setAutoGenerateProxyClasses(false);
            
            $queryCache = new ApcCache();
            $queryCache->setNamespace('db_query::' . md5($proxyDir) . '::');
            $config->setQueryCacheImpl($queryCache);

            $metadataCache = new ApcCache();
            $metadataCache->setNamespace('metadata::' . md5($proxyDir) . '::');
            $config->setMetadataCacheImpl($metadataCache);

            $resultCache = new ApcCache();
            $resultCache->setNamespace('results::' . md5($proxyDir) . '::');
            $config->setResultCacheImpl($resultCache);
        }else{
            $config->setSQLLogger(new Logger());
            $config->setAutoGenerateProxyClasses(true);
        }
        
        $em = EntityManager::create($this->dbParams, $config);

        return $em;
    }

}

?>
