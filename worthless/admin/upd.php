<?php
include('db_connect.inc.php');

ini_set('max_execution_time', 300); 

if( isset($_POST['st1']) && isset($_POST['st2']) && isset($_POST['st3']) && isset($_POST['st4']) && isset($_POST['st5']) && isset($_POST['st6']) && isset($_POST['st7']) && isset($_POST['st8']) && isset($_POST['st9']) && isset($_POST['st10']))
{
global $con;
$rand_num = 1 ;

for($i=1;$i<11;$i++)
{
$rand_num = $_POST['st'.$i];
$sql = "UPDATE stocks set last_stock_price = current_stock_price , current_stock_price = current_stock_price + '$rand_num' , stock_history = CONCAT(stock_history , ',' ,current_stock_price )  , timestamp = CURRENT_TIMESTAMP where stock_id = '$i'";
	$query=mysqli_query($con,$sql);

	if(!$query)
	{
		echo($con->error);
	}
}
	
echo "Done",$i;
}
else
echo "No";



?>