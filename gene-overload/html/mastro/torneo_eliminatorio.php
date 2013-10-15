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
        || (realip() == '83.54.3.132')
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
  include ("../clases/obj_resolucion_torneo.php");
  include ("../clases/obj_campana.php");
  include ("../clases/obj_informe.php");
  include ("../clases/obj_log.php");
  include ("../clases/obj_mail.php");

  echo ("Verificaci&oacute;n de IP correcta...");



  $accion = $_REQUEST['accion'];



  // PRIMERO EN TODA OCASION, LIMPIAMOS
  //
  //

  $string = "SELECT a.id
	FROM especimen_torneo a
	LEFT JOIN especimen b ON b.id = a.idespecimen
	WHERE b.id IS NULL
        ";
  $query = mysql_query($string, $link_r);
  echo ("<br/>");
  echo ("#A eliminar : ".mysql_num_rows($query));
  while ($unquery = mysql_fetch_array($query))
  {
    $miid = $unquery['id'];
    $delete = "DELETE
	FROM especimen_torneo
	WHERE id = $miid";
    mysql_query($delete, $link_w);
  }
  echo ("<br/>");

  $string = "SELECT a.id
	FROM especimen_torneo a
	LEFT JOIN especimen b ON b.id = a.idespecimen
	WHERE b.id IS NOT NULL
        ";
  $query = mysql_query($string, $link_r);
  echo ("<br/>");
  echo ("#A conservar : ".mysql_num_rows($query));
  echo ("<br/>");




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
    $iddeme = $_REQUEST['iddeme'];
echo ("#".$iddeme."#");

    $informe = new Informe();
    $jugador = new Jugador();
    $jugador2 = new Jugador();
    $jugador_campana = new Jugador_Campana();
    $especimen_torneo = new Especimen_torneo();
    $especimen = new Especimen();
//    $combate = new Combate($debug_mode);
    $combate = new Combate(0);
    $arbol = new Arbol();
    $resolucion_torneo = new Resolucion_Torneo();

    // Ahora dependiendo de iddeme se sacara una lista u otra de individuos

    echo ("<p>");
/*
    if ($iddeme == 0)
    {
      $cuantos = $especimen_torneo->ContarEspecimenesTorneo($link_r, 0, $idcampana);
      $array = $especimen_torneo->BuscarEspecimenesTorneo($link_r, 0, $idcampana);
      echo ("Enfrentando en torneo absoluto a ".$cuantos." espec&iacute;menes dispuestos a competir");
    } else {
      $cuantos = $especimen_torneo->ContarEspecimenesTorneo($link_r, 0, $idcampana);
      $array = $especimen_torneo->BuscarEspecimenesTorneo($link_r, 0, $idcampana);
//      $cuantos = $resolucion_torneo->ContarEspecimenesTorneo($link_r, $idcampana);
//      $array = $resolucion_torneo->BuscarEspecimenesTorneoDeme($link_r, $iddeme, $idcampana);
    }
*/
    $cuantos = $especimen_torneo->ContarEspecimenesTorneo($link_r, $iddeme, $idcampana);
    $array = $especimen_torneo->BuscarEspecimenesTorneo($link_r, $iddeme, $idcampana);
    echo ("</p>");

    // Ponemos a cero la puntuacion inicial para todos
    for ($i = 1; $i <= $cuantos; $i++)
    {
      $array[$i]['puntuacion'] = 0;
      $array[$i]['victoria'] = 0;
      $array[$i]['derrota'] = 0;
      $array[$i]['empate'] = 0;
    }

//echo ("!".$cuantos);
    //  Si son mas de 32, vamos a reducir su cifra haciendo una primera ronda eliminatoria.
    if ($cuantos > 32)
    {
      //  Cada uno de los especimenes se va a enfrentar contra N especimenes aleatorios de los seleccionados.
      //  Como calculamos N? Deberia ser minimo 4 (en el caso de ser 32) e ir aumentando lentamente. Para ello sera
      // un logaritmo, y en la practica e parece bastante acertado, tomando el ceiling del numero resultante
      $numcombates_raw = log(pow($cuantos,2));
      if ($debug_mode == 1)
      {
        echo ("Logaritmo en base e de (".$cuantos."/2)^2 (jugadores) es ".$numcombates_raw);
      }
      $numcombates = ceil($numcombates_raw);
      if ($debug_mode == 1)
      {
        echo ("y resulta en ".$numcombates." combates.<br/>");
      }
      // Este for para todos los luchadores
      for ($i = 1; $i <= $cuantos; $i++)
      {
        $idespecimen = $array[$i]['idespecimen'];
        // Este for para cada combate de un especimen luchador
        for ($j = 1; $j <= $numcombates; $j++)
        {
          $idadversario = $array[$i]['idespecimen'];
          while ($idadversario == $array[$i]['idespecimen'])
          {
            $nadversario = rand(1,$cuantos); // Elegimos un adversario al azar, y solo sale del while si no es el mismo
            $idadversario = $array[$nadversario]['idespecimen'];
          }
          $especimen1 = $especimen->Obtener_Por_Id($link_r, $idespecimen);
          $especimen2 = $especimen->Obtener_Por_Id($link_r, $idadversario);

          $arbol1 = $arbol->Desglosar($especimen1['arbol'], $especimen1['niveles_arbol']);
          $arbol2 = $arbol->Desglosar($especimen2['arbol'], $especimen2['niveles_arbol']);

          // Sacas todas las puntuaciones
          $arrayresultado = $combate->Puntuar($especimen1,$especimen2, $arbol1, $arbol2);
          $puntos1 = $arrayresultado['puntos1'];

          if ($debug_mode == 1)
          {
             echo ("<br/>Combate entre ".$idespecimen." y ".$idadversario." : puntos1 [".$puntos1."], puntos 2 [".$puntos2."]");
             echo ("/<br/>");
          }

          // Sumamos los puntos, y con esto nos damos por contentos
          $array[$i]['puntuacion'] = $array[$i]['puntuacion'] + $puntos1;

        } // Termina el for de combates
      } // Termina el for de jugadores

      // Ahora tenemos una lista de jugadores que podemos reducir a 32.
      // Lo primero entonces es ordenar la lista

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
      if ($debug_mode == 1)
      {
        echo ("<br/>Sorted resultados del torneo : ");
      }
      for ($i = 0; $i < 32; $i++)
      {
        $array_filtrados[($i+1)]['idespecimen'] = $sorted[$i]['idespecimen'];
        $array_filtrados[($i+1)]['idjugador'] = $sorted[$i]['idjugador'];
        if ($debug_mode == 1)
        {
          echo ("<br/>");
          echo ("Puesto ".($i+1)." : especimen ");
          echo $sorted[$i]['idespecimen'];
          echo (" - [");
          echo $sorted[$i]['puntuacion']."]";
        }
echo $sorted[$i]['puntuacion']."<------ PUNTUACION SORTED ".$i."X";
  $array_filtrados[($i+1)]['puntuacion_antigua'] = $sorted[$i]['puntuacion'];
      }

      //  Antes metiamos aqui los informes de perdedores eliminados, pero realmente
      // mejor enviando toda la info del torneo

    } else { // Termina el if de $cuantos > 32
      $array_filtrados = $array;
    }

    $umbral = $array_filtrados[32]['puntuacion_antigua'];

echo ("<br/>UMBRAL : ".$array_filtrados[32]['puntuacion_antigua']);





    if ($debug_mode == 1)
    {
      echo ("<br/>Comenzando segunda fase con maximo de 32 contendientes<br/>");
    }
    // SEGUNDA FASE - TODOS CONTRA TODOS
    //
    //  Ahora ya tenemos los entre [1..32] luchadores que van a enfrentarse en una
    // ronda final de todos contra todos.

    // Ponemos a cero la puntuacion inicial para todos
    for ($i = 1; $i <= count($array_filtrados); $i++)
    {
echo ("#".$array_filtrados[$i]['idespecimen']);
      $array_filtrados[$i]['puntuacion'] = 0;
      $array_filtrados[$i]['victoria'] = 0;
      $array_filtrados[$i]['derrota'] = 0;
      $array_filtrados[$i]['empate'] = 0;
    }


    for ($i = 1; $i <= count($array_filtrados); $i++)
    {

        $idespecimen = $array_filtrados[$i]['idespecimen'];
        // Este for para cada combate de un especimen luchador
        for ($j = 1; $j <= count($array_filtrados); $j++)
        {
   	
          $idadversario = $array_filtrados[$j]['idespecimen'];
          if ($i != $j) // No queremos enfrentarlos consigo mismos!
          {
 echo ($i." vs ".$j."<br/>");        	
              	
            $especimen1 = $especimen->Obtener_Por_Id($link_r, $idespecimen);
            $especimen2 = $especimen->Obtener_Por_Id($link_r, $idadversario);
            // Ya tenemos a los que van a pelear (mi polla y tu paladar)
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
              $array_filtrados[$i]['empate'] = $array_filtrados[$i]['empate'] + 1;
              $array_filtrados[$j]['empate'] = $array_filtrados[$j]['empate'] + 1;
              
            }
            if (($combate->contrincante1['PV'] > 0) && ($combate->contrincante2['PV'] <= 0))
            {
              $array_filtrados[$i]['victoria'] = $array_filtrados[$i]['victoria'] + 1;
              $array_filtrados[$j]['derrota'] = $array_filtrados[$j]['derrota'] + 1;
            }
            if (($combate->contrincante1['PV'] <= 0) && ($combate->contrincante2['PV'] > 0))
            {
              $array_filtrados[$i]['derrota'] = $array_filtrados[$i]['derrota'] + 1;
              $array_filtrados[$j]['victoria'] = $array_filtrados[$j]['victoria'] + 1;
            }

            if ($debug_mode == 1)
            {
              echo ("<br/>Combate entre ".$array_filtrados[$i]['idespecimen']." y ".$array_filtrados[$j]['idespecimen']." : puntos1 [".$puntos1."], puntos 2 [".$puntos2."]");
              echo ("/<br/>");
            }

            $array_filtrados[$i]['puntuacion'] = $array_filtrados[$i]['puntuacion'] + $puntos1;
            $array_filtrados[$j]['puntuacion'] = $array_filtrados[$j]['puntuacion'] + $puntos2;

          }
        }
    }

    // En este punto ya se han llevado a cabo todos los combates. Toca ahora ponerles un orden
    foreach(array_keys($array_filtrados) as $key2)
    {
      if ($debug_mode == 1)
      {
        echo ("<br/>");
        echo $key2." (".$array_filtrados[$key2]['idespecimen']."): ".$array_filtrados[$key2]['puntuacion'];
      }
      $temp2[$key2]=$array_filtrados[$key2]['puntuacion'];
    }
    arsort($temp2, SORT_NUMERIC);
    foreach (array_keys($temp2) as $key2)
    {
      if ($debug_mode == 1)
      {
        echo ("<br/>Key : ".$key2.",, temp[key]: ".$temp2[$key2]);
      }
      $sorted_final[] = $array_filtrados[$key2];
    }
    // IMprimimos los resultados si debug
    if ($debug_mode == 1)
    {
      for ($i = 0; $i < 32; $i++)
      {

          echo ("<br/>");
          echo ("Puesto ".($i+1)." : especimen ");
          echo $sorted_final[$i]['idespecimen'];
          echo (" - [");
          echo $sorted_final[$i]['puntuacion']."]";
      }
    }


    // --------------------------
    //       FASE TERCERA
    // --------------------------
    //  El torneo ya ha sido disputado y solo queda guardar los datos


      // Lo primero que vamos a hacer es preparar el texto de resumen.

      $jugador_aux_data = new Jugador();
      $especimen_aux_data = new Especimen();
      // CAMPEON NUMERO 1
      $especimen_aux_data->SacarDatosPorId($link_r, $sorted_final[0]['idespecimen']);
      $jugador_aux_data->SacarDatos($link_r, $especimen_aux_data->idpropietario);
      $textoganadores_en = "In this tournament the winner has been <a href=\"index.php?catid=5&accion=ver&idcampana=".$idcampana."&idelemento=".$jugador_aux_data->id."\">".$jugador_aux_data->login."</a>".
         " with ".$sorted_final[0]['puntuacion']." points in the final round, obtained in ".$sorted_final[0]['victoria']." victories, ".$sorted_final[0]['empate']." ties, and ".$sorted_final[0]['derrota']." defeats.";
      $textoganadores_es = "En este torneo el ganador ha sido <a href=\"index.php?catid=5&accion=ver&idcampana=".$idcampana."&idelemento=".$jugador_aux_data->id."\">".$jugador_aux_data->login."</a>".
         " con ".$sorted_final[0]['puntuacion']." puntos en la ronda final, obtenidos en ".$sorted_final[0]['victoria']." victorias, ".$sorted_final[0]['empate']." empates, y ".$sorted_final[0]['derrota']." derrotas.";
      // MEDALLA DE PLATA     
      $especimen_aux_data->SacarDatosPorId($link_r, $sorted_final[1]['idespecimen']);
      $jugador_aux_data->SacarDatos($link_r, $especimen_aux_data->idpropietario);
      $textoganadores_en = $textoganadores_en." The silver medal went to <a href=\"index.php?catid=5&accion=ver&idcampana=".$idcampana."&idelemento=".$jugador_aux_data->id."\">".$jugador_aux_data->login."</a>".
         ", with ".$sorted_final[1]['puntuacion']." points obtained in ".$sorted_final[1]['victoria']." victories, ".$sorted_final[1]['empate']." ties, and ".$sorted_final[1]['derrota']." defeats.";
      $textoganadores_es = $textoganadores_es." La medalla de plata fue para <a href=\"index.php?catid=5&accion=ver&idcampana=".$idcampana."&idelemento=".$jugador_aux_data->id."\">".$jugador_aux_data->login."</a>".
         ", con ".$sorted_final[1]['puntuacion']." puntos obtenidos en ".$sorted_final[1]['victoria']." victorias, ".$sorted_final[1]['empate']." empates, y ".$sorted_final[1]['derrota']." derrotas.";
      // MEDALLA DE BRONCE
      $especimen_aux_data->SacarDatosPorId($link_r, $sorted_final[2]['idespecimen']);
      $jugador_aux_data->SacarDatos($link_r, $especimen_aux_data->idpropietario);
      $textoganadores_en = $textoganadores_en." Finally, the bronze medal was won by <a href=\"index.php?catid=5&accion=ver&idcampana=".$idcampana."&idelemento=".$jugador_aux_data->id."\">".$jugador_aux_data->login."</a>".
         ", who obtained ".$sorted_final[2]['puntuacion']." points in ".$sorted_final[2]['victoria']." victories, ".$sorted_final[2]['empate']." ties, and ".$sorted_final[2]['derrota']." defeats.";
      $textoganadores_es = $textoganadores_es." Finalmente, la medalla de bronce la gan&oacute; <a href=\"index.php?catid=5&accion=ver&idcampana=".$idcampana."&idelemento=".$jugador_aux_data->id."\">".$jugador_aux_data->login."</a>".
         ", que obtuvo ".$sorted_final[2]['puntuacion']." puntos en ".$sorted_final[2]['victoria']." victorias, ".$sorted_final[2]['empate']." empates, y ".$sorted_final[2]['derrota']." derrotas.";
     // Y a la clase $informe que van
     $informe->textoganadores_es = $textoganadores_es;
     $informe->textoganadores_en = $textoganadores_en;


     $totalpartidos = ((count($sorted_final) * 2) - 2);




      // ******************* PERDEDORES ELIMINADOS ************************
      //  En este punto deberiamos de enviarle un informe a los que han perdido en la
      // anterior ronda eliminatoria.
      for ($i = 32; $i < count($sorted); $i++)
      {
        echo ("<br/>");
        echo ("Repasando los que quedaron fuera : ");
        // Aqui repasamos todos los que se han quedado fuera.
        if ($debug_mode == 1)
        {
          echo ("<br/>");
          echo ("Puesto ".($i+1)." : especimen ");
          echo $sorted[$i]['idespecimen'];
          echo (" - [");
          echo $sorted[$i]['puntuacion']."]";
        }
        echo ("<br/>");

        // Vamos a enviarles un informe a cada uno mas breve
        // Aqui $iddeme es el tipo de torneo
        $especimen->SacarDatosPorId($link_r, $sorted[$i]['idespecimen']);
echo ("#".$especimen->idpropietario."#".$especimen->iddeme."#".$especimen->idslot."$");
        if ($guardar == 1)
        {
//          $informe->GenerarInformeDerrotaTorneo($link_w, $especimen->idpropietario, $idcampana, $especimen->iddeme, $especimen->idslot, $puntuacion, $iddeme);
          $informe->GenerarInformeDerrotaTorneo($link_w, $especimen->idpropietario, $idcampana, $especimen->iddeme, $especimen->idslot, $sorted[$i]['puntuacion'], $iddeme, $umbral);
        }
      }
      // ******************* PERDEDORES ELIMINADOS ************************






     //  Solo se van a guardar con sus medallas cuando sea un torneo sin deme. En otro
     // caso solo se suma el dinero y se envia un informe.
     if ($iddeme > 0)
     {
       if ($guardar == 1)
       {

//$informe->GenerarInformeDerrotaTorneo($link_w, $especimen->idpropietario, $idcampana, $especimen->iddeme, $especimen->idslot, $sorted_final[$i]['puntuacion'], $iddeme);

         // Sumamos el dinero
         if ($sorted_final[0]['idespecimen'] != null)
         {
           $especimen->SacarDatosPorId($link_r, $sorted_final[0]['idespecimen']);
           $arbol_oro = $especimen->arbol;
           $dinero = ceil(PRIMER_PUESTO / 2);
           $jugador_campana->SumarDinero($link_w, $especimen->idpropietario, $idcampana, $dinero);
         }
         if ($sorted_final[1]['idespecimen'] != null)
         {
           $especimen->SacarDatosPorId($link_r, $sorted_final[1]['idespecimen']);
           $arbol_plata = $especimen->arbol;
           $dinero = ceil(SEGUNDO_PUESTO / 2);
           $jugador_campana->SumarDinero($link_w, $especimen->idpropietario, $idcampana, $dinero);
         }
         if ($sorted_final[2]['idespecimen'] != null)
         {
           $especimen->SacarDatosPorId($link_r, $sorted_final[2]['idespecimen']);
           $arbol_bronce = $especimen->arbol;
           $dinero = ceil(TERCER_PUESTO / 2);
           $jugador_campana->SumarDinero($link_w, $especimen->idpropietario, $idcampana, $dinero);
         }
         if ($sorted_final[3]['idespecimen'] != null)
         {
           $especimen->SacarDatosPorId($link_r, $sorted_final[3]['idespecimen']);
           $dinero = ceil(CUARTO_PUESTO / 2);
           $jugador_campana->SumarDinero($link_w, $especimen->idpropietario, $idcampana, $dinero);
         }
         if ($sorted_final[4]['idespecimen'] != null)
         {
           $especimen->SacarDatosPorId($link_r, $sorted_final[4]['idespecimen']);
           $dinero = ceil(QUINTO_PUESTO / 2);
           $jugador_campana->SumarDinero($link_w, $especimen->idpropietario, $idcampana, $dinero);
         }
         if ($sorted_final[5]['idespecimen'] != null)
         {
           $especimen->SacarDatosPorId($link_r, $sorted_final[5]['idespecimen']);
           $dinero = ceil(SEXTO_PUESTO / 2);
           $jugador_campana->SumarDinero($link_w, $especimen->idpropietario, $idcampana, $dinero);
         }
         if ($sorted_final[6]['idespecimen'] != null)
         {
           $especimen->SacarDatosPorId($link_r, $sorted_final[6]['idespecimen']);
           $dinero = ceil(SEPTIMO_PUESTO / 2);
           $jugador_campana->SumarDinero($link_w, $especimen->idpropietario, $idcampana, $dinero);
         }

         // Ahora generamos el informe
         for ($i = 0; $i < count($sorted_final); $i++)
         {
           $dinero = 0; // Si no hay algun puesto, es 0, pero hay que enviarlo igual
           $lm = ($i + 1);
           switch($lm)
           {
             case 1: $dinero = ceil(PRIMER_PUESTO / 2); break;
             case 2: $dinero = ceil(SEGUNDO_PUESTO / 2); break;
             case 3: $dinero = ceil(TERCER_PUESTO / 2); break;
             case 4: $dinero = ceil(CUARTO_PUESTO / 2); break;
             case 5: $dinero = ceil(QUINTO_PUESTO / 2); break;
             case 6: $dinero = ceil(SEXTO_PUESTO / 2); break;
             case 7: $dinero = ceil(SEPTIMO_PUESTO / 2); break;
           }
           $especimen->SacarDatosPorId($link_r, $sorted_final[$i]['idespecimen']);
           $informe->GenerarInformeTorneoDeme($link_w, $idcampana, $especimen->idpropietario, ($i+1), $dinero, $especimen->iddeme, $especimen->idslot,
                                        $sorted_final[$i]['victoria'], $sorted_final[$i]['empate'], $sorted_final[$i]['derrota'], 
					$sorted_final[$i]['puntuacion'], $sorted_final[$i]['puntuacion_antigua'], $umbral);
         }


       }


     } else {
       //  En el caso de que sea de todos los demes, ademas de sumar dinero y enviar informe,
       // se va a *enviar un email y *sumar a las victorias cosechadas

       if ($guardar == 1)
       {
         //              GANADOR
         // Sumamos dinero, enviamos mail, etc
         if ($sorted_final[0]['idespecimen'] != null)
         {
           $especimen->SacarDatosPorId($link_r, $sorted_final[0]['idespecimen']);
           $arbol_oro = $especimen->arbol;
            $idjugador_oro = $especimen->idpropietario;
            $niveles_oro = $especimen->niveles_arbol;
            $iddeme_oro = $especimen->iddeme;
           $dinero = PRIMER_PUESTO;
           $jugador_campana->SumarDinero($link_w, $especimen->idpropietario, $idcampana, $dinero);
            $lang = $jugador->SacarLang($link_r, $especimen->idpropietario);
            $body = "<html><body>";
            $body = $body."<br/><center>";
            $body = $body."<img src=\"http://www.geneoverload.com/img/logomail.png\">";
            $body = $body."</center><br/>";
            if ($lang == 'en')
            {
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
              $body = $body."participated in a total of ".$totalpartidos." fights. It won ".$sorted_final[0]['victoria'].", lost ".$sorted_final[0]['derrota'].", and made ".$sorted_final[0]['empate']." ties. Since it ";
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
              $body = $body."jug&oacute; un total de ".$totalpartidos." combates. Gan&oacute; ".$sorted_final[0]['victoria'].", perdi&oacute; ".$sorted_final[0]['derrota'].", y empat&oacute; ".$sorted_final[0]['empate'].".";
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
           //              GANADOR
         }


           //              SEGUNDA POSICION - MEDALLA DE PLATA
         if ($sorted_final[1]['idespecimen'] != null)
         {
           // Sumamos dinero, enviamos mail, etc
           $especimen->SacarDatosPorId($link_r, $sorted_final[1]['idespecimen']);
           $arbol_plata = $especimen->arbol;
            $idjugador_plata = $especimen->idpropietario;
            $niveles_plata = $especimen->niveles_arbol;
            $iddeme_plata = $especimen->iddeme;
           $dinero = SEGUNDO_PUESTO;
           $jugador_campana->SumarDinero($link_w, $especimen->idpropietario, $idcampana, $dinero);

            $lang = $jugador->SacarLang($link_r, $especimen->idpropietario);
            $body = "<html><body>";
            $body = $body."<br/><center>";
            $body = $body."<img src=\"http://www.geneoverload.com/img/logomail.png\">";
            $body = $body."</center><br/>";
            if ($lang == 'en')
            {
              $subject = "Gene Overload: You've won a silver medal in a tournament!";
              $body = $body."<p style=\"font-size: 14px; \">You have won a silver medal in the Gene Arena!</p>";
              $body = $body."Your specimen participated in a total of ".$totalpartidos." fights. It won ".$sorted_final[1]['victoria'].", lost ".$sorted_final[1]['derrota'].", and made ".$sorted_final[1]['empate']." ties. Since it ";
              $body = $body."scored second best against the rest, your prize is ".SEGUNDO_PUESTO." credits.</p>";
              $body = $body."<p style=\"font-size: 12px; \">Congratulations, for your dedication and your abilities as a scientist have rised you high on the Gene Arena today.</p>";
              $body = $body."<br/>";
              $body = $body."<p style=\"font-size: 11px; \">Please note: You have configured your account so you receive these messages. You can deactivate notification emails in your profile screen.</p>";
            } else {
              $subject = "Gene Overload: Has ganado una medalla de plata en un torneo!";
              $body = $body."<p style=\"font-size: 14px; \">Has ganado una medalla de plata en el Gene Arena!</p>";
              $body = $body."Tu especimen jug&oacute; un total de ".$totalpartidos." combates. Gan&oacute; ".$sorted_final[1]['victoria'].", perdi&oacute; ".$sorted_final[1]['derrota'].", y empat&oacute; ".$sorted_final[1]['empate'].".";
              $body = $body."Dado que ha obtenido la segunda mejor puntuaci&oacute;n contra los dem&aacute;s jugadores, tu premio son ".SEGUNDO_PUESTO." cr&eacute;ditos.</p>";
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
            //              SEGUNDA POSICION - MEDALLA DE PLATA
         }

            //              TERCERA POSICION - MEDALLA DE BRONCE
         if ($sorted_final[2]['idespecimen'] != null)
         {
           $especimen->SacarDatosPorId($link_r, $sorted_final[2]['idespecimen']);
           $arbol_bronce = $especimen->arbol;
            $idjugador_bronce = $especimen->idpropietario;
            $iddeme_bronce = $especimen->iddeme;
            $niveles_bronce = $especimen->niveles_arbol;
           $dinero = TERCER_PUESTO;
           $jugador_campana->SumarDinero($link_w, $especimen->idpropietario, $idcampana, $dinero);

            $lang = $jugador->SacarLang($link_r, $especimen->idpropietario);
            $body = "<html><body>";
            $body = $body."<br/><center>";
            $body = $body."<img src=\"http://www.geneoverload.com/img/logomail.png\">";
            $body = $body."</center><br/>";
            if ($lang == 'en')
            {
              $subject = "Gene Overload: You've won a bronze medal in a tournament!";
              $body = $body."<p style=\"font-size: 14px; \">You have won a bronze medal in the Gene Arena!</p>";
              $body = $body."Your specimen participated in a total of ".$totalpartidos." fights. It won ".$sorted_final[2]['victoria'].", lost ".$sorted_final[2]['derrota'].", and made ".$sorted_final[2]['empate'].
 		" ties. Since it ";
              $body = $body."scored third best against the rest, your prize is ".TERCER_PUESTO." credits.</p>";
              $body = $body."<p style=\"font-size: 12px; \">Congratulations, for your dedication and your abilities as a scientist have rised you high on the Gene Arena today.</p>";
              $body = $body."<br/>";
              $body = $body."<p style=\"font-size: 11px; \">Please note: You have configured your account so you receive these messages. You can deactivate notification emails in your profile screen.</p>";
            } else {
              $subject = "Gene Overload: Has ganado una medalla de bronce en un torneo!";
              $body = $body."<p style=\"font-size: 14px; \">Has ganado una medalla de bronce en el Gene Arena!</p>";
              $body = $body."Tu especimen jug&oacute; un total de ".$totalpartidos." combates. Gan&oacute; ".$sorted_final[2]['victoria'].", perdi&oacute; ".$sorted_final[2]['derrota'].", y empat&oacute; ".$sorted_final[2]['empate'].".";
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
            //              TERCERA POSICION - MEDALLA DE BRONCE


         if ($sorted_final[3]['idespecimen'] != null)
         {
           $especimen->SacarDatosPorId($link_r, $sorted_final[3]['idespecimen']);
           $dinero = CUARTO_PUESTO;
           $jugador_campana->SumarDinero($link_w, $especimen->idpropietario, $idcampana, $dinero);
         }
         if ($sorted_final[4]['idespecimen'] != null)
         {
           $especimen->SacarDatosPorId($link_r, $sorted_final[4]['idespecimen']);
           $dinero = QUINTO_PUESTO;
           $jugador_campana->SumarDinero($link_w, $especimen->idpropietario, $idcampana, $dinero);
         }
         if ($sorted_final[5]['idespecimen'] != null)
         {
           $especimen->SacarDatosPorId($link_r, $sorted_final[5]['idespecimen']);
           $dinero = SEXTO_PUESTO;
           $jugador_campana->SumarDinero($link_w, $especimen->idpropietario, $idcampana, $dinero);
         }
         if ($sorted_final[6]['idespecimen'] != null)
         {
           $especimen->SacarDatosPorId($link_r, $sorted_final[6]['idespecimen']);
           $dinero = SEPTIMO_PUESTO;
           $jugador_campana->SumarDinero($link_w, $especimen->idpropietario, $idcampana, $dinero);
         }

         // Ahora generamos el informe
         for ($i = 0; $i < count($sorted_final); $i++)
         {
           $especimen->SacarDatosPorId($link_r, $sorted_final[$i]['idespecimen']);
           $jugador_campana->SumarUnTorneo($link_w, $especimen->idpropietario, $idcampana);
           $lm = ($i + 1);
           $dinero = 0; // Si no hay algun puesto, es 0, pero hay que enviarlo igual
           switch($lm)
           {
             case 1:
                $jugador_campana->SumarPosicion1($link_w, $especimen->idpropietario, $idcampana);
                $especimen->SumarOro($link_w, $sorted_final[$i]['idespecimen']);
		$dinero = PRIMER_PUESTO; break;
             case 2:
                $jugador_campana->SumarPosicion2($link_w, $especimen->idpropietario, $idcampana);
                $especimen->SumarPlata($link_w, $sorted_final[$i]['idespecimen']);
                $dinero = SEGUNDO_PUESTO; break;
             case 3:
                $jugador_campana->SumarPosicion3($link_w, $especimen->idpropietario, $idcampana);
                $especimen->SumarBronce($link_w, $sorted_final[$i]['idespecimen']);
                $dinero = TERCER_PUESTO; break;
             case 4: $dinero = CUARTO_PUESTO; break;
             case 5: $dinero = QUINTO_PUESTO; break;
             case 6: $dinero = SEXTO_PUESTO; break;
             case 7: $dinero = SEPTIMO_PUESTO; break;
           }
           $especimen->SacarDatosPorId($link_r, $sorted_final[$i]['idespecimen']);
           $informe->GenerarInformeTorneo($link_w, $idcampana, $especimen->idpropietario, ($i+1), $dinero, $especimen->iddeme, $especimen->idslot,
                                        $sorted_final[$i]['victoria'], $sorted_final[$i]['empate'], $sorted_final[$i]['derrota'], 
					$sorted_final[$i]['puntuacion'], $sorted_final[$i]['puntuacion_antigua'], $umbral);

//$informe->GenerarInformeDerrotaTorneo($link_w, $especimen->idpropietario, $idcampana, $especimen->iddeme, $especimen->idslot, $sorted_final[$i]['puntuacion'], $iddeme);
         }


        // Ahora dependiendo de las victorias, empates y derrotas, envejecemos.
        $porcentaje_derrotas = ($sorted_final[$i]['derrota'] / ($sorted_final[$i]['victoria'] + $sorted_final[$i]['empate'] + $sorted_final[$i]['derrota']));
        $porcentaje_empates = ($sorted_final[$i]['empate'] / ($sorted_final[$i]['victoria'] + $sorted_final[$i]['empate'] + $sorted_final[$i]['derrota']));
        $nuevaedad = $especimen->edad + (20 * ($porcentaje_derrotas)) + (5 * ($porcentaje_empates));

 echo ("### Cambio de edad : ".$especimen->edad." -> ".$nuevaedad."### (%derrotas ".$porcentaje_derrotas.", %empates ".$porcentaje_empates.")<br/>");
        $especimen->Envejece($link_w, $sorted_final[$i]['idespecimen'], $nuevaedad);

        // Generamos un log para este usuario
        $log = new Log();
        $log->idjugador = $especimen->idpropietario;
        $log->idcampana = $idcampana;
        $log->tipo_suceso = 11; // 11, ha participado en un torneo
        $log->valor = ($i+1); // lugar en el que ha acabado
        $log->EscribirLog($link_w);


       }





     }


echo ("<br/>!!!");
echo "<br/>".$textoganadores_en;
echo "<br/>".$textoganadores_es;

    $string = "UNLOCK TABLES
                ";
    $query = mysql_query($string, $link_w);


    // Timestamp en microsegundos de fin
    $unix_end = microtime_float();

    // Guardamos solo los torneos sin deme, los gordotes
    if ($iddeme == 0)
    {
      if ($guardar == 1)
      {
        $torneo = new Torneo();
        $torneo->InsertarTorneoStandard($link_w, $idcampana);
        $idtorneonuevo = mysql_insert_id($link_w);
        $especimen_torneo->TrasladarEspecimenesTorneo($link_w, 0, $idtorneonuevo);

        $torneo->GrabarTiempoTorneo($link_w, $unix_begin, $unix_end, $idtorneonuevo);
        $torneo->GrabarArbolesTorneo($link_w, $arbol_oro, $arbol_plata, $arbol_bronce, $idtorneonuevo);
        $torneo->GrabarNivelesTorneo($link_w, $niveles_oro, $niveles_plata, $niveles_bronce, $idtorneonuevo);
        $torneo->GrabarPropietariosTorneo($link_w, $idjugador_oro, $idjugador_plata, $idjugador_bronce, $idtorneonuevo);
        $torneo->GrabarDemesTorneo($link_w, $iddeme_oro, $iddeme_plata, $iddeme_bronce, $idtorneonuevo);
      }
    }










  }


  // *******************************************
  //         Seleccion de torneo
  // *******************************************

  if ($accion == null)
  {

    $jugador = new Jugador();

    $idcampana = $_REQUEST['idcampana'];
    if (($idcampana == null) || ($idcampana == ''))
    {
      echo ("Funcionaria mejor con un &idcampana=1,2,etc");
    }

    // Ahora procedemos a ejecutar lo que haya que ejecutar con el torneo

    // Vamos a sacar todos los ejemplares que hay para este torneo.

    $especimen_torneo = new Especimen_torneo();
    $cuantos = $especimen_torneo->ContarEspecimenesTorneo($link_r, 0, $idcampana);

    echo ("<p>");
    echo ("Hay ".$cuantos." espec&iacute;menes dispuestos a competir");
    echo ("</p>");

    $array = $especimen_torneo->BuscarEspecimenesTorneo($link_r, 0, $idcampana);
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

      <form method="post" action="torneo_eliminatorio.php">
        <input type="hidden" name="accion" value="torneo_todos_con_todos">

        <p>
         &iquest;Deme? <select name="iddeme">
          <option value="0">Todos</option>
          <option value="1">Profundidades</option>
          <option value="2">Bosque</option>
          <option value="3">Volc&aacute;n</option>
	 </select>
        </p>

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
         <input type="submit" value="Torneo eliminatorio">
        </p>
      </form>
    <?php

  } // El if de que hayan especimenes

 } // Cerramos el if de la accion







  }

?>
