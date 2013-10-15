<?php

function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
echo ("<br/>".$usec." microsegundos, ".$sec." segundos.<br/>");
// Micro es 10 elevado a -6, asi que multiplicamos por 1000000 para el numero de microsegundos
// Pero como se nos va de las manos, dividimos por 100 para guardar milisegundos
//    return ( ((float)$usec + (float)$sec) * 1000000 );
    return ( (((float)$usec + (float)$sec) * 1000000 ) / 100);
}


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



  include ("../clases/obj_especimen_torneo.php");
  include ("../clases/obj_especimen.php");
  include ("../clases/obj_jugador.php");
  include ("../clases/obj_jugador_campana.php");
  include ("../clases/obj_combate.php");
  include ("../clases/obj_arbol.php");
  include ("../clases/obj_torneo.php");
  include ("../clases/obj_campana.php");
  include ("../clases/obj_informe.php");
  include ("../clases/obj_log.php");
  include ("../clases/obj_mail.php");

  echo ("Verificaci&oacute;n de IP correcta...");



  $accion = $_REQUEST['accion'];



  // ****************************************
  //   Ejecucion de un torneo tipo eliminatoria
  // ****************************************

  if ($accion == "torneo_eliminatoria")
  {

    $string = "LOCK TABLES especimen WRITE
		";
    $query = mysql_query($string, $link_w);

    $mail = new Mail();

    $idcampana = $_REQUEST['idcampana'];
    $debug_mode = $_REQUEST['debug_mode'];
    $guardar = $_REQUEST['guardar'];

    $informe = new Informe();
    $jugador = new Jugador();
    $jugador2 = new Jugador();
    $jugador_campana = new Jugador_Campana();
    $especimen_torneo = new Especimen_torneo();
    $especimen = new Especimen();
    $combate = new Combate($debug_mode);
    $arbol = new Arbol();
    $cuantos = $especimen_torneo->ContarEspecimenesTorneo($link_r, 0);

    echo ("<p>");
    echo ("Enfrentando ".$cuantos." espec&iacute;menes dispuestos a competir");
    echo ("</p>");

    $array = $especimen_torneo->BuscarEspecimenesTorneo($link_r, 0);

    // Ponemos a cero la puntuacion inicial para todos
    for ($i = 1; $i <= $cuantos; $i++)
    {
      $array[$i]['puntuacion'] = 0;
    }

    // Ahora tendremos que enfrentarles.



  }


  // ****************************************
  //   Ejecucion de un torneo todos vs todos
  // ****************************************

  if ($accion == "torneo_todos_con_todos")
  {

    // Timestamp en microsegundos de comienzo
    $unix_begin = microtime_float();

    $link_r = $link_w;
    $string = "LOCK TABLES especimen WRITE,
		jugador WRITE,
		jugador a WRITE,
		jugador_campana WRITE,
		jugador_campana b WRITE,
		especimen_torneo WRITE,
		especimen_torneo a WRITE,
		especimen b WRITE,
		torneo WRITE,
		log WRITE,
		informe WRITE
		";
    $query = mysql_query($string, $link_w);


    $mail = new Mail();

    $idcampana = $_REQUEST['idcampana'];
    $debug_mode = $_REQUEST['debug_mode'];
    $guardar = $_REQUEST['guardar'];

    $informe = new Informe();
    $jugador = new Jugador();
    $jugador2 = new Jugador();
    $jugador_campana = new Jugador_Campana();
    $especimen_torneo = new Especimen_torneo();
    $especimen = new Especimen();
    $combate = new Combate($debug_mode);
    $arbol = new Arbol();
    $cuantos = $especimen_torneo->ContarEspecimenesTorneo($link_r, 0);

    echo ("<p>");
    echo ("Enfrentando ".$cuantos." espec&iacute;menes dispuestos a competir");
    echo ("</p>");

    $array = $especimen_torneo->BuscarEspecimenesTorneo($link_r, 0);

    // Ponemos a cero la puntuacion inicial para todos
    for ($i = 1; $i <= $cuantos; $i++)
    {
      $array[$i]['puntuacion'] = 0;
      $array[$i]['victoria'] = 0;
      $array[$i]['derrota'] = 0;
      $array[$i]['empate'] = 0;
    }

    // Ahora, por cada uno, sacamos una puntuacion, y guardamos el que la tenga mas gorda
    for ($i = 1; $i <= $cuantos; $i++)
    {
      for ($j = 1; $j <= $cuantos; $j++)
      {
        if ($i != $j) // No queremos enfrentarlos consigo mismos!
        {
          $especimen1 = $especimen->Obtener_Por_Id($link_r, $array[$i]['idespecimen']);
          $especimen2 = $especimen->Obtener_Por_Id($link_r, $array[$j]['idespecimen']);
          // Ahora vamos
          if ($debug_mode == 1)
          {          
				echo ("<br/>NIVELES 1:".$especimen1['niveles_arbol']);
				echo ("<br/>NIVELES 2:".$especimen2['niveles_arbol']);
		    }
          $arbol1 = $arbol->Desglosar($especimen1['arbol'], $especimen1['niveles_arbol']);
          $arbol2 = $arbol->Desglosar($especimen2['arbol'], $especimen2['niveles_arbol']);

          // Sacas todas las puntuaciones
          $arrayresultado = $combate->Puntuar($especimen1,$especimen2, $arbol1, $arbol2);
          $puntos1 = $arrayresultado['puntos1'];
          $puntos2 = $arrayresultado['puntos2'];

          // AHora viendo los puntos de vida que quedaron, sabemos las victorias y derrotas y empates, que guardamos en el array
          if ((($combate->contrincante1['PV'] > 0) && ($combate->contrincante2['PV'] > 0)) ||
              (($combate->contrincante1['PV'] <= 0) && ($combate->contrincante2['PV'] <= 0)) )
          {
            $array[$i]['empate'] = $array[$i]['empate'] + 1;
            $array[$j]['empate'] = $array[$j]['empate'] + 1;
          }
          if (($combate->contrincante1['PV'] > 0) && ($combate->contrincante2['PV'] <= 0))
          {
            $array[$i]['victoria'] = $array[$i]['victoria'] + 1;
            $array[$j]['derrota'] = $array[$j]['derrota'] + 1;
          }
          if (($combate->contrincante1['PV'] <= 0) && ($combate->contrincante2['PV'] > 0))
          {
            $array[$i]['derrota'] = $array[$i]['derrota'] + 1;
            $array[$j]['victoria'] = $array[$j]['victoria'] + 1;
          }

          if ($debug_mode == 1)
          {          
            echo ("<br/>Combate entre ".$array[$i]['idespecimen']." y ".$array[$j]['idespecimen']." : puntos1 [".$puntos1."], puntos 2 [".$puntos2."]");
    	      echo ("/<br/>");
    	    }

          $array[$i]['puntuacion'] = $array[$i]['puntuacion'] + $puntos1;
          $array[$j]['puntuacion'] = $array[$j]['puntuacion'] + $puntos2;
        }
      }
    }
    echo ("<br/>Resultado final: ");
    $maxpuntos = -5555555;
    $ganador = 0;
    for ($i = 1; $i <= $cuantos; $i++)
    {
      if ($array[$i]['puntuacion'] > $maxpuntos)
      {
        $maxpuntos = $array[$i]['puntuacion'];
        $ganador = $array[$i]['idespecimen'];
      }
    }
    echo ("<br/> El ganador es el id ".$ganador." con ".$maxpuntos." puntos.");


    // Esto es un intento de ordenarlos
    foreach(array_keys($array) as $key)
    {
    	if ($debug_mode == 1)
      {          
        echo ("<br/>");
        echo $key." (".$array[$key]['idespecimen']."): ".$array[$key]['puntuacion'];
      }
      $temp[$key]=$array[$key]['puntuacion'];
    }
    arsort($temp, SORT_NUMERIC);
    foreach (array_keys($temp) as $key)
    {
    	if ($debug_mode == 1)
      {          
        echo ("<br/>Key : ".$key.",, temp[key]: ".$temp[$key]);
      }
      $sorted[] = $array[$key];
    }
    echo ("<br/>Sorted resultados del torneo : ");

    for ($i = 0; $i < count($sorted); $i++)
    {
      echo ("<br/>");
      echo ("Puesto ".($i+1)." : especimen ");
      echo $sorted[$i]['idespecimen'];
      echo (" - [");
      echo $sorted[$i]['puntuacion']."]";
      $especimen_torneo->GrabarPuntuacion($link_w, $sorted[$i]['idespecimen'], ($i+1), 0);
    }


    // Ahora entonces, si tenemos $grabar == 1, grabamos el resultado y convertimos los 0s al nuevo torneo



     // *******************************************
     // GUARDAR LOS DATOS DE GANADORES DEL TORNEO
     // *******************************************
      $jugador_aux_data = new Jugador();
      $especimen_aux_data = new Especimen();
      // CAMPEON NUMERO 1
      $especimen_aux_data->SacarDatosPorId($link_r, $sorted[0]['idespecimen']);
      $jugador_aux_data->SacarDatos($link_r, $especimen_aux_data->idpropietario);
      $textoganadores_en = "In this tournament the winner has been ".$jugador_aux_data->login.
         " with ".$sorted[0]['victoria']." victories, ".$sorted[0]['empate']." ties, and ".$sorted[0]['derrota']." defeats.";
      $textoganadores_es = "En este torneo el ganador ha sido ".$jugador_aux_data->login.
         " con ".$sorted[0]['victoria']." victorias, ".$sorted[0]['empate']." empates, y ".$sorted[0]['derrota']." derrotas.";
      // MEDALLA DE PLATA     
      $especimen_aux_data->SacarDatosPorId($link_r, $sorted[1]['idespecimen']);
      $jugador_aux_data->SacarDatos($link_r, $especimen_aux_data->idpropietario);
      $textoganadores_en = $textoganadores_en." The silver medal went to ".$jugador_aux_data->login.
         ", who won ".$sorted[1]['victoria']." fights, had ".$sorted[1]['empate']." ties, and lost ".$sorted[1]['derrota']." times.";
      $textoganadores_es = $textoganadores_es." La medalla de plata fue para ".$jugador_aux_data->login.
         ", que obtuvo ".$sorted[1]['victoria']." victorias, ".$sorted[1]['empate']." empates, y ".$sorted[1]['derrota']." derrotas.";
      // MEDALLA DE BRONCE
      $especimen_aux_data->SacarDatosPorId($link_r, $sorted[2]['idespecimen']);
      $jugador_aux_data->SacarDatos($link_r, $especimen_aux_data->idpropietario);
      $textoganadores_en = $textoganadores_en." Finally, the bronze medal was won by ".$jugador_aux_data->login.
         ", who had ".$sorted[2]['victoria']." victories, ".$sorted[2]['empate']." ties, and ".$sorted[2]['derrota']." defeats.";
      $textoganadores_es = $textoganadores_es." Finalmente, la medalla de bronce la gan&oacute; ".$jugador_aux_data->login.
         ", con ".$sorted[2]['victoria']." victorias, ".$sorted[2]['empate']." empates, y ".$sorted[2]['derrota']." derrotas.";
     // *******************************************        
     // GUARDAR LOS DATOS DE GANADORES DEL TORNEO
     // *******************************************

     $informe->textoganadores_es = $textoganadores_es;
     $informe->textoganadores_en = $textoganadores_en;





    if ($guardar == 1)
    {
echo ("!!!!GRABANDO RESULTADO !!!!");
      $torneo = new Torneo();
      $torneo->InsertarTorneoStandard($link_w, $idcampana);
      $idtorneonuevo = mysql_insert_id($link_w);
      $especimen_torneo->TrasladarEspecimenesTorneo($link_w, 0, $idtorneonuevo);







      // ************************************************************************
      //        + ENVIO DE EMAILS
      //        + SUMA DE PUNTOS
      //        + GRABACION EN EL ESPECIMEN ORO/PLATA/BRONCE
      // ************************************************************************

      // Al primer puesto le sumamos PRIMER_PUESTO (constantes en config/values.php)
      // Hacemos lo mismo con SEGUNDO_PUESTO y TERCER_PUESTO
      //
      // Luego ademas, para cada uno les enviamos un email


      $totalpartidos = ((count($sorted) * 2) - 2);
      

      // ***************************************************************
      //      CAMPEON, DINERO, ETC
      // ***************************************************************

      $especimen->SacarDatosPorId($link_r, $sorted[0]['idespecimen']);
      $arbol_oro = $especimen->arbol;
      $idjugador_oro = $especimen->idpropietario;
      $niveles_oro = $especimen->niveles_arbol;
      $iddeme_oro = $especimen->iddeme;
      $jugador_campana->SumarDinero($link_w, $especimen->idpropietario, $idcampana, PRIMER_PUESTO);
      $lang = $jugador->SacarLang($link_r, $especimen->idpropietario);
      $body = "<html><body>";
      $body = $body."<br/><center>";
      $body = $body."<img src=\"http://www.geneoverload.com/img/logomail.png\">";
      $body = $body."</center><br/>";
      if ($lang == 'en')
      {

//echo ("###".$sorted[0]['victoria']);
//echo ("###".$sorted[0]['empate']);
//echo ("###".$sorted[0]['derrota']);
        $subject = "Gene Overload: You've won a tournament!";
        $body = $body."<p style=\"font-size: 14px; \">You have won a Gold Medal in the Gene Arena!</p>";
        $body = $body."<p style=\"font-size: 12px; \">Your specimen in slot ".$especimen->idslot;
        if ($especimen->iddeme == 1)
        {
          $body = $body." from the depth deme ";
        }
        if ($especimen->iddeme == 2)
        {
          $body = $body." from the forest deme ";
        }
        if ($especimen->iddeme == 3)
        {
          $body = $body." from the volcano deme ";
        }
        $body = $body."participated in a total of ".$totalpartidos." fights. It won ".$sorted[0]['victoria'].", lost ".$sorted[0]['derrota'].", and made ".$sorted[0]['empate']." ties. Since it ";
        $body = $body."scored the best against the rest, your prize is ".PRIMER_PUESTO." credits.</p>";
        $body = $body."<p style=\"font-size: 12px; \">Congratulations, for your dedication and your abilities as a scientist have rised you to the top on Gene Arena today.</p>";
        $body = $body."<br/>";
        $body = $body."<p style=\"font-size: 11px; \">Please note: You have configured your account so you receive these messages. You can deactivate notification emails in your profile screen.</p>";
      } else {
        $subject = "Gene Overload: Has ganado un torneo!";
        $body = $body."<p style=\"font-size: 14px; \">Has ganado una Medalla de Oro en el Gene Arena!</p>";
        $body = $body."<p style=\"font-size: 12px; \">Tu specimen en el slot ".$especimen->idslot;
        if ($especimen->iddeme == 1)
        {
          $body = $body." del deme de las profundidades ";
        }
        if ($especimen->iddeme == 2)
        {
          $body = $body." del deme del bosque ";
        }
        if ($especimen->iddeme == 3)
        {
          $body = $body." del deme del volc&aacute;n ";
        }
        $body = $body."jug&oacute; un total de ".$totalpartidos." combates. Gan&oacute; ".$sorted[0]['victoria'].", perdi&oacute; ".$sorted[0]['derrota'].", y empat&oacute; ".$sorted[0]['empate'].".";
        $body = $body."Dado que ha obtenido la mejor puntuaci&oacute;n contra los dem&aacute;s jugadores, tu premio son ".PRIMER_PUESTO." cr&eacute;ditos.</p>";
        $body = $body."<p style=\"font-size: 12px; \">Felicidades, pues tu dedicaci&oacute;n y tus capacidades cient&iacute;ficas te han alzado hasta lo m&aacute;s alto hoy en el Gene Arena.</p>";
        $body = $body."<br/>";
        $body = $body."<p style=\"font-size: 11px; \">Nota : Has configurado tu cuenta para recibir estos mensajes. Puedes desactivar los emails de notificaci&oacute;n en tu pantalla de perfil.</p>";
      }
      $body = $body."</body></html>";
      $cabeceras  = 'MIME-Version: 1.0' . "\r\n";
      $cabeceras .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
      $cabeceras .= 'From: Gene Overload <no-reply@geneoverload.com> ' . "\r\n" .
                      'Reply-To: no-reply@geneoverload.com' . "\r\n" .
                      'X-Mailer: PHP/5.x'; // . phpversion();

      $jugador2->SacarDatos($link_r, $especimen->idpropietario);
      if ($jugador2->envio_emails == 1)
      {
        $email = $jugador2->email;
        echo ("#enviando mail a: ".$email."<br/>");
        $mail->enviar_mail($email, $subject, $body, $cabeceras);
      }




      // ***************************************************************
      //      ENVIANDO Y SUMANDO AL SEGUNDO JUGADOR
      // ***************************************************************

      if ($sorted[1]['idespecimen'] != null)
      {
        $especimen->SacarDatosPorId($link_r, $sorted[1]['idespecimen']);
        $arbol_plata = $especimen->arbol;
        $idjugador_plata = $especimen->idpropietario;
        $niveles_plata = $especimen->niveles_arbol;
        $iddeme_plata = $especimen->iddeme;
        $jugador_campana->SumarDinero($link_w, $especimen->idpropietario, $idcampana, SEGUNDO_PUESTO);

        $lang = $jugador->SacarLang($link_r, $especimen->idpropietario);
        $body = "<html><body>";
        $body = $body."<br/><center>";
        $body = $body."<img src=\"http://www.geneoverload.com/img/logomail.png\">";
        $body = $body."</center><br/>";
        if ($lang == 'en')
        {
          $subject = "Gene Overload: You've won a silver medal in a tournament!";
          $body = $body."<p style=\"font-size: 14px; \">You have won a silver medal in the Gene Arena!</p>";
          $body = $body."Your specimen participated in a total of ".$totalpartidos." fights. It won ".$sorted[0]['victoria'].", lost ".$sorted[0]['derrota'].", and made ".$sorted[0]['empate']." ties. Since it ";
          $body = $body."scored second best against the rest, your prize is ".SEGUNDO_PUESTO." credits.</p>";
//          $body = $body."<p style=\"font-size: 12px; \">Your specimen scored the second best against every other player, therefore your prize is ".SEGUNDO_PUESTO." credits.</p>";
          $body = $body."<p style=\"font-size: 12px; \">Congratulations, for your dedication and your abilities as a scientist have rised you high on the Gene Arena today.</p>";
          $body = $body."<br/>";
          $body = $body."<p style=\"font-size: 11px; \">Please note: You have configured your account so you receive these messages. You can deactivate notification emails in your profile screen.</p>";
        } else {
          $subject = "Gene Overload: Has ganado una medalla de plata en un torneo!";
          $body = $body."<p style=\"font-size: 14px; \">Has ganado una medalla de plata en el Gene Arena!</p>";
          $body = $body."Tu especimen jug&oacute; un total de ".$totalpartidos." combates. Gan&oacute; ".$sorted[0]['victoria'].", perdi&oacute; ".$sorted[0]['derrota'].", y empat&oacute; ".$sorted[0]['empate'].".";
          $body = $body."Dado que ha obtenido la segunda mejor puntuaci&oacute;n contra los dem&aacute;s jugadores, tu premio son ".SEGUNDO_PUESTO." cr&eacute;ditos.</p>";
//          $body = $body."<p style=\"font-size: 12px; \">Tu especimen ha obtenido la segunda mejor puntuaci&oacute;n contra los dem&aacute;s jugadores, y por lo tanto tu premio son ".SEGUNDO_PUESTO." cr&eacute;ditos.</p>";
          $body = $body."<p style=\"font-size: 12px; \">Felicidades, pues tu dedicaci&oacute;n y tus capacidades cient&iacute;ficas te han alzado muy alto hoy en el Gene Arena.</p>";
          $body = $body."<br/>";
          $body = $body."<p style=\"font-size: 11px; \">Nota : Has configurado tu cuenta para recibir estos mensajes. Puedes desactivar los emails de notificaci&oacute;n en tu pantalla de perfil.</p>";
        }
        $body = $body."</body></html>";
        $cabeceras  = 'MIME-Version: 1.0' . "\r\n";
        $cabeceras .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $cabeceras .= 'From: Gene Overload <no-reply@geneoverload.com> ' . "\r\n" .
                      'Reply-To: no-reply@geneoverload.com' . "\r\n" .
                      'X-Mailer: PHP/5.x'; // . phpversion();

//$email = 'amentoraz@gmail.com';
        $jugador2->SacarDatos($link_r, $especimen->idpropietario);
        if ($jugador2->envio_emails == 1)
        {
          $email = $jugador2->email;
          echo ("#enviando mail a: ".$email."<br/>");
          $mail->enviar_mail($email, $subject, $body, $cabeceras);
        }
        
        

        
        
      }




      if ($sorted[2]['idespecimen'] != null)
      {
        $especimen->SacarDatosPorId($link_r, $sorted[2]['idespecimen']);
        $jugador_campana->SumarDinero($link_w, $especimen->idpropietario, $idcampana, TERCER_PUESTO);
        $arbol_bronce = $especimen->arbol;
        $idjugador_bronce = $especimen->idpropietario;
        $iddeme_bronce = $especimen->iddeme;
        $niveles_bronce = $especimen->niveles_arbol;

        $lang = $jugador->SacarLang($link_r, $especimen->idpropietario);
        $body = "<html><body>";
        $body = $body."<br/><center>";
        $body = $body."<img src=\"http://www.geneoverload.com/img/logomail.png\">";
        $body = $body."</center><br/>";
        if ($lang == 'en')
        {
          $subject = "Gene Overload: You've won a bronze medal in a tournament!";
          $body = $body."<p style=\"font-size: 14px; \">You have won a bronze medal in the Gene Arena!</p>";
          $body = $body."Your specimen participated in a total of ".$totalpartidos." fights. It won ".$sorted[0]['victoria'].", lost ".$sorted[0]['derrota'].", and made ".$sorted[0]['empate']." ties. Since it ";
          $body = $body."scored third best against the rest, your prize is ".TERCER_PUESTO." credits.</p>";
//          $body = $body."<p style=\"font-size: 12px; \">Your specimen scored the third best against every other player, therefore your prize is ".TERCER_PUESTO." credits.</p>";
          $body = $body."<p style=\"font-size: 12px; \">Congratulations, for your dedication and your abilities as a scientist have rised you high on the Gene Arena today.</p>";
          $body = $body."<br/>";
          $body = $body."<p style=\"font-size: 11px; \">Please note: You have configured your account so you receive these messages. You can deactivate notification emails in your profile screen.</p>";
        } else {
          $subject = "Gene Overload: Has ganado una medalla de bronce en un torneo!";
          $body = $body."<p style=\"font-size: 14px; \">Has ganado una medalla de bronce en el Gene Arena!</p>";
//          $body = $body."<p style=\"font-size: 12px; \">Tu especimen ha obtenido la tercera mejor puntuaci&oacute;n contra los dem&aacute;s jugadores, y por lo tanto tu premio son ".TERCER_PUESTO." cr&eacute;ditos</p>";
          $body = $body."Tu especimen jug&oacute; un total de ".$totalpartidos." combates. Gan&oacute; ".$sorted[0]['victoria'].", perdi&oacute; ".$sorted[0]['derrota'].", y empat&oacute; ".$sorted[0]['empate'].".";
          $body = $body."Dado que ha obtenido la tercera mejor puntuaci&oacute;n contra los dem&aacute;s jugadores, tu premio son ".TERCER_PUESTO." cr&eacute;ditos.</p>";
          $body = $body."<p style=\"font-size: 12px; \">Felicidades, pues tu dedicaci&oacute;n y tus capacidades cient&iacute;ficas te han alzado muy alto hoy en el Gene Arena.</p>";
          $body = $body."<br/>";
          $body = $body."<p style=\"font-size: 11px; \">Nota : Has configurado tu cuenta para recibir estos mensajes. Puedes desactivar los emails de notificaci&oacute;n en tu pantalla de perfil.</p>";
        }
        $body = $body."</body></html>";
        $cabeceras  = 'MIME-Version: 1.0' . "\r\n";
        $cabeceras .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $cabeceras .= 'From: Gene Overload <no-reply@geneoverload.com> ' . "\r\n" .
                      'Reply-To: no-reply@geneoverload.com' . "\r\n" .
                      'X-Mailer: PHP/5.x'; // . phpversion();


        $jugador2->SacarDatos($link_r, $especimen->idpropietario);
        if ($jugador2->envio_emails == 1)
        {
          $email = $jugador2->email;
          echo ("#enviando mail a: ".$email."<br/>");
          $mail->enviar_mail($email, $subject, $body, $cabeceras);
        }
        
                



      }

      if ($sorted[3]['idespecimen'] != null)
      {
        $especimen->SacarDatosPorId($link_r, $sorted[3]['idespecimen']);
        $jugador_campana->SumarDinero($link_w, $especimen->idpropietario, $idcampana, CUARTO_PUESTO);
      }

      if ($sorted[4]['idespecimen'] != null)
      {
        $especimen->SacarDatosPorId($link_r, $sorted[4]['idespecimen']);
        $jugador_campana->SumarDinero($link_w, $especimen->idpropietario, $idcampana, QUINTO_PUESTO);
      }

      if ($sorted[5]['idespecimen'] != null)
      {
        $especimen->SacarDatosPorId($link_r, $sorted[5]['idespecimen']);
        $jugador_campana->SumarDinero($link_w, $especimen->idpropietario, $idcampana, SEXTO_PUESTO);
      }

      if ($sorted[6]['idespecimen'] != null)
      {
        $especimen->SacarDatosPorId($link_r, $sorted[6]['idespecimen']);
        $jugador_campana->SumarDinero($link_w, $especimen->idpropietario, $idcampana, SEPTIMO_PUESTO);
      }

      //  Vamos a sumar un torneito a la cuenta,
      // y de paso a los ganadores
      for ($i = 0; $i < count($sorted); $i++)
      {
        $especimen->SacarDatosPorId($link_r, $sorted[$i]['idespecimen']);
        $jugador_campana->SumarUnTorneo($link_w, $especimen->idpropietario, $idcampana);
        $dinero = 0;
        if ($i == 0)
        {
          $jugador_campana->SumarPosicion1($link_w, $especimen->idpropietario, $idcampana);
          $especimen->SumarOro($link_w, $sorted[$i]['idespecimen']);
	  $dinero = PRIMER_PUESTO;
        }
        if ($i == 1)
        {
          $jugador_campana->SumarPosicion2($link_w, $especimen->idpropietario, $idcampana);
          $especimen->SumarPlata($link_w, $sorted[$i]['idespecimen']);
	  $dinero = SEGUNDO_PUESTO;
        }
        if ($i == 2)
        {
          $jugador_campana->SumarPosicion3($link_w, $especimen->idpropietario, $idcampana);
          $especimen->SumarBronce($link_w, $sorted[$i]['idespecimen']);
	  $dinero = TERCER_PUESTO;
        }
        if ($i == 3)
        {
	  $dinero = CUARTO_PUESTO;
        }
        if ($i == 4)
        {
	  $dinero = QUINTO_PUESTO;
        }
        if ($i == 5)
        {
	  $dinero = SEXTO_PUESTO;
        }
        if ($i == 6)
        {
	  $dinero = SEPTIMO_PUESTO;
        }


        // Por ultimo, vamos a enviar un informe sobre su participacion a cada uno
        $informe->GenerarInformeTorneo($link_w, $idcampana, $especimen->idpropietario, ($i+1), $dinero, $especimen->iddeme, $especimen->idslot,
					$sorted[$i]['victoria'], $sorted[$i]['empate'], $sorted[$i]['derrota']);


        // Ahora dependiendo de las victorias, empates y derrotas, envejecemos.
        $porcentaje_derrotas = ($sorted[$i]['derrota'] / ($sorted[$i]['victoria'] + $sorted[$i]['empate'] + $sorted[$i]['derrota']));
        $porcentaje_empates = ($sorted[$i]['empate'] / ($sorted[$i]['victoria'] + $sorted[$i]['empate'] + $sorted[$i]['derrota']));
        $nuevaedad = $especimen->edad + (20 * ($porcentaje_derrotas)) + (5 * ($porcentaje_empates));

echo ("### Cambio de edad : ".$especimen->edad." -> ".$nuevaedad."### (%derrotas ".$porcentaje_derrotas.", %empates ".$porcentaje_empates.")<br/>");
        $especimen->Envejece($link_w, $sorted[$i]['idespecimen'], $nuevaedad);

//echo ("###".$sorted[0]['victoria']);
//echo ("###".$sorted[0]['empate']);
//echo ("###".$sorted[0]['derrota']);

        // Generamos un log para este usuario
        $log = new Log();
        $log->idjugador = $especimen->idpropietario;
        $log->idcampana = $idcampana;
        $log->tipo_suceso = 11; // 11, ha participado en un torneo
        $log->valor = ($i+1); // lugar en el que ha acabado
        $log->EscribirLog($link_w);


      }


    }



//      $textoganadores_en = $textoganadores_en." The silver medal went to ".$jugador2->login.
//         ", who won ".$sorted[1]['victoria']." fights, had ".$sorted[1]['empate']." ties, and lost ".$sorted[1]['derrota']." times.";
//      $textoganadores_es = $textoganadores_es." La medalla de plata fue para ".$jugador2->login.
//         ", que obtuvo ".$sorted[1]['victoria']." victorias, ".$sorted[1]['empate']." empates, y ".$sorted[1]['derrota']." derrotas.";

echo ("<br/>!!!");
echo "<br/>".$textoganadores_en;
echo "<br/>".$textoganadores_es;

    $string = "UNLOCK TABLES
		";
    $query = mysql_query($string, $link_w);


    // Timestamp en microsegundos de fin
    $unix_end = microtime_float();

    // Ahora lo grabamos en el torneo
    if ($guardar == 1)
    {
      $torneo->GrabarTiempoTorneo($link_w, $unix_begin, $unix_end, $idtorneonuevo);
      //$torneo->GrabarDemesTorneo($link_w, $unix_begin, $unix_end, $idtorneonuevo);
      $torneo->GrabarArbolesTorneo($link_w, $arbol_oro, $arbol_plata, $arbol_bronce, $idtorneonuevo);
      $torneo->GrabarNivelesTorneo($link_w, $niveles_oro, $niveles_plata, $niveles_bronce, $idtorneonuevo);
      $torneo->GrabarPropietariosTorneo($link_w, $idjugador_oro, $idjugador_plata, $idjugador_bronce, $idtorneonuevo);
      $torneo->GrabarDemesTorneo($link_w, $iddeme_oro, $iddeme_plata, $iddeme_bronce, $idtorneonuevo);
    }



  }

  // ****************************************
  //   Formulario de zona de control
  // ****************************************

  if ($accion == null)
  {

  $jugador = new Jugador();

  // Ahora procedemos a ejecutar lo que haya que ejecutar con el torneo

  // Vamos a sacar todos los ejemplares que hay para este torneo.

  $especimen_torneo = new Especimen_torneo();
  $cuantos = $especimen_torneo->ContarEspecimenesTorneo($link_r, 0);

  echo ("<p>");
  echo ("Hay ".$cuantos." espec&iacute;menes dispuestos a competir");
  echo ("</p>");

  $array = $especimen_torneo->BuscarEspecimenesTorneo($link_r, 0);
  if ($cuantos > 0)
  {
    echo ("<table border=\"1\" cellspacing=\"5\" cellpadding=\"10\">");
    echo ("<tr>");
    echo ("<th>Jugador</th>");
    echo ("<th>Idespecimen</th>");
    echo ("<th>Iddeme</th>");
    echo ("</tr>");
    for ($i = 1; $i <= $cuantos; $i++)
    {
      echo ("<tr>");
      echo ("<td>");
      $jugador->SacarDatos($link_r, $array[$i]['idjugador']);
      echo ($jugador->login);
      echo ("</td>");
      echo ("<td>");
      echo ($array[$i]['idespecimen']);
      echo ("</td>");
      echo ("<td>");
      $especimen = new Especimen();
      $especimen->SacarDatosPorId($link_r, $array[$i]['idespecimen']);
      echo ($especimen->iddeme);
      echo ("</td>");
      echo ("</tr>");
    }
    echo ("</table>");

    ?>
    <br/>
    <br/>

    <form method="post" action="evaluar_torneo.php">
      <input type="hidden" name="accion" value="torneo_todos_con_todos">
      <p>
       &iquest;Torneo de prueba? <select name="guardar">
        <option value="1">No: Guardar los resultados</option>
        <option value="0">Si: Ignorar los resultados</option>
       </select>
      </p>
      <p>
       &iquest;Modo de debug? <select name="debug_mode">
        <option value="1">Activado</option>
        <option value="0">Desactivado</option>
       </select>
      </p>

      <p>Campa&ntilde;a :
      <select name="idcampana">
      <?php
        $campana = new Campana();
        $arraycampanas = $campana->ListarCampanas($link_r);
        for ($i = 1; $i <= count($arraycampanas); $i++)
        {
          echo ("<option value=\"".$arraycampanas[$i]['id']."\">".$arraycampanas[$i]['nombre']."</option>");
        }
      ?>
      </select>
      </p>


      <p>
       <input type="submit" value="Torneo de todos contra todos">
      </p>
    </form>
    <?php

  } // El if de que hayan especimenes

 } // Cerramos el if de la accion





}


?>
