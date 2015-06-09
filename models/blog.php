<?php

class Blog
{
    private $registry;
    private $ID;
    private $content;
    private $creator;
    private $created;
    private $title;
    private $createdFriendly;
    private $creatorFName;
    private $creatorLName;
    private $creator_photo;
    private $creator_ID;
    private $edit;
    private $delete;
    private $type;
    private $category;
    private $allowcomments;
    
    public function __construct(Registry $registry, $id=0)
    {
        $this->registry=$registry;
        if($id>0)
        {
            $this->ID=$id;
            $user=$this->registry->getObject('authenticate')->getUser()->getUserID();//if(CAST(b.created AS DATE)=current_date, CONCAT('Today ', date_format(b.created,'%h:%i %p')),if(CAST(b.created AS DATE)=current_date-1,CONCAT('Yesterday ', date_format(b.created,'%h:%i %p')),concat(date_format(b.created,'%d-%m-%Y'),' ',date_format(b.created,'%h:%i %p'))))
            $sql="select b.*, unix_timestamp(b.created) as createdFriendly, p.fname as creatorFName, p.lname as creatorLName,p.photo as creator_photo, p.ID as creator_ID,
                 if(b.creator={$user} or {$this->registry->getObject('authenticate')->getUser()->isAdmin()},concat('<a href=\"blogs/edit/',$this->ID,'\">Edit</a>'),'') as edit,
            if(b.creator={$user} or {$this->registry->getObject('authenticate')->getUser()->isAdmin()},concat('<a class=\"deleteblog\" href=\"blogs/delete/',$this->ID,'\">Delete</a>'),'') as `delete`
from blog b, profile p where p.ID= b.creator and b.ID =". $this->ID;
            $this->registry->getObject('db')->executeQuery($sql);
            if($this->registry->getObject('db')->numRows()==1)
            {
                $result=$this->registry->getObject('db')->getRows();
                foreach($result as $field=>$data)
                {
                    $this->$field=$data;
                }
            }
            else
            {
                $this->ID=0;
            }
        }
    }
    
    public function getComments()
    {//
        $siteurl='blog/'.$this->ID;
        $user=$this->registry->getObject('authenticate')->getUser()->getUserID();
        /*if(unix_timestamp(now())<(unix_timestamp(bc.created)+60),concat(unix_timestamp(now())-unix_timestamp(bc.created),''),
                if(unix_timestamp(now())<(unix_timestamp(bc.created)+120),'over a minute ago',
                        if(unix_timestamp(now())<(unix_timestamp(bc.created)+(60*60)),concat(round((unix_timestamp(now())-unix_timestamp(bc.created))/60),' minutes ago'),
                                if(unix_timestamp(now())<(unix_timestamp(bc.created)+60*120),'over an hour ago',
                                        if(unix_timestamp(now())<(unix_timestamp(bc.created)+(60*50*24)),concat(round(((unix_timestamp(now())-unix_timestamp(bc.created))/60/60)),' hours ago'),
                                                concat("at ",date_format( 'h:ia \o\n l \t\h\e jS \o\f M',bc.created)))))))as createdFriendly_comment*/
        $sql="select bc.*, if(unix_timestamp(now())<(unix_timestamp(bc.created)+60),concat(unix_timestamp(now())-unix_timestamp(bc.created),' seconds ago'),
                if(unix_timestamp(now())<(unix_timestamp(bc.created)+120),'over a minute ago',
                        if(unix_timestamp(now())<(unix_timestamp(bc.created)+(60*60)),concat(round((unix_timestamp(now())-unix_timestamp(bc.created))/60),' minutes ago'),
                                if(unix_timestamp(now())<(unix_timestamp(bc.created)+60*120),'over an hour ago',
                                        if(unix_timestamp(now())<(unix_timestamp(bc.created)+(60*50*24)),concat(round(((unix_timestamp(now())-unix_timestamp(bc.created))/60/60)),' hours ago'),
                                                concat('at ',date_format(bc.created,'%l:%i'),lower(date_format(bc.created,'%p')),date_format(bc.created,' %o%n %a, %D %b, %Y')))))))as createdFriendly_comment,
        pr.fname as creatorFName_comment, pr.lname as creatorLName_comment, pr.photo as creator_photo_comment, bc.creator as creator_ID_comment,
            if(bc.creator={$user} or {$this->registry->getObject('authenticate')->getUser()->isAdmin()},concat('<a href=\"','{$siteurl}','/comment/',bc.ID,'/','edit\">Edit</a>'),'') as edit_comment,
            if(bc.creator={$user} or {$this->registry->getObject('authenticate')->getUser()->isAdmin()},concat('<a class=\"deletecomment\" href=\"','{$siteurl}','/comment/',bc.ID,'/','delete\">Delete</a>'),'') as `delete_comment`
            from profile pr, blogcomments bc where bc.creator=pr.ID and bc.blogid=".$this->ID;
        $cache=$this->registry->getObject('db')->cacheQuery($sql);
        return $cache;
    }
      
    public function getID()
    {
        return $this->ID;
    }
    public function getTitle()
    {
        return $this->title;
    }
    
    public function getType()
    {
        return $this->type;
    }
    
    public function getCreator()
    {
        return $this->creator;
    }
    public function getCreated()
    {
        return $this->created;
    }
    public function getCategory()
    {
        return $this->category;
    }
    public function getAllowComments()
    {
        return $this->allowcomments;
    }
    public function getContent()
    {
        return $this->content;
    }
    
    
    public function setCreator($creator)
    {
        $this->creator=$creator;
    }
    
    public function setTitle($title)
    {
        $this->title=$title;
    }
    
    public function setCategory($category)
    {
        $this->category=$category;
    }
    
    public function setType($type)
    {
        $this->type=$type;
    }
    public function setContent($content)
    {
        $this->content=$content;
    }
    public function setAllowComments($ac)
    {
        $this->allowcomments=$ac;
    }
    
    public function save()
    {
        if($this->ID>0)
        {
            $update=array();
            $update['title']=$this->title;
            $update['content']=$this->content;
            $update['category']=$this->category;
            $update['type']=$this->type;
            $update['allowcomments']=$this->allowcomments;
            $this->registry->getObject('db')->updateRecords('blog',$update,'ID ='.$this->ID);
        }
        else
        {
            $insert=array();
            $insert['title']=$this->title;
            $insert['content']=$this->content;
            $insert['category']=$this->category;
            $insert['type']=$this->type;
            $insert['allowcomments']=$this->allowcomments;
            $insert['creator']=$this->creator;
            $this->registry->getObject('db')->insertRecords('blog',$insert);
            $this->ID=$this->registry->getObject('db')->lastInsertID();
        }
    }
  
    public function toTags($prefix='')
    {
        foreach($this as $field=>$data)
        {
            if($field=='createdFriendly')
            {
                $data=$this->getFriendlyTime ($data);
            }
            if(!is_object($data) && !is_array($data))
            {
                $this->registry->getObject('template')->getPage()->addPPTag($prefix.$field,$data);
            }
        }
    }
    
    public function delete()
    {
        $this->registry->getObject('db')->deleteRecords('blog','ID ='.$this->ID,1);
        if($this->registry->getObject('db')->affectedRows()==1)
        {
            $this->registry->getObject('db')->deleteRecords('blogcomments','blogid =' .$this->ID,'' );
            $this->ID=0;
            return true;
        }
        else
        {
            return false;
        }
    }
    
 public function getFriendlyTime($time){
        $current=time();
        if($current<($time+60)){
            return $current-$time." seconds ago";
        }
        elseif($current<($time+120)){
            return "over a minute ago";
        }
        elseif($current<($time+(60*60))){
            return round(($current-$time)/60)." minutes ago";
        }
        elseif($current<($time+(60*120))){
            return "over an hour ago";
        }
        elseif($current<($time+(60*60*24))){
            return round(($current-$time)/(60*60))." hours ago";
        }
        else{
            return "at " . date( 'h:ia \o\n l\, jS M\,Y',$time);
        }
    }
    
}
?>