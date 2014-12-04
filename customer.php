<?php
	session_start();
	require_once("php/admin.class.php");
	require_once("php/customers.class.php");
	
	$admin = new Admin();
	if(!$admin->isLogged()){
		header('Location: ./');
		die();
	}
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
			<?php
				$customers = new Customers();
				$customer = $customers->customerData($_GET["id"]);
				array_walk($customer, function(&$item){
					if($item == ""){
						$item = "unspecified";
					}
				});
				extract($customer);
			?>
			<h3><?php echo $CompanyName; ?></h3>
			<h4 id = "customer-id"><?php echo $CustomerID; ?></h4>
			
			<div class = "infoContainer">
				<label class = "infoLabel">Contact Name:</label> 
				<span id = "contact-name" class = "info"><?php echo $ContactName; ?></span>
				<input id = "contact-name-input" class = "infoTextbox dark-textbox" style = "display:none;" type = "text" value="<?php echo $ContactName == "unspecified" ? "" : $ContactName ?>"/>
				<span class = "createInfoHeight"></span>
			</div>

			<div class = "infoContainer">
				<label class = "infoLabel">Contact Title:</label> 
				<span id = "contact-title" class = "info"><?php echo $ContactTitle; ?></span>
				<input id = "contact-title-input" class = "infoTextbox dark-textbox" style = "display:none;" type = "text" value="<?php echo $ContactTitle == "unspecified" ? "" : $ContactTitle ?>"/>
				<span class = "createInfoHeight"></span>
			</div>
			
			<div class = "infoContainer">
				<label class = "infoLabel">Address:</label> 
				<span id = "address" class = "info"><?php echo $Address; ?></span>
				<input id = "address-input" class = "infoTextbox dark-textbox" style = "display:none;" type = "text" value="<?php echo $Address == "unspecified" ? "" : $Address; ?>"/>
				<span class = "createInfoHeight"></span>
			</div>
			
			<div class = "infoContainer">
				<label class = "infoLabel">City:</label> 
				<span class = "info"><?php echo $City; ?></span>
				<input id = "city-input" class = "infoTextbox dark-textbox" style = "display:none;" type = "text" value="<?php echo $City == "unspecified" ? "" : $City; ?>"/>
				<span class = "createInfoHeight"></span>
			</div>
			
			<div class = "infoContainer">
				<label class = "infoLabel">Region:</label> 
				<span id = "region" class = "info"><?php echo $Region; ?></span>
				<input id = "region-input" class = "infoTextbox dark-textbox" style = "display:none;" type = "text" value="<?php echo $Region == "unspecified" ? "" : $Region; ?>"/>
				<span class = "createInfoHeight"></span>
			</div>
			
			<div class = "infoContainer">
				<label class = "infoLabel">Postal Code:</label>	
				<span id = "postal-code" class = "info"><?php echo $PostalCode; ?></span>
				<input id = "postal-code-input" class = "infoTextbox dark-textbox" style = "display:none;" type = "text" value="<?php echo $PostalCode == "unspecified" ? "" : $PostalCode; ?>"/>
				<span class = "createInfoHeight"></span>
			</div>
			
			<div class = "infoContainer">
				<label class = "infoLabel">Country:</label> 
				<span id = "country" class = "info"><?php echo $Country; ?></span>
				<input id = "country-input" class = "infoTextbox dark-textbox" style = "display:none;" type = "text" value="<?php echo $Country == "unspecified" ? "" : $Country; ?>"/>
				<span class = "createInfoHeight"></span>
			</div>
			
			<div class = "infoContainer">
				<label class = "infoLabel">Phone:</label> 
				<span id = "phone" class = "info"><?php echo $Phone; ?></span>
				<input id = "phone-input" class = "infoTextbox dark-textbox" style = "display:none;" type = "text" value="<?php echo $Phone == "unspecified" ? "" : $Phone; ?>"/>
				<span class = "createInfoHeight"></span>
			</div>
			
			<div class = "infoContainer">
				<label class = "infoLabel">Fax:</label>
				<span id = "fax" class = "info"><?php echo $Fax; ?></span>
				<input id = "fax-input" class = "infoTextbox dark-textbox" style = "display:none;" type = "text" value="<?php echo $Fax == "unspecified" ? "" : $Fax; ?>"/>
				<span class = "createInfoHeight"></span>
			</div>
			
			<div id = "edit-tools">
				<div id = "edit-button" class = "dark-button">Edit</div>
				<div id = "cancel-button" class = "dark-button" style = "display:none;">Cancel</div>
				<div id = "save-button" class = "dark-button" style = "display:none;">Save</div>	
				<a id = "back-button" class = "dark-button" href = "list.php">Back</a>		
			</div>
					
		</div>
	</div>
</body>
</html>