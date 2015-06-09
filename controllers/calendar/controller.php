<?php
/**
 * Calendar controller for events and birthdays
 */
class Calendarcontroller {
	
	public function __construct( $registry, $directCall=true )
	{
		$this->registry = $registry;
		if( $this->registry->getObject('authenticate')->isLoggedIn() )
		{
		   $urlBits = $this->registry->getObject('url')->getURLBits();
		 $this->birthdaysCalendar();
                }
	}
	
	private function birthdaysCalendar()
	{
		// require the class
		require_once( FRAMEWORK_PATH . 'lib/calendar/calendar.class.php' );
		// set the default month and year, i.e. the current month and year
		$m = intval(date('m'));
		$y = intval(date('Y'));
		// check for a different Month / Year (i.e. user has moved to another month)
		if( isset( $_GET['month'] ) )
		{
			$m = intval( $_GET['month']);
			if( $m > 0 && $m < 13 )
			{
			}
			else
			{
				$m = intval(date('m'));
			}
		}
		if( isset( $_GET['year'] ) )
		{
			$y = intval( $_GET['year']);
		}
		// Instantiate the calendar object
		$calendar = new Calendar( '', $m, $y );
		// Get next and previous month / year
		$nm = $calendar->getNextMonth()->getMonth();
		$ny = $calendar->getNextMonth()->getYear();
		$pm = $calendar->getPreviousMonth()->getMonth();
		$py = $calendar->getPreviousMonth()->getYear();
		
		// send next / previous month data to the template		
		$this->registry->getObject('template')->getPage()->addTag('nm', $nm );
		$this->registry->getObject('template')->getPage()->addTag('pm', $pm );
		$this->registry->getObject('template')->getPage()->addTag('ny', $ny );
		$this->registry->getObject('template')->getPage()->addTag('py', $py );
		// send the current month name and year to the template
		$this->registry->getObject('template')->getPage()->addTag('month_name', $calendar->getMonthName() );
		$this->registry->getObject('template')->getPage()->addTag('the_year', $calendar->getYear() );
		// Set the start day of the week
		$calendar->setStartDay(0);
		require_once( FRAMEWORK_PATH . 'models/relationships.php');
		$relationships = new Relationships( $this->registry );
		$idsSQL = $relationships->getIdsByUser( $this->registry->getObject('authenticate')->getUser()->getUserID() );
		$my_id=$this->registry->getObject('authenticate')->getUser()->getUserID();
                $m=str_pad($m,2,'0',STR_PAD_LEFT);
                //get the IDs of the connected users and related necessary info for birhtdays
		$sql = "SELECT DATE_FORMAT(pr.dob, '%d' ) as profile_dob, pr.fname as profile_fname, pr.lname as profile_lname, pr.ID
		as profile_id, ( ( YEAR( CURDATE() ) ) - ( DATE_FORMAT(pr.dob, '%Y' ) ) ) as profile_new_age FROM profile pr WHERE pr.ID IN (".$idsSQL.") AND pr.dob LIKE '%-{$m}-%'";
                $this->registry->getObject('db')->executeQuery( $sql );
		$dates = array();
		$data = array();
	 //echo $this->registry->getObject('db')->numRows();
		if( $this->registry->getObject('db')->numRows() > 0 )
		{
			while( $row = $this->registry->getObject('db')->getRows() )
			{
				$dates[] = $row['profile_dob'];
                                if(isset($data[ intval($row['profile_dob']) ]))
                                $data[ intval($row['profile_dob'])].= "<img src='views/default/images/birthday.png'/> <a href='profile/view/".$row['profile_id']."'>".$row['profile_fname'].' '.$row['profile_lname']."'s birthday! (". $row['profile_new_age'] . ")</a><br />";
                                else
				$data[ intval($row['profile_dob']) ] = "<br /><img src='views/default/images/birthday.png'/> <a href='profile/view/".$row['profile_id']."'>".$row['profile_fname'].' '.$row['profile_lname']."'s birthday! (". $row['profile_new_age'] . ")</a><br />";
			}
		}
		$sql="select distinct event_id from event_attendees ea where user_id={$my_id} and isgroup=0";//Get ids of event the person is invite to
		$sql2="SELECT DATE_FORMAT(e.date,'%d') as event_date,e.name as event_name,e.ID as event_id FROM events e WHERE e.ID in ({$sql}) AND e.date LIKE '{$y}-{$m}-%' 
				AND IF(e.type='private',e.creator=".$my_id.",e.date LIKE '{$y}-{$m}-%')";
		$this->registry->getObject('db')->executeQuery( $sql2);
		if( $this->registry->getObject('db')->numRows() > 0 )
		{
			while( $row = $this->registry->getObject('db')->getRows() )
			{
				$dates[] = $row['event_date'];
                                if(isset($data[intval($row['event_date'])]))
				//if($data[intval($row['event_date'])]!='')
				$data[ intval($row['event_date']) ] .= "<img src='views/default/images/star.png'/> <a href='event/view/".$row['event_id']."'>".$row['event_name']."</a><br />";
				else
				$data[ intval($row['event_date']) ] = "<br /><img src='views/default/images/star.png'/> <a href='event/view/".$row['event_id']."'>".$row['event_name']."</a><br />";
				
			}
		}
		$calendar->setData( $data );
		// tell the calendar which days should be highlighted
		$calendar->setDaysWithEvents($dates);
		$calendar->buildMonth();
		// days
		$this->registry->getObject('template')->dataToTags( $calendar->getDaysInOrder(),'cal_0_day_' ); 
		// dates
		$this->registry->getObject('template')->dataToTags( $calendar->getDates(),'cal_0_dates_' ); 
		// styles
		$this->registry->getObject('template')->dataToTags( $calendar->getDateStyles(),'cal_0_dates_style_' ); 
		// data
		$this->registry->getObject('template')->dataToTags( $calendar->getDateData(),'cal_0_dates_data_' );
		$this->registry->getObject('template')->buildFromTemplates('calendar/bd-calendar.tpl.php');
                
	}
	
	private function generateTestCalendar()
	{
		
		
		// Get how many days there are in the month
		
		
		// build the month, generate some data
		$calendar->buildMonth();
		// days
		$this->registry->getObject('template')->dataToTags( $calendar->getDaysInOrder(),'cal_0_day_' ); 
		// dates
		$this->registry->getObject('template')->dataToTags( $calendar->getDates(),'cal_0_dates_' ); 
		// styles
		$this->registry->getObject('template')->dataToTags( $calendar->getDateStyles(),'cal_0_dates_style_' ); 
		// data
		$this->registry->getObject('template')->dataToTags( $calendar->getDateData(),'cal_0_dates_data_' ); 
		
		$this->registry->getObject('template')->buildFromTemplates( 'test-calendar.tpl.php' );	
	
				
	}

	
	
}


?>