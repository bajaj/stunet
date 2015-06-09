<?php

class Membershipcontroller
{
  private $registry;
  private $groupID;
  private $group;
  
  public function __construct(Registry $registry,$groupID)
  {
      $this->registry=$registry;
      $this->groupID=$groupID;
      require_once(FRAMEWORK_PATH.'models/group.php');
      $this->group=new Group($this->registry,$this->groupID);
  }
  
  public function join()
  {
      switch($this->group->getType())
      {
          case 'public':
              $this->autoJoinGroup();
              break;
      }
  }
  
  public function autoJoinGroup()
  {
      require_once(FRAMEWORK_PATH.'models/groupmembership.php');
      $gm=new Groupmembership($this->registry,0);
      $user=$this->registry->getObject('authenticate')->getUser()->getUserID();
      $gm->getByUserAndGroup($user,$this->groupID);
      if($gm->isValid())
      {
          $gm=new Groupmembership($this->registry,$gm->getID());
      }
      $gm->setApproved(1);
      $gm->save();
      $this->registry->errorPage('New membership','Thanks. you have joined the group successfully!');
  }
}
?>