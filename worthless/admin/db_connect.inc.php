<?php 

try
{
$con=mysqli_connect("localhost","root","","vsm_sid");

if (!$con)
  {die('Could not connect: ' . mysql_error());}
}
catch(Exception $ex)
{
echo 'Caught exception: ',  $ex->getMessage(), "\n";
}



?>