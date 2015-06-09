<?php
class Event
{
  private $ID;
  private $name;
  private $type;
  private $creator;
  private $start_time;
  private $end_time;
  private $date;
  private $friendly_date;
  private $active;
  private $description;
  private $invitees=array();
  private $grp_invitees=array();
  
  public function __construct(Registry $registry,$id=0)
  {
      $this->registry=$registry;
      if($id>0)
      {
          $this->ID=$id;
          $sql="select *,date_format(date,'%D %M %Y') as friendly_date from events where ID =".$this->ID;
          $this->registry->getObject('db')->executeQuery($sql);
          if($this->registry->getObject('db')->numRows()>0)
          {
              $data=$this->registry->getObject('db')->getRows();
              foreach($data as $key=>$value)
              {
                  $this->$key=$value;
              }
          }
      }
  }
  
  public function save()
  {
      if($this->registry->getObject('authenticate')->isLoggedIn() &&($this->registry->getObject('authenticate')->getUser()->isAdmin()||
              $this->creator==$this->registry->getObject('authenticate')->getUser()->getUserID()||
              $this->ID==0))
      {
          $event=array();
          foreach($this as $field=>$data)
          {
            if(!is_array($data) && !is_object($data) && $field!='ID' && $field!='friendly_date')
            {
                $event[$field]=$this->$field;
            }
          }
          if($this->ID==0)//insert values
          {
              $this->registry->getObject('db')->insertRecords('events',$event);
              $this->ID=$this->registry->getObject('db')->lastInsertID();
			  $this->insert_event_attendees(0);
			  
               
               $insert=array();
               $insert['event_id']=$this->ID;
               $insert['status']='attending';
               $insert['user_id']=$this->creator;
               $this->registry->getObject('db')->insertRecords('event_attendees',$insert);
          }
          else
          {
              $this->registry->getObject('db')->updateRecords('events',$insert,'ID ='.$this->ID);
              if($this->registry->getObject('db')->affectedRows()==1)
              {
                  return true;
              }
              else
              {
                  return false;
              }
          }
      }
      else
      {
          return false;
      }
  }
  
  
  //
  
  
  public function insert_event_attendees($is_update=0)   // to insert into event_attendees table
  {
  
    if(is_array($this->invitees) and count($this->invitees)>0)
              {
                  foreach($this->invitees as $invitee)
                  {
                      $insert=array();
                      $insert['event_id']=$this->ID;
                      $insert['status']='invited';
                      $insert['user_id']=$invitee;
                      $this->registry->getObject('db')->insertRecords('event_attendees',$insert);
					  $this->mail_invitee($invitee,0);
					  
                  }
              }
			 
				if($is_update>0)			 
			  {
			  $sql="SELECT distinct(et.user_id) FROM event_attendees et WHERE et.event_id=".$this->ID;
			  $this->registry->getObject('db')->executeQuery( $sql );
			  
			    $arr=array();
				while($data=$this->registry->getObject('db')->getRows())
				{
				$arr[]=$data['user_id'];
				}
				foreach($arr as $field=>$value)
				$this->mail_invitee($value,1);
				
			  
			  }
			  
			  // for inserting groups
			  
			  if(is_array($this->grp_invitees) and count($this->grp_invitees)>0)
              {
                  foreach($this->grp_invitees as $grp_invitee)
                  {
                      $insert=array();
                      $insert['event_id']=$this->ID;
                      $insert['status']='invited';
                      $insert['user_id']=$grp_invitee;
					  $insert['isgroup']=1;                 // it is a group not a member
					  
                      $this->registry->getObject('db')->insertRecords('event_attendees',$insert);
					  
					  // after inserting group invitation email group member about the event they are envited  
					  					  
					  $this->mail_grp_invite($grp_invitee,$is_update);
                  
              
			  
			  // for inserting for all group member no need to send email as function mail_grp_invitee does the work
						$sql="SELECT gm.user as grp_mate FROM group_membership gm WHERE gm.group=".$grp_invitee." AND gm.user!=".$this->creator;
					  
						$this->registry->getObject('db')->executeQuery($sql);
					  
						while($data=$this->registry->getObject('db')->getRows()) 
						{
						$arr[]=$data['grp_mate'];
						}
						foreach($arr as $key=>$invitee)
						{
						$insert=array();
						$insert['event_id']=$this->ID;
						$insert['status']='invited';
						$insert['user_id']=$invitee;
						$this->registry->getObject('db')->insertRecords('event_attendees',$insert);
						}
		 
					}	  
				}
  
  }
  
  
  
  public function mail_grp_invite($grp_id,$is_update)     // send email to all the group members
  {
           require_once( FRAMEWORK_PATH . 'models/message.php' );
			
           $message = new Message( $this->registry, 0 );
		  $subject="Event Invitation";
			$arr=array();
			
			// get inviter name	
				if($this->isteacher($this->creator))
				$data.="Prof ";	
		
		$data=$this->creator_name($this->creator);
		
	
		
		if($is_update==1)
		{
		$body="<p>".$data." has updated & Invited you to the event <a href=\"event/view/".$this->ID."\"> $this->name </a></p>";
		}
		else
		{
	    $body="<p>".$data." Invited you to the event <a href=\"event/view/".$this->ID."\"> $this->name </a></p>";
  	    }
	 
        $sql="SELECT gm.user as grp_mate FROM group_membership gm WHERE gm.group=".$grp_id." AND gm.user!=".$this->creator;
					  
	     $this->registry->getObject('db')->executeQuery($sql);
					  
		 while($data=$this->registry->getObject('db')->getRows()) 
		 {
		 $arr[]=$data['grp_mate'];
		 }
					
			if(is_array($arr) and count($arr)>0)    // iterating through groups members and sending mail
              {
                  foreach($arr as $grp_mate)
                  {		
					  $message->setSender( $this->creator);
				      $message->setRecipient( $grp_mate);
				      $message->setSubject($subject) ;
				      $message->setMessage($body);
					  $message->set_event_mail(1);
				      $message->save();
			      }
			}	
  
  }
  
  public function mail_invitee($id,$is_update)
  {
  
	require_once( FRAMEWORK_PATH . 'models/message.php' );
			
     $message = new Message( $this->registry, 0 );
	 $subject="Event Invitation";
	 
	 if($this->isteacher($this->creator))
	 $data.="Prof ";	
	 
	 $data=$this->creator_name($this->creator);	
	
		if($is_update==1)
		{
			$body="<p>".$data." has updated the event <a href=\"event/view/".$this->ID."\"> $this->name </a></p>";
		}
		else
		{
	     $body="<p>".$data." Invited you to the event <a href=\"event/view/".$this->ID."\"> $this->name </a></p>";
  	    }
	 
	 
	 $message->setSender( $this->creator);
     $message->setRecipient( $id);
	 $message->setSubject($subject) ;
	 $message->setMessage($body);
	 $message->set_event_mail(1);
	 $message->save();  
  
  }
  
  public function creator_name($id)       // used in inserting the name of creator in message  body
  {
        $sql="SELECT p.fname,p.lname from profile p WHERE p.ID=".$this->creator;
	$this->registry->getObject('db')->executeQuery($sql);
	$data=$this->registry->getObject('db')->getRows();  
	return $data['fname'].' '.$data['lname']; 
  }
  
  public function save_update()
  {
         $event=array();
          foreach($this as $field=>$data)
          {
            if(!is_array($data) && !is_object($data) && $field!='ID' && $field!='friendly_date')
            {
                $event[$field]=$this->$field;
            }
          } 
  $this->registry->getObject('db')->updateRecords('events',$event,'ID='.$this->ID);
  $this->insert_event_attendees(1);
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
  
  public function getName()
  {
      return $this->name;
  }
  
  public function getID()
  {
      return $this->ID;
  }
  public function getDate()
  {
  return $this->date;  
  }
  
  
  public function getType()
  {
      return $this->type;
  }
  public function getstart_time()
  {
      return $this->start_time;
  }
  public function getend_time()
  {
      return $this->end_time;
  }
  
  public function getdescription()
  {
  
  return $this->description;
  }
  
   public function setID($id)
  {
      $this->ID=$id;
  }
 
  public function setEvent($name)
  {
      $this->name=$name;
  }
    
  public function setCreator($creator)
  {
      $this->creator=$creator;
  }
  
  public function setDescription($description)
  {
      $this->description=$description;
  }
  
  public function setStartTime($start_time)
  {
      $this->start_time=$start_time;
  }
  
  public function setEndTime($end_time)
  {
      $this->end_time=$end_time;
  }
  
  public function setEventDate($event_date)
  {
      $this->date=$event_date;
  }
  
  public function setType($type,$checked=true)
  {
      if($checked==true)
      {
        $this->type=$type;
      }
      else
      {
          $types=array('Public','Private');
          if(in_array($type, $types))
          {
              $this->type=$type;
          }
      }
  }
  
  public function setActive($active)
  {
      $this->active=$active;
  }
  public function setInvitees($xyz)
  {
  $this->invitees=$xyz;
  }
  
  public function setgrp_Invitees($xyz)
  {
  $this->grp_invitees=$xyz;  
  }
  
 public function check_id($id)
 {
	$sql="select ID from events";
	$this->registry->getObject('db')->executeQuery($sql);
	while($data=$this->registry->getObject('db')->getRows())
	{
	if($id==$data['ID'])
	return true;  
	}
	return false;
 }

public function check_creator($id)
{
	$sql="select creator from events where ID=".$id;
	$this->registry->getObject('db')->executeQuery($sql);
	if( $this->registry->getObject('db')->numRows() == 1 )
	{
	    $data = $this->registry->getObject('db')->getRows();
		if($data['creator']==$this->registry->getObject('authenticate')->getUser()->getUserID())
		{
		return true;
		}
		else
		return false;
	}
	else
	{
	return false;
	}
}

public function delete_event($id)
{
	$this->registry->getObject('db')->deleteRecords( 'events','ID='.$id,2);
	if( $this->registry->getObject('db')->affectedRows() > 0 )        // affected rows not working
	return true;
	
	return true;
	
}

  public function getInvited()
  {
	
      $sql="SELECT distinct p.ID,p.* from profile p, event_attendees a where p.ID=a.user_id and a.status='invited' and a.event_id =" . $this->ID." AND a.isgroup=0";
      $cache=$this->registry->getObject('db')->cacheQuery($sql);
      return $cache;
  }
  
  public function getCreator()
  {
  
     return $this->creator;
  }
  
  public function getAttending()
  {
      $sql="select distinct p.ID,p.* from profile p, event_attendees a where a.user_id=p.ID and
          a.status='attending' and a.event_id = ".$this->ID." AND a.isgroup=0";
  
      $cache=$this->registry->getObject('db')->cacheQuery($sql);
      return $cache;
  }
  
  public function getNotAttending()
  {
      $sql="select distinct p.ID, p.* from profile p, event_attendees a where a.user_id=p.ID and
          a.status='not attending' and a.event_id = ".$this->ID." AND a.isgroup=0";
      $cache=$this->registry->getObject('db')->cacheQuery($sql);
      return $cache;
  }
  
  public function getMaybeAttending()
  {
      $sql="select distinct p.ID, p.* from profile p, event_attendees a where p.ID=a.user_id
          and a.status='maybe' and a.event_id = ".$this->ID." AND a.isgroup=0";
      $cache=$this->registry->getObject('db')->cacheQuery($sql);
      return $cache;
  }
 
 private function isteacher()
{
$my_id=$this->registry->getObject('authenticate')->getUser()->getUserID();

$sql="SELECT p.type FROM profile p WHERE p.ID=".$my_id;

$this->registry->getObject('db')->executeQuery( $sql );

$data = $this->registry->getObject('db')->getRows();

if($data['type']=="professor")
return true;
else
return false;

}
  
}
?>