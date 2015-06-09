<?php 
class authenticate
{
	private $registry;
	private $justProcessed;
	private $user;
	private $loggedIn;
	private $loginFailureReason;
	public function __construct(Registry $registry)
	{	
		$this->registry=$registry;
	}
	//This method checks for a live session and user credentials being passed in POST data and calls appropriate functions
	public function checkForAuthentication()
	{ 
		if(isset($_SESSION['sn_auth_session_uid']) && intval($_SESSION['sn_auth_session_uid'])>0)
		{
			$this->sessionAuthenticate(intval($_SESSION['sn_auth_session_uid']));
			if($this->loggedIn==true)
			{
				$this->registry->getObject('template')->getPage()->addTag('error','');
			}
			else
			{
				$this->registry->getObject('template')->getPage()->addTag('error','<p><strong>Invalid username or password! Please Try Again.</strong></p>');
			}
		}
		elseif(isset($_POST['sn_auth_user'])&&$_POST['sn_auth_user']!=''&&isset($_POST['sn_auth_pass'])&&$_POST['sn_auth_pass']!='')
		{
			$this->postAuthenticate($_POST['sn_auth_user'],$_POST['sn_auth_pass']);
			if($this->loggedIn==true)
			{
				$this->registry->getObject('template')->getPage()->addTag('error','');
			}
			else
			{
				$this->registry->getObject('template')->getPage()->addTag('error','<p><strong>'.$this->loginFailureReason.'</strong></p><br/>');
			}
		}
		elseif(isset($_POST['login']))
		{
                    $this->justProcessed=true;
			$this->registry->getObject('template')->getPage()->addTag('error','<p><strong>You must enter a username and password.</strong></p><br/>');
                        
		}
	}//end of check for authentication method
	
	//If the user has tried to log in by submitting a login form, the post authenticate method is called
	private function postAuthenticate($u,$p)
	{
		$this->justProcessed=true;
		require_once(FRAMEWORK_PATH.'registry/user.class.php');
		$this->user=new User($this->registry,0,$u,$p);
		if($this->user->isValid()==1)
		{
			if($this->user->isActive()==false)
			{
				$this->loggedIn=false;
				$this->loginFailureReason='You must confirm your email! Please click on the confirmation link in your email or you can request the verification email again <a href="authenticate/resendemail">here</a> ';
			}
			elseif($this->user->isBanned()==true)
			{   
				$this->loggedIn=false;
				$this->loginFailureReason='You have been banned';
			}
			else
			{
				$this->loggedIn=true;
				$_SESSION['sn_auth_session_uid']=$this->user->getUserID();
			}
		}
		else
		{
			$this->loggedIn=false;
			$this->loginFailureReason='Invalid username or password';
		}
	}
	
//This method is called if the user has not tried to log in by submitting a data form, but has some session data set.
	private function sessionAuthenticate($uid)
	{
		require_once(FRAMEWORK_PATH.'registry/user.class.php');
		$this->user=new User($this->registry,intval( $_SESSION['sn_auth_session_uid'] ),'','');
		if($this->user->isValid())
		{
			if($this->user->isActive()==false)
			{
				$this->loggedIn=false;
				$this->loginFailureReason='inactive';
			}
			elseif($this->user->isBanned()==true)
			{
				$this->loggedIn=false;
				$this->loginFailureReason='banned';
			}
			else
			{
				$this->loggedIn=true;
			}
		}
		else
		{
			$this->loggedIn=false;
			$this->loginFailureReason='nouser';
		}
		if($this->loggedIn==false)
		{
			$this->logout();
		}
	}
	
	//Logout the user
	public function logout()
	{
		$_SESSION['sn_auth_session_uid']='0';
		$this->loggedIn = false;
		$this->user = null;
	}
	
	//Force login the user
	public function forceLogin($username,$password)
	{
		$this->postAuthenticate($username,$password);
	}
	
	public function isLoggedIn()
	{
		return $this->loggedIn;
	}
	public function isJustProcessed()
	{
		return $this->justProcessed;
	}
	public function getUser()
	{
		return $this->user;
	}
}

?>