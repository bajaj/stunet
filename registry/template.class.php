<?php 
/*Template management class
@author Priyank Jain
@version 1.0
 * +__construct(Registry reference):void -> creates our template and page object! 
 * +buildFromTemplates(list of templates files or template file locations of the form(views/TEMPLATENAME/templates/FILENAME.php)!):void -> sets the content of the page to be the combination of all template files passed as arguments!
 * +addTemplateBit(tag where the template is to be inserted, template path or filename):void -> Add a template bit from a view to our page
 * +getPage():page -> returns the page object, $page
 * +parseOutput():void -> performs title parsing and replacement of tags!
 * +dataToTags($data  as $key=>content, $prefix): void -> convert an array of data to tags, calls addTag($key.$prefix,$content) function of page object!
*/

/*Include our page class, create a page object to manage the content and structure of the page
@param Object $registry the the registry object
*/
class Template
{
private $registry;
private $page;

public function __construct(Registry $registry)
{
	$this->registry=$registry;
	include(FRAMEWORK_PATH.'registry/page.class.php');
	$this->page=new Page($this->registry);
}

/*Set the content of the page based on a number of templates
pass template file locations as individual arguments
@return void
*/
public function buildFromTemplates()
{
	$bits=func_get_args();
	$content="";
	foreach($bits as $bit)
	{
		if(strpos($bit,'views/')===false)
		{
			$bit='views/'.$this->registry->getSetting('view').'/templates/'.$bit;
		}
		if(file_exists($bit)==true)
		{
			$content.=file_get_contents($bit);
		}
	}
	$this->page->setContent($content);
}

/*Add a template bit from a view to our page
@param String $tag the tag where we insert the template e.g. {hello}
@param String $bits the template filename or path
@return void
*/
public function addTemplateBit($tag,$bit,$replacements=array())
{
	if(strpos($bit,'views/')===false)
	{
		$bit='views/'.$this->registry->getSetting('view').'/templates/'.$bit;
	}
	$this->page->addTemplateBit($tag,$bit,$replacements);
}

/*Take the template bits from the view and insert them into our page content
Updates the page content
@return void*/
private function replaceBits()
{
	$bits=$this->page->getBits();
	
	//loop through template bits
	foreach($bits as $tag=>$template)
	{
		$templateContent=file_get_contents($template['template']);
                $tags=array_keys($template['replacements']);
                $tagsNew=array();
                foreach($tags as $tagsa){
                    $tagsNew[]='{'.$tagsa.'}';
                }
                $values=array_values($template['replacements']);
                $templateContent=str_replace($tagsNew,$values,$templateContent);
		$newContent=str_replace('{'.$tag.'}',$templateContent,$this->page->getContent());
		$this->page->setContent($newContent);
	}
}

/*Replace the tags in our page with content
@param boolean $pp variable indicating if the tags are Post Parse Tags
@return void
*/
private function replaceTags($pp=false)
{
	//get the tags in the page
	if($pp==false)
	{
		$tags=$this->page->getTags();
	}
	else
	{
		$tags=$this->page->getPPTags();
	}
	
	//go through them all
	foreach($tags as $tag=>$data)
	{
		//if the tag is an array, then it is not simple find and replace
		if(is_array($data))
		{
			if($data[0]=='SQL')
			{
				//it is a cached query, replace tags from the database
				$this->replaceDBTags($tag,$data[1]);
			}
			elseif($data[0]=='DATA')
			{
				//it is some cached data, replace tags from cached data
				$this->replaceDataTags($tag,$data[1]);
			}
		}
		else
		{
			//replace the content
			$newContent=str_replace('{'.$tag.'}',$data,$this->page->getContent());
			//update contents of the page
			$this->page->setContent($newContent);
		}
	}
}

/*Replace content on the page with data from the database
@param String $tag the tag defining the area of content
@param int $cacheId the queries ID in the query cache
@return void
*/
private function replaceDBTags($tag,$cacheId)
{
	$block='';
	$blockOld=$this->page->getBlock($tag);
	$apd=$this->page->getAdditionalParsingData();
	$apdkeys=array_keys($apd);
	
	//for each record relating to the query
	while($tags=$this->registry->getObject('db')->resultsFromCache($cacheId))
	{
		$blockNew=$blockOld;
		//check if we have any apd tags
		if(in_array($tag,$apdkeys))
		{
			foreach($tags as $ntag=>$data)
			{
				$blockNew=str_replace('{'.$ntag.'}',$data,$blockNew);
				
				//is this the tag where extra parsing is required?
				if(array_key_exists($ntag,$apd[$tag]))
				{
					$extra=$apd[$tag][$ntag];
					//does the tag equal the condition?
					if($data==$extra['condition'])
					{
						$blockNew=str_replace('{'.$extra['tag'].'}',$extra['data'],$blockNew);
					}
					else
					{
						//remove the extra tag
						$blockNew=str_replace('{'.$extra['tag'].'}','',$blockNew);
					}
				}
			}
			
		}
		//create a new block of content with results replaced into it
		else
		{
			foreach($tags as $ntag=>$data)
			{
				$blockNew=str_replace('{'.$ntag.'}',$data,$blockNew);
			}
		}
		$block.=$blockNew;
	}
	//remove the seperator in the template and replace the page contents
	$newContent=str_replace('<!-- START '.$tag.' -->'.$blockOld.'<!-- END '.$tag.' -->',$block,$this->page->getContent());
	//update the page contents
	$this->page->setContent($newContent);
}

/*Replace content on the page with data from the cache
@param String $tag the tag defining the area of content
@param int $cacheId the datas ID in the data cache
@return void
*/
private function replaceDataTags($tag,$cacheId)
{
	$blockOld=$this->page->getBlock($tag);
        $block='';
        $tags=$this->registry->getObject('db')->dataFromCache($cacheId);
	foreach($tags as $key=>$tagsdata)
	{
		$blockNew=$blockOld;
		foreach($tagsdata as $taga=>$data)
		{
			$blockNew=str_replace('{'.$taga.'}',$data,$blockNew);
		}
                $block .= $blockNew;
		
	}
        
	//replace the contents
	$newContent=str_replace('<!-- START '.$tag.' -->'.$blockOld.'<!-- END '.$tag.' -->',$block,$this->page->getContent());
	//update the contents
	$this->page->setContent($newContent);
}

/*Convert an array of data into tags
@param array the data
@param String prefix to be added to field name to create the tag name
@return void
*/
public function dataToTags($data,$prefix)
{
	foreach($data as $key=>$content)
	{
		$this->page->addTag($prefix.$key,$content);
	}
}

/*Take the title we set in the page object and insert it into the title view*/
private function parseTitle()
{
	$newContent=str_replace('<title>','<title>'.$this->page->getTitle(),$this->page->getContent());
	$this->page->setContent($newContent);
}

/*Parse the page object into some output
@return void*/
public function parseOutput()
{
	$this->replaceBits();
	$this->replaceTags(false);
	$this->replaceBits();
	$this->replaceTags(true);
	$this->parseTitle();
}

/*Get the page object
@return object*/
public function getPage()
{
	return $this->page;
}
}
?>