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
define ( 'SERVICE_CONFIG', CONFIG_PATH . 'services.yaml' );

require_once CORE_PATH . '/core.php';
//\Athene\Core\Core::addAutoloaderPath ( APP_PATH );
//\Athene\Core\Core::generateAutoloaderConfigFile ();
spl_autoload_register ( '\Athene\Core\Core::loadClass' );
$ServiceProvider = Athene\Core\Service\ServiceProvider::getInstance ();
$grp_perm = $ServiceProvider->getService ('ORMapper', array('table'=>'group_permissions') );
echo '<pre>';
print_r($grp_perm);