<?php

  session_start();
?>
<html>
<head></head>
<body>
<?php


  include ("config/database.php");
  include ("config/values.php");
  include ("clases/obj_log.php");
  include ("clases/obj_secure.php");
  $secure = new Secure();


include('clases/obj_especimen.php');

$especimen = new Especimen();


echo date("T");
echo ("<br/>");


$especimen->iddeme = rand(1,3);
echo ("Deme ".$especimen->iddeme."<br/><br/>");


$especimen->rapidez = rand(1,10);
$especimen->inteligencia = rand(1,10);
$especimen->fuerza = rand(1,10);
$especimen->constitucion = rand(1,10);
$especimen->percepcion = rand(1,10);
$especimen->sabiduria = rand(1,10);
$especimen->CrearNombre($link_r);

echo ("<span style=\"font-size: 16px;\"><h1><b>");
echo $especimen->silaba1;
echo $especimen->silaba2;
echo (" ");
echo $especimen->silaba3;
echo (substr($especimen->silabacar, 0, 3));
echo (substr($especimen->silabacar, 3, 3));
echo ("</h1></b></span>");
//echo $especimen->silabacar;

echo ("-");
echo ($especimen->silabacar);

die;

?>
</body>
</html>
