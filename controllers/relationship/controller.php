<?php


class Relationshipcontroller{
    private $registry;
    
    public function __construct(Registry $registry,$directCall)
    {
        $this->registry=$registry;
        $urlBits=$this->registry->getObject('url')->getURLBits();
        if(isset($urlBits[1]))
        {
            switch($urlBits[1])
            {
                case 'create':
                    $this->createRelationship(intval($_POST['ID']),$_POST['relationship']);
                    break;
                case 'approve':
                   $this->approveRelationship(intval($_POST['ID']));
                    break;
                case 'reject':
                    $this->rejectRelationship(intval($_POST['ID']));
                    break;
                default:
                    break;
            }
        }
    }

    private function createRelationship($userb,$relationshiptype)
    {
        $this->registry->getObject('template')->buildFromTemplates();
        $usera=$this->registry->getObject('authenticate')->getUser()->getUserID();
        $typea=$this->registry->getObject('authenticate')->getUser()->getType();
        if($relationshiptype=='Mate')
        {
            $typeb=$typea;
        }
        elseif($typea=='professor' && $relationshiptype=='Pupil')
        {
            $typeb='student';
        }
        elseif($typea=='student' && $relationshiptype=='Professor')
        {
            $typeb='professor';
        }
        require_once(FRAMEWORK_PATH.'models/relationship.php');
        if($typeb=='student' && $typea=='professor') $approved=1;
        else $approved=0;
        $relationship = new Relationship($this->registry,0,$usera,$userb,$approved);
        if(!$relationship->alreadyExists())
        {
              if($relationship->isApproved())
                    {//The relation ship type is not mutual, directly email the user informing they have got a new relationship!
                       // $sql="select * from users,profile where users.ID=profile.ID and users.ID= ".$userb;
                       // $this->registry->getObject('db')->executeQuery($sql);
                       // if($this->registry->getObject('db')->numRows()==1)
                      //  {
                         //  $data=$this->registry->getObject('db')->getRows();
                          //  $this->registry->getObject('mailout')->startFresh();
                          //  $this->registry->getObject('mailout')->setSender('');
                          //  $this->registry->getObject('mailout')->setFromName($this->registry->getSetting('cms_name'));
                          //  $this->registry->getObject('mailout')->setTo($data['email']);
                          //  $this->registry->getObject('mailout')->setSubject('New '.$relationshiptype.' on '.$this->registry->getSetting('sitename'));
                           // $this->registry->getObject('mailout')->buildFromTemplates('relationship/approved.tpl.php');
                           // $tags=array();
                           // $sql="select * from users,profile where users.ID=profile.ID and users.ID =".$usera;
                           // $this->registry->getObject('db')->executeQuery($sql);
                           // $result=$this->registry->getObject('db')->getRows();
                           // $tags['fnamea']=$result['fname'];
                           // $tags['lnamea']=$result['lname'];
                           // $tags['fnameb']=$data['fname'];
                           // $tags['lnameb']=$data['lname'];
                         //   $tags['relationship']=$relationshiptype;
                         //   $tags['sitename']=$this->registry->getSetting('sitename');
                          //  $tags['siteurl']=$this->registry->getSetting('siteurl');
                          //  $this->registry->getObject('mailout')->replaceTags($tags);
                          //  $this->registry->getObject('mailout')->send();
                            //$this->registry->redirectUser($_SERVER['HTTP_REFERER'],'New mate added!',$data['username']. " has been added as your mate!");
                        //}
                        echo 'added';
                        exit;
                        
                    }
                    else
                    {//notify the user that he has a pending request
                        //$sql="select * from users,profile where users.ID=profile.ID and users.ID= ".$userb;
                        //$this->registry->getObject('db')->executeQuery($sql);
                        //if($this->registry->getObject('db')->numRows()==1)
                        //{
                         //   $data=$this->registry->getObject('db')->getRows();
                           // $this->registry->getObject('mailout')->startFresh();
                          //  $this->registry->getObject('mailout')->setSender('');
                          //  $this->registry->getObject('mailout')->setFromName($this->registry->getSetting('cms_name'));
                          //  $this->registry->getObject('mailout')->setTo($data['email']);
                          //  $this->registry->getObject('mailout')->setSubject('New pending request on '.$this->registry->getSetting('sitename'));
                          //  $this->registry->getObject('mailout')->buildFromTemplates('relationship/pending.tpl.php');
                          //  $tags=array();
                          //   $sql="select * from users,profile where users.ID=profile.ID and users.ID =".$usera;
                          //  $this->registry->getObject('db')->executeQuery($sql);
                          //  $result=$this->registry->getObject('db')->getRows();
                          //  $tags['fnamea']=$result['fname'];
                          //  $tags['lnamea']=$result['lname'];
                          //  $tags['fnameb']=$data['fname'];
                          //  $tags['lnameb']=$data['lname'];
                          //  $tags['relationship']=$relationshiptype;
                          //  $tags['sitename']=$this->registry->getSetting('sitename');
                          //  $tags['siteurl']=$this->registry->getSetting('siteurl');
                          //  $this->registry->getObject('mailout')->replaceTags($tags);
                          //  $this->registry->getObject('mailout')->send();
                            //$this->registry->redirectUser($_SERVER['HTTP_REFERER'],'Request Sent!',$data['username']. " has been sent an approval request!");
                        //}
                    }
                    echo 'request';
                    exit;
        }
     else
     {
         if($relationship->isApproved())
             echo 'approved';
         else
             echo 'pending';
         exit;
     }
                    
    }
    
    private function approveRelationship($r)
    {
        if($this->registry->getObject('authenticate')->isLoggedIn())
        {
            require_once(FRAMEWORK_PATH.'models/relationship.php');
            $relationship=new Relationship($this->registry,$r,0,0,0,0);
            if($relationship->getUserB()==$this->registry->getObject('authenticate')->getUser()->getUserID())
           {
               $relationship->approveRelationship();
               $relationship->save();
               echo 1;
               exit;
            }
            else
            {
                echo 0;
                exit;
            }
        }
        else{
            echo 0;
            exit;
        }
        
    }
    
    private function rejectRelationship($r)
    {
        if($this->registry->getObject('authenticate')->isLoggedIn())
        {
            require_once(FRAMEWORK_PATH.'models/relationship.php');
            $relationship=new Relationship($this->registry,$r,0,0,0,0);
            if($relationship->getUserB()==$this->registry->getObject('authenticate')->getUser()->getUserID())
            {
                $relationship->delete();
                echo 1;
               exit;
            }
            else
            {
                echo 0;
                exit;
            }
        }
        else{
            echo 0;
            exit;
        }
    }
}
?>