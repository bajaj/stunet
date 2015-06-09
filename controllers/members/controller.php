<?php

class Memberscontroller{
    private $registry;
    
    public function __construct(Registry $registry)
    {
        $this->registry=$registry;
        $urlBits=$this->registry->getObject('url')->getURLBits();
        if(isset($urlBits[1]))
        {
            switch($urlBits[1])
            {
                case 'search':
                    $this->searchMembers(false,'',0);
                    break;
                case 'search-results':
                    $this->searchMembers(true,'',intval(isset($urlBits[2])?$urlBits[2]:0));
                    break;
                default:
                    $this->searchMembers(false,'',0);                    
            }
        }
        else
        {
                    $this->searchMembers(false,'',0);
        }
    }
    
    private function searchMembers($search=true,$name='',$offset=0)
    {
        require_once(FRAMEWORK_PATH.'models/members.php');
        $this->registry->getObject('template')->getPage()->setTitle($this->registry->getSetting('sitename').' | Search Members');
        $members=new Members($this->registry);
        if((isset($_GET) && count($_GET)>0 && isset($_GET['searchname']) && $_GET['searchname']!='')&&$search==true)
        {//search results are being shown
            $name=urlencode($this->registry->getObject('db')->sanitizeData($_GET['searchname']));
            $by=$this->registry->getObject('db')->sanitizeData($_GET['searchby']);
            $pagination=$members->searchMembers($name,$by,$offset);
        
			$get="?searchby=".$by."&searchname=".$name;
        if($pagination->getNumRowsPage()==0)
        {
            $this->registry->getObject('template')->buildFromTemplates('header.tpl.php','members/invalid.tpl.php','footer.tpl.php');
        }
        else
        {
            $this->registry->getObject('template')->buildFromTemplates('header.tpl.php','members/search.tpl.php','footer.tpl.php');
            $this->registry->getObject('template')->getPage()->addTag('members',array('SQL',$pagination->getCache()));
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
                $this->registry->getObject('template')->getPage()->addTag('first',"<a href='members/search-results/".$get."'>First</a>");
                $this->registry->getObject('template')->getPage()->addTag('previous',"<a href='members/search-results/".($offset-1).$get."'>Previous</a>");
            }
            if($pagination->isLast())
            {
                $this->registry->getObject('template')->getPage()->addTag('last','');
                $this->registry->getObject('template')->getPage()->addTag('next','');
            }
            else
            {
                $this->registry->getObject('template')->getPage()->addTag('last',"<a href='members/search-results/".($pagination->getNumPages()-1).$get."'>Last</a>");
                $this->registry->getObject('template')->getPage()->addTag('next',"<a href='members/search-results/".($offset+1).$get."'>Next</a>");
            }
        }
		}
        else
        {//we are showing the search form, no search has yet been performed
            $this->registry->getObject('template')->buildFromTemplates('header.tpl.php','members/no-results.tpl.php','footer.tpl.php');
           
			}
    }
}
?>
