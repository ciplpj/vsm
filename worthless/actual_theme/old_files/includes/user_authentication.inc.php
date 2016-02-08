<?php

function getuser_id()
{
$x = $_SESSION['vsm_user_id'];
if (!$x) {
        throw new Exception('No session available.');
    }
return $x;
}

function getuser_name()
{
$x = $_SESSION['vsm_user_name'];
if (!$x) {
        throw new Exception('No session available.');
    }
return $x;
}


try
{
 $id = getuser_id();
 $name = getuser_name();

}
catch(Exception $ex)
{
 echo 'Caught exception: ',  $ex->getMessage(), "\n";
}




?>