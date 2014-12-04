<?php
	session_start();
	require_once("php/admin.class.php");
	
	$admin = new Admin();
	if(!$admin->isLogged()){
		header('Location: ../');
		die();
	}
?>

<!DOCTYPE>
<html>
<head>
	<?php include("php/head.php"); ?>
</head>
<body data-page = "new">
	<div id = "site-container">
		<?php include("php/nav.php"); ?>
		<div id = "content-container">
			<h3>Add New Customer</h3>
			<div class = "infoContainer">
				<label class = "infoLabel">Company Name:</label> 
				<input id = "company-name-input" class = "infoTextbox dark-textbox" type = "text"/>
				<span class = "createInfoHeight"></span>
			</div>

			<div class = "infoContainer">
				<label class = "infoLabel">Customer Id:</label> 
				<input id = "customer-id-input" class = "infoTextbox dark-textbox" type = "text"/>
				<span class = "createInfoHeight"></span>
			</div>

			<div class = "infoContainer">
				<label class = "infoLabel">Contact Name:</label> 
				<input id = "contact-name-input" class = "infoTextbox dark-textbox" type = "text"/>
				<span class = "createInfoHeight"></span>
			</div>
			
			<div class = "infoContainer">
				<label class = "infoLabel">Contact Title:</label> 
				<input id = "contact-title-input" class = "infoTextbox dark-textbox" type = "text"/>
				<span class = "createInfoHeight"></span>
			</div>
			
			<div class = "infoContainer">
				<label class = "infoLabel">Address:</label> 
				<input id = "address-input" class = "infoTextbox dark-textbox" type = "text"/>
				<span class = "createInfoHeight"></span>
			</div>
			
			<div class = "infoContainer">
				<label class = "infoLabel">City:</label> 
				<input id = "city-input" class = "infoTextbox dark-textbox" type = "text"/>
				<span class = "createInfoHeight"></span>
			</div>
			
			<div class = "infoContainer">
				<label class = "infoLabel">Region:</label> 
				<input id = "region-input" class = "infoTextbox dark-textbox" type = "text"/>
				<span class = "createInfoHeight"></span>
			</div>
			
			<div class = "infoContainer">
				<label class = "infoLabel">Postal Code:</label>	
				<input id = "postal-code-input" class = "infoTextbox dark-textbox" type = "text"/>
				<span class = "createInfoHeight"></span>
			</div>
			
			<div class = "infoContainer">
				<label class = "infoLabel">Country:</label> 
				<input id = "country-input" class = "infoTextbox dark-textbox" type = "text"/>
				<span class = "createInfoHeight"></span>
			</div>
			
			<div class = "infoContainer">
				<label class = "infoLabel">Phone:</label> 
				<input id = "phone-input" class = "infoTextbox dark-textbox" type = "text"/>
				<span class = "createInfoHeight"></span>
			</div>
			
			<div class = "infoContainer">
				<label class = "infoLabel">Fax:</label>
				<input id = "fax-input" class = "infoTextbox dark-textbox" type = "text"/>
				<span class = "createInfoHeight"></span>
			</div>
			<div id = "add-button" class = "dark-button">Add</div>
			<a id = "back-button" class = "dark-button" href = "list.php">Back</a>
		</div>	
	</div>
</body>
</html>