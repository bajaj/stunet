<?php

class Blogcontroller
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
				$sql="select * from blog where ID = ".$urlBits[1];
				$this->registry->getObject('db')->executeQuery($sql);
				if($this->registry->getObject('db')->numRows()==1)
				{
                require_once(FRAMEWORK_PATH.'models/blog.php');
                $this->blog=new Blog($this->registry,intval($urlBits[1]));
                $this->blogID=intval($urlBits[1]);
                $usera=$this->registry->getObject('authenticate')->getUser()->getUserID();
                if($this->blog->getType()=='Public') $allow=true;
                elseif($this->blog->getCreator()==$usera || $this->registry->getObject('authenticate')->getUser()->isAdmin()) $allow=true;
                else
                {
                    $userb=$this->blog->getCreator();
                    $sql="select * from relationships where (usera={$usera} and userb={$userb}) or (usera={$userb} and userb={$usera}) and accepted=1";
                    $this->registry->getObject('db')->executeQuery($sql);
                    if($this->registry->getObject('db')->numRows()==1)
                        $allow=true;
                    else
                        $allow=false;
                }
                    if($allow)
                    {
                         if(isset($urlBits[2]))
                        {
                            switch($urlBits[2])
                            {
                                case 'comment':
                                    $this->comment();
                                    break;
                                default:
                                    $this->viewblog();
                                    break;
                            }
                        }
                        else
                        {
                                    $this->viewblog();
                        }
                    }
            
                else
                {
                    $this->registry->errorPage('Access denied','Sorry, you are not allowed to view this blog');
                }
				}
				else
				{
					$this->registry->errorPage('Invalid blog','The specified blog does not exist');
				}
            }
			else
			{
				header('Location: '.$this->registry->getSetting('siteurl').'home');
			}
        }
        else
        {
            $this->registry->errorPage('Please login','You must login to view this blog');
        }
    }
    
   
   
    private function viewblog()
    {
            $this->registry->getObject('template')->getPage()->setTitle($this->registry->getSetting('sitename').' | View Blog');
            $this->blog->toTags('blog_');
            $this->registry->getObject('template')->buildFromTemplates('header.tpl.php', 'blogs/view-creator.tpl.php', 'footer.tpl.php' );
            if($this->blog->getAllowComments())
            {
                $this->registry->getObject('template')->addTemplateBit('comments','blogs/comments.tpl.php');
                $cache=$this->blog->getComments();
                $this->registry->getObject('template')->getPage()->addPPTag('blogcomments',array('SQL',$cache));
            }
            else
            {
                $this->registry->getObject('template')->getPage()->addTag('comments','');
            }
    }
    
    private function comment()
    {//add a comments
        $urlBits=$this->registry->getObject('url')->getURLBits();
        $user=$this->registry->getObject('authenticate')->getUser()->getUserID();
        if(isset($_POST) && count($_POST)>0 && !isset($urlBits[3]))
        {
            require_once(FRAMEWORK_PATH.'models/blogcomments.php');
            $bc=new Blogcomments($this->registry,0);
            $bc->setComment($this->registry->getObject('db')->sanitizeData($_POST['comment']));
            $bc->setBlogid($this->blog->getID());
            $bc->setCreator($user);
            $bc->save();
            header('Location: '.$this->registry->getSetting('siteurl').'blog/'.$this->blog->getID().'#'.$bc->getID());
        }
        elseif(isset($urlBits[3]) && isset($urlBits[4]) && is_numeric($urlBits[3]))
        {//edit and delete
            require_once(FRAMEWORK_PATH.'models/blogcomments.php');
            $bc=new Blogcomments($this->registry,$urlBits[3]);
            if($bc->getBlogid()==$this->blog->getID())
            {
                if($user==$bc->getCreator()||$this->registry->getObject('authenticate')->getUser()->isAdmin())
                {
                    if($urlBits[4]=='edit')
                    {
                        if(isset($_POST) && count($_POST)>0)
                        {
                            $bc->setComment($this->registry->getObject('db')->sanitizeData($_POST['comment']));
                            $bc->save();
                            header('Location: '.$this->registry->getSetting('siteurl').'blog/'.$this->blog->getID().'#'.$bc->getID());
                        }
                        else
                        {
                            $this->registry->getObject('template')->buildFromTemplates('header.tpl.php','blogs/edit-comment.tpl.php','footer.tpl.php');
                            $this->blog->toTags('blog_');
                            $bc->toTags('comment_');
                        }
                    }
                    elseif ($urlBits[4]=='delete')
                    {
                        if($bc->delete())
                        {
                            header('Location: '.$this->registry->getSetting('siteurl').'blog/'.$this->blog->getID());
                        }
                        else
                        {
                            $this->registry->errorPage('Error','An error occured while deleting this comment');
                        }
                        
                    }
                }
                else
                {
                   header('Location: '.$this->registry->getSetting('siteurl').'blog/'.$this->blog->getID()); 
                }
            }
            else
            {//There is not blog comment with the given id!
                header('Location: '.$this->registry->getSetting('siteurl').'blog/'.$this->blog->getID());
            }
        }
        else
        {
            header('Location: '.$this->registry->getSetting('siteurl').'blog/'.$this->blog->getID()); 
        }
    }

    
}
?>