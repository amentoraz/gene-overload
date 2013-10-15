<?php

//  include("clases/obj_jugador_campana.php");
  include("clases/obj_objeto.php");
  include("clases/obj_arbol.php");
  include("clases/obj_especimen.php");
  include("clases/obj_especimen_torneo.php");


  $debug = $_REQUEST['debug'];
  if (!is_numeric($debug))
  {
    $debug = 0;
  }

//  include ("pestanyas_jugar.php");



  function Escribir_Tooltip_Comprar($lang, $dinero)
  {
      echo ("<span>");
      if ($lang == 'en')
      {
        echo ("<table
		width=\"100%\" class=\"tooltip_interno\"><tr><td style=\"text-align: left;\">");
        echo ($dinero);
        echo ("<img src=\"img/goldcoin.gif\" style=\"vertical-align:middle;\"> ");
	echo ("<b>");
        echo ("Click to buy");
	echo ("</b>");
        echo ("</td></tr></table>");
      } else {
        echo ("<table width=\"100%\" class=\"tooltip_interno\"><tr><td style=\"text-align: center;\">");
        echo ($dinero);
        echo ("<img src=\"img/goldcoin.gif\" style=\"vertical-align:middle;\"> ");
	echo ("<b>");
        echo ("Pulsa para comprar");
	echo ("</b>");
        echo ("</td></tr></table>");
      }
      echo ("</span>");
//      echo ("<span class=\"goldcoin\"> 4</span>");
//      echo ("<img src=\"img/goldcoin.gif\" style=\"vertical-align:middle;\">");
  }

  function Escribir_Tooltip_Comprar_Gray($lang, $dinero)
  {
      echo ("<span>");
      if ($lang == 'en')
      {
        echo ("<table
		width=\"100%\" class=\"tooltip_interno\"><tr><td style=\"text-align: left;\">");
	echo ("<b>");
        echo ("<i>You lack enough money to buy</i>");
	echo ("</b> ");
        echo ($dinero);
        echo ("<img src=\"img/goldcoin.gif\" style=\"vertical-align:middle;\"> ");
        echo ("</td></tr></table>");
      } else {
        echo ("<table width=\"100%\" class=\"tooltip_interno\"><tr><td style=\"text-align: center;\">");
        echo ("<img src=\"img/goldcoin.gif\" style=\"vertical-align:middle;\"> ");
	echo ("<b> ");
        echo ($dinero);
        echo ("<i>No tienes dinero para comprar</i>");
	echo ("</b>");
        echo ("</td></tr></table>");
      }
      echo ("</span>");
//      echo ("<span class=\"goldcoin\"> 4</span>");
//      echo ("<img src=\"img/goldcoin.gif\" style=\"vertical-align:middle;\">");
  }



  function Escribir_Tooltip_Obra($lang, $dinero, $txt, $total)
  {
      echo ("<span>");
      if ($lang == 'en')
      {
        echo ("<table
		width=\"100%\" class=\"tooltip_interno\" align=\"left\"><tr><td style=\"text-align: left;\">");
        echo "<i>".$txt." slot</i> ";
        echo ("<br/>");
        echo ("<p style=\"font-size: 11px;\"><i>Currently <b>".$total." slots</b></i></p>");
        echo ("<br/>");
        echo ($dinero);
        echo ("<img src=\"img/goldcoin.gif\" style=\"vertical-align:middle;\"> ");
	echo ("<b>");
        echo ("Click to buy slot");
	echo ("</b>");
        echo ("</td></tr></table>");
      } else {
        echo ("<table width=\"100%\" class=\"tooltip_interno\"><tr><td style=\"text-align: center;\">");
        echo "<i>Hueco en ".$txt."</i> ";
        echo ("<br/>");
        echo ("<p style=\"font-size: 11px;\"><i>Actualmente <b>".$total." huecos</b></i></p>");
        echo ("<br/>");
        echo ($dinero);
        echo ("<img src=\"img/goldcoin.gif\" style=\"vertical-align:middle;\"> ");
	echo ("<b>");
        echo ("Pulsa para comprar hueco");
	echo ("</b>");
        echo ("</td></tr></table>");
      }
      echo ("</span>");
//      echo ("<span class=\"goldcoin\"> 4</span>");
//      echo ("<img src=\"img/goldcoin.gif\" style=\"vertical-align:middle;\">");
  }


  function Escribir_Tooltip_Ampliar($lang, $dinero, $level)
  {
      echo ("<span>");
      if ($lang == 'en')
      {
        echo ("<table
		width=\"100%\" class=\"tooltip_interno\" align=\"left\"><tr><td style=\"text-align: left;\">");
        echo "<i>Brain amplification level ".$level."</i> ";
        echo ("<br/>");
        echo ("<p style=\"font-size: 11px;\"><i>Currently <b>".($level - 1)." levels</b></i></p>");
        echo ("<br/>");
        echo ($dinero);
        echo ("<img src=\"img/goldcoin.gif\" style=\"vertical-align:middle;\"> ");
	echo ("<b>");
        echo ("Click to develop");
	echo ("</b>");
        echo ("</td></tr></table>");
      } else {
        echo ("<table width=\"100%\" class=\"tooltip_interno\"><tr><td style=\"text-align: center;\">");
        echo "<i>Nivel ".$level." de amplificaci&oacute;n cerebral</i> ";
        echo ("<br/>");
        echo ("<p style=\"font-size: 11px;\"><i>Actualmente <b>".($level-1)." niveles</b></i></p>");
        echo ("<br/>");
        echo ($dinero);
        echo ("<img src=\"img/goldcoin.gif\" style=\"vertical-align:middle;\"> ");
	echo ("<b>");
        echo ("Pulsa para desarrollar");
	echo ("</b>");
        echo ("</td></tr></table>");
      }
      echo ("</span>");
//      echo ("<span class=\"goldcoin\"> 4</span>");
//      echo ("<img src=\"img/goldcoin.gif\" style=\"vertical-align:middle;\">");
  }


  // ***********************************************
  //    CONSUMIR OBJETO
  // ***********************************************

  if ($accion == 'consumir_objeto')
  {

    $jugador_campana = new Jugador_campana();
    $jugador_campana->SacarDatos($link_r, $idjugador, $idcampana);

    $idelemento = $_REQUEST['idelemento'];
    if (!is_numeric($idelemento))
    {
      die;
    }

    $objeto = new Objeto();

    // Ahora sacaremos de que objeto se trata
    $objeto->SacarDatos($link_r, $idelemento);

    if ($objeto->idjugadorcampana != $jugador_campana->id)
    {
      if ($lang == 'en')
      {
        echo ("<span class=\"error\">Error: Object isn't yours or doesn't exist.</span>");
      } else {
        echo ("<span class=\"error\">Error: El objeto no es tuyo o no existe.</span>");
      }
      echo("<br/>");
      die;
    }

    switch($objeto->tipo)
    {
      case 1:
                $jugador_campana->num_sexos = 1;
                $jugador_campana->GrabarNumSexos($link_w, $idjugador, $idcampana);
                if ($lang == 'en')
                {
                  echo ("<p class=\"correctosutil\">Object consumed! Now your specimens reproduce asexually through spores</p>");
                } else {
                  echo ("<p class=\"correctosutil\">&iexcl;Objeto consumido! Ahora tus espec&iacute;menes se reproducen por esporas</p>");
                }
	        // Generamos un log para este usuario
	        $log = new Log();
	        $log->idjugador = $idjugador;
	        $log->idcampana = $idcampana;
	        $log->tipo_suceso = 7; // 7, cambio de cantidad de sexos
	        $log->valor = 1; // Numero nuevo de sexos
	        $log->EscribirLog($link_w);

		break;
      case 2:
                $jugador_campana->num_sexos = 2;
                $jugador_campana->GrabarNumSexos($link_w, $idjugador, $idcampana);
                if ($lang == 'en')
                {
                  echo ("<p class=\"correctosutil\">Object consumed! Now your specimens reproduce sexually</p>");
                } else {
                  echo ("<p class=\"correctosutil\">&iexcl;Objeto consumido! Ahora tus espec&iacute;menes tienen dos sexos</p>");
                }
	        // Generamos un log para este usuario
	        $log = new Log();
	        $log->idjugador = $idjugador;
	        $log->idcampana = $idcampana;
	        $log->tipo_suceso = 7; // 7, cambio de cantidad de sexos
	        $log->valor = 2; // Numero nuevo de sexos
	        $log->EscribirLog($link_w);
		break;
      case 3:
                $jugador_campana->num_sexos = 3;
                $jugador_campana->GrabarNumSexos($link_w, $idjugador, $idcampana);
                if ($lang == 'en')
                {
                  echo ("<p class=\"correctosutil\">Object consumed! Now your specimens reproduce trisexually</p>");
                } else {
                  echo ("<p class=\"correctosutil\">&iexcl;Objeto consumido! Ahora tus espec&iacute;menes tienen tres sexos</p>");
                }
	        // Generamos un log para este usuario
	        $log = new Log();
	        $log->idjugador = $idjugador;
	        $log->idcampana = $idcampana;
	        $log->tipo_suceso = 7; // 7, cambio de cantidad de sexos
	        $log->valor = 3; // Numero nuevo de sexos
	        $log->EscribirLog($link_w);
		break;

      case 4:
                $jugador_campana->ratio_mutacion_pendiente = $jugador_campana->ratio_mutacion_pendiente + 5;
                $jugador_campana->GrabarMutacionPendiente($link_w, $idjugador, $idcampana);
                if ($lang == 'en')
                {
                  echo ("<p class=\"correctosutil\">Object consumed! Now you have 5 more points for using in mutation ratio alteration</p>");
                } else {
                  echo ("<p class=\"correctosutil\">&iexcl;Objeto consumido! Ahora tienes 5 puntos pendientes por usar alterando el ratio de mutaci&oacute;n</p>");
                }
		break;

      case 5:
                $jugador_campana->ratio_intensidad_mutacion = 1;
                $jugador_campana->GrabarIntensidadMutacion($link_w, $idjugador, $idcampana);
                if ($lang == 'en')
                {
                  echo ("<p class=\"correctosutil\">Object consumed! Now your specimens suffer <b>soft</b> mutations when evolving</p>");
                } else {
                  echo ("<p class=\"correctosutil\">&iexcl;Objeto consumido! Ahora tus espec&iacute;menes sufrir&aacute;n mutaciones <b>leves</b> al evolucionar</p>");
                }
	        // Generamos un log para este usuario
	        $log = new Log();
	        $log->idjugador = $idjugador;
	        $log->idcampana = $idcampana;
	        $log->tipo_suceso = 8; // 8, cambio de intensidad de mutacion
	        $log->valor = 1; // 1, suave
	        $log->EscribirLog($link_w);
		break;
      case 6:
                $jugador_campana->ratio_intensidad_mutacion = 2;
                $jugador_campana->GrabarIntensidadMutacion($link_w, $idjugador, $idcampana);
                if ($lang == 'en')
                {
                  echo ("<p class=\"correctosutil\">Object consumed! Now your specimens suffer <b>normal</b> mutations when evolving</p>");
                } else {
                  echo ("<p class=\"correctosutil\">&iexcl;Objeto consumido! Ahora tus espec&iacute;menes sufrir&aacute;n mutaciones <b>normales</b> al evolucionar</p>");
                }
	        // Generamos un log para este usuario
	        $log = new Log();
	        $log->idjugador = $idjugador;
	        $log->idcampana = $idcampana;
	        $log->tipo_suceso = 8; // 8, cambio de intensidad de mutacion
	        $log->valor = 2; // 2, media
	        $log->EscribirLog($link_w);
		break;
      case 7:
                $jugador_campana->ratio_intensidad_mutacion = 3;
                $jugador_campana->GrabarIntensidadMutacion($link_w, $idjugador, $idcampana);
                if ($lang == 'en')
                {
                  echo ("<p class=\"correctosutil\">Object consumed! Now your specimens suffer <b>strong</b> mutations when evolving</p>");
                } else {
                  echo ("<p class=\"correctosutil\">&iexcl;Objeto consumido! Ahora tus espec&iacute;menes sufrir&aacute;n mutaciones <b>fuertes</b> al evolucionar</p>");
                }
	        // Generamos un log para este usuario
	        $log = new Log();
	        $log->idjugador = $idjugador;
	        $log->idcampana = $idcampana;
	        $log->tipo_suceso = 8; // 8, cambio de intensidad de mutacion
	        $log->valor = 3; // 3, fuerte
	        $log->EscribirLog($link_w);
		break;
      case 8:
                $jugador_campana->mezcla_activa = 1;
                $jugador_campana->GrabarMezclaActiva($link_w, $idjugador, $idcampana);
                if ($lang == 'en')
                {
                  echo ("<p class=\"correctosutil\">Object consumed! Next generation you evolve will mix every deme</p>");
                } else {
                  echo ("<p class=\"correctosutil\">&iexcl;Objeto consumido! La pr&oacute;xima generaci&oacute;n que mezcles mezclar&aacute; distintos demes</p>");
                }
	        // Generamos un log para este usuario
	        $log = new Log();
	        $log->idjugador = $idjugador;
	        $log->idcampana = $idcampana;
	        $log->tipo_suceso = 10; // 10, hausado mezcla de demes
	        $log->valor = 0; // no importa
	        $log->EscribirLog($link_w);
		break;
      case 9:
                $jugador_campana->superman1 = 1;
                $jugador_campana->GrabarSuperman1($link_w, $idjugador, $idcampana);
                if ($lang == 'en')
                {
                  echo ("<p class=\"correctosutil\">Object consumed! Next generation you evolve will have a boost</p>");
                } else {
                  echo ("<p class=\"correctosutil\">&iexcl;Objeto consumido! La pr&oacute;xima generaci&oacute;n que mezcles estar&aacute; mejorada</p>");
                }
	        // Generamos un log para este usuario
	        $log = new Log();
	        $log->idjugador = $idjugador;
	        $log->idcampana = $idcampana;
	        $log->tipo_suceso = 9; // 9, ha usado superman
	        $log->valor = 1; // superman1
	        $log->EscribirLog($link_w);
		break;
      case 10:
                $jugador_campana->superman2 = 1;
                $jugador_campana->GrabarSuperman2($link_w, $idjugador, $idcampana);
                if ($lang == 'en')
                {
                  echo ("<p class=\"correctosutil\">Object consumed! Next generation you evolve will have an increased boost</p>");
                } else {
                  echo ("<p class=\"correctosutil\">&iexcl;Objeto consumido! La pr&oacute;xima generaci&oacute;n que mezcles estar&aacute; muy mejorada</p>");
                }
	        // Generamos un log para este usuario
	        $log = new Log();
	        $log->idjugador = $idjugador;
	        $log->idcampana = $idcampana;
	        $log->tipo_suceso = 9; // 9, ha usado superman
	        $log->valor = 2; // superman2
	        $log->EscribirLog($link_w);
		break;

      case 11:    // Destruir todos los especimenes de un deme
                // Sacamos datos del jugador, especimenes y niveles del arbol
                $jugador_campana = new Jugador_campana();
                $jugador_campana->SacarDatos($link_r, $idjugador, $idcampana);
                $total_especimenes = $jugador_campana->num_slots_deme_profundidades;
                $niveles_arbol = $jugador_campana->niveles_arbol;

                // Ahora borramos los especimenes antiguos
                $arbol = new Arbol();
		$especimen = new Especimen();
                $especimen->HacerGeneracionOldDeme($link_w, $idjugador, $idcampana, 1); // El 1 es $iddeme
                $especimen->BorrarGeneracionOldDeme($link_w, $idjugador, $idcampana, 1); // El 1 es $iddeme

                // Tenemos que sacar los niveles de su arbol, y el total es especimenes
                for ($i = 1; $i <= $total_especimenes; $i++)
		{
                  $especimen->CrearEspecimen($link_w, TOTAL_PUNTOS_BASE, 1, $idjugador, $idcampana, $i, $niveles_arbol);
                  $idarbol = mysql_insert_id($link_w);
                  // Generamos un arbol con n niveles
                  $string_arbol = $arbol->GenerarArbolInicial($niveles_arbol);
                  $especimen->arbol = $string_arbol;
                  $especimen->ActualizarArbol($link_w, $idarbol);
		}

                // Si el elegido para torneo esta aqui, tenemos que rehacerlo
                // y tambien rehacemos la eleccion en el torneo de deme especifico
                $especimen_torneo = new Especimen_Torneo();

                // Sacamos el deme del que tenemos aqui
                $especimen_torneo->ObtenEspecimenTorneo($link_r, 0, $idjugador);
                $idespecimen_torneo = $especimen_torneo->idespecimen;
                $especimen->SacarDatosPorId($link_r, $idespecimen_torneo);
                $deme_especimen_torneo = $especimen->iddeme;
                if ($deme_especimen_torneo == 1)
                {
                  $especimen_torneo->DesapuntarTorneo($link_w, $idjugador, $idcampana, 0);  // Del torneo absoluto
                  $especimen->SacarDatos($link_r, 1, 1, $idjugador, $idcampana); // el primero es iddeme, el segundo idslot
                  $especimen_torneo->ApuntarTorneo($link_w, $especimen->id, 0);
                }

                $especimen_torneo->DesapuntarTorneo($link_w, $idjugador, $idcampana, 1);  // El 1 es iddeme
                $especimen->SacarDatos($link_r, 1, 1, $idjugador, $idcampana); // el primero es iddeme, el segundo idslot
                $especimen_torneo->ApuntarTorneo($link_w, $especimen->id, 1);


                if ($lang == 'en')
                {
                  echo ("<p class=\"correctosutil\">Object consumed! Your abyssal depths deme specimens have been wiped out.</p>");
                } else {
                  echo ("<p class=\"correctosutil\">&iexcl;Objeto consumido! Tus espec&iacute;menes del deme de las profundidades han sido destru&iacute;dos.</p>");
                }
		break;

      case 12:    // Destruir todos los especimenes de un deme
                // Sacamos datos del jugador, especimenes y niveles del arbol
                $jugador_campana = new Jugador_campana();
                $jugador_campana->SacarDatos($link_r, $idjugador, $idcampana);
                $total_especimenes = $jugador_campana->num_slots_deme_bosque;
                $niveles_arbol = $jugador_campana->niveles_arbol;

                // Ahora borramos los especimenes antiguos
                $arbol = new Arbol();
		$especimen = new Especimen();
                $especimen->HacerGeneracionOldDeme($link_w, $idjugador, $idcampana, 2); // El 2 es $iddeme
                $especimen->BorrarGeneracionOldDeme($link_w, $idjugador, $idcampana, 2); // El 2 es $iddeme

                // Tenemos que sacar los niveles de su arbol, y el total es especimenes
                for ($i = 1; $i <= $total_especimenes; $i++)
		{
                  $especimen->CrearEspecimen($link_w, TOTAL_PUNTOS_BASE, 2, $idjugador, $idcampana, $i, $niveles_arbol);
                  $idarbol = mysql_insert_id($link_w);
                  // Generamos un arbol con n niveles
                  $string_arbol = $arbol->GenerarArbolInicial($niveles_arbol);
                  $especimen->arbol = $string_arbol;
                  $especimen->ActualizarArbol($link_w, $idarbol);
		}

                // Si el elegido para torneo esta aqui, tenemos que rehacerlo
                // y tambien rehacemos la eleccion en el torneo de deme especifico
                $especimen_torneo = new Especimen_Torneo();

                // Sacamos el deme del que tenemos aqui
                $especimen_torneo->ObtenEspecimenTorneo($link_r, 0, $idjugador);
                $idespecimen_torneo = $especimen_torneo->idespecimen;
                $especimen->SacarDatosPorId($link_r, $idespecimen_torneo);
                $deme_especimen_torneo = $especimen->iddeme;
                if ($deme_especimen_torneo == 2) // Para el torneo gordo
                {
                  $especimen_torneo->DesapuntarTorneo($link_w, $idjugador, $idcampana, 0);  // Del torneo absoluto
                  $especimen->SacarDatos($link_r, 1, 1, $idjugador, $idcampana); // el primero es iddeme, el segundo idslot
                  $especimen_torneo->ApuntarTorneo($link_w, $especimen->id, 0);
                }

                $especimen_torneo->DesapuntarTorneo($link_w, $idjugador, $idcampana, 2);  // El 1 es iddeme
                $especimen->SacarDatos($link_r, 2, 1, $idjugador, $idcampana); // el primero es iddeme, el segundo idslot
                $especimen_torneo->ApuntarTorneo($link_w, $especimen->id, 2);



                if ($lang == 'en')
                {
                  echo ("<p class=\"correctosutil\">Object consumed! Your forest deme specimens have been wiped out.</p>");
                } else {
                  echo ("<p class=\"correctosutil\">&iexcl;Objeto consumido! Tus espec&iacute;menes del deme del bosque han sido destru&iacute;dos.</p>");
                }
		break;

      case 13:    // Destruir todos los especimenes de un deme *** DEME DEL VOLCAN ****
                // Sacamos datos del jugador, especimenes y niveles del arbol
                $jugador_campana = new Jugador_campana();
                $jugador_campana->SacarDatos($link_r, $idjugador, $idcampana);
                $total_especimenes = $jugador_campana->num_slots_deme_volcan;
                $niveles_arbol = $jugador_campana->niveles_arbol;

                // Ahora borramos los especimenes antiguos
                $arbol = new Arbol();
		$especimen = new Especimen();
                $especimen->HacerGeneracionOldDeme($link_w, $idjugador, $idcampana, 3); // El 3 es $iddeme
                $especimen->BorrarGeneracionOldDeme($link_w, $idjugador, $idcampana, 3); // El 3 es $iddeme

                // Tenemos que sacar los niveles de su arbol, y el total es especimenes
                for ($i = 1; $i <= $total_especimenes; $i++)
		{
                  $especimen->CrearEspecimen($link_w, TOTAL_PUNTOS_BASE, 3, $idjugador, $idcampana, $i, $niveles_arbol);
                  $idarbol = mysql_insert_id($link_w);
                  // Generamos un arbol con n niveles
                  $string_arbol = $arbol->GenerarArbolInicial($niveles_arbol);
                  $especimen->arbol = $string_arbol;
                  $especimen->ActualizarArbol($link_w, $idarbol);
		}

                // Si el elegido para torneo esta aqui, tenemos que rehacerlo
                // y tambien rehacemos la eleccion en el torneo de deme especifico
                $especimen_torneo = new Especimen_Torneo();

                // Sacamos el deme del que tenemos aqui
                $especimen_torneo->ObtenEspecimenTorneo($link_r, 0, $idjugador);
                $idespecimen_torneo = $especimen_torneo->idespecimen;
                $especimen->SacarDatosPorId($link_r, $idespecimen_torneo);
                $deme_especimen_torneo = $especimen->iddeme;
                if ($deme_especimen_torneo == 3)
                {
                  $especimen_torneo->DesapuntarTorneo($link_w, $idjugador, $idcampana, 0);  // Del torneo absoluto
                  $especimen->SacarDatos($link_r, 1, 1, $idjugador, $idcampana); // el primero es iddeme, el segundo idslot
                  $especimen_torneo->ApuntarTorneo($link_w, $especimen->id, 0);
                }

                $especimen_torneo->DesapuntarTorneo($link_w, $idjugador, $idcampana, 3);  // El 3 es iddeme
                $especimen->SacarDatos($link_r, 3, 1, $idjugador, $idcampana); // el primero es iddeme, el segundo idslot
                $especimen_torneo->ApuntarTorneo($link_w, $especimen->id, 3);

                if ($lang == 'en')
                {
                  echo ("<p class=\"correctosutil\">Object consumed! Your volcano deme specimens have been wiped out.</p>");
                } else {
                  echo ("<p class=\"correctosutil\">&iexcl;Objeto consumido! Tus espec&iacute;menes del deme del volc&aacute;n han sido destru&iacute;dos.</p>");
                }
		break;

      case 14:    // Rejuvenecer a todos los especimenes
		$especimen = new Especimen();
		$especimen->Pocion_Rejuvejece($link_w, $idjugador, $idcampana);
                if ($lang == 'en')
                {
                  echo ("<p class=\"correctosutil\">Object consumed! Your specimens are now younger.</p>");
                } else {
                  echo ("<p class=\"correctosutil\">&iexcl;Objeto consumido! Tus espec&iacute;menes son ahora m&aacute;s j&oacute;venes.</p>");
                }
		break;

    }
    $objeto->EliminarElemento($link_w, $idelemento);
//    $objeto->ReordenarInventario($link_w, $idjugador, $idcampana);

    $accion = null;

  }


//        echo (" [<a href=\"index.php?catid=".$catid."&accion=consumir_objeto&idelemento=".$array[$j]['id']."\">");


  // ***********************************************
  //    COMPRAR OBJETO
  // ***********************************************

  if ($accion == 'comprar_objeto')
  {

    $jugador_campana = new Jugador_campana();
    $jugador_campana->SacarDatos($link_r, $idjugador, $idcampana);

    $idobjeto = $_REQUEST['idobjeto'];
    if (!is_numeric($idobjeto))
    {
      die;
    }

    $objeto = new Objeto();

    $numobjetos = $objeto->ContarInventario($link_r, $jugador_campana->id);
    if ($numobjetos < 5)
    {
      switch($idobjeto)
      {
        case 1:
                // Reproduccion asexual
                $coste = 4;
		break;
        case 2:
                // Reproduccion sexual
                $coste = 4;
		break;
        case 3:
                // Reproduccion trisexual
                $coste = 4;
		break;
        case 4:
                // Alterar 5 puntos la mutacion
                $coste = 1;
		break;
        case 5:
                // Mutacion soft
                $coste = 3;
		break;
        case 6:
                // Mutacion medium
                $coste = 3;
		break;
        case 7:
                // Mutacion hard
                $coste = 3;
		break;
        case 8:
                // Deme mixing
                $coste = 3;
		break;
        case 9:
                // Superman1
                $coste = 10;
		break;
        case 10:
                // Superman2
                $coste = 50;
		break;
        case 11:
                // Corrosivo1
                $coste = 2;
		break;
        case 12:
                // Corrosivo2
                $coste = 2;
		break;
        case 13:
                // Corrosivo3
                $coste = 2;
		break;
        case 14:
                // Ankh
                $coste = 10;
		break;
      }
      $result = $jugador_campana->RestarDinero($link_w, $idjugador, $idcampana, $coste);
      if ($result == 0)
      {
        $dinerito = $dinerito - $coste;  // Lo restamos para que aparezca actualizado

        // Ha conseguido comprarlo
        $objeto->tipo = $idobjeto;
        $objeto->cantidad = 1;
        $objeto->InsertarObjeto($link_w, $jugador_campana->id);
      } else {
        if ($lang == 'en')
        {
          echo ("<p class=\"errorsutil\">You lack the money to buy this </p>");
        } else {
          echo ("<p class=\"errorsutil\">No tienes cr&eacute;ditos suficientes para comprar esto</p>");
        }
      }

    } else {
      if ($lang == 'en')
      {
        echo ("<p class=\"errorsutil\">You lack inventory space to buy this</p>");
      } else {
        echo ("<p class=\"errorsutil\">No tienes espacio en el inventario para comprar esto</p>");
      }
    }
    $accion = null;

  }

  // ***********************************************
  //    COMPRAR (Y CONSUMIR) UNA OBRA ESTRUCTURAL
  // ***********************************************

  if ($accion == 'comprar_obra')
  {

    $arbol = new Arbol();
    $especimen = new Especimen();
    $jugador_campana = new Jugador_campana();
    $jugador_campana->SacarDatos($link_r, $idjugador, $idcampana);
    $idobra = $_REQUEST['idobra'];
    if (!is_numeric($idobra))
    {
      die;
    }

    switch($idobra)
    {

      case 1:
                $total_slots = $jugador_campana->num_slots_deme_profundidades +
			$jugador_campana->num_slots_deme_bosque +
			$jugador_campana->num_slots_deme_volcan;

                //$coste = pow(2, ($total_slots - 20)) * 2;
                $coste = ceil(pow(2, (  ($total_slots - 20) / 5)) * 2);
                $result = $jugador_campana->RestarDinero($link_w, $idjugador, $idcampana, $coste);
                if ($result == 0)
                {
                  $dinerito = $dinerito - $coste;  // Lo restamos para que aparezca actualizado
                  $jugador_campana->num_slots_deme_profundidades = $jugador_campana->num_slots_deme_profundidades + 1;
                  $jugador_campana->GrabarSlots($link_w, $idjugador, $idcampana);
                  if ($lang == 'en')
                  {
                    echo ("<p class=\"correctosutil\">Work done!</p>");
                  } else {
                    echo ("<p class=\"correctosutil\">&iexcl;Obra realizada!</p>");
                  }

                  // Por ultimo, insertamos el especimen
                  $especimen->CrearEspecimen($link_w, TOTAL_PUNTOS_BASE, 1, $idjugador, $idcampana, $jugador_campana->num_slots_deme_profundidades, $jugador_campana->niveles_arbol);
                  $idarbol = mysql_insert_id($link_w);
                  // Generamos un arbol con X niveles
                  $string_arbol = $arbol->GenerarArbolInicial($jugador_campana->niveles_arbol);
                  $especimen->arbol = $string_arbol;
                  $especimen->ActualizarArbol($link_w, $idarbol);

  	          // Generamos un log para este usuario
	          $log = new Log();
	          $log->idjugador = $idjugador;
	          $log->idcampana = $idcampana;
	          $log->tipo_suceso = 4; // 4, ha aumentado slots
	          $log->valor = 1; // 1, deme profundidades
	          $log->EscribirLog($link_w);

                } else {
                  if ($lang == 'en')
                  {
                    echo ("<p class=\"errorsutil\">You lack the money to build this</p>");
                  } else {
                    echo ("<p class=\"errorsutil\">No tienes dinero para realizar esta obra</p>");
                  }
                }

		break;

      case 2:
                $total_slots = $jugador_campana->num_slots_deme_profundidades +
			$jugador_campana->num_slots_deme_bosque +
			$jugador_campana->num_slots_deme_volcan;

                //$coste = pow(2, ($total_slots - 20)) * 2;
                $coste = ceil(pow(2, (  ($total_slots - 20) / 5)) * 2);
                $result = $jugador_campana->RestarDinero($link_w, $idjugador, $idcampana, $coste);
                if ($result == 0)
                {
                  $dinerito = $dinerito - $coste;  // Lo restamos para que aparezca actualizado
                  $jugador_campana->num_slots_deme_bosque = $jugador_campana->num_slots_deme_bosque + 1;
                  $jugador_campana->GrabarSlots($link_w, $idjugador, $idcampana);
                  if ($lang == 'en')
                  {
                    echo ("<p class=\"correctosutil\">Work done!</p>");
                  } else {
                    echo ("<p class=\"correctosutil\">&iexcl;Obra realizada!</p>");
                  }

                  // Por ultimo, insertamos el especimen
                  $especimen->CrearEspecimen($link_w, TOTAL_PUNTOS_BASE, 2, $idjugador, $idcampana, $jugador_campana->num_slots_deme_bosque, $jugador_campana->niveles_arbol);
                  $idarbol = mysql_insert_id($link_w);
                  // Generamos un arbol con X niveles
                  $string_arbol = $arbol->GenerarArbolInicial($jugador_campana->niveles_arbol);
                  $especimen->arbol = $string_arbol;
                  $especimen->ActualizarArbol($link_w, $idarbol);

  	          // Generamos un log para este usuario
	          $log = new Log();
	          $log->idjugador = $idjugador;
	          $log->idcampana = $idcampana;
	          $log->tipo_suceso = 4; // 4, ha aumentado slots
	          $log->valor = 2; // 2, deme bosque
	          $log->EscribirLog($link_w);

                } else {
                  if ($lang == 'en')
                  {
                    echo ("<p class=\"errorsutil\">You lack the money to build this</p>");
                  } else {
                    echo ("<p class=\"errorsutil\">No tienes dinero para realizar esta obra</p>");
                  }
                }
		break;

      case 3:
                $total_slots = $jugador_campana->num_slots_deme_profundidades +
			$jugador_campana->num_slots_deme_bosque +
			$jugador_campana->num_slots_deme_volcan;

                //$coste = pow(2, ($total_slots - 20)) * 2;
                $coste = ceil(pow(2, (  ($total_slots - 20) / 5)) * 2);
                $result = $jugador_campana->RestarDinero($link_w, $idjugador, $idcampana, $coste);
                if ($result == 0)
                {
                  $dinerito = $dinerito - $coste;  // Lo restamos para que aparezca actualizado
                  $jugador_campana->num_slots_deme_volcan = $jugador_campana->num_slots_deme_volcan + 1;
                  $jugador_campana->GrabarSlots($link_w, $idjugador, $idcampana);
                  if ($lang == 'en')
                  {
                    echo ("<p class=\"correctosutil\">Work done!</p>");
                  } else {
                    echo ("<p class=\"correctosutil\">&iexcl;Obra realizada!</p>");
                  }

                  // Por ultimo, insertamos el especimen
                  $especimen->CrearEspecimen($link_w, TOTAL_PUNTOS_BASE, 3, $idjugador, $idcampana, $jugador_campana->num_slots_deme_volcan, $jugador_campana->niveles_arbol);
                  $idarbol = mysql_insert_id($link_w);
                  // Generamos un arbol con X niveles
                  $string_arbol = $arbol->GenerarArbolInicial($jugador_campana->niveles_arbol);
                  $especimen->arbol = $string_arbol;
                  $especimen->ActualizarArbol($link_w, $idarbol);

  	          // Generamos un log para este usuario
	          $log = new Log();
	          $log->idjugador = $idjugador;
	          $log->idcampana = $idcampana;
	          $log->tipo_suceso = 4; // 4, ha aumentado slots
	          $log->valor = 3; // 3, deme volcan
	          $log->EscribirLog($link_w);

                } else {
                  if ($lang == 'en')
                  {
                    echo ("<p class=\"errorsutil\">You lack the money to build this</p>");
                  } else {
                    echo ("<p class=\"errorsutil\">No tienes dinero para realizar esta obra</p>");
                  }
                }
		break;

      case 4:
                if ($jugador_campana->niveles_arbol == 3) { $coste = 20; }
                if ($jugador_campana->niveles_arbol == 4) { $coste = 150; }
                if ($jugador_campana->niveles_arbol == 5) { $coste = 1000; }
                if ($jugador_campana->niveles_arbol == 6) {
                  if ($lang == 'en')
                  {
                    echo ("(You can't make the tree deeper)");
                  } else {
                    echo ("(No puedes ampliar m&aacute;s tu &aacute;rbol)");
                  }
                } else {
                  $result = $jugador_campana->RestarDinero($link_w, $idjugador, $idcampana, $coste);
                  if ($result == 0)
                  {
                    $dinerito = $dinerito - $coste;  // Lo restamos para que aparezca actualizado
                    $jugador_campana->niveles_arbol = $jugador_campana->niveles_arbol + 1;
                    $jugador_campana->GrabarNiveles($link_w, $idjugador, $idcampana);

                    // Borramos todos los ejemplares antiguos
                    // ................................................
                    $especimen->HacerGeneracionOld($link_w, $idjugador, $idcampana);
                    $especimen->BorrarGeneracionOld($link_w, $idjugador, $idcampana);

                    // Al hacer esto, hay que generarlos todos de nuevo
                    // ................................................
                    for ($i = 1; $i <= $jugador_campana->num_slots_deme_profundidades; $i++)
                    {
                      $especimen->CrearEspecimen($link_w, TOTAL_PUNTOS_BASE, 1, $idjugador, $idcampana, $i, $jugador_campana->niveles_arbol);
                      $idarbol = mysql_insert_id($link_w);
                      $string_arbol = $arbol->GenerarArbolInicial($jugador_campana->niveles_arbol);
                      $especimen->arbol = $string_arbol;
                      $especimen->ActualizarArbol($link_w, $idarbol);
                    }
                    // Segundo deme (bosque)
                    for ($i = 1; $i <= $jugador_campana->num_slots_deme_bosque; $i++)
                    {
                      $especimen->CrearEspecimen($link_w, TOTAL_PUNTOS_BASE, 2, $idjugador, $idcampana, $i, $jugador_campana->niveles_arbol);
                      $idarbol = mysql_insert_id($link_w);
                      $string_arbol = $arbol->GenerarArbolInicial($jugador_campana->niveles_arbol);
                      $especimen->arbol = $string_arbol;
                      $especimen->ActualizarArbol($link_w, $idarbol);
                    }
                    // Tercer deme (volcan)
                    for ($i = 1; $i <= $jugador_campana->num_slots_deme_volcan; $i++)
                    {
                      $especimen->CrearEspecimen($link_w, TOTAL_PUNTOS_BASE, 3, $idjugador, $idcampana, $i, $jugador_campana->niveles_arbol);
                      $idarbol = mysql_insert_id($link_w);
                      $string_arbol = $arbol->GenerarArbolInicial($jugador_campana->niveles_arbol);
                      $especimen->arbol = $string_arbol;
                      $especimen->ActualizarArbol($link_w, $idarbol);
                    }

                    if ($lang == 'en')
                    {
                      echo ("<p class=\"correctosutil\">Work done!</p>");
                    } else {
                      echo ("<p class=\"correctosutil\">&iexcl;Obra realizada!</p>");
                    }

                    // Rehacemos eleccion para torneo y para torneo de deme
                    $especimen_torneo = new Especimen_Torneo();
                    $especimen_torneo->DesapuntarTorneo($link_w, $idjugador, $idcampana, 0);  // El 1 es iddeme
                    $especimen_torneo->DesapuntarTorneo($link_w, $idjugador, $idcampana, 1);  // El 1 es iddeme
                    $especimen_torneo->DesapuntarTorneo($link_w, $idjugador, $idcampana, 2);  // El 1 es iddeme
                    $especimen_torneo->DesapuntarTorneo($link_w, $idjugador, $idcampana, 3);  // El 1 es iddeme
                    $especimen->SacarDatos($link_r, 1, 1, $idjugador, $idcampana); // el primero es iddeme, el segundo idslot
                    $especimen_torneo->ApuntarTorneo($link_w, $especimen->id, 0);
                    $especimen_torneo->ApuntarTorneo($link_w, $especimen->id, 1);
                    $especimen->SacarDatos($link_r, 2, 1, $idjugador, $idcampana); // el primero es iddeme, el segundo idslot
                    $especimen_torneo->ApuntarTorneo($link_w, $especimen->id, 2);
                    $especimen->SacarDatos($link_r, 3, 1, $idjugador, $idcampana); // el primero es iddeme, el segundo idslot
                    $especimen_torneo->ApuntarTorneo($link_w, $especimen->id, 3);


    	            // Generamos un log para este usuario
	            $log = new Log();
	            $log->idjugador = $idjugador;
	            $log->idcampana = $idcampana;
	            $log->tipo_suceso = 3; // Aumentamos niveles
	            $log->valor = $jugador_campana->niveles_arbol; // numero niveles nuevo
	            $log->EscribirLog($link_w);


                  } else {
                    if ($lang == 'en')
                    {
                      echo ("<p class=\"errorsutil\">You lack the money to build this</p>");
                    } else {
                      echo ("<p class=\"errorsutil\">No tienes dinero para realizar esta obra</p>");
                    }
                  }
                }
                break;

    }

    // Aparecemos otra vez en la tienda
    $accion = null;

  }




  // ***********************************************************************************************
  //                        Pantalla principal de la tienda/inventario
  // **************************************

  if ($accion == null)
  {

    $jugador_campana = new Jugador_campana();
    $jugador_campana->SacarDatos($link_r, $idjugador, $idcampana);

    $objeto = new Objeto();

    $numobjetos = $objeto->ContarInventario($link_r, $jugador_campana->id);

    echo ("<span style=\"color: #ddab00;
		font-size: 13px;
		font-weight: bold;
		\">");
    if ($lang == 'en')
    {
      echo ("Inventory ");
    } else {
      echo ("Inventario");
    }
    echo ("</span>");
    echo ("<br/>");
    echo ("<br/>");


      $array = $objeto->ObtenerInventario($link_r, $jugador_campana->id);
      for ($j=1; $j <= $numobjetos; $j++)
      {
        switch($array[$j]['tipo'])
        {
          case 1:
                      echo ("<a href=\"index.php?catid=".$catid."&accion=consumir_objeto&idcampana=".$idcampana."&idelemento=".$array[$j]['id']."\"
                        class=\"Ntooltip\"
                        >");
                      echo ("<img src=\"img/inventario_sex1.png\"
                                >");
                      echo ("<span>");
                      if ($lang == 'en')
                      {
                        echo ("<table width=\"100%\" class=\"tooltip_interno\"><tr><td>");
                        echo ("Fukushima I Coolant for mutating specimens into asexual (spores) reproduction.");
                        echo ("<br/><br/><p style=\"color:#ffff55; font-weight: bold;\">Click to consume</p>");
                        echo ("</td></tr></table>");
                      } else {
                        echo ("<table width=\"100%\" class=\"tooltip_interno\"><tr><td>");
                        echo ("Refrigerante de Fukushima I para mutar tus espec&iacute;menes haciendo que tengan reproducci&oacute;n asexual (por esporas).");
                        echo ("<br/><br/><p style=\"color:#ffff55; font-weight: bold;\">Haz click para consumir</p>");
                        echo ("</td></tr></table>");
                      }
                      echo ("</span>");
                      echo ("</a>");
                      echo ("&nbsp;&nbsp;");
                  break;
          case 2:
                      echo ("<a href=\"index.php?catid=".$catid."&accion=consumir_objeto&idcampana=".$idcampana."&idelemento=".$array[$j]['id']."\"
                        class=\"Ntooltip\"
                        >");
                      echo ("<img src=\"img/inventario_sex2.png\"
                                >");
                      echo ("<span>");
                      if ($lang == 'en')
                      {
                        echo ("<table width=\"100%\" class=\"tooltip_interno\"><tr><td>");
                        echo ("Fukushima II Coolant for mutating specimens into sexual reproduction.");
                        echo ("<br/><br/><p style=\"color:#ffff55; font-weight: bold;\">Click to consume</p>");
                        echo ("</td></tr></table>");
                      } else {
                        echo ("<table width=\"100%\" class=\"tooltip_interno\"><tr><td>");
                        echo ("Refrigerante de Fukushima II para mutar tus espec&iacute;menes haciendo que tengan reproducci&oacute;n sexual.");
                        echo ("<br/><br/><p style=\"color:#ffff55; font-weight: bold;\">Haz click para consumir</p>");
                        echo ("</td></tr></table>");
                      }
                      echo ("</span>");
                      echo ("</a>");
                      echo ("&nbsp;&nbsp;");
                  break;
          case 3:
                      echo ("<a href=\"index.php?catid=".$catid."&accion=consumir_objeto&idcampana=".$idcampana."&idelemento=".$array[$j]['id']."\"
                        class=\"Ntooltip\"
                        >");
                      echo ("<img src=\"img/inventario_sex3.png\"
                                >");
                      echo ("<span>");
                      if ($lang == 'en')
                      {
                        echo ("<table width=\"100%\" class=\"tooltip_interno\"><tr><td>");
                        echo ("Fukushima III Coolant for mutating specimens into trisexual reproduction.");
                        echo ("<br/><br/><p style=\"color:#ffff55; font-weight: bold;\">Click to consume</p>");
                        echo ("</td></tr></table>");
                      } else {
                        echo ("<table width=\"100%\" class=\"tooltip_interno\"><tr><td>");
                        echo ("Refrigerante de Fukushima III para mutar tus espec&iacute;menes haciendo que tengan reproducci&oacute;n trisexual.");
                        echo ("<br/><br/><p style=\"color:#ffff55; font-weight: bold;\">Haz click para consumir</p>");
                        echo ("</td></tr></table>");
                      }
                      echo ("</span>");
                      echo ("</a>");
                      echo ("&nbsp;&nbsp;");
                  break;
          case 4:
		      echo ("<a href=\"index.php?catid=".$catid."&accion=consumir_objeto&idcampana=".$idcampana."&idelemento=".$array[$j]['id']."\"
			class=\"Ntooltip\"
			>");
		      echo ("<img src=\"img/inventario_mutacion.png\"
		                >");
		      echo ("<span>");
		      if ($lang == 'en')
		      {
		        echo ("<table width=\"100%\" class=\"tooltip_interno\"><tr><td>");
		        echo ("Three Mile Island dust for modifying the mutation ratio 5 points (without limit)");
		        echo ("<br/><br/><p style=\"color:#ffff55; font-weight: bold;\">Click to consume</p>");
		        echo ("</td></tr></table>");
		      } else {
		        echo ("<table width=\"100%\" class=\"tooltip_interno\"><tr><td>");
		        echo ("Polvo de Three Mile Island para modificar el ratio de mutaci&oacute;n 5 puntos (sin l&iacute;mite)");
		        echo ("<br/><br/><p style=\"color:#ffff55; font-weight: bold;\">Haz click para consumir</p>");
		        echo ("</td></tr></table>");
		      }
		      echo ("</span>");
		      echo ("</a>");
		      echo ("&nbsp;&nbsp;");
                  break;
          case 5:
		      echo ("<a href=\"index.php?catid=".$catid."&accion=consumir_objeto&idcampana=".$idcampana."&idelemento=".$array[$j]['id']."\"
			class=\"Ntooltip\"
			>");
		      echo ("<img src=\"img/inventario_caesium_134.png\"
 		                >");
		      echo ("<span>");
		      if ($lang == 'en')
		      {
		        echo ("<table width=\"100%\" class=\"tooltip_interno\"><tr><td>");
		        echo ("Caesium 134 Oxide for turning mutation intensity to <b>soft</b>");
		        echo ("<br/><br/><p style=\"color:#ffff55; font-weight: bold;\">Click to consume</p>");
		        echo ("</td></tr></table>");
		      } else {
		        echo ("<table width=\"100%\" class=\"tooltip_interno\"><tr><td>");
		        echo ("&Oacute;xido de Cesio 134 para cambiar la intensidad de la mutaci&oacute;n a <b>d&eacute;bil</b>");
		        echo ("<br/><br/><p style=\"color:#ffff55; font-weight: bold;\">Haz click para consumir</p>");
		        echo ("</td></tr></table>");
		      }
		      echo ("</span>");
		      echo ("</a>");
		      echo ("&nbsp;&nbsp;");
                  break;
          case 6:
		      echo ("<a href=\"index.php?catid=".$catid."&accion=consumir_objeto&idcampana=".$idcampana."&idelemento=".$array[$j]['id']."\"
			class=\"Ntooltip\"
			>");
		      echo ("<img src=\"img/inventario_caesium_135.png\"
 		                >");
		      echo ("<span>");
		      if ($lang == 'en')
		      {
		        echo ("<table width=\"100%\" class=\"tooltip_interno\"><tr><td>");
		        echo ("Caesium 135 Oxide for turning mutation intensity to <b>normal</b>");
		        echo ("<br/><br/><p style=\"color:#ffff55; font-weight: bold;\">Click to consume</p>");
		        echo ("</td></tr></table>");
		      } else {
		        echo ("<table width=\"100%\" class=\"tooltip_interno\"><tr><td>");
		        echo ("&Oacute;xido de Cesio 135 para cambiar la intensidad de la mutaci&oacute;n a <b>normal</b>");
		        echo ("<br/><br/><p style=\"color:#ffff55; font-weight: bold;\">Haz click para consumir</p>");
		        echo ("</td></tr></table>");
		      }
		      echo ("</span>");
		      echo ("</a>");
		      echo ("&nbsp;&nbsp;");
                  break;
          case 7:
		      echo ("<a href=\"index.php?catid=".$catid."&accion=consumir_objeto&idcampana=".$idcampana."&idelemento=".$array[$j]['id']."\"
			class=\"Ntooltip\"
			>");
		      echo ("<img src=\"img/inventario_caesium_137.png\"
 		                >");
		      echo ("<span>");
		      if ($lang == 'en')
		      {
		        echo ("<table width=\"100%\" class=\"tooltip_interno\"><tr><td>");
		        echo ("Caesium 134 Oxide for turning mutation intensity to <b>strong</b>");
		        echo ("<br/><br/><p style=\"color:#ffff55; font-weight: bold;\">Click to consume</p>");
		        echo ("</td></tr></table>");
		      } else {
		        echo ("<table width=\"100%\" class=\"tooltip_interno\"><tr><td>");
		        echo ("&Oacute;xido de Cesio 134 para cambiar la intensidad de la mutaci&oacute;n a <b>fuerte</b>");
		        echo ("<br/><br/><p style=\"color:#ffff55; font-weight: bold;\">Haz click para consumir</p>");
		        echo ("</td></tr></table>");
		      }
		      echo ("</span>");
		      echo ("</a>");
		      echo ("&nbsp;&nbsp;");
                  break;
          case 8:
		      echo ("<a href=\"index.php?catid=".$catid."&accion=consumir_objeto&idcampana=".$idcampana."&idelemento=".$array[$j]['id']."\"
			class=\"Ntooltip\"
			>");
		      echo ("<img src=\"img/inventario_mezcla.png\"
		                >");
		      echo ("<span>");
		      if ($lang == 'en')
		      {
		        echo ("<table width=\"100%\" class=\"tooltip_interno\"><tr><td>");
		        echo ("Polonium 210 Cloud for deme mixing in the next generation after consuming");
		        echo ("<br/><br/><p style=\"color:#ffff55; font-weight: bold;\">Click to consume</p>");
		        echo ("</td></tr></table>");
		      } else {
		        echo ("<table width=\"100%\" class=\"tooltip_interno\"><tr><td>");
		        echo ("Nube de Polonio 210 para mezcla de demes en la generaci&oacute;n siguiente a su consumo");
		        echo ("<br/><br/><p style=\"color:#ffff55; font-weight: bold;\">Haz click para consumir</p>");
		        echo ("</td></tr></table>");
		      }
		      echo ("</span>");
		      echo ("</a>");
		      echo ("&nbsp;&nbsp;");
                  break;
          case 9:
                      echo ("<a href=\"index.php?catid=".$catid."&accion=consumir_objeto&idcampana=".$idcampana."&idelemento=".$array[$j]['id']."\"
                        class=\"Ntooltip\"
                        >");
                      echo ("<img src=\"img/inventario_superman1.png\"
                                >");
                      echo ("<span>");
                      if ($lang == 'en')
                      {
                        echo ("<table width=\"100%\" class=\"tooltip_interno\"><tr><td>");
                        echo ("5X Chernobyl Extract for a boost in the basic specimen characteristics as next generation is evolved.");
                        echo ("<br/><br/><p style=\"color:#ffff55; font-weight: bold;\">Click to consume</p>");
                        echo ("</td></tr></table>");
                      } else {
                        echo ("<table width=\"100%\" class=\"tooltip_interno\"><tr><td>");
                        echo ("Extracto 5X de Chernobyl para una mejora de las caracter&iacute;sticas b&aacute;sicas de los espec&iacute;menes en la pr&oacute;xima generaci&oacute;n evolucionada.");
                        echo ("<br/><br/><p style=\"color:#ffff55; font-weight: bold;\">Haz click para consumir</p>");
                        echo ("</td></tr></table>");
                      }
                      echo ("</span>");
                      echo ("</a>");
                      echo ("&nbsp;&nbsp;");
                  break;
          case 10:
                      echo ("<a href=\"index.php?catid=".$catid."&accion=consumir_objeto&idcampana=".$idcampana."&idelemento=".$array[$j]['id']."\"
                        class=\"Ntooltip\"
                        >");
                      echo ("<img src=\"img/inventario_superman2.png\"
                                >");
                      echo ("<span>");
                      if ($lang == 'en')
                      {
                        echo ("<table width=\"100%\" class=\"tooltip_interno\"><tr><td>");
                        echo ("10X Chernobyl Extract for an increased boost in the basic specimen characteristics as next generation is evolved.");
                        echo ("<br/><br/><p style=\"color:#ffff55; font-weight: bold;\">Click to consume</p>");
                        echo ("</td></tr></table>");
                      } else {
                        echo ("<table width=\"100%\" class=\"tooltip_interno\"><tr><td>");
                        echo ("Extracto 10X de Chernobyl para una mejora aumentada de las caracter&iacute;sticas b&aacute;sicas de los espec&iacute;menes en la pr&oacute;xima generaci&oacute;n evolucionada.");
                        echo ("<br/><br/><p style=\"color:#ffff55; font-weight: bold;\">Haz click para consumir</p>");
                        echo ("</td></tr></table>");
                      }
                      echo ("</span>");
                      echo ("</a>");
                      echo ("&nbsp;&nbsp;");
                  break;
          case 11:
                      echo ("<a href=\"index.php?catid=".$catid."&accion=consumir_objeto&idcampana=".$idcampana."&idelemento=".$array[$j]['id']."\"
                        class=\"Ntooltip\"
                        >");
                      echo ("<img src=\"img/inventario_corrosivo1.png\"
                                >");
                      echo ("<span>");
                      if ($lang == 'en')
                      {
                        echo ("<table width=\"100%\" class=\"tooltip_interno\"><tr><td>");
                        echo ("Hydrogen Peroxide to destroy all the specimens in your abyssal depths deme and replace them by random ones (non evolved from anything).");
                        echo ("<br/><br/><p style=\"color:#ffff55; font-weight: bold;\">Click to consume</p>");
                        echo ("</td></tr></table>");
                      } else {
                        echo ("<table width=\"100%\" class=\"tooltip_interno\"><tr><td>");
                        echo ("Per&oacute;xido de Hidr&oacute;geno para destruir todos los espec&iacute;menes en tu deme de las profundidades y sustituirlos por otros aleatorios (sin evoluci&oacute;n previa).");
                        echo ("<br/><br/><p style=\"color:#ffff55; font-weight: bold;\">Haz click para consumir</p>");
                        echo ("</td></tr></table>");
                      }
                      echo ("</span>");
                      echo ("</a>");
                      echo ("&nbsp;&nbsp;");
                  break;
          case 12:
                      echo ("<a href=\"index.php?catid=".$catid."&accion=consumir_objeto&idcampana=".$idcampana."&idelemento=".$array[$j]['id']."\"
                        class=\"Ntooltip\"
                        >");
                      echo ("<img src=\"img/inventario_corrosivo2.png\"
                                >");
                      echo ("<span>");
                      if ($lang == 'en')
                      {
                        echo ("<table width=\"100%\" class=\"tooltip_interno\"><tr><td>");
                        echo ("Hydrogen Sulfide to destroy all the specimens in your forest deme and replace them by random ones (non evolved from anything).");
                        echo ("<br/><br/><p style=\"color:#ffff55; font-weight: bold;\">Click to consume</p>");
                        echo ("</td></tr></table>");
                      } else {
                        echo ("<table width=\"100%\" class=\"tooltip_interno\"><tr><td>");
                        echo ("&Aacute;cido Sulfh&iacute;drico para destruir todos los espec&iacute;menes en tu deme del bosque y sustituirlos por otros aleatorios (sin evoluci&oacute;n previa).");
                        echo ("<br/><br/><p style=\"color:#ffff55; font-weight: bold;\">Haz click para consumir</p>");
                        echo ("</td></tr></table>");
                      }
                      echo ("</span>");
                      echo ("</a>");
                      echo ("&nbsp;&nbsp;");
                  break;
          case 13:
                      echo ("<a href=\"index.php?catid=".$catid."&accion=consumir_objeto&idcampana=".$idcampana."&idelemento=".$array[$j]['id']."\"
                        class=\"Ntooltip\"
                        >");
                      echo ("<img src=\"img/inventario_corrosivo3.png\"
                                >");
                      echo ("<span>");
                      if ($lang == 'en')
                      {
                        echo ("<table width=\"100%\" class=\"tooltip_interno\"><tr><td>");
                        echo ("Sulfuric Acid to destroy all the specimens in your volcano deme and replace them by random ones (non evolved from anything).");
                        echo ("<br/><br/><p style=\"color:#ffff55; font-weight: bold;\">Click to consume</p>");
                        echo ("</td></tr></table>");
                      } else {
                        echo ("<table width=\"100%\" class=\"tooltip_interno\"><tr><td>");
                        echo ("&Aacute;cido Sulf&uacute;rico para destruir todos los espec&iacute;menes en tu deme del volc&aacute;n y sustituirlos por otros aleatorios (sin evoluci&oacute;n previa).");
                        echo ("<br/><br/><p style=\"color:#ffff55; font-weight: bold;\">Haz click para consumir</p>");
                        echo ("</td></tr></table>");
                      }
                      echo ("</span>");
                      echo ("</a>");
                      echo ("&nbsp;&nbsp;");
                  break;
          case 14:
                      echo ("<a href=\"index.php?catid=".$catid."&accion=consumir_objeto&idcampana=".$idcampana."&idelemento=".$array[$j]['id']."\"
                        class=\"Ntooltip\"
                        >");
                      echo ("<img src=\"img/inventario_ankh.png\"
                                >");
                      echo ("<span>");
                      if ($lang == 'en')
                      {
                        echo ("<table width=\"100%\" class=\"tooltip_interno\"><tr><td>");
                        echo ("Resveratrol solute to rejuvenate all your specimens");
                        echo ("<br/><br/><p style=\"color:#ffff55; font-weight: bold;\">Click to consume</p>");
                        echo ("</td></tr></table>");
                      } else {
                        echo ("<table width=\"100%\" class=\"tooltip_interno\"><tr><td>");
                        echo ("Soluto de Resveratrol para rejuvenecer a todos tus espec&iacute;menes");
                        echo ("<br/><br/><p style=\"color:#ffff55; font-weight: bold;\">Haz click para consumir</p>");
                        echo ("</td></tr></table>");
                      }
                      echo ("</span>");
                      echo ("</a>");
                      echo ("&nbsp;&nbsp;");
                  break;
        }
      }


      for ($m=$j; $m <= 5; $m++)
      {
        echo ("<a href=\"#\" class=\"Ntooltip\">");
        echo ("<img src=\"img/inventario_vacio.png\"
                  >");
        echo ("<span>");
        if ($lang == 'en')
        {
          echo ("<table width=\"100%\" class=\"tooltip_interno_2\"><tr><td>");
          echo ("Empty inventory slot.");
          echo ("</td></tr></table>");
        } else {
          echo ("<table width=\"100%\" class=\"tooltip_interno_2\"><tr><td>");
          echo ("Hueco de inventario vac&iacute;o.");
          echo ("</td></tr></table>");
        }
        echo ("</span>");
        echo ("</a>");
        echo ("&nbsp;&nbsp;");
      }






    // ****************************
    //       BUFFS activos
    // ****************************

    $numbuffs = 0;

      echo ("&nbsp;");
      echo ("&nbsp;");
      echo ("&nbsp;");
      echo ("&nbsp;");

    // Buff de Chernobyl 5X
    if ($jugador_campana->superman1 == 1)
    {
      $numbuffs++;
      echo ("<a href=\"#\" class=\"Ntooltip\">");
      echo ("<img src=\"img/buff_superman1.png\"
                >");
      echo ("<span>");
      if ($lang == 'en')
      {
        echo ("<table width=\"100%\" class=\"tooltip_interno\"><tr><td>");
        echo ("Chernobyl 5X strength active for next generation");
        echo ("</td></tr></table>");
      } else {
        echo ("<table width=\"100%\" class=\"tooltip_interno\"><tr><td>");
        echo ("Fuerza de Chernobyl 5X activa para la pr&oacute;xima generaci&oacute;n");
        echo ("</td></tr></table>");
      }
      echo ("</span>");
      echo ("</a>");
      echo ("&nbsp;&nbsp;");
    }

    // Buff de Chernobyl 10X
    if ($jugador_campana->superman2 == 1)
    {
      $numbuffs++;
      echo ("<a href=\"#\" class=\"Ntooltip\">");
      echo ("<img src=\"img/buff_superman2.png\"
                >");
      echo ("<span>");
      if ($lang == 'en')
      {
        echo ("<table width=\"100%\" class=\"tooltip_interno\"><tr><td>");
        echo ("Chernobyl 10X strength active for next generation");
        echo ("</td></tr></table>");
      } else {
        echo ("<table width=\"100%\" class=\"tooltip_interno\"><tr><td>");
        echo ("Fuerza de Chernobyl 10X activa para la pr&oacute;xima generaci&oacute;n");
        echo ("</td></tr></table>");
      }
      echo ("</span>");
      echo ("</a>");
      echo ("&nbsp;&nbsp;");
    }

    // Buff de mezcla activa
    if ($jugador_campana->mezcla_activa == 1)
    {
      $numbuffs++;
      echo ("<a href=\"#\" class=\"Ntooltip\">");
      echo ("<img src=\"img/buff_mezcla.png\"
                >");
      echo ("<span>");
      if ($lang == 'en')
      {
        echo ("<table width=\"100%\" class=\"tooltip_interno\"><tr><td>");
        echo ("Deme mixing active for next generation");
        echo ("</td></tr></table>");
      } else {
        echo ("<table width=\"100%\" class=\"tooltip_interno\"><tr><td>");
        echo ("Mezcla de demes activo para la pr&oacute;xima generaci&oacute;n");
        echo ("</td></tr></table>");
      }
      echo ("</span>");
      echo ("</a>");
      echo ("&nbsp;&nbsp;");
    }

    // Mutaciones pendientes
    if ($jugador_campana->ratio_mutacion_pendiente > 0)
    {
      $numbuffs++;
      echo ("<a href=\"#\" class=\"Ntooltip\">");
      echo ("<img src=\"img/buff_mutacion.png\"
                >");
      echo ("<span>");
      if ($lang == 'en')
      {
        echo ("<table width=\"100%\" class=\"tooltip_interno\"><tr><td>");
        echo ("Remaining points for ratio mutation manipulation : ".$jugador_campana->ratio_mutacion_pendiente);
        echo ("</td></tr></table>");
      } else {
        echo ("<table width=\"100%\" class=\"tooltip_interno\"><tr><td>");
        echo ("Puntos por gastar en alterar el ratio de mutaci&oacute;n : ".$jugador_campana->ratio_mutacion_pendiente);
        echo ("</td></tr></table>");
      }
      echo ("</span>");
      echo ("</a>");
    }


   echo ("<br/>");
   echo ("<br/>");



   //               -------------------------------------------------------------------------
   //                             JAVASCRIPT CON LA DESCRIPCION DE LOS OBJETOS
   //               -------------------------------------------------------------------------
   ?>

     <script>
        var text_es = new Array();
        var text_en = new Array();
        text_en[1] = '<span style="color: #ffff00; font-size: 13px;"><b>Fukushima I coolant</b></span> is a thick liquid which can be applied to specimens to mutate them into spore reproduction. This asexual reproduction means that every time a new specimen is evolved it will be the same as its parent except for mutations.';
        text_es[1] = '<span style="color: #ffff00; font-size: 13px;"><b>Refrigerante de Fukushima I</b></span> es un l&iacute;quido espeso que se puede aplicar a espec&iacute;menes para hacer que se reproduzcan asexualmente. Esto implica que cuando se evolucione un nuevo especimen, este ser&aacute; el mismo que su progenitor excepto por las mutaciones que pueda sufrir.';
        text_en[2] = '<span style="color: #ffff00; font-size: 13px;"><b>Fukushima II coolant</b></span> is a greenish liquid which can be applied to specimens to mutate them into sexual reproduction. This means that every time a new specimen is evolved its decission tree will be the combination of its two parents\' trees, and its basic characteristics will be a mixture from both.';
        text_es[2] = '<span style="color: #ffff00; font-size: 13px;"><b>Refrigerante de Fukushima II</b></span> es un l&iacute;quido verdoso que se puede aplicar a espec&iacute;menes para mutarlos y hacer que se reproduzcan sexualmente. Esto significa que cada vez que un nuevo especimen sea evolucionado, su &aacute;rbol de decisi&oacute;n ser&aacute; la combinaci&oacute;n de los dos &aacute;rboles de sus padres, y que sus caracter&iacute;sticas ser&aacute;n una mezcla de ambos.';
        text_en[3] = '<span style="color: #ffff00; font-size: 13px;"><b>Fukushima III coolant</b></span>  is a viscous liquid which can be applied to specimens to mutate them into trisexual reproduction. What this means is that whenever a new specimen is evolved its decission tree will be the combination of its three parents\' trees, and its basic characteristics will be a mixture from all them.';
        text_es[3] = '<span style="color: #ffff00; font-size: 13px;"><b>Refrigerante de Fukushima III</b></span> es un l&iacute;quido viscoso que se puede aplicar a espec&iacute;menes para mutarlos y hacer que se reproduzcan trisexualmente. Lo que esto quiere decir es que cada vez que un nuevo especimen sea evolucionado, su &aacute;rbol de decisi&oacute;n ser&aacute; la combinaci&oacute;n de los tres &aacute;rboles de sus padres, y que sus caracter&iacute;sticas ser&aacute;n una mezcla de todos ellos';
        text_en[4] = '<span style="color: #ffff00; font-size: 13px;"><b>Three Mile Island dust</b></span> is a powerful mutagen in powder form which allows you to modify the mutation ratio for specimen generation. Each item will let you change this value up to five percentual points.';
        text_es[4] = '<span style="color: #ffff00; font-size: 13px;"><b>Polvo de Three Mile Island</b></span> es un poderoso mut&aacute;geno que permite modificar el ratio de mutaci&oacute;n al generar espec&iacute;menes. Cada objeto te permitir&aacute; cambiar este valor hasta en cinco puntos percentuales.';
        text_en[5] = '<span style="color: #ffff00; font-size: 13px;"><b>Caesium 134 Oxide</b></span> is a mutagen which allows you to modify the mutation vulnerability in your specimens. When consumed, it turns mutation intensity to <b>soft</b>';
        text_es[5] = '<span style="color: #ffff00; font-size: 13px;"><b>&Oacute;xido de Cesio 134</b></span> es un poderoso mut&aacute;geno que permite modificar la vulnerabilidad de tus espec&iacute;menes a las mutaciones. Cuando se consume, convierte la intensidad de las mutaciones en <b>d&eacute;bil</b>.';
        text_en[6] = '<span style="color: #ffff00; font-size: 13px;"><b>Caesium 135 Oxide</b></span> is a mutagen which allows you to modify the mutation vulnerability in your specimens. When consumed, itturns mutation intensity to <b>medium</b>';
        text_es[6] = '<span style="color: #ffff00; font-size: 13px;"><b>&Oacute;xido de Cesio 135</b></span> es un poderoso mut&aacute;geno que permite modificar la vulnerabilidad de tus espec&iacute;menes a las mutaciones. Cuando se consume, convierte la intensidad de las mutaciones en <b>media</b>.';
        text_en[7] = '<span style="color: #ffff00; font-size: 13px;"><b>Caesium 137 Oxide</b></span> is a mutagen which allows you to modify the mutation vulnerability in your specimens. When consumed, it turns mutation intensity to <b>strong</b>';
        text_es[7] = '<span style="color: #ffff00; font-size: 13px;"><b>&Oacute;xido de Cesio 137</b></span> es un poderoso mut&aacute;geno que permite modificar la vulnerabilidad de tus espec&iacute;menes a las mutaciones. Cuando se consume, convierte la intensidad de las mutaciones en <b>fuerte</b>.';
        text_en[8] = '<span style="color: #ffff00; font-size: 13px;"><b>Polonium 210 cloud</b></span> is a set of particles which allow you to mix all demes in the generation after it is consumed.';
        text_es[8] = '<span style="color: #ffff00; font-size: 13px;"><b>Nube de Polonio 210</b></span> es un conjunto de part&iacute;culas que te permiten mezclar todos los demes en la generaci&oacute;n posterior a su consumo.';
        text_en[9] = '<span style="color: #ffff00; font-size: 13px;"><b>Chernobyl 5X extract</b></span> is a mixture providing a boost in the basic characteristics of specimens in the next generation evolution after it is consumed.';
        text_es[9] = '<span style="color: #ffff00; font-size: 13px;"><b>Extracto 5X de Chernobyl</b></span> es una mezcla que proporciona un incremento en las caracter&iacute;sticas b&aacute;sicas de espec&iacute;menes en la evoluci&oacute;n de la siguiente generaci&oacute;n tras su consumo.';
        text_en[10] = '<span style="color: #ffff00; font-size: 13px;"><b>Chernobyl 10X extract</b></span> is a mixture providing an increased boost in the basic characteristics of specimens in the next generation evolution after it is consumed';
        text_es[10] = '<span style="color: #ffff00; font-size: 13px;"><b>Extracto 10X de Chernobyl</b></span> es una mezcla que proporciona un incremento aumentado en las caracter&iacute;sticas b&aacute;sicas de espec&iacute;menes en la evoluci&oacute;n de la siguiente generaci&oacute;n tras su consumo.';
        text_en[11] = '<span style="color: #ffff00; font-size: 13px;"><b>Hydrogen Peroxide</b></span> is a corrosive substance that can be used to destroy a whole abyssal depths deme population and replace it with a brand new one.';
        text_es[11] = '<span style="color: #ffff00; font-size: 13px;"><b>Per&oacute;xido de Hidr&oacute;geno</b></span> es una sustancia corrosiva que puede usarse para destruir a toda la poblaci&oacute;n del deme de las profundidades para sustituirla por nuevos espec&iacute;menes';
        text_en[12] = '<span style="color: #ffff00; font-size: 13px;"><b>Hydrogen Sulfide</b></span> is a corrosive substance that can be used to destroy a whole forest deme population and replace it with a brand new one.';
        text_es[12] = '<span style="color: #ffff00; font-size: 13px;"><b>&Aacute;cido Sulfh&iacute;drico</b></span> es una sustancia corrosiva que puede usarse para destruir a toda la poblaci&oacute;n del deme del bosque para sustituirla por nuevos espec&iacute;menes';
        text_en[13] = '<span style="color: #ffff00; font-size: 13px;"><b>Sulfuric Acid</b></span> is a corrosive substance that can be used to destroy a whole volcano deme population and replace it with a brand new one.';
        text_es[13] = '<span style="color: #ffff00; font-size: 13px;"><b>&Aacute;cido Sulf&uacute;rico</b></span> es una sustancia corrosiva que puede usarse para destruir a toda la poblaci&oacute;n del deme del volc&aacute;n para sustituirla por nuevos espec&iacute;menes';
        text_en[14] = '<span style="color: #ffff00; font-size: 13px;"><b>Resveratrol Solute</b></span> is a life-giving substance in an ethanol solution that will rejuvenate all your specimens.';
        text_es[14] = '<span style="color: #ffff00; font-size: 13px;"><b>Soluto de Resveratrol</b></span> es una sustancia otorgadora de vida en una soluci&oacute;n de etanol que rejuvenecer&aacute; a todos tus espec&iacute;menes.';

        var imagen = new Array();
        imagen[1] = '<img src="img/shop_sex1.png">';
        imagen[2] = '<img src="img/shop_sex2.png">';
        imagen[3] = '<img src="img/shop_sex3.png">';
        imagen[4] = '<img src="img/shop_mutacion.png">';
        imagen[5] = '<img src="img/shop_caesium_134.png">';
        imagen[6] = '<img src="img/shop_caesium_135.png">';
        imagen[7] = '<img src="img/shop_caesium_137.png">';
        imagen[8] = '<img src="img/shop_mezcla.png">';
        imagen[9] = '<img src="img/shop_superman1.png">';
        imagen[10] = '<img src="img/shop_superman2.png">';
        imagen[11] = '<img src="img/shop_corrosive_1.png">';
        imagen[12] = '<img src="img/shop_corrosive_2.png">';
        imagen[13] = '<img src="img/shop_corrosive_3.png">';
        imagen[14] = '<img src="img/shop_ankh.png">';

        var o_imagen = new Array();
        o_imagen[1] = '<img src="img/shop_deme_abyss.png">';
        o_imagen[2] = '<img src="img/shop_deme_forest.png">';
        o_imagen[3] = '<img src="img/shop_deme_volcano.png">';
        o_imagen[4] = '<img src="img/shop_ampliar.png">';

        var o_text_es = new Array();
        var o_text_en = new Array();
        o_text_en[1] = '<span style="color: #ffff00; font-size: 13px;"><b>Abyssal depths deme slot</b></span> is a structural modification that will increase by one slot your abyssal depths deme space.';
        o_text_es[1] = '<span style="color: #ffff00; font-size: 13px;"><b>Hueco de deme de las profundidades</b></span> es una modificaci&oacute;n estructural que aumentar&aacute; el espacio de almacenamiento del deme de las profundidades en una unidad.';
        o_text_en[2] = '<span style="color: #ffff00; font-size: 13px;"><b>Forest deme slot</b></span> is a structural modification that will increase by one slot your forest deme space.';
        o_text_es[2] = '<span style="color: #ffff00; font-size: 13px;"><b>Hueco de deme del bosque</b></span> es una modificaci&oacute;n estructural que aumentar&aacute; el espacio de almacenamiento del deme del bosque en una unidad.';
        o_text_en[3] = '<span style="color: #ffff00; font-size: 13px;"><b>Volcano deme slot</b></span> is a structural modification that will increase by one slot your volcano deme space.';
        o_text_es[3] = '<span style="color: #ffff00; font-size: 13px;"><b>Hueco de deme del volc&aacute;n</b></span> es una modificaci&oacute;n estructural que aumentar&aacute; el espacio de almacenamiento del deme del volc&aacute;n en una unidad.';
        o_text_en[4] = '<span style="color: #ffff00; font-size: 13px;"><b>Increase cerebral cortex capacity.</b></span> When bought, this improvement expands cerebral cortex capacity from your specimens and builds the necessary changes in your infraestructure, housing one more complexity level in the specimens\' decission tree.<br/><br/> BEWARE! EVERY SPECIMEN WILL DIE AND BE REPLACED!';
        o_text_es[4] = '<span style="color: #ffff00; font-size: 13px;"><b>Ampliar capacidad cerebral y estructural</b></span> Al ser comprado, ampl&iacute;a la capacidad cerebral y de almacenaje de tus espec&iacute;menes, albergando un nivel m&aacute;s de complejidad en su &aacute;rbol de decisi&oacute;n. &iexcl;CUIDADO! TODOS TUS ESPEC&Iacute;MENES MORIR&Aacute;N Y SER&Aacute;N SUSTITUIDOS!';




	function PintarTiendaEs(elemento, coste)
        {
           document.getElementById("tienda_info").innerHTML = '<br/>'+imagen[elemento]+'<br/><br/>'+text_es[elemento]+'<br/><br/><a href="index.php?catid=<?php echo $catid; ?>&idcampana=<?php echo $idcampana;
                ?>&accion=comprar_objeto&idobjeto='+elemento+'">Haz click para comprar ( '+coste+'<img src="img/goldcoin.gif"> )</a>';
        }

	function PintarTiendaEn(elemento, coste)
        {
           document.getElementById("tienda_info").innerHTML = '<br/>'+imagen[elemento]+'<br/><br/>'+text_en[elemento]+'<br/><br/><a href="index.php?catid=<?php echo $catid; ?>&idcampana=<?php echo $idcampana;
		?>&accion=comprar_objeto&idobjeto='+elemento+'">Click to buy ( '+coste+'<img src="img/goldcoin.gif"> )</a>';
        }

	function PintarObraEs(elemento, coste)
        {
           document.getElementById("tienda_info").innerHTML = '<br/>'+o_imagen[elemento]+'<br/><br/>'+o_text_es[elemento]+'<br/><br/><a href="index.php?catid=<?php echo $catid; ?>&idcampana=<?php echo $idcampana;
                ?>&accion=comprar_obra&idobra='+elemento+'">Haz click para comprar ( '+coste+'<img src="img/goldcoin.gif"> )</a>';
        }

	function PintarObraEn(elemento, coste)
        {
           document.getElementById("tienda_info").innerHTML = '<br/>'+o_imagen[elemento]+'<br/><br/>'+o_text_en[elemento]+'<br/><br/><a href="index.php?catid=<?php echo $catid; ?>&idcampana=<?php echo $idcampana;
		?>&accion=comprar_obra&idobra='+elemento+'">Click to buy ( '+coste+'<img src="img/goldcoin.gif"> )</a>';
        }

	function PintarTiendaEsNO(elemento, coste)
        {
           document.getElementById("tienda_info").innerHTML = '<br/>'+imagen[elemento]+'<br/><br/>'+text_es[elemento]+'<br/><br/><span class="errorsutil">No tienes suficiente dinero para pagar esto.</span> ( '+coste+'<img src="img/goldcoin.gif"> )';
        }

	function PintarTiendaEnNO(elemento, coste)
        {
           document.getElementById("tienda_info").innerHTML = '<br/>'+imagen[elemento]+'<br/><br/>'+text_en[elemento]+'<br/><br/><span class="errorsutil">You lack enough money for this</span> ( '+coste+'<img src="img/goldcoin.gif"> )';
        }

	function PintarObraEsNO(elemento, coste)
        {
           document.getElementById("tienda_info").innerHTML = '<br/>'+o_imagen[elemento]+'<br/><br/>'+o_text_es[elemento]+'<br/><br/><span class="errorsutil">No tienes dinero suficiente para pagar esto.</span> ( '+coste+'<img src="img/goldcoin.gif"> )';
        }

	function PintarObraEnNO(elemento, coste)
        {
           document.getElementById("tienda_info").innerHTML = '<br/>'+o_imagen[elemento]+'<br/><br/>'+o_text_en[elemento]+'<br/><br/><span class="errorsutil">You lack enough money for this</span> ( '+coste+'<img src="img/goldcoin.gif"> )</a>';
        }

	function PintarObraEsMAX(elemento, coste)
        {
           document.getElementById("tienda_info").innerHTML = '<br/>'+o_imagen[4]+'<br/><br/>'+o_text_es[4]+'<br/><br/><span class="errorsutil">Has alcanzado el m&aacute;ximo de niveles de expansi&oacute;n cerebral.</span>';
        }

	function PintarObraEnMAX(elemento, coste)
        {
           document.getElementById("tienda_info").innerHTML = '<br/>'+o_imagen[4]+'<br/><br/>'+o_text_en[4]+'<br/><br/><span class="errorsutil">You have reached the max amount of brain expansion levels.</span>';
        }

        function mover(origen, destino)
        {
           $(document.getElementById(origen)).slideUp();
           $(document.getElementById(destino)).slideDown();
        }

     </script>
   <?php
//           $(document.getElementById(origen)).hide('fast');
//           $(document.getElementById(destino)).show('fast');
//           document.getElementById(origen).style.display = 'none';
//           document.getElementById(destino).style.display = 'block';


    // PINTAR LA ZONA DE NAVEGACION PARA TODAS LAS PAGINAS DE LA TIENDA
    function PintarNavegacion($origen, $izquierda, $derecha, $textoizquierda, $textoderecha)
    {
//      echo ("<td width=\"100%\">");
      echo ("<td>");
      if ($izquierda != null)
      {
        echo ("<a href=\"javascript:mover('$origen','$izquierda');\">");
        echo ("<< ".$textoizquierda);
        echo ("</a>");
      }
      echo ("<br/>");
      echo ("<br/>");
      echo ("</td>");
      echo ("<td>");
      echo ("</td>");
      echo ("<td style=\"text-align: right;\">");
      if ($derecha != null)
      {
        echo ("<a href=\"javascript:mover('$origen','$derecha');\">");
        echo ($textoderecha." >>");
        echo ("</a>");
      }
      echo ("</td>");
      echo ("</tr>");
      echo ("<tr height=\"85px\">");
    }



    // ********************************
    //       TIENDA DE OBJETOS
    // ********************************

    echo ("<br/>");
    echo ("<span style=\"color: #ddab00;
		font-size: 13px;
		font-weight: bold;
		\">");
    if ($lang == 'en')
    {
      echo ("Ye Underground Scientist Ole' Shoppe ");
    } else {
      echo ("Tienda Underground de la Ciencia ");
    }
    echo ("</span>");
    echo ("<br/>");
    echo ("<br/>");


    // En la nueva tienda, los objetos ahora forman parte de una tabla un tanto distinta.
    if ($lang == 'en') { $funciont = 'PintarTiendaEn'; } else { $funciont = 'PintarTiendaEs'; }
    if ($lang == 'en') { $funciontNO = 'PintarTiendaEnNO'; } else { $funciontNO = 'PintarTiendaEsNO'; }

    echo ("<table style=\"background-color: #000000;
			\"
			>");

    echo ("<tr height=\"500px\" style=\"
		\">");   // Aqui se determina la altura de la cosa
    echo ("<td width=\"400px\" style=\" background-color: #111111;
		                  padding-left: 8px;
		                  padding-right: 8px;
		                  padding-top: 18px;
		                  padding-bottom: 18px;
				\" >");

    // Dentro del td de productos, ponemos como display el block, para que muestre este que es el primero
    echo ("<div id=\"productos1\"
		style=\"display: block;
		\"
		>");
    // Aqui es donde van listados los productos
     echo ("<table style=\"
			\"
			cellpadding=\"2\"
			cellspacing=\"2\"
			>");
//     echo ("<tr height=\"85px\">");

     echo ("<tr height=\"20px\">");

     // Navegacion
     if ($lang == 'en')
     {
       PintarNavegacion('productos1', '', 'productos2', '', 'Structures');
     } else {
       PintarNavegacion('productos1', '', 'productos2', '', 'Estructuras');
     }


     // Refrigerante de Fukushima I
      echo ("<td width=\"120px\">");
      if ($jugador_campana->dinero >= 4)
      {
        echo ('<div id="tienda1"
		onclick="javascript:'.$funciont.'(1, 4)"
 		onmouseover="this.style.cursor=\'pointer\'"
		>');
        echo ("<center>");
        echo ("<img src=\"img/shop_sex1.png\">");
        echo ("<br/>");
        echo ("<span style='color: #ffff00; font-size: 13px;'><b>");
      } else {
        echo ('<div id="tienda1"
		onclick="javascript:'.$funciontNO.'(1, 4)"
 		onmouseover="this.style.cursor=\'pointer\'"
		>');
        echo ("<center>");
        echo ("<img src=\"img/shop_sex1.png\" style=\"opacity: 0.3; filter:alpha(opacity=30); \">");
        echo ("<br/>");
        echo ("<span style='color: #555500; font-size: 13px;'><b>");
      }
      if ($lang == 'en')
      {
        echo ("Fukushima I Coolant");
      } else {
        echo ("Refrigerante de<br/>Fukushima I");
      }
      echo ("</span></b>");
      echo ("</center>");
      echo ("</div>");
     echo ("</td>");

     // Refrigerante de Fukushima II
     echo ("<td width=\"120px\">");
     if ($jugador_campana->dinero >= 4)
     {
       echo ('<div id="tienda2"
		onclick="javascript:'.$funciont.'(2, 4)"
 		onmouseover="this.style.cursor=\'pointer\'"
		>');
       echo ("<center>");
       echo ("<img src=\"img/shop_sex2.png\">");
       echo ("<br/>");
       echo ("<span style='color: #ffff00; font-size: 13px;'><b>");
     } else {
       echo ('<div id="tienda2"
		onclick="javascript:'.$funciontNO.'(2, 4)"
 		onmouseover="this.style.cursor=\'pointer\'"
		>');
       echo ("<center>");
       echo ("<img src=\"img/shop_sex2.png\" style=\"opacity: 0.3; filter:alpha(opacity=30); \">");
       echo ("<br/>");
       echo ("<span style='color: #555500; font-size: 13px;'><b>");
     }
      if ($lang == 'en')
      {
        echo ("Fukushima II Coolant");
      } else {
        echo ("Refrigerante de<br/>Fukushima II");
      }
      echo ("</span></b>");
      echo ("</center>");
      echo ("</div>");
     echo ("</td>");

     // Refrigerante de Fukushima III
     echo ("<td width=\"120px\">");
     if ($jugador_campana->dinero >= 4)
     {
       echo ('<div id="tienda3"
		onclick="javascript:'.$funciont.'(3, 4)"
 		onmouseover="this.style.cursor=\'pointer\'"
		>');
       echo ("<center>");
       echo ("<img src=\"img/shop_sex3.png\">");
       echo ("<br/>");
       echo ("<span style='color: #ffff00; font-size: 13px;'><b>");
     } else {
       echo ('<div id="tienda3"
		onclick="javascript:'.$funciontNO.'(3, 4)"
 		onmouseover="this.style.cursor=\'pointer\'"
		>');
       echo ("<center>");
       echo ("<img src=\"img/shop_sex3.png\" style=\"opacity: 0.3; filter:alpha(opacity=30); \">");
       echo ("<br/>");
       echo ("<span style='color: #555500; font-size: 13px;'><b>");
     }
      if ($lang == 'en')
      {
        echo ("Fukushima III Coolant");
      } else {
        echo ("Refrigerante de<br/>Fukushima III");
      }
      echo ("</span></b>");
      echo ("</center>");
      echo ("</div>");
     echo ("</td>");

     echo ("</tr>");
     echo ("<tr height=\"85px\">");

     // Three Mile Island Dust
     echo ("<td width=\"120px\">");
     if ($jugador_campana->dinero >= 1)
     {
       echo ('<div id="tienda4"
		onclick="javascript:'.$funciont.'(4,1)"
 		onmouseover="this.style.cursor=\'pointer\'"
		>');
       echo ("<center>");
       echo ("<img src=\"img/shop_mutacion.png\">");
       echo ("<br/>");
       echo ("<span style='color: #ffff00; font-size: 13px;'><b>");
     } else {
       echo ('<div id="tienda4"
		onclick="javascript:'.$funciontNO.'(4,1)"
 		onmouseover="this.style.cursor=\'pointer\'"
		>');
       echo ("<center>");
       echo ("<img src=\"img/shop_mutacion.png\" style=\"opacity: 0.3; filter:alpha(opacity=30); \">");
       echo ("<br/>");
       echo ("<span style='color: #555500; font-size: 13px;'><b>");
     }
      if ($lang == 'en')
      {
        echo ("Three Mile<br/>Island dust");
      } else {
        echo ("Polvo de Three<br/>Mile Island");
      }
      echo ("</span></b>");
      echo ("</center>");
      echo ("</div>");
     echo ("</td>");


     // Caesium 134 Oxide
     echo ("<td width=\"120px\">");
     if ($jugador_campana->dinero >= 3)
     {
       echo ('<div id="tienda5"
		onclick="javascript:'.$funciont.'(5,3)"
 		onmouseover="this.style.cursor=\'pointer\'"
		>');
       echo ("<center>");
       echo ("<img src=\"img/shop_caesium_134.png\">");
       echo ("<br/>");
       echo ("<span style='color: #ffff00; font-size: 13px;'><b>");
     } else {
       echo ('<div id="tienda5"
		onclick="javascript:'.$funciontNO.'(5,3)"
 		onmouseover="this.style.cursor=\'pointer\'"
		>');
       echo ("<center>");
       echo ("<img src=\"img/shop_caesium_134.png\" style=\"opacity: 0.3; filter:alpha(opacity=30); \">");
       echo ("<br/>");
       echo ("<span style='color: #555500; font-size: 13px;'><b>");
     }
      if ($lang == 'en')
      {
        echo ("Caesium 134<br/>Oxide");
      } else {
        echo ("&Oacute;xido de<br/>Cesio 134");
      }
      echo ("</span></b>");
      echo ("</center>");
      echo ("</div>");
     echo ("</td>");


     // Caesium 135 Oxide
     echo ("<td width=\"120px\">");
     if ($jugador_campana->dinero >= 3)
     {
       echo ('<div id="tienda6"
		onclick="javascript:'.$funciont.'(6,3)"
 		onmouseover="this.style.cursor=\'pointer\'"
		>');
       echo ("<center>");
       echo ("<img src=\"img/shop_caesium_135.png\">");
       echo ("<br/>");
       echo ("<span style='color: #ffff00; font-size: 13px;'><b>");
     } else {
       echo ('<div id="tienda6"
		onclick="javascript:'.$funciontNO.'(6,3)"
 		onmouseover="this.style.cursor=\'pointer\'"
		>');
       echo ("<center>");
       echo ("<img src=\"img/shop_caesium_135.png\" style=\"opacity: 0.3; filter:alpha(opacity=30); \">");
       echo ("<br/>");
       echo ("<span style='color: #555500; font-size: 13px;'><b>");
     }
      if ($lang == 'en')
      {
        echo ("Caesium 135<br/>Oxide");
      } else {
        echo ("&Oacute;xido de<br/>Cesio 135");
      }
      echo ("</span></b>");
      echo ("</center>");
      echo ("</div>");
     echo ("</td>");

     echo ("</tr>");
     echo ("<tr height=\"85px\">");

     // Caesium 137 Oxide
     echo ("<td width=\"120px\">");
     if ($jugador_campana->dinero >= 3)
     {
       echo ('<div id="tienda7"
		onclick="javascript:'.$funciont.'(7,3)"
 		onmouseover="this.style.cursor=\'pointer\'"
		>');
       echo ("<center>");
       echo ("<img src=\"img/shop_caesium_137.png\">");
       echo ("<br/>");
       echo ("<span style='color: #ffff00; font-size: 13px;'><b>");
     } else {
       echo ('<div id="tienda7"
		onclick="javascript:'.$funciontNO.'(7,3)"
 		onmouseover="this.style.cursor=\'pointer\'"
		>');
       echo ("<center>");
       echo ("<img src=\"img/shop_caesium_137.png\"  style=\"opacity: 0.3; filter:alpha(opacity=30); \">");
       echo ("<br/>");
       echo ("<span style='color: #555500; font-size: 13px;'><b>");
     }
      if ($lang == 'en')
      {
        echo ("Caesium 137<br/>Oxide");
      } else {
        echo ("&Oacute;xido de<br/>Cesio 137");
      }
      echo ("</span></b>");
      echo ("</center>");
      echo ("</div>");
     echo ("</td>");


     // Polonium 210 cloud
     echo ("<td width=\"120px\">");
     if ($jugador_campana->dinero >= 3)
     {
       echo ('<div id="tienda8"
		onclick="javascript:'.$funciont.'(8,3)"
 		onmouseover="this.style.cursor=\'pointer\'"
		>');
       echo ("<center>");
       echo ("<img src=\"img/shop_mezcla.png\">");
       echo ("<br/>");
       echo ("<span style='color: #ffff00; font-size: 13px;'><b>");
     } else {
       echo ('<div id="tienda8"
		onclick="javascript:'.$funciontNO.'(8,3)"
 		onmouseover="this.style.cursor=\'pointer\'"
		>');
       echo ("<center>");
       echo ("<img src=\"img/shop_mezcla.png\" style=\"opacity: 0.3; filter:alpha(opacity=30); \">");
       echo ("<br/>");
       echo ("<span style='color: #555500; font-size: 13px;'><b>");
     }
      if ($lang == 'en')
      {
        echo ("Polonium 210<br/>cloud");
      } else {
        echo ("Nube de<br/>Polonio 210");
      }
      echo ("</span></b>");
      echo ("</center>");
      echo ("</div>");
     echo ("</td>");


     // Extracto 5X de Chernobyl
     echo ("<td width=\"120px\">");
     if ($jugador_campana->dinero >= 10)
     {
       echo ('<div id="tienda9"
		onclick="javascript:'.$funciont.'(9,10)"
 		onmouseover="this.style.cursor=\'pointer\'"
		>');
       echo ("<center>");
       echo ("<img src=\"img/shop_superman1.png\">");
       echo ("<br/>");
       echo ("<span style='color: #ffff00; font-size: 13px;'><b>");
     } else {
       echo ('<div id="tienda9"
		onclick="javascript:'.$funciontNO.'(9,10)"
 		onmouseover="this.style.cursor=\'pointer\'"
		>');
       echo ("<center>");
       echo ("<img src=\"img/shop_superman1.png\" style=\"opacity: 0.3; filter:alpha(opacity=30); \">");
       echo ("<br/>");
       echo ("<span style='color: #555500; font-size: 13px;'><b>");
     }
      if ($lang == 'en')
      {
        echo ("Chernobyl<br/>5X extract");
      } else {
        echo ("Extracto 5X<br/>de Chernobyl");
      }
      echo ("</span></b>");
      echo ("</center>");
      echo ("</div>");
     echo ("</td>");

     echo ("</tr>");
     echo ("<tr height=\"85px\">");


     // Extracto 10X de Chernobyl
     echo ("<td width=\"120px\">");
     if ($jugador_campana->dinero >= 50)
     {
       echo ('<div id="tienda10"
		onclick="javascript:'.$funciont.'(10,50)"
 		onmouseover="this.style.cursor=\'pointer\'"
		>');
       echo ("<center>");
       echo ("<img src=\"img/shop_superman2.png\">");
       echo ("<br/>");
       echo ("<span style='color: #ffff00; font-size: 13px;'><b>");
     } else {
       echo ('<div id="tienda10"
		onclick="javascript:'.$funciontNO.'(10,50)"
 		onmouseover="this.style.cursor=\'pointer\'"
		>');
       echo ("<center>");
       echo ("<img src=\"img/shop_superman2.png\" style=\"opacity: 0.3; filter:alpha(opacity=30); \">");
       echo ("<br/>");
       echo ("<span style='color: #555500; font-size: 13px;'><b>");
     }
      if ($lang == 'en')
      {
        echo ("Chernobyl<br/>10X extract");
      } else {
        echo ("Extracto 10X<br/>de Chernobyl");
      }
      echo ("</span></b>");
      echo ("</center>");
      echo ("</div>");
     echo ("</td>");


     // Corrosivo 1
     echo ("<td width=\"120px\">");
     if ($jugador_campana->dinero >= 2)
     {
       echo ('<div id="tienda11"
		onclick="javascript:'.$funciont.'(11,2)"
 		onmouseover="this.style.cursor=\'pointer\'"
		>');
       echo ("<center>");
       echo ("<img src=\"img/shop_corrosive_1.png\">");
       echo ("<br/>");
       echo ("<span style='color: #ffff00; font-size: 13px;'><b>");
     } else {
       echo ('<div id="tienda11"
		onclick="javascript:'.$funciontNO.'(11,2)"
 		onmouseover="this.style.cursor=\'pointer\'"
		>');
       echo ("<center>");
       echo ("<img src=\"img/shop_corrosive_1.png\" style=\"opacity: 0.3; filter:alpha(opacity=30); \">");
       echo ("<br/>");
       echo ("<span style='color: #555500; font-size: 13px;'><b>");
     }
      if ($lang == 'en')
      {
        echo ("Hydrogen<br/>Peroxide");
      } else {
        echo ("Per&oacute;xido<br/>de Hidr&oacute;geno");
      }
      echo ("</span></b>");
      echo ("</center>");
      echo ("</div>");
     echo ("</td>");


     // Corrosivo 2
     echo ("<td width=\"120px\">");
     if ($jugador_campana->dinero >= 2)
     {
       echo ('<div id="tienda12"
		onclick="javascript:'.$funciont.'(12,2)"
 		onmouseover="this.style.cursor=\'pointer\'"
		>');
       echo ("<center>");
       echo ("<img src=\"img/shop_corrosive_2.png\">");
       echo ("<br/>");
       echo ("<span style='color: #ffff00; font-size: 13px;'><b>");
     } else {
       echo ('<div id="tienda12"
		onclick="javascript:'.$funciontNO.'(12,2)"
 		onmouseover="this.style.cursor=\'pointer\'"
		>');
       echo ("<center>");
       echo ("<img src=\"img/shop_corrosive_2.png\" style=\"opacity: 0.3; filter:alpha(opacity=30); \">");
       echo ("<br/>");
       echo ("<span style='color: #555500; font-size: 13px;'><b>");
     }

      if ($lang == 'en')
      {
        echo ("Hydrogen<br/>sulfide");
      } else {
        echo ("&Aacute;cido<br/>sulfh&iacute;drico");
      }
      echo ("</span></b>");
      echo ("</center>");
      echo ("</div>");
     echo ("</td>");


     echo ("</tr>");
     echo ("<tr>");

     // Corrosivo 3
     echo ("<td width=\"120px\">");
     if ($jugador_campana->dinero >= 2)
     {
       echo ('<div id="tienda13"
		onclick="javascript:'.$funciont.'(13,2)"
 		onmouseover="this.style.cursor=\'pointer\'"
		>');
       echo ("<center>");
       echo ("<img src=\"img/shop_corrosive_3.png\">");
       echo ("<br/>");
       echo ("<span style='color: #ffff00; font-size: 13px;'><b>");
     } else {
       echo ('<div id="tienda13"
		onclick="javascript:'.$funciontNO.'(13,2)"
 		onmouseover="this.style.cursor=\'pointer\'"
		>');
       echo ("<center>");
       echo ("<img src=\"img/shop_corrosive_3.png\" style=\"opacity: 0.3; filter:alpha(opacity=30); \">");
       echo ("<br/>");
       echo ("<span style='color: #555500; font-size: 13px;'><b>");
     }
      if ($lang == 'en')
      {
        echo ("Sulfuric<br/>acid");
      } else {
        echo ("&Aacute;cido<br/>sulf&uacute;rico");
      }
      echo ("</span></b>");
      echo ("</center>");
      echo ("</div>");
     echo ("</td>");


     // Dador de vida
     echo ("<td width=\"120px\">");
     if ($jugador_campana->dinero >= 10)
     {
       echo ('<div id="tienda14"
		onclick="javascript:'.$funciont.'(14,10)"
 		onmouseover="this.style.cursor=\'pointer\'"
		>');
       echo ("<center>");
       echo ("<img src=\"img/shop_ankh.png\">");
       echo ("<br/>");
       echo ("<span style='color: #ffff00; font-size: 13px;'><b>");
     } else {
       echo ('<div id="tienda14"
		onclick="javascript:'.$funciontNO.'(14,10)"
 		onmouseover="this.style.cursor=\'pointer\'"
		>');
       echo ("<center>");
       echo ("<img src=\"img/shop_ankh.png\" style=\"opacity: 0.3; filter:alpha(opacity=30); \">");
       echo ("<br/>");
       echo ("<span style='color: #555500; font-size: 13px;'><b>");
     }
      if ($lang == 'en')
      {
        echo ("Resveratrol<br/>solute");
      } else {
        echo ("Soluto de<br/>Resveratrol");
      }
      echo ("</span></b>");
      echo ("</center>");
      echo ("</div>");
     echo ("</td>");


     echo ("<td>");
     echo ("</td>");



    // Parte de la derecha, donde aparecera el menu y el tema de comprar
     echo ("</tr>");
     echo ("</table>");


    echo ("</div>");  // Cerramos el div de productos (la primera pagina)


    // --------------------------------
    //   SEGUNDA PAGINA DE PRODUCTOS
    // --------------------------------

    // Dentro del td de productos, ponemos como display el block, para que muestre este que es el primero
    echo ("<div id=\"productos2\"
		style=\"display: none;\"
		>");
    // Aqui es donde van listados los productos
     echo ("<table style=\"
			\"
			cellpadding=\"2\"
			cellspacing=\"2\"
			>");
     echo ("<tr height=\"20px\">");

     // Navegacion
     if ($lang == 'en')
     {
       PintarNavegacion('productos2', 'productos1', '', 'Standard items', '');
     } else {
       PintarNavegacion('productos2', 'productos1', '', 'Objetos est&aacute;ndar', '');
    }



    // SACANDO COSAS QUE TIENEN QUE VER YA CON SLOTS
    $total_slots = $jugador_campana->num_slots_deme_profundidades +
			$jugador_campana->num_slots_deme_bosque +
			$jugador_campana->num_slots_deme_volcan;

    // Redondeo hacia arriba de 2 elevado a ($total_slots - 20),... pero / 4 antes de elevar,
    // para que realmente permita unos cuantos slots
    //$coste = ceil(pow(2, (  ($total_slots - 20) / 4)) * 2);
    $coste = ceil(pow(2, (  ($total_slots - 20) / 5)) * 2);



    // En la nueva tienda, los objetos ahora forman parte de una tabla un tanto distinta.
    if ($lang == 'en') { $funciono = 'PintarObraEn'; } else { $funciono = 'PintarObraEs'; }
    if ($lang == 'en') { $funcionoNO = 'PintarObraEnNO'; } else { $funcionoNO = 'PintarObraEsNO'; }
    if ($lang == 'en') { $funcionoMAX = 'PintarObraEnMAX'; } else { $funcionoMAX = 'PintarObraEsMAX'; }

     // Deme de las profundidades
     echo ("<td width=\"120px\">");
     if ($jugador_campana->dinero >= $coste)
     {
       echo ('<div id="obra1"
		onclick="javascript:'.$funciono.'(1, '.$coste.')"
 		onmouseover="this.style.cursor=\'pointer\'"
		>');
       echo ("<center>");
       echo ("<img src=\"img/shop_deme_abyss.png\">");
       echo ("<br/>");
       echo ("<span style='color: #ffff00; font-size: 13px;'><b>");
     } else {
       echo ('<div id="obra1"
		onclick="javascript:'.$funcionoNO.'(1, '.$coste.')"
 		onmouseover="this.style.cursor=\'pointer\'"
		>');
       echo ("<center>");
       echo ("<img src=\"img/shop_deme_abyss.png\" style=\"opacity: 0.3; filter:alpha(opacity=30); \">");
       echo ("<br/>");
       echo ("<span style='color: #555500; font-size: 13px;'><b>");
     }
      if ($lang == 'en')
      {
        echo ("Abyssal deme<br/>slot");
      } else {
        echo ("Hueco del deme<br/>de profundidades");
      }
      echo ("</span></b>");
      echo ("</center>");
      echo ("</div>");
     echo ("</td>");




     // Deme del bosque
     echo ("<td width=\"120px\">");
     if ($jugador_campana->dinero >= $coste)
     {
       echo ('<div id="obra2"
		onclick="javascript:'.$funciono.'(2,'.$coste.')"
 		onmouseover="this.style.cursor=\'pointer\'"
		>');
       echo ("<center>");
       echo ("<img src=\"img/shop_deme_forest.png\">");
       echo ("<br/>");
       echo ("<span style='color: #ffff00; font-size: 13px;'><b>");
     } else {
       echo ('<div id="obra2"
		onclick="javascript:'.$funcionoNO.'(2,'.$coste.')"
 		onmouseover="this.style.cursor=\'pointer\'"
		>');
       echo ("<center>");
       echo ("<img src=\"img/shop_deme_forest.png\" style=\"opacity: 0.3; filter:alpha(opacity=30); \">");
       echo ("<br/>");
       echo ("<span style='color: #555500; font-size: 13px;'><b>");
     }
      if ($lang == 'en')
      {
        echo ("Forest deme<br/>slot");
      } else {
        echo ("Hueco del deme<br/>del bosque");
      }
      echo ("</span></b>");
      echo ("</center>");
      echo ("</div>");
     echo ("</td>");



     // Deme del volcan
     echo ("<td width=\"120px\">");
     if ($jugador_campana->dinero >= $coste)
     {
       echo ('<div id="obra3"
		onclick="javascript:'.$funciono.'(3,'.$coste.')"
 		onmouseover="this.style.cursor=\'pointer\'"
		>');
       echo ("<center>");
       echo ("<img src=\"img/shop_deme_volcano.png\">");
       echo ("<br/>");
       echo ("<span style='color: #ffff00; font-size: 13px;'><b>");
     } else {
       echo ('<div id="obra3"
		onclick="javascript:'.$funcionoNO.'(3,'.$coste.')"
 		onmouseover="this.style.cursor=\'pointer\'"
		>');
       echo ("<center>");
       echo ("<img src=\"img/shop_deme_volcano.png\" style=\"opacity: 0.3; filter:alpha(opacity=30); \">");
       echo ("<br/>");
       echo ("<span style='color: #555500; font-size: 13px;'><b>");
     }

      if ($lang == 'en')
      {
        echo ("Volcano deme<br/>slot");
      } else {
        echo ("Hueco del deme<br/>del volc&aacute;n");
      }
      echo ("</span></b>");
      echo ("</center>");
      echo ("</div>");
     echo ("</td>");

    echo ("</tr>");
    echo ("<tr>");


     // Expansion de cerebro
    // 6 es en numero maximo de niveles
//    if ($jugador_campana->niveles_arbol < 6)
//    {
//echo ($jugador_campana->niveles_arbol)."#";
      if ($jugador_campana->niveles_arbol == 3) { $coste = 20; }
      if ($jugador_campana->niveles_arbol == 4) { $coste = 150; }
      if ($jugador_campana->niveles_arbol == 5) { $coste = 1000; }
      echo ("<td width=\"120px\">");
      if ($jugador_campana->niveles_arbol == 6)
      {
//echo ("Y");
        echo ('<div id="obra4"
		onclick="javascript:'.$funcionoMAX.'()"
 		onmouseover="this.style.cursor=\'pointer\'"
		>');
        echo ("<center>");
        echo ("<img src=\"img/shop_ampliar.png\" style=\"opacity: 0.3; filter:alpha(opacity=30); \">");
        echo ("<br/>");
        echo ("<span style='color: #555500; font-size: 13px;'><b>");
      } else {
        if ($jugador_campana->dinero >= $coste)
        {
          echo ('<div id="obra4"
		onclick="javascript:'.$funciono.'(4,'.$coste.')"
 		onmouseover="this.style.cursor=\'pointer\'"
		>');
          echo ("<center>");
          echo ("<img src=\"img/shop_ampliar.png\">");
          echo ("<br/>");
          echo ("<span style='color: #ffff00; font-size: 13px;'><b>");
        } else {
//echo ("X");
          echo ('<div id="obra4"
		onclick="javascript:'.$funcionoNO.'(4,'.$coste.')"
 		onmouseover="this.style.cursor=\'pointer\'"
		>');
          echo ("<center>");
          echo ("<img src=\"img/shop_ampliar.png\" style=\"opacity: 0.3; filter:alpha(opacity=30); \">");
          echo ("<br/>");
          echo ("<span style='color: #555500; font-size: 13px;'><b>");
        }
      }
      if ($lang == 'en')
      {
        echo ("Brain<br/>expansion");
      } else {
        echo ("Expansi&oacute;n<br/>cerebral");
      }
      echo ("</span></b>");
      echo ("</center>");
      echo ("</div>");
      echo ("</td>");
//    } else {
      // Aqui hay que poner algo para cuando haya llegado al maximo
//    }




     echo ("<td>");
     echo ("</td>");
     echo ("<td>");
     echo ("</td>");
     echo ("</tr>");
     echo ("</table>");

    echo ("</div>");  // Cerramos el div de productos (la SEGUNDA pagina)






    echo ("</td>");
    echo ("<td width=\"10px\">");
    echo ("</td>");


    echo ("<td width=\"250px\" style=\" background-color: #111111;
		                  padding-left: 8px;
		                  padding-right: 8px;
		                  padding-top: 18px;
		                  padding-bottom: 8px;
				\"
				>");
    echo ("<div id=\"tienda_info\">");
    echo ("<br/>");
    echo ("<br/>");
    echo ("<br/>");
    echo ("<br/>");
    echo ("<br/>");
    echo ("<br/>");
    echo ("<br/>");
    echo ("<br/>");
    echo ("<br/>");
    echo ("<br/>");
    echo ("<br/>");
    echo ("<br/>");
    echo ("<br/>");
    echo ("<br/>");
    echo ("<p style=\"
		font-size: 18px;
		text-align: center;
		color: #fe9999;
		\"
		>");
    if ($lang == 'en')
    {
      echo ("Click on any product<br/>to see its characteristics");
    } else {
      echo ("Haz click en cualquier producto<br/> para ver sus caracter&iacute;sticas");
    }
    echo ("</p>");
    echo ("</div>");
    echo ("</td>");

    echo ("</tr>");
    echo ("</table>");
    echo ("<br/>");





    // TIENDA ANTIGUA
    // TIENDA ANTIGUA
    // TIENDA ANTIGUA
    // TIENDA ANTIGUA
    // TIENDA ANTIGUA
    // TIENDA ANTIGUA
    // TIENDA ANTIGUA

    // ··················································
    //    ENTRADA EN LA TIENDA DE FUKUSHIMA I COOLANT
    // ··················································
/*
    echo ("<table style=\"
			background-color: #111111;
		                  padding-left: 8px;
		                  padding-right: 8px;
		                  padding-top: 8px;
		                  padding-bottom: 0px;
		\">");
    echo ("<tr>");
    echo ("<td width=\"50px\" style=\"vertical-align: middle;\">");
    if ($jugador_campana->dinero >= 4)
    {
      echo ("<a href=\"index.php?catid=".$catid."&idcampana=".$idcampana."&accion=comprar_objeto&idobjeto=1\" class=\"Ntooltip_shop\">");
      echo ("<img src=\"img/shop_sex1.png\">");
      Escribir_Tooltip_Comprar($lang, 4);
      echo ("</a>");
    } else {
      echo ("<a href=\"#\" class=\"Ntooltip_shop\">");
      echo ("<img src=\"img/shop_sex1.png\" style=\"opacity: 0.3; filter:alpha(opacity=30); \">");
      Escribir_Tooltip_Comprar_Gray($lang, 4);
      echo ("</a>");
    }
    echo ("</td>");
    echo ("<td>");
    if ($lang == 'en')
    {
      echo ("<table width=\"100%\" style=\"
		                  padding-left: 0px;
		                  padding-right: 8px;
		                  padding-top: 4px;
		                  padding-bottom: 4px;
		                  background-color: #111111;
                		  color: #ffffff;
			\"
			>");
      echo ("<tr>");
      echo ("<td style=\"text-align: middle; font-size: 12px;
		vertical-align: top;
		\">");
      echo ("<span style='color: #ffff00; font-size: 13px;'><b>Fukushima I coolant</b></span>");
      echo (" is a thick liquid which can be applied to specimens to mutate them into spore reproduction. This asexual reproduction");
      echo (" means that every time a new specimen is evolved it will be the same as its parent except for mutations.");
//                        echo ("<br/><br/><p style=\"color:#ffff55; font-weight: bold;\">Click to consume</p>");
    } else {
      echo ("<table width=\"100%\" style=\"
		                  padding-left: 0px;
		                  padding-right: 8px;
		                  padding-top: 8px;
		                  padding-bottom: 0px;
		                  background-color: #111111;
                		  color: #ffffff;
            \"
		>");
      echo ("<tr>");
      echo ("<td style=\"text-align: middle; font-size: 12px;
		vertical-align: top;
		\">");
      echo ("<span style='color: #ffff00; font-size: 13px;'><b>Refrigerante de Fukushima I</b></span> es un l&iacute;quido espeso que ");
      echo ("se puede aplicar a espec&iacute;menes para hacer que se reproduzcan asexualmente. Esto ");
      echo ("implica que cuando se evolucione un nuevo especimen, este ser&aacute; el mismo que su progenitor excepto ");
      echo ("por las mutaciones que pueda sufrir.");
//                echo ("<br/><br/><p style=\"color:#ffff55; font-weight: bold;\">Haz click para consumir</p>");
                      }
//    echo ("<br/>");
//    echo ("<br/>");
//    echo ("<span class=\"goldcoin\"> 4</span>");
//    echo ("<img src=\"img/goldcoin.gif\" style=\"vertical-align:middle;\">");
    echo ("</td></tr></table>");


    echo ("</td>");
    echo ("</tr>");

    echo ("</table>");




    echo ("<br/>");

    // ··················································
    //    ENTRADA EN LA TIENDA DE FUKUSHIMA II COOLANT
    // ··················································

    echo ("<table style=\"
			background-color: #111111;
		                  padding-left: 8px;
		                  padding-right: 8px;
		                  padding-top: 2px;
		                  padding-bottom: 2px;
		\">");
    echo ("<tr>");
    echo ("<td width=\"50px\" style=\"vertical-align: middle;\">");
    if ($jugador_campana->dinero >= 4)
    {
      echo ("<a href=\"index.php?catid=".$catid."&idcampana=".$idcampana."&accion=comprar_objeto&idobjeto=2\" class=\"Ntooltip_shop\">");
      echo ("<img src=\"img/shop_sex2.png\">");
      Escribir_Tooltip_Comprar($lang, 4);
      echo ("</a>");
    } else {
      echo ("<a href=\"#\" class=\"Ntooltip_shop\">");
      echo ("<img src=\"img/shop_sex2.png\" style=\"opacity: 0.3; filter:alpha(opacity=30); \">");
      Escribir_Tooltip_Comprar_Gray($lang, 4);
      echo ("</a>");
    }
    echo ("</td>");
    echo ("<td>");
    if ($lang == 'en')
    {
      echo ("<table width=\"100%\" style=\"
		                  padding-left: 0px;
		                  padding-right: 8px;
		                  padding-top: 4px;
		                  padding-bottom: 4px;
		                  background-color: #111111;
                		  color: #ffffff;
			\"
			>");
      echo ("<tr>");
      echo ("<td style=\"text-align: middle; font-size: 12px;
		vertical-align: top;
		\">");
      echo ("<span style='color: #ffff00; font-size: 13px;'><b>Fukushima II coolant</b></span>");
      echo (" is a greenish liquid which can be applied to specimens to mutate them into sexual reproduction. This");
      echo (" means that every time a new specimen is evolved its decission tree will be the combination of its two");
      echo (" parents' trees, and its basic characteristics will be a mixture from both.");
//                        echo ("<br/><br/><p style=\"color:#ffff55; font-weight: bold;\">Click to consume</p>");
    } else {
      echo ("<table width=\"100%\" style=\"
		                  padding-left: 0px;
		                  padding-right: 8px;
		                  padding-top: 8px;
		                  padding-bottom: 8px;
		                  background-color: #111111;
                		  color: #ffffff;
            \"
		>");
      echo ("<tr>");
      echo ("<td style=\"text-align: middle; font-size: 12px;
		vertical-align: top;
		\">");
      echo ("<span style='color: #ffff00; font-size: 13px;'><b>Refrigerante de Fukushima II</b></span>");
      echo (" es un l&iacute;quido verdoso que ");
      echo ("se puede aplicar a espec&iacute;menes para mutarlos y hacer que se reproduzcan sexualmente. ");
      echo ("Esto significa que cada vez que un nuevo especimen sea evolucionado, su &aacute;rbol de decisi&oacute;n ");
      echo ("ser&aacute; la combinaci&oacute;n de los dos &aacute;rboles de sus padres, y que sus caracter&iacute;sticas ");
      echo ("ser&aacute;n una mezcla de ambos");
//                echo ("<br/><br/><p style=\"color:#ffff55; font-weight: bold;\">Haz click para consumir</p>");
                      }
//    echo ("<br/>");
//    echo ("<br/>");
//    echo ("<span class=\"goldcoin\"> 4</span>");
//    echo ("<img src=\"img/goldcoin.gif\" style=\"vertical-align:middle;\">");
     echo ("</td></tr></table>");
    echo ("</td>");
    echo ("</tr>");
    echo ("</table>");







    echo ("<br/>");

    // ··················································
    //    ENTRADA EN LA TIENDA DE FUKUSHIMA III COOLANT
    // ··················································

    echo ("<table style=\"
			background-color: #111111;
		                  padding-left: 8px;
		                  padding-right: 8px;
		                  padding-top: 2px;
		                  padding-bottom: 2px;
		\">");
    echo ("<tr>");
    echo ("<td width=\"50px\" style=\"vertical-align: middle;\">");
    if ($jugador_campana->dinero >= 4)
    {
      echo ("<a href=\"index.php?catid=".$catid."&idcampana=".$idcampana."&accion=comprar_objeto&idobjeto=3\"  class=\"Ntooltip_shop\">");
      echo ("<img src=\"img/shop_sex3.png\">");
      Escribir_Tooltip_Comprar($lang, 4);
      echo ("</a>");
    } else {
      echo ("<a href=\"#\" class=\"Ntooltip_shop\">");
      echo ("<img src=\"img/shop_sex3.png\" style=\"opacity: 0.3; filter:alpha(opacity=30); \">");
      Escribir_Tooltip_Comprar_Gray($lang, 4);
      echo ("</a>");
    }
    echo ("</td>");
    echo ("<td>");
    if ($lang == 'en')
    {
      echo ("<table width=\"100%\" style=\"
		                  padding-left: 0px;
		                  padding-right: 8px;
		                  padding-top: 4px;
		                  padding-bottom: 4px;
		                  background-color: #111111;
                		  color: #ffffff;
			\"
			>");
      echo ("<tr>");
      echo ("<td style=\"text-align: middle; font-size: 12px;
		vertical-align: top;
		\">");
      echo ("<span style='color: #ffff00; font-size: 13px;'><b>Fukushima III coolant</b></span>");
      echo (" is a viscous liquid which can be applied to specimens to mutate them into trisexual reproduction. What this");
      echo (" means is that whenever a new specimen is evolved its decission tree will be the combination of its three");
      echo (" parents' trees, and its basic characteristics will be a mixture from all them.");
//                        echo ("<br/><br/><p style=\"color:#ffff55; font-weight: bold;\">Click to consume</p>");
    } else {
      echo ("<table width=\"100%\" style=\"
		                  padding-left: 0px;
		                  padding-right: 8px;
		                  padding-top: 8px;
		                  padding-bottom: 8px;
		                  background-color: #111111;
                		  color: #ffffff;
            \"
		>");
      echo ("<tr>");
      echo ("<td style=\"text-align: middle; font-size: 12px;
		vertical-align: top;
		\">");
      echo ("<span style='color: #ffff00; font-size: 13px;'><b>Refrigerante de Fukushima III</b></span>");
      echo (" es un l&iacute;quido viscoso que ");
      echo ("se puede aplicar a espec&iacute;menes para mutarlos y hacer que se reproduzcan trisexualmente. ");
      echo ("Lo que esto quiere decir es que cada vez que un nuevo especimen sea evolucionado, su &aacute;rbol de decisi&oacute;n ");
      echo ("ser&aacute; la combinaci&oacute;n de los tres &aacute;rboles de sus padres, y que sus caracter&iacute;sticas ");
      echo ("ser&aacute;n una mezcla de todos ellos");
                        echo ("</td></tr></table>");


    echo ("</td>");
    echo ("</tr>");
    echo ("</table>");

    echo ("<br/>");

    // ··················································
    //    ENTRADA EN LA TIENDA DE POLVO DE THREE MILE ISLAND
    // ··················································

    echo ("<table style=\"
			background-color: #111111;
		                  padding-left: 8px;
		                  padding-right: 8px;
		                  padding-top: 2px;
		                  padding-bottom: 2px;
		\">");
    echo ("<tr>");
    echo ("<td width=\"50px\" style=\"vertical-align: middle;\">");
    if ($jugador_campana->dinero >= 1)
    {
      echo ("<a href=\"index.php?catid=".$catid."&idcampana=".$idcampana."&accion=comprar_objeto&idobjeto=4\" class=\"Ntooltip_shop\">");
      echo ("<img src=\"img/shop_mutacion.png\">");
      Escribir_Tooltip_Comprar($lang, 1);
      echo ("</a>");
    } else {
      echo ("<a href=\"#\" class=\"Ntooltip_shop\">");
      echo ("<img src=\"img/shop_mutacion.png\" style=\"opacity: 0.3; filter:alpha(opacity=30); \">");
      Escribir_Tooltip_Comprar_Gray($lang, 1);
      echo ("</a>");
//      echo ("<img src=\"img/shop_caesium_134.png\" style=\"opacity: 0.3; filter:alpha(opacity=30); \">");
    }
    echo ("</td>");
    echo ("<td>");
    if ($lang == 'en')
    {
      echo ("<table width=\"100%\" style=\"
		                  padding-left: 0px;
		                  padding-right: 8px;
		                  padding-top: 8px;
		                  padding-bottom: 8px;
		                  background-color: #111111;
                		  color: #ffffff;
			\"
			>");
      echo ("<tr>");
      echo ("<td style=\"text-align: middle; font-size: 12px;
		vertical-align: top;
		\">");
//      echo ("When used, allows you to alter the mutation ratio in your specimen generation up to 5 percentual points");
      echo ("<span style='color: #ffff00; font-size: 13px;'><b>Three Mile Island dust</b></span>");
      echo (" is a powerful mutagen in powder form which allows you to modify");
      echo (" the mutation ratio for specimen generation. Each item will let you");
      echo (" change this value up to five percentual points.");
//                        echo ("<br/><br/><p style=\"color:#ffff55; font-weight: bold;\">Click to consume</p>");
    } else {
      echo ("<table width=\"100%\" style=\"
		                  padding-left: 0px;
		                  padding-right: 8px;
		                  padding-top: 8px;
		                  padding-bottom: 8px;
		                  background-color: #111111;
                		  color: #ffffff;
            \"
		>");
      echo ("<tr>");
      echo ("<td style=\"text-align: middle; font-size: 12px;
		vertical-align: top;
		\">");
      echo ("<span style='color: #ffff00; font-size: 13px;'><b>Polvo de Three Mile Island</b></span>");
      echo (" es un poderoso mut&aacute;geno que permite modificar el ratio de mutaci&oacute;n ");
      echo ("al generar espec&iacute;menes. Cada objeto te permitir&aacute; cambiar este valor hasta en cinco puntos percentuales.");
//                echo ("<br/><br/><p style=\"color:#ffff55; font-weight: bold;\">Haz click para consumir</p>");

                      }
//    echo ("<br/>");
//    echo ("<br/>");
//    echo ("<span class=\"goldcoin\"> 1</span>");
//    echo ("<img src=\"img/goldcoin.gif\" style=\"vertical-align:middle;\">");
                        echo ("</td></tr></table>");


    echo ("</td>");
    echo ("</tr>");
    echo ("</table>");



    echo ("<br/>");


    // ··················································
    //    ENTRADA EN LA TIENDA DE CAESIUM 134 OXIDE
    // ··················································

    echo ("<table style=\"
			background-color: #111111;
		                  padding-left: 8px;
		                  padding-right: 8px;
		                  padding-top: 2px;
		                  padding-bottom: 2px;
		\">");
    echo ("<tr>");
    echo ("<td width=\"50px\" style=\"vertical-align: middle;\">");
    if ($jugador_campana->dinero >= 3)
    {
      echo ("<a href=\"index.php?catid=".$catid."&idcampana=".$idcampana."&accion=comprar_objeto&idobjeto=5\" class=\"Ntooltip_shop\">");
      echo ("<img src=\"img/shop_caesium_134.png\">");
      Escribir_Tooltip_Comprar($lang, 3);
      echo ("</a>");
    } else {
      echo ("<a href=\"#\" class=\"Ntooltip_shop\">");
      echo ("<img src=\"img/shop_caesium_134.png\" style=\"opacity: 0.3; filter:alpha(opacity=30); \">");
      Escribir_Tooltip_Comprar_Gray($lang, 3);
      echo ("</a>");
//      echo ("<img src=\"img/shop_caesium_134.png\" style=\"opacity: 0.3; filter:alpha(opacity=30); \">");
    }
    echo ("</td>");
    echo ("<td>");
    if ($lang == 'en')
    {
      echo ("<table width=\"100%\" style=\"
		                  padding-left: 0px;
		                  padding-right: 8px;
		                  padding-top: 8px;
		                  padding-bottom: 8px;
		                  background-color: #111111;
                		  color: #ffffff;
			\"
			>");
      echo ("<tr>");
      echo ("<td style=\"text-align: middle; font-size: 12px;
		vertical-align: top;
		\">");
//      echo ("When used, allows you to alter the mutation ratio in your specimen generation up to 5 percentual points");
      echo ("<span style='color: #ffff00; font-size: 13px;'><b>Caesium 134 Oxide</b></span>");
      echo (" is a mutagen which allows you to modify");
      echo (" the mutation vulnerability in your specimens. When consumed, it");
      echo (" turns mutation intensity to <b>soft</b>");
//                        echo ("<br/><br/><p style=\"color:#ffff55; font-weight: bold;\">Click to consume</p>");
    } else {
      echo ("<table width=\"100%\" style=\"
		                  padding-left: 0px;
		                  padding-right: 8px;
		                  padding-top: 8px;
		                  padding-bottom: 8px;
		                  background-color: #111111;
                		  color: #ffffff;
            \"
		>");
      echo ("<tr>");
      echo ("<td style=\"text-align: middle; font-size: 12px;
		vertical-align: top;
		\">");
      echo ("<span style='color: #ffff00; font-size: 13px;'><b>&Oacute;xido de Cesio 134</b></span>");
      echo (" es un poderoso mut&aacute;geno que permite modificar la vulnerabilidad de tus espec&iacute;menes");
      echo ("a las mutaciones. Cuando se consume, convierte la intensidad de las mutaciones en <b>d&eacute;bil</b>.");
//                echo ("<br/><br/><p style=\"color:#ffff55; font-weight: bold;\">Haz click para consumir</p>");

                      }
//    echo ("<br/>");
//    echo ("<br/>");
//    echo ("<span class=\"goldcoin\"> 3</span>");
//    echo ("<img src=\"img/goldcoin.gif\" style=\"vertical-align:middle;\">");
                        echo ("</td></tr></table>");


    echo ("</td>");
    echo ("</tr>");
    echo ("</table>");


    echo ("<br/>");

    // ··················································
    //    ENTRADA EN LA TIENDA DE CAESIUM 134 OXIDE
    // ··················································

    echo ("<table style=\"
			background-color: #111111;
		                  padding-left: 8px;
		                  padding-right: 8px;
		                  padding-top: 2px;
		                  padding-bottom: 2px;
		\">");
    echo ("<tr>");
    echo ("<td width=\"50px\" style=\"vertical-align: middle;\">");
    if ($jugador_campana->dinero >= 3)
    {
      echo ("<a href=\"index.php?catid=".$catid."&idcampana=".$idcampana."&accion=comprar_objeto&idobjeto=6\" class=\"Ntooltip_shop\">");
      echo ("<img src=\"img/shop_caesium_135.png\">");
      Escribir_Tooltip_Comprar($lang, 3);
      echo ("</a>");
    } else {
      echo ("<a href=\"#\" class=\"Ntooltip_shop\">");
      echo ("<img src=\"img/shop_caesium_135.png\" style=\"opacity: 0.3; filter:alpha(opacity=30); \">");
      Escribir_Tooltip_Comprar_Gray($lang, 3);
      echo ("</a>");
    }
    echo ("</td>");
    echo ("<td>");
    if ($lang == 'en')
    {
      echo ("<table width=\"100%\" style=\"
		                  padding-left: 0px;
		                  padding-right: 8px;
		                  padding-top: 8px;
		                  padding-bottom: 8px;
		                  background-color: #111111;
                		  color: #ffffff;
			\"
			>");
      echo ("<tr>");
      echo ("<td style=\"text-align: middle; font-size: 12px;
		vertical-align: top;
		\">");
//      echo ("When used, allows you to alter the mutation ratio in your specimen generation up to 5 percentual points");
      echo ("<span style='color: #ffff00; font-size: 13px;'><b>Caesium 135 Oxide</b></span>");
      echo (" is a mutagen which allows you to modify");
      echo (" the mutation vulnerability in your specimens. When consumed, it");
      echo (" turns mutation intensity to <b>medium</b>");
//                        echo ("<br/><br/><p style=\"color:#ffff55; font-weight: bold;\">Click to consume</p>");
    } else {
      echo ("<table width=\"100%\" style=\"
		                  padding-left: 0px;
		                  padding-right: 8px;
		                  padding-top: 8px;
		                  padding-bottom: 8px;
		                  background-color: #111111;
                		  color: #ffffff;
            \"
		>");
      echo ("<tr>");
      echo ("<td style=\"text-align: middle; font-size: 12px;
		vertical-align: top;
		\">");
      echo ("<span style='color: #ffff00; font-size: 13px;'><b>&Oacute;xido de Cesio 135</b></span>");
      echo (" es un poderoso mut&aacute;geno que permite modificar la vulnerabilidad de tus espec&iacute;menes");
      echo ("a las mutaciones. Cuando se consume, convierte la intensidad de las mutaciones en <b>media</b>.");
//                echo ("<br/><br/><p style=\"color:#ffff55; font-weight: bold;\">Haz click para consumir</p>");

                      }
//    echo ("<br/>");
//    echo ("<br/>");
//    echo ("<span class=\"goldcoin\"> 3</span>");
//    echo ("<img src=\"img/goldcoin.gif\" style=\"vertical-align:middle;\">");
                        echo ("</td></tr></table>");


    echo ("</td>");
    echo ("</tr>");
    echo ("</table>");






    echo ("<br/>");

    // ··················································
    //    ENTRADA EN LA TIENDA DE CESIO 137
    // ··················································

    echo ("<table style=\"
			background-color: #111111;
		                  padding-left: 8px;
		                  padding-right: 8px;
		                  padding-top: 2px;
		                  padding-bottom: 2px;
		\">");
    echo ("<tr>");
    echo ("<td width=\"50px\" style=\"vertical-align: middle;\">");
    if ($jugador_campana->dinero >= 3)
    {
      echo ("<a href=\"index.php?catid=".$catid."&idcampana=".$idcampana."&accion=comprar_objeto&idobjeto=7\" class=\"Ntooltip_shop\">");
      echo ("<img src=\"img/shop_caesium_137.png\">");
      Escribir_Tooltip_Comprar($lang, 3);
      echo ("</a>");
    } else {
      echo ("<a href=\"#\" class=\"Ntooltip_shop\">");
      echo ("<img src=\"img/shop_caesium_137.png\" style=\"opacity: 0.3; filter:alpha(opacity=30); \">");
      Escribir_Tooltip_Comprar_Gray($lang, 3);
      echo ("</a>");
//      echo ("<img src=\"img/shop_caesium_137.png\" style=\"opacity: 0.3; filter:alpha(opacity=30); \">");
    }
    echo ("</td>");
    echo ("<td>");
    if ($lang == 'en')
    {
      echo ("<table width=\"100%\" style=\"
		                  padding-left: 0px;
		                  padding-right: 8px;
		                  padding-top: 8px;
		                  padding-bottom: 8px;
		                  background-color: #111111;
                		  color: #ffffff;
			\"
			>");
      echo ("<tr>");
      echo ("<td style=\"text-align: middle; font-size: 12px;
		vertical-align: top;
		\">");
//      echo ("When used, allows you to alter the mutation ratio in your specimen generation up to 5 percentual points");
      echo ("<span style='color: #ffff00; font-size: 13px;'><b>Caesium 137 Oxide</b></span>");
      echo (" is a mutagen which allows you to modify");
      echo (" the mutation vulnerability in your specimens. When consumed, it");
      echo (" turns mutation intensity to <b>strong</b>");
//                        echo ("<br/><br/><p style=\"color:#ffff55; font-weight: bold;\">Click to consume</p>");
    } else {
      echo ("<table width=\"100%\" style=\"
		                  padding-left: 0px;
		                  padding-right: 8px;
		                  padding-top: 8px;
		                  padding-bottom: 8px;
		                  background-color: #111111;
                		  color: #ffffff;
            \"
		>");
      echo ("<tr>");
      echo ("<td style=\"text-align: middle; font-size: 12px;
		vertical-align: top;
		\">");
      echo ("<span style='color: #ffff00; font-size: 13px;'><b>&Oacute;xido de Cesio 137</b></span>");
      echo (" es un poderoso mut&aacute;geno que permite modificar la vulnerabilidad de tus espec&iacute;menes");
      echo ("a las mutaciones. Cuando se consume, convierte la intensidad de las mutaciones en <b>fuerte</b>.");
//                echo ("<br/><br/><p style=\"color:#ffff55; font-weight: bold;\">Haz click para consumir</p>");

                      }
//    echo ("<br/>");
//    echo ("<br/>");
//    echo ("<span class=\"goldcoin\"> 3</span>");
//    echo ("<img src=\"img/goldcoin.gif\" style=\"vertical-align:middle;\">");
                        echo ("</td></tr></table>");


    echo ("</td>");
    echo ("</tr>");
    echo ("</table>");



    echo ("<br/>");

    // ··················································
    //    ENTRADA EN LA TIENDA DE POLONIO 210
    // ··················································

    echo ("<table style=\"
			background-color: #111111;
		                  padding-left: 8px;
		                  padding-right: 8px;
		                  padding-top: 2px;
		                  padding-bottom: 2px;
		\">");
    echo ("<tr>");
    echo ("<td width=\"50px\" style=\"vertical-align: middle;\">");
    if ($jugador_campana->dinero >= 3)
    {
      echo ("<a href=\"index.php?catid=".$catid."&idcampana=".$idcampana."&accion=comprar_objeto&idobjeto=8\" class=\"Ntooltip_shop\">");
      echo ("<img src=\"img/shop_mezcla.png\">");
      Escribir_Tooltip_Comprar($lang, 3);
      echo ("</a>");
    } else {
      echo ("<a href=\"#\" class=\"Ntooltip_shop\">");
      echo ("<img src=\"img/shop_mezcla.png\" style=\"opacity: 0.3; filter:alpha(opacity=30); \">");
      Escribir_Tooltip_Comprar_Gray($lang, 3);
      echo ("</a>");
//      echo ("<img src=\"img/shop_mezcla.png\" style=\"opacity: 0.3; filter:alpha(opacity=30); \">");
    }
    echo ("</td>");
    echo ("<td>");
    if ($lang == 'en')
    {
      echo ("<table width=\"100%\" style=\"
		                  padding-left: 0px;
		                  padding-right: 8px;
		                  padding-top: 8px;
		                  padding-bottom: 8px;
		                  background-color: #111111;
                		  color: #ffffff;
			\"
			>");
      echo ("<tr>");
      echo ("<td style=\"text-align: middle; font-size: 12px;
		vertical-align: top;
		\">");
      echo ("<span style='color: #ffff00; font-size: 13px;'><b>Polonium 210 cloud</b></span>");
      echo (" is a set of particles which allow you to mix");
      echo (" all demes in the generation after it is consumed.");
//                        echo ("<br/><br/><p style=\"color:#ffff55; font-weight: bold;\">Click to consume</p>");
    } else {
      echo ("<table width=\"100%\" style=\"
		                  padding-left: 0px;
		                  padding-right: 8px;
		                  padding-top: 8px;
		                  padding-bottom: 8px;
		                  background-color: #111111;
                		  color: #ffffff;
            \"
		>");
      echo ("<tr>");
      echo ("<td style=\"text-align: middle; font-size: 12px;
		vertical-align: top;
		\">");
      echo ("<span style='color: #ffff00; font-size: 13px;'><b>Nube de Polonio 210</b></span>");
      echo (" es un conjunto de part&iacute;culas que te permiten mezclar");
      echo (" todos los demes en la generaci&oacute;n posterior a su consumo.");
//                echo ("<br/><br/><p style=\"color:#ffff55; font-weight: bold;\">Haz click para consumir</p>");

                      }
//    echo ("<br/>");
//    echo ("<br/>");
//    echo ("<span class=\"goldcoin\"> 3</span>");
//    echo ("<img src=\"img/goldcoin.gif\" style=\"vertical-align:middle;\">");
                        echo ("</td></tr></table>");


    echo ("</td>");
    echo ("</tr>");
    echo ("</table>");







    echo ("<br/>");

    // ··················································
    //    ENTRADA EN LA TIENDA DE 5XChernobyl Extract
    // ··················································

    echo ("<table style=\"
			background-color: #111111;
		                  padding-left: 8px;
		                  padding-right: 8px;
		                  padding-top: 2px;
		                  padding-bottom: 2px;
		\">");
    echo ("<tr>");
    echo ("<td width=\"50px\" style=\"vertical-align: middle;\">");
    if ($jugador_campana->dinero >= 10)
    {
      echo ("<a href=\"index.php?catid=".$catid."&idcampana=".$idcampana."&accion=comprar_objeto&idobjeto=9\" class=\"Ntooltip_shop\">");
      echo ("<img src=\"img/shop_superman1.png\">");
      Escribir_Tooltip_Comprar($lang, 10);
      echo ("</a>");
    } else {
      echo ("<a href=\"#\" class=\"Ntooltip_shop\">");
      echo ("<img src=\"img/shop_superman1.png\" style=\"opacity: 0.3; filter:alpha(opacity=30); \">");
      Escribir_Tooltip_Comprar_Gray($lang, 10);
      echo ("</a>");
    }
    echo ("</td>");
    echo ("<td>");
    if ($lang == 'en')
    {
      echo ("<table width=\"100%\" style=\"
		                  padding-left: 0px;
		                  padding-right: 8px;
		                  padding-top: 8px;
		                  padding-bottom: 8px;
		                  background-color: #111111;
                		  color: #ffffff;
			\"
			>");
      echo ("<tr>");
      echo ("<td style=\"text-align: middle; font-size: 12px;
		vertical-align: top;
		\">");
//      echo ("When used, allows you to alter the mutation ratio in your specimen generation up to 5 percentual points");
      echo ("<span style='color: #ffff00; font-size: 13px;'><b>Chernobyl 5X extract</b></span>");
      echo (" is a mixture providing a boost in the basic characteristics of specimens ");
      echo ("in the next generation evolution ");
      echo ("after it is consumed.");
//                        echo ("<br/><br/><p style=\"color:#ffff55; font-weight: bold;\">Click to consume</p>");
    } else {
      echo ("<table width=\"100%\" style=\"
		                  padding-left: 0px;
		                  padding-right: 8px;
		                  padding-top: 8px;
		                  padding-bottom: 8px;
		                  background-color: #111111;
                		  color: #ffffff;
            \"
		>");
      echo ("<tr>");
      echo ("<td style=\"text-align: middle; font-size: 12px;
		vertical-align: top;
		\">");
      echo ("<span style='color: #ffff00; font-size: 13px;'><b>Extracto 5X de Chernobyl</b></span>");
      echo (" es una mezcla que proporciona un incremento en las caracter&iacute;sticas b&aacute;sicas de");
      echo (" espec&iacute;menes en la evoluci&oacute;n de la siguiente generaci&oacute;n tras su consumo.");

                      }
                        echo ("</td></tr></table>");


    echo ("</td>");
    echo ("</tr>");
    echo ("</table>");




    echo ("<br/>");

    // ··················································
    //    ENTRADA EN LA TIENDA DE 10X Chernobyl Extract
    // ··················································

    echo ("<table style=\"
			background-color: #111111;
		                  padding-left: 8px;
		                  padding-right: 8px;
		                  padding-top: 2px;
		                  padding-bottom: 2px;
		\">");
    echo ("<tr>");
    echo ("<td width=\"50px\" style=\"vertical-align: middle;\">");
    if ($jugador_campana->dinero >= 50)
    {
      echo ("<a href=\"index.php?catid=".$catid."&idcampana=".$idcampana."&accion=comprar_objeto&idobjeto=10\" class=\"Ntooltip_shop\">");
      echo ("<img src=\"img/shop_superman2.png\">");
      Escribir_Tooltip_Comprar($lang, 50);
      echo ("</a>");
    } else {
      echo ("<a href=\"#\" class=\"Ntooltip_shop\">");
      echo ("<img src=\"img/shop_superman2.png\" style=\"opacity: 0.3; filter:alpha(opacity=30); \">");
      Escribir_Tooltip_Comprar_Gray($lang, 50);
      echo ("</a>");
//      echo ("<img src=\"img/shop_superman2.png\" style=\"opacity: 0.3; filter:alpha(opacity=30); \">");
    }
    echo ("</td>");
    echo ("<td>");
    if ($lang == 'en')
    {
      echo ("<table width=\"100%\" style=\"
		                  padding-left: 0px;
		                  padding-right: 8px;
		                  padding-top: 8px;
		                  padding-bottom: 8px;
		                  background-color: #111111;
                		  color: #ffffff;
			\"
			>");
      echo ("<tr>");
      echo ("<td style=\"text-align: middle; font-size: 12px;
		vertical-align: top;
		\">");
//      echo ("When used, allows you to alter the mutation ratio in your specimen generation up to 5 percentual points");
      echo ("<span style='color: #ffff00; font-size: 13px;'><b>Chernobyl 10X extract</b></span>");
      echo (" is a mixture providing an increased boost in the basic characteristics of specimens ");
      echo ("in the next generation evolution ");
      echo ("after it is consumed.");
//                        echo ("<br/><br/><p style=\"color:#ffff55; font-weight: bold;\">Click to consume</p>");
    } else {
      echo ("<table width=\"100%\" style=\"
		                  padding-left: 0px;
		                  padding-right: 8px;
		                  padding-top: 8px;
		                  padding-bottom: 8px;
		                  background-color: #111111;
                		  color: #ffffff;
            \"
		>");
      echo ("<tr>");
      echo ("<td style=\"text-align: middle; font-size: 12px;
		vertical-align: top;
		\">");
      echo ("<span style='color: #ffff00; font-size: 13px;'><b>Extracto 10X de Chernobyl</b></span>");
      echo (" es una mezcla que proporciona un incremento aumentado en las caracter&iacute;sticas b&aacute;sicas de");
      echo (" espec&iacute;menes en la evoluci&oacute;n de la siguiente generaci&oacute;n tras su consumo.");

                      }
                        echo ("</td></tr></table>");


    echo ("</td>");
    echo ("</tr>");
    echo ("</table>");



*/



    //echo ("<br/>");

    // -------------------------------------------------
    //                Obras estructurales
    // -------------------------------------------------
/*
    echo ("<br/>");
    echo ("<span style=\"color: #ddab00;
		font-size: 13px;
		font-weight: bold;
		\">");
    if ($lang == 'en')
    {
      echo ("Structural modifications ");
    } else {
      echo ("Obras estructurales");
    }
    echo ("</span>");
    echo ("<br/>");
*/



    // ································ AMPLIAR SLOTS ·································

//    echo ("<br/>");
//    echo ("<table style=\"
//			background-color: #111111;
//		                  padding-left: 8px;
//		                  padding-right: 0px;
//		                  padding-top: 2px;
//		                  padding-bottom: 2px;
//		\">");
//    echo ("<tr>");
//    echo ("<td width=\"140px\" style=\"vertical-align: middle;\">");

//    if ($jugador_campana->dinero >= $coste)
//    {
//      echo ("<a href=\"index.php?catid=".$catid."&idcampana=".$idcampana."&accion=comprar_obra&idobra=1\" class=\"Ntooltip_shop\">");
//      echo ("<img src=\"img/shop_deme_abyss.png\">");
//      if ($lang == 'en')
//      {
//        Escribir_Tooltip_Obra($lang, $coste, 'Abyssal deme', $jugador_campana->num_slots_deme_profundidades);
//      } else {
//        Escribir_Tooltip_Obra($lang, $coste, 'deme del Abismo', $jugador_campana->num_slots_deme_profundidades);
//      }
//      echo ("</a>");
//    } else {
//      echo ("<a href=\"#\" class=\"Ntooltip_shop\">");
//      echo ("<img src=\"img/shop_deme_abyss.png\" style=\"opacity: 0.3; filter:alpha(opacity=30); \">");
//      Escribir_Tooltip_Comprar_Gray($lang, $coste);
//      echo ("</a>");
//    }

//    echo ("&nbsp;");
/*
    if ($jugador_campana->dinero >= $coste)
    {
      echo ("<a href=\"index.php?catid=".$catid."&idcampana=".$idcampana."&accion=comprar_obra&idobra=2\" class=\"Ntooltip_shop\">");
      echo ("<img src=\"img/shop_deme_forest.png\">");
      if ($lang == 'en')
      {
        Escribir_Tooltip_Obra($lang, $coste, 'Forest deme', $jugador_campana->num_slots_deme_bosque);
      } else {
        Escribir_Tooltip_Obra($lang, $coste, 'deme del Bosque', $jugador_campana->num_slots_deme_bosque);
      }
      echo ("</a>");
    } else {
      echo ("<a href=\"#\" class=\"Ntooltip_shop\">");
      echo ("<img src=\"img/shop_deme_forest.png\" style=\"opacity: 0.3; filter:alpha(opacity=30); \">");
      Escribir_Tooltip_Comprar_Gray($lang, $coste);
      echo ("</a>");
    }
*/
/*
      echo ("&nbsp;");

    if ($jugador_campana->dinero >= $coste)
    {
      echo ("<a href=\"index.php?catid=".$catid."&idcampana=".$idcampana."&accion=comprar_obra&idobra=3\" class=\"Ntooltip_shop\">");
//      echo ("<img src=\"img/shop_deme_volcano.png\">");
//      if ($lang == 'en')
//      {
//        Escribir_Tooltip_Obra($lang, $coste, 'Volcano deme', $jugador_campana->num_slots_deme_volcan);
//      } else {
//        Escribir_Tooltip_Obra($lang, $coste, 'deme del Volc&aacute;n', $jugador_campana->num_slots_deme_volcan);
//      }
      echo ("</a>");
    } else {
      echo ("<a href=\"#\" class=\"Ntooltip_shop\">");
      echo ("<img src=\"img/shop_deme_volcano.png\" style=\"opacity: 0.3; filter:alpha(opacity=30); \">");
//      Escribir_Tooltip_Comprar_Gray($lang, $coste);
      echo ("</a>");
//      echo ("<img src=\"img/shop_sex1.png\" style=\"opacity: 0.3; filter:alpha(opacity=30); \">");
    }
*/




//    echo ("</td>");
//    echo ("<td>");
//    if ($lang == 'en')
//    {
/*
      echo ("<table width=\"100%\" style=\"
                                  padding-left: 0px;
                                  padding-right: 8px;
                                  padding-top: 8px;
                                  padding-bottom: 8px;
                                  background-color: #111111;
                                  color: #ffffff;
                        \"
                        >");
      echo ("<tr>");
      echo ("<td style=\"text-align: middle; font-size: 12px;
                vertical-align: top;
                \">");
//      echo ("When used, allows you to alter the mutation ratio in your specimen generation up to 5 percentual points");
      echo ("<span style='color: #ffff00; font-size: 13px;'><b>Deme slot expansion</b></span>");
      echo (" is a building work that will increase the chosen deme specimen storage capacity by one.");
//                        echo ("<br/><br/><p style=\"color:#ffff55; font-weight: bold;\">Click to consume</p>");
    } else {
      echo ("<table width=\"100%\" style=\"
                                  padding-left: 0px;
                                  padding-right: 8px;
                                  padding-top: 8px;
                                  padding-bottom: 8px;
                                  background-color: #111111;
                                  color: #ffffff;
            \"
                >");
      echo ("<tr>");
      echo ("<td style=\"text-align: middle; font-size: 12px;
                vertical-align: top;
                \">");
      echo ("<span style='color: #ffff00; font-size: 13px;'><b>Expansi&oacute;n de huecos de deme</b></span>");
      echo (" es una obra que aumentar&aacute; el espacio de almacenamiento del deme elegido en uno.");

                      }
                        echo ("</td></tr></table>");


    echo ("</td>");
//    echo ("<td>");
//    echo ("</td>");
    echo ("</tr>");
    echo ("</table>");


*/




    // ································ AMPLIAR CEREBRO ·································

    // 6 es en numero maximo de niveles
/*
    if ($jugador_campana->niveles_arbol < 6)
    {
      if ($jugador_campana->niveles_arbol == 3) { $coste = 20; }
      if ($jugador_campana->niveles_arbol == 4) { $coste = 150; }
      if ($jugador_campana->niveles_arbol == 5) { $coste = 1000; }

      echo ("<br/>");
      echo ("<table style=\"
			background-color: #111111;
		                  padding-left: 8px;
		                  padding-right: 0px;
		                  padding-top: 2px;
		                  padding-bottom: 2px;
		\">");
      echo ("<tr>");
      echo ("<td width=\"53px\" style=\"vertical-align: middle;\">");

      if ($jugador_campana->dinero >= $coste)
      {
        echo ("<a href=\"index.php?catid=".$catid."&idcampana=".$idcampana."&accion=comprar_obra&idobra=4\" class=\"Ntooltip_shop\">");
        echo ("<img src=\"img/shop_ampliar.png\">");
        if ($lang == 'en')
        {
          Escribir_Tooltip_Ampliar($lang, $coste, ($jugador_campana->niveles_arbol + 1));
        } else {
          Escribir_Tooltip_Ampliar($lang, $coste, ($jugador_campana->niveles_arbol + 1));
        }
        echo ("</a>");
      } else {
        echo ("<a href=\"#\" class=\"Ntooltip_shop\">");
        echo ("<img src=\"img/shop_ampliar.png\" style=\"opacity: 0.3; filter:alpha(opacity=30);       			
        			\">");
//      echo ("<img src=\"img/shop_deme_abyss.png\" style=\"opacity: 0.3; filter:alpha(opacity=30); \">");
        Escribir_Tooltip_Comprar_Gray($lang, $coste);
        echo ("</a>");
      }
      echo ("&nbsp;");

      echo ("</td>");
      echo ("<td>");
      if ($lang == 'en')
      {
        echo ("<table width=\"100%\" style=\"
                                  padding-left: 0px;
                                  padding-right: 8px;
                                  padding-top: 8px;
                                  padding-bottom: 8px;
                                  background-color: #111111;
                                  color: #ffffff;
                        \"
                        >");
        echo ("<tr>");
        echo ("<td style=\"text-align: middle; font-size: 12px;
                vertical-align: top;
                \">");
        echo ("<span style='color: #ffff00; font-size: 13px;'><b>Increase cerebral cortex capacity.</b></span> ");
        echo ("When bought, this improvement expands cerebral cortex capacity from your specimens and ");
        echo ("builds the necessary changes in your infraestructure, housing one more complexity level ");
        echo ("in the specimens' decission tree.<br/>");
        echo ("BEWARE! EVERY SPECIMEN WILL DIE AND BE REPLACED!");
      } else {
        echo ("<table width=\"100%\" style=\"
                                  padding-left: 0px;
                                  padding-right: 8px;
                                  padding-top: 8px;
                                  padding-bottom: 8px;
                                  background-color: #111111;
                                  color: #ffffff;
            \"
                >");
        echo ("<tr>");
        echo ("<td style=\"text-align: middle; font-size: 12px;
                vertical-align: top;
                \">");
        echo ("<span style='color: #ffff00; font-size: 13px;'><b>Ampliar capacidad cerebral y estructural.</b></span> ");
//        echo ("<br/>Ampliar capacidad cerebral y estructural (actual ".$jugador_campana->niveles_arbol.") : ");
        echo ("Al ser comprado, ampl&iacute;a la capacidad cerebral y de almacenaje de tus espec&iacute;menes,");
        echo ("albergando un nivel m&aacute;s de complejidad en su &aacute;rbol de decisi&oacute;n.<br/>");
        echo ("&iexcl;CUIDADO! TODOS TUS ESPEC&Iacute;MENES MORIR&Aacute;N Y SER&Aacute;N SUSTITUIDOS!");

                      }
                        echo ("</td></tr></table>");


      echo ("</td>");
      echo ("</tr>");
      echo ("</table>");
    }





*/











/*
    // 6 es en numero maximo de niveles
    if ($jugador_campana->niveles_arbol < 6)
    {
      if ($jugador_campana->niveles_arbol == 3) { $coste = 10; }
      if ($jugador_campana->niveles_arbol == 4) { $coste = 75; }
      if ($jugador_campana->niveles_arbol == 5) { $coste = 500; }

      if ($lang == 'en')
      {
        echo ("<br/>Increase cerebral cortex capacity (current ".$jugador_campana->niveles_arbol.") : ");
        echo ("When bought, this improvement expands cerebral cortex capacity from your specimens and ");
        echo ("builds the necessary changes in your infraestructure, housing one more complexity level ");
        echo ("in the specimens' decission tree");
        echo ("BE CAREFUL! EVERY SPECIMEN WILL DIE AND BE REPLACED!");
        echo ("<a href=\"index.php?catid=".$catid."&idcampana=".$idcampana."&accion=comprar_obra&idobra=4\">");
        echo (" Buy [".$coste." credits]");
      } else {
        echo ("<br/>Ampliar capacidad cerebral y estructural (actual ".$jugador_campana->niveles_arbol.") : ");
        echo ("Al ser comprado, ampl&iacute;a la capacidad cerebral y de almacenaje de tus espec&iacute;menes,");
        echo ("albergando un nivel m&aacute;s de complejidad en su &aacute;rbol de decisi&oacute;n.");
        echo ("&iexcl;OJO! TODOS TUS ESPEC&Iacute;MENES MORIR&Aacute;N Y SER&Aacute;N SUSTITUIDOS!");
        echo ("<a href=\"index.php?catid=".$catid."&idcampana=".$idcampana."&accion=comprar_obra&idobra=4\">");
        echo (" Comprar [".$coste." cr&eacute;ditos]");
      }
      echo ("</a>");
    }

*/


  }

?>
