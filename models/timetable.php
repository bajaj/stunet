<?php


class Timetable
{

private $registry;

// group_id
private $group_id;

private $row=array();

private $note;

private $type;  // type of timetable class or exam

public function __construct( Registry $registry, $id=0 )
	{
		$this->registry = $registry;
	}	
	


public function setnote($x)
{
$this->note=$x;
}


public function settype($x)
{
$this->type=$x;
}

public function setrow($x,$row_no)
{
if (strlen(implode('', $x)) == 0)
{
$x['empty']=1;
$x['row_no']=$row_no;
$this->row[]=$x;
}
else
{
$x['empty']=0;
$x['row_no']=$row_no;
$this->row[]=$x;	//if(!empty($x))
}
}


public function seteditrow($x)          // not working
{
foreach($x as $field=>$value)
{
echo $field;
if (strlen(implode('', $value)) == 0)
{
$value['empty']=1;
$this->row[]=$value;
}
else
{
$this->row[]=$value;	//if(!empty($x))

}

}
}

public function setgroup_id($x)
{
$this->group_id=$x;
}

public function save($timetable_id=0)            //$timetable_id is jst a name
{
	if($timetable_id > 0)
	{
	//update
	
	foreach($this->row as $field=>$arr)
		{
		$this->registry->getObject('db')->updateRecords( 'timetable', $arr,'group_id='.$this->group_id.' AND type=\''.$this->type.'\' AND row_no='.$arr['row_no']);		// type is text
		}
		
		//if(!empty($this->note))
		//{
		$note_arr=array();
		
		$note_arr['note']=$this->note;
		
		$this->registry->getObject('db')->updateRecords( 'timetable_note', $note_arr,'group_id='.$this->group_id.' AND type=\''.$this->type.'\'');
		//}
	
	       

	}
	else
	{
		foreach($this->row as $field=>$arr)
		{
		
		$arr['group_id']=$this->group_id;
		$arr['type']=$this->type;
		$this->registry->getObject('db')->insertRecords( 'timetable', $arr);		
		}
		
		//if(!empty($this->note))
		//{
		$note_arr=array();
		$note_arr['group_id']=$this->group_id;
		$note_arr['note']=$this->note;
		$note_arr['type']=$this->type;
		$this->registry->getObject('db')->insertRecords( 'timetable_note', $note_arr);
		//}
		
		
		$x=array();
		
		if($this->isgrp_incheck($this->group_id)) //true then update
		{
		$x[$this->type]=1;
		$this->registry->getObject('db')->updateRecords( 'table_check', $x,'group_id='.$this->group_id);		
		}
		else                             // insert 
		{
		$x['group_id']=$this->group_id;
		$x[$this->type]=1;
		$this->registry->getObject('db')->insertRecords( 'table_check', $x);
		}

	}


}

public function getgroupinfo($grp_id)
{
$sql="SELECT g.ID as group_id,g.name as group_name,g.description as group_description FROM groups g WHERE g.ID=".$grp_id;
$cache = $this->registry->getObject('db')->cacheQuery( $sql );
return $cache;
}

public function gettable($type,$grp_id)
{
$sql="SELECT * FROM timetable t WHERE t.group_id=".$grp_id." AND t.type='".$type."' AND t.empty=0";
$cache = $this->registry->getObject('db')->cacheQuery( $sql );
return $cache;
}

public function getnote($type,$grp_id)
{
$sql="SELECT note FROM timetable_note t WHERE t.group_id=".$grp_id." AND t.type='".$type."'";
$cache = $this->registry->getObject('db')->cacheQuery( $sql );
return $cache;
}


public function isgrp_incheck($grp_id)      
{
$sql="SELECT * FROM table_check t WHERE t.group_id=".$grp_id;

$this->registry->getObject('db')->executeQuery( $sql );

if($this->registry->getObject('db')->numRows() > 0)  // one eaxm or class time table  exists
return true ;
else
return FALSE;

}
public function getedittable($type,$grp_id)
{
$sql="SELECT * FROM timetable t WHERE t.group_id=".$grp_id." AND t.type='".$type."'";
$cache = $this->registry->getObject('db')->cacheQuery( $sql );
return $cache;
}



}

?>
	