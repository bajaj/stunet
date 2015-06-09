<?php


class Homecontroller
{


public function __construct( Registry $registry, $directCall=true )
	{
		$this->registry = $registry;
		if( $this->registry->getObject('authenticate')->isLoggedIn() )
		{			
		    $grp=$this->getdefault();
		
			if($grp==0)
			{
			header('Location: '.$this->registry->getSetting('siteurl').'groups/search-results?searchby=college&searchname='.$this->getCollege());
			}
			else
			{
			  header('Location: '.$this->registry->getSetting('siteurl').'group/'.$grp);
			}
		
		
		}
		
		
		
	}
		
	
	
	
	
private function getdefault()
{
$my=$this->registry->getObject('authenticate')->getUser()->getUserID();

$sql="SELECT g_default FROM profile WHERE ID={$my}";
$this->registry->getObject('db')->executeQuery( $sql );

$data=$this->registry->getObject('db')->getRows();

return $data['g_default'];

}	
	
	
private function getcollege()
{
$my=$this->registry->getObject('authenticate')->getUser()->getUserID();

$sql="SELECT college FROM profile WHERE ID={$my}";
$this->registry->getObject('db')->executeQuery( $sql );

$data=$this->registry->getObject('db')->getRows();

return $data['college'];

}	
	
	
	
}