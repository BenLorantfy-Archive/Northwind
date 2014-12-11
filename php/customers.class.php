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
require_once("php/admin.class.php");

//
// Handle AJAX
// -----------
// Tests if page was requested with ajax. This can be spoofed, but that doesn't really matter because
// ajax calls are treated the same as regular calls, and user inputs aren't trusted at any point
// If it was ajax, call method specified in the post variable named "call" and echo return data
// See ajax.php for more info
//
if(isset($_POST["call"]) && realpath(__FILE__) == realpath($_SERVER["SCRIPT_FILENAME"])){
	handleAJAX("Customers");	
}

/*
 *
 * NAME 	: Customers
 *
 * PURPOSE 	: Contains methods used to interact with customers. (i.e. remove, edit, add, display)
 *
 */
class Customers{
	private $db;
	private $admin;
	
	function __construct(){
		$this->db = connect();
		$this->admin = new Admin();
	}
	
	function generateCustomersTable($requestedOrder="", $search="", $reverse=""){
		//
		// Don't run function if db failed to connect or admin is not logged
		// This makes the function easier to read and avoids excessive if statements
		// Called a gaurd statement in Fowler's refactoring
		//
		// According to code complete:
		// "Use a return when it enhances readability. In certain routines, once you know the answer, you want to return it to the calling
		// routine immediately. If the routine is defined in such a way that it doesn't require any cleanup, not returning immediately means 
		// that you have to write more code."
		//
		if($db->connect_errno > 0) return false;
		if(!$this->admin->isLogged()) return false;

		$success = false;

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
		// It's safe to use string concatenation here because the concatenated variables don't contain external information and
		// are not used as value
		//
		$query = $this->db->prepare("SELECT CustomerID,CompanyName,ContactName,City FROM Customers WHERE CompanyName LIKE CONCAT('%',?,'%') ORDER BY $order $direction");

		if($query !== false){
			if($query->bind_param("s",$search)){
				if($query->execute()){
					if($query->store_result()){
						$table = "";
						if($query->num_rows > 0){
							if($query->bind_result($id,$companyName,$contactName,$city)){
								$success = true;
								while($query->fetch()){
									$table .= "<tr data-customer-id='$id'>";
									$table .= "<td><a href = 'customer.php?id=$id'>$id</a></td>";
									$table .= "<td><a href = 'customer.php?id=$id'>$companyName</a></td>";
									$table .= "<td><a href = 'customer.php?id=$id'>$contactName</a></td>";
									$table .= "<td><a href = 'customer.php?id=$id'>$city</a></td>";
									$table .= "</tr>"; 					
								}								
							}
						}else{
							$success = true;
							$table = "
								<tr id = 'noResults'>
									<td colspan='4'>No Results</td>
								</tr>
							";				
						}						
					}
				}
			}
		}

		if($success){
			return $table;
		}else{
			return $success;
		}
	}

	function customerData($id=""){
	
		//
		// Don't continue if db failed to connect or admin is not logged
		//
		if($db->connect_errno > 0) return false;
		if(!$this->admin->isLogged()) return false;
		$success = false;
		
		$query = $this->db->prepare("
			SELECT 
				   CompanyName
			 	  ,ContactName
			 	  ,ContactTitle
			 	  ,Address
			 	  ,City
			 	  ,Region
			 	  ,PostalCode
			 	  ,Country
			 	  ,Phone
			 	  ,Fax
			 FROM Customers WHERE CustomerID = ? LIMIT 1
		");
			
		if($query !== false){
			if($query->bind_param("s",$id)){
				if($query->execute()){
					if($query->store_result()){
						if($query->bind_result($companyName,$contactName,$contactTitle,$address,$city,$region,$postalCode,$country,$phone,$fax)){
							$success = $query->fetch();
						}
					}
				}
			}
		}			
		$success = $query->num_rows > 0;
		
		if($success){			
			return array(
				 "CustomerID" => $id
				,"CompanyName" => $companyName
				,"ContactName" => $contactName
				,"ContactTitle" => $contactTitle
				,"Address" => $address
				,"City" => $city
				,"Region" => $region
				,"PostalCode" => $postalCode
				,"Country" => $country
				,"Phone" => $phone
				,"Fax" => $fax
			);					
		}else{
			return false;
		}	
	}

	function updateCustomer(
		 $id=""
	    ,$companyName=""
	    ,$contactName=""
	    ,$contactTitle=""
	    ,$address=""
	    ,$city=""
	    ,$region=""
	    ,$postalCode=""
	    ,$country=""
	    ,$phone=""
	    ,$fax=""
	){
		//
		// Don't continue if db failed to connect or admin is not logged
		//
		if($db->connect_errno > 0) return false;
		if(!$this->admin->isLogged()) return false;
		
		$success = false;
		
		//
		// Sets all empty strings to null
		//
		foreach(func_get_args() as $key => $value){
			$$key = empty($value) ? NULL : $value;
		}
		
		//
		// Prepares update
		//
		$query = $this->db->prepare("UPDATE Customers SET
			 CompanyName = ?
			,ContactName = ?
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
			
		if($query !== false){
			if($query->bind_param("sssssssssss",$companyName,$contactName,$contactTitle,$address,$city,$region,$postalCode,$country,$phone,$fax,$id)){				
				$success = $query->execute();
			}				
		}
						
		return $success;	
	}

	function checkAvailability($id=""){
		//
		// Don't continue if db failed to connect or admin is not logged
		//
		if($db->connect_errno > 0) return false;
		if(!$this->admin->isLogged()) return false;
		
		//
		// Select all records with id = $id
		//
		$available = false;	
		$query = $this->db->prepare("SELECT CustomerID FROM Customers WHERE CustomerID = ?");
		if($query !== false){
			if($query->bind_param("s",$id)){
				if($query->execute()){
					if($query->store_result()){
						//
						// If no records were found, set $available to true, otherwise, remain false
						//
						if($query->num_rows == 0){
							$available = true;
						}						
					}
										
				}
			
			}
			
		}

		
		return $available;
		
	}

	function addCustomer(
		 $id=""
		,$companyName=""
		,$contactName=""
		,$contactTitle=""
		,$address=""
		,$city=""
		,$region=""
		,$postalCode=""
		,$country=""
		,$phone=""
		,$fax=""
	){
		//
		// Don't continue if db failed to connect or admin is not logged
		//
		if($db->connect_errno > 0) return false;
		if(!$this->admin->isLogged()) return false;

		//
		// If desired id is available, add customer
		//
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
			
			if($query !== false){
				if($query->bind_param("sssssssssss",$id,$companyName,$contactName,$contactTitle,$address,$city,$region,$postalCode,$country,$phone,$fax)){
					$success = $query->execute();					
				}
			}			

		}
		return $success;
		
	}

}

?>