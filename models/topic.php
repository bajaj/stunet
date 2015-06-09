<?php

class Topic
{
    private $registry;
    private $ID;
    private $post;
    private $creator;
    private $created;
    private $groupid;
    private $createdFriendly;
    private $creatorFName;
    private $creatorLName;
    private $numPosts;
    private $name;
    
    private $includeFirstPost=0;
    
    public function __construct(Registry $registry, $id=0)
    {
        $this->registry=$registry;
        if($id>0)
        {
            $this->ID=$id;
            $sql="select t.*, (select count(*) from post po where po.topic=t.ID) as numPosts, if(CAST(t.created AS DATE)=current_date, CONCAT('Today ', date_format(t.created,'%h:%i %p')),if(CAST(t.created AS DATE)=current_date-1,CONCAT('Yesterday ', date_format(t.created,'%h:%i %p')),concat(date_format(t.created,'%d-%m-%Y'),' ',date_format(t.created,'%h:%i %p')))) as createdFriendly, p.fname as creatorFName, p.lname as creatorLName
                from topic t, profile p where p.ID= t.creator and t.ID =". $this->ID;
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
    
    public function getPostsQuery()
    {
        $siteurl= $_SERVER["REQUEST_URI"];
        $user=$this->registry->getObject('authenticate')->getUser()->getUserID();
        if($this->registry->getObject('authenticate')->getUser()->isadmin())
		{
		$sql="select p.*,if(CAST(p.created AS DATE)=current_date, CONCAT('Today, ', date_format(p.created,'%h:%i %p')),if(CAST(p.created AS DATE)=current_date-1,CONCAT('Yesterday, ', date_format(p.created,'%h:%i %p')),concat(date_format(p.created,'%d-%m-%Y'),', ',date_format(p.created,'%h:%i %p')))) as createdFriendly_post, pr.fname as creatorFName_post, pr.lname as creatorLName_post, pr.photo as creator_photo, p.creator as creator_ID,
            if(true,concat('<a href=\"','{$siteurl}','/',p.ID,'/','edit\"><button class=\"editpost\">Edit</button></a>'),'') as edit,
            if(true,concat('<a href=\"','{$siteurl}','/',p.ID,'/','delete\"><button class=\"deletepost\" onclick=\"return confirm(\'Are you sure you want to delete this comment?\');\">Delete</button></a>'),'') as `delete`
            from profile pr, post p where p.creator=pr.ID and p.topic=".$this->ID." order by p.created asc";
		
		}
		else
		{	
		
        $sql="select p.*,if(CAST(p.created AS DATE)=current_date, CONCAT('Today, ', date_format(p.created,'%h:%i %p')),if(CAST(p.created AS DATE)=current_date-1,CONCAT('Yesterday, ', date_format(p.created,'%h:%i %p')),concat(date_format(p.created,'%d-%m-%Y'),', ',date_format(p.created,'%h:%i %p')))) as createdFriendly_post, pr.fname as creatorFName_post, pr.lname as creatorLName_post, pr.photo as creator_photo, p.creator as creator_ID,
            if(p.creator={$user},concat('<a href=\"','{$siteurl}','/',p.ID,'/','edit\"><button class=\"editpost\">Edit</button></a>'),'') as edit,
            if(p.creator={$user},concat('<a href=\"','{$siteurl}','/',p.ID,'/','delete\"><button class=\"deletepost\" onclick=\"return confirm(\'Are you sure you want to delete this comment?\');\">Delete</button></a>'),'') as `delete`
            from profile pr, post p where p.creator=pr.ID and p.topic=".$this->ID." order by p.created asc";
		}	
        $cache=$this->registry->getObject('db')->cacheQuery($sql);
        return $cache;
    }
    
    public function includeFirstPost($ifp)
    {
        $this->includeFirstPost=$ifp;
        require_once(FRAMEWORK_PATH.'models/post.php');
        $this->post=new Post($this->registry,0);
    }
    
    public function getID()
    {
        return $this->ID;
    }
    public function getFirstPost()
    {
        return $this->post;
    }
    
    public function setGroup($groupid)
    {
        $this->groupid=$groupid;
    }
    
    public function setCreator($creator)
    {
        $this->creator=$creator;
    }
    
    public function setName($name)
    {
        $this->name=$name;
    }
    
    public function save()
    {
        if($this->ID>0)
        {
            $update=array();
            $update['creator']=$$this->creator;
            $update['name']=$this->name;
            $update['groupid']=$this->groupid;
            $this->registry->getObject('db')->updateRecords('topic',$update,'ID ='.$this->ID);
        }
        else
        {
            $insert=array();
            $insert['creator']=$this->creator;
            $insert['name']=$this->name;
            $insert['groupid']=$this->groupid;
            $this->registry->getObject('db')->insertRecords('topic',$insert);
            $this->ID=$this->registry->getObject('db')->lastInsertID();
            if($this->includeFirstPost==true)
            {
                $this->post->setTopic($this->ID);
                $this->post->save();
            }
        }
    }
    
    public function getName()
    {
        return $this->name;
    }
    
    public function getGroup()
    {
        return $this->groupid;
    }
    
    public function toTags($prefix='')
    {
        foreach($this as $field=>$data)
        {
            if(!is_object($data) && !is_array($data))
            {
                $this->registry->getObject('template')->getPage()->addTag($prefix.$field,$data);
            }
        }
    }
    
    public function delete()
    {
        $this->registry->getObject('db')->deleteRecords('topic','ID ='.$this->ID,1);
        if($this->registry->getObject('db')->affectedRows()==1)
        {
            $this->registry->getObject('db')->deleteRecords('posts','topic =' .$this->ID,'' );
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
            return "just over a minute ago";
        }
        elseif($current<($time+(60*60))){
            return round(($current-$time)/60)." minutes ago";
        }
        elseif($current<($time+(60*120))){
            return "just over an hour ago";
        }
        elseif($current<($time+(60*60*24))){
            return round(($current-$time)/(60*60))." hours ago";
        }
        else{
            return "at " . date( 'h:ia \o\n l \t\h\e jS \o\f M',$time);
        }
    }
    
}
?>