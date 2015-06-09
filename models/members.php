<?php

class  Members
{
    private $registry;
    private $type;
    private $user;
    public function __construct(Registry $registry)
    {
        $this->registry=$registry;
        $this->user=$this->registry->getObject('authenticate')->getUser()->getUserID();
        $this->type=$this->registry->getObject('authenticate')->getUser()->getType();
    }

//SUBSTRING_INDEX(str,delim,count)

//Returns the substring from string str before count occurrences of the delimiter delim. If count is positive, everything to the left of the final delimiter (counting from the left) is returned. If count is negative, everything to the right of the final delimiter (counting from the right) is returned.
        
    public function searchMembers($name, $by='name',$offset=0)
    {
        require_once(FRAMEWORK_PATH.'lib/pagination/pagination.class.php');
        $name=$this->registry->getObject('db')->sanitizeData($name);
        $pagination=new Pagination($this->registry);
        $pagination->setMethod('cache');
        $pagination->setOffset($offset);
        $pagination->setLimit(12); 
        $existing="select if(usera={$this->user},userb,usera) as user,accepted from relationships where usera={$this->user} or userb={$this->user}";
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
//        echo $accepted;
  //      echo $pending;
        if($by=='name')
        {
            $name=explode("+",$name);
            if(!isset($name[1]))$name[1]='';
            if($this->type=='professor')//
                $query="select concat(p.fname,' ',p.lname) as fullname, u.ID, p.fname, p.lname, if(p.type='professor', concat('Teaching at ',p.college,' - with ',p.info2,' years of experience in ',p.info1), concat('Studying in ', p.college,' - ',p.info2,' ',p.info1 ))as description, if(p.type='professor', 'Mate', 'Pupil') as relationship , p.photo, if(u.ID in {$accepted},'',if(u.ID in {$pending},if(p.type='professor','<button disabled=\"disabled\"><div>Mate request pending</div></button>',' '),if(p.type='professor',concat('<button onClick=\"createRelationship(',u.ID,',\'Mate\');\"><div id=\"create',u.ID,'Mate\">Add Mate</div></button>'),concat('<button onClick=\"createRelationship(',u.ID,',\'Pupil\');\"><div id=\"create',u.ID,'Pupil\">Add Pupil</div></button>')))) as button from users u, profile p where u.ID=p.ID and u.active=1 and u.banned=0 and u.deleted=0 and u.admin=0 and u.ID not in ({$this->user}) and ((p.fname like '%{$name[0]}%' and p.lname like '%{$name[1]}%') or (p.lname like '%{$name[0]}%' and p.fname like '%{$name[1]}%')) order by fullname desc";
                else
                $query="select concat(p.fname,' ',p.lname) as fullname, u.ID, p.fname, p.lname, if(p.type='professor', concat('Teaching at ',p.college,' - with ',p.info2,' years of experience in ',p.info1), concat('Studying in ', p.college,' - ',p.info2,' ',p.info1 ))as description, if(p.type='professor', 'Professor', 'Mate') as relationship , p.photo, if(u.ID in {$accepted},'',if(u.ID in {$pending},if(p.type='professor','<button disabled=\"disabled\"><div>Professor request pending</div></button>','<button disabled=\"disabled\"><div>Mate request pending</div></button>'),if(p.type='professor',concat('<button onClick=\"createRelationship(',u.ID,',\'Professor\');\"><div id=\"create',u.ID,'Professor\">Add Professor</div></button>'),concat('<button onClick=\"createRelationship(',u.ID,',\'Mate\');\"><div id=\"create',u.ID,'Mate\">Add Mate</div></button>')))) as button from users u, profile p where u.ID=p.ID and u.active=1 and u.banned=0 and u.deleted=0  and u.admin=0 and u.ID not in ({$this->user}) and ((p.fname like '%{$name[0]}%' and p.lname like '%{$name[1]}%') or (p.lname like '%{$name[0]}%' and p.fname like '%{$name[1]}%')) order by fullname desc";
        }
        elseif($by=='email')
        {
            $name=urldecode($name);
            if($this->type=='professor')
                 $query="select u.email, u.ID, p.fname, p.lname, if(p.type='professor', concat('Teaching at ',p.college,' - with ',p.info2,' years of experience in ',p.info1), concat('Studying in ', p.college,' - ',p.info2,' ',p.info1 ))as description, if(p.type='professor', 'Mate', 'Pupil') as relationship , p.photo, if(u.ID in {$accepted},'',if(u.ID in {$pending},if(p.type='professor','<button disabled=\"disabled\"><div>Mate request pending</div></button>',' '),if(p.type='professor',concat('<button onClick=\"createRelationship(',u.ID,',\'Mate\');\"><div id=\"create',u.ID,'Mate\">Add Mate</div></button>'),concat('<button onClick=\"createRelationship(',u.ID,',\'Pupil\');\"><div id=\"create',u.ID,'Pupil\">Add Pupil</div></button>')))) as button from users u, profile p where u.ID=p.ID and u.active=1 and u.banned=0 and u.deleted=0 and u.admin=0 and u.ID not in ({$this->user}) and u.email like '%{$name}%' order by u.email desc";
            else
                 $query="select u.email, u.ID, p.fname, p.lname, if(p.type='professor', concat('Teaching at ',p.college,' - with ',p.info2,' years of experience in ',p.info1), concat('Studying in ', p.college,' - ',p.info2,' ',p.info1 ))as description, if(p.type='professor', 'Professor', 'Mate') as relationship , p.photo, if(u.ID in {$accepted},'',if(u.ID in {$pending},if(p.type='professor','<button disabled=\"disabled\"><div>Professor request pending</div></button>','<button disabled=\"disabled\"><div>Mate request pending</div></button>'),if(p.type='professor',concat('<button onClick=\"createRelationship(',u.ID,',\'Professor\');\"><div id=\"create',u.ID,'Professor\">Add Professor</div></button>'),concat('<button onClick=\"createRelationship(',u.ID,',\'Mate\');\"><div id=\"create',u.ID,'Mate\">Add Mate</div></button>')))) as button from users u, profile p where u.ID=p.ID and u.active=1 and u.banned=0 and u.admin=0 and u.deleted=0 and u.ID not in ({$this->user}) and u.email like '%{$name}%' order by u.email desc";
        }
        elseif($by=='college')
        {
            $name=urldecode($name);
             if($this->type=='professor')
                 $query="select p.college, u.ID, p.fname, p.lname, if(p.type='professor', concat('Teaching at ',p.college,' - with ',p.info2,' years of experience in ',p.info1), concat('Studying in ', p.college,' - ',p.info2,' ',p.info1 ))as description, if(p.type='professor', 'Mate', 'Pupil') as relationship , p.photo, if(u.ID in {$accepted},'',if(u.ID in {$pending},if(p.type='professor','<button disabled=\"disabled\"><div>Mate request pending</div></button>',' '),if(p.type='professor',concat('<button onClick=\"createRelationship(',u.ID,',\'Mate\');\"><div id=\"create',u.ID,'Mate\">Add Mate</div></button>'),concat('<button onClick=\"createRelationship(',u.ID,',\'Pupil\');\"><div id=\"create',u.ID,'Pupil\">Add Pupil</div></button>')))) as button from users u, profile p where u.ID=p.ID and u.active=1 and u.banned=0 and u.deleted=0 and u.ID not in ({$this->user}) and p.college like '%{$name}%' order by p.college desc";
            else
                $query="select p.college, u.ID, p.fname, p.lname, if(p.type='professor', concat('Teaching at ',p.college,' - with ',p.info2,' years of experience in ',p.info1), concat('Studying in ', p.college,' - ',p.info2,' ',p.info1 ))as description, if(p.type='professor', 'Professor', 'Mate') as relationship , p.photo, if(u.ID in {$accepted},'',if(u.ID in {$pending},if(p.type='professor','<button disabled=\"disabled\"><div>Professor request pending</div></button>','<button disabled=\"disabled\"><div>Mate request pending</div></button>'),if(p.type='professor',concat('<button onClick=\"createRelationship(',u.ID,',\'Professor\');\"><div id=\"create',u.ID,'Professor\">Add Professor</div></button>'),concat('<button onClick=\"createRelationship(',u.ID,',\'Mate\');\"><div id=\"create',u.ID,'Mate\">Add Mate</div></button>')))) as button from users u, profile p where u.ID=p.ID and u.active=1 and u.banned=0 and u.deleted=0 and u.admin=0 and u.ID not in ({$this->user}) and p.college like '%{$name}%' order by p.college desc";
        }
        elseif($by=='field')
        {
            $name=urldecode($name);
             if($this->type=='professor')
                 $query="select p.info1, u.ID, p.fname, p.lname, if(p.type='professor', concat('Teaching at ',p.college,' - with ',p.info2,' years of experience in ',p.info1), concat('Studying in ', p.college,' - ',p.info2,' ',p.info1 ))as description, if(p.type='professor', 'Mate', 'Pupil') as relationship , p.photo, if(u.ID in {$accepted},'',if(u.ID in {$pending},if(p.type='professor','<button disabled=\"disabled\"><div>Mate request pending</div></button>',' '),if(p.type='professor',concat('<button onClick=\"createRelationship(',u.ID,',\'Mate\');\"><div id=\"create',u.ID,'Mate\">Add Mate</div></button>'),concat('<button onClick=\"createRelationship(',u.ID,',\'Pupil\');\"><div id=\"create',u.ID,'Pupil\">Add Pupil</div></button>')))) as button from users u, profile p where u.ID=p.ID and u.active=1 and u.banned=0 and u.deleted=0 and u.admin=0 and u.ID not in ({$this->user}) and p.info1 like '%{$name}%' order by p.info1 desc";
            else
                $query="select p.info1, u.ID, p.fname, p.lname, if(p.type='professor', concat('Teaching at ',p.college,' - with ',p.info2,' years of experience in ',p.info1), concat('Studying in ', p.college,' - ',p.info2,' ',p.info1 ))as description, if(p.type='professor', 'Professor', 'Mate') as relationship , p.photo, if(u.ID in {$accepted},'',if(u.ID in {$pending},if(p.type='professor','<button disabled=\"disabled\"><div>Professor request pending</div></button>','<button disabled=\"disabled\"><div>Mate request pending</div></button>'),if(p.type='professor',concat('<button onClick=\"createRelationship(',u.ID,',\'Professor\');\"><div id=\"create',u.ID,'Professor\">Add Professor</div></button>'),concat('<button onClick=\"createRelationship(',u.ID,',\'Mate\');\"><div id=\"create',u.ID,'Mate\">Add Mate</div></button>')))) as button from users u, profile p where u.ID=p.ID and u.active=1 and u.banned=0 and u.admin=0 and u.deleted=0 and u.ID not in ({$this->user}) and p.info1 like '%{$name}%' order by p.info1 desc";
        }
        elseif($by=='class')
        {
            $name=urldecode($name);
              if($this->type=='professor')
                 $query="select p.info2, u.ID, p.fname, p.lname, if(p.type='professor', concat('Teaching at ',p.college,' - with ',p.info2,' years of experience in ',p.info1), concat('Studying in ', p.college,' - ',p.info2,' ',p.info1 ))as description, if(p.type='professor', 'Mate', 'Pupil') as relationship , p.photo, if(u.ID in {$accepted},'',if(u.ID in {$pending},if(p.type='professor','<button disabled=\"disabled\"><div>Mate request pending</div></button>',' '),if(p.type='professor',concat('<button onClick=\"createRelationship(',u.ID,',\'Mate\');\"><div id=\"create',u.ID,'Mate\">Add Mate</div></button>'),concat('<button onClick=\"createRelationship(',u.ID,',\'Pupil\');\"><div id=\"create',u.ID,'Pupil\">Add Pupil</div></button>')))) as button from users u, profile p where u.ID=p.ID and u.active=1 and u.admin=0 and u.banned=0 and u.deleted=0 and u.ID not in ({$this->user}) and p.info2 like '%{$name}%' order by p.info2 desc";
            else
                $query="select p.info2, u.ID, p.fname, p.lname, if(p.type='professor', concat('Teaching at ',p.college,' - with ',p.info2,' years of experience in ',p.info1), concat('Studying in ', p.college,' - ',p.info2,' ',p.info1 ))as description, if(p.type='professor', 'Professor', 'Mate') as relationship , p.photo, if(u.ID in {$accepted},'',if(u.ID in {$pending},if(p.type='professor','<button disabled=\"disabled\"><div>Professor request pending</div></button>','<button disabled=\"disabled\"><div>Mate request pending</div></button>'),if(p.type='professor',concat('<button onClick=\"createRelationship(',u.ID,',\'Professor\');\"><div id=\"create',u.ID,'Professor\">Add Professor</div></button>'),concat('<button onClick=\"createRelationship(',u.ID,',\'Mate\');\"><div id=\"create',u.ID,'Mate\">Add Mate</div></button>')))) as button from users u, profile p where u.ID=p.ID and u.active=1 and u.admin=0 and u.banned=0 and u.deleted=0 and u.ID not in ({$this->user}) and p.info2 like '%{$name}%' order by p.info2 desc";
      
        }
        
      
         // if($this->type=='professor'),' - with ',p.info2,' years of experience in ',p.info1), concat('Studying in ', p.college,' - ',p.info2,' ',p.info1 ))as description, if(p.type='professor', 'Mate', 'Pupil') as relationship , p.photo from users u, profile p where u.ID=p.ID and u.active=1 and u.banned=0 and u.deleted=0 and u.ID not in ({$this->user}) and ((p.fname like '%{$name[0]}%' and p.lname like '%{$name[1]}%')
        //$query="select match(p.fname, p.lname, p.college, p.info1) against (\"".$name."\") as rel, u.ID, u.email, p.college, p.info1, p.fname, p.lname, if(p.type='professor', concat('Teaching at ',p.college,' - with ',p.info2,' years of experience in ',p.info1), concat('Studying in ', p.college,' - ',p.info2,' ',p.info1 ))as description, if(p.type='professor', 'Mate', 'Pupil') as relationship ,p.photo from users u, profile p where u.ID=p.ID and u.active=1 and u.banned=0 and u.deleted=0 and u.ID not in ({$this->user}) and match(p.fname, p.lname, p.college, p.info1) against (\"".$name."\") order by rel desc";
        //else
        //$query="select match(p.fname, p.lname, p.college, p.info1, p.info2) against (\"".$name."\") as rel, u.ID, u.email, p.college, p.info1, p.info2, p.fname, p.lname, if(p.type='professor', concat('Teaching at ',p.college,' - with ',p.info2,' years of experience in ',p.info1), concat('Studying in ', p.college,' - ',p.info2,' ',p.info1 ))as description, if(p.type='professor', 'Professor', 'Mate') as relationship ,p.photo from users u, profile p where u.ID=p.ID and u.active=1 and u.banned=0 and u.deleted=0 and u.ID not in ({$this->user}) and match(p.fname, p.lname, p.college, p.info1, p.info2) against (\"".$name."\") order by rel desc";
     //  $query="select u.ID, u.username, p.student_college, p.student_dob, p.student_field, p.student_gender from users u, profile p where u.ID=p.ID and u.active=1 and u.banned=0 and u.deleted=0 and u.username like '%".$name."%' order by u.username asc";
        $pagination->setQuery($query);
        $pagination->generatePagination();
        return $pagination;
    }
}
?>
