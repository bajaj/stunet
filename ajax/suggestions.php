<?php
$type=stripslashes($_POST['type']);
$user=stripslashes($_POST['id']);
include('../config.php');
$conn=new mysqli($configs['db_host_sn'],$configs['db_user_sn'],$configs['db_pass_sn'],$configs['db_name_sn']) or trigger_error('Error connecting to host');
if(version_compare(phpversion(),"4.3.0")=="-1")
{
	$u=$conn->escape_string($user);
}
else
{
$u=$conn->real_escape_string($user);
}
  $sqlconnected="select distinct u.ID from users u, relationships r where r.accepted = 1 and (r.usera={$user} or r.userb={$user})
        and if(r.usera={$user},u.ID=r.userb,u.ID=r.usera)";
$sqlpending="select distinct up.ID from users up, relationships rp where rp.accepted = 0 and (rp.usera={$user} or rp.userb={$user})
        and if(rp.usera={$user},up.ID=rp.userb,up.ID=rp.usera)";
$sqlsuggestions="select distinct us.ID from users us, relationships re where re.accepted=1 and us.ID not in ({$sqlpending}) and us.ID not in ({$sqlconnected}) and us.ID not in ({$user}) and ((re.usera in ({$sqlconnected}) and re.userb = us.ID ) or (re.userb in ({$sqlconnected}) and re.usera = us.ID)) - {$user}";
if($type=='professor')
$sql="select concat(p.fname,' ',p.lname) as suggestion_fullname, usr.ID, p.photo, if(p.type='professor',concat('<button class=\"suggestions\" onClick=\"create1Relationship(',usr.ID,',\'Mate\');\"><div id=\"create1',usr.ID,'Mate\">Add Mate</div></button>'),concat('<button class=\"suggestions\" onClick=\"create1Relationship(',usr.ID,',\'Pupil\');\"><div id=\"create1',usr.ID,'Pupil\">Add Pupil</div></button>')) as suggest_button from users usr, profile p where usr.ID=p.ID and usr.active=1 and usr.banned=0 and usr.deleted=0 and usr.ID not in ({$user})  and usr.ID in ({$sqlsuggestions}) and usr.banned=0 and usr.deleted=0 and usr.active=1 and usr.admin=0 order by rand() limit 3";
else
$sql="select concat(p.fname,' ',p.lname) as suggestion_fullname, usr.ID, p.photo, if(p.type='professor',concat('<button class=\"suggestions\" onClick=\"create1Relationship(',usr.ID,',\'Professor\');\"><div id=\"create1',usr.ID,'Professor\">Add Professor</div></button>'),concat('<button class=\"suggestions\" onClick=\"create1Relationship(',usr.ID,',\'Mate\');\"><div id=\"create1',usr.ID,'Mate\">Add Mate</div></button>')) as suggest_button from users usr, profile p where usr.ID=p.ID and usr.active=1 and usr.banned=0 and usr.deleted=0 and usr.ID not in ({$user}) and usr.ID in ({$sqlsuggestions}) and usr.banned=0 and usr.deleted=0 and usr.active=1 and usr.admin=0 order by rand() limit 3";
$result=$conn->query($sql) or trigger_error(mysql_error());
if($result->num_rows>=1)
{
	$text="<col align=\"left\"><col align=\"center\"><col align=\"center\"><caption>People you may know</caption> ";
	while($row=$result->fetch_array(MYSQLI_ASSOC))
	{
		$text.="<tr><td><div><img src=\"uploads/profilepics/small/".$row['photo']."\" title=\"".$row['suggestion_fullname']."\"/><br/><a href=\"profile/view/".$row['ID']."\">".$row['suggestion_fullname']."</a><br/>".$row['suggest_button']."</div></td></tr>";
	}
	echo $text;
}
else
{
$sqlcollege="select pro.college from profile pro where pro.ID = {$user}";
$result=$conn->query($sqlcollege) or trigger_error(mysql_error());
$college="";
if($result->num_rows==1)
{
while($row=$result->fetch_array(MYSQLI_ASSOC))
{
$college=$row['college'];
}
}
$sqlrandom="select distinct us.ID from users us,profile pr where pr.ID=us.ID and pr.college like '%{$college}%'";
if($type=='professor')
$sql="select concat(p.fname,' ',p.lname) as suggestion_fullname, usr.ID, p.photo, if(p.type='professor',concat('<button class=\"suggestions\" onClick=\"create1Relationship(',usr.ID,',\'Mate\');\"><div id=\"create1',usr.ID,'Mate\">Add Mate</div></button>'),concat('<button class=\"suggestions\" onClick=\"create1Relationship(',usr.ID,',\'Pupil\');\"><div id=\"create1',usr.ID,'Pupil\">Add Pupil</div></button>')) as suggest_button from users usr, profile p where usr.ID=p.ID and usr.active=1 and usr.banned=0 and usr.deleted=0 and usr.ID not in ({$user})  and usr.ID in ({$sqlrandom}) and usr.ID not in ({$sqlconnected}) and usr.ID not in ({$sqlpending}) and usr.banned=0 and usr.deleted=0 and usr.active=1 and usr.admin=0 order by rand() limit 3";
else
$sql="select concat(p.fname,' ',p.lname) as suggestion_fullname, usr.ID, p.photo, if(p.type='professor',concat('<button class=\"suggestions\" onClick=\"create1Relationship(',usr.ID,',\'Professor\');\"><div id=\"create1',usr.ID,'Professor\">Add Professor</div></button>'),concat('<button class=\"suggestions\" onClick=\"create1Relationship(',usr.ID,',\'Mate\');\"><div id=\"create1',usr.ID,'Mate\">Add Mate</div></button>')) as suggest_button from users usr, profile p where usr.ID=p.ID and usr.active=1 and usr.banned=0 and usr.deleted=0 and usr.ID not in ({$user}) and usr.ID in ({$sqlrandom}) and usr.ID not in ({$sqlconnected}) and usr.ID not in ({$sqlpending}) and usr.banned=0 and usr.deleted=0 and usr.active=1 and usr.admin=0 order by rand() limit 3";
$result=$conn->query($sql) or trigger_error(mysql_error());
if($result->num_rows>=1)
{
	$text="<col align=\"left\"><col align=\"center\"><col align=\"center\"><caption>People you may know</caption> ";
	while($row=$result->fetch_array(MYSQLI_ASSOC))
	{
		$text.="<tr><td><div><img src=\"uploads/profilepics/small/".$row['photo']."\" title=\"".$row['suggestion_fullname']."\"/><br/><a href=\"profile/view/".$row['ID']."\">".$row['suggestion_fullname']."</a><br/>".$row['suggest_button']."</div></td></tr>";
	}
	echo $text;
}
else
	echo 'no';
}
$conn->close();
exit;
?>