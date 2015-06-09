<?php

/**
 * Messages model
 */
class Messages {
	
	/**
	 * Messages constructor
	 * @param Registry $registry
	 * @return void
	 */
	public function __construct( Registry $registry )
	{
		$this->registry = $registry;
	}
	
	/**
	 * Get a users inbox
	 * @param int $user the user
	 * @return int the cache of messages
	 */
	public function getInbox( $user )
	{
		$sql = "select if(m.read=0,'unread','read') as read_style, m.subject, m.ID, m.sender, m.recipient, date_format(m.sent,'%l:%i %p, %D %b %Y') as sent_friendly, psender.fname as sender_fname, psender.lname as sender_lname 
		         from messages m, profile psender where psender.ID=m.sender and m.recipient=" . $user . " and m.recipient_delete=0 and m.draft=0 and m.`group`=0
		          order by m.ID desc";
				//echo $sql;  
		$cache = $this->registry->getObject('db')->cacheQuery( $sql );
		return $cache;
		
	}
	public function getsent( $user )
	{
		$sql = "select if(m.read=0,'unread','read') as read_style, m.subject, m.ID, m.sender, m.recipient, date_format(m.sent,'%l:%i %p, %D %b %Y') as sent_friendly, 
		        precipient.fname as recipient_fname, precipient.lname as recipient_lname from messages m, profile precipient where precipient.ID=m.recipient and m.sender=" . $user . " 
				and m.sender_delete=0 and m.draft=0 and m.event_mail=0 and m.group=0 order by m.ID desc";
		$cache = $this->registry->getObject('db')->cacheQuery( $sql );
		return $cache;
		
	}
	
	public function getsent_grp($user)
	{
	$sql = "select if(m.read=0,'unread','read') as read_style, m.subject, m.ID, m.sender, m.recipient, date_format(m.sent,'%l:%i %p, %D %b %Y') as sent_friendly, 
		        g.name as group_name from messages m,groups g WHERE g.ID=m.recipient and m.sender=" . $user . " 
				and m.sender_delete=0 and m.draft=0 and m.event_mail=0 and m.group=1 order by m.ID desc";
		$cache = $this->registry->getObject('db')->cacheQuery( $sql );
		return $cache;
	
	
	}
	
	public function getdraft_grp($user)
	{
	$sql = "select if(m.read=0,'unread','read') as read_style, m.subject, m.ID, m.sender, m.recipient, date_format(m.sent,'%l:%i %p, %D %b %Y') as sent_friendly, 
		        g.name as group_name from messages m,groups g WHERE g.ID=m.recipient and m.sender=" . $user . " 
				and m.sender_delete=0 and m.draft=1 and m.event_mail=0 and m.group=1 order by m.ID desc";
		$cache = $this->registry->getObject('db')->cacheQuery( $sql );
		return $cache;
	
	
	}
	
	
	
	public function getDraft($user)
	{
	$sql = "select if(m.read=0,'unread','read') as read_style, m.subject, m.ID, m.sender, m.recipient, date_format(m.sent,'%l:%i %p, %D %b %Y') as sent_friendly, 
		        precipient.fname as recipient_fname, precipient.lname as recipient_lname  from messages m, profile precipient where precipient.ID=m.recipient and m.sender=" . $user . " 
				and m.sender_delete=0 and m.draft=1 and m.group=0 order by m.ID desc";
		$cache = $this->registry->getObject('db')->cacheQuery( $sql );
		return $cache;
	
	}
	public function getemptyDraft($user)
	{
	$sql = "select if(m.read=0,'unread','read') as read_style, m.subject, m.ID, m.sender, m.recipient, date_format(m.sent,'%l:%i %p, %D %b %Y') as sent_friendly 
		         from messages m where m.sender=" . $user . " 
				and m.sender_delete=0 and m.draft=1 and m.group=0 and m.recipient=0 order by m.ID desc";
		$cache = $this->registry->getObject('db')->cacheQuery( $sql );
		return $cache;
	
	}
	
	
	
}
?>