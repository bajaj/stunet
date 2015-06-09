<?php 
/*
PHP Social Networking
@author Priyank Jain
Registry Class
 * +createAndStoreObject(object class prefix, object key): void -> Create and store an object
 * +storeSetting(setting data, setting key): void -> Store setting in an array
 * +getObject(object key): Object -> Get Object
 * +getSetting(setting key): setting -> Get setting 
*/

class Registry
{
	/*Array of objects*/
	private $objects;
	
	/*Array of settings*/
	private $settings;
	
	public function __construct()
	{
	}
	
	/*Create and store objects
	@param String $object the object file prefix
	@param String $keys the objects array key
	@return void
	*/
	public function createAndStoreObject($object,$key)
	{
		require_once($object.'.class.php');// This line includes the required class definition, only once!
		$this->objects[$key]=new $object($this);//This line creates an object of the concerned class type, and stores it in the objects array
		//For example: $this->objects['url']=new urlprocessor($this); will create an object of type urlprocessor. $this is the reference to the registry BECAUSE MOST OBJECTS REQUIRE ACCESS TO THE REGISTRY AND THIS INCLUDES OBJECTS WITHIN THE REGISTRY ITSELF!
	}
	
	/*Store data or setting
	@param String $setting the setting data
	@param String $keys the settings array key
	@return void
	*/
	public function storeSetting($setting,$key)
	{
		$this->settings[$key]=$setting;
	}
	
	/*Get setting stored in the registeries store
	@param String $key the settings array key
	@return the setting associated with the key
	*/
	public function getSetting($key)
	{
		return $this->settings[$key];
	}
	
		/*Get object stored in the registeries store
	@param String $key the objects array key
	@return the object associated with the key
	*/
	public function getObject($key)
	{
		return $this->objects[$key];
	}
        
        public function errorPage($heading,$content)
       {
	   if($this->getObject('authenticate')->isLoggedIn())
            $this->getObject('template')->buildFromTemplates('header.tpl.php','message.tpl.php','footer.tpl.php');
			else
			$this->getObject('template')->buildFromTemplates('header-nl.tpl.php','message-nl.tpl.php','footer.tpl.php');
			$this->getObject('template')->getPage()->setTitle($this->getSetting('sitename'));
            $this->getObject('template')->getPage()->addTag('heading',$heading);
            $this->getObject('template')->getPage()->addTag('content',$content);
        }
        
        public function redirectUser($url,$heading,$message)
        {
			/*if($url==$this->getSetting('siteurl').'authenticate/register'||$url==$this->getSetting('siteurl').'authenticate/login')
			$url=$this->getSetting('siteurl').'home';*/
                        if($this->getObject('authenticate')->isLoggedIn())
            $this->getObject('template')->buildFromTemplates('redirect.tpl.php');
                        else
                            $this->getObject('template')->buildFromTemplates('redirect-nl.tpl.php');
            $this->getObject('template')->getPage()->addTag('url',$url);
            $this->getObject('template')->getPage()->addTag('heading',$heading);
            $this->getObject('template')->getPage()->addTag('message',$message);
			if($this->getObject('authenticate')->isLoggedIn())
            $this->getObject('template')->getPage()->addTag('username',$this->getObject('authenticate')->getUser()->getUsername());
			else
			$this->getObject('template')->getPage()->addTag('username','');
			
        }
}
?>