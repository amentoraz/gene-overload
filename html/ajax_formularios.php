<?php

  session_start();



//    <td> style="text-align: center;">

  include ("clases/obj_mensajes_personales.php");
  include ("clases/obj_jugador.php");
  include ("clases/obj_campana.php");
  include ("clases/obj_jugador_campana.php");
  include ("clases/obj_informe.php");
  include ("clases/obj_clan.php");
  include ("clases/obj_log.php");
  include ("clases/obj_tmz.php");
  include ("clases/obj_especimen.php");
  include ("clases/obj_especimen_torneo.php");
  include ("clases/obj_arbol.php");
  include ("clases/obj_combate.php");
  include ("clases/obj_secure.php");
  include ("clases/obj_token.php");
  $secure = new Secure();

  include ("config/database.php");
  include ("config/values.php");

  include ("functions.php");

   // Aqui deberiamos meter la autenticacion de admin...

  // Y ahora...






  function Es_Un_Premium($idjugador, $link_r)
  {
      // ESTO hay que unificarlo
      $string_premium = "SELECT id FROM jugador
                        WHERE id = $idjugador
                        AND es_premium = 1
                        AND fecha_fin_premium > NOW()
                        ";
//echo $string_premium;
      $query_premium = mysql_query($string_premium, $link_r);
      // Si devuelve 1 fila, $es_premium = 1, y si no = 0
//echo mysql_num_rows($query_premium)."#";
      return mysql_num_rows($query_premium);
  }






  $accion = $secure->Sanitizar($_REQUEST['accion']);
//  $accion = $_REQUEST['accion'];


    $s_usuario = $_SESSION['REMOTE_USER'];
    $s_clave = $_SESSION['REMOTE_PASS'];

    $string ="SELECT * FROM jugador
                        WHERE login = '$s_usuario'
                        AND clave = '$s_clave'
                        AND baneado = 0
                        AND activado = 1
                        ";
    $query = mysql_query($string, $link_r);
    if (mysql_num_rows($query) == 0)
    {
      // No es correcto
      $autenticado = 0;
      die;
    } else {
      $autenticado = 1;
      $unquery = mysql_fetch_array($query);
      $idjugador = $unquery['id'];
      $es_admin = $unquery['es_admin'];
      $es_premium = Es_Un_Premium($idjugador, $link_r);
    }




 // ****************************************
 //   Comprueba si sigues teniendo la bandera
 // ****************************************

 if ($accion == 'comprobar_jugador')
 {

   $idcampana = $_REQUEST['idcampana'];
   if (!is_numeric($idcampana)) { die; }
   $token = new token();
   $token->SacarDatos($link_r, $idcampana);

   $jugador = new Jugador();
   $jugador->SacarDatos($link_r, $idjugador);
   $jugador_campana = new Jugador_Campana();
   $lang = $jugador->lang;

   $idjugadorbandera = $token->idjugador;
   $jugador_bandera = new Jugador();
   $jugador_bandera->SacarDatos($link_r, $idjugadorbandera);
   $jugador_campana->SacarDatos($link_r, $idjugador, $idcampana);

   // Ahora tenemos $idjugador y $idjugadorbandera, asi que los vamos a enfrentar
   echo ("<p style=\"color: #ffdb00; font-size: 13px;\">");
   if ($idjugadorbandera == $idjugador)
   {
     if ($lang == 'en')
     {
       echo ("The flag is currently held by you!");
     } else {
       echo ("&iexcl;La bandera ahora la tienes t&uacute;!");
     }
     echo ("</p>");
   } else {
       if ($lang == 'en')
       {
         echo ("The flag is currently held by ");
       } else {
         echo ("La bandera la tiene ahora ");
       }
       echo ("<a href=\"index.php?catid=5&accion=ver&idcampana=".$idcampana."&idelemento=".$idjugadorbandera."\" target=\"_blank\">");
       echo $jugador_bandera->login;
       echo ("</a>.");
       echo ("</p>");
       echo ("<br/><br/>");
       if ($jugador_campana->dinero > 0)
       {
         echo ("<a href=\"javascript:atacar('ajax_formularios.php?accion=atacar&idcampana=".$idcampana."','div_ataque')\">");
         ?>

          <img src="img/espadas1.gif"
                  onmouseover="javascript:this.src='img/espadas2.gif';" 
                  onmouseout="javascript:this.src='img/espadas1.gif';"
          >
          <?php
          if ($lang == 'en')
          {
            ?>
            Click to attack the current flag owner (cost: 1<img src="img/goldcoin.gif" style="align: middle;">)
            <?php
          } else {
            ?>
            Click para atacar al actual due&ntilde;o de la bandera (coste: 1<img src="img/goldcoin.gif" style="align: middle;">)
            <?php
          }

         echo ("</a>");
         echo ("</p>");
       } else {
         if ($lang == 'en')
         {
           echo ("You need 1 <img src=\"img/goldcoin.gif\" style=\"align: middle;\"> to fight for the flag");
         } else {
           echo ("Necesitas 1 <img src=\"img/goldcoin.gif\" style=\"align: middle;\"> para luchar por la bandera");
         }
//         echo ("You need 1 <img src=\"img/goldcoin.gif\" style=\"align: middle;\"> to fight for the flag");
         echo ("</p>");
       }
   }

 }

 // ****************************************
 //   Comprueba si sigues teniendo la bandera
 // ****************************************

 if ($accion == 'comprobar_bandera_pasta')
 {

   $idcampana = $_REQUEST['idcampana'];
   if (!is_numeric($idcampana)) { die; }
   $token = new token();
   $token->SacarDatos($link_r, $idcampana);
        if ($token->idjugador == $idjugador)
        {
          echo ("(<span class=\"correcto\">12</span>");
        } else {
          echo ("(3");
        }
        echo ("<img src=\"img/goldcoin.gif\" style=\"vertical-align:middle;\">)");

 }

 // ****************************************
 //   Comprueba si sigues teniendo la bandera
 // ****************************************

 if ($accion == 'comprobar_bandera')
 {

   $idcampana = $_REQUEST['idcampana'];
   if (!is_numeric($idcampana)) { die; }
   $token = new token();
   $token->SacarDatos($link_r, $idcampana);

   $idjugadorbandera = $token->idjugador;
   // Ahora tenemos $idjugador y $idjugadorbandera, asi que los vamos a enfrentar
   if ($idjugadorbandera == $idjugador)
   {
     ?>
        <a href="#" class="Ntooltip"><img src="img/flag_captured.png"
		style="vertical-align:middle;">&nbsp;&nbsp;&nbsp;<span style="width: 250px;"><table width="100%" class="tooltip_interno"><tr><td><?php

   $jugador = new Jugador();
   $jugador->SacarDatos($link_r, $idjugador);
   $lang = $jugador->lang;
        if ($lang == 'en')
	{
          echo ("You currently hold the flag");
        } else {
          echo ("Tienes la bandera");
        }

	?></td></tr></table></span></a>

     <?php
//     echo ("1");
   } else {
//     echo ("XX");
   }

 }



 // ****************************************
 //   Atacar a otro player
 // ****************************************

 if ($accion == 'atacar')
 {

   // LOCK DE TABLES LO PRIMERO

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
                informe WRITE,
		token WRITE
                ";

   $query = mysql_query($string, $link_w);






   $jugador = new Jugador();
   $jugador->SacarDatos($link_r, $idjugador);
   $jugador_campana = new Jugador_Campana();
   $lang = $jugador->lang;



   $idcampana = $_REQUEST['idcampana'];
   if (!is_numeric($idcampana)) { die; }

   $token = new token();
   $token->SacarDatos($link_r, $idcampana);

   $idjugadorbandera = $token->idjugador;
   // Ahora tenemos $idjugador y $idjugadorbandera, asi que los vamos a enfrentar
   if ($idjugadorbandera == $idjugador)
   {
     if ($lang == 'en')
     {
       echo ("<p class=\"errorsutil\"><b>You already got the flag!</b></p>");
     } else {
       echo ("<p class=\"errorsutil\"><b>Ya tienes la bandera!</b></p>");
     }
   } else {

     // Comprobamos si tienes pasta
     $result = $jugador_campana->RestarDinero($link_w, $idjugador, $idcampana, 1);
     if ($result == 0)
     {

       // Ahora es cuando los pegamos y vemos quien gana
       // 1. Seleccionamos al elegido de este jugador y del otro
       $especimen_torneo = new Especimen_Torneo();
       $especimen_torneo->ObtenEspecimenTorneo($link_r, 0, $idjugador);
       $idespecimen_1 = $especimen_torneo->idespecimen;
       $especimen = new Especimen();
       $especimen_1 = $especimen->Obtener_Por_Id($link_r, $idespecimen_1);

       // 2. Seleccionamos al mejor del jugador que tiene la bandera
       $especimen_torneo->ObtenEspecimenTorneo($link_r, 0, $idjugadorbandera);
       $idespecimen_2 = $especimen_torneo->idespecimen;
       $especimen_2 = $especimen->Obtener_Por_Id($link_r, $idespecimen_2);

       // 3. Los enfrentamos
       $combate = new Combate();
       $arbol = new Arbol();
       $arbol1 = $arbol->Desglosar($especimen_1['arbol'], $especimen_1['niveles_arbol']);
       $arbol2 = $arbol->Desglosar($especimen_2['arbol'], $especimen_2['niveles_arbol']);

       $victorias = 0;
       $empates = 0;
       $derrotas = 0;
       // 2 veces por este lado y 2 veces al reves
       $arrayresultado = $combate->Puntuar($especimen_1,$especimen_2, $arbol1, $arbol2);
       $puntos1 = $arrayresultado['puntos1'];
       $puntos2 = $arrayresultado['puntos2'];
       if ($arrayresultado['resultado'] == 1) { $victorias++; }
       if ($arrayresultado['resultado'] == 2) { $derrotas++; }
       if (($arrayresultado['resultado'] == 0) || ($arrayresultado['resultado'] == -1)) { $empates++; }
       $arrayresultado = $combate->Puntuar($especimen_1,$especimen_2, $arbol1, $arbol2);
       $puntos1 = $puntos1 + $arrayresultado['puntos1'];
       $puntos2 = $puntos2 + $arrayresultado['puntos2'];
       if ($arrayresultado['resultado'] == 1) { $victorias++; }
       if ($arrayresultado['resultado'] == 2) { $derrotas++; }
       if (($arrayresultado['resultado'] == 0) || ($arrayresultado['resultado'] == -1)) { $empates++; }
       $arrayresultado = $combate->Puntuar($especimen_2, $especimen_1, $arbol2, $arbol1);
       $puntos1 = $puntos1 + $arrayresultado['puntos2'];
       $puntos2 = $puntos2 + $arrayresultado['puntos1'];
       if ($arrayresultado['resultado'] == 1) { $derrotas++; }
       if ($arrayresultado['resultado'] == 2) { $victorias++; }
       if (($arrayresultado['resultado'] == 0) || ($arrayresultado['resultado'] == -1)) { $empates++; }
       $arrayresultado = $combate->Puntuar($especimen_2, $especimen_1, $arbol2, $arbol1);
       $puntos1 = $puntos1 + $arrayresultado['puntos2'];
       $puntos2 = $puntos2 + $arrayresultado['puntos1'];
       if ($arrayresultado['resultado'] == 1) { $derrotas++; }
       if ($arrayresultado['resultado'] == 2) { $victorias++; }
       if (($arrayresultado['resultado'] == 0) || ($arrayresultado['resultado'] == -1)) { $empates++; }

       // 4. Dependiendo del resultado cambiamos o no la bandera de sitio

       if ($puntos1 > $puntos2)
       {
         if ($lang == 'en')
         {
           echo ("<p class=\"correctosutil\"><b>You've defeated the flag owner and you're its new holder!. ");
         } else {
           echo ("<p class=\"correctosutil\"><b>Has derrotado al poseedor de la bandera y eres su nuevo due&ntilde;o!.");
         }

         // Ademas de imprimir lo que sea, cambiamos la bandera de sitio
         $segundos = $token->CalcularSegundos($link_r, $idcampana);
         $segundos_esta_vez = $segundos;
         $token->AlterarJugador($link_w, $idcampana, $idjugador);

         $jugador_campana->SacarDatos($link_r, $idjugadorbandera, $idcampana);
         $segundos = $segundos + $jugador_campana->segundos_con_bandera;  // Le anyadimos lo que ya tiene
         // Cuantos segundos lo ha sostenido? Actualizamos AL PERDEDOR! Obviamente no al ganador, que justo empieza a sostenerlo
         $jugador_campana->ActualizarSegundos($link_w, $idcampana, $idjugadorbandera, $segundos);


         // Ahora al perdedor hay que enviarle un informe indicandole que ha perdido la bandera
         $informe = new Informe();
         $informe->tipo = 11;

         // Esto no puede ser $lang sino el lenguaje del perdedor!
         $jugador_perdido = new Jugador();
         $jugador_perdido->SacarDatos($link_r, $idjugadorbandera);
         $lang_perdido = $jugador_perdido->lang;
      
         if ($lang_perdido == 'en')
         {
           $informe->subject = mysql_real_escape_string("You've lost the flag against player ".$jugador->login);
           $informe->texto = mysql_real_escape_string("You've lost the flag against player <a href=\"index.php?catid=5&accion=ver&idcampana=".$idcampana."&idelemento=".$jugador->id."\">".$jugador->login."</a><br/><br/>");
           $informe->texto = $informe->texto.mysql_real_escape_string("You scored ".$puntos2." points, and your enemy scored ".$puntos1." points.<br/><br/>");
           $informe->texto = $informe->texto.mysql_real_escape_string("Your specimen fought 4 combats, ".$derrotas." of which were victories, ".$empates." ended up in a tie, and it was defeated ".$victorias." times.");
         } else {
           $informe->subject = mysql_real_escape_string("Has perdido la bandera contra el jugador <a href=\"index.php?catid=5&accion=ver&idcampana=".$idcampana."&idelemento=".$jugador->id."\">".$jugador->login."</a>");
           $informe->texto = mysql_real_escape_string("Has perdido la bandera contra el jugador <a href=\"index.php?catid=5&accion=ver&idcampana=".$idcampana."&idelemento=".$jugador->id."\">".$jugador->login."</a><br/><br/>");
           $informe->texto = $informe->texto."Obtuviste ".$puntos2." puntos, y tu enemigo obtuvo ".$puntos1." puntos.<br/>";
           $informe->texto = $informe->texto." Tu especimen luch&oacute; 4 batallas, de las cuales gan&oacute; ".$derrotas.", empat&oacute; ".$empates." y perdi&oacute; ".$victorias.".";
         }
         $informe->EnviarInformeRaw($link_w, $idjugadorbandera, $idcampana);

	 // Y ahora vamos a guardar logs
         //   Para el ganador
         $log = new Log();
         $log->idjugador = $idjugador;
         $log->idcampana = $idcampana;
         $log->tipo_suceso = 17; // 17, ha obtenido la bandera
         $log->valor = $idjugadorbandera; // El valor de a quien ha derrotado
         $log->EscribirLog($link_w);

         $log2 = new Log();
         $log2->idjugador = $idjugadorbandera;  // Log del perdedor
         $log2->idcampana = $idcampana;
         $log->tipo_suceso = 18; // 18, ha perdido la bandera
         $log->valor = $segundos_esta_vez; // El valor de cuantos segundos lo ha tenido esta vez
         $log->EscribirLog($link_w);



       } else {
         if ($lang == 'en')
         {
           echo ("<p class=\"errorsutil\"><b>You've been defeated by the flag owner.");
         } else {
           echo ("<p class=\"errorsutil\"><b>Has sido derrotado por el poseedor de la bandera.");
         }
       }

       echo ("</b><br/><br/>");
       if ($lang == 'en')
       {
         echo ("You scored ".($puntos1)." points, and your enemy scored ".($puntos2)." points.");
         echo ("<br/>");
         echo ("Your specimen fought 4 combats, ".$victorias." of which were victories, ".$empates." ended up in a tie, and it was defeated ".$derrotas." times.");
         echo ("</p>");
       } else {
         echo ("Obtuviste ".($puntos1)." puntos, y tu enemigo obtuvo ".($puntos2)." puntos.");
         echo ("<br/>");
         echo ("Tu especimen luch&oacute; 4 batallas, de las cuales gan&oacute; ".$victorias.", empat&oacute; ".$empates." y perdi&oacute; ".$derrotas.".");
         echo ("</p>");
       }
       // 5. Revisamos la vejez y quiza matamos a alguno

     } else {

       if ($lang == 'en')
       {
         echo ("<p class=\"errorsutil\"><b>You lack enough money to do this</b></p>");
       } else {
         echo ("<p class=\"errorsutil\"><b>No tienes suficiente dinero para esto</b></p>");
       }

     }

   } // Aqui no hay else que valga pq esto ya era un else, el de q no tengas tu la bandera

    $string = "UNLOCK TABLES
                ";
    $query = mysql_query($string, $link_w);



 }


 // ****************************************
 //   Vamos con el slot
 // ****************************************

 if ($accion == 'buscar_desde_slot')
 {

    $especimen = new Especimen();

    $jugador = new Jugador();
    $jugador_campana = new Jugador_Campana();

    $idluchador = $_REQUEST['idluchador'];
    if (!is_numeric($idluchador)) { die; }
    $iddeme = $_REQUEST['iddeme'];
    if (!is_numeric($iddeme)) { die; }
//    $idjugador = $_REQUEST['idjugador'];
//    if (!is_numeric($idjugador)) { die; }
    $idcampana = $_REQUEST['idcampana'];
    if (!is_numeric($idcampana)) { die; }
    $parametro = $_REQUEST['parametro'];
    if ((!is_numeric($parametro)) && ($parametro != '')) { die; }


    $jugador_campana->SacarDatos($link_r, $idjugador, $idcampana);
//    $lang = $jugador_campana->lang;
      $jugador->SacarDatos($link_r, $idjugador);
      $lang = $jugador->lang;

//echo $lang."#";

    if ($parametro == '')
    {
      echo ("<table id=\"tabla_centro_control\" class=\"tabla_centro_control_enfrentar\"
                >");
      echo ("<tr style=\"font-size: 14px;\">");
      if ($lang == 'en')
      {
        echo ("<th width=\"120px\">Fighter</th>");
        echo ("<th width=\"120px\">Deme</th>");
        echo ("<th width=\"30px\">Slot</th>");
        echo ("<th width=\"30px\">Life</th>");
        echo ("<th width=\"30px\">Mana</th>");
        echo ("<th width=\"60px\">Score</th>");
        echo ("<th width=\"30px\">Gold</th>");
        echo ("<th width=\"30px\">Silver</th>");
        echo ("<th width=\"30px\">Bronze</th>");
      } else {
        echo ("<th width=\"120px\">Luchador</th>");
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
      echo ("<tr style=\"background-color: #111111; font-size: 13px;\">");
      echo ("<td>");
      echo ("</td><td colspan=\"7\">");
      echo ("</td><td></td>");
      echo ("<td></td>");
      echo ("<td></td>");
      echo ("<td></td>");
      echo ("<td></td>");
      echo ("<td></td>");
      echo ("<td></td>");
      echo ("</div>");
      echo ("</td>");
      echo ("</tr>");
      echo ("</table>");

    } else {

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

      $especimen->SacarDatos($link_r, $iddeme, $parametro, $idjugador, $idcampana);

      echo ("<tr style=\"background-color: #111111; font-size: 13px; height: 25px;\">");
      echo ("<td style=\"font-weight: bold;\">");
      if ($lang == 'en')
      {
        switch ($iddeme)
        {
          case 1: echo ("Depths"); break;
          case 2: echo ("Forest"); break;
          case 3: echo ("Volcano"); break;
        }
      } else {
        switch ($iddeme)
        {
          case 1: echo ("Profundidades"); break;
          case 2: echo ("Bosque"); break;
          case 3: echo ("Volc&aacute;n"); break;
        }
      }
      echo ("</td>");

        echo ("<td style=\"font-weight: bold;\">");
        echo $parametro;
        echo ("</td>");
//      echo ("<td>".$parametro."</td>");

      echo ("<td style=\"color: #ffaaaa; width: 30px;\">");
      echo ($especimen->puntos_vida."</td>");
      echo ("<td style=\"color: #ccccff; width: 25px;\">");
      echo ($especimen->puntos_magia);
      echo ("</td>");

      echo ("<td style=\"font-weight: bold;\">");
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

        echo ("<td align=\"center\">");
        echo $especimen->oro;
        echo ("</td>");
        echo ("<td align=\"center\">");
        echo $especimen->plata;
        echo ("</td>");
        echo ("<td align=\"center\">");
        echo $especimen->bronce;
        echo ("</td>");

      echo ("</div>");
      echo ("</td>");
      echo ("</tr>");
      echo ("</table>");

    }
 }



 // ****************************************
 //   Vamos con el deme
 // ****************************************

 if ($accion == 'buscar_desde_deme')
 {

    $jugador = new Jugador();
    $jugador_campana = new Jugador_Campana();

    $idluchador = $_REQUEST['idluchador'];
    if (!is_numeric($idluchador)) { die; }
//    $idjugador = $_REQUEST['idjugador'];
//    if (!is_numeric($idjugador)) { die; }
    $idcampana = $_REQUEST['idcampana'];
    if (!is_numeric($idcampana)) { die; }
    $parametro = $_REQUEST['parametro'];
    if ((!is_numeric($parametro)) && ($parametro != '')) { die; }
//    $lang = $_REQUEST['lang'];

    if ($parametro == '')
    {
      echo ("-");
    } else {

      $jugador_campana->SacarDatos($link_r, $idjugador, $idcampana);
      $jugador->SacarDatos($link_r, $idjugador);
      $lang = $jugador->lang;
      echo ("<select name=\"idslot".$idluchador."\"
                onchange=\"get_ajax('ajax_formularios.php', 'accion=buscar_desde_slot&idluchador=".$idluchador."&parametro='+this.value+'".
                        "&idcampana=".$idcampana."&iddeme=".$parametro."', 'div_main_".$idluchador."')\"
                >");
//                onchange=\"resultado('ajax_formularios.php?accion=buscar_desde_slot&idluchador=".$idluchador."&parametro='+this.value+'".
//                        "&idcampana=".$idcampana."&iddeme=".$parametro."', 'div_main_".$idluchador."')\"
      switch($parametro)
      {
        case 1: $limite = $jugador_campana->num_slots_deme_profundidades; break;
        case 2: $limite = $jugador_campana->num_slots_deme_bosque; break;
        case 3: $limite = $jugador_campana->num_slots_deme_volcan; break;
      }
//echo ("<option>".$lang."</option>");
      if ($lang == 'en')
      {
        echo ("<option value=\"\">[Select]");
      } else {
        echo ("<option value=\"\">[Selecciona]");
      }
//echo $jugador_campana->num_slots_deme_bosque;
      echo ("</option>");
      for ($i = 1; $i <= $limite; $i++)
      {
        echo ("<option value=\"".$i."\">".$i."</option>");
      }
      echo ("</select>");

//echo "#".$limite.",".$jugador_campana->num_slots_deme_profundidades;
    }

  }






    // *************************************************************
    //  Comprobar si existe un email en el formulario de registro
    // *************************************************************

    if ($accion == 'destinatario')
    {
      $jugador = new Jugador();

      $parametro = $secure->Sanitizar($_REQUEST['parametro']);
      $lang = $secure->Sanitizar($_REQUEST['lang']);

      $existeusuario = $jugador->SacarDatosDesdeLogin($link_r, $parametro);
//usuario_clase->ExisteNombre($link_r, $parametro);
      if ($existeusuario == true)
      {


        ?>
         <div id="divdestinatario">
           <p class="textonormal"><strong><?php if ($lang == 'en') { echo ("To"); } else { echo ("Para"); } ?> : </strong><input type="text" name="destinatario" class="inputstandard" 
                value="<?php echo $parametro; ?>"
                onblur="resultado('ajax_formularios.php?accion=destinatario&lang=<?php echo $lang; ?>&parametro='+this.value,'divdestinatario')"></p>
         </div>
        <?php
      } else {
        ?>
         <div id="divdestinatario">
           <p class="textonormal"><strong><?php if ($lang == 'en') { echo ("To"); } else { echo ("Para"); } ?> : </strong><input type="text" name="destinatario" class="inputstandard"
                value="<?php echo $parametro; ?>"
                onblur="resultado('ajax_formularios.php?accion=destinatario&lang=<?php echo $lang; ?>&parametro='+this.value,'divdestinatario')"></p>
        <br/>
        <?php
          if ($lang == 'en')
          {
        ?>
           <p class="error">There is no user with such name.</p>
        <?php
          } else {
        ?>
           <p class="error">No existe ning&uacute;n usuario con ese nombre.</p>
        <?php
          }
        ?>


         </div>
        <?php
      }
    }



  // ************************************
  //      Re-evaluar a un individuo
  // ************************************

  if ($accion == 'reevaluar_individuo')
  {

    $debug_mode = $jugador_campana->debug_mode;

    $especimen = new Especimen();
    $arbol = new Arbol();
    $combate = new Combate($debug_mode);
    $idespecimen = $_REQUEST['idespecimen'];
    if (!is_numeric($idespecimen))
    {
      die;
    }

    $idcampana = $_REQUEST['idcampana'];
    if (!is_numeric($idcampana))
    {
      die;
    }

    $jugador_campana = new Jugador_Campana();
    $jugador_campana->SacarDatos($link_r, $idjugador, $idcampana);


//echo $idespecimen;
//echo $idjugador;

    $result = $jugador_campana->RestarDinero($link_w, $idjugador, $idcampana, 1);
    if ($result == 0)
    {
      $dinerito = $dinerito - 1;  // Lo restamos para que aparezca actualizado
      // Ahora le hacemos combatir contra el resto de todos
//      $jugador_campana->SacarDatos($link_r, $idjugador, $idcampana);
      $total_especimenes = $jugador_campana->num_slots_deme_profundidades +
                        $jugador_campana->num_slots_deme_bosque +
                        $jugador_campana->num_slots_deme_volcan;
//echo $total_especimenes."$";
      // Vamos a ir uno a uno, saltandonos solo el combate contra si mismo
      $especimen_evaluado = $especimen->Obtener_Por_Id($link_r, $idespecimen);
      $puntos_total = 0;
      for ($e = 0; $e < $total_especimenes; $e++)
      {
//echo $puntos_total."#";
        $especimen_enfrentado = $especimen->Obtener_Por_Numero($link_r, $idcampana, $idjugador, $e);
        if ($especimen_enfrentado['id'] != $especimen_evaluado['id'])
        {
//echo ("!");
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
//echo $puntos_total;


// Esto no deberia ir al final?
        $puntos_media = round($puntos_total / $e);
        if ($debug_mode == 1)
        {
          echo ("MEDIA: ".$puntos_media."#");
        }
        $especimen->GuardarPuntuacion($link_w, $idespecimen, $puntos_media);

      // Generamos un log para este usuario
      $log = new Log();
      $log->idjugador = $idjugador;
      $log->idcampana = $idcampana;
      $log->tipo_suceso = 2; // 2, entrenar
      $log->valor = 1; // 1, a un individuo
      $log->EscribirLog($link_w);

          if ($puntos_media > 0)
          {
            $green = ($puntos_media * 2) + 130; $red = 110;
          } else {
            $red = (abs($puntos_media) * 2) + 130; $green = 110;
          }
          if ($green > 255) { $green = 255; }
          if ($red > 255) { $red = 255; }
          echo ("<span style=\"font-weight: bold; color: rgb(".$red.",".$green.",110)\">");
          echo $puntos_media;
          echo ("</span>");

//    } else {
//      if ($lang == 'en')
//      {
//        echo ("<p class=\"errorsutil2\">You lack the money for this</p>");
//      } else {
//        echo ("<p class=\"errorsutil2\">No tienes dinero para ejecutar esta accion</p>");
//      }
//      echo ("<br/>");
    }
  }



?>


