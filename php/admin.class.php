<?php
class Admin{
	private $db = null;
	private $connect = null;
	
	function Admin($connect=null,$db=null){
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
	}
	
	function create($name="",$password=""){
		require("sanitize.php");
		if($this->isLogged()){
			$db = $this->db;
			$password = password_hash($password, PASSWORD_DEFAULT);
			$db->query("INSERT INTO users (name,password) VALUES ('$name','$password')");
			$this->login($name,$password);
		}		
	}

	function login($name="",$password=""){
		require("sanitize.php");
		$isLogged = false;
		$db = $this->db;
		$result = $db->query("SELECT * FROM users WHERE name = '$name' LIMIT 1");
		if($result->num_rows > 0){
			$row = $result->fetch_assoc();
			if(password_verify($password,$row["password"])){
				$_SESSION["name"] = $name;
				$_SESSION["password"] = $password;		
				$isLogged = true;		
			}
		}
		return $this->result($isLogged);
	}
	
	function logout(){
		session_destroy();
	}
	
	function isLogged(){
		$isLogged = false;
		
		if(isset($_SESSION["name"]) && isset($_SESSION["password"])){
			$db = $this->db;			
			$name = $db->escape_string($_SESSION["name"]);
			$password = $db->escape_string($_SESSION["password"]);
			
			$result = $db->query("SELECT * FROM users WHERE name = '$name' LIMIT 1");
			$row = $result->fetch_assoc();
			if($result->num_rows > 0){
				if(password_verify($password,$row["password"])){
					$isLogged = true;
				}
			}
		}
		
		return $this->result($isLogged);
	}
	
	function result($returnData=""){
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

//Handle AJAX
if(isset($_POST["call"]) && __FILE__ == $_SERVER["SCRIPT_FILENAME"]){
	session_start();
	$admin = new Admin("connect.php");
	$admin->$_POST["call"]();
}

?>