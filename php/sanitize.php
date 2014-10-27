<?php

//Escapes all function parameters
foreach(get_defined_vars() as $key => $value){
	if(is_string($value)){
		$$key = $value;
		$$key = $this->db->escape_string($$key);
		$$key = strip_tags($$key);
	}
}

//Escapes all post variables
foreach($_POST as $key => $value){
    $$key = $value;
    $$key = $this->db->escape_string($$key);
    $$key = strip_tags($$key);
}

?>