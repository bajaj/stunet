<?php
//Profile model for interacting with database and getting user's info and editing and populating it's fields accordingly
class Profile{
    private $registry;
    private $profile_fields=array('fname','lname','college','gender','dob','info1','photo','bio','info2','roll_no','mobile_no');
    private $user_fields=array('email','password');
    private $ID;
    private $fname;
    private $lname;
    private $college;
    private $gender;
    private $dob;
    private $dob_friendly;
    private $info1;
    private $info2;
    private $info1_tag;
    private $info2_tag;
    private $photo;
    private $bio;
    private $type;
    private $roll_no;
    private $mobile_no;
    private $email;
    private $password;
    
    public function __construct(Registry $registry,$id=0){
        $this->registry=$registry;
        if($id!=0)
        {
            $sql="select users.ID,fname,lname,dob,gender,college,type,info1,info2,bio,photo,roll_no,mobile_no,username,email,password,DATE_FORMAT(dob,'%D %M %Y') as dob_friendly from profile, users where users.ID=profile.ID and users.ID = ".$id;
            $this->registry->getObject('db')->executeQuery($sql);
            if($this->registry->getObject('db')->numRows()==1){
                $data=$this->registry->getObject('db')->getRows();
                foreach($data as $key=>$value){
                    $this->$key=$value;
                }
            }
        }
    }
    public function setPassword($pw)
    {
        $this->password=$pw;
    }
    
    public function setEmail($pw)
    {
        $this->email=$pw;
    }
    
    public function setRollno($roll)
    {
        $this->roll_no=$roll;
    }
    
    public function setMobileno($mob)
    {
        $this->mobile_no=$mob;
    }
    
    public function setFName($fname){
        $this->fname=$fname;
    }
    
    public function setLname($lname){
        $this->lname=$lname;
    }
    
    public function setCollege($college){
        $this->college=$college;
    }
    
    public function setInfo1($info1){
        $this->info1=$info1;
    }
    
    public function setInfo2($info2){
        $this->info1=$info2;
    }
    
    public function setGender($gender){
        $this->gender=$gender;
    }
    
    public function setDOB($dob){
            $this->dob=$dob;
    }
    
    public function setPhoto($photo){
        $this->photo=$photo;
    }
    
    public function setBio($bio){
        $this->bio=$bio;
    }
    
    public function save(){
        if($this->registry->getObject('authenticate')->isLoggedIn() &&($this->registry->getObject('authenticate')->getUser()->isAdmin()||$this->registry->getObject('authenticate')->getUser()->getUserID()==$this->ID)){
            $changes=array();
            foreach($this->profile_fields as $field){
                $changes[$field]=$this->$field;
            }
            $this->registry->getObject('db')->updateRecords('profile',$changes,'ID ='.$this->ID);
            if($this->registry->getObject('db')->affectedRows()<=1){
               $updates=array();
               foreach($this->user_fields as $field)
               {
                   $updates[$field]=$this->$field;
               }
               $this->registry->getObject('db')->updateRecords('users',$updates,'ID ='.$this->ID);
               if($this->registry->getObject('db')->affectedRows()<=1){
                   return true;
               }
               else
               {
                   return false;
               }
            }
            else{
                return false;
            }
        }
        else{
            return false;
        }
        
    }
    
    public function toTags($prefix=''){
        if($this->type=='student') 
        {
            $this->type='Student';
            $this->info1_tag='Branch';
            $this->info2_tag='Class';
        }
        else
        {
            $this->type='Professor';
            $this->info1_tag='Field of Experience';
            $this->info2_tag='Branch of Experience';
        }
        
        foreach($this as $field => $data){
            if(!is_object($data) && !is_array($data)){
                $this->registry->getObject('template')->getPage()->addTag($prefix.$field,$data);
                }
            }
            $loggedinuser=$this->registry->getObject('authenticate')->getUser()->getUserID();
            if($this->getType()=='Student')
                 $this->registry->getObject('template')->getPage()->addTag('roll_no_entry',"<tr><th>Roll  Number</th><td>".$this->roll_no."</td></tr>");
            else
                $this->registry->getObject('template')->getPage()->addTag('roll_no_entry',"");
            if($this->ID==$loggedinuser)
            {
                $this->registry->getObject('template')->getPage()->addTag('edit',"<tr><th>&nbsp;</th><td><a href='profile/view/edit/'><button>Edit your profile</button></a><br/></td></tr>");
                $this->registry->getObject('template')->getPage()->addTag('profile_connections','View your connections');
                $this->registry->getObject('template')->getPage()->addTag('blogs','<a href="blogs/my-created-blogs">View your blogs</a>');
                $this->registry->getObject('template')->getPage()->addTag('email_entry',"<tr><th>Email</th><td>".$this->email."</td></tr>");
                $this->registry->getObject('template')->getPage()->addTag('mobile_no_entry',"<tr><th>Mobile Number</th><td>".$this->mobile_no."</td></tr>");
            }
            else
            {
               
               $this->registry->getObject('template')->getPage()->addTag('profile_connections','View connections of '.$this->fname.' '.$this->lname);
               require_once(FRAMEWORK_PATH.'models/relationships.php');
               $relationhips=new Relationships($this->registry);
               $network=$relationhips->getNetwork($this->ID);
                       if(in_array($loggedinuser,$network) && !empty($network))
                       {
                               $this->registry->getObject('template')->getPage()->addTag('blogs','<a href="blogs/search-conn-results?searchby=creator&searchname='.$this->fname.'+'.$this->lname.'">View all blogs of '.$this->fname.' '.$this->lname.'</a>');
                               $this->registry->getObject('template')->getPage()->addTag('edit',''); 
                                $this->registry->getObject('template')->getPage()->addTag('email_entry',"<tr><th>Email</th><td>".$this->email."</td></tr>");
                $this->registry->getObject('template')->getPage()->addTag('mobile_no_entry',"<tr><th>Mobile Number</th><td>".$this->mobile_no."</td></tr>");
            
                       }
            else
            {          
                $this->registry->getObject('template')->getPage()->addTag('email_entry',"");
               $this->registry->getObject('template')->getPage()->addTag('mobile_no_entry',"");
                $this->registry->getObject('template')->getPage()->addTag('blogs','<a href="blogs/search-results?searchby=creator&searchname='.$this->fname.'+'.$this->lname.'">View public blogs of '.$this->fname.' '.$this->lname.'</a>');
                $loggedinusertype=$this->registry->getObject('authenticate')->getUser()->getType();
                $existing="select if(usera={$loggedinuser},userb,usera) as user,accepted from relationships where usera={$loggedinuser} or userb={$loggedinuser}";
                $this->registry->getObject('db')->executeQuery($existing);
                $pending='(0,';
                $accepted='(0,';
                while($row=$this->registry->getObject('db')->getRows())
                {
                    if($row['accepted']==1)
                    $accepted.=$row['user'].',';
                    else
                    $pending.=$row['user'].',';
                }
                    $accepted=substr($accepted,0,-1);
                    $pending=substr($pending,0,-1);
                    $pending.=')';
                    $accepted.=')';
                    //Now we have the pending and accepted relationships of logged in user
                if($loggedinusertype=='professor' || $loggedinusertype=='Professor' )
                {
                    $query="select u.ID, if(u.ID in {$accepted},'',if(u.ID in {$pending},if(p.type='professor','<button class=\"disabled\" disabled=\"disabled\"><div>Mate request pending</div></button>',' '),if(p.type='professor',concat('<button onClick=\"createRelationship(',u.ID,',\'Mate\');\"><div id=\"create',u.ID,'Mate\">Add Mate</div></button>'),concat('<button onClick=\"createRelationship(',u.ID,',\'Pupil\');\"><div id=\"create',u.ID,'Pupil\">Add Pupil</div></button>')))) as button from users u, profile p where u.ID=p.ID and u.active=1 and u.banned=0 and u.deleted=0 and u.ID={$this->ID} and u.ID not in ({$loggedinuser}) ";
                }
                elseif($loggedinusertype=='student'||$loggedinusertype=='Student')
                {
                    $query="select u.ID, if(u.ID in {$accepted},'',if(u.ID in {$pending},if(p.type='professor','<button class=\"disabled\" \"disabled=\"disabled\"><div>Professor request pending</div></button>','<button class=\"disabled\" disabled=\"disabled\"><div>Mate request pending</div></button>'),if(p.type='professor',concat('<button onClick=\"createRelationship(',u.ID,',\'Professor\');\"><div id=\"create',u.ID,'Professor\">Add Professor</div></button>'),concat('<button onClick=\"createRelationship(',u.ID,',\'Mate\');\"><div id=\"create',u.ID,'Mate\">Add Mate</div></button>')))) as button from users u, profile p where u.ID=p.ID and u.active=1 and u.banned=0 and u.deleted=0 and u.ID={$this->ID} and u.ID not in ({$loggedinuser})";
                }
                $this->registry->getObject('db')->executeQuery($query);
                if($this->registry->getObject('db')->numRows()==1)
                {
                    $data=$this->registry->getObject('db')->getRows();
                     $this->registry->getObject('template')->getPage()->addTag('edit','<tr><th>&nbsp;</th><td>'.$data['button'].'</td></tr>');
                }
                else
                {
                    $this->registry->getObject('template')->getPage()->addTag('edit','');
                }
            }
          }
          if($this->registry->getObject('authenticate')->getUser()->isAdmin())
          {
		  $sql="select banned from users where ID = ".$this->ID;
		  $this->registry->getObject('db')->executeQuery($sql);
		  $banned=0;
		  if($this->registry->getObject('db')->numRows()==1)
		  {
			$data=$this->registry->getObject('db')->getRows();
			$banned=$data['banned'];
		  }
		  if($banned==1)
		  $banned='<a href="admin/unbanuser/'.$this->ID.'"><button>Unban User</button></a>';
		  else
		  $banned='<a href="admin/banuser/'.$this->ID.'"><button>Ban User</button></a>';
              $this->registry->getObject('template')->getPage()->addTag('edit','<tr><th>&nbsp;</th><td>'.$banned.'
		&nbsp;<a href="admin/delete/'.$this->ID.'"><button>Delete User</button></a></td></tr>');
              $this->registry->getObject('template')->getPage()->addTag('email_entry',"");
          }
    }
    
    public function getFName()
    {
        return $this->fname;
    }
    
    public function getLName(){
        return $this->lname;
    }
    
    public function getPhoto(){
        return $this->photo;
    }
   
    public function getID(){
        return $this->ID;
    }
    
    public function getType(){
        return $this->type;
    }
    public function getDOB()
    {
        return $this->dob;
    }
    public function getGender()
    {
        return $this->gender;
    }
}
?>