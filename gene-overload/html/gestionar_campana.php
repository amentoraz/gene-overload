<?php

  include("clases/obj_arbol.php");
  include("clases/obj_especimen.php");
  include("clases/obj_especimen_torneo.php");

//  $accion = $_REQUEST['accion'];


//echo $accion."#".$catid;



  // **************************************************
  //    Desapuntarte de una campanya
  // **************************************************

  if ($accion == 'desapuntarse')
  {
    $idcampana = $_REQUEST['idcampana'];
    if (!is_numeric($idcampana))
    {
      die;
    }
    $especimen = new Especimen();
    $jugador_campana = new Jugador_campana();

    $estajugador = $jugador_campana->EstaCampanaJugador($link_r, $idjugador, $idcampana);

    if ($estajugador == 1)
    {
      // Borramos los especimenes de este jugador
      $especimen->JugadorEliminaCampana($link_w, $idjugador, $idcampana);

      // Lo borramos de la campanya
      $jugador_campana->Eliminar($link_w, $idjugador, $idcampana);

      echo ("<p class=\"correctosutil\">Quedas desapuntado de esta campa&ntilde;a!</p>");


    } else {
      echo ("<p class=\"errorsutil\">Lo siento, no estabas apuntado a esta campa&ntilde;a</p>");
    }

    $accion = null;

  }


  // **************************************************
  //    Apuntarte a una campanya
  // **************************************************

  if ($accion == 'apuntarse')
  {

    $idcampana = $_REQUEST['idcampana'];
    if (!is_numeric($idcampana))
    {
      die;
    }

    $arbol = new Arbol();
    $especimen = new Especimen();
    $especimen_torneo = new Especimen_Torneo();
    $jugador_campana = new Jugador_campana();

    $estajugador = $jugador_campana->EstaCampanaJugador($link_r, $idjugador, $idcampana);


    if ($estajugador == 1)
    {
      echo ("<p class=\"errorsutil\">Lo siento, ya perteneces a esta campa&ntilde;a</p>");
    } else {
      echo ("<p class=\"correctosutil\">Quedas apuntado a esta campa&ntilde;a!</p>");

      // 15 de dinero inicial, 7 slots por deme y 3 niveles de profundidad en el arbol lo ponemos como lo basico.
      $jugador_campana->InsertarElemento($link_w, $idjugador, $idcampana, 36, 7, 7, 7, 3);

      // Ahora tenemos que crear los especimenes.
      // Primer deme (profundidades)
      for ($i = 1; $i <= 7; $i++)
      {
//echo ($i."#");
        $especimen->CrearEspecimen($link_w, TOTAL_PUNTOS_BASE, 1, $idjugador, $idcampana, $i, 3);
        $idarbol = mysql_insert_id($link_w);
        // Generamos un arbol con 3 niveles
        $string_arbol = $arbol->GenerarArbolInicial(3);
        $especimen->arbol = $string_arbol;
        $especimen->ActualizarArbol($link_w, $idarbol);
      }
      // Segundo deme (bosque)
      for ($i = 1; $i <= 7; $i++)
      {
//echo ($i."$");
        $especimen->CrearEspecimen($link_w, TOTAL_PUNTOS_BASE, 2, $idjugador, $idcampana, $i, 3);
        $idarbol = mysql_insert_id($link_w);
        // Generamos un arbol con 3 niveles
//        $arbol->GenerarArbolInicial(3);
        // Generamos un arbol con 3 niveles
        $string_arbol = $arbol->GenerarArbolInicial(3);
        $especimen->arbol = $string_arbol;
        $especimen->ActualizarArbol($link_w, $idarbol);
      }
      // Tercer deme (fuego)
      for ($i = 1; $i <= 7; $i++)
      {
//echo ($i."|");
        $especimen->CrearEspecimen($link_w, TOTAL_PUNTOS_BASE, 3, $idjugador, $idcampana, $i, 3);
        $idarbol = mysql_insert_id($link_w);
        // Generamos un arbol con 3 niveles
//        $arbol->GenerarArbolInicial(3);
        // Generamos un arbol con 3 niveles
        $string_arbol = $arbol->GenerarArbolInicial(3);
        $especimen->arbol = $string_arbol;
        $especimen->ActualizarArbol($link_w, $idarbol);
      }

//echo ("#");
      // Una vez esta todo creado, ponemos por defecto para torneo
      $especimen->SacarDatos($link_r, 1, 1, $idjugador, $idcampana); // los dos 1s son iddeme e idslot respectivamente
      $especimen_torneo->ApuntarTorneo($link_w, $especimen->id);


    }



    $accion = null;

  }

  // **************************************************
  //    Listar las campanyas disponibles y apuntadas
  // **************************************************

  if ($accion == null)
  {


    echo (" Campa&ntilde;as en curso:");

    $campana = new Campana();
    $arraycampanas = $campana->ListarCampanasActivas($link_r);

    $jugador_campana = new Jugador_campana();

    for ($i = 1; $i <= count($arraycampanas); $i++)
    {
      echo ("<hr>");

      echo ("<p>");
      echo ("Campa&ntilde;a <b>".$arraycampanas[$i]['nombre']."</b>");
      echo (" (".$arraycampanas[$i]['fecha_inicio']." - ".$arraycampanas[$i]['fecha_fin'].")");
      echo ("</p>");

      echo ("<p>");
      echo ("<i>".$arraycampanas[$i]['descripcion']."</i>");
      echo ("</p>");
      $estajugador = $jugador_campana->EstaCampanaJugador($link_r, $idjugador, $arraycampanas[$i]['id']);

      if ($estajugador == 1)
      {
        echo ("<p>");
        echo ("<a href=\"index.php?catid=3&idcampana=".$arraycampanas[$i]['id']."\">");
        echo ("Acceder");
        echo ("</p>");

        echo ("<p>");
        echo ("<a href=\"index.php?catid=2&accion=desapuntarse&idcampana=".$arraycampanas[$i]['id']."\">");
        echo ("Desapuntarse");
        echo ("</p>");
      } else {
        echo ("<p>");
        echo ("<a href=\"index.php?catid=2&accion=apuntarse&idcampana=".$arraycampanas[$i]['id']."\">");
        echo ("Apuntarte a esta campa&ntilde;a");
        echo ("</a>");
        echo ("</p>");
      }
    }

  }

?>


