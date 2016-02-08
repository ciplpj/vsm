<?php
if(isset($_POST['key']) && isset($_POST['user']))
{
$flag = 0;
$user = $_POST['user'];
$key = $_POST['key'];

$con=mysqli_connect("localhost","root","","vsm");


	
	$hello = md5($key."3022");
	
	
	//$sql1 = "SELECT password from key where sal";
	//$query1=mysqli_query($con,$sql1);
	//if(mysqli_num_rows($query1)>0)
	if($hello == "89607bb54d533743535e2c9bdf26b114")
	{$flag = 1;
	session_start();
	$_SESSION['admin'] = "965";
	}
	else $flag = 0;

	echo $flag;
}

?>