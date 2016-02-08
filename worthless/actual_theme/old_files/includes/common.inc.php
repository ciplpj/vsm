<?php

include('db_connect.inc.php');

session_start();

//$_SESSION['vsm_user_id'] = 1;;
//$_SESSION['vsm_user_name'] = "Hanny";

include('user_authentication.inc.php');

function getstockprice()
{
global $con;
 
$sql = "SELECT ROUND(current_stock_price, 1) as ct , ROUND(last_stock_price, 1) as lt from stocks";
$query=mysqli_query($con,$sql);

if(!$query && !mysqli_num_rows($query))
{
	 throw new Exception('Error in SQL');
}

$i = 1;
$ret = array();
while ($row = mysqli_fetch_assoc($query)) {
	if($row['ct'] > $row['lt'])
		{
			$direction = 'up';
			$color = 'green';
		}
		else
		{
			$direction = 'down';
			$color = 'red';
		}
    $arr[$i] =  array($row['ct'] , $direction , $color);
	array_push($ret , $arr[$i]);
	$i++;
}
mysqli_close($con);
return $ret;
}

try
{
$arr = getstockprice();

for($i =0;$i<10 ; $i++)
	{
		$stock_price[$i] = $arr[$i][0];
		$direction[$i] = $arr[$i][1];
		$color[$i] = $arr[$i][2];
	}
}
catch(Exception $ex)
{
 echo 'Caught exception: ',  $ex->getMessage(), "\n";

}

?>