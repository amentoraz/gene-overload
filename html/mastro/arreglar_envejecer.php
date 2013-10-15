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

    // Envejecimiento standard. Vamos sacando a todos los jugadores.
    //
    $idcampana = $_REQUEST['idcampana'];

    $string = "SELECT id, login, last_login
		FROM jugador
		ORDER BY id ASC
		";
    $query = mysql_query($string, $link_r);
    echo ("<table>");
    echo ("<tr>");
    echo ("<th width=\"200px\">");
    echo ("Jugador");
    echo ("</th>");
    echo ("<th width=\"300px\">");
    echo ("Last login");
    echo ("</th>");
    echo ("<th width=\"300px\">");
    echo ("Existe el especimen asignado");
    echo ("</th>");
    echo ("<th width=\"100px\">");
    echo ("Id sustituto");
    echo ("</th>");
    echo ("</tr>");
    while ($unquery = mysql_fetch_array($query))
    {
      $idjugador = $unquery['id'];

      $string2 = "SELECT a.id
		FROM especimen_torneo a, especimen b
		WHERE a.idespecimen = b.id
		AND a.idtorneo = 0
		AND b.idpropietario = $idjugador
		";
      $query2 = mysql_query($string2, $link_r);
      if ($unquery2 = mysql_fetch_array($query2))
      {
        echo ("<tr style=\"background-color: #00ff00\">");
        $ver = 1;
      } else {
        echo ("<tr style=\"background-color: #ff0000\">");
        $ver = 0;
      }

      echo ("<td>".$unquery['login']." (".$unquery['id'].")</td>");

      echo ("<td>".$unquery['last_login']."</td>");


      echo ("<td>");
      if ($ver == 1)
      {
        echo ("<span>especimen found</span>");
        echo ("</td><td>");
      } else {
        echo ("<span>NO ESPECIMEN</span>");

        // Ahora entonces le asignaremos alguno
        $idjugador = $unquery['id'];
        $stringx = "SELECT id FROM especimen
		WHERE idcampana = $idcampana
		AND idpropietario = $idjugador
		AND iddeme = 1
		AND idslot = 1
		";
        $queryx = mysql_query($stringx, $link_r);
        if ($unqueryx = mysql_fetch_array($queryx))
        {
          echo ("</td><td>");
          echo ($unqueryx['id']);
          echo ("</td>");

          // Y aqui es cuando hay que asignarlos con un insert
          $idespecimen = $unqueryx['id'];
          $stringi = "INSERT INTO especimen_torneo
		(idespecimen, idtorneo, posicion)
		VALUES
		($idespecimen, 0, null)
		";
          $queryi = mysql_query($stringi, $link_w);

        } else {
          echo ("</td><td>Error: El jugador no se ha apuntado a la campanya");
        }

      }
      echo ("</td>");
      echo ("</tr>");
    }

    echo ("</table>");

  }



} // Cierre check IP

?>
