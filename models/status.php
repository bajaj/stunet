<?php

class Status{
  private $registry;
  
  private $id;
  
  private $type;
  
  private $profile;
  
  private $poster;
  
  private $content;
  
  private $posted;
  
  private $typeReference='update';
  
  public function getID()
  {
      return $this->id;
  }
  
  public function __construct(Registry $registry, $id=0){
      $this->registry=$registry;
      $this->id=$id;
  }
  
  public function setProfile($profile){
      $this->profile=$profile;
  }
  
  public function setPoster($poster){
      $this->poster=$poster;
  }
  
  public function setType($type){
      $this->type=$type;
  }
  
  public function setContent($content){
      $this->content=$content;
  }
  
  public function setTypeReference($typeReference){
      $this->typeReference=$typeReference;
  }
  
  public function generateType(){
      $sql="select * from status_types where type_reference='{$this->typeReference}'";
      $this->registry->getObject('db')->executeQuery($sql);
      $data=$this->registry->getObject('db')->getRows();
      $this->type=$data['ID'];
  }
  
  public function save(){
      if($this->id==0){
          $insert=array();
          $insert['content']=$this->content;
          $insert['type']=$this->type;
          $insert['profile']=$this->profile;
          $insert['poster']=$this->poster;
          $this->registry->getObject('db')->insertRecords('statuses',$insert);
          $this->id=$this->registry->getObject('db')->lastInsertID();
      }
  }
}
?>