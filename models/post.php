<?php

class Post
{
    private $registry;
    private $ID;
    private $post;
    private $topic;
    private $creator;
    private $creatorFName;
    private $creatorLName;
    private $created;
    private $createdFriendly;
    private $isfirst=0;
    
    public function __construct(Registry $registry,$id=0)
    {
        $this->registry=$registry;
        $this->ID=$id;
        if($id>0)
        {
            $sql="select p.*, if(CAST(p.created AS DATE)=current_date, CONCAT('Today ', date_format(p.created,'%h:%i %p')),if(CAST(p.created AS DATE)=current_date-1,CONCAT('Yesterday ', date_format(p.created,'%h:%i %p')),concat(date_format(p.created,'%d-%m-%Y'),' ',date_format(p.created,'%h:%i %p')))) as createdFriendly, pr.fname as creatorFName, pr.lname as creatorLName
                from post p, profile pr where p.creator=pr.ID and p.ID= ".$this->ID;
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
    public function getID()
    {
        return $this->ID;
    }
    
    public function getTopic()
    {
        return $this->topic;
    }
    public function setTopic($topic)
    {
        $this->topic=$topic;
    }
    
    public function setPost($post)
    {
        $this->post=$post;
    }
    
    public function setCreator($creator)
    {
        $this->creator=$creator;
    }
    
    public function getCreator()
    {
        return $this->creator;
    }
    
    public function save()
    {
        if($this->ID>0)//update
        {
            $update=array();
            $update['creator']=$this->creator;
            $update['topic']=$this->topic;
            $update['post']=$this->post;
            $this->registry->getObject('db')->updateRecords('post',$update, 'ID ='.$this->ID);
        }
        else
        {
            $insert=array();
            $insert['topic']=$this->topic;
            $insert['post']=$this->post;
            $insert['creator']=$this->creator;
            $insert['isfirst']=$this->isfirst;
            $this->registry->getObject('db')->insertRecords('post',$insert);
            $this->ID=$this->registry->getObject('db')->lastInsertID();
        }
    }
    
    public function setIsFirst($val)
    {
        $this->isfirst=$val;
    }
    public function isFirst()
    {
        return $this->isfirst;
    }
    public function delete()
    {
         $this->registry->getObject('db')->deleteRecords('post','ID ='.$this->ID,1);
        if($this->registry->getObject('db')->affectedRows()==1)
        {
            return true;
        }
        else
        {
            return false;
        }
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
}
?>
