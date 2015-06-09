<?php

class Profileinformationcontroller{
    private $registry;
    
    public function __construct(Registry $registry,$user){
        $this->registry=$registry;
        $urlBits=$this->registry->getObject('url')->getURLBits();
        if(isset($urlBits[2])){
            switch($urlBits[2]){
                case 'edit':
                    $this->editProfile();
                    break;
                default:
                    $this->viewProfile($user);
                    break;
            }
        }
        else{
        $this->viewProfile($user);}
    }
    
    private function viewProfile($user){
        $this->registry->getObject('template')->buildFromTemplates('header.tpl.php','profile/information/view.tpl.php','footer.tpl.php');
        require_once(FRAMEWORK_PATH.'models/profile.php');
        $profile= new Profile($this->registry,$user);
        $profile->toTags('p_');
        if($user==$this->registry->getObject('authenticate')->getUser()->getUserID())
            $this->registry->getObject('template')->getPage()->setTitle($this->registry->getSetting('sitename').' | View your Profile');
        else
            $this->registry->getObject('template')->getPage()->setTitle($this->registry->getSetting('sitename').' | View Profile');
            
    }
    
    private function editProfile(){
        if($this->registry->getObject('authenticate')->isLoggedIn()){
            $user=$this->registry->getObject('authenticate')->getUser()->getUserID();
            if(isset($_POST)&& count($_POST)>0){
                $profile=new Profile($this->registry,$user);
                $profile->setBio($this->registry->getObject('db')->sanitizeData($_POST['bio']));
                $profile->setFName($this->registry->getObject('db')->sanitizeData($_POST['fname']));
                $profile->setLname($this->registry->getObject('db')->sanitizeData($_POST['lname']));
                $profile->setInfo1($this->registry->getObject('db')->sanitizeData($_POST['info1']));
                $profile->setInfo2($this->registry->getObject('db')->sanitizeData($_POST['info2']));
                $profile->setCollege($this->registry->getObject('db')->sanitizeData($_POST['college']));
                $profile->setDOB($this->registry->getObject('db')->sanitizeData($_POST['dob_year']).'-'.$this->registry->getObject('db')->sanitizeData($_POST['dob_month']).'-'.$this->registry->getObject('db')->sanitizeData($_POST['dob_day']));
                $profile->setGender($this->registry->getObject('db')->sanitizeData($_POST['gender']));
                if(!empty($_FILES['photo']['name'])){
                    require_once(FRAMEWORK_PATH.'lib/images/imagemanager.class.php');
                    $imagemanager=new Imagemanager();
                    $im=$imagemanager->loadFromPost('profile',$this->registry->getSetting('uploads_path').'profilepics/small/',time());
                    if($im==true){
                        $imagemanager->resize(200,200);
                        $imagemanager->save(FRAMEWORK_PATH.$this->registry->getSetting('uploads_path').'profilepics/small/'.$imagemanager->getName());
                        $profile->setPhoto($imagemanager->getName());
                }
                }
                if(isset($_POST['roll_no']) && $_POST['roll_no']!='')
                {
                    $profile->setRollno($this->registry->getObject('db')->sanitizeData($_POST['roll_no']));
                }
                else
                    $profile->setRollno('-');
                $profile->setMobileno($this->registry->getObject('db')->sanitizeData($_POST['mobile_no']));
                $profile->setEmail($this->registry->getObject('db')->sanitizeData($_POST['email']));
                if(isset($_POST['password']) && $_POST['password']==$_POST['password_confirm'] && isset($_POST['password_confirm']))
                {
                    $profile->setPassword(md5($this->registry->getObject('db')->sanitizeData($_POST['password'].$this->registry->getObject('authenticate')->getUser()->getUsername())));
                }
                    
                $profile->save();
                header('Location: '.$this->registry->getSetting('siteurl').'profile/view');
               
                //$this->registry->redirectUser($this->registry->getSetting('siteurl').'profile/view','Profile edited!','Your profile has been edited successfully');
            }
            else{
                $this->registry->getObject('template')->getPage()->setTitle($this->registry->getSetting('sitename').' | Edit Profile');
                $this->registry->getObject('template')->buildFromTemplates('header.tpl.php','profile/information/edit.tpl.php','footer.tpl.php');
                require_once(FRAMEWORK_PATH.'models/profile.php');
                $profile=new Profile($this->registry,$user);
                $profile->toTags('p_');
                $dob=$profile->getDOB();
                $gender=($profile->getGender()=='Male')?0:1;
                $dob=explode('-',$dob);
                $script="";
          $script.="<script type='text/javascript'>";
          $script.="document.getElementById('gender').selectedIndex=".$gender.';';
          $script.="document.getElementById('dob_day').selectedIndex=".intval($dob[2]).';';
          $script.="document.getElementById('dob_month').selectedIndex=".intval($dob[1]).';';
          $script.="document.getElementById('dob_year').selectedIndex=".(2013-$dob[0]+1).';';
          $script.="setOptions('".$profile->getType()."');";
          $script.="</script>";
          $this->registry->getObject('template')->getPage()->addTag('script',$script);
            }
        }
        else{
            $this->registry->errorPage('Not logged in','You must be logged in to edit your profile!');
        }
    }
    
}
?>
