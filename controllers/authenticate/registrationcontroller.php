

<?php

class Registrationcontroller
{
    private $registry;
    
    private $sanitizedValues=array();
    
    private $profile=array();
    private $submittedValues=array('fname'=>'','lname'=>'','username'=>'','email'=>'','email_confirm'=>'','college'=>'','info1'=>'',
        'info2'=>'','type'=>'','gender'=>'','dob_year'=>'','dob_day'=>'','dob_month'=>'','roll_no'=>'','mobile_no'=>'','bio'=>'');
    
    private $errors=array('fname'=>'','lname'=>'','username'=>'','email'=>'','email_confirm'=>'','college'=>'','info1'=>'',
        'info2'=>'','type'=>'','gender'=>'','dob'=>'','terms'=>'','password'=>'',
        'password_confirm'=>'','captcha'=>'','roll_no'=>'','mobile_no'=>'','bio'=>'');
    
    private $activeValue=0;
    
    
    private $key=0;//email verification key
    
    public function __construct(Registry $registry,$key)
    {
        $this->registry=$registry;
        $this->key=$key;
        if(isset($_POST['process_registration']))
        {
            if($this->checkRegistration())
            {
                $userid=$this->processRegistration();
               // $this->registry->getObject('mailout')->startFresh();
                //$this->registry->getObject('mailout')->setTo($this->sanitizedValues['email']);
               // $this->registry->getObject('mailout')->setSender('');
                //$this->registry->getObject('mailout')->setSubject('Confirm your email address for '.$this->registry->getSetting('sitename'));
               // $this->registry->getObject('mailout')->buildFromTemplates('authenticate/forgot/email.tpl.php');
               // $this->registry->getObject('mailout')->setFromName($this->registry->getSetting('cms_name'));
               // $this->registry->getObject('mailout')->setMethod('sendemail');
               // $tags=array();
               // $tags['fname']=$this->profile['fname'];
               // $tags['lname']=$this->profile['lname'];
               // $tags['sitename']=$this->registry->getSetting('sitename');
               // $tags['siteurl']=$this->registry->getSetting('siteurl');
               // $tags['verify_email']=$this->key;
               // $tags['url']=$this->registry->getObject('url')->buildURL(array('authenticate','verifyemail',$userid,$this->key));
              //  $this->registry->getObject('mailout')->replaceTags($tags);
              //  $this->registry->getObject('mailout')->send();
			  
			  //Line for sms
			  $this->sendsms($this->profile['mobile_no'],$this->key);
                mkdir(FRAMEWORK_PATH.'uploads/files/'.$this->sanitizedValues['username']);//Create directory for personal document manager
                header('Location: '.$this->registry->getObject('url')->buildURL(array('authenticate','verifyemail',$userid)));
            }
            else
            {//There are errors in the registration
               $this->uiRegister(true); 
            }
        }
        else
        {
            $this->uiRegister(false);
        }
                
    }
	
	private function sendsms($no,$key)
	{
	require_once( FRAMEWORK_PATH . 'models/sms.php' );
	
    $msg="Stunet code ".$key;
	
//	$msg="student dashboard  S";
	$sms=new Sms($this->registry,0,"sfd",0);
	
	$sms->sendsms2($no,$msg);
	
	}	
	
    
    private function checkRegistration()
    {
        $allClear=true;
        //check first name
        if($_POST['fname']==''||!isset($_POST['fname']))
        {   
            $allClear=false;
            $this->errors['fname']='<div class="error">Please enter your first name</div><br/>';
        }
        elseif(!preg_match("/^[a-zA-Z]+$/",$_POST['fname']))
        {
            $allClear=false;
            $this->errors['fname']='<div class="error">First name should contain only alphabets</div><br/>';
        }
        
        //check last name
        if($_POST['lname']==''||!isset($_POST['lname']))
        {
            $allClear=false;
            $this->errors['lname']='<div class="error">Please enter your last name</div><br/>';
        }
        elseif(!preg_match("/^[a-zA-Z]+$/", $_POST['lname']))
        {
            $allClear=false;
            $this->errors['lname']='<div class="error">Last name should contain only alphabets</div><br/>';
        }
        
        //check username
        if($_POST['username']==''||!isset($_POST['username']))
        {
            $allClear=false;
            $this->errors['username']='<div class="error">Please enter an username</div><br/>';
        }
        elseif(strlen($_POST['username'])>25)
        {
            $allClear=false;
            $this->errors['username']='<div class="error">Username can of maximum 25 characters</div><br/>';
        }
        elseif(!preg_match("/^[_a-zA-Z0-9]+$/",$_POST['username']))
        {
            $allClear=false;
            $this->errors['username']='<div class="error">Username can contain only alphabets, digits or underscore</div><br/>';
        }
        else
        {
            $u=$this->registry->getObject('db')->sanitizeData($_POST['username']);
            $sql="select * from users where username = '".$u."'";
            $this->registry->getObject('db')->executeQuery($sql);
            if($this->registry->getObject('db')->numRows()==1)
            {
                $allClear=false;
                $this->errors['username']='<div class="error">This username is already in use, please select a different one</div><br/>';
            }
        }
        
        //check password
        if($_POST['password']=='' ||!isset($_POST['password']))
        {
            $allClear=false;
            $this->errors['password']='<div class="error">Please enter a password</div><br/>';
        }
        
        elseif(strlen($_POST['password'])<8)
        {
            $allClear=false;
            $this->errors['password']='<div class="error">Password should be atleast 8 characters long</div><br/>';
        }
        elseif(!(preg_match("/[a-z]+/",$_POST['password']) && preg_match("/[A-Z]+/",$_POST['password']) && preg_match("/[0-9]+/",$_POST['password'])))
        {
            $allClear=false;
            $this->errors['password']='<div class="error">Password should contain atleast one uppercase letter, one lowercase letter and one digit</div><br/>';
        }
        
        if($this->errors['password']=='')
        //check password_confirm
        {
            if($_POST['password_confirm']!=$_POST['password']) 
            {   
                $allClear=false;
                $this->errors['password_confirm']='<div class="error">The two passwords you entered did not match</div><br/>';
            }
        }
        //check email
        if($_POST['email']=='' || !isset($_POST))
        {
              $allClear=false;
            $this->errors['email']='<div class="error">Please specify your email address</div><br/>';
        }
        elseif(strpos(urldecode($_POST['email']),"\r")||strpos(urldecode($_POST['email']),"\n"))
        {
            $allClear=false;
            $this->errors['email']='<div class="error">The email address specified is invalid (security)</div><br/>';
        }
        
        elseif(!preg_match("^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-])*@[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-])*(\.[a-z]{2,4})^",$_POST['email']))
        {
            $allClear=false;
            $this->errors['email']='<div class="error">The email address specified is invalid</div><br/>';
        }
        
        else
        {
            $e=$this->registry->getObject('db')->sanitizeData($_POST['email']);
            $sql="select * from users where email = '".$e."'";
            $this->registry->getObject('db')->executeQuery($sql);
            if($this->registry->getObject('db')->numRows()==1)
            {
                $allClear=false;
                $this->errors['email']='<div class="error">The email address specified is already in use</div><br/>';
            }
        }
        
        //check email_confirm
        if($_POST['email']!=$_POST['email_confirm'])
        {
            $allClear=false;
            $this->errors['email_confirm']='<div class="error">The two email addresses you entered did not match</div><br/>';
        }
        
        //check college
        if($_POST['college']=='' || !isset($_POST['college']))
        {
            $allClear=false;
            $this->errors['college']='<div class="error">Please enter your college name</div><br/>';
        }
        
        elseif(!preg_match("/^[\._a-zA-Z0-9- ]+$/",$_POST['college']))
        {
            $allClear=false;
            $this->errors['college']='<div class="error">Invalid college name</div><br/>';
        }
        
        //check type
        if($_POST['type']=='none' || !isset($_POST['type']) || !in_array($_POST['type'],array('professor','student')))
        {
            $allClear=false;
            $this->errors['type']='<div class="error">Please specify whether you are a student or a professor</div><br/>';
        }
        
        //check student related fields
        if($_POST['type']=='student')
        {
            if($_POST['info1']=='' || !isset($_POST['info1']))
            {
                 $allClear=false;
                 $this->errors['info1']='<div class="error">Please specify your branch of studies</div><br/>';
            }
            elseif(!preg_match("/^[\.a-zA-Z0-9- ]+$/",$_POST['info1']))
            {
                 $allClear=false;
                 $this->errors['info1']='<div class="error">Invalid branch name</div><br/>';
            }
            
            if($_POST['info2']=='' || !isset($_POST['info2']))
            {
                $allClear=false;
                $this->errors['info2']='<div class="error">Please specify the class you are currently studying in</div><br/>';
            }
            elseif(!preg_match("/^[\._a-zA-Z0-9- ]+$/",$_POST['info2']))
            {
                 $allClear=false;
                 $this->errors['info2']='<div class="error">Invalid class name</div><br/>';
            }
        }
        
        //check professor related fields
        elseif($_POST['type']=='professor')
        {
            if($_POST['info1']=='' || !isset($_POST['info1']))
            {
                 $allClear=false;
                 $this->errors['info1']='<div class="error">Please specify your field of experience</div><br/>';
            }
            elseif(!preg_match("/^[\.a-zA-Z0-9- ]+$/",$_POST['info1']))
            {
                 $allClear=false;
                 $this->errors['info1']='<div class="error">Invalid field name</div><br/>';
            }
            
            if($_POST['info2']=='' || !isset($_POST['info2']))
            {
                $allClear=false;
                $this->errors['info2']='<div class="error">Please specify your years of experience</div><br/>';
            }
            elseif(!preg_match("/^[0-9]+$/",$_POST['info2']))
            {
                 $allClear=false;
                 $this->errors['info2']='<div class="error">Invalid years, please specify a whole number</div><br/>';
            }
        }
        
        //check gender
        if($_POST['gender']=='none' || !isset($_POST['gender']) || !in_array($_POST['gender'],array('Male','Female')))
        {
             $allClear=false;
             $this->errors['gender']='<div class="error">Please specify your gender</div><br/>';
         }
        
        //check dob day
         if($_POST['dob_day']=='-1' || !isset($_POST['dob_day']) || $_POST['dob_month']=='-1'||!isset($_POST['dob_month']) || $_POST['dob_year']=='-1' || !isset($_POST['dob_year']))
         {
              $allClear=false;
              $this->errors['dob']='<div class="error">Please specify your complete date of birth</div><br/>';
         }
        
        else
        {
            //check DOB as a whole
            if(!$this->validateDOB($_POST['dob_day'],$_POST['dob_month'],$_POST['dob_year']))
            {
                $allClear=false;
                $this->errors['dob']='<div class="error">Invalid date of birth</div><br/>';
            }
        
            else
            {
                $years=date('Y')-$_POST['dob_year'];
                if($years<13)
                {
                    $allClear=false;
                    $this->errors['dob']='<div class="error">You must be atleast 13 years of age to use our site</div><br/>';
                }
            }
        }
        
        //check terms
        if(!isset($_POST['terms'])||$_POST['terms']!=1)
        {
            $allClear=false;
            $this->errors['terms']='<div class="error">You must accept our terms and conditions</div>';
        }
        
        //captcha check
        require_once(FRAMEWORK_PATH.'recaptchalib.php');
        $privatekey = "6Ld7o9sSAAAAADQ3AVfFbbrnvw49BGCFH0wPGY2Y";
        $resp = recaptcha_check_answer ($privatekey,$_SERVER["REMOTE_ADDR"],$_POST["recaptcha_challenge_field"],$_POST["recaptcha_response_field"]);
        if (!$resp->is_valid) 
        {
            $allClear=false;
            $this->errors['captcha']="<div class='error'>The captcha entered was wrong!</div>";
        } 
        
        //check roll_no
        if($_POST['type']=='student' && ($_POST['roll_no']==''||!isset($_POST['roll_no'])))
        {
             $allClear=false;
             $this->errors['dob']='<div class="error">Please specify your roll number</div><br/>';
        }
        
        //check mobile_no
        
        //Check done, now populate variables!
        if($allClear==true)
	{       //User table
		$this->sanitizedValues['username']=$this->registry->getObject('db')->sanitizeData($_POST['username']);
		$this->sanitizedValues['email']=$this->registry->getObject('db')->sanitizeData($_POST['email']);
		$this->sanitizedValues['password']=md5($this->registry->getObject('db')->sanitizeData($_POST['password'].$this->sanitizedValues['username']));
		$this->sanitizedValues['active']=0;
		$this->sanitizedValues['banned']=0;
		$this->sanitizedValues['admin']=0;
                $this->sanitizedValues['deleted']=0;
                $this->sanitizedValues['verify_email']=$this->key;
                
                //Variable for profile table
                $this->profile['fname']=$this->registry->getObject('db')->sanitizeData($_POST['fname']);
                $this->profile['lname']=$this->registry->getObject('db')->sanitizeData($_POST['lname']);
                $this->profile['college']=$this->registry->getObject('db')->sanitizeData($_POST['college']);
                $this->profile['type']=$this->registry->getObject('db')->sanitizeData($_POST['type']);
                $this->profile['gender']=$this->registry->getObject('db')->sanitizeData($_POST['gender']);
                  $this->profile['info1']=$this->registry->getObject('db')->sanitizeData($_POST['info1']);
                $this->profile['info2']=$this->registry->getObject('db')->sanitizeData($_POST['info2']);               
                $this->profile['dob']=$_POST['dob_year'].'-'.$_POST['dob_month'].'-'.$_POST['dob_day'];
                if($this->profile['type']=='student') $this->profile['roll_no']=$this->registry->getObject('db')->sanitizeData($_POST['roll_no']);
                else $this->profile['roll_no']='-';
                $this->profile['mobile_no']=$this->registry->getObject('db')->sanitizeData($_POST['mobile_no']);
                $this->profile['bio']=$this->registry->getObject('db')->sanitizeData($_POST['bio']); 
                if(!empty($_FILES['photo']['name']))
                    {
                    require_once(FRAMEWORK_PATH.'lib/images/imagemanager.class.php');
                    $imagemanager=new Imagemanager();
                    $im=$imagemanager->loadFromPost('photo',$this->registry->getSetting('uploads_path').'profilepics/small/',time());
                    if($im==true){
                        $imagemanager->resize(200,200);
                        $imagemanager->save(FRAMEWORK_PATH.$this->registry->getSetting('uploads_path').'profilepics/small/'.$imagemanager->getName());
                        $this->profile['photo']=$imagemanager->getName();
                }}
                if($this->profile['photo']==null)
                {
                    if($this->profile['gender']=='Male')$this->profile['photo']='boy.jpg';
                elseif($this->profile['gender']=='Female')$this->profile['photo']='girl.jpg';
              
                }    
                    
		return true;
	}
	else
	{
                $gender=array('none','Male','Female');
                $type=array('student','professor');
		$this->submittedValues['username']=$_POST['username'];
		$this->submittedValues['email']=$_POST['email'];
                $this->submittedValues['email_confirm']=$_POST['email_confirm'];
                $this->submittedValues['fname']=$_POST['fname'];
                $this->submittedValues['lname']=$_POST['lname'];
                $this->submittedValues['college']=$_POST['college'];
                $this->submittedValues['type']=array_search($_POST['type'],$type);
                $this->submittedValues['info1']=$_POST['info1'];
                $this->submittedValues['info2']=$_POST['info2']; 
                $this->submittedValues['dob_day']=$_POST['dob_day'];
                $this->submittedValues['dob_month']=$_POST['dob_month'];
                $this->submittedValues['dob_year']=$_POST['dob_year'];
                $this->submittedValues['bio']=$_POST['bio'];
                $this->submittedValues['roll_no']=(isset($_POST['roll_no']))?$_POST['roll_no']:'';
                $this->submittedValues['mobile_no']=$_POST['mobile_no'];
                $this->submittedValues['gender']=array_search($_POST['gender'],$gender);
                return false;
	}
    }
    
    
    private function uiRegister($error)
    {
        $this->registry->getObject('template')->getPage()->setTitle($this->registry->getSetting('sitename').' | Register');
	$this->registry->getObject('template')->buildFromTemplates('header-nl.tpl.php','authenticate/register/main.tpl.php','footer.tpl.php');
        $script="";
        if($error)
        {
            $day=($this->submittedValues['dob_day']==-1)?0:$this->submittedValues['dob_day'];
            $month=($this->submittedValues['dob_month']==-1)?0:$this->submittedValues['dob_month'];
            $year=($this->submittedValues['dob_year']==-1)?0:(2013-$this->submittedValues['dob_year']+1);
          $script.="<script type='text/javascript'>";
          $script.="document.getElementById('gender').selectedIndex=".$this->submittedValues['gender'].';';
          $script.="document.getElementById('type').selectedIndex=".$this->submittedValues['type'].';';
          $script.="document.getElementById('dob_day').selectedIndex=".$day.';';
          $script.="document.getElementById('dob_month').selectedIndex=".$month.';';
          $script.="document.getElementById('dob_year').selectedIndex=".$year.';';
          $script.="setOptions('".$_POST['type']."');";
          $script.="</script>";
          
        }
        $this->registry->getObject('template')->getPage()->addTag('script',$script);
         foreach($this->errors as $field=>$data)
            {
                $this->registry->getObject('template')->getPage()->addTag('error_'.$field,$data);
            }
            foreach($this->submittedValues as $field=>$data)
            {
                $this->registry->getObject('template')->getPage()->addTag($field,$data);
            }
    }
    
    private function validateDOB($d,$m,$y)
    {
         if($m<1||$m>12||$d<0||$d>31||$y>date('Y'))
        {
            return false;
        }
        else
        {
            if(($m==4||$m==6||$m==9||$m==11))
            {
                if($d<=30)
                    return true;
                else
                    return false;
            }
            elseif($m!=2)
            {
                if($d<=31)
                    return true;
                else
                    return false;
            }
            else
            {
                if($y%4!=0)
                {
                    if($d<=28)
                        return true;
                    else
                        return false;
                }
                else
                {
                    if($y%100!=0)
                    {
                        if($d<=29)
                            return true;
                        else
                            return false;
                    }
                    else
                    {
                        if($y%400!=0)
                        {
                            if($d<=28)
                                return true;
                            else
                                return false;
                        }
                        else
                        {
                            if($d<=29)
                                return true;
                            else
                                return false;
                        }
                    }
                }
            }
        }
    }
    
    private function processRegistration()
    {
        $this->registry->getObject('db')->insertRecords('users',$this->sanitizedValues);
        $uid=$this->registry->getObject('db')->lastInsertID();
        $this->profile['ID']=$uid;
        $this->registry->getObject('db')->insertRecords('profile',$this->profile);
        return $uid;
    }
}
/*
class Registrationcontroller
{
//reference to the registry
private $registry;

//Standard registration fields
private $fields=array('user'=>'username','password'=>'password','password_confirm'=>'password_confirmation','email'=>'email confirmation');

//Any errors in registration
private $registrationErrors=array();

//Array of error label classes - allows us to make a field a different color, to indicate there were errors
private $registrationErrorLabels=array();

//Array of values the user has submitted while registering
private $submittedValues=array();

//Sanitized versions of the values the user has submitted
private $sanitizedValues=array();

//Users would require email verification! So not active by default!
private $activeValue=0;

private $key=0;

public function __construct(Registry $registry,$key)
{
    $this->key=$key;
	$this->registry=$registry;
	require_once(FRAMEWORK_PATH.'controllers/authenticate/registrationcontrollerextention.php');
	$this->registrationExtention=new Registrationcontrollerextention($this->registry);
	
	//if the user has submitted the registration form
	if(isset($_POST['process_registration']))
	{
		//check if form was submitted correctly
		if($this->checkRegistration())
		{
			$userId=$this->processRegistration();
			//since we have set the user active by default, we automatically login the user!
			//if($this->activeValue==1)
			//{
			//	$this->registry->getObject('authenticate')->forceLogin($this->submittedValues['user'],$this->submittedValues['password']);
                        //        $this->uiRegistrationProcessed();
			//}
                        //else
                       // {
                               	$this->registry->getObject('mailout')->startFresh();
					$this->registry->getObject('mailout')->setTo($this->sanitizedValues['email']);
					$this->registry->getObject('mailout')->setSender('');
					$this->registry->getObject('mailout')->setSubject('Confirm your email address for '.$this->registry->getSetting('sitename'));
					$this->registry->getObject('mailout')->setMethod('sendemail');
					$this->registry->getObject('mailout')->setFromName($this->registry->getSetting('cms_name'));
					$tags=array();
					$tags['username']=$this->sanitizedValues['username'];
					$tags['sitename']=$this->registry->getSetting('sitename');
					$tags['siteurl']=$this->registry->getSetting('siteurl');
                                        $tags['verify_email']=$this->key;
					$url=$this->registry->getObject('url')->buildURL(array('authenticate','verifyemail',$this->registry->getObject('db')->lastInsertID(),$this->key));
					$tags['url']=$url;
					$this->registry->getObject('mailout')->buildFromTemplates('authenticate/forgot/email.tpl.php');
					$this->registry->getObject('mailout')->replaceTags($tags);
					$this->registry->getObject('mailout')->send();
					
					   mkdir(FRAMEWORK_PATH.'uploads/files/'.$this->sanitizedValues['username']);
                            	$this->registry->redirectUser($this->registry->getSetting('siteurl').'authenticate/verifyemail/'.$this->registry->getObject('db')->lastInsertID(),'Thank you for joining Dash Board!', 'Please wait while you are being redirected!');
        
                        //}
                        
		}
		//if the registration wasn't successful, we display the user interface, passing a parameter to indicate that errors are to be displayed
		else
		{
			$this->uiRegister(true);
		}
	}
	
	//if the user is just viewing the registration form
	else
	{
		$this->uiRegister(false);
	}
}


//Form validation using PHP, checking registration

public function checkRegistration()
{
	//allClear variable is true if there are no errors, is set to false when an error is encountered
	$allClear=true;
	
	//check for any blank fields
	/*foreach($this->fields as $field=>$name)
	{
		if(!isset($_POST[''.$field])||$_POST[''.$field]=='')
		{
			$allClear=false;
			$this->registrationErrors[]='You must enter a '.$name;
			$this->registrationErrorLabels[''.$field.'_label']='error';
		}
	}*/
	/*
	//check if password and confirm password fields match
	if($_POST['password']!=$_POST['password_confirm'])
	{
		$allClear=false;
		$this->registrationErrors[]="The passwords do not match";
		$this->registrationErrorLabels['password_label']='error';
		$this->registrationErrorLabels['password_confirm_label']='error';
	}
      
	
	//check if password is of atleast 8 characters
	if(strlen($_POST['password'])<8)
	{
		$allClear=false;
		$this->registrationErrors[]="Password should be atleast 8 characters long";
		$this->registrationErrorLabels['passsword_label']='error';
		$this->registrationErrorLabels['password_confirm_label']='error';
        }
	//check email headers for header injection. Header injection is a web application security vulnerability where in an attacker inserts a sequence of URL encoded characters "%0d%0a" that are the equivalents of "\r\n" which is a carriage return and line feed.
	if(strpos(urldecode($_POST['email']),"\r")==true||strpos(urldecode($_POST['email']),"\n")==true)
	{
		$allClear=false;
		$this->registrationErrors[]="Your email is not valid (security)";
		$this->registrationErrorLabels['email_label']='error';
	}
	//check if email is valid or not
	//The pattern check: 
	//Starting with One or more (alphanumeric character or _ or -) followed by zero or more(an optional . and then alphanumeric character or _ or -) followed  by @ followed  One or more (alphanumeric character or _ or -) followed by zero or more(an optional . and then alphanumeric character or _ or -) followed by a compulsory . and then 2-4 alphabets       
	if(!preg_match("^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-])*@[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-])*(\.[a-z]{2,4})^",$_POST['email']))
	{
		$allClear=false;
		$this->registrationErrors[]="You must enter a valid email address";
		$this->registrationErrorLabels['email_label']='error';
	}
	
	//terms accepted
	if(!isset($_POST['terms'])||$_POST['terms']!=1)
	{
		$allClear=false;
		$this->registrationErrors[]="You must accept our terms and conditions";
		$this->registrationErrorLabels['terms_label']='error';
	}
	
	//double user+email check
	$u=$this->registry->getObject('db')->sanitizeData($_POST['user']);
	$e=$this->registry->getObject('db')->sanitizeData($_POST['email']);
	$sql="select * from users where username='{$u}' or email='{$e}'";
	$this->registry->getObject('db')->executeQuery($sql);
	
	if($this->registry->getObject('db')->numRows()==2)
	{
		$allClear=false;
		$this->registrationErrors[]="Both the username and email are already in use";
		$this->registrationErrorLabels['email_label']='error';
		$this->registrationErrorLabels['user_label']='error';
	}
	elseif($this->registry->getObject('db')->numRows()==1)
	{
		$data=$this->registry->getObject('db')->getRows();
		if($data['username']==$u&&$data['email']==$e)
		{
			$allClear=false;
			$this->registrationErrors[]="Both the username and email address are already in use";
			$this->registrationErrorLabels['email_label']='error';
			$this->registrationErrorLabels['user_label']='error';
		}
		elseif($data['username']==$u)
		{
			$allClear=false;
			$this->registrationErrors[]="The username is already in use";
			$this->registrationErrorLabel['user_label']='error';
		}
		else
		{
			$allClear=false;
			$this->registrationErrors[]="The email address is already in use";
			$this->registrationErrorLabel['email_label']='error';
		}
	}
     
	//captcha
	if($this->registry->getSetting('captcha.enabled')==1)
	{
		//captcha check
             require_once(FRAMEWORK_PATH.'recaptchalib.php');
  $privatekey = "6Ld7o9sSAAAAADQ3AVfFbbrnvw49BGCFH0wPGY2Y";
  $resp = recaptcha_check_answer ($privatekey,
                                $_SERVER["REMOTE_ADDR"],
                                $_POST["recaptcha_challenge_field"],
                                $_POST["recaptcha_response_field"]);
if (!$resp->is_valid) {
    // What happens when the CAPTCHA was entered incorrectly
    //die ("The reCAPTCHA wasn't entered correctly. Go back and try it again." .
      //   "(reCAPTCHA said: " . $resp->error . ")");
    $allClear=false;
			$this->registrationErrors[]="The captcha entered was wrong!";
                        $this->registrationErrorLabel['recaptcha_challenge_field']='error';
			
  } 
	}
	
	if($this->registrationExtention->checkRegistrationSubmission()==false)
	{
		$allClear=false;
	}
      
	
	if($allClear==true)
	{
		$this->sanitizedValues['username']=$u;
		$this->sanitizedValues['email']=$e;
		$this->sanitizedValues['password_hash']=md5($_POST['password']);
		$this->sanitizedValues['active']=$this->activeValue;
		$this->sanitizedValues['banned']=0;
		$this->sanitizedValues['admin']=0;
                $this->sanitizedValues['verify_email']=$this->key;
		$this->submittedValues['user']=$_POST['user'];
		$this->submittedValues['password']=$_POST['password'];
		return true;
	}
	else
	{
		$this->submittedValues['user']=$_POST['user'];
		$this->submittedValues['email']=$_POST['email'];
		$this->submittedValues['password']=$_POST['password'];
		$this->submittedValues['password_confirm']=$_POST['password_confirm'];
		$this->submittedValues['captcha']=(isset($_POST['captcha']))?$_POST['captcha']:'';
		return false;
	}
}

/*Process the users registration, create the user and users profile
Insert the user's core data into the database
@return int*/
/*
private function processRegistration()
{
	//insert the users entry
	$this->registry->getObject('db')->insertRecords('users',$this->sanitizedValues);
	
	//get the user's id
	$uid=$this->registry->getObject('db')->lastInsertID();
	
	//call the extention function to insert the profile
	$this->registrationExtention->processRegistration($uid);
	
	return $uid;
}

private function uiRegistrationProcessed()
{
	$this->registry->getObject('template')->getPage()->setTitle('Register for '.$this->registry->getSetting('sitename'));
	$this->registry->getObject('template')->buildFromTemplates('header-nl.tpl.php','authenticate/register/complete.tpl.php','footer.tpl.php');
}

private function uiRegister($error)
{
	$this->registry->getObject('template')->getPage()->setTitle('Register for '.$this->registry->getSetting('sitename'));
	$this->registry->getObject('template')->buildFromTemplates('header-nl.tpl.php','authenticate/register/main.tpl.php','footer.tpl.php');
	//blank out all field values
	$fields=array_keys($this->fields);
	$fields=array_merge($fields,$this->registrationExtention->getExtraFields());
	foreach($fields as $field)
	{
		$this->registry->getObject('template')->getPage()->addTag(''.$field.'_label','');
		$this->registry->getObject('template')->getPage()->addTag(''.$field,'');
	}
	if($error==false)
	{
		$this->registry->getObject('template')->getPage()->addTag('error','');
	}
	else
	{
		$this->registry->getObject('template')->addTemplateBit('error','authenticate/register/error.tpl.php');
		$errorsData=array();
		$errors=array_merge($this->registrationErrors,$this->registrationExtention->getRegistrationErrors());
		foreach($errors as $error)
		{
			$errorsData[]=array('error_text'=>$error);
		}
		$errorsCache=$this->registry->getObject('db')->cacheData($errorsData);
		$this->registry->getObject('template')->getPage()->addTag('errors',array('DATA',$errorsCache));
		$toFill=array_merge($this->submittedValues,$this->registrationExtention->getRegistrationValues(),$this->registrationErrorLabels,$this->registrationExtention->getErrorLabels());
		foreach($toFill as $tag=>$value)
		{
			$this->registry->getObject('template')->getPage()->addTag($tag,$value);
		}
	}
}

}
?>*/