<?php
/**
 * Private message class
 */
class Sms {

	/**
	 * The registry object
	 */
	private $registry;
	
	/**
	 * my accdetails
	 */
	private $user="ritesh.bajaj6@gmail.com:123ritesh";
	// priyankjjain61@gmail.com:123ritesh
	
	
	private $recipientno;      // mobile no of recipient
	/**
	 * the message to send
	 */
	private $msg;
	
	private $senderID="TEST SMS";
	/**
	 * Name of the sender
	 */
	public function __construct( Registry $registry, $recipient_user_id,$msg ,$x=0)
	{
	$this->registry=$registry;
	$this->msg=$msg;
	
	if($x==1)	
	$this->setmobileno($recipient_user_id);
	
	}
	
	private function setmobileno($id)
	{
	$sql="SELECT mobile_no FROM profile WHERE ID=".$id;
	$this->registry->getObject('db')->executeQuery( $sql );
    $data=$this->registry->getObject('db')->getRows();
	
	$this->recipientno=$data['mobile_no'];
	
	// echo $this->recipientno;
	
	}
	
	public function sendsms()
	{
	
	$ch = curl_init();
	$user="ritesh.bajaj6@gmail.com:123ritesh";
	$receipientno=$this->recipientno;
	$senderID="TEST SMS"; 
	$msgtxt=$this->msg;
	
	curl_setopt($ch,CURLOPT_URL,  "http://api.mVaayoo.com/mvaayooapi/MessageCompose");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch,CURLOPT_POSTFIELDS,"user=$user&senderID=$senderID&receipientno=$receipientno&msgtxt=$msgtxt&state=0");
$buffer = curl_exec($ch);
if(empty ($buffer))
{ echo " buffer is empty "; }
else
{ echo $buffer; }
curl_close($ch);
	
	
	}
	
	public function sendsms2($no,$msg)
	{
	
	$ch = curl_init();
	$user="ritesh.bajaj6@gmail.com:123ritesh";
	$receipientno=$no;
	$senderID="TEST SMS"; 
	$msgtxt=$msg;
	
	curl_setopt($ch,CURLOPT_URL,  "http://api.mVaayoo.com/mvaayooapi/MessageCompose");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch,CURLOPT_POSTFIELDS,"user=$user&senderID=$senderID&receipientno=$receipientno&msgtxt=$msgtxt&state=0");
$buffer = curl_exec($ch);
if(empty ($buffer))
{ //echo " buffer is empty ";
 }
else
{ //echo $buffer;
 }
curl_close($ch);
	
	
	}
	
	
	
}	
	
	
	
	
	
?>	