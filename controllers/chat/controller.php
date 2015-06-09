<?php

class chatcontroller{
    private $registry,$groupid;
    
    public function __construct(Registry $registry)
	{
	  $this->registry=$registry;
            $urlBits=$this->registry->getObject('url')->getURLBits();
	  $this->groupid=$urlBits[1];
	  if( $this->registry->getObject('authenticate')->isLoggedIn())
		{
	  $name=$this->registry->getObject('authenticate')->getUser()->getFname();
	  $name.=" ".$this->registry->getObject('authenticate')->getUser()->getLname();
          require_once(FRAMEWORK_PATH.'models/group.php');
          $group=new Group($this->registry,$this->groupid);
          $this->registry->getObject('template')->getPage()->setTitle($this->registry->getSetting('sitename').' | Chat');
	  $this->registry->getObject('template')->buildFromTemplates('chat/header.tpl.php','chat/view.tpl.php','footer.tpl.php');
	  $this->registry->getObject('template')->getPage()->addTag('username',$name);
	  $this->registry->getObject('template')->getPage()->addTag('grpname',$group->getName());
          $this->registry->getObject('template')->getPage()->addTag('grpid',$this->groupid);
	  $this->registry->getObject('template')->getPage()->addTag('userlist',array('SQL',$this->getusers()));
	  }
	}
	
	private function getusers()
	{
	$sql="SELECT distinct concat(p.fname,' ',p.lname) as fullname FROM chat_users p,group_membership gm,groups g WHERE g.ID=gm.group and g.ID={$this->groupid}
            AND ((gm.user=p.user_id and gm.approved=1)or g.creator=p.user_id) order by concat(p.fname,' ',p.lname) asc";
	$cache = $this->registry->getObject('db')->cacheQuery( $sql );
        return $cache;
	}
	
	
}	