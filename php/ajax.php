<?php
function handleAJAX($class){
	$method = $_POST["call"];	// method to call
	$post = $_POST;				// Don't modify the super global, instead, copy it
	
	if(method_exists($class,$method)){
		//
		// Since request was made using AJAX, session has not been started, so start it
		//
		session_start();
		
		//
		// Generates an array of paramter names in the order they appear in method
		//
		// Reflection is ussually not good practice, but because this is used for a very specific
		// purpose, this is an acceptable exception, esspecially since the added time accounts for
		// about 0.00004 seconds when measured with microtime(), and it is only used once per
		// request. The benefit is that the method parameters can be specified by name.
		//
		$params = (new ReflectionMethod($class, $method))->getParameters();
		$paramNames = array();
		foreach($params as $param){
			$paramNames[] = $param->name;
		}
		$paramNamesAsKeys = array_flip($paramNames);
		
		//
		// Removes all elements from $post whose keys aren't parameter names 
		//
		$post = array_intersect_key($post, $paramNamesAsKeys);
		
		//
		// Cut off $paramNamesAsKeys where $post stops providing values
		// If a parameter was skipped, error
		//
		$ended = false;
		foreach($paramNamesAsKeys as $key => $value){
			if(!isset($post[$key])){
				if(!$ended){
					array_splice($paramNamesAsKeys, (array_search($key, array_keys($paramNamesAsKeys))));		
					$ended = true;				
				}
			}else{
				if($ended){
					throw new Exception("Can't skip parameters in AJAX call");
				}
			}
		}
		
		//
		// Sorts post so elements are in the same order as method parameters
		//
		$post = array_merge($paramNamesAsKeys, $post);
			
		//
		// Calls method with elements of $post as parameters
		//
		$returnData = call_user_func_array(array(new $class(),$method), $post);
		
		//
		// Echos $returnData in json format
		// In an AJAX request, this can be accessed client-side as response data
		//
		// Second parameter is a bitmask that ensures html is treated correctly when decoded
		// JSON_HEX_TAG: All < and > are converted to \u003C and \u003E. Available since PHP 5.3.0.
		// JSON_HEX_QUOT: All " are converted to \u0022. Available since PHP 5.3.0
		//
		echo json_encode($returnData,JSON_HEX_QUOT | JSON_HEX_TAG);
	}else{
		throw new Exception("Method doesn't exist");
	}
}

?>