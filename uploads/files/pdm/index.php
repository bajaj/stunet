<?php
session_start();

DEFINE("FRAMEWORK_PATH", dirname(".") ."/" );


require(FRAMEWORK_PATH.'registry/registry.class.php');
$registry = new Registry();
// setup our core registry objects
$registry->createAndStoreObject( 'template', 'template' );
$registry->createAndStoreObject( 'mysqldb', 'db' );
$registry->createAndStoreObject( 'authenticate', 'authenticate' );
$registry->createAndStoreObject( 'urlprocessor', 'url' );
$registry->getObject('url')->getURLData();
// database settings
include(FRAMEWORK_PATH . 'config2.php');
// create a database connection
$registry->getObject('db')->newConnection( $configs['db_host_sn'], $configs['db_user_sn'], $configs['db_pass_sn'], $configs['db_name_sn']);
$controller = $registry->getObject('url')->getURLBit(0);





if( $controller != 'api' )
{
	$registry->getObject('authenticate')->checkForAuthentication();
}


// store settings in our registry
$settingsSQL = "SELECT `skey`, `svalue` FROM settings";
$registry->getObject('db')->executeQuery( $settingsSQL );
while( $setting = $registry->getObject('db')->getRows() )
{
	$registry->storeSetting( $setting['svalue'], $setting['skey'] );
}

if( $controller != 'api' )
{
	$registry->getObject('authenticate')->checkForAuthentication();
}





$registry->getObject('template')->getPage()->addTag( 'siteurl', $registry->getSetting('siteurl') );
$registry->getObject('template')->buildFromTemplates('header.tpl.php', 'main.tpl.php', 'footer.tpl.php');
				
$controllers = array();
$controllersSQL = "SELECT * FROM controllers WHERE active=1";
$registry->getObject('db')->executeQuery( $controllersSQL );
while( $cttrlr = $registry->getObject('db')->getRows() )
{
	$controllers[] = $cttrlr['controller'];
}
$controller = $registry->getObject('url')->getURLBit(0);




if($registry->getObject('authenticate')->isLoggedIn() && $controller == 'pdm')
{
	require_once( FRAMEWORK_PATH . 'controllers/' . $controller . '/controller.php');
	$controllerInc = $controller.'controller';
	$controller = new $controllerInc( $registry, true );


}





elseif( $registry->getObject('authenticate')->isLoggedIn() && $controller != 'api')
{
	$registry->getObject('template')->addTemplateBit('userbar', 'userbar_loggedin.tpl.php');
	$registry->getObject('template')->getPage()->addTag( 'username', $registry->getObject('authenticate')->getUser()->getUsername() );
	$registry->getObject('template')->getPage()->addTag( 'p_user_id', $registry->getObject('authenticate')->getUser()->getUserID());
	
}
elseif( $controller != 'api' )
{
	$registry->getObject('template')->addTemplateBit('userbar', 'userbar.tpl.php');
}


if( in_array( $controller, $controllers ) )
{
	
	require_once( FRAMEWORK_PATH . 'controllers/' . $controller . '/controller.php');
	$controllerInc = $controller.'controller';
	$controller = new $controllerInc( $registry, true );

}
else
{
	// default controller, or pass control to CMS type system?
}


// $registry->getObject('template')->parseOutput();
 // print $registry->getObject('template')->getPage()->getContentToPrint();

?>