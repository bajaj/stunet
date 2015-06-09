<?php
/**
 * Events model
 * - builds lists of events
 */
class Events{
	
	/**
	 * Registry object
	 */
	private $registry;
	
	/**
	 * Events constructor
	 * @param Registry $registry
	 * @return void
	 */
	public function __construct( Registry $registry )
	{
		$this->registry = $registry;
	}
	
	/**
	 * List events by connected users in specified month / year
	 * @param int $connectedTo events of users connected to this user
	 * @param int $month
	 * @param int $year
	 * @return int database cacehe
	 */
	public function listEventsMonthYear($connectedTo,$month,$year)
	{
		require_once( FRAMEWORK_PATH . 'models/relationships.php');
		$relationships = new Relationships( $this->registry );
		$idsSQL = $relationships->getIDsByUser( $connectedTo );
		$sql = "SELECT p.fname as creator_fname, p.lname as creator_lname, e.* FROM events e, profile p WHERE p.ID=e.creator AND e.date LIKE '{$year}-{$month}-%' AND e.creator IN ($idsSQL) ";
		$cache = $this->registry->getObject('db')->cacheQuery( $sql );
		return $cache;
	}
	
	/**
	 * List events by connected users in specified time period
	 * @param int $connectedTo events of users connected to this user
	 * @param int $days days in the future
	 * @return int database cacehe
	 */
	public function listEventsFuture($connectedTo)
	{
		require_once( FRAMEWORK_PATH . 'models/relationships.php');
		$relationships = new Relationships( $this->registry );
                $myid=$connectedTo;
		//$idsSQL = $relationships->getIDsByUser($connectedTo);
		//$sql3="SELECT ea.user_id FROM event_attendees ea WHERE ea.event_id=e.ID AND e.type='private'";
		//$sql4="SELECT ea.user_id FROM event_attendees ea WHERE ea.event_id=e.ID AND e.type='public'";
		//Query to get events the person is invited to
                $sqlprivate="select e.ID from events e where e.type='Private' and e.creator={$myid}";
                
                $sqldistinct="select distinct ea.event_id from event_attendees ea where ea.user_id={$myid} and isgroup=0";
        $id=$this->registry->getObject('authenticate')->getUser()->getUserID();
		$sqlfinal="select distinct final.ID, p.fname as creator_fname, p.lname as creator_lname, date_format(final.date,'%D %M %Y') as friendly_date,final.* from events final, profile p where p.ID=final.creator and (final.ID in ($sqlprivate) or final.ID in ($sqldistinct))  and final.date>=CURDATE() order by final.date asc";
		//$sql = "SELECT p.fname as creator_fname, p.lname as creator_lname, e.*,date_format(e.date,'%D %M %Y') as friendly_date FROM events e, profile p WHERE p.ID=e.creator AND e.date >= CURDATE() AND (e.creator IN ($idsSQL) OR e.creator IN({$id})) AND (e.type='public' OR e.type='private')
		  //       AND IF(e.type='private',(e.creator=".$id." OR ".$id." IN(".$sql3.")),e.creator=".$id." OR ".$id." IN(".$sql4."))";
                $cache = $this->registry->getObject('db')->cacheQuery( $sqlfinal );
		return $cache;
	}
	
	/**
	 * List events by a specific user within next X days
	 * @param int $user user whose events to list
	 * @param int $days
	 * @return int database cache
	 */
	public function listEventsUserFuture($user)
	{
		$sql = "SELECT p.fname as creator_fname,p.lname as creator_lname, e.*,date_format(e.date,'%D %M %Y') as friendly_date FROM events e, profile p WHERE p.ID=e.creator AND e.date >= CURDATE() AND e.creator={$user} ";
		$cache = $this->registry->getObject('db')->cacheQuery( $sql );
		return $cache;
	}
	
	/**
	 * List events in the future user is invited to
	 * @param int $user the user 
	 * @return int database cache
	 */
	public function listEventsInvited( $user )
	{
		$sql = "SELECT distinct p.ID, p.fname as creator_fname, p.lname as creator_lname, e.* FROM events e, profile p WHERE p.ID=e.creator AND e.date >= CURDATE() AND ( SELECT COUNT(*) FROM events_attendees a WHERE a.event_id=e.ID AND a.user_id={$user} AND a.status='invited' ) > 0";
		$cache = $this->registry->getObject('db')->cacheQuery( $sql );
		return $cache;
	}
	
	/**
	 * List events in the future user is attending 
	 * @param int $user the user 
	 * @return int database cache
	 */
	public function listEventsAttending( $user )
	{
		$sql = "SELECT distinct p.ID, p.fname as creator_fname, p.lname as creator_lname, e.* FROM events e, profile p WHERE p.ID=e.creator AND e.date >= CURDATE() AND ( SELECT COUNT(*) FROM events_attendees a WHERE a.event_id=e.ID AND a.user_id={$user} AND a.status='attending' ) > 0";
		$cache = $this->registry->getObject('db')->cacheQuery( $sql );
		return $cache;
	}
	
	/**
	 * List events in the future user is not attending
	 * @param int $user the user 
	 * @return int database cache
	 */
	public function listEventsNotAttending( $user )
	{
		$sql = "SELECT distinct p.ID, p.fname as creator_fname, p.lname as creator_lname, e.* FROM events e, profile p WHERE p.ID=e.creator AND e.date >= CURDATE() AND ( SELECT COUNT(*) FROM events_attendees a WHERE a.event_id=e.ID AND a.user_id={$user} AND a.status='not attending' ) > 0";
		$cache = $this->registry->getObject('db')->cacheQuery( $sql );
		return $cache;
	}
	
	/**
	 * List events in the future user is maybe attending
	 * @param int $user the user 
	 * @return int database cache
	 */
	public function listEventsMaybeAttending( $user )
	{
		$sql = "SELECT distinct p.ID, p.name as creator_name, e.* FROM events e, profile p WHERE p.user_id=e.creator AND e.date >= CURDATE() AND ( SELECT COUNT(*) FROM events_attendees a WHERE a.event_id=e.ID AND a.user_id={$user} AND a.status='maybe' ) > 0";
		$cache = $this->registry->getObject('db')->cacheQuery( $sql );
		return $cache;
	}
		
	
	
}



?>