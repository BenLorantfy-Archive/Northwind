<?php
	session_start();
	include("php/admin.class.php");
	$admin = new Admin("php/connect.php");
	if($admin->isLogged()){
		header("Location: admin.php");
		die();
	}
	
?>
<!DOCTYPE html>
<html>
<head>
	<?php include("php/header.php"); ?>
</head>
<body data-page = "login">
	<div id = "login-container">
		 <input id = "username" class = "dark-textbox" type = "text"></input>
		 <input id = "password" class = "dark-textbox" type = "password"></input>
		 <input id = "login-button" class = "dark-textbox" type = "button"  value = "Login"></input>
	</div>
</body>
</html>