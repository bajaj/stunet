<?php

class  Groups
{
    private $registry;
    private $user;
    public function __construct(Registry $registry)
    {
        $this->registry=$registry;
        $this->user=$this->registry->getObject('authenticate')->getUser()->getUserID();
    }
   
//SUBSTRING_INDEX(str,delim,count)

//Returns the substring from string str before count occurrences of the delimiter delim. If count is positive, everything to the left of the final delimiter (counting from the left) is returned. If count is negative, everything to the right of the final delimiter (counting from the right) is returned.
        
    public function searchGroups($name, $by='name',$offset=0)
    {
        require_once(FRAMEWORK_PATH.'lib/pagination/pagination.class.php');
        $name=$this->registry->getObject('db')->sanitizeData($name);
        $pagination=new Pagination($this->registry);
        $pagination->setMethod('cache');
        $pagination->setOffset($offset);
        $pagination->setLimit(12); 
        $approved="select a.ID from groups a where a.ID in (select gma.`group` from group_membership gma where gma.user={$this->user} and gma.approved = 1)";
        $requested="select r.ID from groups r where r.ID in (select gmr.`group` from group_membership gmr where gmr.user={$this->user} and gmr.approved = 0 and gmr.requested = 1)";
        $invited="select i.ID from groups i where i.ID in (select gmi.`group` from group_membership gmi where gmi.user={$this->user} and gmi.approved = 0 and gmi.invited = 1)";
        $creator="select c.ID from groups c where c.creator = {$this->user}";
        /*
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
  //      echo $pending;*/
        /* $query="select g.ID, g.name, g.description, g.college, p.photo as creator_photo, g.creator as creator_ID, p.fname as creator_fname, p.lname as creator_lname, if(g.ID in {$requested},'<button disabled=\'disabled\'>Pending Group Creator\'s Approval</button>',if(g.ID in {$invited},concat('<button disabled=\'disabled\'>You were invited</button><a href=\'group/',g.ID,'approve\'><button>Join</button></a>'),concat('<a href=\'group/',g.ID,'request\'><button>Request to join</button></a>')))) as button
            from groups g, profile p where p.ID = g.creator 
and g.type='Public' and g.ID not in ($creator) and g.ID not in {$approved}";
         *  
         */
        
            $query="select g.ID, g.name, g.description, g.college, p.photo as creator_photo, g.creator as creator_ID, if(p.ID={$this->user},'You',p.fname) as creator_fname, if(p.ID={$this->user},'',p.lname) as creator_lname, if(g.ID in ({$approved}),concat('<a href=\"group/',g.ID,'\"><button>Visit Group</button></a>'),if(g.ID in ({$creator}) or {$this->registry->getObject('authenticate')->getUser()->isAdmin()},concat('<a href=\"group/',g.ID,'\"><button>Visit Group</button></a>'),if(g.ID in ({$requested}),'<button disabled=\'disabled\'>Pending Group Creator\'s Approval</button>',if(g.ID in ({$invited}),concat('<button disabled=\'disabled\'>You were invited</button><a href=\'group/',g.ID,'/approve\'><button>Join</button></a>'),concat('<span id=\'requestbutton',g.ID,{$this->user},'\'><button onclick=\'request(',g.ID,',',{$this->user},')\'>Request to join</button></span>'))))) as button
            from groups g, profile p where p.ID = g.creator 
and g.type='Public'";
        if($by=='college')
        {                
            $query.=" and g.college like '%{$name}%'";
        }
        elseif($by=='name')
        {
            $query.=" and g.name like '%{$name}%'";
        }
        elseif($by=='description')
        {
            $query.=" and g.description like '%{$name}%'";
        }
        elseif($by=='creator')
        {
            $query.=" and concat(p.fname,' ',p.lname) like '%{$name}%'";
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
