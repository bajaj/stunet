<?php

//Profile controller
class Profilecontroller{
    private $registry;
    
    public function __construct(Registry $registry,$directCall=true){
        $this->registry=$registry;
        $urlBits=$this->registry->getObject('url')->getURLBits();
        if(isset($urlBits[1])){
            switch($urlBits[1])
            {
                case 'view':
                    $this->staticContentDelegator(intval(isset($urlBits[2])?$urlBits[2]:$this->registry->getObject('authenticate')->getUser()->getUserID()));
                break;
            default:
                $this->staticContentDelegator(intval(isset($urlBits[2])?$urlBits[2]:$this->registry->getObject('authenticate')->getUser()->getUserID()));
                break;
            }
    }
    else{
     $this->staticContentDelegator(intval(isset($urlBits[2])?$urlBits[2]:$this->registry->getObject('authenticate')->getUser()->getUserID()));   
    }
    }
    
    private function staticContentDelegator($user)
    {
        $this->commonTemplateTags($user);
        require_once(FRAMEWORK_PATH.'controllers/profile/profileinformationcontroller.php');
        $profile=new Profileinformationcontroller($this->registry,$user);
        
    }
    
    private function commonTemplateTags($user){
        //get the six random friends
    
        require_once(FRAMEWORK_PATH.'models/relationships.php');
        $relationships=new Relationships($this->registry);
        $cache=$relationships->getByUser($user,true,6);
        $this->registry->getObject('template')->getPage()->addTag('profile_friends',array('SQL',$cache));
        
        //get the profile info
        require_once(FRAMEWORK_PATH.'models/profile.php');
        $profile=new Profile($this->registry,$user);
        $this->registry->getObject('template')->getPage()->addTag('profile_fname',$profile->getFName());
        $this->registry->getObject('template')->getPage()->addTag('profile_lname',$profile->getLName());
        $this->registry->getObject('template')->getPage()->addTag('profile_photo',$profile->getPhoto());
        $this->registry->getObject('template')->getPage()->addTag('profile_ID',$profile->getID());
        $profile="";
    }    
        
}
?>