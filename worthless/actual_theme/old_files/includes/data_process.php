<?php 
include('db_connect.inc.php');

try
{
global $flag;
$flag = 0;

$arr = getstockprice();

$json = json_encode($arr);
echo $json;
	

}
catch(Exception $ex)
{
 echo 'Caught exception: ',  $ex->getMessage(), "\n";

}


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
return $ret;
}
?>