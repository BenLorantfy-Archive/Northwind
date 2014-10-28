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
	<?php include("head.php"); ?>
</head>
<body data-page = "new">
	<div id = "site-container">
		<?php include("nav.php"); ?>
		<div id = "content-container">
			<h3>Add New Customer</h3>
			<a id = "back-button" class = "dark-button" href = "list.php">Back</a>
		</div>	
	</div>
</body>
</html>