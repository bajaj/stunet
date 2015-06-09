<?php 
/*The Page object will contain actual content from the templates
@author Priyank Jain
@version 1.0
 * +addAdditionalParsingData($block,$tag,$condition,$extratag,$data):void -> create APD data
 * +addPPTag($key,$data):void -> add a PP tag
 * +addTag($key,$data):void -> add a Tag
 * +addTemplateBit($tag,$bit):void -> add a template bit to the page
 * +setContent($content):void -> set content
 * +getContent():content -> get content
 * +getTitle():title -> get title
 * +setTitle(title):void -> set title
 * +getBlock($tag):block -> returns the block enclosed within <!-- START {$tag} -->block<!-- END {$tag} -->
 * +removeTag($tag):void -> removes a particular tag from the page!
 * +getTags():tags -> get all the tags on the page
 * +getPPTags():pptags -> get all PP tags on the page
 * +getAdditionalParsingData():apd -> get array of apd data
 * +getBits():void -> get a list of template bits to be added to the page
 * +getContentToPrint():content -> returns content doing some replacements!
 * 
*/
class Page
{
//page title
private $title = '';

//array of tags
private $tags=array();

//array of post parse tags
private $postParseTags=array();

//array of template bits
private $bits=array();

//the page content
private $content='';

//array of additional parsing data
private $apd=array();

//Create our page object
public function __construct(Registry $registry)
{
	$this->registry=$registry;
}

/*Get page title
@return String the page title
*/
public function getTitle()
{
	return $this->title;
}

/*Set the page title
@param String $title the page title
@return void
*/
public function setTitle($title)
{
	$this->title=ucwords($title);
}

/*Get the page content
@return String $content the page content
*/
public function getContent()
{
	return $this->content;
}

/*Set the page content
@param String $content the page content
@retrun void
*/
public function setContent($content)
{
	$this->content=$content;
}

/*Add a template tag and its replacement value/data to the page
@param String $key the key to store within the tags array
@param String $data the replacement data
@return void
*/
public function addTag($key,$data)
{
	$this->tags[$key]=$data;
}

/*Remove a tag
@param String $key the key to remove from the tags array
@return void*/
public function removeTag($key)
{
	unset($this->tags[$key]);
}

/*get tags associated with the page
@return array of tags*/
public function getTags()
{
	return $this->tags;
}

/*Add post parse tags
@param String $key the key to store within the array
@param String $data the replacemenet data
@return void
*/
public function addPPTag($key,$data)
{
	$this->postParseTags[$key]=$data;
}

/*Get tags to be parsed after the first batch have been parsed
@return array of Post Parse tags
*/
public function getPPTags()
{
	return $this->postParseTags;
}

/*Add a template bit to the page, doesn't actually add the content just yet
@param String the tag where the template is added
@param String the template filename
@return void
*/
public function addTemplateBit($tag,$bit,$replacements)
{
	$this->bits[$tag]=array('template'=>$bit,'replacements'=>$replacements);
}

/*Add additional parsing data
APD is used in parsing loops. We may want to have an extra bit of data depending on its iteration value. For example on a form list, we may want a specific item to be selected. 
@param String the block where the condition applies
@param String tag with the block where the condition applies
@param String condition: what the tag must equal 
@param String extratag: if condition = tag value then we have an extra tag called extratag
@param String data: if the tag value =condition then extra tag is replaced with this value
*/
public function addAdditionalParsingData($block,$tag,$condition,$extratag,$data)
{
	$this->apd[$block]=array($tag=>array('condition'=>$condition,'tag'=>$extratag,'data'=>$data));
}

/*Get the template bits to be entered into the page
@return the array of template bits and template filename
*/
public function getBits()
{
	return $this->bits;
}

/*Get the array of additional parsing data
@return array of APD*/
public function getAdditionalParsingData()
{
	return $this->apd;
}

/*Get a chunk of page content
@param String the tag wrapping the block 
@return String the block of content without the comments <!-- START tag --> and <!-- END tag -->
*/
public function getBlock($tag)
{
    $tor=array();
    $tor[0]="";
	preg_match('#<!-- START '.$tag.' -->(.*?)<!-- END '.$tag.' -->#si',$this->content,$tor);
	$tor=str_replace('<!-- START '.$tag.' -->','',isset($tor[0])?$tor[0]:'');
	$tor=str_replace('<!-- END '.$tag.' -->','',$tor);
	return $tor;
}

/*Do some final replacements when we are ready to output the content to the browser */
public function getContentToPrint() 
{
	$this->content=preg_replace('#{form_(.+?)}#si','',$this->content);
	$this->content=preg_replace('#{nbd_(.+?)}#si','',$this->content);
	return $this->content;
}
}
?>