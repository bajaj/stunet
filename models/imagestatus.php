<?php

class Imagestatus extends status
{
  private $image;
  private $registry;
  public function __construct(Registry $registry,$id=0)
  {
      $this->registry=$registry;
        parent::setTypeReference('image');
      parent::__construct($this->registry,$id);
    
  }
  
  public function processImage($postfield)
  {
      require_once(FRAMEWORK_PATH.'lib/images/imagemanager.class.php');
      $im=new Imagemanager();
      if($im->loadFromPost($postfield,$this->registry->getSetting('uploads_path').'statusimages/',time()))
      {
          $im->resize(200,200);
          $im->save($this->registry->getSetting('uploads_path').'statusimages/'.$im->getName());
          $this->image=$im->getName();
          return true;
      }
      else
      {
          return false;
      }
  }
  
  public function save()
  {
      parent::save();
      $id=$this->getID();
      $insert=array();
      $insert['ID']=$id;
      $insert['image']=$this->image;
      $this->registry->getObject('db')->insertRecords('statuses_images',$insert);
  }
}
?>