<?php
/* 
 * 	FILE 			: admin.class.php
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

//
// Handle AJAX
// -----------
// Tests if page was requested with ajax. This can be spoofed, but that doesn't really matter
// If it was, call method specified in the post variable named "call" and echo return data
//
if(isset($_POST["call"]) && realpath(__FILE__) == realpath($_SERVER["SCRIPT_FILENAME"])){
	handleAJAX("Admin");	
}

/*
 * NAME 	: Admin
 *
 * PURPOSE 	: The admin class contains several functions used
 *			  to log in/authenticate
 */
class Admin{
	private $db;
	
	function __construct(){
		$this->db = connect();
	}
	
	function login($name="",$password=""){
		$success = false;
		
		//
		// Using prepared statements is slightly slower in this case, but more secure
		//
		$query = $this->db->prepare("SELECT password FROM users WHERE name = ? LIMIT 1");
		$query->bind_param("s",$name);
		$query->execute();
		$query->store_result();
		
		if($query->num_rows == 1){
			$query->bind_result($hashedPassword);
			$query->fetch();
			if(password_verify($password,$hashedPassword)){
				$_SESSION["name"] = $name;
				$_SESSION["password"] = $password;
				$success = true;
			}
		}
				
		return $success;
	}
	
	function isLogged(){
		$logged = false;		
		if(isset($_SESSION["name"]) && isset($_SESSION["password"])){			
			//
			// Using prepared statements is slightly slower in this case, but more secure
			//
			$query = $this->db->prepare("SELECT password FROM users WHERE name = ? LIMIT 1");
			$query->bind_param("s",$_SESSION["name"]);
			$query->execute();
			$query->store_result();
			
			if($query->num_rows == 1){
				$query->bind_result($hashedPassword);
				$query->fetch();
				if(password_verify($_SESSION["password"],$hashedPassword)){
					$logged = true;
				}
			}
		}
		
		return $logged;
	}
	
	function logout(){
		session_destroy();
	}
}

?>