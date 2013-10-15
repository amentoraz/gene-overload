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


    $especimen_torneo = new Especimen_Torneo();
    $especimen = new Especimen();

    // Esto normaliza torneos para cada jugador
    $idcampana = $_REQUEST['idcampana'];
    if ($idcampana == null) { echo ("Mete un idcampana;"); die; }

    $jugador_campana = new Jugador_Campana();
    $cuantos = $jugador_campana->ContarElementosRank($link_r, $idcampana);
    $array = $jugador_campana->BuscarElementosRank($link_r, $idcampana, 13461356, 0, 1);
    for ($i=1; $i <= count($array); $i++)
    {
echo ("#".$i);
       $idjugador = $array[$i]['idjugador'];
echo ("-idj:".$idjugador);
       for ($d = 0; $d < 4; $d++)
       {
         $hay = $especimen_torneo->ObtenEspecimenTorneo($link_r, $d, $idjugador);
echo ("-hay:".$hay);
         if ($hay == 0)
         {
           if ($d > 0)
           {
             echo ("Metiendo al jugador ".$idjugador." en el deme ".$d);
             $especimen->SacarDatos($link_r, $d, 1, $idjugador, $idcampana); // El idslot sera el 1, pero el iddeme es $d
           } else {
             echo ("Metiendo al jugador ".$idjugador." en el torneo general ".$d);
             $especimen->SacarDatos($link_r, 1, 1, $idjugador, $idcampana); // El idslot sera el 1, pero el iddeme es $d
           }
           $especimen_torneo->ApuntarTorneo($link_w, $especimen->id, $d);
         }
       }

echo ("<br/>");

    }



  }

}

?>
