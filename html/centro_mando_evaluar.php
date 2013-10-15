<?php



  // ****************************************
  //     Evalua contra los campeones
  // ****************************************

  if ($accion == 'evaluar_campeones')
  {
    // Es una evaluacion contra una copia holografica de los ganadores del anterior torneo.

    $torneo = new Torneo();
    $resultt = $torneo->SacarDatosUltima($link_r, $idcampana);
    if ($resultt != -1)
    {
      //  Ahora tenemos en el objeto torneo los datos de todos. O *casi* todos los datos, porque
      // las caracteristicas internas van a ser una estimacion, poniendose todos a 7.

      $jugador_campana = new Jugador_campana();
      $jugador_campana->SacarDatos($link_r, $idjugador, $idcampana);
      $debug_mode = $jugador_campana->debug_mode;

      $combate = new Combate($debug_mode);
      $especimen = new Especimen();
//      $especimen_campeon = new Especimen();
      $arbol = new Arbol();

      $total_especimenes = $jugador_campana->num_slots_deme_profundidades +
			$jugador_campana->num_slots_deme_bosque +
			$jugador_campana->num_slots_deme_volcan;

      $result = $jugador_campana->RestarDinero($link_w, $idjugador, $idcampana, 2);
      if ($result == 0)
      {
        $dinerito = $dinerito - 2;  // Lo restamos para que aparezca actualizado

        for ($k = 1; $k <= $total_especimenes; $k++)
        {
        	 // Cada vez el especimen a evaluar es el de la posicion $k
          $array_especimen_evaluar[$k] = $especimen->Obtener_Por_Numero($link_r, $idcampana, $idjugador, $k);
          // Ponemos a 0 sus puntos
          $puntos_total = 0;
          // Sacamos el arbol de este especimen a evaluar
          $arbol1 = $arbol->Desglosar($array_especimen_evaluar[$k]['arbol'], $array_especimen_evaluar[$k]['niveles_arbol']);

          // Al especimen campeon contra el que se enfrenta, lo rellenamos nosotros
          $especimen_campeon['id'] = 0;
          $especimen_campeon['iddeme'] = $torneo->iddeme_oro;
          $especimen_campeon['rapidez'] = 10;
          $especimen_campeon['inteligencia'] = 10;
          $especimen_campeon['fuerza'] = 10;
          $especimen_campeon['constitucion'] = 10;
          $especimen_campeon['percepcion'] = 10;
          $especimen_campeon['sabiduria'] = 10;
          $especimen_campeon['arbol'] = $torneo->arbol_oro;
          $especimen_campeon['niveles_arbol'] = $torneo->niveles_oro;

          $arbol2 = $arbol->Desglosar($especimen_campeon['arbol'], $especimen_campeon['niveles_arbol']);
          $arrayresultado = $combate->Puntuar($array_especimen_evaluar[$k], $especimen_campeon, $arbol1, $arbol2);
          $puntos = $arrayresultado['puntos1'];
          // Informacion de debug
          if ($debug_mode == 1)
          {
            echo ("<br/><b>Puntos en esta ronda : ".$puntos."</b>");
            echo ("<br/><br/><hr/>");
          }
          $puntos_total = $puntos + $puntos_total;

          // Les hacemos luchar en ambas posiciones
          $arrayresultado = $combate->Puntuar($especimen_campeon, $array_especimen_evaluar[$k], $arbol2, $arbol1);
          $puntos = $arrayresultado['puntos2'];
          // Informacion de debug
          if ($debug_mode == 1)
          {
            echo ("<br/><b>Puntos en esta ronda(2) : ".$puntos."</b>");
            echo ("<br/><br/><hr/>");
          }
          $puntos_total = $puntos + $puntos_total;


          // Al especimen subcampeon lo rellenamos nosotros
          $especimen_campeon['id'] = 0;
          $especimen_campeon['iddeme'] = $torneo->iddeme_plata;
          $especimen_campeon['rapidez'] = 9;
          $especimen_campeon['inteligencia'] = 9;
          $especimen_campeon['fuerza'] = 9;
          $especimen_campeon['constitucion'] = 9;
          $especimen_campeon['percepcion'] = 9;
          $especimen_campeon['sabiduria'] = 9;
          $especimen_campeon['arbol'] = $torneo->arbol_plata;
          $especimen_campeon['niveles_arbol'] = $torneo->niveles_plata;

          $arbol2 = $arbol->Desglosar($especimen_campeon['arbol'], $especimen_campeon['niveles_arbol']);
          $arrayresultado = $combate->Puntuar($array_especimen_evaluar[$k], $especimen_campeon, $arbol1, $arbol2);
          $puntos = $arrayresultado['puntos1'];
          // Informacion de debug
          if ($debug_mode == 1)
          {
            echo ("<br/><b>Puntos en esta ronda : ".$puntos."</b>");
            echo ("<br/><br/><hr/>");
          }
          $puntos_total = $puntos + $puntos_total;

          // Les hacemos luchar en ambas posiciones
          $arrayresultado = $combate->Puntuar($especimen_campeon, $array_especimen_evaluar[$k], $arbol2, $arbol1);
          $puntos = $arrayresultado['puntos2'];
          // Informacion de debug
          if ($debug_mode == 1)
          {
            echo ("<br/><b>Puntos en esta ronda(2) : ".$puntos."</b>");
            echo ("<br/><br/><hr/>");
          }
          $puntos_total = $puntos + $puntos_total;


          // Al especimen bronce lo rellenamos nosotros
          $especimen_campeon['id'] = 0;
          $especimen_campeon['iddeme'] = $torneo->iddeme_bronce;
          $especimen_campeon['rapidez'] = 8;
          $especimen_campeon['inteligencia'] = 8;
          $especimen_campeon['fuerza'] = 8;
          $especimen_campeon['constitucion'] = 8;
          $especimen_campeon['percepcion'] = 8;
          $especimen_campeon['sabiduria'] = 8;
          $especimen_campeon['arbol'] = $torneo->arbol_bronce;
          $especimen_campeon['niveles_arbol'] = $torneo->niveles_bronce;

          $arbol2 = $arbol->Desglosar($especimen_campeon['arbol'], $especimen_campeon['niveles_arbol']);
          $arrayresultado = $combate->Puntuar($array_especimen_evaluar[$k], $especimen_campeon, $arbol1, $arbol2);
          $puntos = $arrayresultado['puntos1'];
          // Informacion de debug
          if ($debug_mode == 1)
          {
            echo ("<br/><b>Puntos en esta ronda : ".$puntos."</b>");
            echo ("<br/><br/><hr/>");
          }
          $puntos_total = $puntos + $puntos_total;

          // Les hacemos luchar en ambas posiciones
          $arrayresultado = $combate->Puntuar($especimen_campeon, $array_especimen_evaluar[$k], $arbol2, $arbol1);
          $puntos = $arrayresultado['puntos2'];
          // Informacion de debug
          if ($debug_mode == 1)
          {
            echo ("<br/><b>Puntos en esta ronda(2) : ".$puntos."</b>");
            echo ("<br/><br/><hr/>");
          }
          $puntos_total = $puntos + $puntos_total;



	  $puntos_media = $puntos_total / 6;  // Porque se ha peleado contra 3, 2 veces, y para reducirlo un poco

          if ($debug_mode == 1)
          {
            echo ("<br/>");
            echo ("<b>MEDIA OBTENIDA:</b> ".$puntos_media."#");
            echo ("<br/><br/><hr/>");
          }
          // Ahora que tenemos la media, podemos guardarla
          $especimen->GuardarPuntuacion($link_w, $array_especimen_evaluar[$k]['id'], $puntos_media);

        }

        echo ("<p class=\"correctosutil\">");
        if ($lang == 'en')
        {
          echo ("Specimens tested against holographic copy of latest tournament champions.");
        } else {
          echo ("Espec&iacute;menes evaluados contra copia hologr&aacute;fica de los actuales campeones del torneo.");
        }
        echo ("</p>");

      } else {
        if ($lang == 'en')
        {
          echo ("<p class=\"errorsutil2\">You lack the money for this</p>");
        } else {
          echo ("<p class=\"errorsutil2\">No tienes dinero para ejecutar esta accion</p>");
        }
        echo ("<br/>");
      }

      $accion = null;


      } else {
        if ($lang == 'en')
        {
          echo ("<p class=\"errorsutil2\">Wait till there's already one tournament!</p>");
        } else {
          echo ("<p class=\"errorsutil2\">Espera a que haya al menos un torneo!</p>");
        }
        echo ("<br/>");
      }

  }

  // ****************************************
  //     Evaluacion normal
  // ****************************************

  if ($accion == 'evaluar')
  {
    //  Una evaluacion normal no puede ser total de todos contra todos,
    // porque esto implicaria en el mejor caso 20*19*...*2 combates = 2.43290201 * 10^18 , y
    // esto no hay dios que lo maneje.
    //
    //  Asi, lo que hara sera evaluar cada uno (que no haya sido evaluado)
    // contra otros 3 especimenes aleatorios
    //

    $jugador_campana = new Jugador_campana();
    $jugador_campana->SacarDatos($link_r, $idjugador, $idcampana);
    $debug_mode = $jugador_campana->debug_mode;

    $combate = new Combate($debug_mode);
    $especimen = new Especimen();
    $arbol = new Arbol();


    $total_especimenes = $jugador_campana->num_slots_deme_profundidades +
			$jugador_campana->num_slots_deme_bosque +
			$jugador_campana->num_slots_deme_volcan;

    // Obtenemos los que faltan por evaluar
    $array_evaluar = $especimen->BuscarTodosEvaluar($link_r, $idcampana, $idjugador);

    if (count($array_evaluar) > 0)
    {
     for ($j = 1; $j <= count($array_evaluar); $j++)
     {


      // ****************************************************
      // Para cada uno a evaluar, vamos a sacar un aleatorio
      // ****************************************************
      //  Al ser tres combates, tres aleatorios

      $eleccion1 = rand(0, ($total_especimenes - 1));
      $eleccion2 = $eleccion1;
      while ($eleccion1 == $eleccion2)
      {
        $eleccion2 = rand(0, ($total_especimenes - 1));
        $eleccion3 = $eleccion2;
      }
      while (($eleccion2 == $eleccion3) || ($eleccion1 == $eleccion3))
      {
        $eleccion3 = rand(0, ($total_especimenes - 1));
      }
      
// EL PREMIUM NO DEBE TENER VENTAJAS DE JUEGO	        
      while (($eleccion2 == $eleccion4) || ($eleccion1 == $eleccion4) || ($eleccion3 == $eleccion4))
      {
      	 $eleccion4 = rand(0, ($total_especimenes - 1));
      }
//      $cantidad_enfrentar = 4; // A cuanta gente se va a enfrentar en esta prueba
      $cantidad_enfrentar = ceil(log( pow(($total_especimenes / 2), 2))); // A cuanta gente se va a enfrentar en esta prueba
//echo ("#".$cantidad_enfrentar."#");

      $arrayunico[1] = $especimen->Obtener_Por_Numero($link_r, $idcampana, $idjugador, $eleccion1);
      $arrayunico[2] = $especimen->Obtener_Por_Numero($link_r, $idcampana, $idjugador, $eleccion2);
      $arrayunico[3] = $especimen->Obtener_Por_Numero($link_r, $idcampana, $idjugador, $eleccion3);
      // El resto que se sumen de modo totalmente aleatorio
      for ($l = 4; $l <= $cantidad_enfrentar; $l++)
      {
        $arrayunico[$l] = $especimen->Obtener_Por_Numero($link_r, $idcampana, $idjugador, rand(0, ($total_especimenes - 1)));
      }

      //if ($es_premium == 1)
      //{
      //  $arrayunico[4] = $especimen->Obtener_Por_Numero($link_r, $idcampana, $idjugador, $eleccion4);
      //}
      // Y ahora enfrentamos a $array_evaluar[$j] con $arrayunico[$n]
      $puntos_total = 0;
      $arbol1 = $arbol->Desglosar($array_evaluar[$j]['arbol'], $array_evaluar[$j]['niveles_arbol']);
      
      //for ($n = 1; $n <= 3; $n++)
      if ($debug_mode == 1)
      {
      	echo ("Enfrentando contra ".$cantidad_enfrentar." especimenes.");
      }
      for ($n = 1; $n <= $cantidad_enfrentar; $n++)
      {
        $arbol2 = $arbol->Desglosar($arrayunico[$n]['arbol'], $arrayunico[$n]['niveles_arbol']);
        $arrayresultado = $combate->Puntuar($array_evaluar[$j],$arrayunico[$n], $arbol1, $arbol2);
        $puntos = $arrayresultado['puntos1'];
        // Informacion de debug
        if ($debug_mode == 1)
        {
          echo ("<br/><b>Puntos en esta ronda : ".$puntos."</b>");
          echo ("<br/><br/><hr/>");
        }
        $puntos_total = $puntos + $puntos_total;
      }
      //$puntos_media = $puntos_total / 3;
      $puntos_media = $puntos_total / $cantidad_enfrentar;
      if ($debug_mode == 1)
      {
        echo ("<br/>");
        echo ("<b>MEDIA OBTENIDA:</b> ".$puntos_media."#");
        echo ("<br/><br/><hr/>");
      }
      // Ahora que tenemos la media, podemos guardarla
      $especimen->GuardarPuntuacion($link_w, $array_evaluar[$j]['id'], $puntos_media);

     }

/*
     if ($lang == 'en')
     {
       echo ("<p class=\"correctosutil\">Specimens have been tested!!</p>");
     } else {
       echo ("<p class=\"correctosutil\">Espec&iacute;menes evaluados!!</p>");
     }
*/

        // Generamos un log para este usuario
        $log = new Log();
        $log->idjugador = $idjugador;
        $log->idcampana = $idcampana;
        $log->tipo_suceso = 2; // 2, entrenar
        $log->valor = 2; // generacion entera
        $log->EscribirLog($link_w);



    } else {

     if ($lang == 'en')
     {
       echo ("<p class=\"errorsutil2\"><span style=\"font-weight: bold;\">Evaluating: </span>There were not untested specimens to test</p>");
     } else {
       echo ("<p class=\"errorsutil2\"><span style=\"font-weight: bold;\">Evaluando: </span>No hab&iacute;a espec&iacute;menes sin evaluar</p>");
     }
     echo ("<br/>");

    }

    $accion = null;

  }


  // ****************************************
  //     Reevalua un ejemplar haciendolo combatir contra todos
  // ****************************************

  if ($accion == 'reevaluar_individuo')
  {
    $jugador_campana = new Jugador_Campana();
    $jugador_campana->SacarDatos($link_r, $idjugador, $idcampana);

    $debug_mode = $jugador_campana->debug_mode;

    $especimen = new Especimen();
    $arbol = new Arbol();
    $combate = new Combate($debug_mode);
    $idespecimen = $_REQUEST['idespecimen'];
    if (!is_numeric($idespecimen))
    {
      die;
    }


    $result = $jugador_campana->RestarDinero($link_w, $idjugador, $idcampana, 1);
    if ($result == 0)
    {
      $dinerito = $dinerito - 1;  // Lo restamos para que aparezca actualizado

      // Ahora le hacemos combatir contra el resto de todos
//      $jugador_campana->SacarDatos($link_r, $idjugador, $idcampana);
      $total_especimenes = $jugador_campana->num_slots_deme_profundidades +
			$jugador_campana->num_slots_deme_bosque +
			$jugador_campana->num_slots_deme_volcan;
      // Vamos a ir uno a uno, saltandonos solo el combate contra si mismo
      $especimen_evaluado = $especimen->Obtener_Por_Id($link_r, $idespecimen);
      $puntos_total = 0;
      for ($e = 0; $e < $total_especimenes; $e++)
      {
//echo $puntos_total."#";
        $especimen_enfrentado = $especimen->Obtener_Por_Numero($link_r, $idcampana, $idjugador, $e);
        if ($especimen_enfrentado['id'] != $especimen_evaluado['id'])
        {
          $arbol1 = $arbol->Desglosar($especimen_evaluado['arbol'], $especimen_evaluado['niveles_arbol']);
          $arbol2 = $arbol->Desglosar($especimen_enfrentado['arbol'], $especimen_enfrentado['niveles_arbol']);
          $arrayresultado = $combate->Puntuar($especimen_evaluado,$especimen_enfrentado, $arbol1, $arbol2);
          $puntos = $arrayresultado['puntos1'];
          if ($debug_mode == 1)
          {
            echo ("<br/>Puntos : ".$puntos);
            echo ("<br/><br/><hr/>");
          }
          $puntos_total = $puntos + $puntos_total;
        } else {
          if ($debug_mode == 1)
          {
            echo ("<br/>No contra si mismo!");
          }
        }

     }

// Esto no deberia ir al final?
        $puntos_media = $puntos_total / $e;
        if ($debug_mode == 1)
        {
          echo ("MEDIA: ".$puntos_media."#");
        }
//echo "$".$puntos_media."$";
        // Ahora que tenemos la media, podemos guardarla
        $especimen->GuardarPuntuacion($link_w, $idespecimen, $puntos_media);
//      }

      // Generamos un log para este usuario
      $log = new Log();
      $log->idjugador = $idjugador;
      $log->idcampana = $idcampana;
      $log->tipo_suceso = 2; // 2, entrenar
      $log->valor = 1; // 1, a un individuo
      $log->EscribirLog($link_w);

    } else {
      if ($lang == 'en')
      {
        echo ("<p class=\"errorsutil2\">You lack the money for this</p>");
      } else {
        echo ("<p class=\"errorsutil2\">No tienes dinero para ejecutar esta accion</p>");
      }
      echo ("<br/>");
    }
    $accion = null;
  }


?>
