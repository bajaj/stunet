<?php

class Group
{
  private $types=array('Public','Private');
  //Public group, open to anyone on the site
  //Private group, open to those who have been sent a request by the group creator
  //Private member invite, open to those who have been sent a request by members of the group
  //Private self invite, users need to send a request to become members of the group
  private $registry;
  private $ID;
  private $name;
  private $creator;
  private $created;
  private $type;
  private $active=1;
  private $description;
  private $invitees;
  private $valid;
  private $creatorFName;
  private $creatorLName;
  private $createdFriendly;
  private $college;
  
  public function __construct(Registry $registry,$id=0)
  {
      $this->registry=$registry;
      if($id>0)
      {
          $this->ID=$id;
          $sql="select g.*, date_format(g.created,'%D %M %Y') as createdFriendly, p.fname as creatorFName, p.lname as creatorLName
              from profile p, groups g where g.creator=p.ID and g.ID=".$this->ID;
          $this->registry->getObject('db')->executeQuery($sql);
          if($this->registry->getObject('db')->numRows()==1)
          {
              $result=$this->registry->getObject('db')->getRows();
              foreach($result as $field=>$data)
              {
                  $this->$field=$data;
              }
              $this->valid=true;
          }
          else
          {
              $this->valid=false;
          }
      }
      else
      {
          $this->ID=0;
      }
  }
  
  public function setName($name)
  {
      $this->name=$name;
  }
  
  public function setCollege($college)
  {
      $this->college=$college;
  }
  
  public function getCollege()
  {
      return $this->college;
  }
   public function getName()
  {
      return $this->name;
  }  
  
  public function setCreator($creator)
  {
      $this->creator=$creator;
  }
  
  public function setType($type)
  {
      if(in_array($type,$this->types))
      {
          $this->type=$type;
      }
  }
  
  public function getType()
  {
      return $this->type;
  }
  
  public function setInvitees($xyz)
  {
      $this->invitees=$xyz;
  }
  
  public function setDescription($description)
  {
      $this->description=$description;
  }
  
   public function getDescription()
  {
      return $this->description;
  }
  
  public function save()
  {
      if($this->ID>0)
      {
          $update=array();
          $update['active']=$this->active;
          $update['name']=$this->name;
          $update['description']=$this->description;
          $update['type']=$this->type;
          $update['college']=$this->college;
          $this->registry->getObject('db')->updateRecords('groups',$update,'ID ='.$this->ID);
           if(is_array($this->invitees) and count($this->invitees)>0)
              {
                  foreach($this->invitees as $invitee)
                  {
                      $insert=array();
                      $insert['group']=$this->ID;
                      $insert['invited']=1;
                      $insert['user']=$invitee;
                      $insert['invited_date']=date('Y-m-d H:i:s');
                      $insert['inviter']=$this->registry->getObject('authenticate')->getUser()->getUserID();
                      $this->registry->getObject('db')->insertRecords('group_membership',$insert);
                      $this->mail_invitee($invitee);		  
                  }
              }
      }
      else
      {
          $insert=array();
          $insert['active']=$this->active;
          $insert['name']=$this->name;
          $insert['description']=$this->description;
          $insert['type']=$this->type;
          $insert['creator']=$this->creator;
          $insert['college']=$this->college;
          $this->registry->getObject('db')->insertRecords('groups',$insert); 
          $this->ID=$this->registry->getObject('db')->lastInsertID();
          if(is_array($this->invitees) and count($this->invitees)>0)
              {
                  foreach($this->invitees as $invitee)
                  {
                      $insert=array();
                      $insert['group']=$this->ID;
                      $insert['invited']=1;
                      $insert['user']=$invitee;
                      $insert['invited_date']=date('Y-m-d H:i:s');
                      $insert['inviter']=$this->registry->getObject('authenticate')->getUser()->getUserID();
                      $this->registry->getObject('db')->insertRecords('group_membership',$insert);
                      $this->mail_invitee($invitee);		  
                  }
              }
      }
  }
  
  public function getTopics()
  {
      $sql="select t.*, (select count(*) from post po where po.topic= t.ID) as posts, if(CAST(t.created AS DATE)=current_date, CONCAT('Today ', date_format(t.created,'%h:%i %p')),if(CAST(t.created AS DATE)=current_date-1,CONCAT('Yesterday ', date_format(t.created,'%h:%i %p')),concat(date_format(t.created,'%d-%m-%Y'),' ',date_format(t.created,'%h:%i %p')))) as createdFriendly,
          pr.fname as creatorFName, pr.lname as creatorLName from topic t, profile pr where t.creator=pr.ID and t.groupid=". $this->ID. " order by t.ID desc";
      $cache=$this->registry->getObject('db')->cacheQuery($sql);
      return $cache;
  }
  
    public function creator_name($id)       // used in inserting the name of creator in message  body
  {
        $sql="SELECT p.fname,p.lname from profile p WHERE p.ID=".$this->creator;
	$this->registry->getObject('db')->executeQuery($sql);
	$data=$this->registry->getObject('db')->getRows();  
	return $data['fname'].' '.$data['lname']; 
  }
  
  public function getID()
  {
      return $this->ID;
  }
  
  public function toTags($prefix='')
  {
      foreach($this as $field=>$data)
      {
          if(!is_array($data) && !is_object($data))
          {
              $this->registry->getObject('template')->getPage()->addTag($prefix.$field,$data);
          }
      }
  }
  
  public function isActive()
  {
      return $this->active;
  }
  
  public function isValid()
  {
      return $this->valid;
  }
  
  public function getCreator()
  {
      return $this->creator;
  }
  
    public function mail_invitee($id)
  {
         require_once( FRAMEWORK_PATH . 'models/message.php' );		
         $message = new Message( $this->registry, 0 );
	 $subject="Group Invitation";
	 $data=$this->creator_name($this->creator);		
	 $body="<p>".$data." invited you to the group ".$this->name." </p>";
         $body.="College: ".$this->college.'<br/>';
         $body.="About: ".$this->description.'<br/>';
         $body.="<a href=\"group/".$this->ID."/approve\"><button>Approve</button></a> or <a href=\"group/".$this->ID."/reject\"><button>Reject</button></a>";
	 $message->setSender( $this->creator);
         $message->setRecipient( $id);
	 $message->setSubject($subject) ;
	 $message->setMessage($body);
	 $message->set_event_mail(1);
	 $message->save();
  }
  
  
      
}
?>
