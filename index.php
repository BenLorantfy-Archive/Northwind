<?php
	session_start();
	include("php/admin.class.php");
	$admin = new Admin("php/connect.php");
	if($admin->isLogged()){
		if(isset($_GET["logout"])){
			$admin->logout();
			header("Location: ./");
		}else{
			header("Location: list.php");
			die();			
		}
	}
	
?>
<!DOCTYPE html>
<html>
<head>
	<?php include("head.php"); ?>
</head>
<body data-page = "login">
	<div id = "login-container">
		<h1>Northwind<br/>Admin Panel</h1>
		<input id = "username" class = "dark-textbox" type = "text"></input>
		<input id = "password" class = "dark-textbox" type = "password"></input>
		<input id = "login-button" class = "dark-textbox" type = "button"  value = "Login"></input>
	</div>
</body>
</html>