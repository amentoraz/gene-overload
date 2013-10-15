<?php


//  $accion = $_REQUEST['accion'];

  $debug = $_REQUEST['debug'];
  if (!is_numeric($debug))
  {
    $debug = 0;
  }


  // ··························· Zona comun de subpestanyas ···························

  echo ("<center>");
  if ($accion == null)
  {
//    echo ("<a href=\"index.php?catid=".$catid."&idcampana=".$idcampana."\">");
    if ($lang == 'en')
    {
      echo ("Tournament victories");
    } else {
      echo ("Victorias en torneos");
    }
//    echo ("</a>");
  } else {
    echo ("<a href=\"index.php?catid=".$catid."&idcampana=".$idcampana."\">");
    if ($lang == 'en')
    {
      echo ("Tournament victories");
    } else {
      echo ("Victorias en torneos");
    }
    echo ("</a>");
  }

  echo ("&nbsp;");
  echo ("-");
  echo ("&nbsp;");

  if ($accion == 'ranking_equipos')
  {
//    echo ("<a href=\"index.php?catid=".$catid."&idcampana=".$idcampana."\">");
    if ($lang == 'en')
    {
      echo ("Team victories");
    } else {
      echo ("Victorias en equipo");
    }
//    echo ("</a>");
  } else {
    echo ("<a href=\"index.php?catid=".$catid."&idcampana=".$idcampana."&accion=ranking_equipos\">");
    if ($lang == 'en')
    {
      echo ("Team victories");
    } else {
      echo ("Victorias en equipo");
    }
    echo ("</a>");
  }

  echo ("&nbsp;");
  echo ("-");
  echo ("&nbsp;");

  if ($accion == 'ranking_slots')
  {
//    echo ("<a href=\"index.php?catid=".$catid."&accion=ranking_slots&idcampana=".$idcampana."\">");
    if ($lang == 'en')
    {
      echo ("Slots");
    } else {
      echo ("Slots");
    }
//    echo ("</a>");
  } else {
    echo ("<a href=\"index.php?catid=".$catid."&accion=ranking_slots&idcampana=".$idcampana."\">");
    if ($lang == 'en')
    {
      echo ("Slots");
    } else {
      echo ("Slots");
    }
    echo ("</a>");
  }

  echo ("&nbsp;");
  echo ("-");
  echo ("&nbsp;");

  if ($accion == 'ranking_generaciones')
  {
//    echo ("<a href=\"index.php?catid=".$catid."&accion=ranking_generaciones&idcampana=".$idcampana."\">");
    if ($lang == 'en')
    {
      echo ("Generations");
    } else {
      echo ("Generaciones");
    }
//    echo ("</a>");
  } else {
    echo ("<a href=\"index.php?catid=".$catid."&accion=ranking_generaciones&idcampana=".$idcampana."\">");
    if ($lang == 'en')
    {
      echo ("Generations");
    } else {
      echo ("Generaciones");
    }
    echo ("</a>");
  }

  echo ("&nbsp;");
  echo ("-");
  echo ("&nbsp;");

  if ($accion == 'ranking_capturar')
  {
    if ($lang == 'en')
    {
      echo ("Capture the flag");
    } else {
      echo ("Capturar la bandera");
    }
  } else {
    echo ("<a href=\"index.php?catid=".$catid."&accion=ranking_capturar&idcampana=".$idcampana."\">");
    if ($lang == 'en')
    {
      echo ("Capture the flag");
    } else {
      echo ("Capturar la bandera");
    }
    echo ("</a>");
  }


/*
  echo ("&nbsp;");
  echo ("-");
  echo ("&nbsp;");
  if ($accion == 'ranking_creditos')
  {
    if ($lang == 'en')
    {
      echo ("Cr&eacute;dits");
    } else {
      echo ("Cr&eacute;ditos");
    }
  } else {
    echo ("<a href=\"index.php?catid=".$catid."&accion=ranking_creditos&idcampana=".$idcampana."\">");
    if ($lang == 'en')
    {
      echo ("Cr&eacute;dits");
    } else {
      echo ("Cr&eacute;ditos");
    }
    echo ("</a>");
  }
*/
  echo ("</center>");

      echo ("<div id=\"espacio\" class=\"espacio\">");
      echo ("</div>");



//  echo ("<hr/>");


  // ****************** $LIMITELEMENTOS SON LOS MOSTRADOS POR PAGINA ******************
  $limitelementos = 20;







  // *******************************************************
  //              Ranking por equipos
  // *******************************************************

  if ($accion == 'ranking_equipos')
  {
    $elclan = new Clan();
    $elclan->link_r = $link_r;
    $elclan->link_w = $link_w;

//echo $miclan->id;

    $pg = $_REQUEST['pg'];
    if ($pg == null) {
        $pg = $elclan->BuscarPaginaRank($link_r, $idcampana, $limitelementos, $miclan->id);
//	$pg = 1;
	}
    if (!is_numeric($pg))
    {
      $pg = 1;
    }
//$limitelementos = 2;
    $offset = (($pg - 1) * $limitelementos);

    $numelementostotal = $elclan->ContarElementosRank($link_r, $idcampana);
    $array = $elclan->BuscarElementosRank($link_r, $idcampana, $limitelementos, $offset);

//echo $numelementostotal;

    if ($numelementostotal > 0)
    {

    // ------------------------------> Paginado <--------------------------------------
    // Pagina $pg de $totpg
    $totpg = floor(($numelementostotal - 1) / $limitelementos) + 1;
    if ($totpg < 1) { $totpg = 1; }
    if ($totpg > 1)
    {
    if ($pg > 1) {
      $pgant = $pg - 1;
      echo ("<a class=\"paginado\" href=\"".$PATHBASE_exterior."index.php?accion=ranking_equipos&catid=".$catid."&idcampana=".$idcampana."&pg=".$pgant."\">");
      echo ("<img src=\"img/arrow_left.gif\" style=\"vertical-align: middle;\">");
      echo ("</a>");
      $pgf = 1;
    }

    for ($i = 1; $i <= $totpg; $i++)
    {
      if ($i == $pg) { echo (" ".$i." "); }
      // Para poner el numero de pagina con link
      if (($i == ($pg - 1)) ||
        ($i == ($pg - 2)) ||
        ($i == ($pg + 1)) ||
        ($i == ($pg + 2))
        )
      {
        echo (" <a class=\"pginado\" href=\"".$PATHBASE_exterior."index.php?accion=ranking_equipos&catid=".$catid."&idcampana=".$idcampana."&pg=".$i."\">".$i."</a> ");
      }
      // Para poner puntos suspensivos
      if (
          (($i == ($pg + 3)) && ($totpg > $i)) ||
          (($i == ($pg - 3)) && ($i > 1))
         )
      {
        echo (" ... ");
      }

      // Para la primera pagina
      if (($i == 1) && ($pg > (3)))
      {
        echo (" <a class=\"pginado\" href=\"".$PATHBASE_exterior."index.php?accion=ranking_equipos&catid=".$catid."&idcampana=".$idcampana."&pg=".$i."\">".$i."</a> ");
      }

      // Para la ultima pagina
      if (($i == $totpg) && ($pg < ($totpg - 2)))
      {
        echo (" <a class=\"pginado\" href=\"".$PATHBASE_exterior."index.php?accion=ranking_equipos&catid=".$catid."&idcampana=".$idcampana."&pg=".$i."\">".$i."</a> ");
      }

    }

    if ( ($offset + $limitelementos) < $numelementostotal) {
      $pgsig = $pg + 1;
      echo ("<a class=\"paginado\" href=\"".$PATHBASE_exterior."index.php?accion=ranking_equipos&catid=".$catid."&idcampana=".$idcampana."&pg=".$pgsig."\">");
      //P&aacute;gina siguiente</a>");
      echo ("<img src=\"img/arrow_right.gif\" style=\"vertical-align: middle;\">");
      echo ("</a>");
    }
    }
    // ------------------------------> Paginado <--------------------------------------


  ?>

     <div id="espacio" class="espacio">
     </div>


     <table class="tabla_standard" border="1">
     <tr>
      <?php
      if ($lang == 'en')
      {
      ?>
       <th width="70px">Position</th>
       <th width="150px">Team name</th>
       <th width="60px">Gold</th>
       <th width="60px">Silver</th>
       <th width="60px">Bronze</th>
       <th width="100px">Overall member generations</th>
      <?php } else { ?>
       <th width="70px">Posici&oacute;n</th>
       <th width="150px">Nombre equipo</th>
       <th width="60px">Oro</th>
       <th width="60px">Plata</th>
       <th width="60px">Bronce</th>
       <th width="100px">Generaciones totales de miembros</th>
      <?php }?>
     </tr>

     <?php

     // Mostramos la lista
     $mostrar = ($numelementostotal - $offset);
     if ($mostrar > $limitelementos){ $mostrar = $limitelementos; }
     if ($mostrar < 20) { $loquehay = $mostrar; $mostrar = 20; } else { $loquehay = 20; }
//echo $loquehay."#";
     for ($i = 1 ; $i <= $mostrar ; $i++)
     {
       if ($i % 2 == 1)
       {
         echo ("<tr style=\"background-color: #111111;\">");
       } else {
         echo ("<tr style=\"background-color: #333333;\">");
       }
       if ($i <= $loquehay)
       {

         // Posicion
         echo ("<td>");
         if ($miclan->id == $array[$i]['idclan'])
         {
             echo ("<strong>");
 	 }
         echo ($i + $offset);
         if ($miclan->id == $array[$i]['idclan']) { echo ("</strong>"); }
         echo ("</td>");

         // Nombre
         echo ("<td>");
         if ($miclan->id != $array[$i]['idclan'])
         {
             echo ("<a
			href=\"index.php?catid=9&idcampana=".$idcampana."&accion=ver_clan&idclan=".$array[$i]['idclan']."\"
			>");
 	 }
         echo $array[$i]['nombre'];
         if ($miclan->id != $array[$i]['idclan']) { echo ("</a>"); }

         if ($miclan->id == $array[$i]['idclan'])
         {
             echo ("<span style=\"color: #55ff55\">");
             if ($array[$i]['identificador'] != '') { echo (" [".$array[$i]['identificador']."]"); }
             echo ("</span>");
         } else {
             echo ("<span style=\"color: #ff5599\">");
             if ($array[$i]['identificador'] != '') { echo (" [".$array[$i]['identificador']."]"); }
             echo ("</span>");
         }
         echo ("</td>");

         echo ("<td>");
         echo $array[$i]['victorias'];
         echo ("</td>");

         echo ("<td>");
         echo $array[$i]['segundo'];
         echo ("</td>");

         echo ("<td>");
         echo $array[$i]['tercero'];
         echo ("</td>");

         echo ("<td>");
         echo $array[$i]['g_total']."+".$array[$i]['g_deme']."+".$array[$i]['g_individual'];
         echo ("</td>");

       } else {
         echo ("<td>&nbsp;</td>");
         echo ("<td>&nbsp;</td>");
         echo ("<td>&nbsp;</td>");
         echo ("<td>&nbsp;</td>");
         echo ("<td>&nbsp;</td>");
         echo ("<td>&nbsp;</td>");
       }
     }


     echo ("</tr>");
     echo ("</table>");

	// JQUERY PARA QUE PINTE LA ROW
	?>
	<script>
	$('table.tabla_standard tr').hover(function(){
	  $(this).find('td').addClass('hovered');
	}, function(){
	  $(this).find('td').removeClass('hovered');
	});
	</script>
	<?php


    }


  }












  // *******************************************************
  //              Ranking de slots totales
  // *******************************************************

  if ($accion == 'ranking_slots')
  {

    $idcampana = $_REQUEST['idcampana'];
    if (!is_numeric($idcampana))
    {
      die;
    }
    $jugador_campana = new Jugador_Campana();
    $jugador = new Jugador();

    $suclan = new Clan();
    $suclan->link_r = $link_r;
    $suclan->link_w = $link_w;


    $pg = $_REQUEST['pg'];
    if ($pg == null) {
        $pg = $jugador_campana->BuscarPaginaRank($link_r, $idcampana, $limitelementos, $idjugador, 1);
//	$pg = 1;
	}
    if (!is_numeric($pg))
    {
      $pg = 1;
    }
    $offset = (($pg - 1) * $limitelementos);

    $numelementostotal = $jugador_campana->ContarElementosRank($link_r, $idcampana);
    $array = $jugador_campana->BuscarElementosRank($link_r, $idcampana, $limitelementos, $offset, 1);

    if ($numelementostotal > 0)
    {

    // ------------------------------> Paginado <--------------------------------------
    // Pagina $pg de $totpg
    $totpg = floor(($numelementostotal - 1) / $limitelementos) + 1;
    if ($totpg < 1) { $totpg = 1; }
    if ($totpg > 1)
    {
    if ($pg > 1) {
      $pgant = $pg - 1;
      echo ("<a class=\"paginado\" href=\"".$PATHBASE_exterior."index.php?accion=ranking_slots&catid=".$catid."&idcampana=".$idcampana."&pg=".$pgant."\">");
      echo ("<img src=\"img/arrow_left.gif\" style=\"vertical-align: middle;\">");
      echo ("</a>");
      $pgf = 1;
    }

    for ($i = 1; $i <= $totpg; $i++)
    {
      if ($i == $pg) { echo (" ".$i." "); }
      // Para poner el numero de pagina con link
      if (($i == ($pg - 1)) ||
        ($i == ($pg - 2)) ||
        ($i == ($pg + 1)) ||
        ($i == ($pg + 2))
        )
      {
        echo (" <a class=\"pginado\" href=\"".$PATHBASE_exterior."index.php?accion=ranking_slots&catid=".$catid."&idcampana=".$idcampana."&pg=".$i."\">".$i."</a> ");
      }
      // Para poner puntos suspensivos
      if (
          (($i == ($pg + 3)) && ($totpg > $i)) ||
          (($i == ($pg - 3)) && ($i > 1))
         )
      {
        echo (" ... ");
      }

      // Para la primera pagina
      if (($i == 1) && ($pg > (3)))
      {
        echo (" <a class=\"pginado\" href=\"".$PATHBASE_exterior."index.php?accion=ranking_slots&catid=".$catid."&idcampana=".$idcampana."&pg=".$i."\">".$i."</a> ");
      }

      // Para la ultima pagina
      if (($i == $totpg) && ($pg < ($totpg - 2)))
      {
        echo (" <a class=\"pginado\" href=\"".$PATHBASE_exterior."index.php?accion=ranking_slots&catid=".$catid."&idcampana=".$idcampana."&pg=".$i."\">".$i."</a> ");
      }

    }

    if ( ($offset + $limitelementos) < $numelementostotal) {
      $pgsig = $pg + 1;
      echo ("<a class=\"paginado\" href=\"".$PATHBASE_exterior."index.php?accion=ranking_slots&catid=".$catid."&idcampana=".$idcampana."&pg=".$pgsig."\">");
      //P&aacute;gina siguiente</a>");
      echo ("<img src=\"img/arrow_right.gif\" style=\"vertical-align: middle;\">");
      echo ("</a>");
    }
    }
    // ------------------------------> Paginado <--------------------------------------




  ?>

     <div id="espacio" class="espacio">
     </div>


     <table class="tabla_standard" border="1">
     <tr>
      <?php
      if ($lang == 'en')
      {
      ?>
       <th width="70px">Position</th>
       <th width="150px">Player name</th>
       <th width="40px">Slots</th>
       <th width="120px">Abyssal depths</th>
       <th width="100px">Forest</th>
       <th width="100px">Volcano</th>
      <?php } else { ?>
       <th width="70px">Posici&oacute;n</th>
       <th width="150px">Nombre jugador</th>
       <th width="40px">Slots</th>
       <th width="100px">Profundidades</th>
       <th width="100px">Bosque</th>
       <th width="100px">Volc&aacute;n</th>
      <?php }?>
     </tr>


     <?php

     // Mostramos la lista
     $mostrar = ($numelementostotal - $offset);
     if ($mostrar > $limitelementos){ $mostrar = $limitelementos; }
     if ($mostrar < 20) { $loquehay = $mostrar; $mostrar = 20; } else { $loquehay = 20; }
//echo $loquehay."#";
     for ($i = 1 ; $i <= $mostrar ; $i++)
     {
       if ($i % 2 == 1)
       {
         echo ("<tr style=\"background-color: #111111;\">");
       } else {
         echo ("<tr style=\"background-color: #333333;\">");
       }
       if ($i <= $loquehay)
       {
         $jugador->SacarDatos($link_r, $array[$i]['idjugador']);
         echo ("<td>");
         if ($idjugador == $array[$i]['idjugador']) { echo ("<strong>"); }
         echo ($i + $offset);
         if ($idjugador == $array[$i]['idjugador']) { echo ("</strong>"); }
         echo ("</td>");
         echo ("<td>");

         if ($idjugador == $array[$i]['idjugador'])
         {
           echo ("<strong>");
	 } else {
           echo ("<a href=\"index.php?catid=5&accion=ver&idcampana=".$idcampana."&idelemento=".$array[$i]['idjugador']."\">");
         }
         echo $jugador->login;
         if ($idjugador == $array[$i]['idjugador']) { echo ("</strong>");
         } else {
           echo ("</a>");
         }
         if (($suclan->ObtieneClanJugador($array[$i]['idjugador'], $idcampana) == true)
		&& ($suclan->identificador != '')
		&& ($suclan->identificador != null)
		 )
         {
           if ($miclan->id == $suclan->id)
           {
             echo ("<span style=\"color: #55ff55\">");
             echo ("[".$suclan->identificador."]");
             echo ("</span>");
           } else {
             echo ("<a class=\"clan\"
			href=\"index.php?catid=9&idcampana=".$idcampana."&accion=ver_clan&idclan=".$suclan->id."\"
			>");
             echo ("[".$suclan->identificador."]");
             echo ("</a>");
           }

         }



         echo ("</td>");
         echo ("<td>");
         echo ($array[$i]['num_slots_total']);
         echo ("</td>");
         echo ("<td>");
         echo ($array[$i]['num_slots_deme_profundidades']);
         echo ("</td>");
         echo ("<td>");
         echo ($array[$i]['num_slots_deme_bosque']);
         echo ("</td>");
         echo ("<td>");
         echo ($array[$i]['num_slots_deme_volcan']);
         echo ("</td>");
         echo ("</tr>");
       } else {
         echo ("<td>&nbsp;</td>");
         echo ("<td>&nbsp;</td>");
         echo ("<td>&nbsp;</td>");
         echo ("<td>&nbsp;</td>");
         echo ("<td>&nbsp;</td>");
         echo ("<td>&nbsp;</td>");
       }


     }

     echo ("</tr>");
     echo ("</table>");

	// JQUERY PARA QUE PINTE LA ROW
	?>
	<script>
	$('table.tabla_standard tr').hover(function(){
	  $(this).find('td').addClass('hovered');
	}, function(){
	  $(this).find('td').removeClass('hovered');
	});
	</script>
	<?php

    }

  }


  // *******************************************************
  //              Ranking por creditos
  // *******************************************************
/*
  if ($accion == 'ranking_creditos')
  {

    $idcampana = $_REQUEST['idcampana'];
    if (!is_numeric($idcampana))
    {
      die;
    }
    $jugador_campana = new Jugador_Campana();
    $jugador = new Jugador();

    $suclan = new Clan();
    $suclan->link_r = $link_r;
    $suclan->link_w = $link_w;

    $pg = $_REQUEST['pg'];
    if ($pg == null) {
        $pg = $jugador_campana->BuscarPaginaRank($link_r, $idcampana, $limitelementos, $idjugador, 4);
    }
    if (!is_numeric($pg))
    {
      $pg = 1;
    }
    $offset = (($pg - 1) * $limitelementos);

    $numelementostotal = $jugador_campana->ContarElementosRank($link_r, $idcampana);
    $array = $jugador_campana->BuscarElementosRank($link_r, $idcampana, $limitelementos, $offset, 4);

    if ($numelementostotal > 0)
    {


    // ------------------------------> Paginado <--------------------------------------
    // Pagina $pg de $totpg
    $totpg = floor(($numelementostotal - 1) / $limitelementos) + 1;
    if ($totpg < 1) { $totpg = 1; }
    if ($totpg > 1)
    {
    if ($pg > 1) {
      $pgant = $pg - 1;
      echo ("<a class=\"paginado\" href=\"".$PATHBASE_exterior."index.php?accion=ranking_creditos&catid=".$catid."&idcampana=".$idcampana."&pg=".$pgant."\">");
      echo ("<img src=\"img/arrow_left.gif\" style=\"vertical-align: middle;\">");
      echo ("</a>");
      $pgf = 1;
    }

    for ($i = 1; $i <= $totpg; $i++)
    {
      if ($i == $pg) { echo (" ".$i." "); }
      // Para poner el numero de pagina con link
      if (($i == ($pg - 1)) ||
        ($i == ($pg - 2)) ||
        ($i == ($pg + 1)) ||
        ($i == ($pg + 2))
        )
      {
        echo (" <a class=\"pginado\" href=\"".$PATHBASE_exterior."index.php?accion=ranking_creditos&catid=".$catid."&idcampana=".$idcampana."&pg=".$i."\">".$i."</a> ");
      }
      // Para poner puntos suspensivos
      if (
          (($i == ($pg + 3)) && ($totpg > $i)) ||
          (($i == ($pg - 3)) && ($i > 1))
         )
      {
        echo (" ... ");
      }

      // Para la primera pagina
      if (($i == 1) && ($pg > (3)))
      {
        echo (" <a class=\"pginado\" href=\"".$PATHBASE_exterior."index.php?accion=ranking_creditos&catid=".$catid."&idcampana=".$idcampana."&pg=".$i."\">".$i."</a> ");
      }

      // Para la ultima pagina
      if (($i == $totpg) && ($pg < ($totpg - 2)))
      {
        echo (" <a class=\"pginado\" href=\"".$PATHBASE_exterior."index.php?accion=ranking_creditos&catid=".$catid."&idcampana=".$idcampana."&pg=".$i."\">".$i."</a> ");
      }

    }

    if ( ($offset + $limitelementos) < $numelementostotal) {
      $pgsig = $pg + 1;
      echo ("<a class=\"paginado\" href=\"".$PATHBASE_exterior."index.php?accion=ranking_creditos&catid=".$catid."&idcampana=".$idcampana."&pg=".$pgsig."\">");
      //P&aacute;gina siguiente</a>");
      echo ("<img src=\"img/arrow_right.gif\" style=\"vertical-align: middle;\">");
      echo ("</a>");
    }
    }
    // ------------------------------> Paginado <--------------------------------------




  ?>

     <div id="espacio" class="espacio">
     </div>

     <table class="tabla_standard" border="1">
     <tr>
      <?php
      if ($lang == 'en')
      {
      ?>
       <th width="70px">Position</th>
       <th width="175px">Player name</th>
      <?php } else { ?>
       <th width="70px">Posici&oacute;n</th>
       <th width="175px">Nombre jugador</th>
      <?php } 

//       <th width="100px">Credits</th>
//       <th width="100px">Creditos</th>

?>
     </tr>


     <?php

     // Mostramos la lista
     $mostrar = ($numelementostotal - $offset);
     if ($mostrar > $limitelementos){ $mostrar = $limitelementos; }
     if ($mostrar < 20) { $loquehay = $mostrar; $mostrar = 20; } else { $loquehay = 20; }
     for ($i = 1 ; $i <= $mostrar ; $i++)
     {
//       echo ("<tr>");
       if ($i % 2 == 1)
       {
         echo ("<tr style=\"background-color: #111111;\">");
       } else {
         echo ("<tr style=\"background-color: #333333;\">");
       }

       if ($i <= $loquehay)
       {

         $jugador->SacarDatos($link_r, $array[$i]['idjugador']);

         echo ("<td>");
         if ($idjugador == $array[$i]['idjugador']) { echo ("<strong>"); }
         echo ($i + $offset);
         if ($idjugador == $array[$i]['idjugador']) { echo ("</strong>"); }
         echo ("</td>");
         echo ("<td>");

         if ($idjugador == $array[$i]['idjugador'])
         {
           echo ("<strong>");
	 } else {
           echo ("<a href=\"index.php?catid=5&accion=ver&idcampana=".$idcampana."&idelemento=".$array[$i]['idjugador']."\">");
         }
         echo $jugador->login;
         if ($idjugador == $array[$i]['idjugador']) { echo ("</strong>");
         } else {
           echo ("</a>");
         }
         if (($suclan->ObtieneClanJugador($array[$i]['idjugador'], $idcampana) == true) 
                && ($suclan->identificador != '')
                && ($suclan->identificador != null)
                 )
         {
           if ($miclan->id == $suclan->id)
           {
             echo ("<span style=\"color: #55ff55\">");
             echo ("[".$suclan->identificador."]");
             echo ("</span>");
           } else {
             echo ("<a class=\"clan\"
                        href=\"index.php?catid=9&idcampana=".$idcampana."&accion=ver_clan&idclan=".$suclan->id."\"
                        >");
             echo ("[".$suclan->identificador."]");
             echo ("</a>");
           }
         }


         echo ("</td>");
//         echo ("<td>");
//         echo ($array[$i]['dinero']);
//         echo ("</td>");
         echo ("</tr>");
       } else {
         echo ("<td>&nbsp;</td>");
         echo ("<td>&nbsp;</td>");
         echo ("<td>&nbsp;</td>");
       }


     }

     echo ("</tr>");
     echo ("</table>");

    }

  }

*/
  // *******************************************************
  //              Ranking de generaciones
  // *******************************************************

  if ($accion == 'ranking_generaciones')
  {

    $idcampana = $_REQUEST['idcampana'];
    if (!is_numeric($idcampana))
    {
      die;
    }
    $jugador_campana = new Jugador_Campana();
    $jugador = new Jugador();

    $suclan = new Clan();
    $suclan->link_r = $link_r;
    $suclan->link_w = $link_w;

    $pg = $_REQUEST['pg'];
    if ($pg == null) {
        $pg = $jugador_campana->BuscarPaginaRank($link_r, $idcampana, $limitelementos, $idjugador, 3);
    }
    if (!is_numeric($pg))
    {
      $pg = 1;
    }
    $offset = (($pg - 1) * $limitelementos);

    $numelementostotal = $jugador_campana->ContarElementosRank($link_r, $idcampana);
    $array = $jugador_campana->BuscarElementosRank($link_r, $idcampana, $limitelementos, $offset, 3);

    if ($numelementostotal > 0)
    {

    // ------------------------------> Paginado <--------------------------------------
    // Pagina $pg de $totpg
    $totpg = floor(($numelementostotal - 1) / $limitelementos) + 1;
    if ($totpg < 1) { $totpg = 1; }
    if ($totpg > 1)
    {
    if ($pg > 1) {
      $pgant = $pg - 1;
      echo ("<a class=\"paginado\" href=\"".$PATHBASE_exterior."index.php?accion=ranking_generaciones&catid=".$catid."&idcampana=".$idcampana."&pg=".$pgant."\">");
      echo ("<img src=\"img/arrow_left.gif\" style=\"vertical-align: middle;\">");
      echo ("</a>");
      $pgf = 1;
    }

    for ($i = 1; $i <= $totpg; $i++)
    {
      if ($i == $pg) { echo (" ".$i." "); }
      // Para poner el numero de pagina con link
      if (($i == ($pg - 1)) ||
        ($i == ($pg - 2)) ||
        ($i == ($pg + 1)) ||
        ($i == ($pg + 2))
        )
      {
        echo (" <a class=\"pginado\" href=\"".$PATHBASE_exterior."index.php?accion=ranking_generaciones&catid=".$catid."&idcampana=".$idcampana."&pg=".$i."\">".$i."</a> ");
      }
      // Para poner puntos suspensivos
      if (
          (($i == ($pg + 3)) && ($totpg > $i)) ||
          (($i == ($pg - 3)) && ($i > 1))
         )
      {
        echo (" ... ");
      }

      // Para la primera pagina
      if (($i == 1) && ($pg > (3)))
      {
        echo (" <a class=\"pginado\" href=\"".$PATHBASE_exterior."index.php?accion=ranking_generaciones&catid=".$catid."&idcampana=".$idcampana."&pg=".$i."\">".$i."</a> ");
      }

      // Para la ultima pagina
      if (($i == $totpg) && ($pg < ($totpg - 2)))
      {
        echo (" <a class=\"pginado\" href=\"".$PATHBASE_exterior."index.php?accion=ranking_generaciones&catid=".$catid."&idcampana=".$idcampana."&pg=".$i."\">".$i."</a> ");
      }

    }

    if ( ($offset + $limitelementos) < $numelementostotal) {
      $pgsig = $pg + 1;
      echo ("<a class=\"paginado\" href=\"".$PATHBASE_exterior."index.php?accion=ranking_generaciones&catid=".$catid."&idcampana=".$idcampana."&pg=".$pgsig."\">");
      //P&aacute;gina siguiente</a>");
      echo ("<img src=\"img/arrow_right.gif\" style=\"vertical-align: middle;\">");
      echo ("</a>");
    }
    }
    // ------------------------------> Paginado <--------------------------------------
  ?>

     <div id="espacio" class="espacio">
     </div>


     <table class="tabla_standard" border="1">
     <tr>
      <?php
      if ($lang == 'en')
      {
      ?>
      <th width="70px">Position</th>
      <th width="150px">Player name</th>
      <th width="100px">Total generations</th>
      <th width="100px">Partial generations (deme)</th>
      <th width="100px">Partial generations (individuals)</th>
      <?php } else { ?>
      <th width="70px">Posici&oacute;n</th>
      <th width="150px">Nombre jugador</th>
      <th width="100px">Generaciones totales</th>
      <th width="100px">Generaciones parciales (deme)</th>
      <th width="100px">Generaciones parciales (individuos)</th>
      <?php } ?>


     </tr>


     <?php

     // Mostramos la lista
     $mostrar = ($numelementostotal - $offset);
     if ($mostrar > $limitelementos){ $mostrar = $limitelementos; }
     if ($mostrar < 20) { $loquehay = $mostrar; $mostrar = 20; } else { $loquehay = 20; }
     for ($i = 1 ; $i <= $mostrar ; $i++)
     {
       if ($i % 2 == 1)
       {
         echo ("<tr style=\"background-color: #111111;\">");
       } else {
         echo ("<tr style=\"background-color: #333333;\">");
       }
       if ($i <= $loquehay)
       {
         $jugador->SacarDatos($link_r, $array[$i]['idjugador']);
         echo ("<td>");
         if ($idjugador == $array[$i]['idjugador']) { echo ("<strong>"); }
         echo ($i + $offset);
         if ($idjugador == $array[$i]['idjugador']) { echo ("</strong>"); }
         echo ("</td>");
         echo ("<td>");

         if ($idjugador == $array[$i]['idjugador'])
         {
           echo ("<strong>");
	 } else {
           echo ("<a href=\"index.php?catid=5&accion=ver&idcampana=".$idcampana."&idelemento=".$array[$i]['idjugador']."\">");
         }
         echo $jugador->login;
         if ($idjugador == $array[$i]['idjugador']) { echo ("</strong>");
         } else {
           echo ("</a>");
         }
         if (($suclan->ObtieneClanJugador($array[$i]['idjugador'], $idcampana) == true) 
                && ($suclan->identificador != '')
                && ($suclan->identificador != null)
                 )
         {
           if ($miclan->id == $suclan->id)
           {
             echo ("<span style=\"color: #55ff55\">");
             echo ("[".$suclan->identificador."]");
             echo ("</span>");
           } else {
             echo ("<a class=\"clan\"
                        href=\"index.php?catid=9&idcampana=".$idcampana."&accion=ver_clan&idclan=".$suclan->id."\"
                        >");
             echo ("[".$suclan->identificador."]");
             echo ("</a>");
           } 

         }


         echo ("</td>");
         echo ("<td>");
         echo ($array[$i]['num_generaciones_total']);
         echo ("</td>");
         echo ("<td>");
         echo ($array[$i]['num_generaciones_demes']);
         echo ("</td>");
         echo ("<td>");
         echo ($array[$i]['num_generaciones_individual']);
         echo ("</td>");
         echo ("</tr>");
       } else {
         echo ("<td>&nbsp;</td>");
         echo ("<td>&nbsp;</td>");
         echo ("<td>&nbsp;</td>");
         echo ("<td>&nbsp;</td>");
         echo ("<td>&nbsp;</td>");
       }


     }

     echo ("</tr>");
     echo ("</table>");

	// JQUERY PARA QUE PINTE LA ROW
	?>
	<script>
	$('table.tabla_standard tr').hover(function(){
	  $(this).find('td').addClass('hovered');
	}, function(){
	  $(this).find('td').removeClass('hovered');
	});
	</script>
	<?php

    }

  }



  // *******************************************************
  //              Ranking de torneos
  // *******************************************************

  if ($accion == null)
  {

    $idcampana = $_REQUEST['idcampana'];
    if (!is_numeric($idcampana))
    {
      die;
    }
    $jugador_campana = new Jugador_Campana();
    $jugador = new Jugador();

    $suclan = new Clan();
    $suclan->link_r = $link_r;
    $suclan->link_w = $link_w;

    $pg = $_REQUEST['pg'];
    if ($pg == null) {
        $pg = $jugador_campana->BuscarPaginaRank($link_r, $idcampana, $limitelementos, $idjugador, 2);
//echo $pg;
    }
    if (!is_numeric($pg))
    {
      $pg = 1;
    }
    $offset = (($pg - 1) * $limitelementos);

    $numelementostotal = $jugador_campana->ContarElementosRank($link_r, $idcampana);
    $array = $jugador_campana->BuscarElementosRank($link_r, $idcampana, $limitelementos, $offset, 2);

    if ($numelementostotal > 0)
    {

    // ------------------------------> Paginado <--------------------------------------
    // Pagina $pg de $totpg
    $totpg = floor(($numelementostotal - 1) / $limitelementos) + 1;
    if ($totpg < 1) { $totpg = 1; }
    if ($totpg > 1)
    {
    if ($pg > 1) {
      $pgant = $pg - 1;
      echo ("<a class=\"paginado\" href=\"".$PATHBASE_exterior."index.php?catid=".$catid."&idcampana=".$idcampana."&pg=".$pgant."\">");
      echo ("<img src=\"img/arrow_left.gif\" style=\"vertical-align: middle;\">");
      echo ("</a>");
      $pgf = 1;
    }

    for ($i = 1; $i <= $totpg; $i++)
    {
      if ($i == $pg) { echo (" ".$i." "); }
      // Para poner el numero de pagina con link
      if (($i == ($pg - 1)) ||
        ($i == ($pg - 2)) ||
        ($i == ($pg + 1)) ||
        ($i == ($pg + 2))
        )
      {
        echo (" <a class=\"pginado\" href=\"".$PATHBASE_exterior."index.php?catid=".$catid."&idcampana=".$idcampana."&pg=".$i."\">".$i."</a> ");
      }
      // Para poner puntos suspensivos
      if (
          (($i == ($pg + 3)) && ($totpg > $i)) ||
          (($i == ($pg - 3)) && ($i > 1))
         )
      {
        echo (" ... ");
      }

      // Para la primera pagina
      if (($i == 1) && ($pg > (3)))
      {
        echo (" <a class=\"pginado\" href=\"".$PATHBASE_exterior."index.php?catid=".$catid."&idcampana=".$idcampana."&pg=".$i."\">".$i."</a> ");
      }

      // Para la ultima pagina
      if (($i == $totpg) && ($pg < ($totpg - 2)))
      {
        echo (" <a class=\"pginado\" href=\"".$PATHBASE_exterior."index.php?catid=".$catid."&idcampana=".$idcampana."&pg=".$i."\">".$i."</a> ");
      }

    }

    if ( ($offset + $limitelementos) < $numelementostotal) {
      $pgsig = $pg + 1;
      echo ("<a class=\"paginado\" href=\"".$PATHBASE_exterior."index.php?catid=".$catid."&idcampana=".$idcampana."&pg=".$pgsig."\">");
      //P&aacute;gina siguiente</a>");
      echo ("<img src=\"img/arrow_right.gif\" style=\"vertical-align: middle;\">");
      echo ("</a>");
    }
    }
    // ------------------------------> Paginado <--------------------------------------

  ?>

     <div id="espacio" class="espacio">
     </div>

     <table class="tabla_standard" border="1">
     <tr>
      <?php
      if ($lang == 'en')
      {
      ?>
      <th width="50px">Position</th>
      <th width="150px">Player name</th>
      <th width="70px">Gold</th>
      <th width="70px">Silver</th>
      <th width="70px">Bronze</th>
      <th width="100px">Total tournaments played</th>
      <?php } else { ?>
      <th width="70px">Posici&oacute;n</th>
      <th width="150px">Nombre jugador</th>
      <th width="70px">Oro</th>
      <th width="70px">Plata</th>
      <th width="70px">Bronce</th>
      <th width="100px">Torneos disputados</th>
      <?php } 

//      <th width="100px">Cr&eacute;ditos</th>
//      <th width="100px">Credits</th>

?>
     </tr>


     <?php

     // Mostramos la lista
     $mostrar = ($numelementostotal - $offset);
     if ($mostrar > $limitelementos){ $mostrar = $limitelementos; }
     if ($mostrar < 20) { $loquehay = $mostrar; $mostrar = 20; } else { $loquehay = 20; }
     for ($i = 1 ; $i <= $mostrar ; $i++)
     {
       if ($i % 2 == 1)
       {
         echo ("<tr style=\"background-color: #111111;\">");
       } else {
         echo ("<tr style=\"background-color: #333333;\">");
       }
//       echo ("<tr>");
       if ($i <= $loquehay)
       {
         $jugador->SacarDatos($link_r, $array[$i]['idjugador']);
         echo ("<td>");
         if ($idjugador == $array[$i]['idjugador']) { echo ("<strong>"); }
         echo ($i + $offset);
         if ($idjugador == $array[$i]['idjugador']) { echo ("</strong>"); }
         echo ("</td>");
         echo ("<td>");

         if ($idjugador == $array[$i]['idjugador'])
         {
           echo ("<strong>");
	 } else {
           echo ("<a href=\"index.php?catid=5&accion=ver&idcampana=".$idcampana."&idelemento=".$array[$i]['idjugador']."\">");
         }
         echo $jugador->login;
         if ($idjugador == $array[$i]['idjugador']) { echo ("</strong>");
         } else {
           echo ("</a>");
         }
         if (($suclan->ObtieneClanJugador($array[$i]['idjugador'], $idcampana) == true) 
                && ($suclan->identificador != '')
                && ($suclan->identificador != null)
                 )
         {
           if ($miclan->id == $suclan->id)
           {
             echo ("<span style=\"color: #55ff55\">");
             echo ("[".$suclan->identificador."]");
             echo ("</span>");
           } else {
             echo ("<a class=\"clan\"
                        href=\"index.php?catid=9&idcampana=".$idcampana."&accion=ver_clan&idclan=".$suclan->id."\"
                        >");
             echo ("[".$suclan->identificador."]");
             echo ("</a>");
           } 

         }



         echo ("</td>");
         echo ("<td>");
         echo ($array[$i]['num_torneos_victorias']);
         echo ("</td>");
         echo ("<td>");
         echo ($array[$i]['num_torneos_segundo']);
         echo ("</td>");
         echo ("<td>");
         echo ($array[$i]['num_torneos_tercero']);
         echo ("</td>");
         echo ("<td>");
         echo ($array[$i]['num_torneos']);
         echo ("</td>");
//         echo ("<td>");
//         echo ($array[$i]['dinero']);
//         echo ("</td>");
         echo ("</tr>");
       } else {
         echo ("<td>&nbsp;</td>");
         echo ("<td>&nbsp;</td>");
         echo ("<td>&nbsp;</td>");
         echo ("<td>&nbsp;</td>");
         echo ("<td>&nbsp;</td>");
         echo ("<td>&nbsp;</td>");
       }

     }

     echo ("</tr>");
     echo ("</table>");

	// JQUERY PARA QUE PINTE LA ROW
	?>
	<script>
	$('table.tabla_standard tr').hover(function(){
	  $(this).find('td').addClass('hovered');
	}, function(){
	  $(this).find('td').removeClass('hovered');
	});
	</script>
	<?php

    }

  }





  // *******************************************************
  //              Ranking de tcapturar la bandera
  // *******************************************************

  if ($accion == 'ranking_capturar')
  {

    $idcampana = $_REQUEST['idcampana'];
    if (!is_numeric($idcampana))
    {
      die;
    }
    $jugador_campana = new Jugador_Campana();
    $jugador = new Jugador();

    $suclan = new Clan();
    $suclan->link_r = $link_r;
    $suclan->link_w = $link_w;

    $pg = $_REQUEST['pg'];
    if ($pg == null) {
        $pg = $jugador_campana->BuscarPaginaRank($link_r, $idcampana, $limitelementos, $idjugador, 5);
//echo $pg;
    }
    if (!is_numeric($pg))
    {
      $pg = 1;
    }
    $offset = (($pg - 1) * $limitelementos);

    $numelementostotal = $jugador_campana->ContarElementosRank($link_r, $idcampana);
    $array = $jugador_campana->BuscarElementosRank($link_r, $idcampana, $limitelementos, $offset, 5);

    if ($numelementostotal > 0)
    {

    // ------------------------------> Paginado <--------------------------------------
    // Pagina $pg de $totpg
    $totpg = floor(($numelementostotal - 1) / $limitelementos) + 1;
    if ($totpg < 1) { $totpg = 1; }
    if ($totpg > 1)
    {
    if ($pg > 1) {
      $pgant = $pg - 1;
      echo ("<a class=\"paginado\" href=\"".$PATHBASE_exterior."index.php?accion=ranking_capturar&catid=".$catid."&idcampana=".$idcampana."&pg=".$pgant."\">");
      echo ("<img src=\"img/arrow_left.gif\" style=\"vertical-align: middle;\">");
      echo ("</a>");
      $pgf = 1;
    }

    for ($i = 1; $i <= $totpg; $i++)
    {
      if ($i == $pg) { echo (" ".$i." "); }
      // Para poner el numero de pagina con link
      if (($i == ($pg - 1)) ||
        ($i == ($pg - 2)) ||
        ($i == ($pg + 1)) ||
        ($i == ($pg + 2))
        )
      {
        echo (" <a class=\"pginado\" href=\"".$PATHBASE_exterior."index.php?accion=ranking_capturar&catid=".$catid."&idcampana=".$idcampana."&pg=".$i."\">".$i."</a> ");
      }
      // Para poner puntos suspensivos
      if (
          (($i == ($pg + 3)) && ($totpg > $i)) ||
          (($i == ($pg - 3)) && ($i > 1))
         )
      {
        echo (" ... ");
      }

      // Para la primera pagina
      if (($i == 1) && ($pg > (3)))
      {
        echo (" <a class=\"pginado\" href=\"".$PATHBASE_exterior."index.php?accion=ranking_capturar&catid=".$catid."&idcampana=".$idcampana."&pg=".$i."\">".$i."</a> ");
      }

      // Para la ultima pagina
      if (($i == $totpg) && ($pg < ($totpg - 2)))
      {
        echo (" <a class=\"pginado\" href=\"".$PATHBASE_exterior."index.php?accion=ranking_capturar&catid=".$catid."&idcampana=".$idcampana."&pg=".$i."\">".$i."</a> ");
      }

    }

    if ( ($offset + $limitelementos) < $numelementostotal) {
      $pgsig = $pg + 1;
      echo ("<a class=\"paginado\" href=\"".$PATHBASE_exterior."index.php?accion=ranking_capturar&catid=".$catid."&idcampana=".$idcampana."&pg=".$pgsig."\">");
      //P&aacute;gina siguiente</a>");
      echo ("<img src=\"img/arrow_right.gif\" style=\"vertical-align: middle;\">");
      echo ("</a>");
    }
    }
    // ------------------------------> Paginado <--------------------------------------



   $token = new Token();
   $token->SacarDatos($link_r, $idcampana);
   $idjugadorbandera = $token->idjugador;
   $jugador_bandera = new Jugador();
   $jugador_bandera->SacarDatos($link_r, $idjugadorbandera);

   echo ('<div id="espacio" class="espacio"></div>');
   echo ("<p style=\"color: #ffdb00;\">");
   echo ("<img src=\"img/flag_captured.png\">");
   if ($lang == 'en')
   {
     echo ("The flag is currently held by ".$jugador_bandera->login);
   } else {
     echo ("La bandera la tiene actualmente ".$jugador_bandera->login);
   }
   echo ("</p>");

  ?>

     <div id="espacio" class="espacio">
     </div>

     <table class="tabla_standard" border="1">
     <tr>
      <?php
      if ($lang == 'en')
      {
      ?>
      <th width="50px">Position</th>
      <th width="150px">Player name</th>
      <th width="250px">Total seconds holding the flag</th>
      <?php } else { ?>
      <th width="70px">Posici&oacute;n</th>
      <th width="150px">Nombre jugador</th>
      <th width="250px">Segundos sosteniendo la bandera</th>
      <?php } 

//      <th width="100px">Cr&eacute;ditos</th>
//      <th width="100px">Credits</th>

?>
     </tr>


     <?php

     // Mostramos la lista
     $mostrar = ($numelementostotal - $offset);
     if ($mostrar > $limitelementos){ $mostrar = $limitelementos; }
     if ($mostrar < 20) { $loquehay = $mostrar; $mostrar = 20; } else { $loquehay = 20; }
     for ($i = 1 ; $i <= $mostrar ; $i++)
     {
       if ($i % 2 == 1)
       {
         echo ("<tr style=\"background-color: #111111;\">");
       } else {
         echo ("<tr style=\"background-color: #333333;\">");
       }
//       echo ("<tr>");
       if ($i <= $loquehay)
       {
         $jugador->SacarDatos($link_r, $array[$i]['idjugador']);
         echo ("<td>");
         if ($idjugador == $array[$i]['idjugador']) { echo ("<strong>"); }
         echo ($i + $offset);
         if ($idjugador == $array[$i]['idjugador']) { echo ("</strong>"); }
         echo ("</td>");
         echo ("<td>");

         if ($idjugadorbandera == $array[$i]['idjugador'])
         {
           echo ("<img src=\"img/flag_captured.png\">");
         }

         if ($idjugador == $array[$i]['idjugador'])
         {
           echo ("<strong>");
	 } else {
           echo ("<a href=\"index.php?catid=5&accion=ver&idcampana=".$idcampana."&idelemento=".$array[$i]['idjugador']."\">");
         }
         echo $jugador->login;
         if ($idjugador == $array[$i]['idjugador']) { echo ("</strong>");
         } else {
           echo ("</a>");
         }
         if (($suclan->ObtieneClanJugador($array[$i]['idjugador'], $idcampana) == true) 
                && ($suclan->identificador != '')
                && ($suclan->identificador != null)
                 )
         {
           if ($miclan->id == $suclan->id)
           {
             echo ("<span style=\"color: #55ff55\">");
             echo ("[".$suclan->identificador."]");
             echo ("</span>");
           } else {
             echo ("<a class=\"clan\"
                        href=\"index.php?catid=9&idcampana=".$idcampana."&accion=ver_clan&idclan=".$suclan->id."\"
                        >");
             echo ("[".$suclan->identificador."]");
             echo ("</a>");
           } 

         }

         echo ("</td>");

        // Vamos a calcular el tiempo que estan con la bandera
         echo ("<td>");
         $segundos = $array[$i]['segundos_con_bandera'];
//echo ("#".$segundos."#");
         if ($segundos > 60)
         {
           $minutos = ($segundos - ($segundos % 60)) / 60;
           $segundos = $segundos % 60;
           if ($minutos > 60)
           {
             $horas = ($minutos - ($minutos % 60)) / 60;
             $minutos = $minutos % 60;
             if ($horas > 24)
             {
               $dias = ($horas - ($horas % 24)) / 24;
               $horas = $horas % 24;
               echo ($dias."d ".$horas."h ".$minutos."' ".$segundos."''");
             } else {
               echo ($horas."h ".$minutos."' ".$segundos."''");
             }
           } else {
             echo ($minutos."' ".$segundos."''");
           }
         } else {
           echo ($segundos."''");
         }
         echo ("</td>");
         echo ("</tr>");
       } else {
         echo ("<td>&nbsp;</td>");
         echo ("<td>&nbsp;</td>");
         echo ("<td>&nbsp;</td>");
       }

     }

     echo ("</tr>");
     echo ("</table>");

	// JQUERY PARA QUE PINTE LA ROW
	?>
	<script>
	$('table.tabla_standard tr').hover(function(){
	  $(this).find('td').addClass('hovered');
	}, function(){
	  $(this).find('td').removeClass('hovered');
	});
	</script>
	<?php

    }

  }

?>
