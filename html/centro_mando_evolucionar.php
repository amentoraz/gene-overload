<?php

  // ****************************************************
  //            Evolucionar un individuo
  // ****************************************************

  if ($accion == 'evolucionar_individuo')
  {

    $idespecimen = $_REQUEST['idespecimen'];
    if (!is_numeric($idespecimen))
    {
      die;
    }
    $accion = null;

    $especimen = new Especimen();
    $especimen_torneo = new Especimen_Torneo();
    $jugador_campana = new Jugador_Campana();
    $jugador_campana->SacarDatos($link_r, $idjugador, $idcampana);

    $debug_mode = $jugador_campana->debug_mode;

    $evolucion = new Evolucion($debug_mode, $es_premium, $lang);

    // Empezamos por comprobar si esta evaluado, cosa necesaria
    $especimen->SacarDatosPorId($link_r, $idespecimen); //$d, $l, $idjugador, $idcampana);
    $d = $especimen->iddeme;
    $l = $especimen->idslot;

    $evaluados = $especimen->ComprobarEvaluadosDeme($link_r, $idjugador, $idcampana, $d);
    // Tiene que estar evaluado todo el deme, porque es con quien se va a juntar
    if ($evaluados == 0)
    {
      if ($lang == 'en')
      {
        echo ("<span class=\"errorsutil2\">Sorry, you can't evolve an individual if its whole deme has not been evaluated.</span>");
      } else {
        echo ("<span class=\"errorsutil2\">Lo siento, no puede evolucionarse un individuo cuyo deme no haya sido evaluado.</span>");
      }
      echo ("<br/>");
      echo ("<br/>");

    } else {

      // Bueno, lo realmente primero es quitarle una monedita al pringui
      $result = $jugador_campana->RestarDinero($link_w, $idjugador, $idcampana, 1);
      if ($result == 0)
      {
        $dinerito = $dinerito - 1;  // Lo restamos para que aparezca donde debe
        // Vale, ya se han cumplido las condiciones: esta evaluado y tiene dinero suficiente
        $jugador_campana->num_generaciones_individual = $jugador_campana->num_generaciones_individual+1;
        $jugador_campana->GrabarNGenInd($link_w, $idjugador, $idcampana, $iddeme);



        // Comprobamos si este es el elegido para torneos standard
        $hay_en_torneo = $especimen_torneo->ContarJugadorTorneo($link_r, $idjugador, 0);
        $asignar_nuevo = 0;
        if ($hay_en_torneo > 0)
        {
          $especimen_torneo->ObtenEspecimenTorneo($link_r, 0, $idjugador);
          // Vamos a ver si tenemos un especimen apuntado a un torneo
          $idespecimen_torneo = $especimen_torneo->idespecimen;
          // Si es este que vamos a evolucionar, luego reasignaremos el del torneo al desaparecer este
          if ($idespecimen_torneo == $idespecimen)
          {
            $asignar_nuevo = 1;
          }
        }

        // Ahora lo hacemos para el deme que sea este
        $hay_en_torneo = $especimen_torneo->ContarJugadorTorneo($link_r, $idjugador, $d);
        $asignar_nuevo_deme = 0;
        if ($hay_en_torneo > 0)
        {
//echo ("PUTASI");
          $especimen_torneo->ObtenEspecimenTorneo($link_r, $d, $idjugador);
          // Vamos a ver si tenemos un especimen apuntado a un torneo
          $idespecimen_torneo_deme = $especimen_torneo->idespecimen;
          // Si es este que vamos a evolucionar, luego reasignaremos el del torneo al desaparecer este
          if ($idespecimen_torneo_deme == $idespecimen)
          {
            $asignar_nuevo_deme = 1;
          }
        }



        //  Marcamos el especimen como old
//        $especimen->HacerGeneracionOldIndividuo($link_w, $idjugador, $idcampana, $d, $l);
        // Parece un poco excesivo, pero hacer old a todo temporalmente (para que pueda ser seleccionado) es la unica manera
        $especimen->HacerGeneracionOldDeme($link_w, $idjugador, $idcampana, $d);


        // Ahora sacamos los datos del especimen, y con eso sacar $d (deme) y $l (slot dentro del deme)
        if ($debug_mode == 1)
        {
          echo ("<br/>Sexos: ".$jugador_campana->num_sexos);
        }


        // Solo para el slot $l, en el deme $d (justo el seleccionado)
        $evolucion->EvolucionarEspecimen($link_r, $link_w, $idjugador, $idcampana, $d, $l, $jugador_campana->num_sexos, 0, 0);
        if ($es_premium == 1)
        {
          $informe = new Informe();
          if ($lang == 'en')
          {
            $informe->subject = "Individual evolution report";
          } else {
            $informe->subject = "Informe de evoluci&oacute;n individual";
          }
          $informe->texto = $evolucion->informe_premium;
          $informe->tipo = 3;
          $informe->EnviarInformeRaw($link_w, $idjugador, $idcampana);
        }

        //  Si coincide el deme que hemos evolucionado con el
        // seleccionado para torneo, re-seleccionamos ejemplar
        if ($asignar_nuevo == 1)
        {
          $especimen->SacarDatos($link_r, 1, 1, $idjugador, $idcampana); // los dos 1s son iddeme e idslot respectivamente
          $especimen_torneo->ApuntarTorneo($link_w, $especimen->id, 0);
        }

        // Lo mismo para el deme
        if ($asignar_nuevo_deme == 1)
        {
          $especimen->SacarDatos($link_r, $d, 1, $idjugador, $idcampana); // El idslot sera el 1, pero el iddeme es $d
          $especimen_torneo->ApuntarTorneo($link_w, $especimen->id, $d);
        }


        // Borramos el que ocupaba este slot anteriormente
        $especimen->BorrarGeneracionOldIndividuo($link_w, $idjugador, $idcampana, $d, $l);
        $especimen->DesHacerGeneracionOldDeme($link_w, $idjugador, $idcampana, $d);

        // Generamos un log para este usuario
        $log = new Log();
        $log->idjugador = $idjugador;
        $log->idcampana = $idcampana;
        $log->tipo_suceso = 1; // 1, evolucionar
        $log->valor = 1; // individuo
        $log->EscribirLog($link_w);

        // Y hacemos que evalue
//        $accion = 'evaluar';

        if ($lang == 'en')
        {
          echo ("<p class=\"correctosutil\">You have killed the individual in slot ".$l." in the ");
          switch($d)
          {
            case 1: echo("Abyssal depths deme"); break;
            case 2: echo("Forest deme"); break;
            case 3: echo("Volcano deme"); break;
          }
          echo (", and it has been replaced by a new one</p>");
        } else {
          echo ("<p class=\"correctosutil\">Has evolucionado el individuo en el hueco ".$l." del deme ");
          switch($d)
          {
            case 1: echo ("de las profundidades"); break;
            case 2: echo ("del bosque"); break;
            case 3: echo ("del volc&aacute;n"); break;
          }
          echo (", y ha sido sustitu&iacute;do por uno nuevo.</p>");
        }

      } else {
        if ($lang == 'en')
        {
          echo ("<p class=\"errorsutil2\">You lack the money for this. Wait until the next tournament or subsidy.</p>");
        } else {
          echo ("<p class=\"errorsutil2\">No tienes dinero para ejecutar esta accion. Espera al pr&oacute;ximo subsidio o torneo.</p>");
        }
      }

    }

  }

  // ****************************************************
  //            Evolucionar un deme
  // ****************************************************

  if ($accion == 'evolucionar_deme')
  {

    echo ("<p class=\"correctosutil\">");
    if ($lang == 'en')
    {
      echo ("Evolving ");
    } else {
      echo ("Evolucionando ");
    }
    $iddeme = $_REQUEST['iddeme'];
    if (!is_numeric($iddeme))
    {
      die;
    }
    switch($iddeme)
    {
      case 1:
        if ($lang == 'en')
        {
          echo ("abyssal depths deme.");
        } else {
          echo ("deme de las profundidades.");
        }
	break;
      case 2:
        if ($lang == 'en')
        {
          echo ("forest deme.");
        } else {
          echo ("deme del bosque.");
        }
	break;
      case 3:
        if ($lang == 'en')
        {
          echo ("vulcano deme.");
        } else {
          echo ("deme del volc&aacute;n.");
        }
	break;
    }
    echo ("</p><br/>");
    
    $accion = null;

    $especimen = new Especimen();
    $especimen_torneo = new Especimen_Torneo();
    $jugador_campana = new Jugador_Campana();
    $jugador_campana->SacarDatos($link_r, $idjugador, $idcampana);

    $debug_mode = $jugador_campana->debug_mode;

    $evolucion = new Evolucion($debug_mode, $es_premium, $lang);


    $evaluados = $especimen->ComprobarEvaluadosDeme($link_r, $idjugador, $idcampana, $iddeme);

    if ($evaluados == 1)
    {
      // Bueno, lo realmente primero es quitarle una monedita al pringui
      $result = $jugador_campana->RestarDinero($link_w, $idjugador, $idcampana, 1);
      if ($result == 0)
      {
        $dinerito = $dinerito - 1;  // Lo restamos para que aparezca donde debe

        $informe_premium_total = '';

        $jugador_campana->num_generaciones_demes = $jugador_campana->num_generaciones_demes+1;
        $jugador_campana->GrabarNGenDemes($link_w, $idjugador, $idcampana, $iddeme);

        $deme_especimen_torneo = 0;
        $hay_en_torneo = $especimen_torneo->ContarJugadorTorneo($link_r, $idjugador, 0);
//echo ("<br/>Decidiendo, hay_en torneo: ".$hay_en_torneo);
        if ($hay_en_torneo > 0)
        {
//echo ("<br/>Hay apuntado al torneo");
          $especimen_torneo->ObtenEspecimenTorneo($link_r, 0, $idjugador);
          // Vamos a ver si tenemos un especimen apuntado a un torneo
          $idespecimen_torneo = $especimen_torneo->idespecimen;
//echo (", idespecimen ".$idespecimen_torneo);
          $especimen->SacarDatosPorId($link_r, $idespecimen_torneo);
          //  Si lo tenemos, guardamos su deme. Caso de que sea el mismo
          // que el que hemos evolucionado, luego resetearemos el apuntado a torneo
          $deme_especimen_torneo = $especimen->iddeme;
//echo (" y su deme es ".$deme_especimen_torneo);
        }
//echo ("<br/> deme_especimen_torneo: ".$deme_especimen_torneo.", hay_en torneo: ".$hay_en_torneo);

        //  Lo primero es marcar la generacion actual para que solo sea ella el
        // origen, y para que luego podamos borrarla del tiron
        $especimen->HacerGeneracionOldDeme($link_w, $idjugador, $idcampana, $iddeme);

        // Tenemos que recorrer los tres demes, del primer al ultimo slot, para rellenarlos
        $d = $iddeme;

        if ($d == 1) { $numslots = $jugador_campana->num_slots_deme_profundidades; }
        if ($d == 2) { $numslots = $jugador_campana->num_slots_deme_bosque; }
        if ($d == 3) { $numslots = $jugador_campana->num_slots_deme_volcan; }

        if ($debug_mode == 1)
        {
          echo ("<br/>Sexos: ".$jugador_campana->num_sexos);
        }

        // Durante todos los slots de este deme
        for ($l = 1; $l <= $numslots; $l++)
        {
          $evolucion->EvolucionarEspecimen($link_r, $link_w, $idjugador, $idcampana, $d, $l, $jugador_campana->num_sexos, 0, 0);

          $informe_premium_total = $informe_premium_total.$evolucion->informe_premium."<br/><br/>";

        }

        //  Si coincide el deme que hemos evolucionado con el
        // seleccionado para torneo, re-seleccionamos ejemplar
        if ($d == $deme_especimen_torneo)
        {
//echo ("<br/> Asignando nuevo a torneo");
          $especimen->SacarDatos($link_r, $d, 1, $idjugador, $idcampana); // los dos 1s son iddeme e idslot respectivamente
          $especimen_torneo->ApuntarTorneo($link_w, $especimen->id, 0);
        }

        // El elegido para este deme cambia obligatoriamente, debemos eliminar el anterior y meterlo
        $especimen_torneo->DesapuntarTorneo($link_w, $idjugador, $idcampana, $d);
        $especimen->SacarDatos($link_r, $d, 1, $idjugador, $idcampana); // el primero es iddeme, el segundo idslot
        $especimen_torneo->ApuntarTorneo($link_w, $especimen->id, $d);




        // Finalmente, eliminamos la generacion Old.
        $especimen->BorrarGeneracionOldDeme($link_w, $idjugador, $idcampana, $iddeme);
        // NADA DE BORRAR BUFFS SI ES DE DEME
        //$jugador_campana->BorrarBuffs($link_w, $idjugador, $idcampana);


        // Y generamos el informe si procede (premium)
        // Solo para el slot $l, en el deme $d (justo el seleccionado)
//        $evolucion->EvolucionarEspecimen($link_r, $link_w, $idjugador, $idcampana, $d, $l, $jugador_campana->num_sexos, 0);
        if ($es_premium == 1)
        {
          $informe = new Informe();
          if ($lang == 'en')
          {
            $informe->subject = "Deme evolution report";
          } else {
            $informe->subject = "Informe de evoluci&oacute;n de deme";
          }
          $informe->texto = $informe_premium_total;
          $informe->tipo = 3;
          $informe->EnviarInformeRaw($link_w, $idjugador, $idcampana);
        }

        // Generamos un log para este usuario
        $log = new Log();
        $log->idjugador = $idjugador;
        $log->idcampana = $idcampana;
        $log->tipo_suceso = 1; // 1, evolucionar
        $log->valor = 2; // deme
        $log->EscribirLog($link_w);

        // Y hacemos que evalue
//        $accion = 'evaluar';


      } else {
        if ($lang == 'en')
        {
          echo ("<p class=\"errorsutil2\">You lack the money for this</p>");
        } else {
          echo ("<p class=\"errorsutil2\">No tienes dinero para ejecutar esta accion</p>");
        }
        echo ("<br/>");
      }


    } else {

      if ($lang == 'en')
      {
        echo ("<p class=\"errorsutil2\">Sorry, you can't evolve a deme which members aren't all scored.</p>");
      } else {
        echo ("<p class=\"errorsutil2\">Lo siento, no puede evolucionarse un deme cuyos miembros no estan todos evaluados.</p>");
      }
      echo ("<br/>");
    }

  }





  // ****************************************************
  //     Evolucionar una generacion tomando como base
  //  los especimenes evaluados en todo tu clan
  // ****************************************************

  if ($accion == 'evolucionar_generacion_clan')
  {

    $link_r = $link_w;
    $string = "LOCK TABLES
		jugador_campana WRITE,
		especimen WRITE,
		jugador a WRITE,
		jugador_campana b WRITE,
		especimen c WRITE,
		especimen_torneo WRITE,
		informe WRITE,
		log WRITE,
		especimen a WRITE,
		jugador_campana b WRITE,
		clan_jugador c WRITE
		";
    $query = mysql_query($string, $link_w);




    $accion = null;

    $especimen = new Especimen();
    $especimen_torneo = new Especimen_Torneo();
    $jugador_campana = new Jugador_Campana();
    $jugador_campana->SacarDatos($link_r, $idjugador, $idcampana);

    $debug_mode = $jugador_campana->debug_mode;

    $evolucion = new Evolucion($debug_mode, $es_premium, $lang);


    $evaluados = $especimen->ComprobarEvaluados($link_r, $idjugador, $idcampana);

    // Igualmente necesitamos que como minimo tengas tus especimenes para esto
    if ($evaluados == 1)
    {
      // Bueno, lo realmente primero es quitarle dos moneditax
      $result = $jugador_campana->RestarDinero($link_w, $idjugador, $idcampana, 2);
      if ($result == 0)
      {

        $dinerito = $dinerito - 2;  // Lo restamos para que aparezca donde debe
        $informe_premium_total = '';

        $jugador_campana->num_generaciones_total = $jugador_campana->num_generaciones_total+1;
        $jugador_campana->GrabarNGenTotal($link_w, $idjugador, $idcampana);


        //  Lo primero es marcar la generacion actual para que solo sea ella el
        // origen, y para que luego podamos borrarla del tiron
        $especimen->HacerGeneracionOld($link_w, $idjugador, $idcampana);

        // Tenemos que recorrer los tres demes, del primer al ultimo slot, para rellenarlos
        for ($d = 1; $d <= 3; $d++)
        {
          if ($d == 1) { $numslots = $jugador_campana->num_slots_deme_profundidades; }
          if ($d == 2) { $numslots = $jugador_campana->num_slots_deme_bosque; }
          if ($d == 3) { $numslots = $jugador_campana->num_slots_deme_volcan; }

          if ($debug_mode == 1)
          {
            echo ("<br/>Sexos: ".$jugador_campana->num_sexos);
          }

          // Durante todos los slots de este deme
          for ($l = 1; $l <= $numslots; $l++)
          {
            $evolucion->EvolucionarEspecimen($link_r, $link_w, $idjugador, $idcampana, $d, $l, $jugador_campana->num_sexos, 1, $miclan->id); // 1 porque es del total, luego idclan

            $informe_premium_total = $informe_premium_total.$evolucion->informe_premium."<br/><br/>";
          }

        }

        // Finalmente, eliminamos la generacion Old.
        $especimen->BorrarGeneracionOld($link_w, $idjugador, $idcampana);

        $jugador_campana->BorrarBuffs($link_w, $idjugador, $idcampana);

        // Acabado, vamos a seleccionar el primero para el torneo
        $especimen->SacarDatos($link_r, 1, 1, $idjugador, $idcampana); // los dos 1s son iddeme e idslot respectivamente
        $especimen_torneo->ApuntarTorneo($link_w, $especimen->id, 0);


        // El elegido para este deme cambia obligatoriamente, debemos eliminar el anterior y meterlo
        for ($d = 1; $d <= 3; $d++)
        {
          $especimen_torneo->DesapuntarTorneo($link_w, $idjugador, $idcampana, $d);
          $especimen->SacarDatos($link_r, $d, 1, $idjugador, $idcampana); // el primero es iddeme, el segundo idslot
          $especimen_torneo->ApuntarTorneo($link_w, $especimen->id, $d);
        }



        if ($es_premium == 1)
        {
          $informe = new Informe();
          if ($lang == 'en')
          {
            $informe->subject = "Generation evolution report";
          } else {
            $informe->subject = "Informe de evoluci&oacute;n de una generaci&oacute;n";
          }
          $informe->texto = $informe_premium_total;
          $informe->tipo = 3;
          $informe->EnviarInformeRaw($link_w, $idjugador, $idcampana);
        }


        // Generamos un log para este usuario
        $log = new Log();
        $log->idjugador = $idjugador;
        $log->idcampana = $idcampana;
        $log->tipo_suceso = 1; // 1, evolucionar
        $log->valor = 4; // clan
        $log->EscribirLog($link_w);

        if ($lang == 'en')
        {
          if ($jugador_campana->mezcla_activa == 1)
          {
            echo ("<p class=\"correctosutil\">You've team evolved mixing all demes. This means there was a total of ".$evolucion->num_origenes." source specimens</p>");
          } else {
            echo ("<p class=\"correctosutil\">You've team evolved with ".$evolucion->num_origenes_1." source specimens in the Abyssal depths deme, ");
            echo ($evolucion->num_origenes_2." in the Forest deme, and ");
            echo ($evolucion->num_origenes_3." in the Volcano deme.");
            echo ("</p>");
          }
        } else {
          if ($jugador_campana->mezcla_activa == 1)
          {
            echo ("<p class=\"correctosutil\">Has evolucionado en equipo con mezcla de demes. Esto significa que hab&iacute;a un total de  ".$evolucion->num_origenes." espec&iacute;menes de origen</p>");
          } else {
            echo ("<p class=\"correctosutil\">Has evolucionado en equipo con ".$evolucion->num_origenes_1." espec&iacute;menes origen en el deme de las Profundidades, ");
            echo ($evolucion->num_origenes_2." en el deme del Bosque, y ");
            echo ($evolucion->num_origenes_3." en el deme del Volc&aacute;n.");
            echo ("</p>");
          }
        }
        echo ("<br/>");

        // Y hacemos que evalue
        $accion = 'evaluar';


      } else {
        if ($lang == 'en')
        {
          echo ("<p class=\"errorsutil2\">You lack the money for this</p>");
        } else {
          echo ("<p class=\"errorsutil2\">No tienes dinero para ejecutar esta accion</p>");
        }
        echo ("<br/>");
      }


    } else {

      if ($lang == 'en')
      {
        echo ("<p class=\"errorsutil2\">Sorry, you can't evolve a generation which members aren't all scored.</p>");
      } else {
        echo ("<p class=\"errorsutil2\">Lo siento, no puede evolucionarse una generaci&oacute;n cuyos miembros no estan todos evaluados.</p>");
      }
      echo ("<br/>");

    }



    $string = "UNLOCK TABLES
                ";
    $query = mysql_query($string, $link_w);




  }





  // ****************************************************
  //            Evolucionar una generacion
  // ****************************************************

  if ($accion == 'evolucionar_generacion')
  {

//    if ($lang == 'en')
//    {
//      echo ("Evolving a generation...");
//    } else {
//      echo ("Evolucionando una generacion...");
//    }
    $accion = null;

    $especimen = new Especimen();
    $especimen_torneo = new Especimen_Torneo();
    $jugador_campana = new Jugador_Campana();
    $jugador_campana->SacarDatos($link_r, $idjugador, $idcampana);

    $debug_mode = $jugador_campana->debug_mode;

    $evolucion = new Evolucion($debug_mode, $es_premium, $lang);


    $evaluados = $especimen->ComprobarEvaluados($link_r, $idjugador, $idcampana);

    if ($evaluados == 1)
    {


      $mode = $_REQUEST['mode'];
      if (!is_numeric($debug))
      {
        $mode = 0;
      }

      // Bueno, lo realmente primero es quitarle una monedita al pringui
      switch($mode)
      {
        case 0:
                $repetir = 1;
                $result = $jugador_campana->RestarDinero($link_w, $idjugador, $idcampana, 1);
                break;
        case 1:
//                $repetir = 5;
                $repetir = 1;
                $result = $jugador_campana->RestarDinero($link_w, $idjugador, $idcampana, 1);
//                $result = $jugador_campana->RestarDinero($link_w, $idjugador, $idcampana, 5);
                break;
        case 2:
                if ($es_admin == 1)
                {
                  $repetir = 25;
                } else {
                  $repetir = 1;
                }
//echo $repetir;
                $result = $jugador_campana->RestarDinero($link_w, $idjugador, $idcampana, 1);
                break;
        default:
                $repetir = 1;
                $result = $jugador_campana->RestarDinero($link_w, $idjugador, $idcampana, 1);
                break;
      }

      // Bueno, lo realmente primero es quitarle una monedita al pringui
//      $result = $jugador_campana->RestarDinero($link_w, $idjugador, $idcampana, $repetir);
//      $result = $jugador_campana->RestarDinero($link_w, $idjugador, $idcampana, 1);
      if ($result == 0)
      {

        for ($k = 1; $k <= $repetir; $k++)
        {

        $dinerito = $dinerito - 1;  // Lo restamos para que aparezca actualizado
        $informe_premium_total = '';

        $jugador_campana->num_generaciones_total = $jugador_campana->num_generaciones_total+1;
        $jugador_campana->GrabarNGenTotal($link_w, $idjugador, $idcampana);


        //  Lo primero es marcar la generacion actual para que solo sea ella el
        // origen, y para que luego podamos borrarla del tiron
        $especimen->HacerGeneracionOld($link_w, $idjugador, $idcampana);

        // Tenemos que recorrer los tres demes, del primer al ultimo slot, para rellenarlos
        for ($d = 1; $d <= 3; $d++)
        {
          if ($d == 1) { $numslots = $jugador_campana->num_slots_deme_profundidades; }
          if ($d == 2) { $numslots = $jugador_campana->num_slots_deme_bosque; }
          if ($d == 3) { $numslots = $jugador_campana->num_slots_deme_volcan; }

          if ($debug_mode == 1)
          {
            echo ("<br/>Sexos: ".$jugador_campana->num_sexos);
          }

          // Durante todos los slots de este deme
          for ($l = 1; $l <= $numslots; $l++)
          {
            $evolucion->EvolucionarEspecimen($link_r, $link_w, $idjugador, $idcampana, $d, $l, $jugador_campana->num_sexos, 1, 0); // 1 porque es del total, 0 pq no es clan

            $informe_premium_total = $informe_premium_total.$evolucion->informe_premium."<br/><br/>";
          }

        }

        // Finalmente, eliminamos la generacion Old.
        $especimen->BorrarGeneracionOld($link_w, $idjugador, $idcampana);

        $jugador_campana->BorrarBuffs($link_w, $idjugador, $idcampana);

        // Acabado, vamos a seleccionar el primero para el torneo
        $especimen->SacarDatos($link_r, 1, 1, $idjugador, $idcampana); // los dos 1s son iddeme e idslot respectivamente
        $especimen_torneo->ApuntarTorneo($link_w, $especimen->id, 0);

        // El elegido para este deme cambia obligatoriamente, debemos eliminar el anterior y meterlo
        for ($d = 1; $d <= 3; $d++)
        {
          $especimen_torneo->DesapuntarTorneo($link_w, $idjugador, $idcampana, $d);
          $especimen->SacarDatos($link_r, $d, 1, $idjugador, $idcampana); // el primero es iddeme, el segundo idslot
          $especimen_torneo->ApuntarTorneo($link_w, $especimen->id, $d);
        }



        if ($es_premium == 1)
        {
          $informe = new Informe();
          if ($lang == 'en')
          {
            $informe->subject = "Generation evolution report";
          } else {
            $informe->subject = "Informe de evoluci&oacute;n de una generaci&oacute;n";
          }
          $informe->texto = $informe_premium_total;
          $informe->tipo = 3;
          $informe->EnviarInformeRaw($link_w, $idjugador, $idcampana);
        }

        // Generamos un log para este usuario
        $log = new Log();
        $log->idjugador = $idjugador;
        $log->idcampana = $idcampana;
        $log->tipo_suceso = 1; // 1, evolucionar
        $log->valor = 3; // generacion entera
        $log->EscribirLog($link_w);

        // Y hacemos que evalue
        $accion = 'evaluar';

        } // Esto del "repetir"

        if ($lang == 'en')
        {
          if ($repetir == 5)
          {
            echo ("<p class=\"correctosutil\">You have evolved 5 complete generations. </p>");
          } else {
            echo ("<p class=\"correctosutil\">You have evolved a complete generation. </p>");
          }
        } else {
          if ($repetir == 5)
          {
            echo ("<p class=\"correctosutil\">Has evolucionado 5 generaciones completas. </p>");
          } else {
            echo ("<p class=\"correctosutil\">Has evolucionado una generaci&oacute;n completa. </p>");
          }
        }
//echo $repetir;

      } else {
        if ($lang == 'en')
        {
          echo ("<p class=\"errorsutil2\">You lack the money for this</p>");
        } else {
          echo ("<p class=\"errorsutil2\">No tienes dinero para ejecutar esta accion</p>");
        }
        echo ("<br/>");
      }


    } else {

      if ($lang == 'en')
      {
        echo ("<p class=\"errorsutil2\">Sorry, you can't evolve a generation which members aren't all scored.</p>");
      } else {
        echo ("<p class=\"errorsutil2\">Lo siento, no puede evolucionarse una generaci&oacute;n cuyos miembros no estan todos evaluados.</p>");
      }
      echo ("<br/>");

    }

  }




  // Sea lo que sea, hay que repasar y evaluar todos.

?>
