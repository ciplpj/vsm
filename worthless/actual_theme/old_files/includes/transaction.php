<?php
include('db_connect.inc.php');
session_start();

if( isset($_POST['stock_id'])  && isset($_POST['stock_name'])  && isset($_POST['number'])  && isset($_POST['present'])  && isset($_POST['amount']) && isset($_POST['type']) )
{

$ret = array();

$user_id = $_SESSION['vsm_user_id'];

$stock_id = $_POST['stock_id'];
$stock_name = $_POST['stock_name'];
$number = $_POST['number'];
$present = $_POST['present'];
$amount = $_POST['amount'];
$init_current_cash = get_current_cash($user_id);   //get current cash
$type = $_POST['type'];

if($type ==0)
{
$balance = $init_current_cash - $amount;
}
else
{
updatetrans($stock_id , $number);
$balance = $init_current_cash + $amount;
}

if($balance <0) $allow = 0;
else $allow = 1;


$success = 0;

if($allow ==1){
	
	global $con;
	$err = 0;

	$sql = "INSERT into transactions (id,user_id , stock_id , stock_name , number_of_stocks , present_stock_price , amount , balance , timestamp , type) values (NULL , '$user_id', '$stock_id', '$stock_name', '$number', '$present', '$amount', '$balance', CURRENT_TIMESTAMP, '$type')";

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

array_push($ret ,$user_id , $stock_id , $stock_name , $number , $present , $amount , $init_current_cash , $type , $allow ,$success, $err);
$json = json_encode($ret);
echo $json;
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

function updatetrans($par1 , $par2)
{
	global $con;

	$sql = "UPDATE transactions set number_of_stocks = number_of_stocks -'$par2' , timestamp = CURRENT_TIMESTAMP where stock_id = '$par1' AND type = 0 LIMIT 1 ";
	$query=mysqli_query($con,$sql);

	if(!$query)
	{
		echo('Error in SQL');
	}
}

?>






