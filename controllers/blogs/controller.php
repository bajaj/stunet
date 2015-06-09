<?php

class Blogscontroller
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
              $this->createBlog();
              break;
          case 'edit':
              $this->editblog(intval($urlBits[2]));
              break;
          case 'delete':
              $this->deleteblog(intval($urlBits[2]));
              break;
          case 'my-created-blogs':
              $this->listMyCreatedblogs();
              break;
           case 'search':
              $this->searchblogs(false,'',0);
              break;
           case 'search-results':
              $this->searchblogs(true,'',intval(isset($urlBits[2])?$urlBits[2]:0));
              break;
          case 'search-conn':
              $this->searchPrivateblogs(false,'',0);
              break;
           case 'search-conn-results':
              $this->searchPrivateblogs(true,'',intval(isset($urlBits[2])?$urlBits[2]:0));
              break;
          default: 
              $this->listMyCreatedblogs();
              break;
          }
      }
 else {
          $this->listMyCreatedblogs();
      }
      
 }
 
 private function  createBlog()
 {
     if(isset($_POST) && count($_POST)>0)
     {
         
         require_once(FRAMEWORK_PATH.'models/blog.php');
         $blog=new Blog($this->registry,0);
         $blog->setTitle($this->registry->getObject('db')->sanitizeData($_POST['title']));
         $blog->setContent($this->registry->getObject('db')->sanitizeData($_POST['content']));
         $blog->setCategory($this->registry->getObject('db')->sanitizeData($_POST['category']));
         $blog->setType($this->registry->getObject('db')->sanitizeData($_POST['type']));
         $blog->setCreator($this->registry->getObject('authenticate')->getUser()->getUserID());
         $blog->setAllowComments($this->registry->getObject('db')->sanitizeData($_POST['allowcomments']));
         $blog->save();
         header('Location: '.$this->registry->getSetting('siteurl').'blog/'.$blog->getID());
     }
     else
     {
         $this->registry->getObject('template')->getPage()->setTitle($this->registry->getSetting('sitename').' | Create Blog');
         $this->registry->getObject('template')->buildFromTemplates('header.tpl.php','blogs/create.tpl.php','footer.tpl.php');
     }
 }
 
 private function deleteblog($bid)
 {
     require_once(FRAMEWORK_PATH.'models/blog.php');
     $blog=new Blog($this->registry,$bid);
     $user=$this->registry->getObject('authenticate')->getUser()->getUserID();
     if($user==$blog->getCreator()||$this->registry->getObject('authenticate')->getUser()->isAdmin())
     {
         if($blog->delete())
         {
             header('Location: '.$this->registry->getSetting('siteurl').'blogs/my-created-blogs');
         }
     }
     else
     {
         $this->registry->errorPage('Access denied','Sorry, you are not allowed to delete this blog');
     }
 }
 private function editblog($gid)
 {
     require_once(FRAMEWORK_PATH.'models/blog.php');
     $blog=new Blog($this->registry,$gid);
     if($blog->getCreator()==$this->registry->getObject('authenticate')->getUser()->getUserID()||$this->registry->getObject('authenticate')->getUser()->isAdmin())
     {
        if(isset($_POST) && count($_POST)>0)
        { 
            $blog->setTitle($this->registry->getObject('db')->sanitizeData($_POST['title']));
            $blog->setContent($this->registry->getObject('db')->sanitizeData($_POST['content']));
            $blog->setType($this->registry->getObject('db')->sanitizeData($_POST['type']));
            $blog->setCategory($this->registry->getObject('db')->sanitizeData($_POST['category']));
            $blog->setAllowComments($this->registry->getObject('db')->sanitizeData($_POST['allowcomments']));
           
         $blog->save();
        header('Location: '.$this->registry->getSetting('siteurl').'blog/'.$gid);
        }
        else
        {
         $this->registry->getObject('template')->getPage()->setTitle($this->registry->getSetting('sitename').' | Edit Blog');
         $this->registry->getObject('template')->buildFromTemplates('header.tpl.php','blogs/edit.tpl.php','footer.tpl.php');
         $blog->toTags('blog_');
         $script="";
         $type=($blog->getType()=='Public')?0:1;
         $script="";
         $script.="<script type='text/javascript'>";
         $script.="document.getElementById('type').selectedIndex=".$type.';';
         $script.="</script>";
         $this->registry->getObject('template')->getPage()->addTag('script',$script);
        }
     }
     else
     {
         $this->registry->errorPage('Access denied','Sorry, you are not allowed to view this page');
     }
 }
 
 
   
    
    private function listMyCreatedblogs()
    {
        $this->registry->getObject('template')->getPage()->setTitle($this->registry->getSetting('sitename').' | Your Blogs');
        $user=$this->registry->getObject('authenticate')->getUser()->getUserID();
        $sql="select *,if(unix_timestamp(now())<(unix_timestamp(created)+60),concat(unix_timestamp(now())-unix_timestamp(created),' seconds ago'),
                if(unix_timestamp(now())<(unix_timestamp(created)+120),'over a minute ago',
                        if(unix_timestamp(now())<(unix_timestamp(created)+(60*60)),concat(round((unix_timestamp(now())-unix_timestamp(created))/60),' minutes ago'),
                                if(unix_timestamp(now())<(unix_timestamp(created)+60*120),'over an hour ago',
                                        if(unix_timestamp(now())<(unix_timestamp(created)+(60*50*24)),concat(round(((unix_timestamp(now())-unix_timestamp(created))/60/60)),' hours ago'),
                                                concat('at ',date_format(created,'%l:%i'),lower(date_format(created,'%p')),date_format(created,' %o%n %a, %D %b, %Y')))))))as createdFriendly from blog where creator={$user}";
        $cache=$this->registry->getObject('db')->cacheQuery($sql);
        $this->registry->getObject('template')->getPage()->addTag('my-created-blogs',array('SQL',$cache));
        $this->registry->getObject('template')->buildFromTemplates('header.tpl.php','blogs/mine-created.tpl.php','footer.tpl.php');
    }
    
 private function searchblogs($search=true,$name='',$offset=0)
    {
        require_once(FRAMEWORK_PATH.'models/blogs.php');
        $blogs=new Blogs($this->registry);
        $this->registry->getObject('template')->getPage()->setTitle($this->registry->getSetting('sitename').' | Search Blogs');
        if((isset($_GET) && count($_GET)>0 && isset($_GET['searchname']) && $_GET['searchname']!='')&&$search==true)
        {//search results are being shown
            $name=urlencode($this->registry->getObject('db')->sanitizeData($_GET['searchname']));
            $by=$this->registry->getObject('db')->sanitizeData($_GET['searchby']);
            $pagination=$blogs->searchBlogs($name,$by,$offset);
			$get="?searchby=".$by."&searchname=".$name;
        if($pagination->getNumRowsPage()==0)
        {
            $this->registry->getObject('template')->buildFromTemplates('header.tpl.php','blogs/invalid.tpl.php','footer.tpl.php');
        }
        else
        {
            $this->registry->getObject('template')->buildFromTemplates('header.tpl.php','blogs/search.tpl.php','footer.tpl.php');
            $this->registry->getObject('template')->getPage()->addTag('blogs',array('SQL',$pagination->getCache()));
            $this->registry->getObject('template')->getPage()->addTag('encoded_name',$name);
            $this->registry->getObject('template')->getPage()->addTag('public_name',$_GET['searchname']);
            $this->registry->getObject('template')->getPage()->addTag('num_pages',$pagination->getNumPages());
            $this->registry->getObject('template')->getPage()->addTag('current_page',$pagination->getCurrentPage());
            if($pagination->isFirst())
            {
                $this->registry->getObject('template')->getPage()->addTag('first','');
                $this->registry->getObject('template')->getPage()->addTag('previous','');
            }
            else
            {
                $this->registry->getObject('template')->getPage()->addTag('first',"<a href='blogs/search-results/".$get."'>First</a>");
                $this->registry->getObject('template')->getPage()->addTag('previous',"<a href='blogs/search-results/".($offset-1).$get."'>Previous</a>");
            }
            if($pagination->isLast())
            {
                $this->registry->getObject('template')->getPage()->addTag('last','');
                $this->registry->getObject('template')->getPage()->addTag('next','');
            }
            else
            {
                $this->registry->getObject('template')->getPage()->addTag('last',"<a href='blogs/search-results/".($pagination->getNumPages()-1).$get."'>Last</a>");
                $this->registry->getObject('template')->getPage()->addTag('next',"<a href='blogs/search-results/".($offset+1).$get."'>Next</a>");
            }
			}
			}
        else
        {//we are showing the search form, no search has yet been performed
            $this->registry->getObject('template')->buildFromTemplates('header.tpl.php','blogs/no-results.tpl.php','footer.tpl.php');
        }
        
    }
 
 private function searchPrivateblogs($search=true,$name='',$offset=0)
    {
        $this->registry->getObject('template')->getPage()->setTitle($this->registry->getSetting('sitename').' | Search Blogs by your connections');
        require_once(FRAMEWORK_PATH.'models/blogs.php');
        $blogs=new Blogs($this->registry);
        if((isset($_GET) && count($_GET)>0 && isset($_GET['searchname']) && $_GET['searchname']!='')&&$search==true)
        {//search results are being shown
            $name=urlencode($this->registry->getObject('db')->sanitizeData($_GET['searchname']));
            $by=$this->registry->getObject('db')->sanitizeData($_GET['searchby']);
            $pagination=$blogs->searchPrivateBlogs($name,$by,$offset);
			$get="?searchby=".$by."&searchname=".$name;
			if($pagination->getNumRowsPage()==0)
        {
            $this->registry->getObject('template')->buildFromTemplates('header.tpl.php','blogs/invalid-conn.tpl.php','footer.tpl.php');
        }
        else
        {
            $this->registry->getObject('template')->buildFromTemplates('header.tpl.php','blogs/search-conn.tpl.php','footer.tpl.php');
            $this->registry->getObject('template')->getPage()->addTag('blogs',array('SQL',$pagination->getCache()));
            $this->registry->getObject('template')->getPage()->addTag('encoded_name',$name);
            $this->registry->getObject('template')->getPage()->addTag('public_name',$_GET['searchname']);
            $this->registry->getObject('template')->getPage()->addTag('num_pages',$pagination->getNumPages());
            $this->registry->getObject('template')->getPage()->addTag('current_page',$pagination->getCurrentPage());
            if($pagination->isFirst())
            {
                $this->registry->getObject('template')->getPage()->addTag('first','');
                $this->registry->getObject('template')->getPage()->addTag('previous','');
            }
            else
            {
                $this->registry->getObject('template')->getPage()->addTag('first',"<a href='blogs/search-results/".$get."'>First</a>");
                $this->registry->getObject('template')->getPage()->addTag('previous',"<a href='blogs/search-results/".($offset-1).$get."'>Previous</a>");
            }
            if($pagination->isLast())
            {
                $this->registry->getObject('template')->getPage()->addTag('last','');
                $this->registry->getObject('template')->getPage()->addTag('next','');
            }
            else
            {
                $this->registry->getObject('template')->getPage()->addTag('last',"<a href='blogs/search-results/".($pagination->getNumPages()-1).$get."'>Last</a>");
                $this->registry->getObject('template')->getPage()->addTag('next',"<a href='blogs/search-results/".($offset+1).$get."'>Next</a>");
            }
        }
        }
        else
        {//we are showing the search form, no search has yet been performed
            $this->registry->getObject('template')->buildFromTemplates('header.tpl.php','blogs/no-results-conn.tpl.php','footer.tpl.php');
        }
        
    }
}
?>