<?php

$u=stripslashes($_POST['pwcheck']);
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

$sql="select password,username from users where ID = {$_POST['id']} ";

$result=$conn->query($sql) or trigger_error('Error executing query');
$rows=$result->fetch_array(MYSQLI_ASSOC);
$pwentered=md5($u.$rows['username']);
if($pwentered==$rows['password'])
echo 1;
else
echo 0;
$conn->close();
exit;
?>