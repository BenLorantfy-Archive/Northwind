<?php
	/* 
	 * FUNCTION 	: ret
	 *
	 * DESCRIPTION 	: This function is used to determine if return data should be printed or not
	 *				  $data is echoed if the following conditions are true:
	 *					1. Ajax was used to request the file
	 *					2. The calling function is inside the file requested by ajax
	 *				 	3. The calling function was the function called as specified by the "call" post variable
	 *				  It does this because the only way the page that had the ajax that sent the request can get
	 *				  back information is if it is echoed to the page
	 *				  The return value of this function should be returned by every function that returns a value
	 *			  	  This way, each function doesn't have to worry about if it should echo or simply return, because
	 *				  ret() decides this for them
	 *					
	 *							e.g. return ret($successFlag,__FILE__,__FUNCTION__);
	 *
	 * PARAMETERS 	: $data - the data to be returned and potentially printed. 
	 *						  $data is always returned, regardless of the above conditions
	 * 				: $file - should always be __FILE__
	 *				: $function - should always be __FUNCTION__
	 *
	 * RETURNS 		: $data
	 */
	function ret($data="",$file="",$function=""){		
		if(isset($_POST["call"]) && $_POST["call"] == $function &&  $_SERVER["SCRIPT_FILENAME"] == $file){			
			if(is_string($data)){
				echo $data;
			}else{
				if($data){
					echo "true";
				}else{
					echo "false";
				}				
			}	
		}
		return $data;
	}

	/* 
	 * FUNCTION 	: connect
	 *
	 * DESCRIPTION 	: This function returns a mysqli connection object given a file that creates the object, or a mysqli connection object
	 *					
	 *							e.g. return ret($successFlag,__FILE__,__FUNCTION__);
	 *
	 * PARAMETERS 	: $connectOrDb - string containing path to file that creates a mysqli object, or the mysqli object
	 *
	 * RETURNS 		: a mysqli connection object
	 */	
	function connect($connectOrDb){
		$connectedDB = null;
		
		//
		// If paramter was mysqli connection object, return parameter
		// This allows the check to be contained in this function, instead of in the calling function  
		//
		if(is_a($connectOrDb,"mysqli")){
			$connectedDB = $connectOrDb;
		}else{
			require($connectOrDb);
			
			//
			// Checks if name of mysqli connection object is $db, since this is a common name
			//
			if(isset($db) && is_a($db,"mysqli")){
				$connectedDB = $db;
				$foundMysqliObject = true;
			// Else, name of mysqli connection object is something else
			}else{
				// Loops through every variable within scope and checks if any of them are mysqli objects
				// If a mysqli object was saved in the connect file, it should be found
				// Uses the first mysqli object it finds
				// This means the name of the variable that stores the mysqli object does not matter
				$foundMysqliObject = false;
				foreach(get_defined_vars() as $key => $value){
					// Checks if variable is an instance of mysqli
					// Uses mysqli instead of mysql beacuse mysql "is deprecated as of PHP 5.5.0, and is not 
						// recommended for writing new code as it will be removed in the future"
					if(is_a($value,"mysqli")){
						$connectedDB = $value;
						$foundMysqliObject = true;
						break;
					}
				}				
			}
			
			// Errors if mysqli object was not found
			if(!$foundMysqliObject){
				die("'" . $connectedDB . "' must declare a variable that stores a mysqli object. ");
			}					
				
		}
		
		return $connectedDB;
	}

?>