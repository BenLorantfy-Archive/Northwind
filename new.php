<?php
	session_start();
	include("php/admin.class.php");
	$admin = new Admin("php/connect.php");
	if(!$admin->isLogged()){
		header('Location: ../');
		die();
	}
?>

<!DOCTYPE>
<html>
<head>
	<?php include("php/header.php"); ?>
</head>
<body data-page = "new">
	<div id = "site-container">
		<div id = "title-container">
			<h1>Northwind</h1>
			<h2>Admin Panel</h2>	
		</div>
		<div id = "content-container">
			<h3>Add New Customer</h3>
		</div>	
	</div>
</body>
</html>