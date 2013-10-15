<?php

//
//
// PONER UN NOMBRE A LOS ESPECIMENES
//
//
//
//
//
//
//
//
//
//

function realip(){
        if ($_SERVER) {
                if ( $_SERVER[HTTP_X_FORWARDED_FOR] ) {
                        $realip = $_SERVER["HTTP_X_FORWARDED_FOR"];
                        } elseif ( $_SERVER["HTTP_CLIENT_IP"] ) {
                                $realip = $_SERVER["HTTP_CLIENT_IP"];
                        } else {
                                $realip = $_SERVER["REMOTE_ADDR"];
                        }
                } else {
                        if ( getenv( 'HTTP_X_FORWARDED_FOR' ) ) {
                                $realip = getenv( 'HTTP_X_FORWARDED_FOR' );
                                } elseif ( getenv( 'HTTP_CLIENT_IP' ) ) {
                                        $realip = getenv( 'HTTP_CLIENT_IP' );
                        } else {
                                $realip = getenv( 'REMOTE_ADDR' );
                }
        }
        return $realip;
}

//if (realip() != '80.88.225.141'){
if ((realip() == '127.0.0.1')
                        || (realip() == '87.216.167.252')
                        || (realip() == '83.52.236.143') 
        || (realip() == '89.131.177.181')        
        || (realip() == '66.147.242.154')
        || (realip() == '83.32.138.219')         
        || (realip() == '192.168.0.23') )
{

  include ("../config/database.php");
  include ("../config/values.php");

  session_start();
  include ("../clases/obj_especimen.php");
  include ("../clases/obj_evolucion.php");
  include ("../clases/obj_especimen_torneo.php");
  include ("../clases/obj_informe.php");
  include ("../clases/obj_jugador_campana.php");
  include ("../clases/obj_jugador.php");
  include ("../clases/obj_log.php");
  include ("../clases/obj_mail.php");

  echo ("Verificaci&oacute;n de IP correcta...");

  $accion = $_REQUEST['accion'];



  if ($accion == null)
  {

    $string = "SELECT id, iddeme,
		fuerza, rapidez, inteligencia,
		sabiduria, percepcion, constitucion
		FROM especimen
		ORDER BY id DESC
		";
    $query = mysql_query($string, $link_r);
    while($unquery = mysql_fetch_array($query))
    {
      $idespecimen = $unquery['id'];

      echo ($unquery['id']."-".$unquery['iddeme']."<br/>");
      echo ("<br/>");
      $iddeme = $unquery['iddeme'];
      $fuerza = $unquery['fuerza'];
      $inteligencia = $unquery['inteligencia'];
      $rapidez = $unquery['rapidez'];
      $sabiduria = $unquery['sabiduria'];
      $percepcion = $unquery['percepcion'];
      $constitucion = $unquery['constitucion'];
      


        $silaba_car = '';
    if ($iddeme == 1)
    {
        // RAPIDEZ
      if(($rapidez) <= 4){        $silaba_car = $silaba_car."g"; }
      if((($rapidez) <= 7) && ($rapidez > 4)) {     $silaba_car = $silaba_car."z"; }
      if(($rapidez) > 7) {        $silaba_car = $silaba_car."k"; }            
        // FUERZA
        if(($fuerza) <= 4) { $silaba_car = $silaba_car."i"; }
      if((($fuerza) <= 7) && ($fuerza > 4)) { $silaba_car = $silaba_car."e"; }
      if(($fuerza) > 7) { $silaba_car = $silaba_car."u"; }       
        // INTELIGENCIA
      if(($inteligencia) <= 4) {  $silaba_car = $silaba_car."t"; }
      if((($inteligencia) <= 7) && ($inteligencia > 4)) { $silaba_car = $silaba_car."k"; }
      if(($inteligencia) > 7) {   $silaba_car = $silaba_car."d"; }          
        // CONSTITUCION
      if(($constitucion) <= 4) { $silaba_car = $silaba_car."g"; }
      if((($constitucion) <= 7) && ($constitucion > 4)) { $silaba_car = $silaba_car."n"; }      
      if(($constitucion) > 7) {   $silaba_car = $silaba_car."k"; }           
        // PERCEPCION
      if(($percepcion) <= 4) { $silaba_car = $silaba_car."o"; }
      if((($percepcion) <= 7) && ($percepcion > 4)) {       $silaba_car = $silaba_car."u"; }
      if(($percepcion) > 7) { $silaba_car = $silaba_car."y"; }     
        // SABIDURIA
      if(($sabiduria) <= 4) {     $silaba_car = $silaba_car."j"; }
      if((($sabiduria) <= 7) && ($sabiduria > 4)) { $silaba_car = $silaba_car."h"; }
      if(($sabiduria) > 7) {      $silaba_car = $silaba_car."k"; }
    }      
    if ($iddeme == 2)
    {
        // RAPIDEZ
      if(($rapidez) <= 4){        $silaba_car = $silaba_car."t"; }
      if((($rapidez) <= 7) && ($rapidez > 4)) {     $silaba_car = $silaba_car."f"; }
      if(($rapidez) > 7) {        $silaba_car = $silaba_car."l"; }            
        // FUERZA
        if(($fuerza) <= 4) { $silaba_car = $silaba_car."a"; }
      if((($fuerza) <= 7) && ($fuerza > 4)) { $silaba_car = $silaba_car."e"; }
      if(($fuerza) > 7) { $silaba_car = $silaba_car."i"; }       
        // INTELIGENCIA
      if(($inteligencia) <= 4) {  $silaba_car = $silaba_car."r"; }
      if((($inteligencia) <= 7) && ($inteligencia > 4)) { $silaba_car = $silaba_car."n"; }
      if(($inteligencia) > 7) {   $silaba_car = $silaba_car."s"; }          
        // CONSTITUCION
      if(($constitucion) <= 4) { $silaba_car = $silaba_car."n"; }
      if((($constitucion) <= 7) && ($constitucion > 4)) { $silaba_car = $silaba_car."l"; }      
      if(($constitucion) > 7) {   $silaba_car = $silaba_car."f"; }           
        // PERCEPCION
      if(($percepcion) <= 4) { $silaba_car = $silaba_car."e"; }
      if((($percepcion) <= 7) && ($percepcion > 4)) {       $silaba_car = $silaba_car."i"; }
      if(($percepcion) > 7) { $silaba_car = $silaba_car."a"; }     
        // SABIDURIA
      if(($sabiduria) <= 4) {     $silaba_car = $silaba_car."l"; }
      if((($sabiduria) <= 7) && ($sabiduria > 4)) { $silaba_car = $silaba_car."r"; }
      if(($sabiduria) > 7) {      $silaba_car = $silaba_car."n"; }
    }      
    if ($iddeme == 3)
    {
        // RAPIDEZ
      if(($rapidez) <= 4){        $silaba_car = $silaba_car."s"; }
      if((($rapidez) <= 7) && ($rapidez > 4)) {     $silaba_car = $silaba_car."t"; }
      if(($rapidez) > 7) {        $silaba_car = $silaba_car."r"; }            
        // FUERZA
        if(($fuerza) <= 4) { $silaba_car = $silaba_car."a"; }
      if((($fuerza) <= 7) && ($fuerza > 4)) { $silaba_car = $silaba_car."e"; }
      if(($fuerza) > 7) { $silaba_car = $silaba_car."o"; }       
        // INTELIGENCIA
      if(($inteligencia) <= 4) {  $silaba_car = $silaba_car."t"; }
      if((($inteligencia) <= 7) && ($inteligencia > 4)) { $silaba_car = $silaba_car."f"; }
      if(($inteligencia) > 7) {   $silaba_car = $silaba_car."m"; }          
        // CONSTITUCION
      if(($constitucion) <= 4) { $silaba_car = $silaba_car."r"; }
      if((($constitucion) <= 7) && ($constitucion > 4)) { $silaba_car = $silaba_car."s"; }      
      if(($constitucion) > 7) {   $silaba_car = $silaba_car."p"; }           
        // PERCEPCION
      if(($percepcion) <= 4) { $silaba_car = $silaba_car."o"; }
      if((($percepcion) <= 7) && ($percepcion > 4)) {       $silaba_car = $silaba_car."e"; }
     if(($percepcion) > 7) { $silaba_car = $silaba_car."a"; }     
        // SABIDURIA
      if(($sabiduria) <= 4) {     $silaba_car = $silaba_car."r"; }
      if((($sabiduria) <= 7) && ($sabiduria > 4)) { $silaba_car = $silaba_car."m"; }
      if(($sabiduria) > 7) {      $silaba_car = $silaba_car."n"; }
    }

//    echo $silaba_car;





      global $array_silabas_profundidades;
      global $array_silabas_bosque;
      global $array_silabas_volcan;

        switch ($iddeme)
        {
                case 1: $array = $array_silabas_profundidades; break;
                case 2: $array = $array_silabas_bosque; break;
                case 3: $array = $array_silabas_volcan; break;
                default : $array = $array_silabas_volcan; break;
        }

print_r ($array);

        // Primero vamos a seleccionar 3 silabas aleatorias
        $longitud = count($array);
        $silaba1 = rand(0,($longitud - 1));
        $silaba2 = $silaba1;

        while ($silaba1 == $silaba2)
        {
                $silaba2 = rand(0,($longitud - 1));
        }
        $silaba3 = $silaba2;
        while (($silaba1 == $silaba3) || ($silaba2 == $silaba3))
        {
                $silaba3 = rand(0, ($longitud - 1));
        }

       $silaba1 = $array[$silaba1];
       $silaba2 = $array[$silaba2];
       $silaba3 = $array[$silaba3];

       echo $silaba1.$silaba2." ".$silaba3.$silaba_car."<br/>";

       $insertar = "UPDATE especimen
		SET silaba1 = '$silaba1',
		silaba2 = '$silaba2',
		silaba3 = '$silaba3',
		silabacar = '$silaba_car'
		WHERE id = $idespecimen
		";
//echo $insertar;
       $query2 = mysql_query($insertar, $link_w);


    } // del while


  } // del if


}


?>
