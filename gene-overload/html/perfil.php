<?php

  include("clases/obj_jugador_fotoperfil.php");


//  include("clases/obj_clan.php");


  $debug = $_REQUEST['debug'];
  if (!is_numeric($debug))
  {
    $debug = 0;
  }





  include ("perfil_foto.php");

  // **************************************
  //   Invitacion
  // **************************************

  if ($accion == 'invitar')
  {

    $idelemento = $_REQUEST['idelemento'];
    if (!is_numeric($idelemento))
    {
      die;
    }

    $jugador = new Jugador();
    $jugador->SacarDatos($link_r, $idelemento);

    $miclan = new Clan();
    $miclan->link_r = $link_r;
    $miclan->link_w = $link_w;
    if ($miclan->ObtieneClanJugador($idjugador, $idcampana) == true)
    {
      if (($miclan->administrador == 1) || ($miclan->fundador == 1))
      {
        // Ahora hay que comprobar que es invitable
        if ($miclan->EsInvitable($jugador->id, $idcampana, $miclan->id) == true)
        {
          $idelemento = $_REQUEST['idelemento'];
          if (!is_numeric($idelemento))
          {
            die;
          }
          $jugador = new Jugador();
          $jugador->SacarDatos($link_r, $idelemento);
          $jugador_campana = new Jugador_campana();
          $jugador_campana->SacarDatos($link_r, $idelemento, $idcampana);

          // Asi que le invitamos
          $miclan->InvitarUsuario($jugador_campana->id, $miclan->id);

          // Y por ultimo generamos un informe al respecto
          $informe = new Informe();
          $informe->tipo = 5;
          if ($lang == 'en')
          {
            $informe->subject = 'You have been invited to the team "'.$miclan->nombre.'"';
            $informe->texto = 'You have been invited to a scientific investigation team called <a href="index.php?catid=9&idcampana='.$idcampana.
		'&accion=ver_clan&idclan='.$miclan->id.'">'.$miclan->nombre.
		'</a>. If you want to accept this invitation then <a href="index.php?catid=9&accion=aceptar_invitacion&idcampana='.$idcampana.'&idclan='.$miclan->id.
		'">click here</a>. You can also decline this invitation clicking <a href="index.php?catid=9&idcampana='.
		$idcampana.'&idclan='.$miclan->id.'&accion=declinar_invitacion&idjugadorcampana='.$jugador_campana->id.'">here</a>';
          } else {
            $informe->subject = 'Has sido invitado al equipo '.$miclan->nombre.'"';
            $informe->texto = 'Has sido invitado a un equipo de investigaci&oacute;n cient&iacute;fica llamado <a href="index.php?catid=9&idcampana='.$idcampana.
                '&accion=ver_clan&idclan='.$miclan->id.'">'.$miclan->nombre.
		'</a>. Si quieres aceptar la invitaci&oacute;n puedes <a href="index.php?catid=9&accion=aceptar_invitacion&idcampana='.$idcampana.'&idclan='.$miclan->id.
		'">pulsar aqu&iacute;</a>. Tambi&eacute;n puedes declinar la invitaci&oacute;n pulsando <a href="index.php?catid=9&idcampana='.
		$idcampana.'&idclan='.$miclan->id.'&accion=declinar_invitacion&idjugadorcampana='.$jugador_campana->id.'">aqu&iacute;</a>';
          }
          $informe->EnviarInformeRaw($link_w, $jugador->id, $idcampana);
        }
      }
    }
    $accion = 'ver';

  }



  // **************************************
  //   Ver un perfil de otro
  // **************************************

  if ($accion == 'ver')
  {

    $idelemento = $_REQUEST['idelemento'];
    if (!is_numeric($idelemento))
    {
      die;
    }

    $jugador = new Jugador();
    $jugador->SacarDatos($link_r, $idelemento);

    echo ("<center>");
    echo ("<table width=\"100%\" bgcolor=\"#331111\">");
    echo ("<tr><td style=\"text-align: center; line-height: 2em;\">");
    echo ("<p>");
    echo ("<b>");
    if ($lang == 'en')
    {
      echo ($jugador->login.'\'s ');
      echo ("Profile options and statistics");
    } else {
      echo ("Opciones de perfil y estad&iacute;sticas de ".$jugador->login);
    }
    echo ("</b>");
    echo ("</p>");
    echo ("</td></tr></table>");
    echo ("</center>");

    $jugador_campana = new Jugador_campana();
    $jugador_campana->SacarDatos($link_r, $idelemento, $idcampana);


    $suclan = new Clan();
    $suclan->link_r = $link_r;
    $suclan->link_w = $link_w;

    ?>

    <form method="post" action="index.php">
      <input type="hidden" name="catid" value="<?php echo $catid; ?>">
      <input type="hidden" name="idcampana" value="<?php echo $idcampana; ?>">
      <input type="hidden" name="accion" value="alterar_perfil">

      <table width="100%" bgcolor="#221111" style="padding: 10px;">
      <tr>
      <td>

      <p><b>Login : </b><?php echo $jugador->login; ?></p>
      <br/>
      <?php
        if ($jugador->email_publico == 1)
        {
          ?>
             <p><b>Email : </b><?php echo $jugador->email; ?></p>
          <?php
        } else {
          if ($lang == 'en') {
          ?>
             <p><b>Email : </b><?php echo ("[hidden]"); ?></p>
          <?php
          } else {
          ?>
             <p><b>Email : </b><?php echo ("[oculto]"); ?></p>
          <?php
          }
        }
          ?>
      <br/>



      <?php
      if ($suclan->ObtieneClanJugador($idelemento, $idcampana) == true)
      {
        if ($lang == 'en')
        {
          echo ("<p><b>Team : <a href=\"index.php?catid=9&idcampana=".$idcampana."\">".$suclan->nombre."</a></b>");
        } else {
          echo ("<p><b>Equipo : <a href=\"index.php?catid=9&idcampana=".$idcampana."\">".$suclan->nombre."</a></b>");
        }

      } else {
        if ($lang == 'en')
        {
          echo ("<p><b>Team : </b><span class=\"errorsutil\"><b>Player does not belong to any team.</b></span>");
        } else {
          echo ("<p><b>Equipo : </b><span class=\"errorsutil\"><b>El jugador no pertenece a ning&uacute;n equipo.</b></span>");
        }
      }
      ?></p><br/>



      <p><b><?php
        if ($lang == 'en')
        {
          echo ("Sign-up date : ");
        } else {
          echo ("Fecha de alta : ");
        }
	?></b><?php
        $id_tmz = $jugador->id_tmz;
        $tmz = new TMZ();
        $tmz->SacarDatos($link_r, $id_tmz);
        $hora_servidor = -6;
        $min_servidor = 0;
        $diferencia_hora = $tmz->tmz_hour - $hora_servidor;
        $diferencia_min = $tmz->tmz_min - $min_servidor;

        $anyo = substr($jugador->fecha_alta, 0, 4);
        $mes = substr($jugador->fecha_alta, 5, 2);
        $dia = substr($jugador->fecha_alta, 8, 2);
        $hora = substr($jugador->fecha_alta, 11, 2);
        $min = substr($jugador->fecha_alta, 14, 2);
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
//echo $jugador->fecha_alta; 
		?></p>
      <br/>



	</td><td style="text-align: right;">

          <?php
            $jugador_fotoperfil = new Jugador_Fotoperfil();
            if ($jugador_fotoperfil->Obtener_Imagen_Jugador($link_r, $idelemento))
            {
              echo ("<img src=\"img/profile/".$jugador_fotoperfil->ruta."\" style=\"vertical-align: top;\">");
            } else {
              // No tiene imagen de perfil elegida.
              ?>
              <img src="img/profile/picstandard.jpg" style="vertical-align: top;">
              <?php
            }
          ?>

	</td></tr></table>




      <table width="100%" bgcolor="#1A1111" style="padding: 10px;">
      <tr>
      <td>


    <?php

    // ---------------- LIMIT -------------------



    $suclan = new Clan();
    $suclan->link_r = $link_r;
    $suclan->link_w = $link_w;

    echo ("<center>");

    if (($suclan->ObtieneClanJugador($idelemento, $idcampana) == true)
                && ($suclan->identificador != '')
                && ($suclan->identificador != null)
                 )
    {
      $stringclan = "<a class=\"clan\"
                        href=\"index.php?catid=9&idcampana=".$idcampana."&accion=ver_clan&idclan=".$suclan->id."\"
                        >[".$suclan->identificador."]</a>";
    } else {
      $stringclan = "";
    }

    echo ("</center>");

    ?>
      <p><b><?php
//        if ($lang == 'en')
//        {
//          echo ("Description :");
//        } else {
//          echo ("Descripci&oacute;n :");
//        }
        ?></b></p>
      <p><?php echo $jugador->texto_presentacion; ?></p>

	</td></tr></table>

      <table width="100%" bgcolor="#111111" style="padding: 10px;">
      <tr>
      <td>


    <?php





//    echo ("<hr/>");
    echo ("<center>");
    if ($lang == 'en')
    {
      echo ("<b>Statistical data</b>");
    } else {
      echo ("<b>Datos estad&iacute;sticos</b>");
    }
    echo ("</center>");
    echo ("<br/>");


    echo ("<p><b>");
    if ($lang == 'en')
    {
      echo ("Abyssal depths deme slots : ");
    } else {
      echo ("Slots del deme de las profundidades : ");
    }
    echo ("</b>");
    echo ($jugador_campana->num_slots_deme_profundidades);
    echo ("</p>");
    echo ("<br/>");
    echo ("<p><b>");
    if ($lang == 'en')
    {
      echo ("Forest deme slots : ");
    } else {
      echo ("Slots del deme del bosque : ");
    }
    echo ("</b>");
    echo ($jugador_campana->num_slots_deme_bosque);
    echo ("</p>");
    echo ("<br/>");

    echo ("<p><b>");
    if ($lang == 'en')
    {
      echo ("Vulcano deme slots : ");
    } else {
      echo ("Slots del deme del volc&aacute;n : ");
    }
    echo ("</b>");
    echo ($jugador_campana->num_slots_deme_volcan);
    echo ("</p>");
    echo ("<br/>");

    echo ("<p><b>");
    if ($lang == 'en')
    {
      echo ("Decission tree depth level : ");
    } else {
      echo ("Niveles de los &aacute;rboles de decisi&oacute;n : ");
    }
    echo ("</b>");
    echo ($jugador_campana->niveles_arbol);
    echo ("</p>");
    echo ("<br/>");

    echo ("<p><b>");
    if ($lang == 'en')
    {
      echo ("Gender amount : ");
    } else {
      echo ("N&uacute;mero de sexos en los espec&iacute;menes : ");
    }
    echo ("</b>");
    echo ($jugador_campana->num_sexos);
    echo ("</p>");
    echo ("<br/>");

    echo ("<p><b>");
    if ($lang == 'en')
    {
     echo ("Mutational intensity : ");
    } else {
     echo ("Intensidad de las mutaciones : ");
    }
    echo ("</b>");
    if ($jugador_campana->ratio_intensidad_mutacion == 1) { if ($lang == 'en') { echo ("soft"); } else { echo ("suave"); } }
    if ($jugador_campana->ratio_intensidad_mutacion == 2) { if ($lang == 'en') { echo ("medium"); } else { echo ("medio"); } }
    if ($jugador_campana->ratio_intensidad_mutacion == 3) { if ($lang == 'en') { echo ("hard"); } else { echo ("duro"); } }
    echo ("</p>");
    echo ("<br/>");

    echo ("<p><b>");
    if ($lang == 'en')
    {
      echo ("Overall tournaments played : ");
    } else {
      echo ("N&uacute;mero de torneos participados : ");
    }
    echo ("</b>");
    echo ($jugador_campana->num_torneos);
    echo ("</p>");
    echo ("<br/>");

    echo ("<p><b>");
    if ($lang == 'en')
    {
      echo ("Overall tournament victories : ");
    } else {
      echo ("N&uacute;mero de victorias en torneos : ");
    }
    echo ("</b>");
    echo ($jugador_campana->num_torneos_victorias);
    echo ("</p>");
    echo ("<br/>");

    echo ("<p><b>");
    if ($lang == 'en')
    {
      echo ("Overall evolved generations : ");
    } else {
      echo ("N&uacute;mero de generaciones evolucionadas : ");
    }
    echo ("</b>");
    echo ($jugador_campana->num_generaciones_total);
    echo ("</p>");
    echo ("<br/>");

    echo ("<p><b>");
    if ($lang == 'en')
    {
      echo ("Overall separately evolved demes : ");
    } else {
      echo ("N&uacute;mero de demes evolucionados individualmente : ");
    }
    echo ("</b>");
    echo ($jugador_campana->num_generaciones_demes);
    echo ("</p>");
    echo ("<br/>");

    echo ("<p><b>");
    if ($lang == 'en')
    {
      echo ("Overall separately evolved specimens : ");
    } else {
      echo ("N&uacute;mero de espec&iacute;menes evolucionados individualmente : ");
    }
    echo ("</b>");
    echo ($jugador_campana->num_generaciones_individual);
    echo ("</p>");
    echo ("<br/>");



    // -------------------------------------
    //    Invitarle a tu clan
    // -------------------------------------

    $miclan = new Clan();
    $miclan->link_r = $link_r;
    $miclan->link_w = $link_w;
    if ($miclan->ObtieneClanJugador($idjugador, $idcampana) == true)
    {
      if (($miclan->administrador == 1) || ($miclan->fundador == 1))
      {
        // Ahora hay que comprobar que es invitable
        if ($miclan->EsInvitable($jugador->id, $idcampana, $miclan->id) == true)
        {
//echo ("!");
          echo ("<br/>");
          echo ("<a href=\"index.php?catid=".$catid."&idcampana=".$idcampana."&accion=invitar&idelemento=".$idelemento."\">");
          if ($lang == 'en')
          {
            echo ("Invite this user to your team");
          } else {
            echo ("Invitar a este usuario a tu equipo");
          }
          echo ("</a>");
        }
      }
    }






//    $suclan = new Clan();
//    $suclan->link_r = $link_r;
//    $suclan->link_w = $link_w;

    // *********************** El clan del que forma parte ************************

    if ($suclan->ObtieneClanJugador($jugador->id, $idcampana) == true)
    {
      if ($lang == 'en')
      {
        echo ("<br/>This player belongs to the team <b>\"".$suclan->nombre."\"</b>");
      } else {
        echo ("<br/>Este jugador pertenece al equipo <b>\"".$suclan->nombre."\"</b>");
      }
    }




    // ------------------ Ahora sacamos detalles respecto a tu clan -----------------

//    $suclan = new Clan();
//    $suclan->link_r = $link_r;
//    $suclan->link_w = $link_w;


    if ($suclan->SacarDatosClanJugador($jugador_campana->id, $miclan->id) == true)
    {

      // ------------- Esta ya invitado pero no ha aceptado? -----------------

      if (($suclan->invitado == 1) && ($suclan->invitado_declinado == 0) && ($suclan->aceptado == 0))
      {
        echo ("<br/>");
        if ($lang == 'en')
        {
          echo ("This user has not decided if he accepts an invitation to your team.");
        } else {
          echo ("Este usuario est&aacute; pendiente de confirmar si acepta una invitaci&oacute;n a tu equipo.");
        }
      }

      // ------------- Esta pendiente de solicitado? -----------------

      if (($suclan->solicitado == 1) && ($suclan->aceptado == 0))
      {
        echo ("<br/>");
        if ($lang == 'en')
        {
          echo ("This user has requested to enter your team.");
        } else {
          echo ("Este usuario ha pedido entrar en tu equipo.");
        }
      }

      // ------------- Esta baneado? -----------------

      if ($suclan->baneado == 1)
      {
        echo ("<br/>");
        if ($lang == 'en')
        {
          echo ("This user has been banned from your team.");
        } else {
          echo ("Este usuario ha sido expulsado de tu equipo.");
        }
      }

      // ------------- Es parte del clan? ----------------

      if (($suclan->aceptado == 1) && ($suclan->baneado == 0))
      {
        echo ("<br/>");
        if ($lang == 'en')
        {
          echo ("This user is part of your team.");
        } else {
          echo ("Este usuario es parte de tu equipo.");
        }
      }




    }

    echo ("</td></tr></table>");


  }


  // **************************************
  //   Activar el modo debug
  // **************************************

  if (($accion == "activar_debug") && ($es_admin == 1))
  {

    $jugador_campana = new Jugador_campana();
    $jugador_campana->AlterarDebug($link_w, $idjugador, $idcampana, 1);
    if ($lang == 'en')
    {
      echo ("<p class=\"correctosutil\">Debug mode activated</p>");
    } else {
      echo ("<p class=\"correctosutil\">Modo debug activado</p>");
    }
    $accion = null;

  }

  // **************************************
  //   Desactivar el modo debug
  // **************************************

  if (($accion == "desactivar_debug") && ($es_admin == 1))
  {

    $jugador_campana = new Jugador_campana();
    $jugador_campana->AlterarDebug($link_w, $idjugador, $idcampana, 0);
    if ($lang == 'en')
    {
      echo ("<p class=\"correctosutil\">Debug mode deactivated</p>");
    } else {
      echo ("<p class=\"correctosutil\">Modo debug desactivado</p>");
    }
    $accion = null;

  }



  // **************************************
  //   Alterar datos de perfil
  // **************************************

  if ($accion == "alterar_perfil")
  {

    $jugador = new Jugador();

    $email_publico = $secure->Sanitizar($_REQUEST['email_publico']);
//echo $email_publico;
    if (!is_numeric($email_publico)) { die; }
    $jugador->email_publico = $email_publico;

    $jugador->nombre = $secure->Sanitizar($_REQUEST['nombre']);
    $jugador->lang = $secure->Sanitizar($_REQUEST['lang']);
    $jugador->texto_presentacion = $secure->Sanitizar($_REQUEST['texto_presentacion']);

    $envio_emails = $secure->Sanitizar($_REQUEST['envio_emails']);
//echo $envio_emails;
    if (!is_numeric($envio_emails)) { die; }
    $jugador->envio_emails = $envio_emails;

    $envio_boletines = $secure->Sanitizar($_REQUEST['envio_boletines']);
//echo $envio_boletines;
    if (!is_numeric($envio_boletines)) { die; }
    $jugador->envio_boletines = $envio_boletines;

//echo ("!");

    $id_tmz = $secure->Sanitizar($_REQUEST['id_tmz']);
//echo $id_tmz;
    if (!is_numeric($id_tmz)) { die; }
    $jugador->id_tmz = $id_tmz;

    $jugador->GrabarDatos($link_w, $idjugador);

    if ($lang == 'en')
    {
      echo ("<p class=\"correctosutil\">Profile data changed</p>");
    } else {
      echo ("<p class=\"correctosutil\">Datos de perfil alterados</p>");
    }
    echo ("<br/>");
    $accion = null;

  }



  // **************************************
  //   Cambiar la clave
  // **************************************

  if ($accion == "alterar_clave")
  {
    // Esto se ha pasado mayormente al idnex.php
    echo $error;
    echo ("<br/>");
    $accion = null;
  }
/*
    $jugador = new Jugador();
    $clave = $secure->Sanitizar($_REQUEST['clave']);
    $clave2 = $secure->Sanitizar($_REQUEST['clave2']);
    if ($clave == $clave2)
    {
      if ($clave != '')
      {

        $jugador->clave = $clave;
        $jugador->CambiarClave($link_w, $idjugador);


        if ($lang == 'en')
        {
          echo ("<p class=\"correctosutil\">Password changed</p>");
        } else {
          echo ("<p class=\"correctosutil\">Clave cambiada</p>");
        }

        // Actualizamos para la sesion o cookies del player
        $_SESSION['REMOTE_PASS'] = $clave;
        setcookie("password", $clave, time()+60*60*24*100, "/");



      } else {
        if ($lang == 'en')
        {
          echo ("<p class=\"error\">Error: Password is empty.</p>");
        } else {
          echo ("<p class=\"error\">Error: Las claves est&aacute; vac&iacute;a.</p>");
        }
      }
    } else {
      if ($lang == 'en')
      {
        echo ("<p class=\"error\">Error: Passwords do not match.</p>");
      } else {
        echo ("<p class=\"error\">Error: Las claves no coinciden.</p>");
      }
    }

    echo ("<br/>");
    $accion = null;

  }

*/

  // **************************************
  //   EDITAR OPCIONES DE PERFIL
  // **************************************

  if ($accion == null)
  {

    echo ("<center>");
    echo ("<table width=\"100%\" bgcolor=\"#331111\">");
    echo ("<tr><td style=\"text-align: center; line-height: 2em;\">");
    echo ("<p>");
    echo ("<b>");
    if ($lang == 'en')
    {
      echo ("Profile options and statistics");
    } else {
      echo ("Opciones de perfil y estad&iacute;sticas");
    }
    echo ("</b>");
    echo ("</p>");
    echo ("</td></tr></table>");
    echo ("</center>");

    $jugador_campana = new Jugador_campana();
    $jugador_campana->SacarDatos($link_r, $idjugador, $idcampana);



    // ·································· Datos personales de perfil ····································

    $jugador = new Jugador();
    $jugador->SacarDatos($link_r, $idjugador);


    $miclan = new Clan();
    $miclan->link_r = $link_r;
    $miclan->link_w = $link_w;


    ?>

      <table width="100%" bgcolor="#221111" style="padding: 10px;">
      <tr>
      <td>

      <p><b>Login : </b><?php echo $jugador->login; ?></p>
      <br/>
      <p><b>Email : </b><?php echo $jugador->email; ?></p>
      <br/>



      <?php
      if ($miclan->ObtieneClanJugador($idjugador, $idcampana) == true)
      {
        if ($lang == 'en')
        {
          echo ("<p><b>Team : <a href=\"index.php?catid=9&idcampana=".$idcampana."\">".$miclan->nombre."</a></b>");
        } else {
          echo ("<p><b>Equipo : <a href=\"index.php?catid=9&idcampana=".$idcampana."\">".$miclan->nombre."</a></b>");
        }

      } else {
        if ($lang == 'en')
        {
          echo ("<p><b>Team : </b><span class=\"errorsutil\"><b>You do not belong to any team.</b></span>");
        } else {
          echo ("<p><b>Equipo : </b><span class=\"errorsutil\"><b>No perteneces a ning&uacute;n equipo.</b></span>");
        }
      }
      ?></p><br/>



      <p><b><?php
        if ($lang == 'en')
        {
          echo ("Sign-up date : ");
        } else {
          echo ("Fecha de alta : ");
        }
	?></b><?php //echo $jugador->fecha_alta; 

        $id_tmz = $jugador->id_tmz;
        $tmz = new TMZ();
        $tmz->SacarDatos($link_r, $id_tmz);
        $hora_servidor = -6;
        $min_servidor = 0;
        $diferencia_hora = $tmz->tmz_hour - $hora_servidor;
        $diferencia_min = $tmz->tmz_min - $min_servidor;

        $anyo = substr($jugador->fecha_alta, 0, 4);
        $mes = substr($jugador->fecha_alta, 5, 2);
        $dia = substr($jugador->fecha_alta, 8, 2);
        $hora = substr($jugador->fecha_alta, 11, 2);
        $min = substr($jugador->fecha_alta, 14, 2);
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

?></p>
      <br/>
      <p><b><?php
        if ($lang == 'en')
        {
          echo ("Premium user? : ");
        } else {
          echo ("&iquest;Usuario premium? : ");
        }

	?></b><?php if ($jugador->es_premium == 1) {
                   if ($lang == 'en')
                   {
			echo ("Yes, until <i>".$jugador->fecha_fin_premium."</i>");
                   } else {
			echo ("Si, hasta <i>".$jugador->fecha_fin_premium."</i>");
                   }
		} else {
			echo ("No");
		}
				?></p>

	</td><td style="text-align: right;">

          <?php
            $jugador_fotoperfil = new Jugador_Fotoperfil();
            if ($jugador_fotoperfil->Obtener_Imagen_Jugador($link_r, $idjugador))
            {
              echo ("<img src=\"img/profile/".$jugador_fotoperfil->ruta."\" style=\"vertical-align: top;\">");
            } else {
              // No tiene imagen de perfil elegida.
              ?>
              <img src="img/profile/picstandard.jpg" style="vertical-align: top;">
              <?php
            }
          ?>

  	      <br/>
  	      <br/>
              <form method="post" action="index.php">
              <input type="hidden" name="accion" value="fotografia_perfil">
              <input type="hidden" name="catid" value="<?php echo $catid; ?>">
              <input type="hidden" name="idcampana" value="<?php echo $idcampana; ?>">
              <?php
                if ($lang == 'en')
                {
                ?>
                <input type="submit" value="Change">
                <?php
                } else {
                ?>
                <input type="submit" value="Cambiar">
                <?php
                }
              ?>
	      </form>

	</td></tr></table>




      <!-- Zona de alteracion de datos -->


    <form method="post" action="index.php">
      <input type="hidden" name="catid" value="<?php echo $catid; ?>">
      <input type="hidden" name="idcampana" value="<?php echo $idcampana; ?>">
      <input type="hidden" name="accion" value="alterar_perfil">



      <table width="100%" bgcolor="#161111" style="padding: 10px;">
      <tr>
      <td>

      <br/>
      <p><b><?php
        if ($lang == 'en')
        {
          echo ("Name : ");
        } else {
          echo ("Nombre : ");
        }
	?></b><input type="text" name="nombre" value="<?php echo $jugador->nombre; ?>"></p>
	<br/>



      <p><b><?php
        if ($lang == 'en')
        {
          echo ("Timezone :");
        } else {
          echo ("Zona horaria :");
        }
	?></b> <select name="id_tmz">
		<?php
                  $tmz = new TMZ();
                  $array = $tmz->BuscarElemento($link_r);
                  for ($i = 1; $i <= count($array); $i++)
                  {
                    if ($array[$i]['id'] == $jugador->id_tmz)
                    {
                      echo ("<option value=\"".$array[$i]['id']."\" selected=\"selected\">");
                    } else {
                      echo ("<option value=\"".$array[$i]['id']."\">");
                    }
                    echo ("UTC ".$array[$i]['tmz_hour'].":");
                    if ($array[$i]['tmz_min'] == 0) { echo ("00"); } else { echo (abs($array[$i]['tmz_min'])); }
                    echo (" ");
                    if ($lang == 'en')
                    {
                      if (strlen($array[$i]['desc_es']) > 50) { echo (substr($array[$i]['desc_en'], 0, 50).'...'); } else { echo ($array[$i]['desc_en']); }
                    } else {
                      if (strlen($array[$i]['desc_es']) > 50) { echo (substr($array[$i]['desc_es'], 0, 50).'...'); } else { echo ($array[$i]['desc_es']); }
                    }
                    echo ("</option>");
                  }
		?>
		</select>
	</p>
	<br/>



      <p><b><?php
        if ($lang == 'en')
        {
          echo ("Public email? :");
        } else {
          echo ("&iquest;Email p&uacute;blico? :");
        }
	?></b> <select name="email_publico">
		<option value="1" <?php if ($jugador->email_publico == 1) { echo ("selected=\"selected\""); } ?>><?php if ($lang == 'en') { echo ("Yes"); } else { echo ("Si"); } ?></option>
		<option value="2" <?php if ($jugador->email_publico == 2) { echo ("selected=\"selected\""); } ?>><?php if ($lang == 'en') { echo ("No"); } else { echo ("No"); } ?></option>
		</select>
	</p>
	<br/>



      <p><b><?php
        if ($lang == 'en')
        {
          echo ("Do you want to receive email in-game notifications (i.e. winning a tournament) ? :");
        } else {
          echo ("&iquest;Quiere recibir notificaciones del juego por email (por ejemplo, ganar un torneo) ? :");
        }
	?></b> <select name="envio_emails">
		<option value="1" <?php if ($jugador->envio_emails == 1) { echo ("selected=\"selected\""); } ?>><?php if ($lang == 'en') { echo ("Yes"); } else { echo ("Si"); } ?></option>
		<option value="0" <?php if ($jugador->envio_emails == 0) { echo ("selected=\"selected\""); } ?>><?php if ($lang == 'en') { echo ("No"); } else { echo ("No"); } ?></option>
		</select>
	</p>
	<br/>


      <p><b><?php
        if ($lang == 'en')
        {
          echo ("Do you want to receive the game newsletter by email? :");
        } else {
          echo ("&iquest;Quiere recibir la newsletter del juego por email? :");
        }
	?></b> <select name="envio_boletines">
		<option value="1" <?php if ($jugador->envio_boletines == 1) { echo ("selected=\"selected\""); } ?>><?php if ($lang == 'en') { echo ("Yes"); } else { echo ("Si"); } ?></option>
		<option value="0" <?php if ($jugador->envio_boletines == 0) { echo ("selected=\"selected\""); } ?>><?php if ($lang == 'en') { echo ("No"); } else { echo ("No"); } ?></option>
		</select>
	</p>
	<br/>




      <p><b><?php
        if ($lang == 'en')
        {
          echo ("Description :");
        } else {
          echo ("Descripci&oacute;n :");
        }
        ?></b></p>
	<br/>
      <p><textarea name="texto_presentacion" cols="80" rows="12"><?php echo $jugador->texto_presentacion; ?></textarea></p>
	<br/>

      <p><b><?php
        if ($lang == 'en')
        {
          echo ("Language :");
        } else {
          echo ("Lenguaje :");
        }
	?></b> <select name="lang">
		<option value="en" <?php if ($jugador->lang == 'en') { echo ("selected=\"selected\""); } ?>><?php if ($lang == 'en') { echo ("English"); } else { echo ("Ingl&eacute;s"); } ?></option>
		<option value="es" <?php if ($jugador->lang == 'es') { echo ("selected=\"selected\""); } ?>><?php if ($lang == 'en') { echo ("Spanish"); } else { echo ("Espa&ntilde;ol"); } ?></option>
		</select>
	</p>
        <br/>

      <p>
       <?php
	if ($lang == 'en')
        {
       ?>
        <input type="submit" value="Change data">
       <?php
        } else {
       ?>
        <input type="submit" value="Cambiar datos">
       <?php
        }
       ?>
      </p>
    </form>


	</td></tr></table>






      <!-- Zona de alteracion de clave -->


    <form method="post" action="index.php">
      <input type="hidden" name="catid" value="<?php echo $catid; ?>">
      <input type="hidden" name="idcampana" value="<?php echo $idcampana; ?>">
      <input type="hidden" name="accion" value="alterar_clave">

      <br/>
      <br/>


      <table width="100%" bgcolor="#161111" style="padding: 10px;">
      <tr>
      <td>

      <br/>
      <p><b><?php
        if ($lang == 'en')
        {
          echo ("Password : ");
        } else {
          echo ("Clave : ");
        }
	?></b><input type="password" name="clave" value=""></p>
	<br/>

      <p><b><?php
        if ($lang == 'en')
        {
          echo ("Password (confirm) : ");
        } else {
          echo ("Clave (confirmar) : ");
        }
	?></b><input type="password" name="clave2" value=""></p>
	<br/>

      <p>
       <?php
	if ($lang == 'en')
        {
       ?>
        <input type="submit" value="Change password">
       <?php
        } else {
       ?>
        <input type="submit" value="Cambiar clave">
       <?php
        }
       ?>
      </p>
    </form>


	</td></tr></table>






    <?php


    if ($es_admin == 1)
    {
      echo ("<br/>");
      echo ("<br/>");
      if ($jugador_campana->debug_mode == 1)
      {
        echo ("<a href=\"index.php?catid=5&idcampana=".$idcampana."&accion=desactivar_debug\">");
        if ($lang == 'en')
        {
          echo ("Deactivate debug mode");
        } else {
          echo ("Desactivar modo debug");
        }
        echo ("</a>");
      } else {
        echo ("<a href=\"index.php?catid=5&idcampana=".$idcampana."&accion=activar_debug\">");
        if ($lang == 'en')
        {
          echo ("Activate debug mode");
        } else {
          echo ("Activar modo debug");
        }
        echo ("</a>");
      }
    }







  }

?>






