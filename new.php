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
		
	</div>
</body>
</html>