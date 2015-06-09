<?php
class Linkstatus extends status
{
    private $url;
    private $description;
    private $registry;
    
    public function __construct(Registry $registry,$id=0)
    {
        $this->registry=$registry;
        parent::__construct($this->registry,$id);
        parent::setTypeReference('link');
    }
    
    public function setURL($url)
    {
        $this->url=$url;
    }
    
    public function setDescription($description)
    {
        $this->description=$description;
    }
    
    public function save()
    {
        parent::save();
        $id=$this->getID();
        $insert=array();
        $insert['ID']=$id;
        $insert['url']=$this->url;
        $insert['description']=$this->description;
        $this->registry->getObject('db')->insertRecords('statuses_links',$insert);
    }
        
}
?>