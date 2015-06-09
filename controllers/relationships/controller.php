<?php
class RelationshipsController{
    private $registry;
    public function __construct(Registry $registry) {
        $this->registry=$registry;
        $urlBits=$this->registry->getObject('url')->getURLBits();
        if(isset($urlBits[1]))
        {
            switch($urlBits[1])
            {
                case 'pending':
                    $this->pendingRelationships();
                    break;
                case 'all':
                    if(isset($urlBits[2]))
                    $this->viewAll($urlBits[2]);
                    break;
                default:
                    $this->myRelationships();
                    break;
            }
        }
        else
        {
            $this->myRelationships();
        }
    }
    
    private function myRelationships()
    {	//Used to get connections of logged in user!
        if($this->registry->getObject('authenticate')->isLoggedIn())
        {
            $this->registry->getObject('template')->getPage()->setTitle($this->registry->getSetting('sitename').' | Your Connections');
        require_once(FRAMEWORK_PATH.'models/relationships.php');
        $relationships=new Relationships($this->registry);
        $relationship=$relationships->getByUser($this->registry->getObject('authenticate')->getUser()->getUserID(),'same');
        $this->registry->getObject('template')->buildFromTemplates('header.tpl.php','friends/mine.tpl.php','footer.tpl.php');
        $this->registry->getObject('template')->getPage()->addTag('sameconnections',array('SQL',$relationship));
        $relationship=$relationships->getByUser($this->registry->getObject('authenticate')->getUser()->getUserID(),'different');
        $this->registry->getObject('template')->buildFromTemplates('header.tpl.php','friends/mine.tpl.php','footer.tpl.php');
        $this->registry->getObject('template')->getPage()->addTag('differentconnections',array('SQL',$relationship));
        $tag=($this->registry->getObject('authenticate')->getUser()->getType()=='professor')?'Pupils':'Professors';
        $this->registry->getObject('template')->getPage()->addTag('different',$tag);
        }
        else
        {
            $this->registry->errorPage('Please login','Please login to see your connections');
        }
    }
    
    private function pendingRelationships()
	{//Used to get pending connections of logged in user!
		if( $this->registry->getObject('authenticate')->isLoggedIn() )
		{
                    $this->registry->getObject('template')->getPage()->setTitle($this->registry->getSetting('sitename').' | Pending Connections');
			require_once( FRAMEWORK_PATH . 'models/relationships.php');
			$relationships = new Relationships( $this->registry );
			$pending = $relationships->getRelationships( 0, $this->registry->getObject('authenticate')->getUser()->getUserID(), 0 );
			$this->registry->getObject('template')->buildFromTemplates('header.tpl.php', 'friends/pending.tpl.php', 'footer.tpl.php');
			$this->registry->getObject('template')->getPage()->addTag('pending', array( 'SQL', $pending ) );	
		}
		else
		{
			$this->registry->errorPage( 'Please login', 'Please login to manage pending connections');
		}
	}
        
        private function viewAll($user){
            //Used to get connections of other users!
            if($this->registry->getObject('authenticate')->isLoggedIn()){
                if($this->registry->getObject('authenticate')->getUser()->getUserID()==$user)
                {
                    $this->myRelationships();
                }
                else
                {
                $this->registry->getObject('template')->getPage()->setTitle($this->registry->getSetting('sitename').' | View Connections');
                require_once(FRAMEWORK_PATH.'models/relationships.php');
                $relationships=new Relationships($this->registry);
                require_once( FRAMEWORK_PATH . 'models/profile.php');
                $p = new Profile( $this->registry, $user );
                $name = $p->getFName().' '.$p->getLName();
                $this->registry->getObject('template')->getPage()->addTag('connecting_name', $name );
                $type=$p->getType();
                $relationship=$relationships->getAll($user,$type,'same');
                $this->registry->getObject('template')->buildFromTemplates('header.tpl.php','friends/all.tpl.php','footer.tpl.php');
                $this->registry->getObject('template')->getPage()->addTag('allsame',array('SQL',$relationship));
                $relationship=$relationships->getAll($user,$type,'different');
                $this->registry->getObject('template')->buildFromTemplates('header.tpl.php','friends/all.tpl.php','footer.tpl.php');
                $this->registry->getObject('template')->getPage()->addTag('alldifferent',array('SQL',$relationship));
                
                $tag=($type=='student')?'Professors':'Pupils';
                $this->registry->getObject('template')->getPage()->addTag('different',$tag);
                }
            }
            
        }
    
}
?>