<?php
/**
 * Messages controller
 * Basic private message system for Dino Space
 */
class Messagescontroller {
	
	/**
	 * Messages controller constructor
	 * @param Registry $registry
	 * @param boolean $directCall
	 * @return void
	 */
	public function __construct( Registry $registry, $directCall=true )
	{
		$this->registry = $registry;
		if( $this->registry->getObject('authenticate')->isLoggedIn() )
		{
			$urlBits = $this->registry->getObject('url')->getURLBits();
			if( isset( $urlBits[1] ) )
			{
				switch( $urlBits[1] )
				{
					case 'view':
						$this->viewMessage( intval( $urlBits[2] ) );
						break;
					case 'delete':
						$this->deleteMessage( intval( $urlBits[2] ) );
						break;
					case 'create':
						$this->newMessage( isset( $urlBits[2] ) ? intval( $urlBits[2] ) : 0 );
						break;	
					case 'sent':
						$this->sentitem();
						break;
					case 'draft':
						$this->draft(isset( $urlBits[2] ) ? $urlBits[2]: '', isset( $urlBits[3] ) ? intval( $urlBits[3] ) : 0);  
						break;
					default:
						$this->viewInbox();
						break;
				}
			}
			else
			{
				$this->viewInbox();
			}
		}
	}
	
	/**
	 * View your inbox
	 * @return void
	 */
	private function viewInbox()
	{
		$this->registry->getObject('template')->getPage()->setTitle($this->registry->getSetting('sitename').' | Inbox');
		require_once( FRAMEWORK_PATH . 'models/messages.php' );
		$messages = new Messages( $this->registry );
		$cache = $messages->getInbox( $this->registry->getObject('authenticate')->getUser()->getUserID() );
		$this->registry->getObject('template')->buildFromTemplates('header.tpl.php', 'messages/inbox.tpl.php', 'footer.tpl.php');
		$this->registry->getObject('template')->getPage()->addTag( 'messages', array( 'SQL', $cache ) );
			
	}
	
	private function sentitem()
	{
		$this->registry->getObject('template')->getPage()->setTitle($this->registry->getSetting('sitename').' | Sent Items');
		require_once( FRAMEWORK_PATH . 'models/messages.php' );
		$messages = new Messages( $this->registry );
		$cache = $messages->getsent( $this->registry->getObject('authenticate')->getUser()->getUserID() );
		$this->registry->getObject('template')->buildFromTemplates('header.tpl.php', 'messages/sent.tpl.php', 'footer.tpl.php');
		$this->registry->getObject('template')->getPage()->addTag( 'messages', array( 'SQL', $cache ) );
		$this->registry->getObject('template')->getPage()->addTag( 'grp_messages', array( 'SQL', $messages->getsent_grp($this->registry->getObject('authenticate')->getUser()->getUserID()) ) );
			
	}
	
	/**
	 * View a message
	 * @param int $message the ID of the message
	 * @return void
	 */
	private function viewMessage( $message )
	{
		$this->registry->getObject('template')->getPage()->setTitle($this->registry->getSetting('sitename').' | View Message');
		require_once( FRAMEWORK_PATH . 'models/message.php' );
		$message = new Message( $this->registry, $message );
		if( $message->getRecipient() == $this->registry->getObject('authenticate')->getUser()->getUserID())
		{
			$this->registry->getObject('template')->buildFromTemplates('header.tpl.php', 'messages/view.tpl.php', 'footer.tpl.php');
			$message->toTags( 'inbox_' );
			$message->setRead(1);
			$message->save();
		}
		elseif($message->getSender()==$this->registry->getObject('authenticate')->getUser()->getUserID() and $message->getgroup()==0)               // my changes
		{
		 $this->registry->getObject('template')->buildFromTemplates('header.tpl.php', 'messages/view_sent.tpl.php', 'footer.tpl.php');
			$message->toTags( 'inbox_' );
		}
		elseif($message->getSender()==$this->registry->getObject('authenticate')->getUser()->getUserID() and $message->getgroup()==1)
		{
			
			$this->registry->getObject('template')->buildFromTemplates('header.tpl.php', 'messages/view_sent_grp.tpl.php', 'footer.tpl.php');
			$message->toTags( 'inbox_' );
		
		}
		else
		{
			$this->registry->errorPage( 'Access denied', 'Sorry, you are not allowed to view that message');
		}
		
	}
	
	/**
	 * Compose a new message, and process new message submissions
	 * @parm int $reply message ID this message is in reply to [optional] only used to pre-populate subject and recipient
	 * @return void
	 */
	private function newMessage( $reply=0 )
	{
			if($this->registry->getObject('authenticate')->getUser()->isadmin())
			{
			$this->registry->getObject('template')->buildFromTemplates('header.tpl.php', 'messages/create_admin.tpl.php', 'footer.tpl.php');
			}
			else
			{
			$this->registry->getObject('template')->buildFromTemplates('header.tpl.php', 'messages/create.tpl.php', 'footer.tpl.php');
			}
			
		require_once( FRAMEWORK_PATH . 'models/relationships.php' );
		$relationships = new Relationships( $this->registry );
		
		if( isset( $_POST ) && count( $_POST ) > 0 )
		{
			$network = $relationships->getNetwork( $this->registry->getObject('authenticate')->getUser()->getUserID() );
			$recipient =$_POST['recipient'];
			
			if( in_array( $recipient, $network ) || $recipient==0 || $recipient==1 || $this->registry->getObject('authenticate')->getUser()->isadmin())
			{
				// this additional check may not be something we require for private messages?	
				require_once( FRAMEWORK_PATH . 'models/message.php' );
				$message = new Message( $this->registry, 0 );
				$message->setSender( $this->registry->getObject('authenticate')->getUser()->getUserID() );
					if($recipient!=0)
				$message->setRecipient( $recipient );
			//	if($recipient==1)
			//	$message->setRecipient(1);
				$message->setSubject( $this->registry->getObject('db')->sanitizeData( $_POST['subject'] ) );
				$message->setMessage( $this->registry->getObject('db')->sanitizeData( $_POST['message'] ) );
				
				if( isset( $_POST['grp_invitees'] ) && is_array( $_POST['grp_invitees'] ) && count( $_POST['grp_invitees'] ) > 0 )
			{// assumes invitees are added to a table using javascript, with a hidden field with name invitees[] for the ID of invitee 
				$is = array();
				foreach( $_POST['grp_invitees'] as $i )
				{
					$is[] = intval( $i );
				}
				$message->setgrp_Invitees( $is );
			}					
				
				$message->save();
				// email notification to the recipient perhaps??
				
				// confirm, and redirect
                            header('Location: '.$this->registry->getSetting('siteurl').'messages/sent');
                         }
			else
			{
				$this->registry->errorPage('Invalid recipient', 'Sorry, you can only send messages to your recipients');
			}
		}
		else
		{
				$this->registry->getObject('template')->getPage()->setTitle($this->registry->getSetting('sitename').' | Compose Message');
				$cache=0;$cache2=0;
				if($this->registry->getObject('authenticate')->getUser()->isadmin())
				{
				$cache=$this->getalluser();
				$cache2=$this->getallgroup();	
				}
				else
				{
				$id=$this->registry->getObject('authenticate')->getUser()->getUserID();
			    $cache = $relationships->getByUser( $this->registry->getObject('authenticate')->getUser()->getUserID(),'both');
				
				$sql="SELECT distinct g.name as grp_name,g.ID as grp_ID FROM groups g,group_membership gm WHERE (gm.user={$id} AND gm.group=g.ID) or (g.creator={$id})";
			    $cache2=$this->registry->getObject('db')->cacheQuery($sql);
				}
		
			
			$this->registry->getObject('template')->getPage()->addTag( 'recipients', array( 'SQL', $cache ) );
			$this->registry->getObject('template')->getPage()->addTag( 'opt','');
			
			$this->registry->getObject('template')->getPage()->addTag('grp_invitees',array('SQL',$cache2));
			
			if( $reply > 0 )
			{
				require_once( FRAMEWORK_PATH . 'models/message.php' );
				$message = new Message( $this->registry, $reply );
				if( $message->getRecipient() == $this->registry->getObject('authenticate')->getUser()->getUserID() )
				{
					$this->registry->getObject('template')->getPage()->addAdditionalParsingData( 'recipients', 'ID', $message->getSender(), 'opt', "selected='selected'");
					$this->registry->getObject('template')->getPage()->addTag( 'subject', 'Re: ' . $message->getSubject() );
				}
				else
				{
					$this->registry->getObject('template')->getPage()->addTag( 'subject', '' );
				}
				
			}
			else
			{
				$this->registry->getObject('template')->getPage()->addTag( 'subject', '' );
			}
		}
		
	}
	
	private function draft($save,$id)
	{			

	if($id==0 || $save=='save')              // if id=0 then user is saving draft  else he is sending the draft  
	{
		if( isset( $_POST ) && count( $_POST ) > 0 )
		{
		require_once( FRAMEWORK_PATH . 'models/relationships.php' );
		$relationships = new Relationships( $this->registry );
			
			$network = $relationships->getNetwork( $this->registry->getObject('authenticate')->getUser()->getUserID() );
			$recipient = intval( $_POST['recipient'] );
	if( in_array( $recipient, $network ) || $recipient==0 || $recipient==1 || $this->registry->getObject('authenticate')->getUser()->isadmin())
			{
				// this additional check may not be something we require for private messages?	
				require_once( FRAMEWORK_PATH . 'models/message.php' );
				$message = new Message( $this->registry, $id );
				$message->setSender( $this->registry->getObject('authenticate')->getUser()->getUserID() );
				if($recipient!=0)
				$message->setRecipient( $recipient );
				$message->setSubject( $this->registry->getObject('db')->sanitizeData( $_POST['subject'] ) );
				$message->setMessage( $this->registry->getObject('db')->sanitizeData( $_POST['message'] ) );
				$message->setDraft(1);
			if( isset( $_POST['grp_invitees'] ) && is_array( $_POST['grp_invitees'] ) && count( $_POST['grp_invitees'] ) > 0 )
			{// assumes invitees are added to a table using javascript, with a hidden field with name invitees[] for the ID of invitee 
				$is = array();
				foreach( $_POST['grp_invitees'] as $i )
				{
					$is[] = intval( $i );
				}
				$message->setgrp_Invitees( $is );
			}					
				
				$message->save();
				
				
				// confirm, and redirect
				header('Location: '.$this->registry->getSetting('siteurl').'messages/draft');
			}
			else
			{
				$this->registry->errorPage('Specify recipient', 'Sorry, you need to sepcify the recipient');
			}
		}
		else
		{
		$this->registry->getObject('template')->getPage()->setTitle($this->registry->getSetting('sitename').' | Drafts');
		require_once( FRAMEWORK_PATH . 'models/messages.php' );
		$messages = new Messages( $this->registry );
		$this->registry->getObject('template')->buildFromTemplates('header.tpl.php', 'messages/view_draft.tpl.php', 'footer.tpl.php');
		$cache = $messages->getDraft( $this->registry->getObject('authenticate')->getUser()->getUserID() );
		$this->registry->getObject('template')->getPage()->addTag( 'messages', array( 'SQL', $cache ) );
		$this->registry->getObject('template')->getPage()->addTag( 'empty_messages', array( 'SQL', $messages->getemptydraft($this->registry->getObject('authenticate')->getUser()->getUserID()) ) );
		$this->registry->getObject('template')->getPage()->addTag( 'grp_messages', array( 'SQL', $messages->getdraft_grp($this->registry->getObject('authenticate')->getUser()->getUserID()) ) );
		
		}
	}
	
	else         // for sending draft
	{
	
		$this->registry->getObject('template')->getPage()->setTitle($this->registry->getSetting('sitename').' | Edit Draft');
		$this->registry->getObject('template')->buildFromTemplates('header.tpl.php', 'messages/draft_edit.tpl.php', 'footer.tpl.php');
	
	  require_once( FRAMEWORK_PATH . 'models/relationships.php' );
		$relationships = new Relationships( $this->registry );
	     $cache = $relationships->getByUser( $this->registry->getObject('authenticate')->getUser()->getUserID(),'both' );
			$this->registry->getObject('template')->getPage()->addTag( 'recipients', array( 'SQL', $cache ) );
			if( $id > 0 )
			{
				require_once( FRAMEWORK_PATH . 'models/message.php' );
				$message = new Message( $this->registry, $id );
				if( $message->getSender() == $this->registry->getObject('authenticate')->getUser()->getUserID() )
				{
					$this->registry->getObject('template')->getPage()->addAdditionalParsingData( 'recipients', 'ID', $message->getSender(), 'opt', "selected='selected'");
					$this->registry->getObject('template')->getPage()->addTag( 'subject',$message->getSubject() );
					$this->registry->getObject('template')->getPage()->addTag( 'message',$message->getMessage() );
					$this->registry->getObject('template')->getPage()->addTag( 'delete_id',$id );
				}
				else
				{
					$this->registry->getObject('template')->getPage()->addTag( 'subject', '' );
				}
				
			}
			else
			{
				$this->registry->errorPage('some error', 'retry there is some error');
			}
	
	
	}
	
	
}
	
	
	/**
	 * Delete a message
	 * @param int $message the message ID
	 * @return void
	 */
	private function deleteMessage( $message_id )
	{
		require_once( FRAMEWORK_PATH . 'models/message.php' );
		$message = new Message( $this->registry, $message_id );
		if( $message->getRecipient() == $this->registry->getObject('authenticate')->getUser()->getUserID() )
		{
			if( $message->delete_Recipient() )
			{
                            header('Location: '.$this->registry->getSetting('siteurl').'messages/inbox');
                        }
			else
			{
				$this->registry->errorPage( 'Sorry...', 'An error occured while trying to delete the message');
			}
		}
		elseif($message->getSender() == $this->registry->getObject('authenticate')->getUser()->getUserID())
		{
				if($message->delete_Sender())
				{
                                    if($_POST['msgtype']=='draft')
                                    {
                                        header('Location: '.$this->registry->getSetting('siteurl').'messages/draft');
                                    }
                                    else
                                    {
                                        header('Location: '.$this->registry->getSetting('siteurl').'messages/sent');
                                    }
				}	
				else
				{
				
				$this->registry->errorPage( 'Sorry...', 'An error occured while trying to delete the message');
				}
			
		}
		
		else
		{
			$this->registry->errorPage( 'Access denied', 'Sorry, you are not allowed to delete that message');
		}
	}
	
	public function getalluser()
	{
	$sql="SELECT p.ID,p.fname,p.lname FROM profile p,users u WHERE p.ID=u.ID and u.admin=0";
	$cache=$this->registry->getObject('db')->cacheQuery($sql);
	
	return $cache;
	
	}
	public function getallgroup()
	{
	
	$sql="SELECT g.name as grp_name,g.ID as grp_ID FROM groups g";
    $cache2=$this->registry->getObject('db')->cacheQuery($sql);
	return $cache2;
	}
	
	
	
}


?>