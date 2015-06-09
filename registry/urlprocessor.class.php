<?php

/*URL processor class
 * @author Priyank Jain
 * @version 1.0
 * +buildURL($bits,$query,$admin or not= 1 or 0):URL -> returns URL made up from the arguments
 * +getURLPath():URLpath -> get the URL path
 * +setURLPath(URLpath):void -> sets the URL path
 * +getURLBits():URLbitsarray -> get the array of url bits
 * +getURLBit(key):URLbits element -> get a specific element from the URL bits
 * +getURLData():void -> stores the url path in urlPath variable, and URL bits in urlBits array!
 */

class urlprocessor
{
	private $urlBits=array();
	private $urlPath;
	private $registry;
	
	public function __construct(Registry $registry)
	{
		$this->registry=$registry;
	}
	/*Set the URL path
	@param String the url path
	@return void
	*/
	public function setURLPath($urlPath)
	{
		$this->urlPath=$urlPath;
	}
	/*Get the URL path
	@return String the url Path
	*/
	public function getURLPath()
	{
		return $this->urlPath;
	}
	
	/*get data from the current URL
	@return void
	*/
	public function getURLData()
	{
		$urldata=(isset($_GET['page']))?$_GET['page']:'';
		$this->urlPath=$urldata;
		if($urldata=='')
		{
			$this->urlBits[]='';
		}
		else
		{
                    
			$data=explode('/',$urldata);//parse the url
			while(!empty($data) && strlen(reset($data))===0)//reset function sets the pointer of the array to the first element //strlen(reset($data)) function returns 1 if the first element is anything except '' (empty string or null), returns 0 if it is null or empty string
			{
				array_shift($data);//if the first element of the array is not set or null, right shift the array by 1 position removing the first element
			}
			while(!empty($data) && strlen(end($data))===0)//end function sets the pointer of the array to the last element
			//strlen(end($data)) function returns 1 if the first element is anything except null or empty string, returns 0 if it is null or empty string
			{
				array_pop($data);//if the last element is null, discard it!
			}
			$this->urlBits=$data;
			
		}
	}
	
	/*Get the URL bits 
	@return void */
	public function getURLBits()
	{
		return $this->urlBits;
	}
	
	/*Get a specific URL bit
	@return void*/
	public function getURLBit($whichBit)
	{
		return (isset($this->urlBits[$whichBit]))?$this->urlBits[$whichBit]:0;
	}
	
	/*Build a url from URL bits
	@param array $bits array of URL bits
	@param String $qs the query string
	@param boolean $admin if it is an administrative URL
	@return the URL build from the components
	*/
	public function buildURL($bits,$qs='',$admin=0)
	{
		$admin=($admin==1)?$this->registry->getSetting('admin_folder').'/':'';
		$the_rest='';
		foreach($bits as $bit)
		{
			$the_rest.=$bit.'/';
		}
		$the_rest.=($qs!='')?'?&'.$qs:'';
		return $this->registry->getSetting('siteurl').$admin.$the_rest;
	}
}
?>