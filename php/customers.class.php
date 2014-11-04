<?php

//
// Handle AJAX
// If ajax requested this file; not a file this was included into
//
if(isset($_POST["call"]) && __FILE__ == $_SERVER["SCRIPT_FILENAME"]){
	//
	// Require all classes used in this class
	//
	require_once("admin.class.php");
	require_once("utility.php");
	
	//
	// Start session
	// Create new class instance
	// Call method requested by ajax
	//
	session_start();
	$customers = new Customers("connect.php");
	$customers->$_POST["call"]();
}else{
	//
	// Require all classes used in this class
	//
	require_once("php/admin.class.php");
	require_once("php/utility.php");
}

class Customers{
	private $db = null;
	private $admin = null;
	
	function Customers($connect=null,$db=null){
		$this->db = connect($connect);
		$this->admin = new Admin($connect,$db);
	}
	
	function getTableRows($order="", $search="", $reverse=""){
		if($this->admin->isLogged()){
			require("sanitize.php");
			$reverse = is_string($reverse) ? $reverse == "true" : $reverse;
			$db = $this->db;
			$output = "";
			$direction = "";
					
			if($reverse){
				$direction = "DESC";
			}
			
			$result = $db->query("SELECT * FROM Customers WHERE CompanyName LIKE '%$search%' ORDER BY $order $direction");
			
	
			while($row = $result->fetch_assoc()){
				$id = $row["CustomerID"];
				$output .= "<tr>";
				$output .= "<td><a href = 'customer.php?id=$id'>$id</a></td>";
				$output .= "<td><a href = 'customer.php?id=$id'>" . $row["CompanyName"] . "</a></td>";
				$output .= "<td><a href = 'customer.php?id=$id'>" . $row["ContactName"] . "</a></td>";
				$output .= "<td><a href = 'customer.php?id=$id'>" . $row["City"] . "</a></td>";
				$output .= "<td style = 'text-align:center;'><input type = 'checkbox'></input></td>";
				$output .= "</tr>"; 
			}
			
			if($output == ""){
				$output = "
					<tr id = 'noResults'>
						<td colspan='5'>No Results</td>
					</tr>
				";
			}
			return ret($output,__FILE__,__FUNCTION__);			
		}
	}
	
	function getCustomer($id=""){
		if($this->admin->isLogged()){
			require("sanitize.php");
			$db = $this->db;
			$result = $db->query("SELECT * FROM Customers WHERE CustomerID = '$id' LIMIT 1");
			return ret($result->fetch_assoc(),__FILE__,__FUNCTION__);
		}
	}
	
	function updateCustomer($id="",$contactName="",$contactTitle="",$address="",$city="",$region="",$postalCode="",$country="",$phone="",$fax=""){
		if($this->admin->isLogged()){
			require("sanitize.php");
			$db = $this->db;
			
			$contactNameUpdate 	= $contactName 	!= "" ? "ContactName = '$contactName'" 	: "ContactName = NULL";
			$contactTitleUpdate = $contactTitle != "" ? "ContactTitle = '$contactTitle'": "ContactTitle = NULL";
			$addressUpdate 		= $address 		!= "" ? "Address = '$address'" 			: "Address = NULL";
			$cityUpdate			= $city 		!= "" ? "City = '$city'" 				: "City = NULL";
			$regionUpdate		= $region	 	!= "" ? "Region = '$region'" 			: "Region = NULL";
			$postalCodeUpdate	= $postalCode 	!= "" ? "PostalCode = '$postalCode'" 	: "PostalCode = NULL";
			$countryUpdate		= $country 		!= "" ? "Country = '$country'" 			: "Country = NULL";
			$phoneUpdate 		= $phone 		!= "" ? "Phone = '$phone'" 				: "Phone = NULL";
			$fax 				= $fax 			!= "" ? "Fax = '$fax'" 					: "Fax = NULL";
			
			$result = $db->query("UPDATE Customers SET 
				 $contactNameUpdate
				,$contactTitleUpdate
				,$addressUpdate
				,$cityUpdate
				,$regionUpdate
				,$postalCodeUpdate
				,$countryUpdate
				,$phoneUpdate
				,$fax
				WHERE CustomerID = '$id'
			");
			
			return ret(true,__FILE__,__FUNCTION__);
		}
	}

	function checkAvailability($id=""){
		if($this->admin->isLogged()){
			require("sanitize.php");
			$db = $this->db;
			$available = false;
			
			$result = $db->query("SELECT * FROM Customers WHERE CustomerID='$id'");
			if($result->num_rows == 0){
				$available = true;
			}
			return ret($available,__FILE__,__FUNCTION__);
		}
	}

	function addCustomer($id="",$companyName="",$contactName="",$contactTitle="",$address="",$city="",$region="",$postalCode="",$country="",$phone="",$fax=""){
		if($this->admin->isLogged()){
			$success = false;
			if($this->checkAvailability($id)){
				require("sanitize.php");
				$db = $this->db;			
					
				$id				= "'$id'";
				$companyName 	= $companyName 	!= "" ? "'$companyName'" 	: "NULL";
				$contactName 	= $contactName 	!= "" ? "'$contactName'" 	: "NULL";
				$contactTitle 	= $contactTitle != "" ? "'$contactTitle'"	: "NULL";
				$address 		= $address 		!= "" ? "'$address'" 		: "NULL";
				$city			= $city 		!= "" ? "'$city'" 			: "NULL";
				$region			= $region	 	!= "" ? "'$region'" 		: "NULL";
				$postalCode		= $postalCode 	!= "" ? "'$postalCode'" 	: "NULL";
				$country		= $country 		!= "" ? "'$country'" 		: "NULL";
				$phone 			= $phone 		!= "" ? "'$phone'" 			: "NULL";
				$fax 			= $fax 			!= "" ? "'$fax'" 			: "NULL";
				
				$result = $db->query("INSERT INTO Customers 
					(
						 CustomerID
						,CompanyName
						,ContactName
						,ContactTitle
						,Address
						,City
						,Region
						,PostalCode
						,Country
						,Phone
						,Fax
					)
					VALUES (
						 $id
						,$companyName
						,$contactName
						,$contactTitle
						,$address
						,$city
						,$region
						,$postalCode
						,$country
						,$phone
						,$fax
					)
				");
				
				if(!$result){
				    die('There was an error running the query [' . $db->error . ']');
				}
				$success = true;
			}
			return ret($success,__FILE__,__FUNCTION__);
		}	
	}
}



?>