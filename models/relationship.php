<?php

class Relationship{
    private $registry;
    private $usera;
    private $userb;
    private $accepted;
    private $id=0;
    private $alreadyExists=false;
    public function __construct(Registry $registry, $id=0,$usera,$userb,$accepted=0)
    {
        $this->registry=$registry;
        if($id==0)
        {
            $this->createRelationship($usera,$userb,$accepted);
        }
        else{
            $sql="select * from relationships where id= ".$id;
            $this->registry->getObject('db')->executeQuery($sql);
            if($this->registry->getObject('db')->numRows()==1){
                $data=$this->registry->getObject('db')->getRows();
                $this->populate($data['ID'],$data['usera'],$data['userb'],$data['accepted']);
            } } }
     
    public function createRelationship($usera,$userb,$accepted=0)
    {
        $sql="select * from relationships where usera='{$usera}' and userb='{$userb}' or usera='{$userb}' and userb='{$usera}'";
        $this->registry->getObject('db')->executeQuery($sql);
        if($this->registry->getObject('db')->numRows()==1)
        {//a relationship already exists
            $data=$this->registry->getObject('db')->getRows();
            $this->populate($data['ID'],$data['usera'],$data['userb'],$data['type'],$data['accepted']);
           $this->alreadyExists=true; 
        }
        else{
           
            $this->accepted=$accepted;
            $insert=array();
            $insert['usera']=$usera;
            $insert['userb']=$userb;
            $insert['accepted']=$accepted;
            $this->registry->getObject('db')->insertRecords('relationships',$insert);
            $this->id=$this->registry->getObject('db')->lastInsertID();
        }
        
        
    }
    
    public function approveRelationship()
    {
        $this->accepted=true;
    }
    
    public function delete()
    {
        $this->registry->getObject('db')->deleteRecords('relationships','ID ='.$this->id,1);
        $this->id=0;
    }
    
    private function populate($id,$usera,$userb,$accepted)
    {
        $this->id=$id;
        $this->usera=$usera;
        $this->userb=$userb;
        $this->accepted=$accepted;
    }
    
    public function isApproved(){
        return $this->accepted;
    }
    
    public function getUserB()
    {
        return $this->userb;
    }
    
     public function getUserA()
    {
        return $this->usera;
    }
    
    public function save()
    {
        $changes=array();
        $changes['usera']=$this->usera;
        $changes['userb']=$this->userb;
        $changes['accepted']=$this->accepted;
        $this->registry->getObject('db')->updateRecords('relationships',$changes,'ID ='.$this->id);
    }
   
    public function alreadyExists()
    {
        return $this->alreadyExists;
    }
    
}