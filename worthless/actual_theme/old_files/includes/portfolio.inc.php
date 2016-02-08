<?php 
include('db_connect.inc.php');

$user_id = $_SESSION['vsm_user_id'];
$arr = getdetails($user_id);

$stock_arr = getstock($user_id);


$currentcash = $arr[0];
$currentholdings = $arr[1];

function getdetails($param){
global $con;
$ret = array();

$sql = "SELECT ROUND(current_cash,1) as cc , ROUND(holdings,1) as hh from players where user_id = '$param' ";
$query=mysqli_query($con,$sql);

if(!$query && !mysqli_num_rows($query))
{
	 echo mysqli_error();
}

$row = mysqli_fetch_assoc($query);
array_push($ret , $row['cc'] , $row['hh']);
return $ret;

}

function getstock($par)
{
global $con;
$ret = array();

$sql = "SELECT  b.stock_id, b.stock_name, SUM( number_of_stocks ) AS numb, ROUND(a.current_stock_price,1) AS csp, ROUND(SUM( amount ),1) AS amount , b.timestamp as timest
FROM transactions AS b
INNER JOIN stocks AS a ON a.stock_id = b.stock_id
WHERE user_id = '$par' AND b.type = 0
GROUP BY stock_id ORDER BY b.timestamp DESC";

$query=mysqli_query($con,$sql);

if(!$query && !mysqli_num_rows($query))
{
	 echo "Some error occured".mysqli_error();
}

if(mysqli_num_rows($query) > 0)
{
	$i =0; $curr = 0;
		while ($row = mysqli_fetch_assoc($query)) {
			
			$curr = $curr + ($row['numb'] * $row['csp']);
			$date = date_create($row['timest']);
			
			if($row['numb']!=0)
			{$arr[$i] =  array(($row['stock_id']-1) , $row['stock_name'] , $row['numb'] , $row['csp'] , $row['amount'] , date_format($date, 'd/m/y h:ia') , $curr);
				array_push($ret , $arr[$i]);
				}
				
			
			$i++;
		}
}
else
{
return "No rows found";
}

return $ret;
}

?>