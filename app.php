<?php

use Silex\Application,
    Silex\Provider\SessionServiceProvider,
    Silex\Provider\TwigServiceProvider,
    Silex\Provider\MonologServiceProvider,
    Silex\Provider\UrlGeneratorServiceProvider,
    Silex\Provider\FormServiceProvider,
    Silex\Provider\ValidatorServiceProvider,
    Silex\Provider\TranslationServiceProvider,
    Igorw\Silex\ConfigServiceProvider,
    VitorNP\Provider\DoctrineServiceProvider,
    Doctrine\Common\Cache\ApcCache;

define('DIR', __DIR__);
define('DS', DIRECTORY_SEPARATOR);
define('APP_ENV', getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production');

$loader = require DIR . DS . 'vendor' . DS . 'autoload.php';
$loader->add('VitorNP', DIR . DS . 'src');

// Inicio de configuração do Silex
$app = new Application();

$app->register(new ConfigServiceProvider(DIR . DS . "resources" . DS . APP_ENV .".json"));

$app->register(new UrlGeneratorServiceProvider());

$app->register(new SessionServiceProvider());

$app->register(new TwigServiceProvider(), array(
    'twig.path' => DIR . DS . 'views'
));

$app->register(new FormServiceProvider());

$app->register(new ValidatorServiceProvider());

$app->register(new TranslationServiceProvider(), array(
    'locale_fallback' => 'pt-BR',
    'translator.domains' => array(
	'messages' => array(
	    'pt-BR' => array(
		
	    )
	)
    )
));

$app->register(new DoctrineServiceProvider(), array(
    'doctrine_orm.metadata_cache' => new ApcCache(),
    'doctrine_orm.query_cache' => new ApcCache(),
    'doctrine_orm.entities_path' => DIR . DS . 'src',
    'doctrine_orm.proxies_path' => DIR . DS . 'src' . DS . 'VitorNP' . DS . 'Proxies' . DS,
    'doctrine_orm.proxies_namespace' => 'VitorNP\Proxies',
    'doctrine_orm.connection_parameters' => array(
	'driver' => $app["driver"],
	'dbname' => $app["dbname"],
	'user' => $app["user"],
	'password' => $app["password"],
	'host' => $app["host"],
	'port' => $app["port"]
    )
));

$app->register(new MonologServiceProvider(), array(
    'monolog.logfile' => DIR . DS . 'log' . DS . 'development.log',
));

return $app;