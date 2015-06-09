<?php
/**
 * Messages controller
 * Basic private message system for Dino Space
 */
class Admincontroller {
private $registry;
	
	/**
	 * Messages controller constructor
	 * @param Registry $registry
	 * @param boolean $directCall
	 * @return void
	 */
	public function __construct( Registry $registry)
	{
		$this->registry = $registry;
		if( $this->registry->getObject('authenticate')->isLoggedIn() and $this->registry->getObject('authenticate')->getUser()->isadmin())
		{
			$urlBits = $this->registry->getObject('url')->getURLBits();
			if( isset( $urlBits[1] ) )
			{
				switch( $urlBits[1] )
				{
					case 'delete':
						$this->delete(intval($urlBits[2]));
						break;	
					case 'banuser':
						$this->banuser(intval($urlBits[2]));
						break;
					case 'unbanuser':
						$this->Unbanuser(intval($urlBits[2]));
						break;	
						
					case 'listbanned':
						$this->listbanned();
						break;
					
					default:
					break;
						
				}		
			}
		}

	}
	
	private function banuser($user_id)
	{
	$changes=array();
	
	$changes['banned']=1;
	
	$this->registry->getObject('db')->UpdateRecords('users',$changes,'ID='.$user_id);
	
		if($this->registry->getObject('db')->affectedRows()==1)
		{
		$this->registry->errorPage('Ban Successful', 'User has been successfully banned. He/She won\'t be allowed to login');	
		}
		else
		{
		$this->registry->errorPage('Error', 'An unexpected error occurred');
		}
		
	
	}
	
	private function unbanuser($user_id)
	{
	$changes=array();
	
	$changes['banned']=0;
	
	$this->registry->getObject('db')->updateRecords('users',$changes,'ID='.$user_id);
	
		if($this->registry->getObject('db')->affectedRows()==1)
		{
		$this->registry->errorPage('Unban Successful', 'User has been successfully unbanned');	
		}
		else
		{
		$this->registry->errorPage('Error', 'An unexpected error occurred');
		}
		
	
	}
	
	private function listbanned()
	{
		$sql="SELECT p.fname,p.lname,p.college,p.ID FROM profile p,users u WHERE u.banned=1 AND u.ID=p.ID";
		$cache = $this->registry->getObject('db')->cacheQuery( $sql );
		$this->registry->getObject('template')->buildFromTemplates('header.tpl.php', 'admin/banned.tpl.php', 'footer.tpl.php');
		$this->registry->getObject('template')->getPage()->addTag( 'banneduser', array( 'SQL', $cache ) );
		
	
	}
	
	
	
	private function delete($user_id)
	{
	
	$this->registry->getObject('db')->deleteRecords('users','ID='.$user_id,1);
	
	     if($this->registry->getObject('db')->affectedRows()==1)
		{
		$this->registry->errorPage('Delete Successful', 'User has been successfully deleted.');	
		}
		else
		{
		$this->registry->errorPage('Error', 'An unexpected error occurred');
		}
	
	
	}
	

}	


	