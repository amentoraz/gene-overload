<?php

  $debug = $_REQUEST['debug'];
  if (!is_numeric($debug))
  {
    $debug = 0;
  }

//  $accion = $_REQUEST['accion'];

  include ('clases/obj_clan_muro.php');

    echo ("<center>");
    echo ("<p class=\"textonormal\">");
    if ($lang == 'en')
    {
      if ($tengoclan == true)
      {
        echo ("<a class=\"textonormal\" href=\"index.php?catid=".$catid."&idcampana=".$idcampana."\">My team</a> - ");
      }
      echo ("<a class=\"textonormal\" href=\"index.php?catid=".$catid."&idcampana=".$idcampana."&accion=listar_clanes\">List teams</a> - ");
    } else {
      if ($tengoclan == true)
      {
        echo ("<a class=\"textonormal\" href=\"index.php?catid=".$catid."&idcampana=".$idcampana."\">Mi equipo</a> - ");
      }
      echo ("<a class=\"textonormal\" href=\"index.php?catid=".$catid."&idcampana=".$idcampana."&accion=listar_clanes\">Listar equipos</a> - ");
    }
    echo ("</center>");





  // *****************************
  //  Mostramos los datos de un clan, llamado desde mas de un lugar
  // *****************************

  function MostrarDatosClan($clan, $lang)
  {


    global $array_months;
    global $array_meses;
    global $ruta_avatar_equipo;

    echo ("<br/>");
    echo ("<table style=\"
			\" width=\"100%\">");
    echo ("<tr style=\"background-color: #222222;
			\"><td colspan=\"2\" style=\"padding: 8px;
						\"
				>");
      echo ("<center><span style=\"font-weight: bold; color: #ffffaa; \">");
      echo ($clan->nombre);
      echo ("</span></center>");
    echo ("</td></tr>");
    echo ("<tr style=\"background-color: #111111\">");
      echo ("<td style=\"padding: 4px;\">");
      echo ("<br/>");
      echo ("<p style=\"color: #dddd77\">");
      echo ($clan->presentacion);
      echo ("</p>");

      echo ("<br/>");
      if ($lang == 'en')
      {
        echo ("<p style=\"color: #dddd77\"><b>Members</b> : ");
      } else {
        echo ("<p style=\"color: #dddd77\"><b>Miembros</b> : ");
      }
      echo ($clan->nmiembros);
      echo ("</p>");

      echo ("<br>");
      echo ("<p style=\"color: #dddd77\">");
      if ($lang == 'en')
      {
        echo ("<b>Creation date</b> : ");
      } else {
        echo ("<b>Fecha de fundaci&oacute;n</b> : ");
      }
        $anyo = substr($clan->fecha_fundacion, 0, 4);
        $mes = substr($clan->fecha_fundacion, 5, 2);
        $dia = substr($clan->fecha_fundacion, 8, 2);
        $hora = substr($clan->fecha_fundacion, 11, 5);
        if ($lang == 'en')
        {
          if ($dia == '01') { echo ("1st "); } else {
            if ($dia == '02') { echo ("2nd "); } else {
              if ($dia == '02') { echo ("3rd "); } else {
                echo (abs($dia)."th ");
              }
            }
          }
          echo (" of ".$array_months[(abs($mes))].", ".$anyo.", ".$hora);
        } else {
          echo (abs($dia)." de ".$array_meses[(abs($mes))]." de ".$anyo.", ".$hora);
        }
//echo abs($mes);
//echo $array_meses['1'];
//print_r ($array_meses);
      echo ("</p>");

     echo ("<br/>");

     echo ("</td>");
     echo ("<td style=\"text-align: right; padding: 8px;\">");
      // Pintamos el avatar
      if ($clan->ruta_avatar == null)
      {
        echo ("<img src=\"".$ruta_avatar_equipo."team_unknown.jpg\" style=\"vertical-align: top;\">");
      } else {
        echo ("<img src=\"".$ruta_avatar_equipo.$clan->ruta_avatar."\" style=\"vertical-align: top;\">");
      }


     echo ("</td></tr></table>");


  }


  include ('clanes_configurar.php');



  // *************************************************
  //    Aceptar una invitacion a un equipo
  // *************************************************

  if ($accion == 'aceptar_invitacion')
  {
//    $idjugadorcampana = $_REQUEST['idjugadorcampana'];
    $idclan = $_REQUEST['idclan'];
    if (!is_numeric($idclan))
    {
      die;
    }



    if ($tengoclan == false)
    {

      //  El jugador en la entrada en clan_jugador que hace referencia
      // a los parametros, ha de estar como invitado a secas.

      $idsolicitud = $miclan->EstaInvitado($idjugador, $idcampana, $idclan);

      if ($idsolicitud != -1)
      {
        // De estarlo, ponemos aceptado = 1

        $miclan->Aceptar_Invitacion($idsolicitud, $idclan);

        // Y borramos invitaciones y solicitudes pendientes

        $jugador = new Jugador();
        $jugador->SacarDatos($link_r, $idjugador);
        $jugador_campana = new Jugador_campana();
        $jugador_campana->SacarDatos($link_r, $idjugador, $idcampana);


        // Debe comunicarle ahora a los admins del otro equipo que lo has aceptado
        $array_jefes = $miclan->ObtenerJefes($idclan);
        for ($i = 1; $i <= count($array_jefes); $i++)
        {
          $informe = new Informe();
          $informe->tipo = 5;
          if ($lang == 'en')
          {
            $informe->subject = 'User '.$jugador->login.' has accepted the invitation to the team you manage';
            $informe->texto = 'User '.$jugador->login.' has decided it will be a part of the scientific team you manage and so accepts the invitation.';
          } else {
            $informe->subject = 'El usuario '.$jugador->login.' ha aceptado la invitaci&oacute;n al equipo que gestionas';
            $informe->texto = 'El usuario '.$jugador->login.' ha decidido que ser&aacute; parte del equipo cient&iacute;fico que gestionas, y por lo tanto ha aceptado la invitaci&oacute;n.';
          }
	  $informe->EnviarInformeRaw($link_w, $array_jefes[$i]['idjugador'], $idcampana);
        }


        $miclan->BorrarSolicitudes($jugador_campana->id);

        if ($lang == 'en')
        {
          echo ("You have accepted this invitation.");
        } else {
          echo ("Has aceptado esta invitaci&oacute;n.");
        }

        // Generamos un log para este nuevo pertenecer
        $log = new Log();
        $log->idjugador = $idjugador;
        $log->idcampana = $idcampana;
        $log->tipo_suceso = 12; // 12, unido a un clan
        $log->valor = $idclan; // id de clan
        $log->EscribirLog($link_w);




      } else {

        if ($lang == 'en')
        {
          echo ("You have not been invited here.");
        } else {
          echo ("No has sido invitado aqu&iacute;.");
        }

      }


    } else {

      if ($lang == 'en')
      {
        echo ("You already belong to a team.");
      } else {
        echo ("Ya perteneces a un equipo.");
      }

    }
//    $accion = null;
  }

  // *************************************************
  //    DEclinar una invitacion a un equipo
  // *************************************************

  if ($accion == 'declinar_invitacion')
  {
//    $idjugadorcampana = $_REQUEST['idjugadorcampana'];
    $idclan = $_REQUEST['idclan'];
    if (!is_numeric($idclan))
    {
      die;
    }



    if ($tengoclan == false)
    {

      //  El jugador en la entrada en clan_jugador que hace referencia
      // a los parametros, ha de estar como invitado a secas.

      $idsolicitud = $miclan->EstaInvitado($idjugador, $idcampana, $idclan);

      if ($idsolicitud != -1)
      {

        // De estarlo, ponemos invitado_declinado = 1
        $miclan->Rechazar_Invitacion($idsolicitud, $idclan);

        $jugador = new Jugador();
        $jugador->SacarDatos($link_r, $idjugador);

        // Debe comunicarle ahora a los admins del otro equipo que lo has rechazado
        $array_jefes = $miclan->ObtenerJefes($idclan);
        for ($i = 1; $i <= count($array_jefes); $i++)
        {
          $informe = new Informe();
          $informe->tipo = 5;
          if ($lang == 'en')
          {
            $informe->subject = 'User '.$jugador->login.' has rejected the invitation to the team you manage';
            $informe->texto = 'User '.$jugador->login.' has decided it will not be a part of the scientific team you manage and so rejects the invitation.';
          } else {
            $informe->subject = 'El usuario '.$jugador->login.' ha rechazado la invitaci&oacute;n al equipo que gestionas';
            $informe->texto = 'El usuario '.$jugador->login.' ha decidido que no quiere formar parte del equipo cient&iacute;fico que gestionas, y por lo tanto ha rechazado la invitaci&oacute;n.';
          }
	  $informe->EnviarInformeRaw($link_w, $array_jefes[$i]['idjugador'], $idcampana);
        }

        if ($lang == 'en')
        {
          echo ("You have rejected this invitation.");
        } else {
          echo ("Has rechazado esta invitaci&oacute;n.");
        }


      } else {
        if ($lang == 'en')
        {
          echo ("You have not been invited here.");
        } else {
          echo ("No has sido invitado aqu&iacute;.");
        }
      }

    } else {

      if ($lang == 'en')
      {
        echo ("You already belong to a team.");
      } else {
        echo ("Ya perteneces a un equipo.");
      }

    }

//    $accion = null;
  }




  // *************************************************
  //    Pronmover a un jugador y hacerlo admin
  // *************************************************

  if ($accion == 'promover')
  {
    if ($miclan->fundador == 1)
    {

      $idjugadorcampana = $_REQUEST['idjugadorcampana'];
      if (!is_numeric($idjugadorcampana))
      {
        die;
      }
 
      $suclan = new Clan();
      $suclan->link_w = $link_w;
      $suclan->link_r = $link_r;
      $suclan->SacarDatosClanJugador($idjugadorcampana, $miclan->id);

      if (($suclan->administrador == 0) && ($suclan->fundador == 0))
      {
        // Vamos a hacerlo admin
        $suclan->HacerAdmin($idjugadorcampana, $miclan->id);
        $accion = 'listar_miembros';
        if ($lang == 'en')
        {
          echo ("<p class=\"correctosutil\">Player promoted to admin</p>");
        } else {
          echo ("<p class=\"correctosutil\">Jugador promovido a admin</p>");
        }
      } else {
        echo ("Error f1255");
      }

    }
  }

  // *************************************************
  //    Pronmover a un jugador y hacerlo admin
  // *************************************************

  if ($accion == 'degradar')
  {
    if ($miclan->fundador == 1)
    {

      $idjugadorcampana = $_REQUEST['idjugadorcampana'];
      if (!is_numeric($idjugadorcampana))
      {
        die;
      }
      $suclan = new Clan();
      $suclan->link_w = $link_w;
      $suclan->link_r = $link_r;
      $suclan->SacarDatosClanJugador($idjugadorcampana, $miclan->id);

      if (($suclan->administrador == 1) && ($suclan->fundador == 0))
      {
        // Vamos a hacerlo admin
        $suclan->QuitarAdmin($idjugadorcampana, $miclan->id);
        $accion = 'listar_miembros';
        if ($lang == 'en')
        {
          echo ("<p class=\"correctosutil\">Player degraded from admin</p>");
        } else {
          echo ("<p class=\"correctosutil\">Jugador degradado</p>");
        }
      } else {
        echo ("Error f1256");
      }

    }
  }


  // *************************************************
  //    Banear a un jugador
  // *************************************************

  if ($accion == 'ban')
  {
  	 // ············ Si eres administrador de este clan ················
    if (($miclan->administrador == 1) || ($miclan->fundador == 1))
    {

      //  Si eres administrador puedes banear, a todo el que no
      // sea administrador. Si eres fundador puedes banear a los admins tb

      $idjugadorcampana = $_REQUEST['idjugadorcampana'];
      if (!is_numeric($idjugadorcampana))
      {
        die;
      }
      $suclan = new Clan();
      $suclan->link_r = $link_r;
      $suclan->link_w = $link_w;
      $suclan->SacarDatosClanJugador($idjugadorcampana, $miclan->id);
//echo $suclan->idjugador."#".$suclan->id;

      if ($miclan->fundador == 1)
      {
        // Entonces siempre que no banees a un fundador, esta ok
        if ($suclan->fundador == 1)
	{
          echo ("Error f1291");
          $correcto = 0;
	} else {
          $correcto = 1;
	}
      } else {
        // Entonces tiene que ser ni admin ni fundador
        if (($suclan->fundador == 1) || ($suclan->administrador == 1))
	{
          echo ("Error f1292");
          $correcto = 0;
	} else {
          $correcto = 1;
	}
      }

      // Si entra aqui es que puede banear
      if ($correcto == 1)
      {
        $miclan->Banear($idjugadorcampana, $miclan->id);
        $miclan->DecrementarMiembros($miclan->id);
        echo ("<br/>");
        if ($lang == 'en')
        {
          echo ("<span class=\"correctosutil\">User banned</span>");
        } else {
          echo ("<span class=\"correctosutil\">Usuario baneado</span>");
        }
        echo ("<br/>");
        echo ("<br/>");

        // Generamos un log para este usuario
        $log = new Log();
        $log->idjugador = $suclan->idjugador;
        $log->idcampana = $idcampana;
        $log->tipo_suceso = 14; // 14, baneado de un clan
        $log->valor = $miclan->id; // watever
        $log->EscribirLog($link_w);


      }


    } else {
      if ($lang == 'en')
      {
        echo ("You have no permissions to do this.");
      } else {
        echo ("No tienes permisos para hacer esto.");
      }
    }

    $accion = 'listar_miembros';
  }


  // *************************************************
  //    Listado de miembros de un clan
  // *************************************************

  if ($accion == 'listar_miembros')
  {
    $array_miembros = $miclan->Listar_Miembros($miclan->id);   
    if (count($array_miembros) > 0)
    {
    	echo ("<br/>");
    	if ($lang == 'en')
    	{
    		echo ("Members from team <i>\"".$miclan->nombre."\"</i>");
	} else {
		echo ("Miembros del equipo <i>\"".$miclan->nombre."\"</i>");
	}
      ?>


     <?php
     if ($lang == 'en')
     {
     ?>
     <script>
       function confirmarBanear(delUrl) {
        if (confirm("Are you sure you want to ban this user?")) {
         document.location = delUrl;
        }
       }
     </script>
     <?php
     } else {
     ?>
     <script>
       function confirmarBanear(delUrl) {
        if (confirm("Seguro que desea banear a esta usuario?")) {
         document.location = delUrl;
        }
       }
     </script>
     <?php
     }
     ?>



      <br/>
      <br/>
      <table class="tabla_standard">
      <tr>
        <?php
          if ($lang == 'en')
          {
          	echo ("<th width=\"200px\">");
          	echo ("Player name");
          	echo ("</th>");
         	echo ("<th width=\"220px\">");
          	echo ("Date joined");
          	echo ("</th>");
          	echo ("<th width=\"70px\">");
          	echo ("Status");
          	echo ("</th>");
      		// ············ Si eres administrador de este clan ················
      		if (($miclan->administrador == 1) || ($miclan->fundador == 1))
      		{
      			echo ("<th width=\"60px\">");
      			echo ("Ban");
      			echo ("</th>");
		        if ($miclan->fundador == 1)
		        {
      			  echo ("<th width=\"60px\">");
        		  echo ("Promote / degrade");
      			  echo ("</th>");
                        }
      		}
          	echo ("<th width=\"100px\">");
          	echo ("Tree depth");
          	echo ("</th>");
          } else {
          	echo ("<th width=\"200px\">");
          	echo ("Jugador");
          	echo ("</th>");
          	echo ("<th width=\"180px\">");
          	echo ("Fecha de uni&oacute;n");
          	echo ("</th>");
          	echo ("<th width=\"70px\">");
          	echo ("Estatus");
          	echo ("</th>");
      		// ············ Si eres administrador de este clan ················
      		if (($miclan->administrador == 1) || ($miclan->fundador == 1))
      		{
      			echo ("<th width=\"60px\">");
      			echo ("Expulsar");
      			echo ("</th>");
		        if ($miclan->fundador == 1)
		        {
      			  echo ("<th width=\"60px\">");
      			  echo ("Promocionar / degradar");
      			  echo ("</th>");
                        }
      		}
          	echo ("<th width=\"100px\">");
          	echo ("Niveles &aacute;rbol");
          	echo ("</th>");
          }
          ?>
      </tr>
      <?php

	// PREPARAMOS LOS AJUSTES DE HORA
                $jugador_aux = new Jugador($link_r);
                $jugador_aux->SacarDatos($link_r, $idjugador);
                $id_tmz = $jugador_aux->id_tmz;
                $tmz = new TMZ();
                $tmz->SacarDatos($link_r, $id_tmz);
                $hora_servidor = -6;
                $min_servidor = 0;
                $diferencia_hora = $tmz->tmz_hour - $hora_servidor;
                $diferencia_min = $tmz->tmz_min - $min_servidor;
	// PREPARAMOS LOS AJUSTES DE HORA (FIN)

    	for ($i = 1; $i <= count($array_miembros); $i++)
    	{

             if ($i % 2)
             {
    		echo ("<tr style=\"background-color: #111111;\">");
             } else {
    		echo ("<tr style=\"background-color: #222222;\">");
             }

    		echo ("<td style=\"font-size: 13px;\">");
//echo $idjugador;
                if ($array_miembros[$i]['idjugador'] != $idjugador)
                {
                  echo ("<a href=\"index.php?catid=5&accion=ver&idcampana=".$idcampana."&idelemento=".$array_miembros[$i]['idjugador']."\">");
                  echo ($array_miembros[$i]['login']);
                  echo ("</a>");
                } else {
                  echo ($array_miembros[$i]['login']);
                }
    		echo ("</td>");

         echo ("<td style=\"font-size: 13px;\">");    		
         $anyo = substr($array_miembros[$i]['fecha_union'], 0, 4);
         $mes = substr($array_miembros[$i]['fecha_union'], 5, 2);
         $dia = substr($array_miembros[$i]['fecha_union'], 8, 2);
//         $hora = substr($array_miembros[$i]['fecha_union'], 11, 5);
         $hora = substr($array_miembros[$i]['fecha_union'], 11, 2);
         $min = substr($array_miembros[$i]['fecha_union'], 14, 2);

//                $anyo = substr($array[$i]['fecha'], 0, 4);
//                $mes = substr($array[$i]['fecha'], 5, 2);
//                $dia = substr($array[$i]['fecha'], 8, 2);
//                $hora = substr($array[$i]['fecha'], 11, 2);
//                $min = substr($array[$i]['fecha'], 14, 2);
                // Ajustamos la fecha con el TMZ
                $hora = $hora + $diferencia_hora;
                $min = $min + $diferencia_min;
                $arrayf = AjustarFecha($anyo, $mes, $dia, $hora, $min);
                $anyo = $arrayf['anyo']; $mes = $arrayf['mes']; $dia = $arrayf['dia']; $hora = $arrayf['hora']; $min = $arrayf['min'];
//                echo ("<i>".$dia."/".$mes."/".$anyo." ".$hora.":".$min."</i> - <b>");


         if ($lang == 'en')
         {
           if ($dia == '01') { echo ("1st "); } else {
            if ($dia == '02') { echo ("2nd "); } else {
              if ($dia == '02') { echo ("3rd "); } else {
                echo (abs($dia)."th ");
              }
            }
         }
           echo (" of ".$array_months[(abs($mes))].", ".$anyo.", ".$hora.":".$min);
         } else {
           echo (abs($dia)." de ".$array_meses[(abs($mes))]." de ".$anyo.", ".$hora.":".$min);
         }    		
    		echo ("</td>");
    		
    		echo ("<td style=\"font-size: 13px;\">");
    		if ($array_miembros[$i]['fundador'] == 1)
    		{
                        echo ("<img src=\"img/founder.gif\" style=\"vertical-align: middle;\">");
//    			echo ("F");
    		} else {
    			if ($array_miembros[$i]['administrador'] == 1)
    			{
                                echo ("<img src=\"img/admin.gif\" style=\"vertical-align: middle;\">");
//    				echo ("A");
    			}
			}
    		
    		echo ("</td>");
    		
    		// ············ Si eres administrador de este clan ················
     		if (($miclan->administrador == 1) || ($miclan->fundador == 1))
     		{
   			echo ("<td>");
     			if (($array_miembros[$i]['fundador'] == 0) && ($array_miembros[$i]['administrador'] == 0))
     			{
     			  echo ("<a href=\"javascript:confirmarBanear('index.php?catid=".$catid."&idcampana=".$idcampana."&accion=ban&idjugadorcampana=".$array_miembros[$i]['idjugadorcampana']."')\">");
                          echo ("<img src=\"img/ban.gif\" style=\"vertical-align: middle;\">");
//     			  echo ("K");
     			  echo ("</a>");
     			}
     			echo ("</td>");
     		}



    		// ············ Si eres fundador de este clan ················
 	        if ($miclan->fundador == 1)
	        {
                  echo ("<td>");
                  if ($array_miembros[$i]['fundador'] == 0)
                  {
                    if ($array_miembros[$i]['administrador'] == 0)
                    {
                      echo ("<a href=\"index.php?catid=".$catid."&idcampana=".$idcampana."&accion=promover&idjugadorcampana=".$array_miembros[$i]['idjugadorcampana']."\">");
                      echo ("<img src=\"img/flecha_arriba2.png\">");
//                      echo ("UP");
	  	      echo ("</a>");
                    } else {
                      echo ("<a href=\"index.php?catid=".$catid."&idcampana=".$idcampana."&accion=degradar&idjugadorcampana=".$array_miembros[$i]['idjugadorcampana']."\">");
                      echo ("<img src=\"img/flecha_abajo2.png\">");
//                      echo ("DOWN");
		      echo ("</a>");
                    }
                  }
                }


		// Niveles de profundidad de su arbolico
		echo ("<td>");
                echo $array_miembros[$i]['niveles_arbol'];
		echo ("</td>");

    		echo ("</tr>");


      }
      echo ("</table>");
    } else {
    	echo ("<br/>");
        if ($lang == 'en')
        {
          echo ("There is no members in this team");
        } else {
    	  echo ("No hay miembros en el equipo");
        }
    }
  }

  // *************************************************
  //    Rechaza la solicitud de entrada en un clan
  // *************************************************

  if ($accion == 'rechazar_solicitud')
  {

    // primero vamos a comprobar que soy admin de mi clan
    if (($miclan->administrador == 1) || ($miclan->fundador == 1))
    {

      $idsolicitud = $_REQUEST['idsolicitud'];
      if (!is_numeric($idsolicitud))
      {
        die;
      }

      //  visto esto, ahora con el id de solicitud (de la tabla clan_jugador)
      // en mano tenemos que ver que pertenezca a nuestro clan, claro, y que
      // no nos dedicamos a aprobar a gente de otros clanes :P

      // ademas, comprobarAprobar se asegurara de que es una solicitud y blabla
      if ($miclan->ComprobarAprobar($miclan->id, $idcampana, $idjugador, $idsolicitud))
      {

        // Vale, soy el admin del grupo solicitado, asi que lo apruebo
        $miclan->RechazarSolicitud($idsolicitud, $miclan->id);
        if ($lang == 'en')
        {
          echo ("Request denied.");
        } else {
          echo ("Petici&oacute;n denegada.");
        }


          $miclan->SacarDatosSolicitud($idsolicitud);
          // Enviamos un informe al jugador afectado, indicandole que ha sido aceptado
          $informe = new Informe();
          $informe->tipo = 6;
          if ($miclan->lang == 'en')
          {
            $informe->subject = 'Team '.$miclan->nombre.' has rejected you';
            $informe->texto = 'Team '.$miclan->nombre.' has rejected your request to be a part of it. You will not be able to apply again for this scientific team.';
          } else {
            $informe->subject = 'El equipo '.$miclan->nombre.' te ha rechazado';
            $informe->texto = 'El equipo '.$miclan->nombre.' ha rechazado tu petici&oacute;n de formar parte de &eacute;l. No podr&aacute;s volver a solicitar formar parte de este equipo cient&iacute;fico de nuevo.';
          }
          $informe->EnviarInformeRaw($link_w, $miclan->idjugador, $idcampana);




      }

    }
    $accion = null;

  }


  // *************************************************
  //    Aprueba la solicitud de entrada en un clan
  // *************************************************

  if ($accion == 'aprobar_solicitud')
  {

    // primero vamos a comprobar que soy admin de mi clan
    if (($miclan->administrador == 1) || ($miclan->fundador == 1))
    {

      $idsolicitud = $_REQUEST['idsolicitud'];
      if (!is_numeric($idsolicitud))
      {
        die;
      }

      //  visto esto, ahora con el id de solicitud (de la tabla clan_jugador)
      // en mano tenemos que ver que pertenezca a nuestro clan, claro, y que
      // no nos dedicamos a aprobar a gente de otros clanes :P

      // ademas, comprobarAprobar se asegurara de que es una solicitud y blabla
      if ($miclan->ComprobarAprobar($miclan->id, $idcampana, $idjugador, $idsolicitud))
      {

        // Vale, soy el admin del grupo solicitado, asi que lo apruebo
        $miclan->AprobarSolicitud($idsolicitud, $miclan->id);
        echo ("<br/>");
        if ($lang == 'en')
        {
          echo ("Request approved. There is a new member in the team.");
        } else {
          echo ("Petici&oacute;n aprobada. Ya hay un nuevo miembro en el equipo.");
        }
        echo ("<br/>");
        echo ("<br/>");


          $miclan->SacarDatosSolicitud($idsolicitud);
          // Enviamos un informe al jugador afectado, indicandole que ha sido aceptado
          $informe = new Informe();
          $informe->tipo = 6;
          if ($miclan->lang == 'en')
          {
            $informe->subject = 'Team '.$miclan->nombre.' has accepted you';
            $informe->texto = 'Team '.$miclan->nombre.' has accepted your request to be a part of it. From now on you can consider yourself a member of this scientific team.';
          } else {
            $informe->subject = 'El equipo '.$miclan->nombre.' te ha aceptado';
            $informe->texto = 'El equipo '.$miclan->nombre.' ha aceptado tu petici&oacute;n de formar parte de &eacute;l. De ahora en adelante puedes considerarte un miembro de este equipo cient&iacute;fico';
          }
          $informe->EnviarInformeRaw($link_w, $miclan->idjugador, $idcampana);


          // Generamos un log para este nuevo pertenecer
          $log = new Log();
          $log->idjugador = $miclan->idjugador;
          $log->idcampana = $idcampana;
          $log->tipo_suceso = 12; // 12, unido a un clan
          $log->valor = $miclan->id; // id de clan
          $log->EscribirLog($link_w);



      }

    }
    $accion = null;

  }




  // ********************************************
  //      Disolver el equipo
  // ********************************************

  if ($accion == 'disolver')
  {
    if ($miclan->fundador == 1)
    {
      $miclan->DisolverClan($miclan->id);

        if ($lang == 'en')
        {
          echo ("You have dissolved your team.");
        } else {
          echo ("Has disuelto tu equipo.");
        }

          // Generamos un log para este usuario
          $log = new Log();
          $log->idjugador = $idjugador;
          $log->idcampana = $idcampana;
          $log->tipo_suceso = 16; // 16, disolver un clan
          $log->valor = $miclan->id; // watever
          $log->EscribirLog($link_w);


    }
  }


  // ********************************************
  //      Dejar el clan
  // ********************************************

  if ($accion == 'dejar')
  {
    if ($miclan->fundador != 1)
    {
      // Ahora vamos a ver si puedes
      if (($miclan->aceptado == 1) && ($miclan->baneado == 0))
      {
        $jugador_campana = new Jugador_campana();
        $jugador_campana->SacarDatos($link_r, $idjugador, $idcampana);
        $miclan->DejarClan($jugador_campana->id, $miclan->id);
	$miclan->DecrementarMiembros($miclan->id);

        if ($lang == 'en')
        {
          echo ("You have left your team.");
        } else {
          echo ("Has dejado tu equipo.");
        }

        // Generamos un log para este usuario
        $log = new Log();
        $log->idjugador = $idjugador;
        $log->idcampana = $idcampana;
        $log->tipo_suceso = 15; // 15, dejar un clan
        $log->valor = $miclan->id; // watever
        $log->EscribirLog($link_w);


      } else {
        if ($lang == 'en')
        {
          echo ("You can't leave this.");
        } else {
          echo ("No puedes dejar esto.");
        }
      }


    } else {
      if ($lang == 'en')
      {
        echo ("You can't leave a team you've founded");
      } else {
        echo ("No puedes dejar un equipo del cual eres fundador");
      }
    }
  }


  // ********************************************
  //      Solicitar la entrada en un clan
  // ********************************************

  if ($accion == 'solicitar')
  {
    $idclan = $_REQUEST['idclan'];
    if (!is_numeric($idclan))
    {
      die;
    }
    $clan = new Clan();
    $clan->link_r = $link_r;
    $clan->link_w = $link_w;
    if ($clan->SacarDatos($idclan))
    {
      $existe_relacion = $clan->VerRelacion($idjugador, $idcampana, $idclan);

      if (($existe_relacion == false) && ($tengoclan == false))
      {
///      if (($tengoclan == false) &&
//	($clan->j_solicitado == 0) &&
//	($clan->j_baneado == 0) &&
//	($clan->j_invitado == 0)
//	)
//      {
        // Insertamos la solicitud
        $clan->InsertarSolicitud($idjugador, $idcampana, $idclan);
        echo ("<br/><p class=\"correctosutil\">");
        if ($lang == 'en')
        {
          echo ("Request sent.");
        } else {
          echo ("Solicitud realizada.");
        }
        echo ("</p><br/>");
        $accion = "ver_clan";

      } else {

        echo ("<br/><p class=\"errorsutil\">");
        if ($lang == 'en')
        {
          echo ("Error: You are already in some kind of relationship with this team.");
        } else {
          echo ("Error: Ya est&aacute;s en alg&uacute;n tipo de relaci&oacute;n con este equipo.");
        }
        echo ("</p><br/>");
        $accion = "listar_clanes";
      }
    } else {
      if ($lang == 'en')
      {
        echo ("Team doesn't exist.");
      } else {
        echo ("El equipo no existe.");
      }
      $accion = "listar_clanes";
    }
  }

  // ********************************************
  //      Ver sobre un clan (que no es el tuyo)
  // ********************************************

  if ($accion == 'ver_clan')
  {

    $idclan = $_REQUEST['idclan'];
    if (!is_numeric($idclan))
    {
      die;
    }
    $clan = new Clan();
    $clan->link_r = $link_r;
    if ($clan->SacarDatos($idclan))
    {

      MostrarDatosClan($clan, $lang);

      // Vamos a ver si puedes unirte
      // Puedes pedir unirte si
      // - no lo has solicitado ya
      // - no estas invitado a ese clan
      $existe_relacion = $clan->VerRelacion($idjugador, $idcampana, $idclan);

      // BANEADO
      if ($clan->j_baneado == 1)
      {
        echo ("<br/>");
        if ($lang == 'en')
        {
          echo ("You are banned from this team.");
        } else {
          echo ("Est&aacute;s baneado de este equipo.");
        }
      }

      // SOLICITADO SIN RESOLVER
      if (($clan->j_solicitado == 1) && ($clan->solicitado_declinado == 0) && ($clan->j_aceptado == 0))
      {
        echo ("<br/>");
        if ($lang == 'en')
        {
          echo ("You are pending approval from this team.");
        } else {
          echo ("Est&aacute;s esperando ser admitido por este equipo.");
        }
      }

      // SOLICITADO PERO RECHAZADO
      if (($clan->j_solicitado == 1) && ($clan->solicitado_declinado == 1))
      {
        echo ("<br/>");
        if ($lang == 'en')
        {
          echo ("You have been rejected by this team.");
        } else {
          echo ("Has sido rechazado por este equipo.");
        }
      }

      // INVITADO PERO RECHAZASTE
      if (($clan->j_invitado == 1) && ($clan->invitado_declinado == 1))
      {
        echo ("<br/>");
        if ($lang == 'en')
        {
          echo ("You were invited by this team but you rejected it.");
        } else {
          echo ("Fuiste invitado por este equipo pero lo rechazaste.");
        }
      }


      // INVITADO , QUIERES UNIRTE?
      if (($clan->j_invitado == 1) && ($clan->invitado_declinado == 0) && ($clan->aceptado == 0))
      {
        echo ("<br/>");
        if ($lang == 'en')
        {
          echo ("You have been invited by this team. ");
          echo ("<a href=\"index.php?catid=".$catid."&idclan=".$idclan."&idcampana=".$idcampana."&accion=aceptar_invitacion\">");
          echo ("Click here to accept the invitation and join this team.");
          echo ("</a>");
        } else {
          echo ("Fuiste invitado por este equipo pero lo rechazaste.");
          echo ("<a href=\"index.php?catid=".$catid."&idclan=".$idclan."&idcampana=".$idcampana."&accion=aceptar_invitacion\">");
          echo ("Haz click aqu&iacute; para aceptar la invitaci&oacute;n y unirte a este equipo.");
          echo ("</a>");
        }
      }

      // Y LA OPCION DE SOLICITAR ENTRAR

//      if (($tengoclan == false) &&
//	($clan->j_solicitado == 0) &&
//	($clan->j_baneado == 0) &&
//	($clan->j_invitado == 0)
//	)
      if (($existe_relacion == false) && ($tengoclan == false))
      {
        echo ("<br/>");
        if ($lang == 'en')
        {
          echo ("You can ask this team to join. ");
          echo ("<a href=\"index.php?catid=".$catid."&idclan=".$idclan."&idcampana=".$idcampana."&accion=solicitar\">");
          echo ("Click here to send a request.");
          echo ("</a>");
        } else {
          echo ("Puedes pedirle a este equipo unirte a ellos. ");
          echo ("<a href=\"index.php?catid=".$catid."&idclan=".$idclan."&idcampana=".$idcampana."&accion=solicitar\">");
          echo ("Haz click aqu&iacute; para enviar una petici&oacute;n.");
          echo ("</a>");
        }
      }




    } else {
      if ($lang == 'en')
      {
        echo ("Team doesn't exist.");
      } else {
        echo ("El equipo no existe.");
      }
      $accion = "listar_clanes";
    }


  }


  // ***********************************
  //     Creacion efectiva del clan
  // ***********************************

  if ($accion == 'crear_clan_efectivo')
  {

    // Primero comprobamos que no pertenezca ya a un clan
    $clan = new Clan();
    $clan->link_r = $link_r;
    $clan->link_w = $link_w;

//    if ($clan->ObtieneClanJugador($idjugador, $idcampana) == true)
    if ($tengoclan == true)
    {
      if ($lang == 'en')
      {
        echo ("You already belong to a team");
      } else {
        echo ("Ya perteneces a un equipo");
      }
      $accion = "listar_clanes";
    } else {

      $identificador = $secure->Sanitizar($_REQUEST['identificador']);
      if (strlen($identificador) <= 4)
      {

        $jugador_campana = new Jugador_campana();

        // Ahora comprobamos si tiene pasta, y si la tiene lo creamos
        $result = $jugador_campana->RestarDinero($link_w, $idjugador, $idcampana, 15);
        if ($result == 0)
        {

          // Vale, dinero restado, ya solo nos falta crearlo y volver
          $clan->identificador = $identificador;
          $clan->nombre = $secure->Sanitizar($_REQUEST['nombre']);
          $clan->presentacion = $secure->Sanitizar($_REQUEST['presentacion']);
          $idclan = $clan->CrearClan($idjugador, $idcampana);
//          $idnuevoclan = mysql_insert_id($link_w);
          if ($lang == 'en')
          {
            echo ("Team created<br/>");
          } else {
            echo ("Equipo creado<br/>");
          }
          echo ("<br/>");
          $accion = null;


          // Generamos un log para este usuario
          $log = new Log();
          $log->idjugador = $idjugador;
          $log->idcampana = $idcampana;
          $log->tipo_suceso = 13; // 13, crear un clan
          $log->valor = $idclan; // watever
          $log->EscribirLog($link_w);

        } else {
          if ($lang == 'en')
          {
            echo ("You lack the money to do this.");
          } else {
            echo ("No tienes dinero para hacer esto.");
          }
        }


      } else {
        if ($lang == 'en')
        {
          echo ("Identifier can't be longer than 4 characters.");
        } else {
          echo ("El identificador no puede tener m&aacute;s de 4 caracteres.");
        }
        $nombre = $secure->Sanitizar($_REQUEST['nombre']);
        $presentacion = $secure->Sanitizar($_REQUEST['presentacion']);
        $accion = 'crear_clan';
      }


    }

  }


  // ***********************************
  //   Formulario para crear el clan
  // ***********************************

  if ($accion == 'crear_clan')
  {

    $clan = new Clan();
    $clan->link_r = $link_r;

    if ($tengoclan == true)
//    if ($clan->ObtieneClanJugador($idjugador, $idcampana))
    {
      if ($lang == 'en')
      {
        echo ("You already belong to a team");
      } else {
        echo ("Ya perteneces a un equipo");
      }
      $accion = "listar_clanes";
    } else {
    ?>
    <form method="post" action="index.php">
      <input type="hidden" name="accion" value="crear_clan_efectivo">
      <input type="hidden" name="idcampana" value="<?php echo $idcampana; ?>">
      <input type="hidden" name="catid" value="<?php echo $catid; ?>">

    <p>
    <?php
      if ($lang == 'en')
      {
        echo ("Team name :");
      } else {
        echo ("Nombre del equipo :");
      }
    ?>
    <input type="text" size="70" name="nombre" value="<?php echo $nombre; ?>">
    </p>
    <br/>

    <p>
    <?php
      if ($lang == 'en')
      {
        echo ("Identifier (4 characters max):");
      } else {
        echo ("Identificador (4 caracteres m&aacute;ximo):");
      }
    ?>
    <input type="text" size="4" name="identificador">
    </p>
    <br/>


    <p>
    <?php
      if ($lang == 'en')
      {
        echo ("Introductory text :");
      } else {
        echo ("Texto de presentaci&oacute;n :");
      }
    ?></p><p>
    <textarea name="presentacion" cols="80" rows="10"><?php echo $presentacion; ?></textarea>
    </p>
    <br/>

    <?php
      if ($lang == 'en')
      {
        echo ("<input type=\"submit\" value=\"Create clan\">");
      } else {
        echo ("<input type=\"submit\" value=\"Crear clan\">");
      }
      echo ("<span class=\"goldcoin\">15</span>");
      echo ("<img src=\"img/goldcoin.gif\" style=\"vertical-align:middle;\">");
    ?>
    </form>
    <?php

    } // Cierra el if ya perteneces a un clan

  }






  // ***********************************
  //    Escribir en el muro
  // ***********************************

  if ($accion == 'muro_escribir')
  {
    $texto = $secure->Sanitizar($_REQUEST['texto']);
    if (strlen($texto) <= 255)
    {
      // Vamos a insertar
      if ($tengoclan == true)
      {
        $clan_muro = new Clan_Muro();
        $clan_muro->Escribir_Muro($link_w, $miclan->id, $idjugador, $texto);
        $accion = null;
      }
    } else {
      echo ("<p class=\"errorsutil\">");
      if ($lang == 'en')
      {
        echo ("Error: Maximum length is 255 characters");
      } else {
        echo ("Error: La longitud m&aacute;xima son 255 caracteres");
      }
      echo ("</p>");
    }
    $accion = null;
  }



  // ***********************************
  //     PAGINA STANDARD DE CLAN
  // ***********************************

  if ($accion == null)
  {
    //  Primero vamos a ver si el jugador pertenece o no a un clan.
    // Si no pertenece a un clan, nos vamos a listar_clanes

  //  $clan = new Clan();
//    $clan->link_r = $link_r;
//    if ($clan->ObtieneClanJugador($idjugador, $idcampana))
    if ($tengoclan == true)
    {

	?>
     <script>
       function confirmarEliminar(delUrl) {
	   <?php
		if ($lang == 'en')
		{
		?>
		   if (confirm("Are you sure you want to dissolve your team?")) {
		    document.location = delUrl;
		   }
		<?php
		} else {
		?>
		   if (confirm("Seguro que deseas disolver tu equipo?")) {
		    document.location = delUrl;
		   }
		<?php
		}
		?>
       }
     </script>

	<?php

      MostrarDatosClan($miclan, $lang);



      // ············ Si eres administrador de este clan ················
      if (($miclan->administrador == 1) || ($miclan->fundador == 1))
      {
        if ($miclan->administrador == 1)
        {
          echo ("<br/>");
          if ($lang == 'en')
          {
            echo ("You are an administrator in this team.");
          } else {
            echo ("Eres un administrador de este equipo.");
          }
        }
        if ($miclan->fundador == 1)
        {
          echo ("<br/>");
          if ($lang == 'en')
          {
            echo ("You are the creator of this team.");
          } else {
            echo ("Eres el fundador de este equipo.");
          }
        }

        $array_solicitudes = $miclan->Listar_Solicitudes($miclan->id);
        if (count($array_solicitudes) > 0)
        {
          echo ("<br/>");
          echo ("<br/>");
          echo ("<table class=\"tabla_chat\" width=\"100%\">");
//          echo ("<table width=\"90%\">");
          echo ("<tr style=\"background-color: #331111;\">");
          echo ("<th colspan=\"4\">");
          if ($lang == 'en')
          {
            echo ("Join requests pending");
          } else {
            echo ("Peticiones de uni&oacute;n pendientes");
          }
          echo ("</th></tr>");
          echo ("<tr>");
          if ($lang == 'en')
          {
            echo ("<th width=\"130px\">Date</th>");
            echo ("<th width=\"200px\">User</th>");
            echo ("<th width=\"50px\">Approve</th>");
            echo ("<th width=\"50px\">Reject</th>");
          } else {
            echo ("<th width=\"130px\">Fecha</th>");
            echo ("<th width=\"200px\">Usuario</th>");
            echo ("<th width=\"50px\">Aprobar</th>");
            echo ("<th width=\"50px\">Rechazar</th>");
          }
	  echo ("</tr>");

          for ($i = 1; $i <= count($array_solicitudes); $i++)
          {
            echo ("<tr style=\"background-color: #221111;\">");
            echo ("<td style=\"text-align: center;\">");
//            echo ("<br/>");
            echo ($array_solicitudes[$i]['fecha_union']);
            echo ("</td><td style=\"text-align: center;\">");
            echo ("<a href=\"index.php?catid=5&accion=ver&idcampana=".$idcampana."&idelemento=".$array_solicitudes[$i]['idjugador']."\">");
            echo ($array_solicitudes[$i]['login']);
            echo ("</a>");
            echo ("</td><td style=\"text-align: center;\">");
            echo ("<a href=\"index.php?catid=".$catid."&idcampana=".$idcampana."&accion=aprobar_solicitud&idsolicitud=".$array_solicitudes[$i]['id']."\">");

echo ("<img src=\"img/thumbs_up.gif\" style=\"vertical-align: middle;\">");
//            if ($lang == 'en')
//            {
//              echo ("[approve]");
//            } else {
//              echo ("[aprobar]");
  //          }
            echo ("</a>");
            echo ("</td><td style=\"text-align: center;\">");
            echo ("<a href=\"index.php?catid=".$catid."&idcampana=".$idcampana."&accion=rechazar_solicitud&idsolicitud=".$array_solicitudes[$i]['id']."\">");
echo ("<img src=\"img/thumbs_down.gif\" style=\"vertical-align: middle;\">");
//            if ($lang == 'en')
//            {
//              echo ("[reject]");
//            } else {
//              echo ("[rechazar]");
//            }
            echo ("</a>");
            echo ("</td></tr>");

          }

          echo ("</table>");

        }
      } // Fin de si eres admin del clan

      // Opcion de listar los miembros del clan
		//
		echo ("<br/>");
		echo ("<br/>");
		
		echo ("<a href=\"index.php?catid=".$catid."&idcampana=".$idcampana."&accion=listar_miembros\">");
                if ($lang == 'en')
                {
	  	  echo ("List members");
                } else {
	  	  echo ("Listar miembros");
                }
		echo ("</a>");      


	//  Ahora las opciones de disolver el equipo (si eres fundador)
	// y simplemente dejarlo (en cq otro caso)
	echo ("<br/>");
	echo ("<br/>");
        if ($miclan->fundador == 1)
        {
          echo ("<a href=\"javascript:confirmarEliminar('index.php?catid=".$catid."&idcampana=".$idcampana."&accion=disolver')\">");
          if ($lang == 'en')
          {
            echo ("Dissolve team");
          } else {
            echo ("Disolver el equipo");
          }
          echo ("</a>");
        } else {
          echo ("<a href=\"index.php?catid=".$catid."&idcampana=".$idcampana."&accion=dejar\">");
          echo ("Dejar el equipo");
          echo ("</a>");
        }


	// Opcion de editar
        if ($miclan->fundador == 1)
        {
  	  echo ("<br/>");
 	  echo ("<br/>");
          echo ("<a href=\"index.php?catid=".$catid."&idcampana=".$idcampana."&accion=editar_opciones\">");
          if ($lang == 'en')
          {
            echo ("Edit team options");
          } else {
            echo ("Editar opciones del equipo");
          }
          echo ("</a>");
	}


        echo ("<br/>");
        echo ("<br/>");
        echo ("<br/>");
        // Muro del clan
        $clan_muro = new Clan_Muro();
        $numelementos = $clan_muro->Contar_Muro($link_r, $miclan->id);
//echo ("#".$numelementos);
        $offset = 0;
        $limit = 20;
        $array = $clan_muro->Buscar_Muro($link_r, $miclan->id, $limit, $offset);
        echo ("<table class=\"tabla_chat\" width=\"100%\">");
        echo ("<tr style=\"background-color: #441111;\">");
        echo ("<th>Team chat</th>");
        echo ("</tr>");
        if ($numelementos > 0)
        {
//          echo ("<table style=\" padding: 3px;
//			\">");
          for ($i = 1; $i <= count($array); $i++)
          {
            if (($i % 2) == 1)
            {
              echo ("<tr style=\"background-color: rgb(17, 17, 17); text-align: left; \">");
            } else {
              echo ("<tr style=\"background-color: rgb(31, 31, 31); text-align: left; \">");
            }
            echo ("<td>");
//            echo ("<i>".$array[$i]['fecha']."</i> - <b>");
            // AJUSTANDO LA HORA ** AJUSTANDO LA HORA ** AJUSTANDO LA HORA
                $jugador_aux = new Jugador($link_r);
                $jugador_aux->SacarDatos($link_r, $idjugador);
	        $id_tmz = $jugador_aux->id_tmz;
	        $tmz = new TMZ();
	        $tmz->SacarDatos($link_r, $id_tmz);
	        $hora_servidor = -6;
	        $min_servidor = 0;
	        $diferencia_hora = $tmz->tmz_hour - $hora_servidor;
	        $diferencia_min = $tmz->tmz_min - $min_servidor;

	        $anyo = substr($array[$i]['fecha'], 0, 4);
	        $mes = substr($array[$i]['fecha'], 5, 2);
	        $dia = substr($array[$i]['fecha'], 8, 2);
	        $hora = substr($array[$i]['fecha'], 11, 2);
	        $min = substr($array[$i]['fecha'], 14, 2);
	        // Ajustamos la fecha con el TMZ
	        $hora = $hora + $diferencia_hora;
	        $min = $min + $diferencia_min;
	        $arrayf = AjustarFecha($anyo, $mes, $dia, $hora, $min);
	        $anyo = $arrayf['anyo']; $mes = $arrayf['mes']; $dia = $arrayf['dia']; $hora = $arrayf['hora']; $min = $arrayf['min'];
		echo ("<i>".$dia."/".$mes."/".$anyo." ".$hora.":".$min."</i> - <b>");
/*
	        if ($lang == 'en')
	        {
	          if ($dia == '01') { echo ("1st "); } else {
	            if ($dia == '02') { echo ("2nd "); } else {
	              if ($dia == '02') { echo ("3rd "); } else {
	                echo (abs($dia)."th ");
	              }
	            }
	          }
	          echo (" of ".$array_months[(abs($mes))].", ".$anyo.", ".$hora.":".$min);
	        } else {
	          echo (abs($dia)." de ".$array_meses[(abs($mes))]." de ".$anyo.", ".$hora.":".$min);
	        }
*/
            // AJUSTANDO LA HORA ** AJUSTANDO LA HORA ** AJUSTANDO LA HORA

            if ($idjugador != $array[$i]['idjugador'])
            {
              echo ("<a href=\"index.php?catid=5&accion=ver&idcampana=".$idcampana."&idelemento=".$array[$i]['idjugador']."\">");
              echo ($array[$i]['login']);
              echo ("</a>");
            } else {
              echo ($array[$i]['login']);
            }
            echo (" : </b>");
            echo ($array[$i]['texto']);
            echo ("</td>");
            echo ("</tr>");
          }
          echo ("</table>");
        } else {
          echo ("<tr style=\"background-color: #111111;\">");
          echo ("<td>");
          echo ("<span class=\"errorsutil\">");
          if ($lang == 'en')
          {
            echo ("Team chat is empty");
          } else {
            echo ("El chat de equipo est&aacute; vac&iacute;o");
          }
          echo ("</span>");
          echo ("</td>");
          echo ("</tr>");
          echo ("</table>");
        }
        ?>
        <br/>
        <form method="post" action="index.php">
          <input type="hidden" name="accion" value="muro_escribir">
          <input type="hidden" name="idcampana" value="<?php echo $idcampana; ?>">
          <input type="hidden" name="catid" value="<?php echo $catid; ?>">
          <input type="text" name="texto" size="70" style="background-color: #000000;
		                border: solid 1px #442200;
		                color: #ffffff;
				"
					>
          <?php
            if ($lang == 'en')
            {
            ?>
              <input type="submit" value="Send">
            <?php
            } else {
            ?>
              <input type="submit" value="Enviar">
            <?php
            }
            ?>
        </form>
        <?php


    } else {
      $noclan = 1;
      $accion = 'listar_clanes';
    }
  }


  // ***********************************
  //     PAGINA LISTAR CLANES
  // ***********************************

  if ($accion == 'listar_clanes')
  {

    // Esta parte por si no tienes clan
    if ($noclan == 1)
    {
      echo ("<p><a href=\"index.php?catid=".$catid."&idcampana=".$idcampana."&accion=crear_clan\">");
      if ($lang == 'en')
      {
        echo ("Create a team");
      } else {
        echo ("Fundar un equipo");
      }
      echo ("</a>");
      echo ("</b></span>&nbsp;");
      echo ("<span class=\"goldcoin\">15</span>");
      echo ("<img src=\"img/goldcoin.gif\" style=\"vertical-align:middle;\">");
      echo ("</a></b>");
    }

    // Listado normal
    echo ("<div id=\"espacio\" class=\"espacio\">");
    echo ("</div>");


	// PREPARAMOS LOS AJUSTES DE HORA
                $jugador_aux = new Jugador($link_r);
                $jugador_aux->SacarDatos($link_r, $idjugador);
                $id_tmz = $jugador_aux->id_tmz;
                $tmz = new TMZ();
                $tmz->SacarDatos($link_r, $id_tmz);
                $hora_servidor = -6;
                $min_servidor = 0;
                $diferencia_hora = $tmz->tmz_hour - $hora_servidor;
                $diferencia_min = $tmz->tmz_min - $min_servidor;
	// PREPARAMOS LOS AJUSTES DE HORA (FIN)


    $clan = new Clan();
    $clan->link_r = $link_r;
    $clan->ObtieneClanJugador($idjugador, $idcampana);

    $limitelementos = 20;
    $pg = $_REQUEST['pg'];
    if ($pg == null) { $pg = 1; }
    if (!is_numeric($pg))
    {
      die;
    }
    $offset = (($pg - 1) * $limitelementos);

    $numelementostotal = $clan->ContarTodos($idjugador, $idcampana);
    $array = $clan->BuscarTodos($idjugador, $idcampana, $offset, $limitelementos);
//echo $numelementostotal."#";

   echo '<div class="caja-text-long corner-top">';
   echo '<p class="textonormal left" style="width: 150px">Total: </p>';
   if ($lang == 'en')
   {
     echo '<p class="textonormal left" style="margin-left: 10px;"><b>'.$numelementostotal.' teams</b></p>';
   } else {
     echo '<p class="textonormal left" style="margin-left: 10px;"><b>'.$numelementostotal.' equipos</b></p>';
   }
   echo '<p class="clear-both"></p>';
   echo '</div>';


      // ------------------------------> Paginado <--------------------------------------

      echo ("<p style='margin-bottom: 10px; margin-top: 10px;'>");
      if ($pg > 1)
      {
        $pgant = $pg - 1;
        echo ("<a class=\"paginado\" href=\"index.php?catid=".$catid.
        "&accion=listar_clanes&idcampana=".$idcampana."&pg=".$pgant."\" title='P&aacute;gina anterior'>P&aacute;gina anterior</a>");
        $pgf = 1;
      }

     if ( ($offset + $limitelementos) < $numelementostotal)
     {
       if ($pgf == 1) { echo ("<span class=\"paginado\"> - </span>"); }
       $pgsig = $pg + 1;
       echo ("<a class=\"paginado\" href=\"index.php?catid=".$catid.
        "&accion=listar_clanes&idcampana=".$idcampana."&pg=".$pgsig."\" title='P&aacute;gina siguiente'>P&aacute;gina siguiente</a></p>");
     }
     // Pagina $pg de $totpg
     $totpg = floor(($numelementostotal - 1) / $limitelementos) + 1;
     if ($totpg < 1) { $totpg = 1; }
     // Y ahora el for


//     echo '<div style="width: 770px">';
//     echo '<div id="" style="float:left; margin-right: 30px;">';

     ?>
     <form method="post" action="index.php">
     <input type="hidden" name="catid" value="<?php echo $catid;?>">
     <input type="hidden" name="accion" value="listar_clanes">
     <input type="hidden" name="idcampana" value="<?php echo $idcampana;?>">
     <?php
     if ($lang == 'en')
     {
       ?>
       <p class="textonormal pag">Go to page : <select name="pg">
     <?php } else { ?>
       <p class="textonormal pag">Ir a p&aacute;gina : <select name="pg">
     <?php } ?>
     <?php
        for ($k = 1 ; $k <= $totpg ; $k++) {
                if ($k == $pg) {
                        echo ("<option value=\"".$k."\" selected = \"selected\">".$k."</option>");
                } else {
                        echo ("<option value=\"".$k."\">".$k."</option>");
                }
        }
     echo ("</select>");
     if ($lang == 'en')
     {
       echo (" of ");
     } else {
       echo (" de ");
     }
     echo ($totpg." <input type=\"submit\" value=\"Ir\"></p></form>");
//         echo ("</select> de ".$totpg." </p><input type=\"image\" src=\"images/structure/btn-ir.jpg\" value=\"Ir\" class='btn-ir' 
//onmouseover=\"javascript:this.src='images/structure/btn-ir_rol.jpg';\" onmouseout=\"javascript:this.src='images/structure/btn-ir.jpg';\"><p class='clear-both'></p></form>");

      // ------------------------------> Paginado <--------------------------------------



    echo ("<div id=\"espacio\" class=\"espacio\">");
    echo ("</div>");

     echo ("<table id=\"tabla_clan\" class=\"tabla_clan\">");
      echo ("<tr>");
      echo ("<th width=\"50px\">Logo</th>");
      if ($lang == 'en')
      {
        echo ("<th width=\"230px\">Name</th>");
        echo ("<th width=\"30px\">Members</th>");
        echo ("<th width=\"200px\">Creation date</th>");
//        echo ("<th width=\"80px\">Join</th>");
      } else {
        echo ("<th width=\"230px\">Nombre</th>");
        echo ("<th width=\"30px\">Miembros</th>");
        echo ("<th width=\"200px\">Fecha creaci&oacute;n</th>");
//        echo ("<th width=\"80px\">Unirse</th>");
      }
    echo ("</tr>");
    echo ("<tr>");



    if ($numelementostotal > 0)
    {
      $mostrar = ($numelementostotal - $offset);
      if ($mostrar > $limitelementos){ $mostrar = $limitelementos; }

//      for ($i = 1; $i <= count($array); $i++)
     for ($i = 1; $i <= $mostrar; $i++)
      {

        if ($i % 2 == 1)
        {
          echo ("<tr style=\"background-color: #111111;\">");
        } else {
          echo ("<tr style=\"background-color: #222222;\">");
        }


//        echo ("<tr>");

        echo ("<td>");
	if (($array[$i]['ruta_avatar'] != '') &&
		($array[$i]['ruta_avatar'] != null))
        {
//echo $array[$i]['ruta_avatar']."#";
//		$nombre = $miclan->ruta_avatar;
		$nombre = $array[$i]['ruta_avatar'];
		$nombre_thumb = substr($nombre, 0, strlen($nombre) - 4)."_thumb".substr($nombre, (strlen($nombre) - 4), strlen($nombre));
                echo ("<img src=\"".$ruta_avatar_equipo.$nombre_thumb."\" 
			style=\"border:1px solid #000000; padding: 1px;
				vertical-align: middle;
				background-color: #955728;
				\"
			>");
        }
        echo ("</td>");


        echo ("<td>");
        // Si es tu propio clan no te manda a ver_clan
        if ($array[$i]['id'] == $clan->id)
        {
          echo ("<a href=\"index.php?catid=".$catid."&idcampana=".$idcampana."&accion=\">");
          echo ($array[$i]['nombre']);
          echo ("</a>");
        } else {
          echo ("<a href=\"index.php?catid=".$catid."&idcampana=".$idcampana."&accion=ver_clan&idclan=".$array[$i]['id']."\">");
          echo ($array[$i]['nombre']);
          echo ("</a>");
        }
        echo ("</td>");

        echo ("<td>");
        echo ($array[$i]['nmiembros']);
        echo ("</td>");


        echo ("<td>");
//        echo ($array[$i]['fecha']);
        $anyo = substr($array[$i]['fecha_fundacion'], 0, 4);
        $mes = substr($array[$i]['fecha_fundacion'], 5, 2);
        $dia = substr($array[$i]['fecha_fundacion'], 8, 2);
//        $hora = substr($array[$i]['fecha_fundacion'], 11, 5);
        $hora = substr($array[$i]['fecha_fundacion'], 11, 2);
        $min = substr($array[$i]['fecha_fundacion'], 14, 2);
//	        $anyo = substr($array[$i]['fecha'], 0, 4);
//	        $mes = substr($array[$i]['fecha'], 5, 2);
//	        $dia = substr($array[$i]['fecha'], 8, 2);
//	        $hora = substr($array[$i]['fecha'], 11, 2);
//	        $min = substr($array[$i]['fecha'], 14, 2);
	        // Ajustamos la fecha con el TMZ
	        $hora = $hora + $diferencia_hora;
	        $min = $min + $diferencia_min;
	        $arrayf = AjustarFecha($anyo, $mes, $dia, $hora, $min);
	        $anyo = $arrayf['anyo']; $mes = $arrayf['mes']; $dia = $arrayf['dia']; $hora = $arrayf['hora']; $min = $arrayf['min'];



        if ($lang == 'en')
        {
          if ($dia == '01') { echo ("1st "); } else {
            if ($dia == '02') { echo ("2nd "); } else {
              if ($dia == '02') { echo ("3rd "); } else {
                echo (abs($dia)."th ");
              }
            }
          }
          echo (" of ".$array_months[(abs($mes))].", ".$anyo.", ".$hora.":".$min);
        } else {
          echo (abs($dia)." de ".$array_meses[(abs($mes))]." de ".$anyo.", ".$hora.":".$min);
        }
        echo ("</td>");

	echo ("</tr>");
      }
    } else {
      echo ("</table><br/>");
      if ($lang == 'en')
      {
        echo ("There are still no teams.");
      } else {
        echo ("No existen equipos todav&iacute;a.");
      }
    }
    echo ("</table>");







  }


?>
