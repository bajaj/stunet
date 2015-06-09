<?php


class Evaluation
{

private $registry;

// id of evaluation
private $ID;

// name of evaluation
private $name;

private $description; // about evaluation

// id of teacher who created it
private $creator;

private $out_of_marks;

private $group_id;

// array indexed on user_id and value is the marks obtained

private $marks=array();

private $grade;private $subject;

// any comment indexed on user_id

private $remarks=array();


// array of SR NO

private $sr_no=array();

// array of student name

private $student_name=array();

public function getID()
{
    return $this->ID;
}

public function __construct( Registry $registry, $id=0 )
	{
		$this->registry = $registry;
		$this->ID = $id;
		
		/*
		if( $this->ID > 0 )
		{
		$my_id=$this->registry->getObject('authenticate')->getUser()->getUserID();
		
		// and group id as well
		
		$sql="SELECT e.name as eval_name,e.out_of_marks as out_marks FROM  evaluation_details e WHERE e.ID=".$this->ID;
		
		$this->registry->getObject('db')->executeQuery( $sql );
		
		if( $this->registry->getObject('db')->numRows() > 0 )
			{
				$data = $this->registry->getObject('db')->getRows();
				
				$this->setname($data['eval_name']);
				$this->setOutofmarks($data['out_marks']);
				$this->setdescription($data['description']);
					
			}	
		}
		*/
		
		

	}

public function setname($x)
{
$this->name=$x;
}

public function setDescription($x)
{
$this->description=$x;
}	

public function setgroup_id($x)
{
$this->group_id=$x;
}

public function setOutofmarks($x)
{
$this->out_of_marks=$x;
}

public function setgrade($x)
{
$this->grade=$x;
}	

public function getgrade()
{
return $this->grade;
}	

public function setsubject($x)
{
$this->subject=$x;
}	

public function getsubject()
{
return $this->subject;
}	


public function setremarks($x)
{

foreach($x as $field=>$value)
{
$this->remarks[$field]=$value;
}


}


public function setmarks($x)
{

foreach($x as $field=>$value)
{
$this->marks[$field]=$value;
}


}

private function getteacher_id()
{
return $this->registry->getObject('authenticate')->getUser()->getUserID();

}


public function save($eval_id=0)
{
	if( $eval_id > 0 )
		{
			
		$update=array();
		$update['name']=$this->name;
		$update['out_of_marks']=$this->out_of_marks;
		$update['description']=$this->description;
		$update['date']=date("j M Y");
	//	$update['group_id']=$this->group_id;
	
		
		$this->registry->getObject('db')->updateRecords( 'evaluation_details', $update, 'ID=' . $eval_id );
		
		// to update evalution_result table
		$update2=array();
		foreach($this->marks as $field=>$value)
		{
		$update2['marks']=$value;
		$update2['remarks']=$this->remarks[$field];
		$this->registry->getObject('db')->updateRecords( 'evaluation_results', $update2, 'evaluation_id=' . $eval_id.' AND user_id='.$field );
		}
		
		
		}
	else
	{	
		// update is an "insert' array
			
		$update=array();
		$update['name']=$this->name;
		$update['description']=$this->description;
		$update['out_of_marks']=$this->out_of_marks;
		$update['group_id']=$this->group_id;
	
		$update['creator']=$this->getteacher_id();
		$update['date']=date("j M Y");
		
		$this->registry->getObject('db')->insertRecords( 'evaluation_details',$update);
		$this->ID=$this->registry->getObject('db')->lastInsertID();
		
		$this->insert_results();
		
	}


}

public function grade_save($ID,$teacher_id,$grp_id,$x)
{
if($x==1)   //then update
{

$update=array();
$update['date']=date("j M Y");
// $update['teacher']=$teacher_id;

$update['secured']=$this->grade;
$update['subject']=$this->subject;
$this->registry->getObject('db')->updateRecords( 'grade',$update,'user_id='.$ID);


}
else
{
$update=array();
$update['date']=date("j M Y");
$update['user_id']=$ID;
$update['teacher']=$teacher_id;

$update['secured']=$this->grade;
$update['subject']=$this->subject;
$update['group_id']=$grp_id;
$this->registry->getObject('db')->insertRecords( 'grade',$update);
}


}


public function insert_results()
{

$update2=array();
		foreach($this->marks as $field=>$value)
		{
		$update2['marks']=$value;
		$update2['evaluation_id']=$this->ID;
		$update2['user_id']=$field;   
		$this->sendmessage($field);				// send message
		$update2['remarks']=$this->remarks[$field];
		$this->registry->getObject('db')->insertRecords( 'evaluation_results', $update2);
		}

}

public function sendmessage($id)
  {
  
	require_once( FRAMEWORK_PATH . 'models/message.php' );
			
     $message = new Message( $this->registry, 0 );
	 $subject="Exam Evaluation";
	 
	 $data="Prof ".$this->registry->getObject('authenticate')->getUser()->fname." ".$this->registry->getObject('authenticate')->getUser()->lname;
	 
	
	     $body="<p>".$data." has Entered Your Marks for <a href=\"evaluation/check/\">".$this->name."</a></p>";
  	    
	 
	 
	 $message->setSender( $this->getteacher_id());
     $message->setRecipient( $id);
	 $message->setSubject($subject) ;
	 $message->setMessage($body);
	 $message->set_event_mail(1);
	 $message->save();  
  
  }
 



public function get_edit_evaluation($id)
{
$sql="SELECT r.user_id,r.marks FROM evaluation_results r WHERE r.evaluation_id=".$this->ID;
$cache = $this->registry->getObject('db')->cacheQuery( $sql );
return $cache;		
}

public function getmystudents($grp_id)
{

$sql="SELECT distinct p.ID,p.fname,p.lname,p.roll_no FROM profile p,group_membership gm,groups g WHERE gm.group=".$grp_id." AND g.ID={$grp_id} AND ((gm.user=p.ID AND gm.approved=1) OR g.creator=p.ID)  AND p.type='student' ORDER BY p.roll_no";
$cache = $this->registry->getObject('db')->cacheQuery( $sql );
return $cache;

}

public function getgroupinfo($grp_id)
{
$sql="SELECT g.ID as group_id,g.name as group_name,g.description as group_description FROM groups g WHERE g.ID=".$grp_id;
$cache = $this->registry->getObject('db')->cacheQuery( $sql );
return $cache;
}

public function getevalview($grp_id)
{
$sql="SELECT e.ID as eval_id,e.name,e.description,e.out_of_marks,e.date FROM evaluation_details e WHERE e.creator=".$this->getteacher_id()." AND e.group_id=".$grp_id." ORDER BY e.ID DESC";
$cache = $this->registry->getObject('db')->cacheQuery( $sql );
return $cache;
}

public function getedit($eval_id)
{
$sql="SELECT p.roll_no,p.fname,p.lname,e.user_id,e.marks,e.remarks FROM evaluation_results e,profile p WHERE p.ID=e.user_id AND e.evaluation_id=".$eval_id;

$cache = $this->registry->getObject('db')->cacheQuery( $sql );
return $cache;
}

public function getevalinfo($eval_id)
{

$sql="SELECT e.ID as eval_id,e.date,e.name,e.description,e.out_of_marks FROM evaluation_details e WHERE e.ID=".$eval_id;
$cache = $this->registry->getObject('db')->cacheQuery( $sql );
return $cache;

}

public function getstudent($user_id)
{

$sql="SELECT ed.date,ed.out_of_marks,ed.name as eval_name,ed.description,ed.creator,er.marks,er.remarks,p.ID,p.fname,p.lname FROM profile p,evaluation_details ed,
      evaluation_results er WHERE er.user_id=".$user_id." AND ed.ID=er.evaluation_id AND ed.creator=p.ID ORDER BY er.ID DESC";

$cache = $this->registry->getObject('db')->cacheQuery( $sql );
return $cache;	  


}
public function get_grade_student($user_id)
{

$sql="SELECT g.date,g.subject,g.secured,p.ID,p.fname,p.lname FROM grade g,profile p WHERE g.user_id=".$user_id." AND g.teacher=p.ID";

$cache = $this->registry->getObject('db')->cacheQuery( $sql );
return $cache;	  


}

public function get_teach_student($user_id)
{

$my_id=$this->registry->getObject('authenticate')->getUser()->getUserID();
$sql="SELECT ed.date,ed.out_of_marks,ed.name as eval_name,ed.description,er.marks,er.remarks,p.ID,p.fname,p.lname FROM profile p,evaluation_details ed,
      evaluation_results er WHERE er.user_id=".$user_id." AND ed.ID=er.evaluation_id AND ed.creator=p.ID and ed.creator={$my_id} ORDER BY er.ID DESC";

$cache = $this->registry->getObject('db')->cacheQuery( $sql );
return $cache;	  


}

		

public function getsummary($id)
{
$my_id=$this->registry->getObject('authenticate')->getUser()->getUserID();
$sql="select p.roll_no,p.fname as stu_fname,p.lname as stu_lname,er.user_id,ed.creator as teacher_id,sum(er.marks) as marks_scored,sum(ed.out_of_marks) as total_marks FROM profile p,evaluation_details ed,evaluation_results er
 WHERE p.ID=er.user_id and er.evaluation_id=ed.ID and ed.creator={$my_id} and er.user_id=".$id;
 $cache = $this->registry->getObject('db')->cacheQuery( $sql );
return $cache;

}

public function getpercent($id)
{
$my_id=$this->registry->getObject('authenticate')->getUser()->getUserID();
$sql="select p.roll_no,p.fname as stu_fname,p.lname as stu_lname,er.user_id,ed.creator as teacher_id,sum(er.marks) as marks_scored,sum(ed.out_of_marks) as total_marks FROM profile p,.evaluation_details ed,evaluation_results er
 WHERE p.ID=er.user_id and er.evaluation_id=ed.ID and ed.creator={$my_id} and er.user_id=".$id;
 $this->registry->getObject('db')->executeQuery( $sql );

 $data=$this->registry->getObject('db')->getRows();

 return ($data['marks_scored']/$data['total_marks'])*100;
}

public function teach_grade($teach)
{

$sql="SELECT p.ID,p.fname,p.lname as user_name,p.roll_no,g.grade FROM grade g,profile p,group_membership gm WHERE gm.group=".$grp_id." AND gm.user=p.ID AND p.type='student' OR g.user_id=gm.user ORDER BY p.roll_no";
$cache = $this->registry->getObject('db')->cacheQuery( $sql );
return $cache;


}

public function viewallgrade($grp_id,$teach)
{
$sql="SELECT p.ID,p.fname,p.lname,p.roll_no,g.secured,g.subject FROM grade g,profile p WHERE g.group_id=".$grp_id." AND g.teacher=".$teach." AND p.ID=g.user_id ORDER BY p.roll_no";
$cache = $this->registry->getObject('db')->cacheQuery( $sql );
return $cache;

}


public function get_a_grade($grp_id,$teach,$ID)
{
$sql="SELECT g.secured,g.subject FROM grade g WHERE g.group_id=".$grp_id." AND g.teacher=".$teach." AND g.user_id=".$ID;
$cache = $this->registry->getObject('db')->cacheQuery( $sql );
return $cache;

}



}







?>