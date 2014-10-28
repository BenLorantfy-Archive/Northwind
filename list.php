<?php
	session_start();
	include("php/admin.class.php");
	$admin = new Admin("php/connect.php");

	if(!$admin->isLogged()){
		header("Location: ./");
		die();
	}
?>

<!DOCTYPE>
<html>
<head>
	<?php include("php/head.php"); ?>
</head>
<body data-page = "list">
	<div id = "site-container">
		<?php include("php/nav.php"); ?>
		<div id = "content-container">
			<h3>Customer List</h3>
			<div id = "tools">
				<input type = "text" id = "search" class = "dark-textbox" placeholder="Filter"></input><a id = "new" href = "new.php" class = "dark-button">+</a><div id = "delete" class = "dark-button">-</div>
			</div>
			<table id = "recordTable">
				<thead>
					<tr>
						<td id = "IDHeader">ID</td>
						<td id = "companyHeader" data-field = "CompanyName">Company <span class = "arrow">&#x25B2;</span></td>
						<td id = "contactHeader" data-field = "ContactName">Contact <span class = "arrow"></span></td>
						<td id = "cityHeader" data-field = "City">City <span class = "arrow"></span></td>
						<td id = "checkAll"><input type = "checkbox"/></td>
					</tr>
				</thead>
				<tbody id = "records">
					<?php
						include("php/customers.class.php");
						$customers = new Customers("php/connect.php");
						echo $customers->getTableRows("CompanyName","",false);
					?>
				</tbody>
			</table>
		</div>
	</div>
</body>
</html>