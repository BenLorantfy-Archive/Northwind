<?php
	session_start();
	require_once("php/admin.class.php");
	require_once("php/customers.class.php");
	
	$admin = new Admin();
	if(!$admin->isLogged()){
		header("Location: ./");
		die();
	}
	
	$customers = new Customers();
	$table = $customers->generateCustomersTable("CompanyName","",false);
?>

<!DOCTYPE>
<html>
<head>
	<?php include("php/head.php"); ?>
</head>
<body data-page = "list" <?php if($table === false) echo "data-error='true'"?>>
	<div id = "site-container">
		<?php include("php/nav.php"); ?>
		<div id = "content-container">
			<?php 
				//
				// Only try to generate page if generateCustomersTable succeded
				//
				if($table !== false){ 					
			?>
				<h3>Customer List</h3>
				<div id = "tools">
					<input type = "text" id = "search" class = "dark-textbox" placeholder="Filter"></input><a id = "new" href = "new.php" class = "dark-button">+</a>
				</div>
				<table id = "recordTable">
					<thead>
						<tr>
							<td id = "IDHeader">ID</td>
							<td id = "companyHeader" data-field = "CompanyName">Company <span class = "arrow">&#x25B2;</span></td>
							<td id = "contactHeader" data-field = "ContactName">Contact <span class = "arrow"></span></td>
							<td id = "cityHeader" data-field = "City">City <span class = "arrow"></span></td>
						</tr>
					</thead>
					<tbody id = "records">
						<?php
							echo $table;
						?>
					</tbody>
				</table>
			<?php } ?>
		</div>	
	</div>
</body>
</html>