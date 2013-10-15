<?php
   include ("../include/xml.php");
   include ("../config/vars.php");
   include ("../config/bd.php");

	$bd = new BD();
	$bd->escribeBD(); 

	$padre = 0;

	$XML = "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>".ObtenerSecciones($padre,"","","");
    // Borramos el que haya
	unlink ($path_interior."/xml/structure.xml");
	$handlefichero = fopen($path_interior."/xml/structure.xml","w+");
	fwrite($handlefichero, $XML);
	fclose($handlefichero);
	$bd->cierraBD(); 
?>
