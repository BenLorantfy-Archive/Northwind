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
	<?php include("head.php"); ?>
</head>
<body data-page = "customer">
	<div id = "site-container">
		<?php include("nav.php"); ?>
		<div id = "content-container">
			<?php
				$companyName 	= $customer["CompanyName"];
				$customerId		= $customer["CustomerID"];
				$contactName 	= $customer["ContactName"] == ""	? "unspecified" : $customer["ContactName"]; 
				$contactTitle 	= $customer["ContactTitle"] == ""	? "unspecified" : $customer["ContactTitle"];
				$address 		= $customer["Address"] == "" 		? "unspecified" : $customer["Address"]; 
				$city 			= $customer["City"] == ""			? "unspecified" : $customer["City"];
				$region			= $customer["Region"] == "" 		? "unspecified" : $customer["Region"];
				$postalCode 	= $customer["PostalCode"] == ""		? "unspecified" : $customer["PostalCode"];
				$country 		= $customer["Country"] == ""		? "unspecified" : $customer["Country"]; 
				$phone 			= $customer["Phone"] == ""			? "unspecified" : $customer["Phone"]; 
				$fax 			= $customer["Fax"] == ""			? "unspecified" : $customer["Fax"];
			?>
			<h3><?php echo $companyName; ?></h3>
			<h4 id = "customer-id"><?php echo $customerId; ?></h4>
			
			<div class = "infoContainer">
				<label class = "infoLabel">Contact Name:</label> 
				<span id = "contact-name" class = "info"><?php echo $contactName; ?></span>
				<input id = "contact-name-input" class = "infoTextbox dark-textbox" style = "display:none;" type = "text" value="<?php echo $contactName == "unspecified" ? "" : $contactName ?>"/>
				<span class = "createInfoHeight"></span>
			</div>

			<div class = "infoContainer">
				<label class = "infoLabel">Contact Title:</label> 
				<span id = "contact-title" class = "info"><?php echo $contactTitle; ?></span>
				<input id = "contact-title-input" class = "infoTextbox dark-textbox" style = "display:none;" type = "text" value="<?php echo $contactTitle == "unspecified" ? "" : $contactTitle ?>"/>
				<span class = "createInfoHeight"></span>
			</div>
			
			<div class = "infoContainer">
				<label class = "infoLabel">Address:</label> 
				<span id = "address" class = "info"><?php echo $address; ?></span>
				<input id = "address-input" class = "infoTextbox dark-textbox" style = "display:none;" type = "text" value="<?php echo $address == "unspecified" ? "" : $address; ?>"/>
				<span class = "createInfoHeight"></span>
			</div>
			
			<div class = "infoContainer">
				<label class = "infoLabel">City:</label> 
				<span class = "info"><?php echo $city; ?></span>
				<input id = "city-input" class = "infoTextbox dark-textbox" style = "display:none;" type = "text" value="<?php echo $city == "unspecified" ? "" : $city; ?>"/>
				<span class = "createInfoHeight"></span>
			</div>
			
			<div class = "infoContainer">
				<label class = "infoLabel">Region:</label> 
				<span id = "region" class = "info"><?php echo $region; ?></span>
				<input id = "region-input" class = "infoTextbox dark-textbox" style = "display:none;" type = "text" value="<?php echo $region == "unspecified" ? "" : $region; ?>"/>
				<span class = "createInfoHeight"></span>
			</div>
			
			<div class = "infoContainer">
				<label class = "infoLabel">Postal Code:</label>	
				<span id = "postal-code" class = "info"><?php echo $postalCode; ?></span>
				<input id = "postal-code-input" class = "infoTextbox dark-textbox" style = "display:none;" type = "text" value="<?php echo $postalCode == "unspecified" ? "" : $postalCode; ?>"/>
				<span class = "createInfoHeight"></span>
			</div>
			
			<div class = "infoContainer">
				<label class = "infoLabel">Country:</label> 
				<span id = "country" class = "info"><?php echo $country; ?></span>
				<input id = "country-input" class = "infoTextbox dark-textbox" style = "display:none;" type = "text" value="<?php echo $country == "unspecified" ? "" : $country; ?>"/>
				<span class = "createInfoHeight"></span>
			</div>
			
			<div class = "infoContainer">
				<label class = "infoLabel">Phone:</label> 
				<span id = "phone" class = "info"><?php echo $phone; ?></span>
				<input id = "phone-input" class = "infoTextbox dark-textbox" style = "display:none;" type = "text" value="<?php echo $phone == "unspecified" ? "" : $phone; ?>"/>
				<span class = "createInfoHeight"></span>
			</div>
			
			<div class = "infoContainer">
				<label class = "infoLabel">Fax:</label>
				<span id = "fax" class = "info"><?php echo $fax; ?></span>
				<input id = "fax-input" class = "infoTextbox dark-textbox" style = "display:none;" type = "text" value="<?php echo $fax == "unspecified" ? "" : $fax; ?>"/>
				<span class = "createInfoHeight"></span>
			</div>
			
			<div id = "edit-tools">
				<div id = "edit-button" class = "dark-button">Edit</div>
				<div id = "cancel-button" class = "dark-button" style = "display:none;">Cancel</div>
				<div id = "save-button" class = "dark-button" style = "display:none;">Save</div>			
			</div>
			
			<a id = "back-button" class = "dark-button" href = "list.php">Back</a>
		</div>
	</div>
</body>
</html>