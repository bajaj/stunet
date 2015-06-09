<?php


class Evaluationcontroller
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
					case 'enter':
					$this->enter(intval( $urlBits[2] ));   // group_id
					break;
					case 'edit':
					$this->edit(intval( $urlBits[2] ),intval( $urlBits[3] ));
					break;
					case 'view':
					$this->view(intval( $urlBits[2] ));
					break;
					case 'grade':
                                        if(isset($urlBits[3])) $student=intval($urlBits[3]);
                                        else $student=0;
					$this->grade(intval( $urlBits[2] ),$student);
					break;
					case 'allgrade':
					$this->allgrade(intval( $urlBits[2] ));
					break;
					case 'editgrade':
					$this->editgrade(intval( $urlBits[2] ),intval( $urlBits[3] ));
					break;
					default:
					$this->allgrade(intval( $urlBits[2] ));
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
					case 'check':
					$this->check();  
					break;
					default:
					$this->check();
				  } 	
				}
			}
		}
		else
		{
		$this->registry->errorPage( 'Access denied', 'Please login to view this page');
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

private function isstudent($id)
{
$my_id=$this->registry->getObject('authenticate')->getUser()->getUserID();
$sql="SELECT p.ID from profile p where p.ID=".$id;
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

private function eval_valid($eval_id)
{
$my_id=$this->registry->getObject('authenticate')->getUser()->getUserID();
$sql="SELECT e.ID FROM evaluation_details e WHERE e.creator=".$my_id." AND e.ID=".$eval_id;
$this->registry->getObject('db')->executeQuery( $sql );
if($this->registry->getObject('db')->numRows() > 0)
return true ;
else
return false;
}


private function grade($grp_id,$ID)
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
			if($this->isstudent($ID))
				{
			   if( isset( $_POST ) && count( $_POST ) > 0 )
				{
				require_once( FRAMEWORK_PATH . 'models/evaluation.php' );
				$eval = new Evaluation( $this->registry,0);
				$eval->setgrade($this->registry->getObject('db')->sanitizeData( $_POST['grade']));
				$eval->setsubject($this->registry->getObject('db')->sanitizeData( $_POST['subject']));
				$my_id=$this->registry->getObject('authenticate')->getUser()->getUserID();
				$eval->grade_save($ID,$my_id,$grp_id,0);
				// confirm and redirect
				header('Location: '.$this->registry->getSetting('siteurl').'evaluation/allgrade/'.$grp_id.'/');
				}
				else
				{
				   require_once( FRAMEWORK_PATH . 'models/evaluation.php' );
					$eval = new Evaluation( $this->registry,0);
					$this->registry->getObject('template')->buildFromTemplates('header.tpl.php', 'evaluation/grade.tpl.php', 'footer.tpl.php');
					$my_id=$this->registry->getObject('authenticate')->getUser()->getUserID();
				//	$this->registry->getObject('template')->getPage()->addTag('teacher_id',$my_id);
					$percent=$eval->getpercent($ID);
					$this->registry->getObject('template')->getPage()->addTag('group_info',array('SQL',$eval->getgroupinfo($grp_id)));
					$this->registry->getObject('template')->getPage()->addTag('evaluation',array('SQL',$eval->get_teach_student($ID)));
					$this->registry->getObject('template')->getPage()->addTag('grade',array('SQL',$eval->getsummary($ID)));
					$this->registry->getObject('template')->getPage()->addTag('percentage',$percent);
				}
			}
			else
				{
				require_once( FRAMEWORK_PATH . 'models/evaluation.php' );
				$eval = new Evaluation( $this->registry,0);
				$my_id=$this->registry->getObject('authenticate')->getUser()->getUserID();
			    $this->registry->getObject('template')->buildFromTemplates('header.tpl.php', 'evaluation/view_grade.tpl.php', 'footer.tpl.php');
				$this->registry->getObject('template')->getPage()->addTag('group_info',array('SQL',$eval->getgroupinfo($grp_id)));
				$this->registry->getObject('template')->getPage()->addTag('evaluation',array('SQL',$eval->getmystudents($grp_id)));
				}
		}	
	}		
}	

private function editgrade($grp_id,$ID)
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
			if($this->isstudent($ID))
				{
				
			   if( isset( $_POST ) && count( $_POST ) > 0 )
				{
				require_once( FRAMEWORK_PATH . 'models/evaluation.php' );
				$eval = new Evaluation( $this->registry,0);
				$eval->setgrade($this->registry->getObject('db')->sanitizeData( $_POST['grade']));
				$eval->setsubject($this->registry->getObject('db')->sanitizeData( $_POST['subject']));
				$my_id=$this->registry->getObject('authenticate')->getUser()->getUserID();
				$eval->grade_save($ID,$my_id,$grp_id,1);
				// confirm and redirec
				header('Location: '.$this->registry->getSetting('siteurl').'evaluation/allgrade/'.$grp_id.'/');
				}
				else
				{
				   require_once( FRAMEWORK_PATH . 'models/evaluation.php' );
					$eval = new Evaluation( $this->registry,0);
					$this->registry->getObject('template')->buildFromTemplates('header.tpl.php', 'evaluation/update_grade.tpl.php', 'footer.tpl.php');
					$my_id=$this->registry->getObject('authenticate')->getUser()->getUserID();
				//	$this->registry->getObject('template')->getPage()->addTag('teacher_id',$my_id);
					$percent=$eval->getpercent($ID);
					$this->registry->getObject('template')->getPage()->addTag('group_info',array('SQL',$eval->getgroupinfo($grp_id)));
					$this->registry->getObject('template')->getPage()->addTag('evaluation',array('SQL',$eval->get_teach_student($ID)));
					$this->registry->getObject('template')->getPage()->addTag('grade',array('SQL',$eval->getsummary($ID)));
					$this->registry->getObject('template')->getPage()->addTag('percentage',$percent);
					$this->registry->getObject('template')->getPage()->addTag('update_grade',array('SQL',$eval->get_a_grade($grp_id,$my_id,$ID)));
                                }		
			}
			else
				{
				require_once( FRAMEWORK_PATH . 'models/evaluation.php' );
				$eval = new Evaluation( $this->registry,0);
				$my_id=$this->registry->getObject('authenticate')->getUser()->getUserID();
                                $this->registry->getObject('template')->buildFromTemplates('header.tpl.php', 'evaluation/view_grade.tpl.php', 'footer.tpl.php');
				$this->registry->getObject('template')->getPage()->addTag('group_info',array('SQL',$eval->getgroupinfo($grp_id)));
				$this->registry->getObject('template')->getPage()->addTag('evaluation',array('SQL',$eval->getmystudents($grp_id)));
				}
		}	
	}	
}	





private function allgrade($grp_id)
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
				require_once( FRAMEWORK_PATH . 'models/evaluation.php' );
				$eval = new Evaluation( $this->registry,0);
				$this->registry->getObject('template')->buildFromTemplates('header.tpl.php', 'evaluation/viewall.tpl.php', 'footer.tpl.php');
                $my_id=$this->registry->getObject('authenticate')->getUser()->getUserID();
				$this->registry->getObject('template')->getPage()->addTag('group_info',array('SQL',$eval->getgroupinfo($grp_id)));
				$this->registry->getObject('template')->getPage()->addTag('grade',array('SQL',$eval->viewallgrade($grp_id,$my_id)));
		}
	}
}

private function enter($grp_id)
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
			require_once( FRAMEWORK_PATH . 'models/evaluation.php' );
			$eval = new Evaluation( $this->registry,0);
			$eval->setname($this->registry->getObject('db')->sanitizeData( $_POST['name']));
			$eval->setdescription($this->registry->getObject('db')->sanitizeData( $_POST['description']));
			$eval->setOutofmarks($this->registry->getObject('db')->sanitizeData( $_POST['out_of_marks']));
			$eval->setgroup_id($grp_id);
			$eval->setmarks($_POST['marks']);
			$eval->setremarks($_POST['remarks']);
			$eval->save(0);
		// send email for evaluation details write code in save
			$url = $this->registry->getObject('url')->buildURL( array('evaluation/view/'.$grp_id), '', false );
                        header('Location: '.$this->registry->getSetting('siteurl').'evaluation/view/'.$grp_id);
//$this->registry->redirectUser( $url, 'Evaluation Successfull', 'You have successfully entered the marks of your student');
			}
			else
			{
				 require_once( FRAMEWORK_PATH . 'models/evaluation.php' );
				$eval = new Evaluation( $this->registry,0);
				$this->registry->getObject('template')->buildFromTemplates('header.tpl.php', 'evaluation/enter.tpl.php', 'footer.tpl.php');
				$this->registry->getObject('template')->getPage()->addTag('group_info',array('SQL',$eval->getgroupinfo($grp_id)));
				$this->registry->getObject('template')->getPage()->addTag('evaluation',array('SQL',$eval->getmystudents($grp_id)));
			}
		}	
		else
		{
		  $this->registry->errorPage( 'Access Denied', 'Only members of this group who are teachers can add an evaluation');
		}
	}
}


private function edit($grp_id,$eval_id)
{
	if(!($this->group_valid($grp_id)))
	{
	$this->registry->errorPage( 'Invalid group', 'The specified group does not exist');	
	}
	else
	{
		if(!($this->eval_valid($eval_id)))
		{
		$this->registry->errorPage( 'No such Evaluation ', 'The specified evaluation does not exist');		
		}
		else
		{	
                        require_once(FRAMEWORK_PATH.'models/group.php');
                        $group=new Group($this->registry,$grp_id);
			if(!($this->isteacher_group($grp_id)|| $this->registry->getObject('authenticate')->getUser()->getUserID()==$group->getCreator()))
			{
			$this->registry->errorPage( 'Access Denied', 'Only members of this group who are teachers can edit this evaluation');
			}
			else
			{
				require_once( FRAMEWORK_PATH . 'models/evaluation.php' );
				$eval = new Evaluation( $this->registry,0);
				if( isset( $_POST ) && count( $_POST ) > 0 )  // then teacher have submitted the form
					{
					$eval->setname($this->registry->getObject('db')->sanitizeData( $_POST['name']));
					$eval->setdescription($this->registry->getObject('db')->sanitizeData( $_POST['description']));
					$eval->setOutofmarks($this->registry->getObject('db')->sanitizeData( $_POST['out_of_marks']));
				//	$eval->setgroup_id($grp_id);
					$eval->setmarks($_POST['marks']);
					$eval->setremarks($_POST['remarks']);
					$eval->save($eval_id); // indicating it is update
				// send email for evaluation details write code in save
					$url = $this->registry->getObject('url')->buildURL( array('evaluation/view/'.$grp_id), '', false );
					header('Location: '.$this->registry->getSetting('siteurl').'evaluation/view/'.$grp_id);
				}
				else
				{
				require_once( FRAMEWORK_PATH . 'models/evaluation.php' );
					$eval = new Evaluation( $this->registry,0);
					$this->registry->getObject('template')->buildFromTemplates('header.tpl.php', 'evaluation/edit.tpl.php', 'footer.tpl.php');
					$this->registry->getObject('template')->getPage()->addTag('group_info',array('SQL',$eval->getgroupinfo($grp_id)));
					$this->registry->getObject('template')->getPage()->addTag('eval_info',array('SQL',$eval->getevalinfo($eval_id)));
					$this->registry->getObject('template')->getPage()->addTag('evaluation',array('SQL',$eval->getedit($eval_id)));
				}
			}
		}
	}		
}


private function view($grp_id)
{
 require_once( FRAMEWORK_PATH . 'models/evaluation.php' );
 $eval = new Evaluation( $this->registry,0);	
$this->registry->getObject('template')->buildFromTemplates('header.tpl.php', 'evaluation/view.tpl.php', 'footer.tpl.php');
$this->registry->getObject('template')->getPage()->addTag('group_info',array('SQL',$eval->getgroupinfo($grp_id)));
$this->registry->getObject('template')->getPage()->addTag('evaluation',array('SQL',$eval->getevalview($grp_id)));
}

private function check()  //for student to check their evaluation
{
    $this->registry->getObject('template')->getPage()->setTitle($this->registry->getSetting('sitename').' | Check your evaluation');
require_once( FRAMEWORK_PATH . 'models/evaluation.php' );
 $eval = new Evaluation( $this->registry,0);
 $my_id=$this->registry->getObject('authenticate')->getUser()->getUserID();
 $this->registry->getObject('template')->buildFromTemplates('header.tpl.php', 'evaluation/student.tpl.php', 'footer.tpl.php');
 $this->registry->getObject('template')->getPage()->addTag('evaluation',array('SQL',$eval->getstudent($my_id))); 
 $this->registry->getObject('template')->getPage()->addTag('grade',array('SQL',$eval->get_grade_student($my_id)));
}
}
?>