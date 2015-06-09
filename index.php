<?php session_start();
//define constant FRAMEWORK_PATH
DEFINE("FRAMEWORK_PATH",dirname(__FILE__)."/");
//DEFINE("FRAMEWORK_PATH","/");
//__FILE__ is a constant in php containing the absolute path of the current file!
//dirname($path) returns the directory name of a file!
//include the registry class
require(FRAMEWORK_PATH.'registry/registry.class.php');
//create our registry
$registry =new registry();
//setup our core registry objects
$registry->createAndStoreObject('template','template');
$registry->createAndStoreObject('mysqldb','db');
$registry->createAndStoreObject('authenticate','authenticate');
$registry->createAndStoreObject('urlprocessor','url');
$registry->createAndStoreObject('mailout','mailout');
$registry->getObject('url')->getURLData();


//database settings
include(FRAMEWORK_PATH.'config.php');
//connect to the database
$registry->getObject('db')->newConnection($configs['db_host_sn'],$configs['db_user_sn'],$configs['db_pass_sn'],$configs['db_name_sn']);

//check for authentication 
$registry->getObject('authenticate')->checkForAuthentication();

//store settings in our registry from the database
$settingsSQL="select skey, svalue from settings";
$registry->getObject('db')->executeQuery($settingsSQL);
while($setting=$registry->getObject('db')->getRows())
{
	$registry->storeSetting($setting['svalue'],$setting['skey']);
}

//Build the default template
$registry->getObject('template')->getPage()->setTitle($registry->getSetting('sitename').' | The network of students and professors');
$registry->getObject('template')->getPage()->addTag('siteurl',$registry->getSetting('siteurl'));
$registry->getObject('template')->buildFromTemplates('header-nl.tpl.php','main.tpl.php','footer.tpl.php');


//store controllers in a controllers array from the database
$controllers=array();
$controllersSQL="select * from controllers where active=1";
$registry->getObject('db')->executeQuery($controllersSQL);
while($controller=$registry->getObject('db')->getRows())
{
	$controllers[]=$controller['controller'];
}
$controller=$registry->getObject('url')->getURLBit(0);
if( in_array( $controller, $controllers ) )
{
	require_once( FRAMEWORK_PATH . 'controllers/' . $controller . '/controller.php');
	$controllerInc = $controller.'controller';
	if(!$registry->getObject('authenticate')->isLoggedIn())
	{
		if($controllerInc=='authenticatecontroller')
		$controller = new $controllerInc( $registry, true );
	}
	else
		$controller = new $controllerInc( $registry, true );

}
else
{
	// default controller, or pass control to CMS type system?
}



//check if the user is logged in
if($registry->getObject('authenticate')->isLoggedIn())
{//send them to the profile page
	$registry->getObject('template')->addTemplateBit('userbar','userbar_loggedin.tpl.php');
        $registry->getObject('template')->getPage()->addTag('fname',$registry->getObject('authenticate')->getUser()->getFName());
        $registry->getObject('template')->getPage()->addTag('lname',$registry->getObject('authenticate')->getUser()->getLName());
        $registry->getObject('template')->getPage()->addTag('logged_in_ID',$registry->getObject('authenticate')->getUser()->getUserID());
        $registry->getObject('template')->getPage()->addTag('logged_in_type',$registry->getObject('authenticate')->getUser()->getType());
        if($registry->getObject('authenticate')->getUser()->getType()=='student')
        $registry->getObject('template')->getPage()->addTag('evaluationlink','<li><a href="evaluation/check"><img src="views/default/images/arrow.png"/> &nbsp;Check Evaluation</a></li>');
        else
        {
            $registry->getObject('template')->getPage()->addTag('evaluationlink','');
        }
		if($registry->getObject('authenticate')->getUser()->isAdmin())
		$registry->getObject('template')->getPage()->addTag('listbanned','<li><a href="admin/listbanned"><img src="views/default/images/arrow.png"/> &nbsp;Banned Users List</a></li>');
        else
				$registry->getObject('template')->getPage()->addTag('listbanned','');
				$urlBit=$registry->getObject('url')->getURLBit(0);
		
}
else
{//send them to the landing page, where they can login or signup!
	$registry->getObject('template')->addTemplateBit('userbar','userbar.tpl.php');	
}


$registry->getObject('template')->getPage()->addTag('sitename',$registry->getSetting('sitename'));
$registry->getObject('template')->parseOutput();
print $registry->getObject('template')->getPage()->getContentToPrint();
exit;
?>