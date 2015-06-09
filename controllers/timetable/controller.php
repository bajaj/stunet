<?php


class Timetablecontroller
{


public function __construct( Registry $registry, $directCall=true )
	{
		$this->registry = $registry;
		if( $this->registry->getObject('authenticate')->isLoggedIn() )
		{
			if($this->isteacher())
			{
				$urlBits = $this->registry->getObject('url')->getURLBits();
				if( isset( $urlBits[1] ) )
			   {
				  switch( $urlBits[1] )
				  {
					case 'create':
					$this->create($urlBits[2],intval( $urlBits[3] ));   // group_id 
					break;
					case 'edit':
					$this->edit($urlBits[2],intval( $urlBits[3] ));
					break;
					case 'view':
					$this->view($urlBits[2],intval( $urlBits[3] ));
					break;
					default:
					$this->view($urlBits[2],intval( $urlBits[3] ));
				  }	
			    }
			}
			else
			{
			// student part
			$urlBits = $this->registry->getObject('url')->getURLBits();
			  if( isset( $urlBits[1] ) )
			   {
				  switch( $urlBits[1] )
				  {
					case 'view':
					$this->view($urlBits[2],intval( $urlBits[3] ));  
					break;
					default:
					$this->view($urlBits[2],intval( $urlBits[3] ));
				  } 	
				}
			}
		}
		else
		{
		$this->registry->errorPage( 'Not logged in', 'Please login to view this page');
		}
	}
	

private function create($type,$grp_id)
{
	if(!($this->group_valid($grp_id)))
	{
	$this->registry->errorPage( 'Invalid group', 'The specified group does not exist');	
	}
	else
	{
            require_once(FRAMEWORK_PATH.'models/group.php');
                $group=new Group($this->registry,$grp_id);
     	if($this->isteacher_group($grp_id)||($this->registry->getObject('authenticate')->getUser()->getUserID()==$group->getCreator()))
		{
			if( isset( $_POST ) && count( $_POST ) > 0 )
			{
			require_once( FRAMEWORK_PATH . 'models/timetable.php' );
			$time = new Timetable( $this->registry,0);
			$time->setgroup_id($grp_id);
			$time->setrow($_POST['row1'],1);$time->setrow($_POST['row2'],2);$time->setrow($_POST['row3'],3);
			$time->setrow($_POST['row4'],4);$time->setrow($_POST['row5'],5);$time->setrow($_POST['row6'],6);
			$time->setrow($_POST['row7'],7);$time->setrow($_POST['row8'],8);$time->setrow($_POST['row9'],9);
			$time->setrow($_POST['row10'],10);
			$time->setnote($_POST['note']);
			$time->settype($type);
			$time->save(intval(0));
			header('Location: '.$this->registry->getSetting('siteurl').'timetable/view/'.$type.'/'.$grp_id);
			}
			else
			{
				if($this->table_check($type,$grp_id))  // table does not exists	if true then create
				{
                                    $this->registry->getObject('template')->getPage()->setTitle($this->registry->getSetting('sitename').' | Create '.$type.' Time Table'); 
                                    require_once( FRAMEWORK_PATH . 'models/timetable.php' );
                                    $time = new Timetable( $this->registry,0);
                                    $this->registry->getObject('template')->buildFromTemplates('header.tpl.php', 'timetable/create.tpl.php', 'footer.tpl.php');
                                    $this->registry->getObject('template')->getPage()->addTag('group_info',array('SQL',$time->getgroupinfo($grp_id)));
                                     $this->registry->getObject('template')->getPage()->addTag('typecaps',ucwords($type));
                         $this->registry->getObject('template')->getPage()->addTag('type',$type);
			 
                                    //$this->registry->getObject('template')->getPage()->addTag('evaluation',array('SQL',$eval->getmystudents($grp_id)));
				}
				else
				{
				$this->registry->redirectUser($this->registry->getSetting('siteurl').'timetable/view/'.$type.'/'.$grp_id,ucwords($type).' time table already exists for this group','');
				}
			}
		}	
		else
		{
		 $this->registry->errorPage( 'Access Denied', 'Only members of this group can see the timetable');
		}
	}

}

private function view($type,$grp_id)
{
   if(!($this->group_valid($grp_id)))
	{
	$this->registry->errorPage( 'Invalid group', 'The specified group does not exist');	
	}
	else
	{
                $this->registry->getObject('template')->getPage()->setTitle($this->registry->getSetting('sitename').' | View '.ucwords($type).' Time Table'); 
                require_once(FRAMEWORK_PATH.'models/group.php');
                $group=new Group($this->registry,$grp_id);
		if($this->isteacher_group($grp_id) || ($this->registry->getObject('authenticate')->getUser()->getUserID()==$group->getCreator())) //  teacher or student belongs to group
		{
			if($type=='class' OR $type=='exam')
			{
		
				if($this->table_check($type,$grp_id))     // check if that timetable exists
				{
                                    require_once( FRAMEWORK_PATH . 'models/timetable.php' );
					$time = new Timetable( $this->registry,0);
                                $this->registry->getObject('template')->buildFromTemplates('header.tpl.php','timetable/no-class-tt.tpl.php','footer.tpl.php');
                         $this->registry->getObject('template')->getPage()->addTag('group_info',array('SQL',$time->getgroupinfo($grp_id)));
			 $this->registry->getObject('template')->getPage()->addTag('typecaps',ucwords($type));
                         
                         }
				else
				{
				require_once( FRAMEWORK_PATH . 'models/timetable.php' );
					$time = new Timetable( $this->registry,0);
		
				if($this->isteacher())
				{
				$this->registry->getObject('template')->buildFromTemplates('header.tpl.php', 'timetable/view_teacher.tpl.php', 'footer.tpl.php');
				}
				else
				{
				$this->registry->getObject('template')->buildFromTemplates('header.tpl.php', 'timetable/view.tpl.php', 'footer.tpl.php');
				}	
			 $this->registry->getObject('template')->getPage()->addTag('group_info',array('SQL',$time->getgroupinfo($grp_id)));
			 $this->registry->getObject('template')->getPage()->addTag('typecaps',ucwords($type));
                         $this->registry->getObject('template')->getPage()->addTag('type',$type);
			  $this->registry->getObject('template')->getPage()->addTag('time',array('SQL',$time->gettable($type,$grp_id)));
			  $this->registry->getObject('template')->getPage()->addTag('note',array('SQL',$time->getnote($type,$grp_id)));
			  }
			}
			else
			{
			$this->registry->getObject('template')->buildFromTemplates('header.tpl.php','timetable/no-class-tt.tpl.php','footer.tpl.php');
                         $this->registry->getObject('template')->getPage()->addTag('group_info',array('SQL',$time->getgroupinfo($grp_id)));
			 $this->registry->getObject('template')->getPage()->addTag('typecaps',ucwords($type));
			}
			
		}
		else
		{
		$this->registry->errorPage( 'Access Denied', 'Only members of this group can see the timetable');	
		}
	}
}	

private function edit($type,$grp_id)
{
   if(!($this->group_valid($grp_id)))
	{
	$this->registry->errorPage( 'Invalid group', 'The specified group does not exist');	
	}
	else
	{
                $this->registry->getObject('template')->getPage()->setTitle($this->registry->getSetting('sitename').' | Edit '.ucwords($type).' Time Table'); 
                require_once(FRAMEWORK_PATH.'models/group.php');
                $group=new Group($this->registry,$grp_id);
		if($this->isteacher_group($grp_id) || ($this->registry->getObject('authenticate')->getUser()->getUserID()==$group->getCreator()) ) //  teacher or student belongs to group
		{
			if($type=='class' OR $type=='exam')
			{
		
				if($this->table_check($type,$grp_id))     // check if that timetable exists
				{
                                     require_once( FRAMEWORK_PATH . 'models/timetable.php' );
					$time = new Timetable( $this->registry,0);
				$this->registry->getObject('template')->buildFromTemplates('header.tpl.php','timetable/no-class-tt.tpl.php','footer.tpl.php');
                         $this->registry->getObject('template')->getPage()->addTag('group_info',array('SQL',$time->getgroupinfo($grp_id)));
			 $this->registry->getObject('template')->getPage()->addTag('typecaps',ucwords($type));
				}
				else
				{
				require_once( FRAMEWORK_PATH . 'models/timetable.php' );
					$time = new Timetable( $this->registry,0);
		
					if($this->isteacher())         // edit it
					{	
						if( isset( $_POST ) && count( $_POST ) > 0 )   // update timetable
						{
							$time->setgroup_id($grp_id);
							$time->setrow($_POST['row1'],1);$time->setrow($_POST['row2'],2);$time->setrow($_POST['row3'],3);
                                                        $time->setrow($_POST['row4'],4);$time->setrow($_POST['row5'],5);$time->setrow($_POST['row6'],6);
                                                        $time->setrow($_POST['row7'],7);$time->setrow($_POST['row8'],8);$time->setrow($_POST['row9'],9);
                                                        $time->setrow($_POST['row10'],10);	
                                                        $time->setnote($_POST['note']);
                                                        $time->settype($type);
							$time->save(intval(1));   
                                                        header('Location: '.$this->registry->getSetting('siteurl').'timetable/view/'.$type.'/'.$grp_id);
						}
						else                                            // edit timttable      
						{
					
						$this->registry->getObject('template')->buildFromTemplates('header.tpl.php', 'timetable/edit.tpl.php', 'footer.tpl.php');
						$this->registry->getObject('template')->getPage()->addTag('group_info',array('SQL',$time->getgroupinfo($grp_id)));
						$this->registry->getObject('template')->getPage()->addTag('typecaps',ucwords($type));
                                                $this->registry->getObject('template')->getPage()->addTag('type',$type);
			 
						$this->registry->getObject('template')->getPage()->addTag('time',array('SQL',$time->getedittable($type,$grp_id)));
						$this->registry->getObject('template')->getPage()->addTag('note',array('SQL',$time->getnote($type,$grp_id)));
						}
					}
					else
					{
                                            $this->registry->errorPage( 'Acess Denied ', 'Only Teachers can edit the timetable');
					}
				}
			}
			else
			{
			$this->registry->getObject('template')->buildFromTemplates('header.tpl.php','timetable/no-class-tt.tpl.php','footer.tpl.php');
                         $this->registry->getObject('template')->getPage()->addTag('group_info',array('SQL',$time->getgroupinfo($grp_id)));
			 $this->registry->getObject('template')->getPage()->addTag('typecaps',ucwords($type));
			}	
		}
		
		else
			{
			$this->registry->errorPage( 'Access Denied ', 'You are not member of this group');
			}	
		
		
	}
	
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

private function isteacher_group($grp_id)
{
$my_id=$this->registry->getObject('authenticate')->getUser()->getUserID();

$sql="SELECT gm.group FROM group_membership gm WHERE gm.user=".$my_id." AND gm.group=".$grp_id;
$this->registry->getObject('db')->executeQuery( $sql );

if($this->registry->getObject('db')->numRows() > 0)
return true ;
else
return false;

}

private function group_valid($grp_id)
{
$sql="SELECT g.ID FROM groups g WHERE g.ID=".$grp_id;
$this->registry->getObject('db')->executeQuery( $sql );

if($this->registry->getObject('db')->numRows() > 0)
return true ;
else
return false;
}


private function table_check($type,$grp_id)
{
$sql="SELECT * FROM table_check t WHERE t.group_id=".$grp_id." AND t.".$type."=1";

$this->registry->getObject('db')->executeQuery( $sql );

if($this->registry->getObject('db')->numRows() > 0)  // time table already exists
return false ;
else
return true;

}


}
	
?>	