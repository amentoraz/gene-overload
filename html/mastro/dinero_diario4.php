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


//  include ("../clases/obj_especimen_torneo.php");
//  include ("../clases/obj_especimen.php");
  include ("../clases/obj_jugador.php");
  include ("../clases/obj_jugador_campana.php");
//  include ("../clases/obj_combate.php");
//  include ("../clases/obj_arbol.php");
//  include ("../clases/obj_torneo.php");
  include ("../clases/obj_campana.php");
  include ("../clases/obj_informe.php");

  echo ("Verificaci&oacute;n de IP correcta...");

  $accion = $_REQUEST['accion'];



### VAMOS A HACERLO A MANO PARA SUMAR!
$accion = sumar;


  // ****************************************
  //   Darle pasta a todo el mundo, formulario
  // ****************************************

  if ($accion == "sumar")
  {
    $idcampana = $_REQUEST['idcampana'];
$idcampana = 4;
    $dinero = $_REQUEST['dinero'];
$dinero = 5;

    $jugador_campana = new Jugador_Campana();
    $informe = new Informe();

    // El maximo es "dinero" cuando llevas menos de un dia sin aparecer, y el minimo es 2.
    $jugador_campana->SumarTodosVariable($link_w, $idcampana, $dinero, 2);

    // Enviamos el informe de que ha llegado una subvencion con esa cantidad de pasta
//    $informe->EnviarSubvencionTodos($link_w, $idcampana, $dinero);

    echo ("Sumados a la campa&ntilde;a ".$idcampana.", ".$dinero." cr&eacute;ditos");

  }


  // ****************************************
  //   Darle pasta a todo el mundo, formulario
  // ****************************************

  if ($accion == null)
  {

    $campana = new Campana();
    $array = $campana->ListarCampanas($link_r);
//print_r ($array);
    ?>
    <form method="post" action="dinero_diario.php">
          <p><strong>Campa&ntilde;a : </strong>
        <select name="idcampana">
          <?php
          for ($i = 1; $i <= count($array); $i++)
          {
            echo ("<option value=\"".$array[$i]['id']."\">");
            echo ($array[$i]['nombre']);
            echo ("</option>");
          }
          ?>
        </select>
      </p>
      <p><strong>Cantidad: </strong>
	<input type="text" name="dinero" size="5" value="3">
	<input type="hidden" name="accion" value="sumar">
      </p>
      <p>
        <input type="submit" value="Sumar">
      </p>
    </form>

    <?php

  }





}

?>
