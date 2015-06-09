<?php

class Blogcomments
{
    private $registry;
    private $ID;
    private $comment;
    private $blogid;
    private $creator;
    private $creatorFName;
    private $creatorLName;
    private $created;
    private $createdFriendly;
    
    public function __construct(Registry $registry,$id=0)
    {
        $this->registry=$registry;
        $this->ID=$id;
        if($id>0)
        {
            $sql="select bc.*, unix_timestamp(bc.created) as createdFriendly, pr.fname as creatorFName, pr.lname as creatorLName
                from blogcomments bc, profile pr where bc.creator=pr.ID and bc.ID= ".$this->ID;
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
    
    public function getBlogid()
    {
        return $this->blogid;
    }
    public function setBlogid($blogid)
    {
        $this->blogid=$blogid;
    }
    
    public function setComment($comment)
    {
        $this->comment=$comment;
    }
    public function getComment()
    {
        return $this->comment;
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
            $update['comment']=$this->comment;
            $this->registry->getObject('db')->updateRecords('blogcomments',$update, 'ID ='.$this->ID);
        }
        else
        {
            $insert=array();
            $insert['blogid']=$this->blogid;
            $insert['comment']=$this->comment;
            $insert['creator']=$this->creator;
            $this->registry->getObject('db')->insertRecords('blogcomments',$insert);
            $this->ID=$this->registry->getObject('db')->lastInsertID();
        }
    }
    
    public function delete()
    {
         $this->registry->getObject('db')->deleteRecords('blogcomments','ID ='.$this->ID,1);
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

