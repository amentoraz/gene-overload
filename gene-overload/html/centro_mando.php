<?php
//<div style="width: 695px;
//	    height: 1000px;
//	    overflow-y: auto;
//            overflow-x: hidden;
//	">


  include("clases/obj_especimen.php");
//  include("clases/obj_campana.php");
//  include("clases/obj_jugador_campana.php");
  include("clases/obj_combate.php");
  include("clases/obj_arbol.php");
  include("clases/obj_evolucion.php");
  include("clases/obj_especimen_torneo.php");
  include("clases/obj_torneo.php");
//  include("clases/obj_token.php");


  $debug = $_REQUEST['debug'];
  if (!is_numeric($debug))
  {
    $debug = 0;
  }


  //  Las acciones donde se evalua a los especimenes

  include("centro_mando_evolucionar.php");
  include("centro_mando_evaluar.php");
  include("centro_mando_detalle.php");
  include("centro_mando_enfrentar.php");




  // ****************************************
  //     Apuntar a un torneo a la criatura
  // ****************************************

  if ($accion == 'apuntar_torneo')
  {
    $idespecimen = $_REQUEST['idespecimen'];
    $tipotorneo = $_REQUEST['tipotorneo'];
    if (!is_numeric($idespecimen))
    {
      die;
    }
    $especimen_torneo = new Especimen_Torneo();
    // Primero quitamos los que hubiera
    $especimen_torneo->DesapuntarTorneo($link_w, $idjugador, $idcampana, $tipotorneo);
    // Ahora apuntamos a este
    $especimen_torneo->ApuntarTorneo($link_w, $idespecimen, $tipotorneo);
    $accion = null;
  }


  // ****************************************
  //     CHEAT: Anyade dinero a un admin
  // ****************************************

  if (($accion == 'cheat_dinero') && ($es_admin == 1))
  {
    $jugador_campana = new Jugador_Campana();

    $jugador_campana->SumarDinero($link_w, $idjugador, $idcampana, 50);
    if ($lang == 'en')
    {
      echo ("<p class=\"correctosutil\">CHETO: 50 credits added</p>");
    } else {
      echo ("<p class=\"correctosutil\">CHETO: A&ntilde;adidos 50 cr&eacute;ditos</p>");
    }
    $accion = null;
  }


  // ****************************************
  //     Subir el ratio de mutacion
  // ****************************************

  if ($accion == "subir_ratio_mutacion")
  {

    $campana = new Campana();
    $jugador_campana = new Jugador_campana();
    $jugador_campana->SacarDatos($link_r, $idjugador, $idcampana);

    if ($jugador_campana->ratio_mutacion_pendiente > 0)
    {
      if ($jugador_campana->ratio_mutacion < 100)
      {
        $jugador_campana->ratio_mutacion_pendiente = $jugador_campana->ratio_mutacion_pendiente - 1;
        $jugador_campana->GrabarMutacionPendiente($link_w, $idjugador, $idcampana);
        $jugador_campana->ratio_mutacion = $jugador_campana->ratio_mutacion + 1;
        $jugador_campana->GrabarMutacion($link_w, $idjugador, $idcampana);
      }
    }

    // Generamos un log para este usuario
    $log = new Log();
    $log->idjugador = $idjugador;
    $log->idcampana = $idcampana;
    $log->tipo_suceso = 5; // 5, sube ratio mutacion
    $log->valor = 0; // irrelevante
    $log->EscribirLog($link_w);


    $accion = null;
  }


  // ****************************************
  //     Bajar el ratio de mutacion
  // ****************************************

  if ($accion == "bajar_ratio_mutacion")
  {

    $campana = new Campana();
    $jugador_campana = new Jugador_campana();
    $jugador_campana->SacarDatos($link_r, $idjugador, $idcampana);

    if ($jugador_campana->ratio_mutacion_pendiente > 0)
    {
      if ($jugador_campana->ratio_mutacion > 0)
      {
        $jugador_campana->ratio_mutacion_pendiente = $jugador_campana->ratio_mutacion_pendiente - 1;
        $jugador_campana->GrabarMutacionPendiente($link_w, $idjugador, $idcampana);
        $jugador_campana->ratio_mutacion = $jugador_campana->ratio_mutacion - 1;
        $jugador_campana->GrabarMutacion($link_w, $idjugador, $idcampana);
      }
    }

    // Generamos un log para este usuario
    $log = new Log();
    $log->idjugador = $idjugador;
    $log->idcampana = $idcampana;
    $log->tipo_suceso = 6; // 6, baja ratio mutacion
    $log->valor = 0; // irrelevante
    $log->EscribirLog($link_w);

    $accion = null;
  }





    // Para cerrar una pestanya y abrir otra
    ?>
    <script>
    function mover(origen, destino)
    {
       $(document.getElementById(origen)).slideUp();
       $(document.getElementById(destino)).slideDown();
    }
    </script>
    <?php


    // PINTAR LA ZONA DE NAVEGACION PARA TODAS LAS PAGINAS DEL CENTRO DE MANDO
    function PintarNavegacion($origen, $izquierda, $derecha, $textoizquierda, $textoderecha)
    {
  echo ("<table width=\"100%\">");
  echo ("<tr>");
      echo ("<td>");
      if ($izquierda != null)
      {
        echo ("<a href=\"javascript:mover('$origen','$izquierda');\"
		class=\"enlace_mov_left\"
		>");
        echo ("<b><< ".$textoizquierda."</b>");
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
        echo ("<a href=\"javascript:mover('$origen','$derecha');\"
		class=\"enlace_mov_right\"
		>");
        echo ("<b>".$textoderecha." >></b>");
        echo ("</a>");
      }
      echo ("</td>");
      echo ("</tr>");
//      echo ("<tr height=\"85px\">");
echo ("</tr>");
echo ("</table>");
    }






  // ****************************************
  //     Vista principal
  // ****************************************

  if ($accion == null)
  {

    $campana = new Campana();
    $jugador_campana = new Jugador_campana();
    $especimen_torneo = new Especimen_Torneo();

    $apuntado_torneo_profundidades = $especimen_torneo->ContarJugadorTorneo($link_r, $idjugador, 1);
    $especimen_torneo->ObtenEspecimenTorneo($link_r, 1, $idjugador);
    $idtorneo_profundidades = $especimen_torneo->idespecimen;

    $apuntado_torneo_bosque = $especimen_torneo->ContarJugadorTorneo($link_r, $idjugador, 2);
    $especimen_torneo->ObtenEspecimenTorneo($link_r, 2, $idjugador);
    $idtorneo_bosque = $especimen_torneo->idespecimen;

    $apuntado_torneo_volcan = $especimen_torneo->ContarJugadorTorneo($link_r, $idjugador, 3);
    $especimen_torneo->ObtenEspecimenTorneo($link_r, 3, $idjugador);
    $idtorneo_volcan = $especimen_torneo->idespecimen;

    $apuntado_torneo = $especimen_torneo->ContarJugadorTorneo($link_r, $idjugador, 0);
    $especimen_torneo->ObtenEspecimenTorneo($link_r, 0, $idjugador);


    $campana->SacarDatos($link_r, $idcampana);
    $especimen = new Especimen();

    $jugador_campana->SacarDatos($link_r, $idjugador, $idcampana);


//        resultado(datos, destino);
    ?>
    <script>
      function reevaluar(datos, parametros, destino)
      {
        dinerito = document.getElementById('dinerito').innerHTML;
        dinerito = dinerito - 1;
        document.getElementById('dinerito').innerHTML = dinerito;
        $.ajax({
          type: 'GET',
          url: datos,
          data: parametros,
          mydestino: destino,
          success: function(html) {
		document.getElementById(this.mydestino).innerHTML = html;
                },

        });

      }
    </script>
    <?php
//                        document.getElementById(destino).innerHTML = html;


    // -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-
    //    Marcamos el DIV del centro de mando estandar
    // -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-
    echo ("<div id=\"centro_mando\"
		style=\"display: block;
		\"
		>
		");

     // Navegacion

     // Le metemos este div para que no lo muestre hasta que la pagina este cargada
     echo ("<div id=\"enlace_ocultar\"
		style=\"display: none;\">");
     if ($lang == 'en')
     {
       PintarNavegacion('centro_mando', '', 'atacar', '', 'Attack other players');
     } else {
       PintarNavegacion('centro_mando', '', 'atacar', '', 'Atacar otros jugadores');
     }
     echo ("</div>");

    // -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-


    // Ratio de mutacion
    echo ("<img src=\"img/radiation.png\"
		style=\" vertical-align: middle; \"
		>&nbsp;");
    echo ("<span style=\"color: #ffdb00; font-size: 13px;\">");
    if ($lang == 'en')
    {
      echo ("<b>Mutation ratio</b> : ".$jugador_campana->ratio_mutacion);
    } else {
      echo ("<b>Ratio de mutaci&oacute;n</b> : ".$jugador_campana->ratio_mutacion);
    }
    echo ("%</span>");

    if ($jugador_campana->ratio_mutacion_pendiente > 0)
    {
//        if ($jugador_campana->ratio_mutacion > 0)
        if ($jugador_campana->ratio_mutacion < 100)
        {
          echo ("&nbsp;");
          echo ("<a href=\"index.php?catid=".$catid."&accion=subir_ratio_mutacion&idcampana=".$idcampana."\">");
          echo ("<img src=\"img/flecha_arriba.png\" style=\"vertical-align: middle;\"
		>");
          echo ("</a>");
//        if ($jugador_campana->ratio_mutacion < 100)
        if ($jugador_campana->ratio_mutacion > 0)
        {
          echo ("<a href=\"index.php?catid=".$catid."&accion=bajar_ratio_mutacion&idcampana=".$idcampana."\">");
          echo ("<img src=\"img/flecha_abajo.png\" style=\"vertical-align: middle;\"
				>");
          echo ("</a>");
        }
      }
    }

    echo (" &nbsp; ");

    echo ("<span style=\"color: #ffdb00; font-size: 13px;\">");
    if ($lang == 'en')
    {
      echo ("<b>Mutation intensity</b> : ");
      if ($jugador_campana->ratio_intensidad_mutacion == 1) { echo ("Weak"); }
      if ($jugador_campana->ratio_intensidad_mutacion == 2) { echo ("Medium"); }
      if ($jugador_campana->ratio_intensidad_mutacion == 3) { echo ("Strong"); }
    } else {
      echo ("<b>Intensidad de mutaci&oacute;n</b> : ");
      if ($jugador_campana->ratio_intensidad_mutacion == 1) { echo ("D&eacute;bil"); }
      if ($jugador_campana->ratio_intensidad_mutacion == 2) { echo ("Mediana"); }
      if ($jugador_campana->ratio_intensidad_mutacion == 3) { echo ("Fuerte"); }
    }
    echo ("</span>");
    echo ("<br/>");
echo ("<br/>");



    // ****************************
    //       BUFFS activos
    // ****************************

    $numbuffs = 0;

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


    // Cheat para admins

    if ($es_admin == 1)
    {
      echo ("<br/>");
      echo ("<b><a href=\"index.php?catid=".$catid."&idcampana=".$idcampana."&accion=cheat_dinero\">");
      if ($lang == 'en')
      {
        echo ("Add 50 credits [admin]");
      } else {
        echo ("Sumar 50 de dinero [admin]");
      }
      echo ("</a></b>");
    }







   // INICIO DE OPCIONES DE EVALUAR Y PUNTUAR

   echo ("<div id=\"espacio\" class=\"espacio\">");
   echo ("</div>");

   echo ("<table width=\"665px\">");
   echo ("<tr>");
   echo ("<td width=\"325px\">");

    echo ("<b>");
    echo ("<a href=\"index.php?catid=".$catid."&idcampana=".$idcampana."&accion=evolucionar_generacion\"
		class=\"enlace_mov_left\"
		>");
	?>
	<img src="img/evolve1.gif"
		onmouseover="javascript:this.src='img/evolve2.gif';" 
		onmouseout="javascript:this.src='img/evolve1.gif';"
	>
	<?php
    echo ("&nbsp;");
    if ($lang == 'en')
    {
      echo ("Evolve generation ");
    } else {
      echo ("Evolucionar generacion ");
    }
    echo ("<span class=\"goldcoin\">1</span>");
    echo ("<img src=\"img/goldcoin.gif\" style=\"vertical-align:middle;\">");
    echo ("</a></b>");

    // Evolucionar 5 generaciones
//    echo ("<br/>");
//    echo ("<b>");
//    echo ("<a href=\"index.php?catid=".$catid."&idcampana=".$idcampana."&accion=evolucionar_generacion&mode=1\"
//		class=\"enlace_mov_left\"
//		>");
//	<img src="img/evolve5-1.gif"
//		onmouseover="javascript:this.src='img/evolve5-2.gif';" 
//		onmouseout="javascript:this.src='img/evolve5-1.gif';"
//	>
//    echo ("&nbsp;");
//    if ($lang == 'en')
//    {
//      echo ("Evolve 5 generations ");
//    } else {
//      echo ("Evolucionar 5 generaciones ");
//    }
//    echo ("<span class=\"goldcoin\">5</span>");
//    echo ("<img src=\"img/goldcoin.gif\" style=\"vertical-align:middle;\">");
//    echo ("</a></b>");



   // --------------------- Evolucion de clan --------------------

   if ($tengoclan == true)
   {
      // Y ahora la evolucion de clan
      echo ("<br/>");
      echo ("<b>");
      echo ("<a href=\"index.php?catid=".$catid."&idcampana=".$idcampana."&accion=evolucionar_generacion_clan\"
		class=\"enlace_mov_left\"
		>");
        ?>
        <img src="img/evolve1.gif"
                onmouseover="javascript:this.src='img/evolve2.gif';" 
                onmouseout="javascript:this.src='img/evolve1.gif';"
        >
        <?php
      echo ("&nbsp;");
      if ($lang == 'en')
      {
        echo ("Evolve generation (team) ");
      } else {
        echo ("Evolucionar generacion (equipo) ");
      }
      echo ("<span class=\"goldcoin\">2</span>");
      echo ("<img src=\"img/goldcoin.gif\" style=\"vertical-align:middle;\">");
      echo ("</a></b>");
   }



   // ----------------------- A la derecha, evaluar ----------------

   echo ("</td>");
   echo ("<td width=\"370px\" style=\"
		text-align: right;
		\">");

//    if (($es_premium == 1) || ($es_admin == 1))
//    {
      echo ("<a href=\"index.php?catid=".$catid."&accion=enfrentar&idcampana=".$idcampana."\"
		class=\"enlace_mov_right\"
		><b>");
      if ($lang == 'en')
      {
        echo ("Play test fight between two specimens");
      } else {
        echo ("Confrontar a dos espec&iacute;menes");
      }
      ?></b>&nbsp;
	<img src="img/espadas1.gif"
		onmouseover="javascript:this.src='img/espadas2.gif';" 
		onmouseout="javascript:this.src='img/espadas1.gif';"
	>
      <?php
      echo ("</a>");
      echo ("<br/>");
//    }

    echo ("<a href=\"index.php?catid=".$catid."&accion=evaluar&idcampana=".$idcampana."\"
		class=\"enlace_mov_right\"
		><b>");
    if ($lang == 'en')
    {
      echo ("Basic test for all samples");
    } else {
      echo ("Evaluaci&oacute;n b&aacute;sica de espec&iacute;menes");
    }
    ?>  </b>&nbsp;
	<img src="img/espadas1.gif"
		onmouseover="javascript:this.src='img/espadas2.gif';" 
		onmouseout="javascript:this.src='img/espadas1.gif';"
	>
    <?php
    echo ("</a>");

   $torneo = new Torneo();
   if ($torneo->SacarDatosUltima($link_r, $idcampana) != -1)
   {
//if ($quiero == 1) {
     // Si han habido torneos en esta campanya, permite evaluar contra hologramas de ellos
     echo ("<br/>");
     echo ("<span class=\"goldcoin\">2</span>");
     echo ("<img src=\"img/goldcoin.gif\" style=\"vertical-align:middle;\">");
     echo ("<a href=\"index.php?catid=".$catid."&accion=evaluar_campeones&idcampana=".$idcampana."\"
		class=\"enlace_mov_right\"
		><b>");
     if ($lang == 'en')
     {
       echo (" Test all against latest tournament champions");
     } else {
       echo (" Evaluar todos con ganadores &uacute;ltimo torneo");
     }
     ?>  </b>&nbsp;
	<img src="img/espadas1.gif"
		onmouseover="javascript:this.src='img/espadas2.gif';" 
		onmouseout="javascript:this.src='img/espadas1.gif';"
	>
     <?php
     echo ("</a>");
//}
   }


   echo ("</td>");
   echo ("</tr>");
   echo ("</table>");
   echo ("<div id=\"espacio\" class=\"espacio\">");
   echo ("</div>");

  // FIN DE OPCIONES GENERALES

/*


$('table tbody tr').hover(function(){  
  $(this).find('td').addClass('hovered');  
}, function(){  
  $(this).find('td').removeClass('hovered');  
});  
*/
?>



<?php



    // ······································································
    //                            Slots y demes
    // ······································································


    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    //          DEME DE LAS PROFUNDIDADES
    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    for ($d = 1; $d <= 3; $d++)
    {

      echo ("<div id=\"espacio\" class=\"espacio\">");
      echo ("</div>");
//      echo ("<table width=\"100%\" style=\"height: 5px;\"><tr><td style=\"height: 5px;\">");
echo ("<div style=\"height: 8px; float: left; text-align: center;\">");
      echo ("<span style=\"font-size: 13px;\">");
      switch ($d)
      {
        case 1:
                if ($lang == 'en')
                {
                  echo ("<b>Abyssal depths deme</b>");
                } else {
                  echo ("<b>Deme de las profundidades</b>");
                }
                $max_l = $jugador_campana->num_slots_deme_profundidades;
		break;
        case 2:
                if ($lang == 'en')
                {
                  echo ("<b>Forest deme</b>");
                } else {
                  echo ("<b>Deme del bosque</b>");
                }
                $max_l = $jugador_campana->num_slots_deme_bosque;
		break;
        case 3:
                if ($lang == 'en')
                {
                  echo ("<b>Volcano deme</b>");
                } else {
                  echo ("<b>Deme del volc&aacute;n</b>");
                }
                $max_l = $jugador_campana->num_slots_deme_volcan;
		break;
      }
      echo ("</span> <a href=\"index.php?catid=".$catid."&idcampana=".$idcampana."&accion=evolucionar_deme&iddeme=".$d."\">");

      ?>
	&nbsp;
	&nbsp;
	<img src="img/evolve1.gif"
		onmouseover="javascript:this.src='img/evolve2.gif';" 
		onmouseout="javascript:this.src='img/evolve1.gif';">&nbsp;
      <?php
      echo ("<span style=\"font-size: 13px;\"><b>");
      if ($lang == 'en')
      {
        echo ("Evolve deme ");
      } else {
        echo ("Evolucionar deme ");
      }
      echo ("</b></span>&nbsp;");
      echo ("<span class=\"goldcoin\">1</span>");
      echo ("<img src=\"img/goldcoin.gif\" style=\"vertical-align:middle;\">");
      echo ("</a></b>");

      echo ("&nbsp;");
      echo ("&nbsp;");
      echo ("&nbsp;");
      echo ("&nbsp;");
echo ("</div>");
echo ("<div style=\"height: 5px; float: right;\">");
      echo ("<span style=\"color: #ffff99;\"><i>");
      if ($lang == 'en') { echo ("Entropy : <b>"); } else { echo ("Entrop&iacute;a : <b>"); }
      echo ($jugador_campana->Entropia($link_r, $idcampana, $idjugador, $d)."%</b>"); // $d es el iddeme
      echo ("</i></span>");
echo ("</div>");

//      echo ("</td></tr></table>");


      echo ("<br/>");
      echo ("<br/>");

//<script>
//$('tbody tr').hover(function(){  
//  $(this).find('td').addClass('hovered');  
//}, function(){  
//  $(this).find('td').removeClass('hovered');  
//});  
//</script>

      echo ("<table id=\"tabla_centro_control".$d."\" class=\"tabla_centro_control\">");
      echo ("<tr style=\"font-size: 14px;\">");
      if ($lang == 'en')
      {
        echo ("<th width=\"30px\" >Slot</th>");
        echo ("<th width=\"30px\">Life</th>");
        echo ("<th width=\"30px\">Mana</th>");
        echo ("<th width=\"50px\">Evaluate</th>");
        echo ("<th width=\"50px\">Score</th>");
        echo ("<th width=\"70px\">Evolve</th>");
        echo ("<th width=\"80px\">Champion</th>");
	echo ("<th width=\"180px\">Name</th>"); 
        echo ("<th width=\"30px\">Detail</th>");
      } else {
        echo ("<th width=\"30px\">Hueco</th>");
        echo ("<th width=\"30px\">Vida</th>");
        echo ("<th width=\"30px\">Mana</th>");
        echo ("<th width=\"60px\">Evaluar</th>");
        echo ("<th width=\"70px\">Puntos</th>");
        echo ("<th width=\"80px\">Evolucionar</th>");
        echo ("<th width=\"80px\">Torneo</th>");
	echo ("<th width=\"180px\">Nombre</th>"); 
        echo ("<th width=\"30px\">Detalle</th>");
      }
      for ($l = 1; $l <= $max_l; $l++)
      {
        if ($l % 2 == 1)
        {
          echo ("<tr style=\"background-color: #111111; font-size: 13px;\">");
        } else {
          echo ("<tr style=\"background-color: #333333; font-size: 13px;\">");
        }
        echo ("<td style=\"font-weight: bold;\">");
        echo $l;
        echo ("</td>");
        echo ("<td style=\"color: #ffaaaa; width: 30px;\">");
        // $d es el deme, $l es el idslot
//        $especimen->SacarDatos($link_r, 1, $l, $idjugador, $idcampana);
        $especimen->SacarDatos($link_r, $d, $l, $idjugador, $idcampana);
        echo ($especimen->puntos_vida);
        echo ("</td>");
        echo ("<td style=\"color: #ccccff; width: 25px;\">");
        echo ($especimen->puntos_magia);
        echo ("</td>");
        echo ("<td>");
//        echo ("<a href=\"index.php?catid=".$catid."&idespecimen=".$especimen->id."&idcampana=".$idcampana."&accion=reevaluar_individuo\">");
        echo ("<a href=\"javascript:reevaluar('ajax_formularios.php', 'accion=reevaluar_individuo&idcampana=".$idcampana."&idespecimen=".$especimen->id."','div_puntuacion_".$especimen->id."')\">");
		?>
	<img src="img/espadas1.gif"
		onmouseover="javascript:this.src='img/espadas2.gif';" 
		onmouseout="javascript:this.src='img/espadas1.gif';"
	>
	<?php
        echo ("</a>");

        echo ("<span class=\"goldcoin\">1</span>");
        echo ("<img src=\"img/goldcoin.gif\" style=\"vertical-align:middle;\">");
        echo ("</td>");
        echo ("<td>");
        echo ("<div id=\"div_puntuacion_".$especimen->id."\">");
        if (($especimen->puntos_evaluacion == null) || ($especimen->puntos_evaluacion == ''))
        {
          if ($lang == 'en')
          {
            echo ("<span class=\"errorstrong\" style=\"font-size: 11px;\">Untested</span>");
          } else {
            echo ("<span class=\"errorstrong\" style=\"font-size: 11px;\">Sin evaluar</span>");
          }
          echo ("</td>");
        } else {
          // Pintamos la puntuacion entre roja y verde
          if ($especimen->puntos_evaluacion > 0)
          {
            $green = ($especimen->puntos_evaluacion * 2) + 130; $red = 110;
          } else {
            $red = (abs($especimen->puntos_evaluacion) * 2) + 130; $green = 110;
          }
          if ($green > 255) { $green = 255; }
          if ($red > 255) { $red = 255; }
          echo ("<span style=\"font-weight: bold; color: rgb(".$red.",".$green.",110)\">");
          if ($lang == 'en')
          {
            echo ($especimen->puntos_evaluacion);
          } else {
            echo ($especimen->puntos_evaluacion);
          }
          echo ("</span>");
          echo ("</td>");
        }
        echo ("</div>"); // Cierra el div de puntuacion

        echo ("<td>");
          // Damos la opcion de evolucionarlo individualmente, ya que esta puntuado
        echo ("<a href=\"index.php?catid=".$catid."&idespecimen=".$especimen->id."&idcampana=".$idcampana."&accion=evolucionar_individuo\">");
		?>
		<img src="img/evolve1.gif"
			onmouseover="javascript:this.src='img/evolve2.gif';" 
			onmouseout="javascript:this.src='img/evolve1.gif';"
		>
		<?php
        echo ("</a>");
        echo ("<span class=\"goldcoin\">1</span>");
        echo ("<img src=\"img/goldcoin.gif\" style=\"vertical-align:middle;\">");
        echo ("</td>");
        echo ("<td align=\"center\" style=\"text-align: center;\">");


        // Posibilidad de unirlos a un torneo, si no estan en ninguno, para GRAN TORNEO
        if ($especimen_torneo->idespecimen == $especimen->id)
        {
          echo ("<img src=\"img/cup.png\">");
        } else {
          echo ("<a class=\"Ntooltip_shop\"
			");
          echo ("href=\"index.php?catid=".$catid."&tipotorneo=0&idespecimen=".$especimen->id."&idcampana=".$idcampana."&accion=apuntar_torneo\">");
          echo ("<img src=\"img/cup-vacio.png\"
		onmouseover=\"javascript:this.src='img/cup.png';\"
		onmouseout=\"javascript:this.src='img/cup-vacio.png';\"
	          >");
          echo ("<span>");
          echo ("<table width=\"100%\"><tr width=\"100%\" style=\"background-color: #333300;\"><td style=\"background-color: #333300;\">");
          if ($lang == 'en')
          {
            echo ("Click to choose this specimen to fight in tournaments");
          } else {
            echo ("Haz click para elegir a este especimen para luchar en torneos");
          }
          echo ("</td></tr></table>");
          echo ("</span>");
          echo ("</a>");
        }

        echo ("&nbsp;");
        echo ("&nbsp;");
        echo ("&nbsp;");
        // Posibilidad de unirlos a un torneo, si no estan en ninguno, para DEME

        switch ($d)
        {
          case 1: $idtorneo_check = $idtorneo_profundidades; $imgyes = "img/cup_blue.png"; $imgno = "img/cup-vacio_blue.png"; break;
          case 2: $idtorneo_check = $idtorneo_bosque; $imgyes = "img/cup_green.png"; $imgno = "img/cup-vacio_green.png"; break;
          case 3: $idtorneo_check = $idtorneo_volcan; $imgyes = "img/cup_red.png"; $imgno = "img/cup-vacio_red.png"; break;
        }
        if ($idtorneo_check == $especimen->id)
        {
          echo ("<img src=\"".$imgyes."\">");

        } else {
          echo ("<a class=\"Ntooltip_shop\"
			");
          echo ("href=\"index.php?catid=".$catid."&tipotorneo=".$d."&idespecimen=".$especimen->id."&idcampana=".$idcampana."&accion=apuntar_torneo\">");
          echo ("<img src=\"".$imgno."\"
		onmouseover=\"javascript:this.src='".$imgyes."';\"
		onmouseout=\"javascript:this.src='".$imgno."';\"
          >");
          echo ("<span>");
          echo ("<table width=\"100%\"><tr width=\"100%\" style=\"background-color: #333300;\"><td style=\"background-color: #333300;\">");
          if ($lang == 'en')
          {
            echo ("Click to choose this specimen to fight in deme tournaments");
          } else {
            echo ("Haz click para elegir a este especimen para luchar en torneos de deme");
          }
          echo ("</td></tr></table>");
          echo ("</span>");
          echo ("</a>");
        }
        echo ("</td>");

        echo ("<td>");
        echo $especimen->silaba1.$especimen->silaba2." ".$especimen->silaba3.$especimen->silabacar;
        echo ("</td>");

        echo ("<td>");
        echo ("<a href=\"index.php?catid=".$catid."&idespecimen=".$especimen->id."&idcampana=".$idcampana."&accion=detalle\">");
        echo ("<img src=\"img/icon_lupa.png\">");
        echo ("</a>");
        echo ("</td>");

        echo ("</tr>");
      }
      echo ("</table>");





// JQUERY PARA QUE PINTE LA ROW
?>
<script>
$('table.tabla_centro_control tr').hover(function(){
  $(this).find('td').addClass('hovered');
}, function(){
  $(this).find('td').removeClass('hovered');
});
</script>

<?php
// JQUERY PARA PINTAR SOLO AL FINAL LA PARTE DEL ENLACE
//  $(document.getElementById('enlace_ocultar')).slideDown();
?>

<script>
  $(document.getElementById('enlace_ocultar')).show();
</script>



<?php

    }


    // -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-
    echo ("</div>");

    // AQUI SE ACABA EL DIV DEL CEMTRO DE MANDO ESTANDAR
    // -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-
    // Y AQUI EMPIEZA EL DIV OCULTO QUE CAMBIARA
    // Lo metemos en un include para no liar la cosa
    include ('centro_mando_atacar.php');

    // -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-



// aqui iban las generales



  }


//</div>
?>
