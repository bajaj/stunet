<?php

$u=stripslashes($_POST['email']);
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

$sql="select * from users where email = '".$u."' and ID not in ({$_POST['id']})";
$result=$conn->query($sql) or trigger_error('Error executing query');
if($result->num_rows==1)
echo 0;
else
echo 1;
$conn->close();
exit;
?>