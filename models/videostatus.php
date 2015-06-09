<?php

class Videostatus extends status
{
  private $videoid;
  
  public function __construct(Registry $registry, $id=0)
  {
      $this->registry=$registry;
      parent::__construct($this->registry,$id);
      parent::setTypeReference('video');
  }
  
  public function setVideoId($videoid)
  {
      $this->videoid=$videoid;
  }
  
  public function setVideoIdFromURL($url)
  {
      $data=array();
      parse_str(parse_url($url,PHP_URL_QUERY),$data);
      $this->videoid=$this->registry->getObject('db')->sanitizeData(isset($data['v'])?$data['v']:'7NzzzcOWPH0');
  }
  
  public function save()
  {
      parent::save();
      $id=$this->getID();
      $insert=array();
      $insert['ID']=$id;
      $insert['video_id']=$this->videoid;
      $this->registry->getObject('db')->insertRecords('statuses_videos',$insert);
  }
}
?>