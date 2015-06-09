<?php
/**
 * Private message class
 */
class Message {

	/**
	 * The registry object
	 */
	private $registry;
	
	/**
	 * ID of the message
	 */
	private $id=0;
	
	/**
	 * ID of the sender
	 */
	private $sender;
	
	/**
	 * Name of the sender
	 */
	private $senderFName;
        private $senderLName;
	
	/**
	 * ID of the recipient
	 */
	private $recipient;
	
	/**
	 * Name of the recipient
	 */
	private $recipientFName;
        private $recipientLName;
		
		
	
	/**
	 * Subject of the message
	 */
	private $subject;
	
	/**
	 * When the message was sent (TIMESTAMP)
	 */
	private $sent;
	
	/**
	 * User readable, friendly format of the time the message was sent
	 */
	private $sentFriendlyTime;
	
	/**
	 * Has the message been read
	 */
	private $read=0;
	
	/**
	 * The message content itself
	 */
	private $message;
	
	private $sender_delete=0,$recipient_delete=0,$draft=0,$trash=0,$event_mail=0;
	
	private $grp_invitees=array();
	
	private $isgroup;
	private $grpname;
	
	/**
	 * Message constructor
	 * @param Registry $registry the registry object
	 * @param int $id the ID of the message
	 * @return void
	 */
	public function __construct( Registry $registry, $id=0 )
	{
		$this->registry = $registry;
		$this->id = $id;
		if( $this->id > 0 )
		{
		
				if($this->isdraft($id))
				{
				$sql=$sql = "select m.*, date_format(m.sent,'%l:%i %p, %D %b %Y') as sent_friendly, psender.fname as sender_fname, psender.lname as sender_lname, NULL as recipient_fname, NULL as recipient_lname, draft from messages m, profile psender WHERE 
			           psender.ID=m.sender AND m.ID=" . $this->id;
				}
				else
				{
		       	$sql = "select m.*, date_format(m.sent,'%l:%i %p, %D %b %Y') as sent_friendly, psender.fname as sender_fname, psender.lname as sender_lname, precipient.fname as recipient_fname, precipient.lname as recipient_lname, draft from messages m, profile psender, profile precipient WHERE 
			          if(m.group=1,psender.ID=m.sender, precipient.ID=m.recipient) AND psender.ID=m.sender AND m.ID=" . $this->id;
			    }
			$this->registry->getObject('db')->executeQuery( $sql );
			if( $this->registry->getObject('db')->numRows() > 0 )
			{
				$data = $this->registry->getObject('db')->getRows();
				$this->sender = $data['sender'];
				$this->recipient = $data['recipient'];
				$this->sent = $data['sent'];
				$this->read = $data['read'];
				$this->subject = $data['subject'];
				$this->message = $data['message'];
				$this->sentFriendlyTime = $data['sent_friendly'];
				$this->senderFName = $data['sender_fname'];
				$this->recipientFName = $data['recipient_fname'];
                                $this->senderLName = $data['sender_lname'];
				$this->recipientLName = $data['recipient_lname'];
				$this->draft=$data['draft'];
				$this->isgroup=$data['group'];
				
				if($data['group']==1)				
				$this->grpname=$this->getgrpname($data['recipient']);
				
			}
			else
			{
				$this->id = 0;
			}
		}
	}
	
	/**
	 * Set the sender of the message
	 * @param int $sender
	 * @return void
	 */
	public function setSender( $sender )
	{
		$this->sender = $sender;	
	}
	
	/**
	 * Set the recipient of the message
	 * @param int $recipient 
	 * @return void
	 */
	public function setRecipient( $recipient )
	{
		$this->recipient = $recipient;
	}
	
	/**
	 * Set the subject of the message
	 * @param String $subject
	 * @return void
	 */
	public function setSubject( $subject )
	{
		$this->subject = $subject;
	}
	
  public function setgrp_Invitees($xyz)
  {
  $this->grp_invitees=$xyz;  
  }
	/**
	 * Set if the message has been read
	 * @param boolean $read
	 * @return void
	 */
	public function setRead( $read )
	{
		$this->read = $read;
	}
	
	public function setDraft($draft)
	{
		$this->draft=$draft;
		
	}
	
	/**
	 * Set the message itself
	 * @param String $message
	 * @return void
	 */
	public function setMessage( $message )
	{
		$this->message = $message;
	}
	public function set_event_mail($x)
	{
		$this->event_mail = $x;
	}
	public function settrash( $t )
	{
		$this->trash = $t;
	}
	/**
	 * Save the message into the database
	 * @return void
	 */
	public function save()
	{
		if( $this->id > 0 )
		{
			$update = array();
			$update['sender'] = $this->sender;
			$update['recipient'] = $this->recipient;
			$update['read'] = $this->read;
			$update['subject'] = $this->subject;
			$update['message'] = $this->message;
			$update['draft']=$this->draft;
			$insert['event_mail']=$this->event_mail;
			$this->registry->getObject('db')->updateRecords( 'messages', $update, 'ID=' . $this->id );
		}
		else
		{
			$insert = array();
			$insert['sender'] = $this->sender;
			$insert['recipient'] = $this->recipient;
			$insert['read'] = $this->read;
			$insert['subject'] = $this->subject;
			$insert['message'] = $this->message;
			$insert['draft']=$this->draft;
			$insert['event_mail']=$this->event_mail;
			
			$this->groupmsg();
			
			if($insert['recipient']==0 )
			{
			if( $this->draft==1)				
			$this->registry->getObject('db')->insertRecords( 'messages', $insert );
			}
			else
			{
			$this->registry->getObject('db')->insertRecords( 'messages', $insert );
			}
			$this->id = $this->registry->getObject('db')->lastInsertID();
		}
	}
	
	public function groupmsg()
	{
	
		if(is_array($this->grp_invitees) and count($this->grp_invitees)>0)
              {
                  foreach($this->grp_invitees as $grp_invitee)
                  {		

						      $insert = array();                               // for group name
								$insert['sender'] = $this->sender;
								$insert['recipient'] =$grp_invitee;
								$insert['read'] = $this->read;
								$insert['subject'] = $this->subject;
								$insert['message'] = $this->message;
								$insert['draft']=$this->draft;
								$insert['event_mail']=0;
								$insert['group']=1;                                 // indicating to retrieve group name
								
								$this->registry->getObject('db')->insertRecords( 'messages', $insert );			
			
						
				if($this->draft==1);
				else
				{
						

				  
					$sql="SELECT gm.user as grp_mate FROM group_membership gm WHERE gm.group=".$grp_invitee." AND gm.user!=".$this->sender." and gm.approved=1";		  
					$this->registry->getObject('db')->executeQuery($sql);
						$arr=array();
					while($data=$this->registry->getObject('db')->getRows()) 
					{
							$arr[]=$data['grp_mate'];
					}
					//To include the group creator
					$sql="SELECT g.creator as grp_mate FROM groups g WHERE g.ID=".$grp_invitee." AND g.creator!=".$this->sender;		  
					$this->registry->getObject('db')->executeQuery($sql);
			
					while($data=$this->registry->getObject('db')->getRows()) 
					{
							$arr[]=$data['grp_mate'];
					}
					if(is_array($arr) and count($arr)>0)    // iterating through groups members and sending mail
						{
							foreach($arr as $grp_mate)
							{		
							
								$insert = array();
								$insert['sender'] = $this->sender;
								$insert['recipient'] =$grp_mate;
								$insert['read'] = $this->read;
								$insert['subject'] = $this->subject;
								$insert['message'] = $this->message;
								$insert['draft']=$this->draft;
								$insert['event_mail']=1;
								$insert['group']=0;
								
								$this->registry->getObject('db')->insertRecords( 'messages', $insert );
							}
				  
						}
				}		
					
				}			
					
					 
                  }
              
	
	
	}
	
public function getgrpname($id)
{
$sql="select name from groups g where g.ID=".$id;
$this->registry->getObject('db')->executeQuery( $sql );

$row=$this->registry->getObject('db')->getRows();

return $row['name'];

}	
private function sendsms()
	{
	require_once( FRAMEWORK_PATH . 'models/sms.php' );
	
    $msg="student dashboard  Sender: ".$this->getsender_name()."Subject: ".$this->subject ."Visit dashboard to check it";
	
//	$msg="student dashboard  S";
	$sms=new Sms($this->registry,$this->recipient,$msg);
	
	$sms->sendsms();
	
	}	
	
	/**
	 * Get the recipient of the message
	 * @return int
	 */
	public function getRecipient()
	{
		return $this->recipient;
	}
		
   public function getgroup()
	{
		return $this->isgroup;
	}
	
	public function getSender_delete()
	{
	return $this->sender_delete;
	}
	
	public function getRecipient_delete()
	{
	return $this->sender_delete;
	}
	/**
	 * Get the sender of the message
	 * @return int
	 */
	public function getSender()
	{
		return $this->sender;
	}
	public function getMessage()
	{
		return $this->message;
	}
	/**
	 * Get the subject of the message
	 */
	public function getSubject()
	{
		return $this->subject;
	}
	
	/**
	 * Convert the message data to template tags
	 * @param String $prefix prefix for the template tags
	 * @return void
	 */
	public function toTags( $prefix='' )
	{
		foreach( $this as $field => $data )
		{
			if( ! is_object( $data ) && ! is_array( $data ) )
			{
				$this->registry->getObject('template')->getPage()->addTag( $prefix.$field, $data );
			}
		}
	}
	
	/**
	 * Delete the current message
	 * @return boolean
	 */
	public function delete_Recipient()
	{
		$sql="select sender_delete from messages where ID=".$this->id;
		$this->registry->getObject('db')->executeQuery( $sql );
		$row=$this->registry->getObject('db')->getRows();
		
		if($row['sender_delete']==1)
		{

		$this->registry->getObject('db')->deleteRecords( 'messages','ID=' . $this->id,2);
		
			if( $this->registry->getObject('db')->affectedRows() > 0 )
			{
			$this->id =0;
			return true;
			}
			else
			{
			$this->id =0;
			return true;                      // some error occuring
			}
		}
		else
		{
			$this->recipient_delete=1;
			$update=array();
			$update['recipient_delete']=1;
			$this->registry->getObject('db')->updateRecords( 'messages', $update, 'ID=' . $this->id );
			return true;		
		}
	}
	public function delete_Sender()
	{
				
		$sql="select recipient_delete from messages where ID=".$this->id;
		$this->registry->getObject('db')->executeQuery( $sql );
		$row=$this->registry->getObject('db')->getRows();
	
		
	if($this->draft==1)
	{
	$this->registry->getObject('db')->deleteRecords( 'messages','ID=' . $this->id,2);
	return true;
	}
	else
	{
		
		if($row['recipient_delete']==1)
		{	

	     $this->registry->getObject('db')->deleteRecords( 'messages','ID=' . $this->id,2);
		
			if( $this->registry->getObject('db')->affectedRows() > 0 )
			{
			$this->id =0;
			return true;
			}
			else
			{
			$this->id =0;
			return true;
			
			}
		}
		
		else
		{
			$this->sender_delete=1;
			$update=array();
			$update['sender_delete']=1;
			$this->registry->getObject('db')->updateRecords( 'messages', $update, 'ID=' . $this->id );
			return true;		
			
		}
	}

	
	}
	
	
private function isdraft($id)
{
$sql="SELECT m.recipient from messages m where m.ID={$id}";

$this->registry->getObject('db')->executeQuery( $sql );
		//	if( $this->registry->getObject('db')->numRows() > 0 )
			
				$data = $this->registry->getObject('db')->getRows();
				
				if($data['recipient']==0)
				return true;
				else
				return false;
			
			

			
}	
	
}


?>