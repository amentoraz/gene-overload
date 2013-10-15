<?php

//
//
//  FUNCION DE ENVEJECIMIENTO
//
//  Recibe como parametro ['idcampana'] indicando la que queremos envejecer.
//
//  Recorrera todos los especimenes de los jugadores apuntados a esta campanya, anyadiendoles la edad propia de un dia.
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

    // Envejecimiento standard. Vamos sacando a todos los jugadores.
    //
    $idcampana = $_REQUEST['idcampana'];

    $debug_mode = 1;
    $lang = 'es';
    $es_premium = 1;
    $evolucion = new Evolucion($debug_mode, $es_premium, $lang);
//    $evolucion = new Evolucion();

    $especimen = new Especimen();
    $especimen_torneo = new Especimen_Torneo();
    $jugador = new Jugador();

    $jugador_campana = new Jugador_Campana();
    $cuantos = $jugador_campana->ContarElementosRank($link_r, $idcampana);
    $array = $jugador_campana->BuscarElementosRank($link_r, $idcampana, 13461356, 0, 1);

    for ($i=1; $i <= count($array); $i++)
    {

      $arraymuertos = '';
      $k = 0;

      // Ahora para cada jugador, vamos a sacar sus especimenes...
      $idjugador = $array[$i]['idjugador'];
      $jugador->SacarDatos($link_r, $idjugador);
      $jugador_campana->SacarDatos($link_r, $idjugador, $idcampana);

echo ("<br/>");
echo ($jugador->login." (".$array[$i]['id'].")");

      for ($d = 1; $d <= 3; $d++)
      {
        // Dependiendo del deme, le tomamos un maximo
        switch ($d)
        {
          case 1:
echo (" - Deme profundidades");
                $max_l = $jugador_campana->num_slots_deme_profundidades;
                break;
          case 2:
echo (" - Deme bosque");
                $max_l = $jugador_campana->num_slots_deme_bosque;
                break;
          case 3:
echo (" - Deme volcan");
                $max_l = $jugador_campana->num_slots_deme_volcan;
                break;
        }

        // Ahora para todos los slots vamos a operar
        for ($l = 1; $l <= $max_l; $l++)
        {
          $especimen->SacarDatos($link_r, $d, $l, $idjugador, $idcampana);
          $edad = $especimen->edad + rand(1,4) + 3;
          echo "[".$especimen->edad."->".$edad."]";
          $especimen->Envejece($link_w, $especimen->id, $edad);

          //  Ahora es cuando a partir de $edad hay que detectar si tenemos que
          // destruir y evolucionar al ejemplar.


//if ($array[$i]['id'] == '180')
//{
//echo ("DEBUG: AHORA SOLO CON EL ADMIN");


          if ($edad > 50) // <------------------- 50!!!
          {
            // Si tiene mas de 50, al hoyo.
            // La putada aqui es la siguiente: Si estan sin evaluar, Como sabe a quien elegir? No podemos forzar esto, en apariencia...
            $evaluados = $especimen->ComprobarEvaluadosDeme($link_r, $idjugador, $idcampana, $d);
            if ($evaluados == 0)
            {
              //  Aqui, en lugar de abortar la operacion lo que debemos hacer es puntuar de otro modo. Para
              // eso en obj_evolucion cuando la puntuacion es null, la pone a -25.
              echo ("[no evaluados]");
            }

            // A ver si tenemos que asignar nuevos
            $hay_en_torneo = $especimen_torneo->ContarJugadorTorneo($link_r, $idjugador, 0);
            $asignar_nuevo = 0;
            if ($hay_en_torneo > 0)
            {
              $especimen_torneo->ObtenEspecimenTorneo($link_r, 0, $idjugador);
echo ("<br/>El especimen que esta en el torneo es el ".$especimen_torneo->idespecimen.", y el idespecimen es el ".$especimen->id);
              // Vamos a ver si tenemos un especimen apuntado a un torneo
              $idespecimen_torneo = $especimen_torneo->idespecimen;
              // Si es este que vamos a evolucionar, luego reasignaremos el del torneo al desaparecer este
              if ($idespecimen_torneo == $especimen->id)
              {
                $asignar_nuevo = 1;
              }
            }

            // Ahora lo hacemos para el deme que sea este
            $hay_en_torneo = $especimen_torneo->ContarJugadorTorneo($link_r, $idjugador, $d);
            $asignar_nuevo_deme = 0;
            if ($hay_en_torneo > 0)
            {
              $especimen_torneo->ObtenEspecimenTorneo($link_r, $d, $idjugador);
              // Vamos a ver si tenemos un especimen apuntado a un torneo
              $idespecimen_torneo_deme = $especimen_torneo->idespecimen;
              // Si es este que vamos a evolucionar, luego reasignaremos el del torneo al desaparecer este
              if ($idespecimen_torneo_deme == $especimen->id)
              {
                $asignar_nuevo_deme = 1;
echo ("YES-");
              }
echo ("PUTATU deme ".$d.", idespecimen_torneo_deme ".$idespecimen_torneo_deme.", este especimen es ".$especimen->id);
            }






            // Marcamos como Old a este especimen, para borrarlo
            $especimen->HacerGeneracionOldDeme($link_w, $idjugador, $idcampana, $d);
            // Solo para el slot $l, en el deme $d (justo el seleccionado)
            $evolucion->EvolucionarEspecimen($link_r, $link_w, $idjugador, $idcampana, $d, $l, $jugador_campana->num_sexos, 0, 0);


            //  Si coincide el deme que hemos evolucionado con el
            // seleccionado para torneo, re-seleccionamos ejemplar
            if ($asignar_nuevo == 1)
            {
echo ("<br/>???? Ha petado el especimen seleccionado, asi que lo ponemos al 1-1 ????");
              $especimen->SacarDatos($link_r, 1, 1, $idjugador, $idcampana); // los dos 1s son iddeme e idslot respectivamente
echo ("(esp ".$especimen->id.")");
              $especimen_torneo->ApuntarTorneo($link_w, $especimen->id,0);
            }
            // Lo mismo para el deme
            if ($asignar_nuevo_deme == 1)
            {
              $especimen->SacarDatos($link_r, $d, 1, $idjugador, $idcampana); // El idslot sera el 1, pero el iddeme es $d
              $especimen_torneo->ApuntarTorneo($link_w, $especimen->id, $d);
echo ("NUEVODEME ".$especimen->id);
            }





            // Borramos el que ocupaba este slot anteriormente
            $especimen->BorrarGeneracionOldIndividuo($link_w, $idjugador, $idcampana, $d, $l);
            $especimen->DesHacerGeneracionOldDeme($link_w, $idjugador, $idcampana, $d);

            // Guardamos el especimen
            $k++;
            $arraymuertos[$k]['iddeme'] = $d;
            $arraymuertos[$k]['idslot'] = $l;

          } // Fin de if $edad == 50

//} // DEBUG PARA QUE SEA ADMIN=180


        }

      } // Fin del while de los 3 demes


      // Si han habido muertos... INFORME!!!
      if ($arraymuertos != '')
      {
        // Vamos a generar un informe para avisar a los jugadores de sus muertos.
        $informe = new Informe();
        $informe->tipo = '7';
        if ($jugador->lang == 'en')
        {
          if (count($arraymuertos) > 1)
          {
            $informe->subject = 'Some of your specimens have died';
            $texto = 'The following specimens died, most probably because of their age: ';
          } else {
            $informe->subject = 'One of your specimens has died';
            $texto = 'The following specimen died, most probably because of its age: ';
          }
        } else {
          if (count($arraymuertos) > 1)
          {
            $informe->subject = 'Algunos de tus espec&iacute;menes han muerto';
            $texto = 'Los siguientes espec&iacute;menes han muerto, con toda probablidad debido a su edad: ';
          } else {
            $informe->subject = 'Uno de tus espec&iacute;menes ha muerto';
            $texto = 'El siguiente especimen ha muerto, con toda probablidad debido a su edad: ';
          }
        }
        // Ahora los listamos todos
        $texto = $texto."<br/>";
        for ($n = 1; $n <= count($arraymuertos); $n++)
        {
          $texto = $texto."<br/>";
          $iddeme = $arraymuertos[$n]['iddeme'];
          if($jugador->lang == 'en')
          {
            switch ($iddeme)
            {
              case 1: $texto = $texto." - Slot ".$arraymuertos[$n]['idslot']." from the Abyssal depths deme."; break;
              case 2: $texto = $texto." - Slot ".$arraymuertos[$n]['idslot']." from the Forest deme."; break;
              case 3: $texto = $texto." - Slot ".$arraymuertos[$n]['idslot']." from the Volcano deme."; break;
            }
          } else {
            switch ($iddeme)
            {
              case 1: $texto = $texto." - Hueco ".$arraymuertos[$n]['idslot']." del deme de las Profundidades."; break;
              case 2: $texto = $texto." - Hueco ".$arraymuertos[$n]['idslot']." del deme del Bosque."; break;
              case 3: $texto = $texto." - Hueco ".$arraymuertos[$n]['idslot']." del deme del Volc&aacute;n."; break;
            }
          }
        }
        if ($jugador->lang == 'en')
        {
            $texto = $texto."<br/><br/> Dead specimens were replaced by others automatically evolved. You might want to test their abilities!";
        } else {
          $texto = $texto."<br/><br/> Los espec&iacute;menes muertos fueron sustitu&iacute;dos por otros evolucionados autom&aacute;ticamente. &iexcl;Quiz&aacute; quieras probar sus capacidades!";
        }
        $informe->texto = $texto;
        $informe->EnviarInformeRaw($link_w, $idjugador, $idcampana);

        unset($informe);
      }



    }

//   CUANDO HAYA QUE MODIFICAR!!!
//        $string = "LOCK TABLES especimen WRITE
//                ";
//    $query = mysql_query($string, $link_w);




//    $string = "UNLOCK TABLES
//                ";
//    $query = mysql_query($string, $link_w);



  }


}


?>
