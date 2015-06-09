<?php 
/*User class, object is created when a user tries to login
 * +__construct(Registry,id=0,username='',password=''):void -> sets the variables of $this user object
 * +isActive():true or false -> returns true if the user is active
 * +isBanned():true or false -> returns true if the user is banned
 * +isValid(): true or fasle -> returns true if the user is valid
 */
class user
{
    private $valid=false;
/*Create our user object*/
public function __construct(Registry $registry,$id=0,$username='',$password='')
{
	$this->registry=$registry;
	if($id==0 && $username!='' && $password!='')
	{
		$user=$this->registry->getObject('db')->sanitizeData($username);
		$hash=md5($password.$user);
		$sql="select u.*,p.type,p.fname,p.lname from users u,profile p where u.username='{$user}' and u.password='{$hash}' and p.ID=u.ID and u.deleted=0";
		$this->registry->getObject('db')->executeQuery($sql);
		if($this->registry->getObject('db')->numRows()==1)
		{
			$data=$this->registry->getObject('db')->getRows();
			$this->id=$data['ID'];
			$this->username=$data['username'];
			$this->active=$data['active'];
			$this->banned=$data['banned'];
			$this->admin=$data['admin'];
			$this->email=$data['email'];
			$this->reset_key=$data['reset_key'];
                        $this->type=$data['type'];
                        $this->fname=$data['fname'];
                        $this->lname=$data['lname'];
			$this->valid=true;
		}
	}
	elseif($id>0)
	{
		$id=intval($id);
		$sql="select u.*,p.type,p.fname,p.lname from users u, profile p where u.ID='{$id}' and u.deleted=0 and u.ID=p.ID";
		$this->registry->getObject('db')->executeQuery($sql);
		if($this->registry->getObject('db')->numRows()==1)
		{
			$data=$this->registry->getObject('db')->getRows();
			$this->id=$data['ID'];
			$this->username=$data['username'];
			$this->banned=$data['banned'];
			$this->active=$data['active'];
			$this->email=$data['email'];
			$this->admin=$data['admin'];
			$this->reset_key=$data['reset_key'];
                        $this->type=$data['type'];
                        $this->fname=$data['fname'];
                        $this->lname=$data['lname'];
			$this->valid=true;
		}
	}
}

public function isValid()
{
	return $this->valid;
}

public function isActive()
{
	return $this->active;
}
public function isBanned()
{
	return $this->banned;
}

public function getUserId()
{
    return $this->id;
}

public function getUsername()
{
    return $this->username;
}

public function isAdmin()
{
    return $this->admin;
}

public function getType()
{
    return $this->type;
}
public function getFName()
{
    return $this->fname;
}

public function getLName()
{
    return $this->lname;
}
}
?>