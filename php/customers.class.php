<?php
/* 
 * 	FILE 			: customers.class.php
 * 	PROJECT 		: Northwind
 * 	PROGRAMMER 		: Ben Lorantfy
 * 	FIRST VERSION 	: 2014-12-22
 * 	DESCRIPTION 	: Contains the admin class.  
 */

//
// Requires
// --------
// Make sure working directory is root so paths point properly.
// Since php files should be placed in either root or root/php, 
// this checks if working directory is /php and if so moves up
//
if(basename(getcwd()) == "php") chdir("../");
require_once("php/connect.php");
require_once("php/ajax.php");
require_once("php/sanitize.php");
require_once("php/admin.class.php");

//
// Handle AJAX
// -----------
// Tests if page was requested with ajax. This can be spoofed, but that doesn't really matter
// If it was, call method specified in the post variable named "call" and echo return data
//
if(isset($_POST["call"]) && realpath(__FILE__) == realpath($_SERVER["SCRIPT_FILENAME"])){
	handleAJAX("Customers");	
}

/*
 * NAME 	: Customers
 *
 * PURPOSE 	: 
 */
class Customers{
	private $db;
	private $admin;
	
	function __construct(){
		$this->db = connect();
		$this->admin = new Admin();
	}
	
	function generateCustomersTable($requestedOrder="", $search="", $reverse=""){
		if($this->admin->isLogged()){
			$direction = "";
			
			if($reverse === true || $reverse == "true"){
				$direction = "DESC";
			}
			
			$order = "CompanyName";
			if($requestedOrder == "ContactName" || $requestedOrder == "City"){
				$order = $requestedOrder;
			}

			//
			// Prepare query
			// It's secure to use string concatenation here because the variables don't contain external information
			//
			$query = $this->db->prepare("SELECT CustomerID,CompanyName,ContactName,City FROM Customers WHERE CompanyName LIKE CONCAT('%',?,'%') ORDER BY $order $direction");

			$query->bind_param("s",$search);
			$query->execute();
			$query->store_result();
			
			$table = "";
			if($query->num_rows > 0){
				$query->bind_result($id,$companyName,$contactName,$city);
				while($query->fetch()){
					$table .= "<tr data-customer-id='$id'>";
					$table .= "<td><a href = 'customer.php?id=$id'>$id</a></td>";
					$table .= "<td><a href = 'customer.php?id=$id'>$companyName</a></td>";
					$table .= "<td><a href = 'customer.php?id=$id'>$contactName</a></td>";
					$table .= "<td><a href = 'customer.php?id=$id'>$city</a></td>";
					$table .= "</tr>"; 					
				}
			}else{
				$table = "
					<tr id = 'noResults'>
						<td colspan='4'>No Results</td>
					</tr>
				";				
			}
			
			return $table;		
		}
	}

	function customerData($id=""){
		if($this->admin->isLogged()){
			$result = $this->db->query("SELECT * FROM Customers WHERE CustomerID = '$id' LIMIT 1");
			return $result->fetch_assoc();
		}
	}

	function updateCustomer($id="",$contactName="",$contactTitle="",$address="",$city="",$region="",$postalCode="",$country="",$phone="",$fax=""){
		if($this->admin->isLogged()){	
			//
			// Sets all empty strings to null
			//
			foreach(func_get_args() as $key => $value){
				$$key = empty($value) ? NULL : $value;
			}
			
			//
			// Prepares statement
			//
			$query = $this->db->prepare("UPDATE Customers SET
				 ContactName = ?
				,ContactTitle = ?
				,Address = ?
				,City = ?
				,Region = ?
				,PostalCode = ?
				,Country = ?
				,Phone = ?
				,Fax = ?
				WHERE CustomerID = ?		
			");
			
			$query->bind_param("ssssssssss",$contactName,$contactTitle,$address,$city,$region,$postalCode,$country,$phone,$fax,$id);
			$query->execute();
			
			return true;
		}
	}

	function checkAvailability($id=""){
		if($this->admin->isLogged()){
			$available = false;
			
			$query = $this->db->prepare("SELECT CustomerID FROM Customers WHERE CustomerID = ?");
			$query->bind_param("s",$id);
			$query->execute();
			$query->store_result();
			if($query->num_rows == 0){
				$available = true;
			}
			
			return $available;
		}
	}

	function addCustomer($id="",$companyName="",$contactName="",$contactTitle="",$address="",$city="",$region="",$postalCode="",$country="",$phone="",$fax=""){
		if($this->admin->isLogged()){
			$success = false;
			if($this->checkAvailability($id)){					
				//
				// Sets all empty strings to null
				//
				foreach(func_get_args() as $key => $value){
					$$key = empty($value) ? NULL : $value;
				}
			
				$query = $this->db->prepare("INSERT INTO Customers 
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
					VALUES (?,?,?,?,?,?,?,?,?,?,?)
				");
				
				$query->bind_param("sssssssssss",$id,$companyName,$contactName,$contactTitle,$address,$city,$region,$postalCode,$country,$phone,$fax);
				$query->execute();
				$success = true;
			}
			return $success;
		}	
	}

}

?>