<?php


class Secure {





function cleanInput($input) {
 
	$search = array(
	    '@<script[^>]*?>.*?</script>@si',   // Strip out javascript
	    '@<[\/\!]*?[^<>]*?>@si',            // Strip out HTML tags
	    '@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly
	    '@<![\s\S]*?--[ \t\n\r]*>@'         // Strip multi-line comments
	);
 
	    $output = preg_replace($search, '', $input);
	    return $output;
}


function cleanInputMensaje($input) {
 
	$search = array(
	    '@<script[^>]*?>.*?</script>@si',   // Strip out javascript
	    '@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly
	);
 
	    $output = preg_replace($search, '', $input);
	    return $output;
}






    // *******************************************************
    //  Sanitizar una cadena de entrada
    // *******************************************************

    function Sanitizar($input) {

	    if (is_array($input)) {
	        foreach($input as $var=>$val) {
	            $output[$var] = Sanitizar($val);
	        }
	    }
	    else {
	        if (get_magic_quotes_gpc()) {
	            $input = stripslashes($input);
	        }
	        $input  = $this->cleanInput($input);
	        $output = mysql_real_escape_string($input);
	    }
	    return $output;


    }

    // *******************************************************
    //  Sanitizar una cadena de entrada para un foro o contenido
    // *******************************************************

    function SanitizarMensaje($input) {

	    if (is_array($input)) {
	        foreach($input as $var=>$val) {
	            $output[$var] = SanitizarMensaje($val);
	        }
	    }
	    else {
	        if (get_magic_quotes_gpc()) {
	            $input = stripslashes($input);
	        }
	        $input  = $this->cleanInputMensaje($input);
		$output = $input;
	    }
	    return $output;


    }


}
