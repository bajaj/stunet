<?php
$g=stripslashes($_POST['gid']);
$u=stripslashes($_POST['uid']);
include('../config.php');
$conn=new mysqli($configs['db_host_sn'],$configs['db_user_sn'],$configs['db_pass_sn'],$configs['db_name_sn']) or trigger_error('Error connecting to host');
if(version_compare(phpversion(),"4.3.0")=="-1")
{
	$u=$conn->escape_string($u);
}
else
{
$u=$conn->real_escape_string($u);
}

$sql="select g_default from profile where ID = '".$u."'";
$result=$conn->query($sql);
$row=$result->fetch_array(MYSQLI_ASSOC);
if($g==$row['g_default'])
echo 'already';
else
{
	$sql="update profile set g_default = ".$g." where ID = ".$u;
	$conn->query($sql);
	if($conn->affected_rows==1)
	echo 'yes';
	else
	echo 'no';
}
$conn->close();
exit;
?>