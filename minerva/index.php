<?php
define ( 'ROOT_PATH', dirname ( realpath ( 'index.php' ) ) );
define ( 'APP_PATH', ROOT_PATH . '/application/' );
define ( 'PUBLIC_PATH', ROOT_PATH . '/public/' );
define ( 'CONFIG_PATH', APP_PATH . 'config/' );
define ( 'CORE_PATH', APP_PATH . 'core/' );
define ( 'EXTERN_PATH', APP_PATH . 'extern/' );
define ( 'MODULE_PATH', APP_PATH . 'modules/' );
define ( 'TEMPLATE_PATH', PUBLIC_PATH . 'templates/' );
define ( 'MEDIA_PATH', PUBLIC_PATH . 'media/' );
define ( 'DATA_PATH', APP_PATH . '/data/' );

require_once CORE_PATH . '/core.php';
require_once EXTERN_PATH . '/robap-php-router/php-router.php';
Core::addAutoloaderPath ( APP_PATH );
Core::generateAutoloaderConfigFile ();
spl_autoload_register ( 'Core::loadClass' );
$ServiceProvider = ServiceProvider::getInstance ();
$ServiceProvider->loadConfig ( CONFIG_PATH . 'services.yaml' );
$sess = $ServiceProvider->getService ( 'SessionProvider' );
$sess->set ( 'test', 'test' );