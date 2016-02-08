<?php require_once('..'.DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'bootstrap.config.php') ?>
<?php
	if(isset($_POST['submit'])){
		if(isset($_POST['user'])){
			$user = $database->escape_value($_POST['user']);
			$uid = User::get_id_by_uid($user);
			if(!is_bool($uid)){
				$sh_id = StockHolder::shid_by_uid($uid);
				if(!is_bool($sh_id)){
					$session->login($uid,$sh_id);
					header("Location: home.php");
				}
			}
		}
	}
?>
<!DOCTYPE html>
<html> 
	<head>
		<title>User Login</title>
		<style type="text/css">
		body{
			/*background-color:#80ff80;*/
			background-color: white;
		}
		input[type=text]{
			border:none;
			border-bottom: 3px solid red;
			border-radius : 5px;
			background-color: #bfff80;
			margin: 8px 0;
			padding : 1em;
			font-size: 1.3em;
		}
		input[type=submit]{
			border:none;
			border: 3px solid #003322;
			border-radius : 5px;
			background-color: #bfff80;
			margin: 8px 0;
			padding : 1em;
			font-size: 1.3em;
		}
		input[type=submit]:hover{
			border:none;
			border: 3px solid #ffffff;
			border-radius : 5px;
			background-color: #003322;
			margin: 8px 0;
			padding : 1em;
			font-size: 1.3em;
			color:white;
		}
		div.login{
			width:14%;
			margin:auto;
			margin-top:10%;
			border: #1a1a1a solid 2px;
			padding:3em;
			background-color:#bfff80;	border-radius:50px;
			box-shadow:12px 17px 10px black;
		}
		.center{
			margin-left: 5.6em;
		}
		</style>
	</head>
	<body>
		<div class="login">
			<form action="index.php" method="post">
				<p style="font-family:sans-serif;font-size:2em;">USER ID:</p>
				<input type="text" name="user" />
				<p class="center"><input type="submit" name="submit" value="Submit"></p>
			</form>	
		</div>
	</body>
</html>		
