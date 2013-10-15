<?php

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


if ((realip() == '127.0.0.1')
        || (realip() == '87.216.167.252')
        || (realip() == '66.147.242.154')
        || (realip() == '192.168.0.23') )
{


  session_start();


  include ("../config/database.php");
  include ("../config/values.php");


  include ("../clases/obj_jugador.php");
  include ("../clases/obj_jugador_campana.php");
  include ("../clases/obj_campana.php");
  include ("../clases/obj_informe.php");

  echo ("Verificaci&oacute;n de IP correcta...");

  $accion = $_REQUEST['accion'];

  $informe = new Informe();
  $informe->EliminarAntiguos($link_w);




}


?>
