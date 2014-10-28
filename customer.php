<?php
	session_start();
	include("php/admin.class.php");
	include("php/customers.class.php");
	
	$admin = new Admin("php/connect.php");
	if(!$admin->isLogged()){
		header('Location: ./');
		die();
	}
	
	$customers = new Customers("php/connect.php");
	$customer = $customers->getCustomer($_GET["id"]);
?>

<!DOCTYPE>
<html>
<head>
	<?php include("php/head.php"); ?>
</head>
<body data-page = "customer">
	<div id = "site-container">
		<?php include("php/nav.php"); ?>
		<div id = "content-container">
			<h3><?php echo $customer["CompanyName"]; ?></h3>
			<h4><?php echo $customer["CustomerID"]; ?></h4>
		</div>
	</div>
</body>
</html>