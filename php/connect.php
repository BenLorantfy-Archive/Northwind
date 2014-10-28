<?php
	$db = new mysqli('localhost', 'lorantbe_public', 'public123', 'lorantbe_northwind');
	
	if($db->connect_errno > 0){
	    die('Unable to connect to database [' . $db->connect_error . ']');
	}
?>
