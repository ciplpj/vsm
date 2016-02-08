<?php
if(isset($_POST['unique']) && isset($_POST['user']))
{
$flag = 0;
$user = $_POST['user'];
$key = $_POST['unique'];

$con=mysqli_connect("localhost","vsm_user","F2P3sNGV8asBdpTq","vsm");


	
	$hello = $key; //md5($key."3022");
	
	
	//$sql1 = "SELECT password from key where sal";
	//$query1=mysqli_query($con,$sql1);
	//if(mysqli_num_rows($query1)>0)
	if($hello == "1")
	{$flag = 1;
	session_start();
	$_SESSION['admin'] = "965";
	include('update.php');
	//header('Location: update.php');
	}
	else $flag = 0;

	echo $flag;
}

?>

<!DOCTYPE html>
<html class="not-ie" lang="en"> <!--<![endif]-->
<head>
	<title>Login Access</title>
	
</head>
<body>

<script src="../js/jquery-1.7.2.min.js"></script>
<!--script src="abcd.js"></script-->
<form action="index.php"  method="post" id="kl">
Enter id:
<input type="text" id="user" name="user" autocomplete="off" />

Enter unique ID:
<input type="password" id="unique" name="unique" autocomplete="off" />
<input type="submit" value="Submit">
</form>	
</body>
</html>		

