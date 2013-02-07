<?php

namespace VitorNP\Provider;

use Silex\Application,
    Silex\ServiceProviderInterface,
    Doctrine\Common\Annotations\AnnotationRegistry,
    Doctrine\Common\Annotations\AnnotationReader,
    Doctrine\ORM\EntityManager,
    Doctrine\ORM\Configuration,
    Doctrine\ORM\Mapping\Driver\AnnotationDriver,
    Doctrine\DBAL\DriverManager,
    Doctrine\Common\EventManager,
    Doctrine\Common\Cache\ArrayCache;

class DoctrineServiceProvider implements ServiceProviderInterface
{

    public function register(Application $app)
    {
        $app['doctrine_orm.configuration'] = $app->share(function($app) {
            $configuration = new Configuration();

            if (isset($app['doctrine_orm.metadata_cache'])) {
                $configuration->setMetadataCacheImpl($app['doctrine_orm.metadata_cache']);
            } else {
                $configuration->setMetadataCacheImpl(new ArrayCache());
            }

            AnnotationRegistry::registerFile(DIR . DS . 'vendor' . DS . 'doctrine' . DS . 'orm' . DS . 'lib' . DS . 'Doctrine' . DS . 'ORM' . DS . 'Mapping' . DS . 'Driver' . DS . 'DoctrineAnnotations.php');
            $driver = new AnnotationDriver(new AnnotationReader(), array($app['doctrine_orm.entities_path']));
            $configuration->setMetadataDriverImpl($driver);

            if (isset($app['doctrine_orm.query_cache'])) {
                $configuration->setQueryCacheImpl($app['doctrine_orm.query_cache']);
            } else {
                $configuration->setQueryCacheImpl(new ArrayCache());
            }

            if (isset($app['doctrine_orm.result_cache'])) {
                $configuration->setResultCacheImpl($app['doctrine_orm.result_cache']);
            }

            $configuration->setProxyDir($app['doctrine_orm.proxies_path']);
            $configuration->setProxyNamespace($app['doctrine_orm.proxies_namespace']);
            $configuration->setAutogenerateProxyClasses(false);

            if (isset($app['doctrine_orm.autogenerate_proxy_classes'])) {
                $configuration->setAutogenerateProxyClasses($app['doctrine_orm.autogenerate_proxy_classes']);
            } else {
                $configuration->setAutogenerateProxyClasses(true);
            }

            return $configuration;
        });

        $app['doctrine_orm.connection'] = $app->share(function($app) {
            return DriverManager::getConnection($app['doctrine_orm.connection_parameters'], $app['doctrine_orm.configuration'], new EventManager());
        });

        $app['doctrine_orm.em'] = $app->share(function($app) {
            return EntityManager::create($app['doctrine_orm.connection'], $app['doctrine_orm.configuration'], $app['doctrine_orm.connection']->getEventManager());
        });
    }

    public function boot(Application $app)
    {
        
    }

}
