<?php

class Groupcontroller
{
    private $registry;
    
    public function __construct(Registry $registry)
    {
        $this->registry=$registry;
        $urlBits=$this->registry->getObject('url')->getURLBits();
     
	 if($this->registry->getObject('authenticate')->isLoggedIn())
        {
            if(isset($urlBits[1]))
            {
                require_once(FRAMEWORK_PATH.'models/group.php');
                $this->group=new Group($this->registry,intval($urlBits[1]));
                $this->groupID=intval($urlBits[1]);
                if($this->group->isActive() && $this->group->isValid())
                {
                    require_once(FRAMEWORK_PATH.'models/groupmembership.php');
                    $gm=new Groupmembership($this->registry);
                    $user=$this->registry->getObject('authenticate')->getUser()->getUserID();
                    $gm->getByUserAndGroup($user, $this->groupID);
                    
                         if(isset($urlBits[2]))
                        {
                            switch($urlBits[2])
                            {
                                case 'create-topic':
                                    if($user==$this->group->getCreator()||($gm->getApproved())||$this->registry->getObject('authenticate')->getUser()->isAdmin())
                                    $this->createTopic();
                                    break;
                                case 'view-topic':
                                    if($user==$this->group->getCreator()||($gm->getApproved()) || $this->registry->getObject('authenticate')->getUser()->isadmin())
                                    $this->viewTopic(intval($urlBits[3]));
                                    break;
                                case 'reply-to-topic':
                                    if($user==$this->group->getCreator()||($gm->getApproved())||$this->registry->getObject('authenticate')->getUser()->isAdmin())
                                    $this->replyToTopic(intval($urlBits[3]));
                                    break;
                                case 'membership':
                                    $this->manageMembership(intval($urlBits[1]));
                                    break;
                                case 'invite':
                                    if($user==$this->group->getCreator()||($gm->getApproved())||$this->registry->getObject('authenticate')->getUser()->isAdmin())
                                    $this->invite(intval($urlBits[1]));
                                    break;
                                case 'approve':
                                    if($user==$this->group->getCreator()||$gm->getInvited()||$this->registry->getObject('authenticate')->getUser()->isAdmin())
                                    {if(isset($urlBits[3]))$uid=intval($urlBits[3]);
                                    else $uid=0;
                                    $this->approve(intval($urlBits[1]),$uid);}
                                    break;
                                case 'reject':
                                    if($user==$this->group->getCreator()||$gm->getInvited()||$this->registry->getObject('authenticate')->getUser()->isAdmin())
                                    {if(isset($urlBits[3]))$uid=intval($urlBits[3]);
                                    else $uid=0;
                                    $this->reject(intval($urlBits[1]),$uid);}
                                    break;
                                case 'pending':
                                    if($user==$this->group->getCreator()||$this->registry->getObject('authenticate')->getUser()->isAdmin())
                                    $this->showPending(intval($urlBits[1]));
                                    break;
                                case 'request':
                                    if(!($user==$this->group->getCreator())&&!($gm->getApproved())&&!($gm->getApproved())&&!($gm->getApproved()))
                                    $this->request(intval($urlBits[1]));
                                    break;
                                default:
                                    if($user==$this->group->getCreator()||($gm->getApproved()) || $this->registry->getObject('authenticate')->getUser()->isadmin())
                                    $this->viewGroup();
                                    break;
                            }
                        }
                        else
                        {
                            if($user==$this->group->getCreator()||($gm->getApproved())|| $this->registry->getObject('authenticate')->getUser()->isadmin())
                                    $this->viewGroup();
                        }
                    
                }
                else
                {
                    $this->registry->errorPage('Invalid group','The group you requested was not found!');
                }
            }
            else
            {
                 $this->registry->errorPage('Invalid group','The group you requested was not found!');
            }
        }
        else
        {
            $this->registry->errorPage('Please login','You must login to view this group');
        }
    }
    
    private function request($gid)
    {
        $this->registry->getObject('template')->buildFromTemplates();
        $myid=$_POST['mid'];
        require_once(FRAMEWORK_PATH.'models/groupmembership.php');
        $gm=new Groupmembership($this->registry);
        $user=$this->registry->getObject('authenticate')->getUser()->getUserID();
        $gm->getByUserAndGroup($user, $this->groupID);
        if($gm->getApproved() || $gm->getRequested()||$gm->getInvited()||$this->group->getCreator()==$myid)
        {
            echo 'no';
            exit;
        }
        else
        {
            $insert=array();
            $insert['group']=$gid;
            $insert['user']=$myid;
            $insert['requested']=1;
            $insert['requested_date']=date('Y-m-d H-i-s');
            $insert['inviter']=0;
            $this->registry->getObject('db')->insertRecords('group_membership',$insert);
            echo 'yes';
            exit;
        }
    }
    private function invite($gid)
    {
        $myid=$this->registry->getObject('authenticate')->getUser()->getUserID();
        if($this->group->getCreator()==$myid||$this->group->getType()=='Public')
        {
            if(isset($_POST) && count($_POST)>0)
            {
                  if( isset( $_POST['invitees'] ) && is_array( $_POST['invitees'] ) && count( $_POST['invitees'] ) > 0 )
			{// assumes invitees are added to a table using javascript, with a hidden field with name invitees[] for the ID of invitee 
				$is = array();
				foreach( $_POST['invitees'] as $i )
				{
					$is[] = intval( $i );
				}
				$this->group->setInvitees( $is );
			}
         $this->group->save();
        header('Location: '.$this->registry->getSetting('siteurl').'group/'.$gid);
            }
            else
            {
                $this->registry->getObject('template')->getPage()->setTitle($this->registry->getSetting('sitename').' | Invite Connections To Group');
                 $this->registry->getObject('template')->buildFromTemplates('header.tpl.php','groups/invite.tpl.php','footer.tpl.php');
                 $this->registry->getObject('template')->getPage()->addTag('name',$this->group->getName());
                 $this->registry->getObject('template')->getPage()->addTag('group_ID',$this->group->getID());
                 $idsql="select user from group_membership where `group` =".$gid;
                 $id=$this->registry->getObject('authenticate')->getUser()->getUserID();
                 $sql="SELECT u.ID,p.fname,p.lname FROM users u,profile p,relationships r WHERE r.accepted=1 AND
                 (r.usera={$id} OR r.userb={$id}) AND IF(r.usera={$id},u.ID=r.userb,u.ID=r.usera) AND p.ID=u.ID and u.ID not in ($idsql) ORDER by p.fname" ;		
                 $cache=$this->registry->getObject('db')->cacheQuery($sql);
                 $this->registry->getObject('template')->getPage()->addTag('invitees',array('SQL',$cache));
            }
        }
    }
    private function viewGroup()
    {
        $this->registry->getObject('template')->getPage()->setTitle($this->registry->getSetting('sitename').' | View Group');
        $this->group->toTags('group_');
        if($this->group->getCreator()==$this->registry->getObject('authenticate')->getUser()->getUserID()||$this->registry->getObject('authenticate')->getUser()->isAdmin())
            $this->registry->getObject('template')->buildFromTemplates('header.tpl.php', 'groups/view-creator.tpl.php', 'footer.tpl.php' );
        else
        $this->registry->getObject('template')->buildFromTemplates('header.tpl.php', 'groups/view.tpl.php', 'footer.tpl.php' );
        if($this->group->getType()=='Private')
            $this->registry->getObject('template')->getPage()->addTag('invite','');
        else
            $this->registry->getObject('template')->getPage()->addTag('invite','<a href="group/'.$this->group->getID().'/invite"><button>Invite your connections</button></a>');
        $cache = $this->group->getTopics();
        $this->registry->getObject('template')->getPage()->addTag('topics',array('SQL',$cache));
        
        $this->registry->getObject('template')->getPage()->addTag('viewclasstt','<a href="timetable/view/class/'.$this->groupID.'"><button>View Class Time Table</button></a>');
        
        $this->registry->getObject('template')->getPage()->addTag('viewexamtt','<a href="timetable/view/exam/'.$this->groupID.'"><button>View Exam Time Table</button></a><br/><br/>');
        $this->registry->getObject('template')->getPage()->addTag('chat','<a href="chat/'.$this->groupID.'"><button>Chat</button></a>');
       if($this->registry->getObject('authenticate')->getUser()->getType()=='professor')
        {
            $this->registry->getObject('template')->getPage()->addTag('createclasstt','<a href="timetable/create/class/'.$this->groupID.'"><button>Create Class Time Table</button></a>');
            $this->registry->getObject('template')->getPage()->addTag('createexamtt','<a href="timetable/create/exam/'.$this->groupID.'"><button>Create Exam Time Table</button></a><br/><br/>');
            $this->registry->getObject('template')->getPage()->addTag('enterevaluation','<a href="evaluation/enter/'.$this->groupID.'"><button>Enter Evaluation</button></a>');
            $this->registry->getObject('template')->getPage()->addTag('viewevaluation','<a href="evaluation/view/'.$this->groupID.'"><button>View Evaluation</button></a><br/><br/>');
            $this->registry->getObject('template')->getPage()->addTag('allgrade','<a href="evaluation/allgrade/'.$this->groupID.'"><button>View Grades</button></a>');
            $this->registry->getObject('template')->getPage()->addTag('entergrade','<a href="evaluation/grade/'.$this->groupID.'"><button>Enter Grades</button></a><br/><br/>');
        }
        else
        {
            $this->registry->getObject('template')->getPage()->addTag('createclasstt','');
            $this->registry->getObject('template')->getPage()->addTag('createexamtt','');
            $this->registry->getObject('template')->getPage()->addTag('enterevaluation','');
            $this->registry->getObject('template')->getPage()->addTag('viewevaluation','');
            $this->registry->getObject('template')->getPage()->addTag('allgrade','');
            $this->registry->getObject('template')->getPage()->addTag('entergrade','');
        }
    }
    
    private function manageMembership($groupID)
    {
        if($this->group->getCreator()==$this->registry->getObject('authenticate')->getUser()->getUserID())
        {
            require_once(FRAMEWORK_PATH.'controllers/group/membership.php');
            $membership=new Membershipcontroller($this->registry,$groupID);
            $membership->manage();
        }
        else
        {
            $this->registry->errorPage( 'Permission denied', 'Only the group creator can manage membership' );
        }
    }
    
    private function createTopic()
    {
        if(isset($_POST) && is_array($_POST) && count($_POST)>0)
        {
            require_once(FRAMEWORK_PATH.'models/topic.php');
            $topic=new Topic($this->registry);
            $topic->includeFirstPost(true);
            $topic->setName($this->registry->getObject('db')->sanitizeData($_POST['name']));
            $user=$this->registry->getObject('authenticate')->getUser()->getUserID();
            $topic->setCreator($user);
            $topic->setGroup($this->groupID);
            $topic->getFirstPost()->setCreator($user);
            $topic->getFirstPost()->setPost($this->registry->getObject('db')->sanitizeData($_POST['post']));
            $topic->getFirstPost()->setIsFirst(1);
            $topic->save();
            header('Location: '.$this->registry->getSetting('siteurl').'group/'.$this->groupID.'/view-topic/'.$topic->getID());
        }
        else
        {
            $this->registry->getObject('template')->getPage()->setTitle($this->registry->getSetting('sitename').' | Create Topic');
             $this->group->toTags( 'group_' );
      $this->registry->getObject('template')->buildFromTemplates('header.tpl.php', 'groups/create-topic.tpl.php','footer.tpl.php' );
        }            
    }
  
    private function viewTopic($topici)
    {
        $urlBits=$this->registry->getObject('url')->getURLBits();
        if(!isset($urlBits[4]))
        {//Person is viewing the topic
        $this->group->toTags('group_');
        require_once(FRAMEWORK_PATH.'models/topic.php');
        $topic=new Topic($this->registry,$topici);
        if($topic->getGroup()==$this->groupID)
        {
            $this->registry->getObject('template')->getPage()->setTitle($this->registry->getSetting('sitename').' | View Topic');
            $topic->toTags('topic_');
            $cache=$topic->getPostsQuery();
            $this->registry->getObject('template')->getPage()->addTag('posts',array('SQL',$cache));
            $this->registry->getObject('template')->buildFromTemplates('header.tpl.php','groups/view-topic.tpl.php','footer.tpl.php');
        }
        else
        {
            $this->registry->errorPage('Invalid topic','Sorry, you tried to view an invalid topic');
        }
        }
        else
        {//Person is editing/deleting posts
            $postid=intval($urlBits[4]);
            $action=$urlBits[5];
            if($action=='delete')
            {
                require_once(FRAMEWORK_PATH.'models/post.php');
                $post=new Post($this->registry,$postid);
                if($post->getCreator()==$this->registry->getObject('authenticate')->getUser()->getUserID() || $this->registry->getObject('authenticate')->getUser()->isAdmin())
                {
                    if($post->isFirst())
                    {
                        $this->registry->errorPage('Cannot delete this post','Sorry you cannot delete this post');
                    }
                    else
                    {
                        if($post->delete())
                        {
                            header('Location: '.$this->registry->getSetting('siteurl').'group/'.$this->groupID.'/view-topic/'.$topici);
                        }
                        else
                        {
                            $this->registry->errorPage('Error deleting post','An error occured while deleting this post');
                        }
                    }
                }
                else
                {
                    $this->registry->errorPage('Access denied','Sorry, you are not allowed to view this page');
                }
            }
            elseif($action=='edit')
            {
                require_once(FRAMEWORK_PATH.'models/post.php');
                $post=new Post($this->registry,$postid);
                if($post->getCreator()==$this->registry->getObject('authenticate')->getUser()->getUserID() || $this->registry->getObject('authenticate')->getUser()->isAdmin())
                {
                   if(isset($_POST) && count($_POST)>0 && isset($_POST['savecomment']))
                   {
                       $post->setPost($this->registry->getObject('db')->sanitizeData($_POST['post']));
                        $post->save();
                        header('Location: '.$this->registry->getSetting('siteurl').'group/'.$this->groupID.'/view-topic/'.$topici.'#'.$postid);
                   }
                   else
                   {
                       $this->registry->getObject('template')->getPage()->setTitle($this->registry->getSetting('sitename').' | Edit Comment');
                        $this->registry->getObject('template')->buildFromTemplates('header.tpl.php','groups/edit-post.tpl.php','footer.tpl.php');
                        $this->group->toTags('group_');
                        require_once(FRAMEWORK_PATH.'models/topic.php');
                        $topic=new Topic($this->registry,$topici);
                        if($topic->getGroup()==$this->groupID)
                        {
                            $topic->toTags('topic_');
                            $post->toTags('');
                        }
                        else
                        {
                        $this->registry->errorPage('Invalid topic','Sorry, you tried to view an invalid topic');
                        }
                    
                   }
                }
                else
                {
                    $this->registry->errorPage('Access denied','Sorry, you are not allowed to view this page');
                }
            }
        }
     }    
   
            
    private function replyToTopic($topici)
    {
        $this->group->toTags('group_');
        require_once(FRAMEWORK_PATH.'models/topic.php');
        $topic=new Topic($this->registry,$topici);
        if($topic->getGroup()==$this->groupID)
        {
            require_once(FRAMEWORK_PATH.'models/post.php');
            $post=new Post($this->registry,0);
            $post->setPost($this->registry->getObject('db')->sanitizeData($_POST['post']));
            $post->setTopic($topici);
            $post->setCreator($this->registry->getObject('authenticate')->getUser()->getUserID());
            $post->save();
            header('Location: '.$this->registry->getSetting('siteurl').'group/'.$this->groupID.'/view-topic/'.$topici.'#'.$post->getID());
           // $this->registry->redirectUser($this->registry->getObject('url')->buildURL(array('group',$this->groupID,'view-topic',$topici),'',false),'Success!','Your reply has been posted!');
        }
        else
        {
            $this->registry->errorPage('Invalid topic','Sorry, you tried to view an invalid topic');
        }
    }
    
    private function approve($gid,$uid)
    {
        if($uid==0)
        {//The user was invited
            $myid=$this->registry->getObject('authenticate')->getUser()->getUserID();
            $sql="select invited, approved from group_membership where user = ".$myid.' and `group` = '.$gid;
            $this->registry->getObject('db')->executeQuery($sql);
            if($this->registry->getObject('db')->numRows()==1)
            {
                $data=$this->registry->getObject('db')->getRows();
                if($data['invited']==1 && $data['approved']==0)
                {
                    $update=array();
                    $update['approved']=1;
                    $update['join_date']=date('Y-m-d H-i-s');
                    $this->registry->getObject('db')->updateRecords('group_membership',$update,'user = '.$myid.' and `group` = '.$gid);
                    header('Location: '.$this->registry->getSetting('siteurl').'group/'.$gid);
                }
                else
                {
                    $this->registry->errorPage('Access Denied','Sorry, you are not allowed to view this page');
                }
            }
            else
            {
                $this->registry->errorPage('Access Denied','Sorry, you are not allowed to view this page');
            }
        }
        else
        {//The user requested to join the group!
            if($this->group->getCreator()==$this->registry->getObject('authenticate')->getUser()->getUserID())
            {
                $sql="select requested, approved from group_membership where user = ".$uid." and `group` = ".$this->groupID;
                $this->registry->getObject('db')->executeQuery($sql);
                if($this->registry->getObject('db')->numRows()==1)
                {
                    $data=$this->registry->getObject('db')->getRows();
                    if($data['approved']==0 && $data['requested']==1)
                    {
                        $update=array();
                        $update['approved']=1;
                        $update['join_date']=date('Y-m-d H-i-s');
                        $this->registry->getObject('db')->updateRecords('group_membership',$update,'user = '.$uid.' and `group` = '.$gid);
                        header('Location: '.$this->registry->getSetting('siteurl').'group/'.$gid.'/pending');
                    }
                }
                else
                {
                    $this->registry->errorPage('Access Denied','Sorry, you are not allowed to view this page');
                }
            }
            else
            {
                $this->registry->errorPage('Access Denied','Sorry, you are not allowed to view this page');
            }
        }
    }
    
    private function reject($gid,$uid)
    {
        if($uid==0)
        {//The user was invited
            $myid=$this->registry->getObject('authenticate')->getUser()->getUserID();
            $sql="select invited, approved from group_membership where user = ".$myid.' and `group` = '.$gid;
            $this->registry->getObject('db')->executeQuery($sql);
            if($this->registry->getObject('db')->numRows()==1)
            {
                $data=$this->registry->getObject('db')->getRows();
                if($data['invited']==1 && $data['approved']==0)
                {
                    $this->registry->getObject('db')->deleteRecords('group_membership','user = '.$myid.' and `group` = '.$gid);
                    header('Location: '.$this->registry->getSetting('siteurl').'messages');
                }
                else
                {
                    $this->registry->errorPage('Access Denied','Sorry, you are not allowed to view this page');
                }
            }
            else
            {
                $this->registry->errorPage('Access Denied','Sorry, you are not allowed to view this page');
            }
        }
        else
        {//The user requested to join the group!
            if($this->group->getCreator()==$this->registry->getObject('authenticate')->getUser()->getUserID())
            {
                $sql="select requested, approved from group_membership where user = ".$uid;
                $this->registry->getObject('db')->executeQuery($sql);
                if($this->registry->getObject('db')->numRows()==1)
                {
                    $data=$this->registry->getObject('db')->getRows();
                    if($data['approved']==0 && $data['requested']==1)
                    {
                        $this->registry->getObject('db')->deleteRecords('group_membership','user = '.$uid.' and `group` = '.$gid);
                        header('Location: '.$this->registry->getSetting('siteurl').'group/'.$gid.'/pending');
                    }
                }
                else
                {
                    $this->registry->errorPage('Access Denied','Sorry, you are not allowed to view this page');
                }
            }
            else
            {
                $this->registry->errorPage('Access Denied','Sorry, you are not allowed to view this page');
            }
        }
    }
    
    public function showPending($gid)
    {
        $this->registry->getObject('template')->getPage()->setTitle($this->registry->getSetting('sitename').' | Pending Group Request');
        $this->registry->getObject('template')->buildFromTemplates('header.tpl.php','groups/pending.tpl.php','footer.tpl.php');
        $sql="select user from group_membership where `group`={$gid} and requested = 1 and approved = 0";
        $sql1="select p.*, if(p.type='professor', concat('Teaching at ',p.college,' - with ',p.info2,' years of experience in ',p.info1), concat('Studying in ', p.college,' - ',p.info2,' ',p.info1 ))as description from profile p where p.ID in ({$sql})";
        $cache=$this->registry->getObject('db')->cacheQuery($sql1);
        $this->registry->getObject('template')->getPage()->addTag('pendinggrouprequests',array('SQL',$cache));
        $this->registry->getObject('template')->getPage()->addTag('name',$this->group->getName());
        $this->registry->getObject('template')->getPage()->addTag('group_ID',$this->group->getID());
    }
    
}
?>