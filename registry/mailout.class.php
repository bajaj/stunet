<?php
class mailout{
private $to;
private $subject;
private $from;
private $message;
private $error;
private $type;
private $lock;
private $headers;
private $fromName;
private $method;

public function __construct(Registry $registry)
{
	$this->registry=$registry;
	$this->startFresh();
}

public function startFresh()
{
	//not in constructor as this method is reused everytime a new email is to be sent
	$this->lock=false;
	$this->error="Message not sent because: ";
	$this->message='';
}

public function setTo($to)
{
	//check for header injection
	if(eregi("\n",urldecode($to))||eregi("\r",urldecode($to)))
	{
		$this->lock();
		$this->error.="Reciepient Email header injection attempt, probably caused by spam attempts";
		return false;
	}
	//check for valid email
	elseif(!eregi("^[_a-z0-9-]+(\.[_a-z0-9-])*@[_a-z0-9-]+(\.[_a-z0-9-])*(\.[a-z]{2,3})$",$to))
	{
		$this->lock();
		$this->error.="Invalid reciepient email address";
		return false;
	}
	//set the reciepient email address
	else
	{
		$this->to=$to;
		return true;
	}
}

public function setSender($sender)
{
	if($sender=='')
	{
		$this->headers='From: '.$this->registry->getSetting('adminEmailAddress');
		$this->from=$this->registry->getSetting('adminEmailAddress');
		return true;
	}
	else
	{
		//check for header injection
	if(eregi("\n",urldecode($sender))||eregi("\r",urldecode($sender)))
	{
		$this->lock();
		$this->error.="Reciepient Email header injection attempt, probably caused by spam attempts";
		return false;
	}
	//check for valid email
	elseif(!eregi("^[_a-z0-9-]+(\.[_a-z0-9-])*@[_a-z0-9-]+(\.[_a-z0-9-])*(\.[a-z]{2,3})$",$sender))
	{
		$this->lock();
		$this->error.="Invalid reciepient email address";
		return false;
	}
	//set the sender email address
	else
	{
		$this->headers='From: '.$sender;
		$this->from=$sender;
		return true;
	}
	}
		
}

public function setSenderIgnoringRules($sender)
{
	$this->headers.="From: ".$sender;
}

public function appendHeader($toAppend)
{
	$this->headers.="\r\n".$toAppend;
}

public function builtFromText($message)
{
	$this->message.=$message;
}

public function setMethod($method)
{
	$this->method=$method;
}

public function setSubject($subject)
{
	$this->subject=$subject;
}

public function setFromName($fromName)
{
	$this->fromName=$fromName;
}

/*Set the lock to true so as to prevent the email from being sent*/
public function lock()
{
	$this->lock=true;
}

public function send()
{
	switch($this->method)
	{
		case 'sendemail':
		return $this->sendWithSendEmail();
		break;
		case 'smtp':
		return $this->sendWithSmtp();
		break;
		default:
		return $this->sendWithSendEmail();
	}
}

public function sendWithSendEmail()
{
	if($this->lock)
	{
		return false;
	}
	else
	{
		if(!@mail($this->to,$this->subject,$this->message,$this->headers))
		{
			$this->error.="Problems sending with PHP\'s mail function";
			return false;
		}
		else
		{ 
			return true;
		}
	}
}

public function sendWithSmtp()
{
}


public function buildFromTemplates()
{
	$bits=func_get_args();
	$content="";
	foreach($bits as $bit)
	{
		if(!strpos($bit,"emailtemplates/"))
		{
			$bit="emailtemplates/".$bit;
		}
		if(file_exists($bit))
		{
			$content.=file_get_contents($bit);
		}
	}
	$this->message=$content;
}

public function replaceTags($tags)
{
	foreach($tags as $tag=>$data)
	{
		if(!is_array($tag))
		{
			$this->message=str_replace('{'.$tag.'}',$data,$this->message);
			
		}
	}
}
}

?>