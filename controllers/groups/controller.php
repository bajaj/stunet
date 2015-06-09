<?php

class Groupscontroller
{
  private $registry;
  
  public function __construct(Registry $registry)
  {
      $this->registry=$registry;
      $urlBits=$this->registry->getObject('url')->getURLBits();
      if(isset($urlBits[1]) && $this->registry->getObject('authenticate')->isLoggedIn())
      {
          switch($urlBits[1])
          {
          case 'create':
              $this->createGroup();
              break;
          case 'edit':
              $this->editGroup(intval($urlBits[2]));
              break;
          case 'my-groups':
              $this->listMyGroups();
              break;
          case 'my-created-groups':
              $this->listMyCreatedGroups();
              break;
           case 'search':
              $this->searchGroups(false,'',0);
              break;
           case 'search-results':
              $this->searchGroups(true,'',intval(isset($urlBits[2])?$urlBits[2]:0));
              break;
          default: 
		  if($this->registry->getObject('authenticate')->getUser()->isAdmin())
		  {$this->listallgroups();}
		  else
              {$this->listMyGroups();}
          }
      }
 else {
          if($this->registry->getObject('authenticate')->getUser()->isAdmin())
		  {$this->listallgroups();}
		  else
              {$this->listMyGroups();}
      }
      
 }
 
 private function  createGroup()
 {
     if(isset($_POST) && count($_POST)>0)
     {
         require_once(FRAMEWORK_PATH.'models/group.php');
         $group=new Group($this->registry,0);
         $group->setName($this->registry->getObject('db')->sanitizeData($_POST['name']));
         $group->setDescription($this->registry->getObject('db')->sanitizeData($_POST['description']));
         $group->setType($this->registry->getObject('db')->sanitizeData($_POST['type']));
         $group->setCreator($this->registry->getObject('authenticate')->getUser()->getUserID());
         $group->setCollege($this->registry->getObject('db')->sanitizeData($_POST['college']));
         if( isset( $_POST['invitees'] ) && is_array( $_POST['invitees'] ) && count( $_POST['invitees'] ) > 0 )
			{// assumes invitees are added to a table using javascript, with a hidden field with name invitees[] for the ID of invitee 
				$is = array();
				foreach( $_POST['invitees'] as $i )
				{
					$is[] = intval( $i );
				}
				$group->setInvitees( $is );
			}
         $group->save();
          mkdir(FRAMEWORK_PATH.'uploads/files/'.$group->getName());//Create directory for personal document manager for group
        header('Location: '.$this->registry->getSetting('siteurl').'group/'.$group->getID());
     }
     else
     {
         $this->registry->getObject('template')->getPage()->setTitle($this->registry->getSetting('sitename').' | Create A Group');
         $this->registry->getObject('template')->buildFromTemplates('header.tpl.php','groups/create.tpl.php','footer.tpl.php');
         require_once( FRAMEWORK_PATH . 'models/event.php' );
         $id=$this->registry->getObject('authenticate')->getUser()->getUserID();
	 $sql="SELECT u.ID,p.fname,p.lname FROM users u,profile p,relationships r WHERE r.accepted=1 AND
	 (r.usera={$id} OR r.userb={$id}) AND IF(r.usera={$id},u.ID=r.userb,u.ID=r.usera) AND p.ID=u.ID ORDER by p.fname" ;		
	 $cache=$this->registry->getObject('db')->cacheQuery($sql);
	 $this->registry->getObject('template')->getPage()->addTag('invitees',array('SQL',$cache));
	  }
 }
 
 private function editGroup($gid)
 {
     require_once(FRAMEWORK_PATH.'models/group.php');
     $group=new Group($this->registry,$gid);
     if($group->getCreator()==$this->registry->getObject('authenticate')->getUser()->getUserID())
     {
      if(isset($_POST) && count($_POST)>0)
     {
         
         $group->setName($this->registry->getObject('db')->sanitizeData($_POST['name']));
         $group->setDescription($this->registry->getObject('db')->sanitizeData($_POST['description']));
         $group->setType($this->registry->getObject('db')->sanitizeData($_POST['type']));
         $group->setCollege($this->registry->getObject('db')->sanitizeData($_POST['college']));
         if( isset( $_POST['invitees'] ) && is_array( $_POST['invitees'] ) && count( $_POST['invitees'] ) > 0 )
			{// assumes invitees are added to a table using javascript, with a hidden field with name invitees[] for the ID of invitee 
				$is = array();
				foreach( $_POST['invitees'] as $i )
				{
					$is[] = intval( $i );
				}
				$group->setInvitees( $is );
			}
         $group->save();
        header('Location: '.$this->registry->getSetting('siteurl').'group/'.$gid);
     }
     else
     {
         $this->registry->getObject('template')->getPage()->setTitle($this->registry->getSetting('sitename').' | Edit Group');
         $this->registry->getObject('template')->buildFromTemplates('header.tpl.php','groups/edit.tpl.php','footer.tpl.php');
         $this->registry->getObject('template')->getPage()->addTag('group_ID',$group->getID());
         $this->registry->getObject('template')->getPage()->addTag('name',$group->getName());
         $this->registry->getObject('template')->getPage()->addTag('description',$group->getDescription());
         $this->registry->getObject('template')->getPage()->addTag('college',$group->getCollege());
         $script="";
         $type=($group->getType()=='Public')?0:1;
         $script="";
         $script.="<script type='text/javascript'>";
         $script.="document.getElementById('type').selectedIndex=".$type.';';
         $script.="</script>";
         $this->registry->getObject('template')->getPage()->addTag('script',$script);
         $idsql="select user from group_membership where `group` =".$gid;
         $id=$this->registry->getObject('authenticate')->getUser()->getUserID();
	 $sql="SELECT u.ID,p.fname,p.lname FROM users u,profile p,relationships r WHERE r.accepted=1 AND
	 (r.usera={$id} OR r.userb={$id}) AND IF(r.usera={$id},u.ID=r.userb,u.ID=r.usera) AND p.ID=u.ID and u.ID not in ($idsql) ORDER by p.fname" ;		
	 $cache=$this->registry->getObject('db')->cacheQuery($sql);
	 $this->registry->getObject('template')->getPage()->addTag('invitees',array('SQL',$cache));
	  }
     }
     else
     {
         $this->registry->errorPage('Access denied','Sorry, you are not allowed to view this page');
     }
 }
 
 
    
    private function listMyGroups()
    {
     
        $this->registry->getObject('template')->getPage()->setTitle($this->registry->getSetting('sitename').' | Your Groups');
        $user=$this->registry->getObject('authenticate')->getUser()->getUserID();
        $sql="select g_default from profile where ID = ".$user;
        $this->registry->getObject('db')->executeQuery($sql);
        $default=0;
        if($this->registry->getObject('db')->numRows()==1)
        {
            $data=$this->registry->getObject('db')->getRows();
            $default=$data['g_default'];
        }
        $sql="select *,if(ID={$default},'<button disabled=\"disabled\">Your Default Group</button>',concat('<button onclick=\"makedefault\(',ID,',',{$user},'\)\" id=\"makedefault',ID,'\">Make Default</button>')) as button, if(CAST(created AS DATE)=current_date,CONCAT('Today ', date_format(created,'%h:%i %p')),if(CAST(created AS DATE)=current_date-1,CONCAT('Yesterday ', date_format(created,'%h:%i %p')),concat(date_format(created,'%d-%m-%Y'),' ',date_format(created,'%h:%i %p')))) as createdFriendly from groups where creator={$user} or ID in 
        (select m.group from group_membership m where m.user={$user} and m.approved=1)";
        $cache=$this->registry->getObject('db')->cacheQuery($sql);
        $this->registry->getObject('template')->getPage()->addTag('my-groups',array('SQL',$cache));
        $this->registry->getObject('template')->buildFromTemplates('header.tpl.php','groups/mine.tpl.php','footer.tpl.php');
    }
    
    private function listMyCreatedGroups()
    {
        $this->registry->getObject('template')->getPage()->setTitle($this->registry->getSetting('sitename').' | Your Created Groups');
        $user=$this->registry->getObject('authenticate')->getUser()->getUserID();
        $sql="select g_default from profile where ID = ".$user;
        $this->registry->getObject('db')->executeQuery($sql);
        $default=0;
        if($this->registry->getObject('db')->numRows()==1)
        {
            $data=$this->registry->getObject('db')->getRows();
            $default=$data['g_default'];
        }
        $sql="select *,if(ID={$default},'<button disabled=\"disabled\">Your Default Group</button>',concat('<button onclick=\"makedefault\(',ID,',',{$user},'\)\" id=\"makedefault',ID,'\">Make Default</button>')) as button, if(CAST(created AS DATE)=current_date, CONCAT('Today ', date_format(created,'%h:%i %p')),if(CAST(created AS DATE)=current_date-1,CONCAT('Yesterday ', date_format(created,'%h:%i %p')),concat(date_format(created,'%d-%m-%Y'),' ',date_format(created,'%h:%i %p')))) as createdFriendly from groups where creator={$user}";
        $cache=$this->registry->getObject('db')->cacheQuery($sql);
        $this->registry->getObject('template')->getPage()->addTag('my-created-groups',array('SQL',$cache));
        $this->registry->getObject('template')->buildFromTemplates('header.tpl.php','groups/mine-created.tpl.php','footer.tpl.php');
    }
    
 private function searchGroups($search=true,$name='',$offset=0)
    {
        $this->registry->getObject('template')->getPage()->setTitle($this->registry->getSetting('sitename').' | Search Groups');
        require_once(FRAMEWORK_PATH.'models/groups.php');
        $groups=new Groups($this->registry);
        if((isset($_GET) && count($_GET)>0 && isset($_GET['searchname']) && $_GET['searchname']!='')&&$search==true)
        {//search results are being shown
            $name=urlencode($this->registry->getObject('db')->sanitizeData($_GET['searchname']));
            $by=$this->registry->getObject('db')->sanitizeData($_GET['searchby']);
            $pagination=$groups->searchGroups($name,$by,$offset);
			$get="?searchby=".$by."&searchname=".$name;
			 if($pagination->getNumRowsPage()==0)
        {
            $this->registry->getObject('template')->buildFromTemplates('header.tpl.php','groups/invalid.tpl.php','footer.tpl.php');
        }
        else
        {
            $this->registry->getObject('template')->buildFromTemplates('header.tpl.php','groups/search.tpl.php','footer.tpl.php');
            $this->registry->getObject('template')->getPage()->addTag('groups',array('SQL',$pagination->getCache()));
            $this->registry->getObject('template')->getPage()->addTag('encoded_name',$name);
            $this->registry->getObject('template')->getPage()->addTag('public_name',urldecode($name));
            $this->registry->getObject('template')->getPage()->addTag('num_pages',$pagination->getNumPages());
            $this->registry->getObject('template')->getPage()->addTag('current_page',$pagination->getCurrentPage());
            if($pagination->isFirst())
            {
                $this->registry->getObject('template')->getPage()->addTag('first','');
                $this->registry->getObject('template')->getPage()->addTag('previous','');
            }
            else
            {
                $this->registry->getObject('template')->getPage()->addTag('first',"<a href='groups/search-results/".$get."'>First</a>");
                $this->registry->getObject('template')->getPage()->addTag('previous',"<a href='groups/search-results/".($offset-1).$get."'>Previous</a>");
            }
            if($pagination->isLast())
            {
                $this->registry->getObject('template')->getPage()->addTag('last','');
                $this->registry->getObject('template')->getPage()->addTag('next','');
            }
            else
            {
                $this->registry->getObject('template')->getPage()->addTag('last',"<a href='groups/search-results/".($pagination->getNumPages()-1).$get."'>Last</a>");
                $this->registry->getObject('template')->getPage()->addTag('next',"<a href='groups/search-results/".($offset+1).$get."'>Next</a>");
            }
        }
        }
        else
        {//we are showing the search form, no search has yet been performed
		
            $this->registry->getObject('template')->buildFromTemplates('header.tpl.php','groups/no-results.tpl.php','footer.tpl.php');
            
        }
       
    }
 
     private function listallgroups()
    {
		if($this->registry->getObject('authenticate')->getUser()->isadmin())
		{		
                    $this->registry->getObject('template')->getPage()->setTitle($this->registry->getSetting('sitename').' | List All Groups');
        $sql="select *,if(CAST(created AS DATE)=current_date, CONCAT('Today ', date_format(created,'%h:%i %p')),if(CAST(created AS DATE)=current_date-1,CONCAT('Yesterday ', date_format(created,'%h:%i %p')),concat(date_format(created,'%d-%m-%Y'),' ',date_format(created,'%h:%i %p')))) as createdFriendly from groups";
        $cache=$this->registry->getObject('db')->cacheQuery($sql);
        $this->registry->getObject('template')->getPage()->addTag('my-groups',array('SQL',$cache));
        $this->registry->getObject('template')->buildFromTemplates('header.tpl.php','groups/admin.tpl.php','footer.tpl.php');
		}
		else
		{
		$this->registry->errorPage('Access denied','Sorry, you are not allowed to view this page');
                }
    }
 
}
?>