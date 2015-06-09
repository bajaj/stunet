<?php

class  Blogs
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
        
    public function searchBlogs($name, $by='name',$offset=0)
    {
        require_once(FRAMEWORK_PATH.'lib/pagination/pagination.class.php');
        $name=$this->registry->getObject('db')->sanitizeData($name);
        $pagination=new Pagination($this->registry);
        $pagination->setMethod('cache');
        $pagination->setOffset($offset);
        $pagination->setLimit(12); 
        $query="select b.*, p.fname, p.lname, p.photo, if(unix_timestamp(now())<(unix_timestamp(b.created)+60),concat(unix_timestamp(now())-unix_timestamp(b.created),' seconds ago'),
                if(unix_timestamp(now())<(unix_timestamp(b.created)+120),'over a minute ago',
                        if(unix_timestamp(now())<(unix_timestamp(b.created)+(60*60)),concat(round((unix_timestamp(now())-unix_timestamp(b.created))/60),' minutes ago'),
                                if(unix_timestamp(now())<(unix_timestamp(b.created)+60*120),'over an hour ago',
                                        if(unix_timestamp(now())<(unix_timestamp(b.created)+(60*50*24)),concat(round(((unix_timestamp(now())-unix_timestamp(b.created))/60/60)),' hours ago'),
                                                concat('at ',date_format(b.created,'%l:%i'),lower(date_format(b.created,'%p')),date_format(b.created,' %o%n %a, %D %b, %Y')))))))as createdFriendly from blog b, profile p where
                                                b.creator=p.ID and b.type='Public'";
        if($by=='title')
        {                
            $name=urldecode($name);
            $query.=" and b.title like '%{$name}%' order by b.title asc";
        }
        elseif($by=='category')
        {
            $name=urldecode($name);
            $query.=" and b.category like '%{$name}%' order by b.category asc";
        }
        elseif($by=='content')
        {
            $name=urldecode($name);
            $query.=" and b.content like '%{$name}%' order by b.content asc";
        }
        elseif($by=='creator')
        {
            $name=explode("+",$name);
            if(!isset($name[1]))$name[1]='';
            $query.=" and ((p.fname like '%{$name[0]}%' and p.lname like '%{$name[1]}%') or (p.lname like '%{$name[0]}%' and p.fname like '%{$name[1]}%')) order by concat(p.fname,' ',p.lname) desc";
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
    
    public function searchPrivateBlogs($name, $by='name',$offset=0)
    {
        require_once(FRAMEWORK_PATH.'lib/pagination/pagination.class.php');
        $name=$this->registry->getObject('db')->sanitizeData($name);
        $pagination=new Pagination($this->registry);
        $pagination->setMethod('cache');
        $pagination->setOffset($offset);
        $pagination->setLimit(12); 
        $sql="select u.ID from users u, relationships r where r.accepted=1 and (r.usera={$this->user} or r.userb={$this->user})
        and if(r.usera={$this->user},u.ID=r.userb,u.ID=r.usera)";
        require_once(FRAMEWORK_PATH.'models/relationships.php');
        $relationships=new Relationships($this->registry);
        $network=$relationships->getNetwork($this->user);
        $networkids='(0,';
        foreach($network as $id) 
        {
            $networkids.=','.$id;
        }
        $networkids.=')';
        $query="select b.*, p.fname, p.lname, p.photo, if(unix_timestamp(now())<(unix_timestamp(b.created)+60),concat(unix_timestamp(now())-unix_timestamp(b.created),' seconds ago'),
                if(unix_timestamp(now())<(unix_timestamp(b.created)+120),'over a minute ago',
                        if(unix_timestamp(now())<(unix_timestamp(b.created)+(60*60)),concat(round((unix_timestamp(now())-unix_timestamp(b.created))/60),' minutes ago'),
                                if(unix_timestamp(now())<(unix_timestamp(b.created)+60*120),'over an hour ago',
                                        if(unix_timestamp(now())<(unix_timestamp(b.created)+(60*50*24)),concat(round(((unix_timestamp(now())-unix_timestamp(b.created))/60/60)),' hours ago'),
                                                concat('at ',date_format(b.created,'%l:%i'),lower(date_format(b.created,'%p')),date_format(b.created,' %o%n %a, %D %b, %Y')))))))as createdFriendly from blog b, profile p where
                                                b.creator=p.ID and b.creator in ({$sql})";
        if($by=='title')
        {                
            $name=urldecode($name);
            $query.=" and b.title like '%{$name}%' order by b.title asc";
        }
        elseif($by=='category')
        {
            $name=urldecode($name);
            $query.=" and b.category like '%{$name}%' order by b.category asc";
        }
        elseif($by=='content')
        {
            $name=urldecode($name);
            $query.=" and b.content like '%{$name}%' order by b.content asc";
        }
        elseif($by=='creator')
        {
            $name=explode("+",$name);
            if(!isset($name[1]))$name[1]='';
            $query.=" and ((p.fname like '%{$name[0]}%' and p.lname like '%{$name[1]}%') or (p.lname like '%{$name[0]}%' and p.fname like '%{$name[1]}%')) order by concat(p.fname,' ',p.lname) desc";
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
