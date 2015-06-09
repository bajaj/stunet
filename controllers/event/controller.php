<?php

class Eventcontroller {
	
	/**
	 * Controller constructor - direct call to false when being embedded via another controller
	 * @param Registry $registry our registry
	 * @param bool $directCall - are we calling it directly via the framework (true), or via another controller (false)
	 */
	public function __construct( Registry $registry, $directCall )
	{
		$this->registry = $registry;
		if( $this->registry->getObject('authenticate')->isLoggedIn() )
		{
			$urlBits = $this->registry->getObject('url')->getURLBits();
			if( isset( $urlBits[1] ) )
			{
				switch( $urlBits[1] )
				{
					case 'create':
						$this->createEvent();
						break;	
					case 'view':
						$this->viewEvent(intval($urlBits[2]));
						break;
					case 'change-attendance':
						$this->changeAttendance( intval( $urlBits[2] ) );
						break;
					case 'edit':
						$this->editevent(intval($urlBits[2]));
						break;
					case 'update':
						$this->updateevent(intval($urlBits[2]));
						break;
					case 'delete':
						$this->delevent(intval($urlBits[2]));
						break;
                                        /*case 'mine':
                                                $this->listMyEvents();
                                                break;*/
					default:
						$this->listUpcomingInNetwork();
						break;
				}	
			}
			else
			{
				$this->listUpcomingInNetwork();
			}
		}
	}
	
	/**
	 * Create an event
	 * @return void
	 */
	private function createEvent()
	{
		// if post data is set, we are creating an event
		if( isset( $_POST ) && count( $_POST ) > 0)
		{
			require_once( FRAMEWORK_PATH . 'models/event.php' );
			$event = new Event( $this->registry, 0 );
			$event->setEvent( $this->registry->getObject('db')->sanitizeData( $_POST['name'] ) );
			$event->setDescription( $this->registry->getObject('db')->sanitizeData( $_POST['description'] ) );
			$event->setEventDate($this->registry->getObject('db')->sanitizeData( $_POST['event_year']).'-'.$this->registry->getObject('db')->sanitizeData( $_POST['event_month']).'-'.$this->registry->getObject('db')->sanitizeData( $_POST['event_day']));
			$event->setStartTime( $this->registry->getObject('db')->sanitizeData( $_POST['start_time'] ) );
			$event->setEndTime( $this->registry->getObject('db')->sanitizeData( $_POST['end_time'] ) );
			$event->setCreator( $this->registry->getObject('authenticate')->getUser()->getUserID() );
			$event->setType( $this->registry->getObject('db')->sanitizeData( $_POST['type'] ) );
			
			
			if( isset( $_POST['invitees'] ) && is_array( $_POST['invitees'] ) && count( $_POST['invitees'] ) > 0 )
			{// assumes invitees are added to a table using javascript, with a hidden field with name invitees[] for the ID of invitee 
				$is = array();
				foreach( $_POST['invitees'] as $i )
				{
					$is[] = intval( $i );
				}
				$event->setInvitees( $is );
			}
			if( isset( $_POST['grp_invitees'] ) && is_array( $_POST['grp_invitees'] ) && count( $_POST['grp_invitees'] ) > 0 )
			{// assumes invitees are added to a table using javascript, with a hidden field with name invitees[] for the ID of invitee 
				$is = array();
				foreach( $_POST['grp_invitees'] as $i )
				{
					$is[] = intval( $i );
				}
				$event->setgrp_Invitees( $is );
			}
			$event->save();
			header('Location: '.$this->registry->getSetting('siteurl').'event/view/'.$event->getID());
		}
		else
		{
                    $this->registry->getObject('template')->getPage()->setTitle($this->registry->getSetting('sitename').' | Create An Event');
                    require_once( FRAMEWORK_PATH . 'models/event.php' );
			$id=$this->registry->getObject('authenticate')->getUser()->getUserID();
			$sql="SELECT u.ID,p.fname,p.lname FROM users u,profile p,relationships r WHERE r.accepted=1 AND
			(r.usera={$id} OR r.userb={$id}) AND IF(r.usera={$id},u.ID=r.userb,u.ID=r.usera) AND p.ID=u.ID ORDER by p.fname" ;		
			$cache=$this->registry->getObject('db')->cacheQuery($sql);
			$this->registry->getObject('template')->buildFromTemplates( 'header.tpl.php', 'events/create.tpl.php', 'footer.tpl.php' );
			$this->registry->getObject('template')->getPage()->addTag('invitees',array('SQL',$cache));
			$sql="SELECT g.name as grp_name,g.ID as grp_ID FROM groups g,group_membership gm WHERE gm.user={$id} AND gm.group=g.ID";
			$cache2=$this->registry->getObject('db')->cacheQuery($sql);
			$this->registry->getObject('template')->getPage()->addTag('grp_invitees',array('SQL',$cache2));	
		}
	}
	
private function updateevent($id)
{
$sql="select creator from events where ID=".$id;
	$this->registry->getObject('db')->executeQuery($sql);
	if( $this->registry->getObject('db')->numRows() == 1 )
	{
	$data = $this->registry->getObject('db')->getRows();
		if($data['creator']==$this->registry->getObject('authenticate')->getUser()->getUserID())
		{
			if( isset( $_POST ) && count( $_POST ) > 0)
			{
			require_once( FRAMEWORK_PATH . 'models/event.php' );
			$event = new Event( $this->registry, 0 );
			$event->setID(intval($id));
			$event->setEvent( $this->registry->getObject('db')->sanitizeData( $_POST['name'] ) );
			$event->setDescription( $this->registry->getObject('db')->sanitizeData( $_POST['description'] ) );
			$event->setEventDate($this->registry->getObject('db')->sanitizeData( $_POST['event_year']).'-'.$this->registry->getObject('db')->sanitizeData( $_POST['event_month']).'-'.$this->registry->getObject('db')->sanitizeData( $_POST['event_day']));
			$event->setStartTime( $this->registry->getObject('db')->sanitizeData( $_POST['start_time'] ) );
			$event->setEndTime( $this->registry->getObject('db')->sanitizeData( $_POST['end_time'] ) );
			$event->setCreator( $this->registry->getObject('authenticate')->getUser()->getUserID() );
			$event->setType( $this->registry->getObject('db')->sanitizeData( $_POST['type'] ) );
			if( isset( $_POST['invitees'] ) && is_array( $_POST['invitees'] ) && count( $_POST['invitees'] ) > 0 )
			{
				// assumes invitees are added to a table using javascript, with a hidden field with name invitees[] for the ID of invitee 
				$is = array();
				foreach( $_POST['invitees'] as $i )
				{
					$is[] = intval( $i );
				}
				$event->setInvitees( $is );
			}
			if( isset( $_POST['grp_invitees'] ) && is_array( $_POST['grp_invitees'] ) && count( $_POST['grp_invitees'] ) > 0 )
			{
				// assumes invitees are added to a table using javascript, with a hidden field with name invitees[] for the ID of invitee 
				$is = array();
				foreach( $_POST['grp_invitees'] as $i )
				{
					$is[] = intval( $i );
				}
				$event->setgrp_Invitees( $is );
			}
			$event->save_update();
			header('Location: '.$this->registry->getSetting('siteurl').'event/view/'.$id);
			}
			else
			{
			$this->registry->errorPage('Form not submitted', 'To update an event please submit the update form');
			}	
		}
		else
		{
		$this->registry->errorPage('You can\'t update', 'Since you are not the creator you can\'t update this event');
		}
	}
	else
	{
	$this->registry->errorPage('No such event', 'The specified event does not exist');
	}

}
	/**
	 * View an event
	 * @param int $id
	 * @return void
	 */
private function viewEvent( $id=0)
{
   if($id==0)
   {
		$this->registry->errorPage('No such event','The specified event does not exist');	
   }
   else
   {		
	 require_once(FRAMEWORK_PATH.'models/event.php');
     $event=new Event($this->registry,$id);
	 if(!$event->check_id($id))
	 {	
		$this->registry->errorPage('No such event','The specified event does not exist');
     }	
	 else
	 {	
        $show=true;
        if($event->getType()=='Private')
        {
            //what to do for private events?
            $show=false;
			
		$event->toTags('event_');
           if($event->getCreator()==$this->registry->getObject('authenticate')->getUser()->getUserID())
		   {
		   $this->registry->getObject('template')->buildFromTemplates('header.tpl.php','events/view_private.tpl.php','footer.tpl.php');
		   
		   }
		   
		   else
		   {
            $this->registry->errorPage('It\'s an Private Event','Sorry you are not allowed to view the details of this event');
    	   }	   
        }
        else
        {
        if($show==true)
        {
            $sql="select * from event_attendees where user_id=".$this->registry->getObject('authenticate')->getUser()->getUserID()." and event_id=".$id;
            $this->registry->getObject('db')->executeQuery($sql);
            if($this->registry->getObject('db')->numRows()!=1)
            {
                $this->registry->redirectUser($this->registry->getSetting('siteurl').'event','Access Denied','Sorry, you are not allowed to view this event');
            }
            else
            {
            $event->toTags('event_');
			if($event->getCreator()==$this->registry->getObject('authenticate')->getUser()->getUserID())
			{
			$this->registry->getObject('template')->buildFromTemplates('header.tpl.php','events/view_creator.tpl.php','footer.tpl.php');
			}
			else
			{
			$this->registry->getObject('template')->buildFromTemplates('header.tpl.php','events/view.tpl.php','footer.tpl.php');
			}
            $this->registry->getObject('template')->getPage()->setTitle($this->registry->getSetting('sitename').' | View Event');
            $this->registry->getObject('template')->getPage()->addTag('invited',array('SQL',$event->getInvited()));
            $this->registry->getObject('template')->getPage()->addTag('attending',array('SQL',$event->getAttending()));
            $this->registry->getObject('template')->getPage()->addTag('notattending',array('SQL',$event->getNotAttending()));
            $this->registry->getObject('template')->getPage()->addTag('maybeattending',array('SQL',$event->getMaybeAttending()));
            $sql="select * from event_attendees where event_id={$id} and user_id=". $this->registry->getObject('authenticate')->getUser()->getUserID();
           $this->registry->getObject('db')->executeQuery($sql);
           if($this->registry->getObject('db')->numRows()==1)
           {
               $data=$this->registry->getObject('db')->getRows();
               if($data['status']=='maybe')
               {
                   $s='maybeattending';
               }
               elseif($data['status']=='attending')
               {
                   $s='attending';
               }
               elseif($data['status']=='not attending')
               {
                   $s='notattending';
               }
               else
               {
                   $s='unknown';
               }
               $this->registry->getObject('template')->getPage()->addTag($s.'_select', "selected='selected'");
           }
           else
           {
               $this->registry->getObject('template')->getPage()->addTag('unknown_select',"selected='selected'");
           }
            }
        }
        else
        {
            $this->registry->errorPage('Access denied','Sorry you are not allowed to view this page');
    	}
	   }
         }
	}
}

private function editevent($id)
{
	$sql="select creator from events where ID=".$id;
	$this->registry->getObject('db')->executeQuery($sql);   
	if( $this->registry->getObject('db')->numRows() == 1 )
	{
                $data = $this->registry->getObject('db')->getRows();
		if($data['creator']==$this->registry->getObject('authenticate')->getUser()->getUserID())
		{
                    $this->registry->getObject('template')->getPage()->setTitle($this->registry->getSetting('sitename').' | Edit Event');
                    require_once(FRAMEWORK_PATH.'models/event.php');
                    $event=new Event($this->registry,$id);
                    $date=explode('-',$event->getDate());
                    $type=($event->getType()=='Public')?0:1;
                    $script="";
                    $script.="<script type='text/javascript'>";
                    $script.="document.getElementById('type').selectedIndex=".$type.';';
                    $script.="document.getElementById('event_day').selectedIndex=".intval($date[2]).';';
                    $script.="document.getElementById('event_month').selectedIndex=".intval($date[1]).';';
                    $script.="document.getElementById('event_year').selectedIndex=".(intval($date[0])-2013+1).';';
                    $script.="</script>";
                    $this->registry->getObject('template')->buildFromTemplates( 'header.tpl.php', 'events/edit.tpl.php', 'footer.tpl.php' );
                    $this->registry->getObject('template')->getPage()->addTag('script',$script);
                    $this->registry->getObject('template')->getPage()->addTag('ID',$event->getID());
                    $this->registry->getObject('template')->getPage()->addTag('start_time',$event->getstart_time());
                    $this->registry->getObject('template')->getPage()->addTag('name',$event->getName());
                    $this->registry->getObject('template')->getPage()->addTag('end_time',$event->getend_time());	
                    $this->registry->getObject('template')->getPage()->addTag('description',$event->getdescription());
                    $mid=$this->registry->getObject('authenticate')->getUser()->getUserID();
                    $sql="SELECT u.ID,p.fname,p.lname FROM users u,profile p,relationships r WHERE r.accepted=1 AND
                    (r.usera={$mid} OR r.userb={$mid}) AND IF(r.usera={$mid},u.ID=r.userb,u.ID=r.usera) AND p.ID=u.ID and u.ID not in 
                    (select user_id from event_attendees where isgroup=0 and event_id={$id})ORDER by p.fname" ;
                    $cache=$this->registry->getObject('db')->cacheQuery($sql);
                    $this->registry->getObject('template')->getPage()->addTag('invitees',array('SQL',$cache));
                    $sql="SELECT g.name as grp_name,g.ID as grp_ID FROM groups g,group_membership gm WHERE gm.user={$mid} AND gm.group=g.ID
                    and g.ID not in (select user_id from event_attendees where isgroup=1 and event_id={$id})";
                    $cache2=$this->registry->getObject('db')->cacheQuery($sql);
                    $this->registry->getObject('template')->getPage()->addTag('grp_invitees',array('SQL',$cache2));
                }
		else
		{
		$this->registry->errorPage('You can\'t edit this event ', 'Since you are not the creator of this event you can\'t edit it');
		}
	}
	else
	{
	$this->registry->errorPage('No such event', 'There is no such event that you want to edit');
	}
}
	
private function delevent($id)
{
	require_once(FRAMEWORK_PATH.'models/event.php');
	$event=new Event($this->registry,0);
	if(!$event->check_id($id))
	{
            $this->registry->errorPage('No Such Event', 'There is no such event that you want to edit');
	}
	else
	{
		if(!$event->check_creator($id))
		{
                    $this->registry->errorPage('You can\'t delete this event', 'since you are not creator you can\'t delete this event');
		}
		else
		{
			if($event->delete_event($id))
			{
                            header('Location: '.$this->registry->getSetting('siteurl').'event/');
			}
			else
			{		
				$this->registry->errorPage('Error', 'Due to some technical error you can\'t delete this event Now.');
			}
		}	
	}
}	
	
	private function changeAttendance( $event )
	{
		$sql = "SELECT * FROM event_attendees WHERE event_id={$event} AND user_id=" . $this->registry->getObject('authenticate')->getUser()->getUserID();
		$this->registry->getObject('db')->executeQuery( $sql );
		if( $this->registry->getObject('db')->numRows() == 1 )
		{
			$data = $this->registry->getObject('db')->getRows();
			$changes = array();
			$changes['status'] = $this->registry->getObject('db')->sanitizeData( $_POST['status'] );
			$this->registry->getObject('db')->updateRecords( 'event_attendees', $changes, 'ID=' . $data['ID'] );
			$this->registry->redirectUser( $this->registry->getObject('url')->buildURL(array('event','view',$event)), 'Attendance updated', 'Thanks, your attendance has been updated for that event'); 	
		}
		else
		{
			$this->registry->errorPage('Attendance not logged', 'Sorry, we could not find any record of your attendance for that event, please try again');
		}
	}
	
	private function listUpcomingInNetwork()
	{
                $this->registry->getObject('template')->getPage()->setTitle($this->registry->getSetting('sitename').' | Upcoming Events');
		require_once( FRAMEWORK_PATH . 'models/events.php' );
		$events = new Events( $this->registry );
		$cache = $events->listEventsFuture( $this->registry->getObject('authenticate')->getUser()->getUserID());
		$this->registry->getObject('template')->getPage()->addTag( 'events', array( 'SQL', $cache ) );
		$this->registry->getObject('template')->buildFromTemplates( 'header.tpl.php', 'events/upcoming.tpl.php', 'footer.tpl.php' );
	}
        
        private function listMyEvents()
        {
            	require_once( FRAMEWORK_PATH . 'models/events.php' );
		$events = new Events( $this->registry );
		$cache = $events->listEventsUserFuture( $this->registry->getObject('authenticate')->getUser()->getUserID());
		$this->registry->getObject('template')->getPage()->addTag( 'events', array( 'SQL', $cache ) );
		$this->registry->getObject('template')->buildFromTemplates( 'header.tpl.php', 'events/upcoming.tpl.php', 'footer.tpl.php' );
        }
}
?>