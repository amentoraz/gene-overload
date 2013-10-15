<?php


  // **************************************
  //   Funcion que muestra lo que sucede en este turno
  // **************************************

  function TurnoAccion($lang, $UAP)
  {
    if ($lang == 'en')
    {
      switch($UAP)
      {
                    case 1: echo("Physical attack"); break;
                    case 2: echo("Block"); break;
                    case 3: echo("Heal"); break;
                    case 4: echo("Use Level 1 ability"); break;
                    case 5: echo("Use Level 2 ability"); break;
                    case 6: echo("Use Level 3 ability"); break;
                    case 7: echo("Use Level 4 ability"); break;
                    case 8: echo("Counterspell"); break;
                    case 9: echo("Ambush"); break;
      }

    } else {
      switch($UAP)
      {
                    case 1: echo("Ataque f&iacute;sico"); break;
                    case 2: echo("Parar"); break;
                    case 3: echo("Curarse"); break;
                    case 4: echo("Utilizar habilidad Nivel 1"); break;
                    case 5: echo("Utilizar habilidad Nivel 2"); break;
                    case 6: echo("Utilizar habilidad Nivel 3"); break;
                    case 7: echo("Utilizar habilidad Nivel 4"); break;
                    case 8: echo("Contrahechizo"); break;
                    case 9: echo("Emboscar"); break;
      }
    }
  }



  // ***********************************************
  //   Pantalla para enfrentar a dos especimenes
  // ***********************************************

//  if (
//       (($accion == 'enfrentar_fight') && ($es_premium == 1)) ||
//       (($accion == 'enfrentar_fight') && ($es_admin == 1))
//     )
  if ($accion == 'enfrentar_fight')
  {

    $jugador_campana = new Jugador_campana();
    $jugador_campana->SacarDatos($link_r, $idjugador, $idcampana);

    $iddeme1 = $_REQUEST['iddeme1'];
    $iddeme2 = $_REQUEST['iddeme2'];
    $idslot1 = $_REQUEST['idslot1'];
    $idslot2 = $_REQUEST['idslot2'];
    if (!is_numeric($iddeme1) ||
        !is_numeric($iddeme2) ||
        !is_numeric($idslot1) ||
        !is_numeric($idslot2)
    )
    {
      if ($lang == 'en')
      {
        echo ("<span class=\"errorsutil\"><b>Error : must choose two specimens</b></span>");
      } else {
        echo ("<span class=\"errorsutil\"><b>Error : debe elegir dos espec&iacute;menes</b></span>");
      }
      $accion = 'enfrentar';
      echo ("<br/>");
    } else {


      // Vamos a ser majos y primero hemos comprobado si han metido bien los especimenes

     // O bien es admin/premium, o...
     if (($es_premium == 1) || ($es_admin == 1))
     {
       $sepuede = 1;
     } else {
       // Si no es premium, puede ver esta seccion 5 veces al dia
       if ($jugador_campana->Detalle_Comprobar_Permitir_Enfrentar($link_w, $idcampana, $idjugador) == true)
       {
         $sepuede = 1;
         echo ("<br/>");
         $detalle_veces_enfrentar = $jugador_campana->detalle_veces_enfrentar;
         if ($detalle_veces_enfrentar == '') { $detalle_veces_enfrentar = 0; }
         if ($lang == 'en')
         {
           echo ("Counting this one, you have used ".($detalle_veces_enfrentar+1)." out of 3 daily accesses to interspecimen test fights.<br/><br/><b> This limit dissapears for ");
           echo ("<a href=\"index.php?catid=52&lang=".$lang."&idcampana=".$idcampana."\">");
           echo ("premium users</b>");
           echo ("</a>.");
         } else {
           echo ("Contando esta vez, has utilizado ".($detalle_veces_enfrentar+1)." de los 3 accesos diarios a la lucha de prueba entre espec&iacute;menes. <br/><br/><b>Este l&iacute;mite desaparece para ");
           echo ("<a href=\"index.php?catid=52&lang=".$lang."&idcampana=".$idcampana."\">");
           echo ("usuarios premium</b>");
           echo ("</a>.");
         }
         echo ("<br/>");
         echo ("<br/>");
         echo ("<br/>");
       } else {
         $sepuede = 0;
         // Y EL MENSAJE DE QUE PETA
         if ($lang == 'en')
         {
           echo ("<p class=\"error\">Error: You've already used the 3 daily fights you are allowed if you are not a premium user.</p>");
           echo ("<br/>");
           echo ("<p class=\"error\">Please come back later or acquire a ");
           echo ("<a href=\"index.php?catid=52&lang=".$lang."&idcampana=".$idcampana."\">");
           echo ("premium account");
           echo ("</a>.");
         } else {
           echo ("<p class=\"error\">Error: Ya has utilizado las 3 luchas diarias que se permiten si no eres un usuario premium.</p>");
           echo ("<br/>");
           echo ("<p class=\"error\">Por favor, vuelve m&aacute;s tarde o adquiere una  ");
           echo ("<a href=\"index.php?catid=52&lang=".$lang."&idcampana=".$idcampana."\">");
           echo ("cuenta premium");
           echo ("</a>.");
         }
       }
     }
     // Si has pasado el control...
     if ($sepuede == 1)
     {






      echo ("<br/>");
      echo ("<center>");
      if ($lang == 'en')
      {
        echo ("<span style=\"color: #ffdb00; font-size: 15px;\"><b>Test combat</b></span>");
      } else {
        echo ("<span style=\"color: #ffbb55; font-size: 15px;\"><b>Combate de prueba</b></span>");
      }
      echo ("</center>");
      echo ("<br/>");
      echo ("<br/>");

      // Vamos a enfrentar a los dos especimenes...
      $debug_mode = $jugador_campana->debug_mode;
      $total_especimenes = $jugador_campana->num_slots_deme_profundidades +
                        $jugador_campana->num_slots_deme_bosque +
                        $jugador_campana->num_slots_deme_volcan;

      $combate = new Combate($debug_mode);
      $especimen = new Especimen();
      $arbol = new Arbol();

      $especimen1 = $especimen->Obtener_Por_Deme_Slot($link_r, $iddeme1, $idslot1, $idcampana, $idjugador);
      $especimen2 = $especimen->Obtener_Por_Deme_Slot($link_r, $iddeme2, $idslot2, $idcampana, $idjugador);

      $arbol1 = $arbol->Desglosar($especimen1['arbol'], $especimen1['niveles_arbol']);
      $arbol2 = $arbol->Desglosar($especimen2['arbol'], $especimen2['niveles_arbol']);

//      $arrayresultado = $combate->Puntuar($array_evaluar[$j],$arrayunico[$n], $arbol1, $arbol2);
      $turnos_combate_premium = 20;
      $arrayresultado = $combate->Puntuar($especimen1, $especimen2, $arbol1, $arbol2, $turnos_combate_premium, 1);

      echo ("<p style=\"color:#ffcb00\">");
      if ($lang == 'en')
      {
        echo ("Fight between ");
        echo ("<b>".$especimen1['silaba1'].$especimen1['silaba2']." ".$especimen1['silaba3'].$especimen1['silabacar']."</b>");
        echo (", the specimen in slot ".$idslot1." in the ");
        switch($iddeme1) {
          case 1: echo ("Abyssal depths deme "); break;
          case 2: echo ("Forest deme "); break;
          case 3: echo ("Volcano deme "); break;
        }
        echo ("and ");
        echo ("<b>".$especimen2['silaba1'].$especimen2['silaba2']." ".$especimen2['silaba3'].$especimen2['silabacar']."</b>");
        echo (", the specimen in slot ".$idslot2." in the ");
        switch($iddeme2) {
          case 1: echo ("Abyssal depths deme."); break;
          case 2: echo ("Forest deme."); break;
          case 3: echo ("Volcano deme."); break;
        }
      } else {
        echo ("Lucha entre ");
        echo ("<b>".$especimen1['silaba1'].$especimen1['silaba2']." ".$especimen1['silaba3'].$especimen1['silabacar']."</b>");
        echo (", el especimen del hueco ".$idslot1." en el  ");
        switch($iddeme1) {
          case 1: echo ("deme de las profundidades "); break;
          case 2: echo ("deme del bosque "); break;
          case 3: echo ("deme del volc&aacute;n "); break;
        }
        echo ("y ");
        echo ("<b>".$especimen2['silaba1'].$especimen2['silaba2']." ".$especimen2['silaba3'].$especimen2['silabacar']."</b>");
//        echo $especimen2['silaba1'].$especimen2['silaba2']." ".$especimen2['silaba3'].$especimen2['silabacar'];
        echo (", el especimen del hueco ".$idslot2." en el ");
        switch($iddeme2) {
          case 1: echo ("deme de las profundidades."); break;
          case 2: echo ("deme del bosque."); break;
          case 3: echo ("deme del volc&aacute;n."); break;
        }
      }
      echo ("</p>");
      echo ("<br/>");
      echo ("<br/>");

      if ($lang == 'en')
      {
        echo ("<table class=\"tabla_centro_control\">");
        echo ("<tr>");
        echo ("<th width=\"30px\">");
        echo ("Turn");
        echo ("</th>");
        echo ("<th colspan=\"4\">");
//        echo ("First fighter");
        echo $especimen1['silaba1'].$especimen1['silaba2']." ".$especimen1['silaba3'].$especimen1['silabacar'];
        echo ("</th>");
        echo ("<th colspan=\"4\">");
//        echo ("Second fighter");
        echo $especimen2['silaba1'].$especimen2['silaba2']." ".$especimen2['silaba3'].$especimen2['silabacar'];
        echo ("</th>");
        echo ("</tr>");
        echo ("<tr>"); // FUCKING TR YO
        echo ("<th width=\"30px\">");
        echo ("</th>");
        echo ("<th width=\"70px\">");
        echo ("Status");
        echo ("</th>");
        echo ("<th width=\"30px\">");
        echo ("Life");
        echo ("</th>");
        echo ("<th width=\"50px\">");
        echo ("Mana");
        echo ("</th>");
        echo ("<th width=\"150px\">");
        echo ("Turn action");
        echo ("</th>");
        echo ("<th width=\"70px\">");
        echo ("Status");
        echo ("</th>");
        echo ("<th width=\"30px\">");
        echo ("Life");
        echo ("</th>");
        echo ("<th width=\"30px\">");
        echo ("Mana");
        echo ("</th>");
        echo ("<th width=\"150px\">");
        echo ("Turn action");
        echo ("</th>");
        echo ("</tr>");
      } else {
        echo ("<table class=\"tabla_centro_control\">");
        echo ("<tr>");
        echo ("<th width=\"30px\">");
        echo ("Paso");
        echo ("</th>");
        echo ("<th colspan=\"4\">");
//        echo ("Primer contendiente");
        echo $especimen1['silaba1'].$especimen1['silaba2']." ".$especimen1['silaba3'].$especimen1['silabacar'];
        echo ("</th>");
        echo ("<th colspan=\"4\">");
//        echo ("Segundo contendiente");
        echo $especimen2['silaba1'].$especimen2['silaba2']." ".$especimen2['silaba3'].$especimen2['silabacar'];
        echo ("</th>");
        echo ("</tr>");
        echo ("<tr>"); // FUCKING TR YO
        echo ("<th width=\"30px\">");
        echo ("</th>");
        echo ("<th width=\"70px\">");
        echo ("Estado");
        echo ("</th>");
        echo ("<th width=\"30px\">");
        echo ("Vida");
        echo ("</th>");
        echo ("<th width=\"30px\">");
        echo ("Mana");
        echo ("</th>");
        echo ("<th width=\"150px\">");
        echo ("Acci&oacute;n");
        echo ("</th>");
        echo ("<th width=\"70px\">");
        echo ("Estado");
        echo ("</th>");
        echo ("<th width=\"30px\">");
        echo ("Vida");
        echo ("</th>");
        echo ("<th width=\"30px\">");
        echo ("Mana");
        echo ("</th>");
        echo ("<th width=\"150px\">");
        echo ("Acci&oacute;n");
        echo ("</th>");
        echo ("</tr>");
      }
      echo ("<tr>");
      echo ("<td><b>0</b></td>");
      echo ("<td></td>"); // Estado
      echo ("<td style=\"color: #ffaaaa; width: 30px;\">".$combate->array_debug_premium[0]['PV_1']."</td>");
      echo ("<td style=\"color: #ccccff; width: 30px;\">".$combate->array_debug_premium[0]['PM_1']."</td>");
      echo ("<td></td>");
      echo ("<td></td>"); // Estado
      echo ("<td style=\"color: #ffaaaa; width: 30px;\">".$combate->array_debug_premium[0]['PV_2']."</td>");
      echo ("<td style=\"color: #ccccff; width: 30px;\">".$combate->array_debug_premium[0]['PM_2']."</td>");
      echo ("<td></td>");
      echo ("</tr>");


      // ********************************* AHORA REPASAMOS TODOS LOS TURNOS DE COMBATE ****************************
      for ($i = 1; $i <= $turnos_combate_premium; $i++)
      {
        if (($i % 2) == 1)
        {
          echo ("<tr style=\"background-color: #111111; font-size: 13px;\">");
        } else {
          echo ("<tr style=\"background-color: #333333; font-size: 13px;\">");
        }
        echo ("<td><b>");
        echo $i;
        echo ("</b></td>");
        echo ("<td>");  // Estado del primer contendiente
         // IMPRIME SI ESTA EMBOSCADO
         if ($combate->array_debug_premium[$i]['emboscado_1'] > 0)
         {
                echo ("<a href=\"#\"
                class=\"Ntooltip\"
                >");
                echo ("<img src=\"img/estado_emboscado.png\" style=\"vertical-align:middle;\">");
                echo ("<span style=\"width: 100px;\">");
                if ($lang == 'en')
                {
                   echo ("<table width=\"100%\" class=\"tooltip_simulador\"><tr><td>");
                   echo ("Ambushed");
                   echo ("</td></tr></table>");
                } else {
                   echo ("<table width=\"100%\" class=\"tooltip_simulador\"><tr><td>");
                   echo ("Emboscado");
                   echo ("</td></tr></table>");
                }
                echo ("</span>");
                echo ("</a>");
         }
         // IMPRIME SI ESTA RAPIDEZ
         if ($combate->array_debug_premium[$i]['rapidez_1'] > 0)
         {
                echo ("<a href=\"#\"
                class=\"Ntooltip\"
                >");
                echo ("<img src=\"img/estado_rapidez.png\" style=\"vertical-align:middle;\">");
                echo ("<span style=\"width: 100px;\">");
                if ($lang == 'en')
                {
                   echo ("<table width=\"100%\" class=\"tooltip_simulador\"><tr><td>");
                   echo ("Fastened");
                   echo ("</td></tr></table>");
                } else {
                   echo ("<table width=\"100%\" class=\"tooltip_simulador\"><tr><td>");
                   echo ("Acelerado");
                   echo ("</td></tr></table>");
                }
                echo ("</span>");
                echo ("</a>");
         }
         // IMPRIME SI REFLEJANDO
         if ($combate->array_debug_premium[$i]['reflejar_1'] > 0)
         {
                echo ("<a href=\"#\"
                class=\"Ntooltip\"
                >");
                echo ("<img src=\"img/estado_reflejar.png\" style=\"vertical-align:middle;\">");
                echo ("<span style=\"width: 100px;\">");
                if ($lang == 'en')
                {
                   echo ("<table width=\"100%\" class=\"tooltip_simulador\"><tr><td>");
                   echo ("Mirroring");
                   echo ("</td></tr></table>");
                } else {
                   echo ("<table width=\"100%\" class=\"tooltip_simulador\"><tr><td>");
                   echo ("Reflejando");
                   echo ("</td></tr></table>");
                }
                echo ("</span>");
                echo ("</a>");
         }
         // IMPRIME SI ESTA ARDIENTE
         if ($combate->array_debug_premium[$i]['miembros_ardientes_1'] > 0)
         {
                echo ("<a href=\"#\"
                class=\"Ntooltip\"
                >");
                echo ("<img src=\"img/estado_ardiente.png\" style=\"vertical-align:middle;\">");
                echo ("<span style=\"width: 140px;\">");
                if ($lang == 'en')
                {
                   echo ("<table width=\"100%\" class=\"tooltip_simulador\"><tr><td>");
                   echo ("Burning members");
                   echo ("</td></tr></table>");
                } else {
                   echo ("<table width=\"100%\" class=\"tooltip_simulador\"><tr><td>");
                   echo ("Miembros ardientes");
                   echo ("</td></tr></table>");
                }
                echo ("</span>");
                echo ("</a>");
         }
         // IMPRIME SI ESTA MALDITO
         if ($combate->array_debug_premium[$i]['maldito_1'] > 0)
         {
                echo ("<a href=\"#\"
                class=\"Ntooltip\"
                >");
                echo ("<img src=\"img/estado_maldito.png\" style=\"vertical-align:middle;\">");
                echo ("<span style=\"width: 100px;\">");
                if ($lang == 'en')
                {
                   echo ("<table width=\"100%\" class=\"tooltip_simulador\"><tr><td>");
                   echo ("Cursed");
                   echo ("</td></tr></table>");
                } else {
                   echo ("<table width=\"100%\" class=\"tooltip_simulador\"><tr><td>");
                   echo ("Maldito");
                   echo ("</td></tr></table>");
                }
                echo ("</span>");
                echo ("</a>");
         }
         // IMPRIME SI ESTA INVULNERABLE
         if ($combate->array_debug_premium[$i]['invulnerable_1'] > 0)
         {
                echo ("<a href=\"#\"
                class=\"Ntooltip\"
                >");
                echo ("<img src=\"img/estado_invulnerable.png\" style=\"vertical-align:middle;\">");
                echo ("<span style=\"width: 100px;\">");
                if ($lang == 'en')
                {
                   echo ("<table width=\"100%\" class=\"tooltip_simulador\"><tr><td>");
                   echo ("Invulnerable");
                   echo ("</td></tr></table>");
                } else {
                   echo ("<table width=\"100%\" class=\"tooltip_simulador\"><tr><td>");
                   echo ("Invulnerable");
                   echo ("</td></tr></table>");
                }
                echo ("</span>");
                echo ("</a>");
         }
         // IMPRIME SI PARANDO
         if ($combate->array_debug_premium[$i]['parar_1'] > 0)
         {
                echo ("<a href=\"#\"
                class=\"Ntooltip\"
                >");
                echo ("<img src=\"img/estado_parar.png\" style=\"vertical-align:middle;\">");
                echo ("<span style=\"width: 100px;\">");
                if ($lang == 'en')
                {
                   echo ("<table width=\"100%\" class=\"tooltip_simulador\"><tr><td>");
                   echo ("Parrying");
                   echo ("</td></tr></table>");
                } else {
                   echo ("<table width=\"100%\" class=\"tooltip_simulador\"><tr><td>");
                   echo ("Parando");
                   echo ("</td></tr></table>");
                }
                echo ("</span>");
                echo ("</a>");
         }
         // IMPRIME SI CONTRAHECHIZO
         if ($combate->array_debug_premium[$i]['contrahechizo_1'] > 0)
         {
                echo ("<a href=\"#\"
                class=\"Ntooltip\"
                >");
                echo ("<img src=\"img/estado_contrahechizo.png\" style=\"vertical-align:middle;\">");
                echo ("<span style=\"width: 110px;\">");
                if ($lang == 'en')
                {
                   echo ("<table width=\"100%\" class=\"tooltip_simulador\"><tr><td>");
                   echo ("Counterspell");
                   echo ("</td></tr></table>");
                } else {
                   echo ("<table width=\"100%\" class=\"tooltip_simulador\"><tr><td>");
                   echo ("Contrahechizo");
                   echo ("</td></tr></table>");
                }
                echo ("</span>");
                echo ("</a>");
         }
         // IMPRIME SI ESTA ATURDIDO
         if ($combate->array_debug_premium[$i]['aturdido_1'] > 0)
         {
                echo ("<a href=\"#\"
                class=\"Ntooltip\"
                >");
                echo ("<img src=\"img/estado_aturdido.png\" style=\"vertical-align:middle;\">");
                echo ("<span style=\"width: 100px;\">");
                if ($lang == 'en')
                {
                   echo ("<table width=\"100%\" class=\"tooltip_simulador\"><tr><td>");
                   echo ("Stunned");
                   echo ("</td></tr></table>");
                } else {
                   echo ("<table width=\"100%\" class=\"tooltip_simulador\"><tr><td>");
                   echo ("Aturdido");
                   echo ("</td></tr></table>");
                }
                echo ("</span>");
                echo ("</a>");
         }
         // IMPRIME SI ATURDIDO Y SIN PODER PARAR
         if ($combate->array_debug_premium[$i]['aturdido_sin_parar_1'] > 0)
         {
                echo ("<a href=\"#\"
                class=\"Ntooltip\"
                >");
                echo ("<img src=\"img/estado_aturdido_sin_parar.png\" style=\"vertical-align:middle;\">");
                echo ("<span style=\"width: 200px;\">");
                if ($lang == 'en')
                {
                   echo ("<table width=\"100%\" class=\"tooltip_simulador\"><tr><td>");
                   echo ("Stunned and unable to parry");
                   echo ("</td></tr></table>");
                } else {
                   echo ("<table width=\"100%\" class=\"tooltip_simulador\"><tr><td>");
                   echo ("Aturdido y sin poder parar");
                   echo ("</td></tr></table>");
                }
                echo ("</span>");
                echo ("</a>");
         }
        echo ("</td>"); // Estado
        echo ("<td style=\"color: #ffaaaa;\">");
        echo $combate->array_debug_premium[$i]['PV_1'];
        echo ("</td>");
        echo ("<td style=\"color: #ccccff;\">");
        echo $combate->array_debug_premium[$i]['PM_1'];
        echo ("</td>");
        echo ("<td>");
        if (($i % 2) == 1)
        {
//          TurnoAccion($lang, $combate->array_debug_premium[$i]['UAP_1']);

            // DECISION TOMADA FINAL, Y CON TOOLTIP EN LOS NODOS
            echo ("<a href=\"#\"
               class=\"Ntooltip\"
                >");
            TurnoAccion($lang, $combate->array_debug_premium[$i]['UAP_1']);
            echo ("<span style=\"width: 240px; color: #ffffff;\">");
            if ($lang == 'en') { echo ("Node decission path :"); } else { echo ("Recorrido nodos de decisi&oacute;n : "); }
            echo ("<br/>");
            $nodo_actual = $combate->array_debug_premium[$i]['hoja1'];
            $m = 1;
            $arrayplay[$m] = $nodo_actual;
            for ($l = $jugador_campana->niveles_arbol; $l > 1; $l--)
            {
              $m++;
              $nodo_actual--;
              if (($nodo_actual % 2) == 1)
              {
                $nodo_actual = (($nodo_actual - 1) / 2);
              } else {
                $nodo_actual = (($nodo_actual) / 2);
              }
              $arrayplay[$m] = $nodo_actual;
            }
            for ($m = $jugador_campana->niveles_arbol; $m > 0; $m--)
            {
              if ($m != $jugador_campana->niveles_arbol) { echo ("->"); }
              echo $arrayplay[$m];
            }
            echo ("</span>");
            echo ("</a>");


        } else {
          echo ("-");
        }
        echo ("</td>");
        // ****************** SEGUNDO CONTENDIENTE *****************
        echo ("<td>"); // Estado
         // IMPRIME SI ESTA EMBOSCADO
         if ($combate->array_debug_premium[$i]['emboscado_2'] > 0)
         {
                echo ("<a href=\"#\"
                class=\"Ntooltip\"
                >");
                echo ("<img src=\"img/estado_emboscado.png\" style=\"vertical-align:middle;\">");
                echo ("<span style=\"width: 100px;\">");
                if ($lang == 'en')
                {
                   echo ("<table width=\"100%\" class=\"tooltip_simulador\"><tr><td>");
                   echo ("Ambushed");
                   echo ("</td></tr></table>");
                } else {
                   echo ("<table width=\"100%\" class=\"tooltip_simulador\"><tr><td>");
                   echo ("Emboscado");
                   echo ("</td></tr></table>");
                }
                echo ("</span>");
                echo ("</a>");

//           echo ("e");
         }
         // IMPRIME SI ESTA RAPIDEZ
         if ($combate->array_debug_premium[$i]['rapidez_2'] > 0)
         {
                echo ("<a href=\"#\"
                class=\"Ntooltip\"
                >");
                echo ("<img src=\"img/estado_rapidez.png\" style=\"vertical-align:middle;\">");
                echo ("<span style=\"width: 100px;\">");
                if ($lang == 'en')
                {
                   echo ("<table width=\"100%\" class=\"tooltip_simulador\"><tr><td>");
                   echo ("Fastened");
                   echo ("</td></tr></table>");
                } else {
                   echo ("<table width=\"100%\" class=\"tooltip_simulador\"><tr><td>");
                   echo ("Acelerado");
                   echo ("</td></tr></table>");
                }
                echo ("</span>");
                echo ("</a>");
         }
         // IMPRIME SI ESTA ARDIENTE
         if ($combate->array_debug_premium[$i]['miembros_ardientes_2'] > 0)
         {
                echo ("<a href=\"#\"
                class=\"Ntooltip\"
                >");
                echo ("<img src=\"img/estado_ardiente.png\" style=\"vertical-align:middle;\">");
                echo ("<span style=\"width: 140px;\">");
                if ($lang == 'en')
                {
                   echo ("<table width=\"100%\" class=\"tooltip_simulador\"><tr><td>");
                   echo ("Burning members");
                   echo ("</td></tr></table>");
                } else {
                   echo ("<table width=\"100%\" class=\"tooltip_simulador\"><tr><td>");
                   echo ("Miembros ardientes");
                   echo ("</td></tr></table>");
                }
                echo ("</span>");
                echo ("</a>");
         }
         // IMPRIME SI REFLEJANDO
         if ($combate->array_debug_premium[$i]['reflejar_2'] > 0)
         {
                echo ("<a href=\"#\"
                class=\"Ntooltip\"
                >");
                echo ("<img src=\"img/estado_reflejar.png\" style=\"vertical-align:middle;\">");
                echo ("<span style=\"width: 100px;\">");
                if ($lang == 'en')
                {
                   echo ("<table width=\"100%\" class=\"tooltip_simulador\"><tr><td>");
                   echo ("Mirroring");
                   echo ("</td></tr></table>");
                } else {
                   echo ("<table width=\"100%\" class=\"tooltip_simulador\"><tr><td>");
                   echo ("Reflejando");
                   echo ("</td></tr></table>");
                }
                echo ("</span>");
                echo ("</a>");
         }
         // IMPRIME SI ESTA MALDITO
         if ($combate->array_debug_premium[$i]['maldito_2'] > 0)
         {
                echo ("<a href=\"#\"
                class=\"Ntooltip\"
                >");
                echo ("<img src=\"img/estado_maldito.png\" style=\"vertical-align:middle;\">");
                echo ("<span style=\"width: 100px;\">");
                if ($lang == 'en')
                {
                   echo ("<table width=\"100%\" class=\"tooltip_simulador\"><tr><td>");
                   echo ("Cursed");
                   echo ("</td></tr></table>");
                } else {
                   echo ("<table width=\"100%\" class=\"tooltip_simulador\"><tr><td>");
                   echo ("Maldito");
                   echo ("</td></tr></table>");
                }
                echo ("</span>");
                echo ("</a>");
         }
         // IMPRIME SI ESTA INVULNERABLE
         if ($combate->array_debug_premium[$i]['invulnerable_2'] > 0)
         {
                echo ("<a href=\"#\"
                class=\"Ntooltip\"
                >");
                echo ("<img src=\"img/estado_invulnerable.png\" style=\"vertical-align:middle;\">");
                echo ("<span style=\"width: 100px;\">");
                if ($lang == 'en')
                {
                   echo ("<table width=\"100%\" class=\"tooltip_simulador\"><tr><td>");
                   echo ("Invulnerable");
                   echo ("</td></tr></table>");
                } else {
                   echo ("<table width=\"100%\" class=\"tooltip_simulador\"><tr><td>");
                   echo ("Invulnerable");
                   echo ("</td></tr></table>");
                }
                echo ("</span>");
                echo ("</a>");
         }
         // IMPRIME SI PARANDO
         if ($combate->array_debug_premium[$i]['parar_2'] > 0)
         {
                echo ("<a href=\"#\"
                class=\"Ntooltip\"
                >");
                echo ("<img src=\"img/estado_parar.png\" style=\"vertical-align:middle;\">");
                echo ("<span style=\"width: 100px;\">");
                if ($lang == 'en')
                {
                   echo ("<table width=\"100%\" class=\"tooltip_simulador\"><tr><td>");
                   echo ("Parrying");
                   echo ("</td></tr></table>");
                } else {
                   echo ("<table width=\"100%\" class=\"tooltip_simulador\"><tr><td>");
                   echo ("Parando");
                   echo ("</td></tr></table>");
                }
                echo ("</span>");
                echo ("</a>");
         }
         // IMPRIME SI CONTRAHECHIZO
         if ($combate->array_debug_premium[$i]['contrahechizo_2'] > 0)
         {
                echo ("<a href=\"#\"
                class=\"Ntooltip\"
                >");
                echo ("<img src=\"img/estado_contrahechizo.png\" style=\"vertical-align:middle;\">");
                echo ("<span style=\"width: 110px;\">");
                if ($lang == 'en')
                {
                   echo ("<table width=\"100%\" class=\"tooltip_simulador\"><tr><td>");
                   echo ("Counterspell");
                   echo ("</td></tr></table>");
                } else {
                   echo ("<table width=\"100%\" class=\"tooltip_simulador\"><tr><td>");
                   echo ("Contrahechizo");
                   echo ("</td></tr></table>");
                }
                echo ("</span>");
                echo ("</a>");
         }
         // IMPRIME SI ESTA ATURDIDO
         if ($combate->array_debug_premium[$i]['aturdido_2'] > 0)
         {
                echo ("<a href=\"#\"
                class=\"Ntooltip\"
                >");
                echo ("<img src=\"img/estado_aturdido.png\" style=\"vertical-align:middle;\">");
                echo ("<span style=\"width: 100px;\">");
                if ($lang == 'en')
                {
                   echo ("<table width=\"100%\" class=\"tooltip_simulador\"><tr><td>");
                   echo ("Stunned");
                   echo ("</td></tr></table>");
                } else {
                   echo ("<table width=\"100%\" class=\"tooltip_simulador\"><tr><td>");
                   echo ("Aturdido");
                   echo ("</td></tr></table>");
                }
                echo ("</span>");
                echo ("</a>");
         }
         // IMPRIME SI ATURDIDO Y SIN PODER PARAR
         if ($combate->array_debug_premium[$i]['aturdido_sin_parar_2'] > 0)
         {
                echo ("<a href=\"#\"
                class=\"Ntooltip\"
                >");
                echo ("<img src=\"img/estado_aturdido_sin_parar.png\" style=\"vertical-align:middle;\">");
                echo ("<span style=\"width: 200px;\">");
                if ($lang == 'en')
                {
                   echo ("<table width=\"100%\" class=\"tooltip_simulador\"><tr><td>");
                   echo ("Stunned and unable to parry");
                   echo ("</td></tr></table>");
                } else {
                   echo ("<table width=\"100%\" class=\"tooltip_simulador\"><tr><td>");
                   echo ("Aturdido y sin poder parar");
                   echo ("</td></tr></table>");
                }
                echo ("</span>");
                echo ("</a>");
         }
        echo ("</td>");
        echo ("<td style=\"color: #ffaaaa; width: 30px;\">");
        echo $combate->array_debug_premium[$i]['PV_2'];
        echo ("</td>");
        echo ("<td style=\"color: #ccccff; width: 25px;\">");
        echo $combate->array_debug_premium[$i]['PM_2'];
        echo ("</td>");
        echo ("<td>");
        if (($i % 2) == 0)
        {
/*
                echo ("<a href=\"#\"
                class=\"Ntooltip\"
                >");
                echo ("<img src=\"img/estado_aturdido_sin_parar.png\" style=\"vertical-align:middle;\">");
                echo ("<span style=\"width: 200px;\">");
                if ($lang == 'en')
                {
                   echo ("<table width=\"100%\" class=\"tooltip_simulador\"><tr><td>");
                   echo ("Stunned and unable to parry");
                   echo ("</td></tr></table>");
                } else {
                   echo ("<table width=\"100%\" class=\"tooltip_simulador\"><tr><td>");
                   echo ("Aturdido y sin poder parar");
                   echo ("</td></tr></table>");
                }
                echo ("</span>");
                echo ("</a>");
*/

            // DECISION TOMADA FINAL, Y CON TOOLTIP EN LOS NODOS
            echo ("<a href=\"#\"
               class=\"Ntooltip\"
                >");
            TurnoAccion($lang, $combate->array_debug_premium[$i]['UAP_2']);
            echo ("<span style=\"width: 240px; color: #ffffff;\">");
            if ($lang == 'en') { echo ("Node decission path :"); } else { echo ("Recorrido nodos de decisi&oacute;n : "); }
            echo ("<br/>");
            $nodo_actual = $combate->array_debug_premium[$i]['hoja2'];
            $m = 1;
            $arrayplay[$m] = $nodo_actual;
            for ($l = $jugador_campana->niveles_arbol; $l > 1; $l--)
            {
              $m++;
              $nodo_actual--;
              if (($nodo_actual % 2) == 1)
              {
                $nodo_actual = (($nodo_actual - 1) / 2);
              } else {
                $nodo_actual = (($nodo_actual) / 2);
              }
              $arrayplay[$m] = $nodo_actual;
            }
            for ($m = $jugador_campana->niveles_arbol; $m > 0; $m--)
            {
              if ($m != $jugador_campana->niveles_arbol) { echo ("->"); }
              echo $arrayplay[$m];
            }
            echo ("</span>");
            echo ("</a>");

//          echo $combate->array_debug_premium[$i]['UAP_2'];
        } else {
          echo ("-");
        }
        echo ("</td>");
        echo ("</tr>");
      }
      echo ("</table>");

      echo ("<br/>");
      echo ("<br/>");

      // Y ahora mostramos los resultados
      echo ("<p style=\"font-size: 13px;
				\">");
      if ($lang == 'en')
      {
        echo ("The result after ".$turnos_combate_premium." combat turns is  ");
        if ($combate->array_debug_premium['resultado'] == 0) { echo ("<b>a draw</b>. Remember, though, that real world combats are much longer"); }
        if ($combate->array_debug_premium['resultado'] == 1) { echo ("<b>first specimen wins</b>"); }
        if ($combate->array_debug_premium['resultado'] == 2) { echo ("<b>second specimen wins</b>"); }
        if ($combate->array_debug_premium['resultado'] == -1) { echo ("<b>both specimens died</b>"); }
        echo ("<br/>");
        echo ("<br/>");
        echo ("First player would have earned <b>".$combate->array_debug_premium['puntos1']."</b> points.");
        echo ("<br/>");
        echo ("<br/>");
        echo ("Second player would have earned <b>".$combate->array_debug_premium['puntos2']."</b> points.");


echo ("<br/>");
echo ("<br/>");
echo ("<br/>");
echo ("<b>Please note:</b> Status reflects the buffs that affect the specimen at the <b>end</b> of the current turn.</b> ");

        echo ("<br/>");

        echo ("<br/>");
        echo ("<br/>");
        echo ("<br/>");
//        echo ("<center>");
        echo ("<a href=\"index.php?catid=".$catid."&accion=enfrentar_fight&idcampana=".$idcampana."&iddeme1=".$iddeme2."&iddeme2=".$iddeme1.
		"&idslot1=".$idslot2."&idslot2=".$idslot1."\">");
        ?>
        <img src="img/espadas1.gif"
                onmouseover="javascript:this.src='img/espadas2.gif';" 
                onmouseout="javascript:this.src='img/espadas1.gif';"
        >
        <?php
        echo ("<b>Click to play a test fight between the same specimens but swapping their positions</b>");
        echo ("</a>");
//        echo ("</center>");

        echo ("<br/>");
        echo ("<br/>");
//        echo ("<center>");
        echo ("<a href=\"index.php?catid=".$catid."&accion=enfrentar&idcampana=".$idcampana."\">");
        ?>
        <img src="img/espadas1.gif"
                onmouseover="javascript:this.src='img/espadas2.gif';" 
                onmouseout="javascript:this.src='img/espadas1.gif';"
        >
        <?php
        echo ("<b>Click to play a different test fight</b>");
        echo ("</a>");
//        echo ("</center>");
        echo ("<br/>");

      } else {
        echo ("El resultado despu&eacute;s de ".$turnos_combate_premium." turnos de combate es ");
        if ($combate->array_debug_premium['resultado'] == 0) { echo ("<b>un empate</b>. Recuerda, sin embargo, que los combates reales son mucho m&aacute;s largos"); }
        if ($combate->array_debug_premium['resultado'] == 1) { echo ("que <b>gana el primer contendiente</b>"); }
        if ($combate->array_debug_premium['resultado'] == 2) { echo ("que <b>gana el segundo contendiente</b>"); }
        if ($combate->array_debug_premium['resultado'] == -1) { echo ("que <b>ambos espec&iacute;menes murieron</b>"); }
        echo ("<br/>");
        echo ("<br/>");
        echo ("El primer jugador habr&iacute;a obtenido <b>".$combate->array_debug_premium['puntos1']."</b> puntos.");
        echo ("<br/>");
        echo ("<br/>");
        echo ("El segundo jugador habr&iacute;a obtenido <b>".$combate->array_debug_premium['puntos2']."</b> puntos.");

echo ("<br/>");
echo ("<br/>");
echo ("<br/>");
echo ("<b>Nota importante:</b> El estado refleja lo que afecta al especimen al <b>final</b> del turno actual.</b> ");

        echo ("<br/>");

        echo ("<br/>");
        echo ("<br/>");
        echo ("<br/>");
//        echo ("<center>");
        echo ("<a href=\"index.php?catid=".$catid."&accion=enfrentar_fight&idcampana=".$idcampana."&iddeme1=".$iddeme2."&iddeme2=".$iddeme1.
		"&idslot1=".$idslot2."&idslot2=".$idslot1."\">");
        ?>
        <img src="img/espadas1.gif"
                onmouseover="javascript:this.src='img/espadas2.gif';" 
                onmouseout="javascript:this.src='img/espadas1.gif';"
        >
        <?php
        echo ("<b>Haz click para confrontar a los mismos pero invirtiendo sus posiciones</b>");
        echo ("</a>");
//        echo ("</center>");

        echo ("<br/>");
        echo ("<br/>");
//        echo ("<center>");
        echo ("<a href=\"index.php?catid=".$catid."&accion=enfrentar&idcampana=".$idcampana."\">");
        ?>
        <img src="img/espadas1.gif"
                onmouseover="javascript:this.src='img/espadas2.gif';" 
                onmouseout="javascript:this.src='img/espadas1.gif';"
        >
        <?php
        echo ("<b>Haz click para confrontar a otros dos espec&iacute;menes distintos</b>");
        echo ("</a>");
//        echo ("</center>");
        echo ("<br/>");

      }
      echo ("</p>");



    }  // Fin del if de sepuede
   }  // Fin del if de si se eligieron los correctos
  }



  // ***********************************************
  //   Pantalla para enfrentar a dos especimenes
  // ***********************************************

//  if (
//       (($accion == 'enfrentar') && ($es_premium == 1)) ||
//       (($accion == 'enfrentar') && ($es_admin == 1))
//     )
  if ($accion == 'enfrentar')
  {

   if (($es_premium != 1) && ($es_admin != 1))
   {
    // Si no es premium, puede ver esta seccion 3 veces al dia
    $jugador_campana_x = new Jugador_campana();
    $jugador_campana_x->SacarDatos($link_r, $idjugador, $idcampana);

    $detalle_veces = $jugador_campana_x->Detalle_Comprobar_Permitir_Enfrentar_SinTocar($link_w, $idcampana, $idjugador);
    if ($detalle_veces >= 0)
    {
      echo ("<br/>");
      echo ("<p style=\"color: #ffff99\">");
      if ($lang == 'en')
      {
        echo ("You have used ".($detalle_veces)." out of 3 daily accesses to interspecimen test fights. <br/><br/><b>This limit dissapears for</b> ");
        echo ("<a href=\"index.php?catid=52&lang=".$lang."&idcampana=".$idcampana."\">");
        echo ("<b>premium users</b>");
        echo ("</a>.");
      } else {
        echo ("Has utilizado ".($detalle_veces)." de los 3 accesos diarios a la lucha de prueba entre espec&iacute;menes. <br/><br/><b>Este l&iacute;mite desaparece para</b> ");
        echo ("<a href=\"index.php?catid=52&lang=".$lang."&idcampana=".$idcampana."\">");
        echo ("<b>usuarios premium</b>");
        echo ("</a>.");
      }
      echo ("</p>");
      echo ("<br/>");
      echo ("<br/>");
      echo ("<br/>");
    } else {

      if ($lang == 'en')
      {
        echo ("<p class=\"error\">Note: You've already used the 3 daily fights you are allowed if you are not a premium user.</p>");
      } else {
        echo ("<p class=\"error\">Nota: Ya has utilizado las 3 luchas diarias que se permiten si no eres un usuario premium.</p>");
      }

    }

   }

//  if ($sepuede == 1)
//  {



    // Para elegir a los dos, no les vamos a poner la tabla entera obviamente

    echo ("<br/>");

    if ($lang == 'en')
    {
      echo ("<b>Please choose two specimens to start the fight:</b>");
    } else {
      echo ("<b>Por favor, selecciona dos espec&iacute;menes para dar comienzo al combate:</b>");
    }

    echo ("<br/>");
    echo ("<br/>");
    echo ("<br/>");



    ?>


     <script>
      function get_ajax(datos, parametros, destino)
      {
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


    <form method="post" action="index.php">

     <input type="hidden" name="accion" value="enfrentar_fight">
     <input type="hidden" name="catid" value="<?php echo $catid; ?>">
     <input type="hidden" name="idcampana" value="<?php echo $idcampana; ?>">



    <?php




    // Lo primero es seleccionar deme y slot

    // *PRIMER LUCHADOR*

    echo ("<table class=\"tabla_centro_control_enfrentar\"
		>");
    echo ("<tr>");
    echo ("<th width=\"250px;\">");
    echo ("</th>");
    echo ("<th width=\"150px;\">");
    echo ("Deme");
    echo ("</th>");
    echo ("<th width=\"120px;\">");
    echo ("Slot");
    echo ("</th>");
    echo ("</tr>");

    echo ("<tr style=\"background-color: #111111; font-size: 13px;\">");
    echo ("<td style=\"padding: 4px;\">");
    if ($lang == 'en')
    {
      echo ("<b>First fighter</b>");
    } else {
      echo ("<b>Primer contendiente</b>");
    }
    echo ("</td>");
    echo ("<td style=\"padding: 4px;\">");
    echo ("<select name=\"iddeme1\"
                onchange=\"get_ajax('ajax_formularios.php', 'accion=buscar_desde_deme&parametro='+this.value+'&idluchador=1&idjugador=".$idjugador.
			"&idcampana=".$idcampana."', 'div_slot_1')\"
		>
		");
//    echo ("<select name=\"iddeme1\"
//                onchange=\"resultado('ajax_formularios.php?accion=buscar_desde_deme&parametro='+this.value+'&idluchador=1&idjugador=".$idjugador.
//			"&idcampana=".$idcampana."', 'div_slot_1')\"
//		>
//		");


//		style=\"
//		background-color: #000000;
//                border: solid 1px #7f5b00;
//                color: #ffffaa;
//		\"
    if ($lang == 'en')
    {
      echo ("<option value=\"\">[Select]</option>");
      echo ("<option value=\"1\">Depths</option>");
      echo ("<option value=\"2\">Forest</option>");
      echo ("<option value=\"3\">Volcano</option>");
    } else {
      echo ("<option value=\"\">[Selecciona]</option>");
      echo ("<option value=\"1\">Profundidades</option>");
      echo ("<option value=\"2\">Bosque</option>");
      echo ("<option value=\"3\">Volc&aacute;n</option>");
    }
    echo ("</select>");
    echo ("</td>");
    echo ("<td>");
    echo ("<div id=\"div_slot_1\">-</div>");
    echo ("</td>");
    echo ("</tr>");
    echo ("</table>");



    echo ("<br/>");
    echo ("<br/>");










    // TABLA DE RESULTADOS DE LA PRIMERA BUSQUEDA

    echo ("<div id=\"div_main_1\">");
    echo ("<table id=\"tabla_centro_control\" class=\"tabla_centro_control_enfrentar\"
		>");
    echo ("<tr style=\"font-size: 14px;\">");
    if ($lang == 'en')
    {
      echo ("<th width=\"120px\">Deme</th>");
      echo ("<th width=\"30px\">Slot</th>");
      echo ("<th width=\"30px\">Life</th>");
      echo ("<th width=\"30px\">Mana</th>");
      echo ("<th width=\"60px\">Score</th>");
      echo ("<th width=\"30px\">Gold</th>");
      echo ("<th width=\"30px\">Silver</th>");
      echo ("<th width=\"30px\">Bronze</th>");
    } else {
      echo ("<th width=\"120px\">Deme</th>");
      echo ("<th width=\"30px\">Hueco</th>");
      echo ("<th width=\"30px\">Vida</th>");
      echo ("<th width=\"30px\">Mana</th>");
      echo ("<th width=\"60px\">Puntos</th>");
      echo ("<th width=\"30px\">Oro</th>");
      echo ("<th width=\"30px\">Plata</th>");
      echo ("<th width=\"30px\">Bronce</th>");
    }
    echo ("</tr>");



    // Ahora le dejamos elegir deme y slot, primero deme clarop
    echo ("<tr style=\"background-color: #111111; font-size: 13px;\">");
    echo ("<td colspan=\"8\">");
    echo ("</td>");
    echo ("</tr>");
    echo ("</table>");
    echo ("</div>");

    echo ("<br/>");
    echo ("<br/>");
    echo ("<br/>");
   





    // *SEGUNDO LUCHADOR*

    echo ("<table class=\"tabla_centro_control_enfrentar\"
		>");
    echo ("<tr>");
    echo ("<th width=\"250px;\">");
    echo ("</th>");
    echo ("<th width=\"150px;\">");
    echo ("Deme");
    echo ("</th>");
    echo ("<th width=\"120px;\">");
    echo ("Slot");
    echo ("</th>");
    echo ("</tr>");

    echo ("<tr style=\"background-color: #111111; font-size: 13px;\">");
    echo ("<td style=\"padding: 4px;\">");
    if ($lang == 'en')
    {
      echo ("<b>Second fighter</b>");
    } else {
      echo ("<b>Segundo contendiente</b>");
    }
    echo ("</td>");
    echo ("<td style=\"padding: 4px;\">");
    echo ("<select name=\"iddeme2\"
                onchange=\"get_ajax('ajax_formularios.php', 'accion=buscar_desde_deme&parametro='+this.value+'&idluchador=2".
			"&lang=".$lang."&idcampana=".$idcampana."', 'div_slot_2')\"
		>
		");

//                onchange=\"resultado('ajax_formularios.php?accion=buscar_desde_deme&parametro='+this.value+'&idluchador=2".
//			"&lang=".$lang."&idcampana=".$idcampana."', 'div_slot_2')\"

//		style=\"
//		background-color: #000000;
//                border: solid 1px #7f5b00;
//                color: #ffffaa;
//		\"

//&idjugador=".$idjugador.
    if ($lang == 'en')
    {
      echo ("<option value=\"\">[Select]</option>");
      echo ("<option value=\"1\">Depths</option>");
      echo ("<option value=\"2\">Forest</option>");
      echo ("<option value=\"3\">Volcano</option>");
    } else {
      echo ("<option value=\"\">[Selecciona]</option>");
      echo ("<option value=\"1\">Profundidades</option>");
      echo ("<option value=\"2\">Bosque</option>");
      echo ("<option value=\"3\">Volc&aacute;n</option>");
    }
    echo ("</select>");
    echo ("</td>");
    echo ("<td>");
    echo ("<div id=\"div_slot_2\">-</div>");
    echo ("</td>");
    echo ("</tr>");
    echo ("</table>");
    //echo ("</div>");



    echo ("<br/>");
    echo ("<br/>");














    // TABLA DE RESULTADOS DE LA SEGUNDA BUSQUEDA

    echo ("<div id=\"div_main_2\">");
    echo ("<table id=\"tabla_centro_control\" class=\"tabla_centro_control_enfrentar\"
		>");
    echo ("<tr style=\"font-size: 14px;\">");
    if ($lang == 'en')
    {
      echo ("<th width=\"120px\">Deme</th>");
      echo ("<th width=\"30px\">Slot</th>");
      echo ("<th width=\"30px\">Life</th>");
      echo ("<th width=\"30px\">Mana</th>");
      echo ("<th width=\"60px\">Score</th>");
      echo ("<th width=\"30px\">Gold</th>");
      echo ("<th width=\"30px\">Silver</th>");
      echo ("<th width=\"30px\">Bronze</th>");
    } else {
      echo ("<th width=\"120px\">Deme</th>");
      echo ("<th width=\"30px\">Hueco</th>");
      echo ("<th width=\"30px\">Vida</th>");
      echo ("<th width=\"30px\">Mana</th>");
      echo ("<th width=\"60px\">Puntos</th>");
      echo ("<th width=\"30px\">Oro</th>");
      echo ("<th width=\"30px\">Plata</th>");
      echo ("<th width=\"30px\">Bronce</th>");
    }
    echo ("</tr>");
    // Ahora le dejamos elegir deme y slot, primero deme clarop
    echo ("<tr style=\"background-color: #111111; font-size: 13px;\">");
    echo ("<td colspan=\"8\">");
    echo ("</td>");
    echo ("</tr>");
    echo ("</table>");
    echo ("</div>");




    echo ("<br/>");
    echo ("<br/>");
    echo ("<br/>");
    echo ("<br/>");
    echo ("<center>");
//    echo ("<a href=\"index.php?catid=".$catid."&accion=enfrentar_fight&idcampana=".$idcampana."\"><b>");
    ?>  </b>&nbsp;
        <img src="img/espadas1.gif"
                onmouseover="javascript:this.src='img/espadas2.gif';" 
                onmouseout="javascript:this.src='img/espadas1.gif';"
        >
	&nbsp;
    <?php
//    echo ("<b>");
    echo ("<input type=\"submit\" value=\"");
    if ($lang == 'en')
    {
      echo ("Start the fight");
    } else {
      echo ("Comenzar la pelea");
    }
    echo ("\" ");
    echo ("style=\" background-color: #221111;
                color: #ffffaa;
                border: solid 1px #7f5b00;
		font-weight: bold;
		padding: 3px;
		text-align: center;
		\">
		");
//    echo ("</b>");
    ?>
	&nbsp;
        <img src="img/espadas1.gif"
                onmouseover="javascript:this.src='img/espadas2.gif';" 
                onmouseout="javascript:this.src='img/espadas1.gif';"
        >
    <?php
//    echo ("</a>");
    echo ("</center>");

    echo ("<br/>");
    echo ("<br/>");
    echo ("<br/>");
    echo ("</form>");





//   } // Del if $sepuede == 1
  }

?>
