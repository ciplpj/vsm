<?php 
include('db_connect.inc.php');

try
{
global $flag;
$flag = 0;
if(isset($_POST['sid']))
{
	$stock_id = $_POST['sid'];
	//$arr = getstockprice($stock_id);
$arr = getstockprice($stock_id);
$json = json_encode($arr);
echo $json;
	
}

}
catch(Exception $ex)
{
 echo 'Caught exception: ',  $ex->getMessage(), "\n";

}


function getstockprice($par)
{
global $con;
 
$sql = 'SELECT `current_stock_price` as ct , `last_stock_price` as lt from stocks where stock_id ='.$par;
$query=mysqli_query($con,$sql);

if(!$row=mysqli_fetch_assoc($query))
{
throw new Exception('Error in SQL.');
}
mysqli_close($con);
if($row['ct'] > $row['lt'])
{
$direction = 'up';
}
else
{
$direction = 'down';
}

$ret = array($row['ct'] , $direction);
return $ret;
}
?>