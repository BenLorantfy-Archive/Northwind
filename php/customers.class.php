<?php



//Handle AJAX
if(isset($_POST["call"]) && __FILE__ == $_SERVER["SCRIPT_FILENAME"]){
	require_once("admin.class.php");
	session_start();
	$customers = new Customers("connect.php");
	$customers->$_POST["call"]();
}else{
	require_once("php/admin.class.php");
}

class Customers{
	private $db = null;
	private $connect = null;
	private $admin = null;
	
	function Customers($connect=null,$db=null){
		$this->connect = $connect;
		
		//Finds database variable and assigns it to $this->db
		if($this->db === null){
			require($this->connect);
			
			//Gets mysqli database object
			//Short circuting meqans is_a won't run if $db isn't defined
			if(isset($db) && is_a($db,"mysqli")){
				$this->db = $db;
				$foundMysqliObject = true;
			}else{
				//Loops through every variable within scope and checks if any of them are mysqli objects
				//If a mysqli object was saved in the connect file, it should be found
				//Uses the first mysqli object it finds
				//This means the name of the variable that stores the mysqli object does not matter
				$foundMysqliObject = false;
				foreach(get_defined_vars() as $key => $value){
					//Checks if variable is an instance of mysqli
					//Uses mysqli instead of mysql beacuse mysql "is deprecated as of PHP 5.5.0, and is not 
						//recommended for writing new code as it will be removed in the future"
					if(is_a($value,"mysqli")){
						$this->db = $value;
						$foundMysqliObject = true;
						break;
					}
				}				
			}

			//Errors if mysqli object was not found
			if(!$foundMysqliObject){
				die("'" . $this->connect . "' must declare a variable that stores a mysqli object. ");
			}
 
		}
		
		$this->admin = new Admin($this->connect,$this->db);
	}
	
	function getTableRows($order, $search, $reverse){
		if($this->admin->isLogged()){
			require("sanitize.php");
			$reverse = is_string($reverse) ? $reverse == "true" : $reverse;
			$db = $this->db;
			$output = "";
			$direction = "";
			
			
			
			if($reverse){
				$direction = "DESC";
			}
			
			$result = $db->query("SELECT * FROM customers WHERE CompanyName LIKE '%$search%' ORDER BY $order $direction");
			
	
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
			return $this->result($output);			
		}
	}
	
	function result($returnData){
		if(isset($_POST["call"]) && __FILE__ == $_SERVER["SCRIPT_FILENAME"]){
			if(is_string($returnData)){
				echo $returnData;
			}else{
				if($returnData){
					echo "true";
				}else{
					echo "false";
				}				
			}
		}
		return $returnData;
	}
}



?>