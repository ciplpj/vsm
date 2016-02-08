<?php
include('db_connect.inc.php');
session_start();
if(isset($_POST['product_id']))
{
$user_id = $_SESSION['vsm_user_id'];
$user_name = $_SESSION['vsm_user_name'];
$product_id = $_POST['product_id'];
$current_cash = get_current_cash($user_id);
$amount = get_product_price($product_id);
$balance = $current_cash - $amount;

if($balance <0) $allow = 0;
else $allow = 1;


$success = 0;

if($allow ==1){
	
	global $con;
	$err = 0;

	$sql = "INSERT into redeem_req (user_id , user_name , product_id) values ('$user_id', '$user_name', '$product_id')";

	$sql1 = "UPDATE players set current_cash = '$balance' where user_id = '$user_id'";
	
	$query=mysqli_query($con,$sql);
	$query1=mysqli_query($con,$sql1);

	if(!$query && !$query1)
	{
		$err = mysqli_error();
		$allow = 0;
		$success = 0;
	}
	else $success = 1;


}
}
else
echo 0;

function get_current_cash($param)
{

	global $con;

	$sql = 'SELECT current_cash as ct from players where user_id = '.$param;
	$query=mysqli_query($con,$sql);

	if(!$query && !mysqli_num_rows($query))
	{
		 echo('Error in SQL');
	}

	$row = mysqli_fetch_assoc($query);
	return $row['ct'];

}

function get_product_price($param)
{

	global $con;

	$sql = 'SELECT price from redeem where product_id = '.$param;
	$query=mysqli_query($con,$sql);

	if(!$query && !mysqli_num_rows($query))
	{
		 echo('Error in SQL');
	}

	$row = mysqli_fetch_assoc($query);
	return $row['price'];

}
?>