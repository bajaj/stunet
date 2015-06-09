<?php

class Groupmembership
{
    private $ID;
    private $registry;
    private $group;
    private $user;
    private $approved;
    private $requested;
    private $invited;
    private $invitedDate;
    private $requestedDate;
    private $joinDate;
    private $inviter;
    
    
    public function __construct(Registry $registry,$id=0)
    {
        $this->registry=$registry;
        if($id>0)
        {
            $this->ID=$id;
            $sql="select * from group_membership where ID = ".$this->ID;
            $this->registry->getObject('db')->executeQuery($sql);
            if($this->registry->getObject('db')->numRows()==1)
            {
                $result=$this->registry->getObject('db')->getRows();
                foreach($result as $field=>$data)
                {
                    $this->$field=$data;
                }
            }
        }
        else
        {
            $this->ID=0;
        }
    }
    
    public function getByUserAndGroup($user,$groupID)
    {
        $this->user=$user;
        $this->ID=$groupID;
        $sql="select * from group_membership where user={$user} and `group`={$groupID}";
        $this->registry->getObject('db')->executeQuery($sql);
        if($this->registry->getObject('db')->numRows()==1)
        {
            $result=$this->registry->getObject('db')->getRows();
            foreach($result as $field=>$data)
            {
                $this->$field=$data;
            }
        }
    }
    
    public function getInviter()
    {
        return $this->inviter;
    }
    
    public function getInvited()
    {
        return $this->invited;
    }
    
    public function getRequested()
    {
        return $this->requested;
    }
    
    public function getApproved()
    {
        return $this->approved;
    }
    
    public function setApproved($approved)
    {
        $this->approved=$approved;
    }
    
    public function setInvited($invited)
    {
        $this->invited=$invited;
    }
    
    public function setRequested($requested)
    {
        $this->requested=$requested;
    }
    
    public function setInviter($inviter)
    {
        $this->inviter=$inviter;
    }
    
    public function save()
    {
        if($this->ID>0)
        {
            $update=array();
            foreach($this as $field=>$data)
            {
                if(!is_array($data) && !is_object($data) && $field!='ID')
                {
                    $update["'".$field."'"]=$data;
                }
            }
            $this->registry->getObject('db')->updateRecords('group_membership',$update,'ID = '.$this->ID);
        }
        else
        {
            $insert=array();
            foreach($this as $field=>$data)
            {
                if(!is_array($data) && !is_object($data) && $field!='ID')
                {
                    $insert["'".$field."'"]=$data;
                }
            }
            $this->registry->getObject('db')->insertRecords('group_membership',$insert);
            $this->ID=$this->registry->getObject('db')->lastInsertID();
        }
    }
    
    public function getID()
    {
        return $this->ID;
    }
}
?>