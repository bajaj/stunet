<?php 
class authenticatecontroller{
	private $registry;
	private $model;
        private $urlBits=array();
	
	/*Controller constructor
	@param Registry $registry the registry reference
	@param bool $directCall true if we are calling it directly via the framework, false if we are calling it via another controller
*/
	public function __construct(Registry $registry,$directCall)
	{
		$this->registry=$registry;
		$this->urlBits=$this->registry->getObject('url')->getURLBits();
		if(isset($this->urlBits[1]))
		{
			switch($this->urlBits[1])
			{
				case 'logout':
				$this->logout();
				break;
				case 'login':
				$this->login();
				break;
				case 'username':
				$this->forgotUsername();
				break;
				case 'password':
				$this->forgotPassword();
				break;
				case 'reset-password':
				$this->resetPassword(isset($this->urlBits[2])?(intval($this->urlBits[2])):'',isset($this->urlBits[3])?$this->urlBits[3]:'');
				break;
				case 'register':
				$this->registrationDelegator();
				break;
                                case 'verifyemail':
				$this->verifyemail();
				break;
				case 'resendemail':
				$this->resendemail();
				break;
                            
			}
		}
		
	}
	
	/*Logout the user
	and send them to the login page
	*/
	private function logout()
	{
                if($this->registry->getObject('authenticate')->isLoggedIn())
                {   
                    $user_id=$this->registry->getObject('authenticate')->getUser()->getUserID();
		    $this->registry->getObject('db')->deleterecords('logged_in','user_id='.$user_id,100);
                    $this->registry->getObject('authenticate')->logout();
                    header('Location: '.$this->registry->getSetting('siteurl'));
                }
                else
                {
                    header('Location: '.$this->registry->getSetting('siteurl').'authenticate/login');
                }
	}
	
	/*The user has pressed login, send them to the appropriate page*/
	private function login()
	{
		//If the authentication check has been done (i.e postAuthenticate method is called! ) i.e. The user pressed the Login submit button
		if($this->registry->getObject('authenticate')->isJustProcessed())
		{
			if(isset($_POST['login'])&&$this->registry->getObject('authenticate')->isLoggedIn()==false)
			{//invalid credentials, login failed!
                             $this->registry->getObject('template')->buildFromTemplates('header-nl.tpl.php','authenticate/login/main.tpl.php','footer.tpl.php');
			}
			else
			{//redirect them to the appropriate page, send them to the page they were previously viewing
				//Logged in successfully!
                            // insert their ID in User_logged in table for chatting
				$insert['user_id']=$this->registry->getObject('authenticate')->getUser()->getUserID();
				$this->registry->getObject('db')->insertrecords('logged_in',$insert);
                                header('Location: '.$this->registry->getSetting('siteurl').'home');
			}
		}
		else
		{// if the user dint press the login button
                    
			if($this->registry->getObject('authenticate')->isLoggedIn()==true)
			{//already logged in
                                header('Location: '.isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:$this->registry->getSetting('siteurl').'/home');
			}
			else
			{
                                $this->registry->getObject('template')->getPage()->setTitle($this->registry->getSetting('sitename').' | Login');
				$this->registry->getObject('template')->buildFromTemplates('header-nl.tpl.php','authenticate/login/main.tpl.php','footer.tpl.php');
				$this->registry->getObject('template')->getPage()->addTag('referer',(isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:''));
                                $this->registry->getObject('template')->getPage()->addTag('error','');
			}
		}
	}
	
	private function forgotUsername()
	{
		if(isset($_POST['email'])&&$_POST['email']!='')
		{
                    if(strpos(urldecode($_POST['email']),"\r")==true || strpos(urldecode($_POST['email']),"\n")==true)
                    {//check for header injection
                        $this->registry->getObject('template')->buildFromTemplates('header-nl.tpl.php','authenticate/username/main.tpl.php','footer.tpl.php');
                        $this->registry->getObject('template')->getPage()->addTag('error','<p><strong>Invalid email (security)</p></strong><br/>');
                    }
                    elseif(!preg_match("^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-])*@[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-])*(\.[a-z]{2,4})^",$_POST['email']))
                    {//check for valid email format
                        $this->registry->getObject('template')->buildFromTemplates('header-nl.tpl.php','authenticate/username/main.tpl.php','footer.tpl.php');
			$this->registry->getObject('template')->getPage()->addTag('error','<p><strong>Invalid email address</p></strong><br/>');
                    }
                    else
                    {//check if user exists with the valid email address
			$e=$this->registry->getObject('db')->sanitizeData($_POST['email']);
			$sql="select * from users,profile where users.ID=profile.ID and email = '{$e}'";
			$this->registry->getObject('db')->executeQuery($sql);
			if($this->registry->getObject('db')->numRows()==1)
			{//user found, email them!
				$data=$this->registry->getObject('db')->getRows();
                                $this->registry->getObject('mailout')->startFresh();
				$this->registry->getObject('mailout')->setTo($_POST['email']);
				$this->registry->getObject('mailout')->setSender('');
				$this->registry->getObject('mailout')->setFromName($this->registry->getSetting('cms_name'));
				$this->registry->getObject('mailout')->setSubject('Username details for '.$this->registry->getSetting('sitename'));
				$this->registry->getObject('mailout')->buildFromTemplates('authenticate/forgot/username.tpl.php');
				$tags=array();
				$tags['sitename']=$this->registry->getSetting('sitename');
				$tags['fname']=$data['fname'];
                                $tags['lname']=$data['lname'];
                                $tags['username']=$data['username'];
				$tags['siteurl']=$this->registry->getSetting('siteurl');
				$this->registry->getObject('mailout')->replaceTags($tags);
				$this->registry->getObject('mailout')->setMethod('sendmail');
				$this->registry->getObject('mailout')->send();
				
				$this->registry->getObject('template')->buildFromTemplates('header-nl.tpl.php','authenticate/username/main.tpl.php','footer.tpl.php');
                                $this->registry->getObject('template')->getPage()->addTag('error','<p><strong>Your username has been sent to the email address specified!</strong></p><br/>');
			
			}
			else
			{//user does not exist!
			$this->registry->getObject('template')->buildFromTemplates('header-nl.tpl.php','authenticate/username/main.tpl.php','footer.tpl.php');
			$this->registry->getObject('template')->getPage()->addTag('error','<p><strong>There is no user with the email address specified!</strong></p><br/>');
			}
                    }
		}
		else
		{//form template
                        $this->registry->getObject('template')->getPage()->setTitle($this->registry->getSetting('sitename').' | Forgot Username');
			$this->registry->getObject('template')->buildFromTemplates('header-nl.tpl.php','authenticate/username/main.tpl.php','footer.tpl.php');
			$this->registry->getObject('template')->getPage()->addTag('error','');	
		}
	}
	
	public function generateKey($len=20)
	{
		$chars="abcdefghijklmnopqrstuvwxyz0123456789";
		$tor='';
		for($i=0;$i<$len;$i++)
		{
			$tor.=$chars[rand()%35];
		}
		return $tor;
	}
	
	private function forgotPassword()
	{
		if(isset($_POST['username'])&&$_POST['username'])
		{
                        if(!preg_match("/^([_a-zA-Z0-9])*$/",$_POST['username']))
                        {//check if username contains only alpanumeric character or underscore
                                $this->registry->getObject('template')->buildFromTemplates('header-nl.tpl.php','authenticate/password/main.tpl.php','footer.tpl.php');
				$this->registry->getObject('template')->getPage()->addTag('error','<p><strong>Invalid username</strong></p><br/>');
			}
                        else
                        {
			$u=$this->registry->getObject('db')->sanitizeData($_POST['username']);
			$sql="select * from users,profile where users.ID=profile.ID and username = '{$u}' ";
			$this->registry->getObject('db')->executeQuery($sql);
			if($this->registry->getObject('db')->numRows()==1)
			{
				$data=$this->registry->getObject('db')->getRows();
				//If they have requested a password already, do not send a link!
				if($data['reset_expires']>date('Y-m-d H:i:s')  && $data['reset_key']!=null)
				{
                                        $this->registry->getObject('template')->buildFromTemplates('header-nl.tpl.php','authenticate/password/main.tpl.php','footer.tpl.php');
                                	$this->registry->getObject('template')->getPage()->addTag('error','<p><strong>You have already requested a password reset link in the last 24 hours!<br/>Please wait for 24 hours before requesting another password reset link</strong></p><br/>');
				}
				else // send the link
				{
					$changes=array();
					$rk=$this->generateKey();
					$changes['reset_key']=$rk;
                                        $date=new DateTime();
					$changes['reset_expires']=date('Y-m-d H:i:s', time()+86400);
					$this->registry->getObject('db')->updateRecords('users',$changes,'ID ='.$data['ID']);
					$this->registry->getObject('mailout')->startFresh();
					$this->registry->getObject('mailout')->setTo($data['email']);
					$this->registry->getObject('mailout')->setSender('');
					$this->registry->getObject('mailout')->setSubject('Password reset request for '.$this->registry->getSetting('sitename'));
					$this->registry->getObject('mailout')->setMethod('sendemail');
					$this->registry->getObject('mailout')->setFromName($this->registry->getSetting('cms_name'));
					$tags=array();
                                        $tags['fname']=$data['fname'];
                                        $tags['lname']=$data['lname'];
					$tags['sitename']=$this->registry->getSetting('sitename');
					$tags['siteurl']=$this->registry->getSetting('siteurl');
					$url=$this->registry->getObject('url')->buildURL(array('authenticate','reset-password',$data['ID'],$rk));
					$tags['url']=$url;
					$this->registry->getObject('mailout')->buildFromTemplates('authenticate/forgot/password.tpl.php');
					$this->registry->getObject('mailout')->replaceTags($tags);
					$this->registry->getObject('mailout')->send();
					
					//Notify the user that email has been sent
                                    	$this->registry->getObject('template')->buildFromTemplates('header-nl.tpl.php','authenticate/password/main.tpl.php','footer.tpl.php');
                                	$this->registry->getObject('template')->getPage()->addTag('error','<p><strong>A password reset link has been sent to your email address!</strong></p><br/>');
			
				}
				
			}
			else
			{//no such user exists!
				$this->registry->getObject('template')->buildFromTemplates('header-nl.tpl.php','authenticate/password/main.tpl.php','footer.tpl.php');
				$this->registry->getObject('template')->getPage()->addTag('error','<p><strong>No user exists with the specified username!</strong></p><br/>');
			}
                        }
		}
		else
		{//form template
                $this->registry->getObject('template')->getPage()->setTitle($this->registry->getSetting('sitename').' | Forgot Password');    
		$this->registry->getObject('template')->buildFromTemplates('header-nl.tpl.php','authenticate/password/main.tpl.php','footer.php');
		$this->registry->getObject('template')->getPage()->addTag('error','');
		}
	}
	
	private function resetPassword($user,$key)
	{
            if(is_numeric($user)&&$user!=''&&$key!='')
            {
		$this->registry->getObject('template')->getPage()->addTag('user',$user);
		$this->registry->getObject('template')->getPage()->addTag('key',$key);
		$sql="select * from users where id= '{$user}' and reset_key='{$key}'";
		$this->registry->getObject('db')->executeQuery($sql);
		if($this->registry->getObject('db')->numRows()==1)
		{//if the password reset link is valid
                    
			$data=$this->registry->getObject('db')->getRows();
			//if the password reset link has expired!
			if($data['reset_expires']<date('Y-m-d H:i:s'))
			{
                            $this->registry->getObject('template')->buildFromTemplates('header-nl.tpl.php','authenticate/password/reset.tpl.php','footer.tpl.php');
                            $this->registry->getObject('template')->getPage()->addTag('error','<p><strong>The password reset link has expired, please request a new one</strong></p><br/>');
			}
			else
			{	//if the user has entered new password
				if(isset($_POST['reset']))
				{
                                    
					//check the password to be of minimum 8 characters
					 if($_POST['password']=='' ||!isset($_POST['password']))
                                        {
                                                $this->registry->getObject('template')->buildFromTemplates('header-nl.tpl.php','authenticate/password/reset.tpl.php','footer.tpl.php');
                                                $this->registry->getObject('template')->getPage()->addTag('error','<p><strong>Please enter a password</p></strong><br/>');
                                         }
        
                                        elseif(strlen($_POST['password'])<8)
                                        {
                                             $this->registry->getObject('template')->buildFromTemplates('header-nl.tpl.php','authenticate/password/reset.tpl.php','footer.tpl.php');
                                             $this->registry->getObject('template')->getPage()->addTag('error','<p><strong>Password should be atleast 8 characters long</p></strong><br/>');
                                        }
                                        elseif(!(preg_match("/[a-z]+/",$_POST['password']) && preg_match("/[A-Z]+/",$_POST['password']) && preg_match("/[0-9]+/",$_POST['password'])))
                                        {   
                                              $this->registry->getObject('template')->buildFromTemplates('header-nl.tpl.php','authenticate/password/reset.tpl.php','footer.tpl.php');
                                              $this->registry->getObject('template')->getPage()->addTag('error','<p><strong>Password should contain atleast one uppercase letter, one lowercase letter and one digit</p></strong><br/>');
                                         }
					else
					{//check if the two passwords match
                                            
						if($_POST['password']!=$_POST['confirm_password'])
						{
                                                    $this->registry->getObject('template')->buildFromTemplates('header-nl.tpl.php','authenticate/password/reset.tpl.php','footer.tpl.php');
                                                    $this->registry->getObject('template')->getPage()->addTag('error','<p><strong>The two passwords you entered did not match</p></strong><br/>');
						}
						else
						{//update the database, password successfully resetted!
							$changes=array();
							$changes['password']=md5($_POST['password'].$data['username']);
                                                        $changes['reset_key']=null;
							$this->registry->getObject('db')->updateRecords('users',$changes,'ID ='.$user);
							$this->registry->redirectUser($this->registry->getSetting('siteurl').'authenticate/login','Password reset!','Your password has been successfully reset, you are now being redirected to the login page!');
                                         	}
					}
				}
				else
				{//show the form
                                    $this->registry->getObject('template')->getPage()->setTitle($this->registry->getSetting('sitename').' | Reset Password');
					$this->registry->getObject('template')->buildFromTemplates('header-nl.tpl.php','authenticate/password/reset.tpl.php','footer.tpl.php');
                                        $this->registry->getObject('template')->getPage()->addTag('error','');
				}
			}
		}
		else
		{//if the password reset link is not valid!
                        $this->registry->getObject('template')->getPage()->setTitle($this->registry->getSetting('sitename').' | Reset Password');
			$this->registry->redirectUser($this->registry->getSetting('siteurl').'authenticate/password','Invalid password reset link','The password reset link is not valid');
                        
                }
        }
        else
		{//if the password reset link is not valid!
                        $this->registry->getObject('template')->getPage()->setTitle($this->registry->getSetting('sitename').' | Reset Password');
			$this->registry->redirectUser($this->registry->getSetting('siteurl').'authenticate/password','Invalid password reset link','The password reset link is not valid');
                        
                }
	}
	
	/*Pass control to the registration controller
	*/
	private function registrationDelegator()
	{
		require_once(FRAMEWORK_PATH.'controllers/authenticate/registrationcontroller.php');
		$rc=new Registrationcontroller($this->registry,$this->generateKey());
	}
        private function verifyemail()
        {
            if(isset($this->urlBits[2]))
            {//valid email verification link, continue
                $id=intval($this->urlBits[2]);
                if(isset($this->urlBits[3]))
                {
                   $rk=$this->urlBits[3];
                }
                else
                    $rk='';
                 if(isset($_POST['verify_email'])&&$_POST['verify_email']!='')
                 {  
                      $sql="select * from users where id='{$id}' and verify_email='{$_POST['verify_email']}'";
                        $this->registry->getObject('db')->executeQuery($sql);
                   if($this->registry->getObject('db')->numRows())
                   {//valid user
                       $data=$this->registry->getObject('db')->getRows();
                       if($data['active']==0)
                       {
                       $changes['active']=1;
                       $this->registry->getObject('db')->updateRecords('users',$changes,'ID ='.$id);
                       $this->registry->redirectUser($this->registry->getSetting('siteurl').'authenticate/login','Congrats!','You have successfully verified your email, you can now login from the login page');
                       }
                       else
                       {
                           $this->registry->redirectUser($this->registry->getSetting('siteurl').'authenticate/login','Error','Your email has already been verified, you can login from the login page!');
                       }
                       
                   }
                     else 
                {
                $this->registry->getObject('template')->buildFromTemplates('header-nl.tpl.php','authenticate/register/complete.tpl.php','footer.tpl.php');
                         $this->registry->getObject('template')->getPage()->addTag('error','<p><strong>The confirmation code is not valid!</p></strong><br/>');
                          $this->registry->getObject('template')->getPage()->setTitle($this->registry->getSetting('sitename').' | Verify Email');
                   $this->registry->getObject('template')->getPage()->addTag('verify_email','');
                }
                   
                }
                else
                {
                $this->registry->getObject('template')->buildFromTemplates('header-nl.tpl.php','authenticate/register/complete.tpl.php','footer.tpl.php');
                   $this->registry->getObject('template')->getPage()->addTag('error','');
                    $this->registry->getObject('template')->getPage()->setTitle($this->registry->getSetting('sitename').' | Verify Email');
                   $this->registry->getObject('template')->getPage()->addTag('verify_email',$rk);
                }
            }
            else
            {//invalid email verification link
                $this->registry->redirectUser($this->registry->getSetting('siteurl').'home','Invalid Link','You are now being redirected to the homepage');
            }
            
            
        }
        
        private function resendemail()
        {
            if(isset($_POST['email'])&&$_POST['email']!='')
		{
                    if(strpos(urldecode($_POST['email']),"\r")==true || strpos(urldecode($_POST['email']),"\n")==true)
                    {//check for header injection
                        $this->registry->getObject('template')->buildFromTemplates('header-nl.tpl.php','authenticate/email/main.tpl.php','footer.tpl.php');
                        $this->registry->getObject('template')->getPage()->addTag('error','<p><strong>Invalid email (security)</p></strong><br/>');
                    }
                    elseif(!preg_match("^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-])*@[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-])*(\.[a-z]{2,4})^",$_POST['email']))
                    {//check for valid email format
                        $this->registry->getObject('template')->buildFromTemplates('header-nl.tpl.php','authenticate/email/main.tpl.php','footer.tpl.php');
			$this->registry->getObject('template')->getPage()->addTag('error','<p><strong>Invalid email address</p></strong><br/>');
                    }
                    else
                    {
			$e=$this->registry->getObject('db')->sanitizeData($_POST['email']);
			$sql="select * from users,profile where users.ID = profile. ID and email = '{$e}'";
			$this->registry->getObject('db')->executeQuery($sql);
			if($this->registry->getObject('db')->numRows()==1)
			{//user found, email them!
				$data=$this->registry->getObject('db')->getRows();
                                $this->registry->getObject('mailout')->startFresh();
				$this->registry->getObject('mailout')->setTo($this->registry->getObject('db')->sanitizeData($_POST['email']));
				$this->registry->getObject('mailout')->setSender('');
				$this->registry->getObject('mailout')->setFromName($this->registry->getSetting('cms_name'));
				$this->registry->getObject('mailout')->setSubject('Confirm your email address for '.$this->registry->getSetting('sitename'));
				$this->registry->getObject('mailout')->buildFromTemplates('authenticate/forgot/email.tpl.php');
				$tags=array();
                                $tags['fname']=$data['fname'];
                                $tags['lname']=$data['lname'];
				$tags['sitename']=$this->registry->getSetting('sitename');
				$tags['username']=$data['username'];
				$tags['siteurl']=$this->registry->getSetting('siteurl');
                                $url=$this->registry->getObject('url')->buildURL(array('authenticate','verifyemail',$data['ID'],$data['verify_email']));
                                $tags['verify_email']=$data['verify_email'];
                                $tags['url']=$url;
				$this->registry->getObject('mailout')->replaceTags($tags);
				$this->registry->getObject('mailout')->setMethod('sendmail');
				$this->registry->getObject('mailout')->send();
				$this->registry->getObject('template')->getPage()->setTitle($this->registry->getSetting('sitename').' | Resend Email');
				$this->registry->getObject('template')->buildFromTemplates('header-nl.tpl.php','authenticate/email/main.tpl.php','footer.tpl.php');
                                $this->registry->getObject('template')->getPage()->addTag('error','<p><strong>The verification link has been sent to the email address specified!</strong></p><br/>');	
			}
			else
			{//user does not exist!
                            $this->registry->getObject('template')->getPage()->setTitle($this->registry->getSetting('sitename').' | Resend Email');
			$this->registry->getObject('template')->buildFromTemplates('header-nl.tpl.php','authenticate/email/main.tpl.php','footer.tpl.php');
			$this->registry->getObject('template')->getPage()->addTag('error','<p><strong>There is no user with the email address specified!</strong></p><br/>');
			}
                    }
		}
		else
		{//form template
                    $this->registry->getObject('template')->getPage()->setTitle($this->registry->getSetting('sitename').' | Resend Email');
			$this->registry->getObject('template')->buildFromTemplates('header-nl.tpl.php','authenticate/email/main.tpl.php','footer.tpl.php');
			$this->registry->getObject('template')->getPage()->addTag('error','');	
		}
        }
}
?>