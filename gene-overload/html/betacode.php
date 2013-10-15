<?php

  include("clases/obj_invitacion.php");


  // Comprobando un codigo y validandolo




  // ************************************************
  // Comprobar codigo y mostrar dialogo de registro
  // ************************************************

  if ($accion == 'validar')
  {

    $betacode = trim($secure->Sanitizar($_REQUEST['betacode']));

    $login = $secure->Sanitizar($_REQUEST['login']);
    $clave = $secure->Sanitizar($_REQUEST['clave']);
    $clave1 = $secure->Sanitizar($_REQUEST['clave1']);
    $nombre = $secure->Sanitizar($_REQUEST['nombre']);
    $email = $secure->Sanitizar($_REQUEST['email']);
    $email_publico = $secure->Sanitizar($_REQUEST['email_publico']);
    $lang = $secure->Sanitizar($_REQUEST['lang']);
    $texto_presentacion = $secure->Sanitizar($_REQUEST['texto_presentacion']);

    $terms_of_use = $secure->Sanitizar($_REQUEST['terms_of_use']);
    $privacy = $secure->Sanitizar($_REQUEST['privacy']);

  if (($terms_of_use == 1) && ($privacy == 1))
  {

    $invitacion = new Invitacion();
    $invitacion->link_r = $link_r;
    $invitacion->link_w = $link_w;

    //  Validamos que es un codigo correcto. Esto es, que coincide con un
    // codigo cuando es tipo 3 (admin), o con codigo+email en tabla de pendientes
    // cuando es tipo 1 (usuario) o tipo 2 (clan)
    if ($invitacion->Validar($betacode))
    {

      $array_validar = unserialize($invitacion->SacarDatosValidar($betacode));

      // Ahora que esta correcto, lo usamos
      if ($array_validar['tipo_origen'] == 3)
      {

        //  Comprobamos si clave y clave1 no son vacias, y coinciden, y si
        // el login ya existe

        $jugador = new Jugador();
        if (($clave != null) && ($clave != ''))
        {
          if ($clave == $clave1)
          {

            // Ahora las comprobaciones del usuario
            if (($jugador->SacarDatosDesdeLogin($link_r, $login) == false) && ($login != null) && ($login != ''))
            {

              $existeemail = $jugador->SacarDatosDesdeEmail($link_r, $email);
              if (($email != null) && ($email != '') && ($existeemail == false))
              {

                // Primero vamos a crear el usuario
                $jugador->lang = $lang;
                $jugador->login = $login;
                $jugador->nombre = $nombre;
                $jugador->email = $email;
                $jugador->email_publico = $email_publico;
                $jugador->texto_presentacion = $texto_presentacion;
                $jugador->clave = $clave;
                $jugador->Insertar($link_w);

                // Habria que meter un "activado" y que se active por email
                $jugador->Activar($link_w, $email);

                // Una vez creado el usuario, marcamos el codigo como utilizado
                $idusuario = mysql_insert_id($link_w);
                $invitacion->UsarAdmin($betacode, $email, $idusuario); // El $idusuario es el id de usuario nuevo

                echo ("<br/>");
                echo ("<br/>");
                echo ("<br/>");
                echo ("<br/>");
                if ($lang == 'en')
                {
                  echo ("<p class=\"correcto\"><b>Code used succesfully</b></p>");
                  echo ("<br/>");
                  echo ("<p class=\"correcto\">You can already access the game writing your login and password.</p>");
                  echo ("<br/>");
                  echo ("<br/>");
                  echo ("<form method=\"post\" action=\"index.php\">");
                  echo ("<table style=\"padding: 10px;
                        \"
                        >");

                  echo ("<tr style=\"height: 35px;\"><td width=\"140px;\">");
                  echo ("<p style=\"font-size: 15px; color: #c58768; \">");
                  echo ("<b>Login name </b> ");
                  echo ("</td><td>");
                  echo ("<input type=\"text\" name=\"s_usuario\" size=\"20\" value=\"".$login."\">");
                  echo ("</p>");
                  echo ("</td></tr>");

                  echo ("<tr style=\"height: 35px;\"><td>");
                  echo ("<p style=\"font-size: 15px; color: #c58768; \">");
                  echo ("<b>Password </b> ");
                  echo ("</td><td>");
                  echo ("<input type=\"password\" name=\"s_clave\" size=\"20\">");
                  echo ("</p>");
                  echo ("</td></tr>");

                  echo ("</table>");

                  echo ("<br/>");
                  echo ("<input type=\"hidden\" value=\"1\" name=\"recien_autenticado\">");
//                  echo ("<input type=\"hidden\" value=\"0\" name=\"catid\">");
                  echo ("<input type=\"submit\" value=\"Sign in\">");
                  echo ("</form>");

                } else {
                  echo ("<p class=\"correcto\"><b>C&oacute;digo utilizado con &eacute;xito</b></p>");
                  echo ("<br/>");
                  echo ("<p class=\"correcto\">Ya puedes acceder al juego escribiendo el usuario y clave.</p>");
                  echo ("<br/>");
                  echo ("<br/>");
                  echo ("<form method=\"post\" action=\"index.php\">");
                  echo ("<table style=\"padding: 10px;
                        \"
                        >");

                  echo ("<tr style=\"height: 35px;\"><td width=\"140px;\">");
                  echo ("<p style=\"font-size: 15px; color: #c58768; \">");
                  echo ("<b>Nombre de usuario </b> ");
                  echo ("</td><td>");
                  echo ("<input type=\"text\" name=\"s_usuario\" size=\"20\" value=\"".$login."\">");
                  echo ("</p>");
                  echo ("</td></tr>");

                  echo ("<tr style=\"height: 35px;\"><td>");
                  echo ("<p style=\"font-size: 15px; color: #c58768; \">");
                  echo ("<b>Clave </b> ");
                  echo ("</td><td>");
                  echo ("<input type=\"password\" name=\"s_clave\" size=\"20\">");
                  echo ("</p>");
                  echo ("</td></tr>");

                  echo ("</table>");

                  echo ("<br/>");
                  echo ("<input type=\"hidden\" value=\"1\" name=\"recien_autenticado\">");
//                  echo ("<input type=\"hidden\" value=\"0\" name=\"catid\">");
                  echo ("<input type=\"submit\" value=\"Entrar\">");
                  echo ("</form>");

                }
                echo ("<br/>");
                echo ("<br/>");
                echo ("<br/>");



              } else {
                if ($lang == 'en')
                {
                  echo ("<p class=\"errorsutil\">Email is wrong or already used.</p>");
                } else {
                  echo ("<p class=\"errorsutil\">El email es err&oacute;neo o ya ha sido utilizado.</p>");
                }
                $accion = null;
              }


            } else {
              if ($lang == 'en')
              {
                echo ("<p class=\"errorsutil\">Login name is already used</p>");
              } else {
                echo ("<p class=\"errorsutil\">El nombre de usuario ya existe</p>");
              }
              $accion = null;
            }



          } else {
            if ($lang == 'en')
            {
              echo ("<p class=\"errorsutil\">Passwords do not match</p>");
            } else {
              echo ("<p class=\"errorsutil\">Las claves no coinciden</p>");
            }
            $accion = null;
          }

        } else {
          if ($lang == 'en')
          {
            echo ("<p class=\"errorsutil\">Password cannot be empty</p>");
          } else {
            echo ("<p class=\"errorsutil\">La clave no puede estar vac&iacute;a</p>");
          }
          $accion = null;
        }


      }


    }

} else {
          if ($lang == 'en')
          {
            echo ("<p class=\"errorsutil\">Terms of Use and Data Protection Clause must be accepted</p>");
          } else {
            echo ("<p class=\"errorsutil\">Debes aceptar los T&eacute;rminos de Uso y la Cl&aacute;usula de Protecci&oacute;n de Datos</p>");
          }
          $accion = null;
}


  }


  // ************************************************
  // Comprobar codigo y mostrar dialogo de registro
  // ************************************************

  if ($accion == null)
  {

    $login = $secure->Sanitizar($_REQUEST['login']);
    $clave = $secure->Sanitizar($_REQUEST['clave']);
    $clave1 = $secure->Sanitizar($_REQUEST['clave1']);
    $nombre = $secure->Sanitizar($_REQUEST['nombre']);
    $email = $secure->Sanitizar($_REQUEST['email']);
    $email_publico = $secure->Sanitizar($_REQUEST['email_publico']);
    $lang = $secure->Sanitizar($_REQUEST['lang']);
    $texto_presentacion = $secure->Sanitizar($_REQUEST['texto_presentacion']);


    $betacode = trim($secure->Sanitizar($_REQUEST['betacode'])); 

    $invitacion = new Invitacion();
    $invitacion->link_r = $link_r;
    $invitacion->link_w = $link_w;

    //  Validamos que es un codigo correcto. Esto es, que coincide con un
    // codigo cuando es tipo 3 (admin), o con codigo+email en tabla de pendientes
    // cuando es tipo 1 (usuario) o tipo 2 (clan)
    if ($invitacion->Validar($betacode))
    {
                // Este array es de un sacar datos
      $array_validar = unserialize($invitacion->SacarDatosValidar($betacode));

      if ($array_validar['tipo_origen'] == 3)
      {
        ?>
          <form method="post" action="index.php">
            <input type="hidden" name="catid" value="<?php echo $catid;?>">
            <input type="hidden" name="betacode" value="<?php echo $betacode;?>">
            <input type="hidden" name="accion" value="validar">
        <?php
          if ($lang == 'en')
          {
            echo ("<br/>");
            echo ("<br/>");
            echo ("<p style=\"font-size: 15px; color: #c58768; \">");
            echo ("Registering with code <b>".$betacode."</b>. Please fill the following data: ");
            echo ("</p>");
            echo ("<br/>");
            echo ("<br/>");

            echo ("<table style=\"padding: 10px;
                        \"
                        >");


            echo ("<tr style=\"height: 35px;\"><td style=\"width: 135px;\">");
            echo ("<p style=\"font-size: 15px; color: #c58768; \">");
            echo ("<b>Login name </b> ");
            echo ("</td><td>");
            echo ("<input type=\"text\" name=\"login\" size=\"20\" value=\"".$login."\">");
            echo ("</p>");
            echo ("</td></tr>");

            echo ("<tr style=\"height: 35px;\"><td style=\"width: 115px;\">");
            echo ("<p style=\"font-size: 15px; color: #c58768; \">");
            echo ("<b>Password </b> ");
            echo ("</td><td>");
            echo ("<input type=\"password\" name=\"clave\" size=\"20\">");
            echo ("</p>");
            echo ("</td></tr>");

            echo ("<tr style=\"height: 35px;\"><td style=\"width: 115px;\">");
            echo ("<p style=\"font-size: 15px; color: #c58768; \">");
            echo ("<b>Password (again) </b> ");
            echo ("</td><td>");
            echo ("<input type=\"password\" name=\"clave1\" size=\"20\">");
            echo ("</p>");
            echo ("</td></tr>");


            echo ("<tr style=\"height: 35px;\"><td>");
            echo ("<p style=\"font-size: 15px; color: #c58768; \">");
            echo ("<b>Real name</b> ");
            echo ("</td><td>");
            echo ("<input type=\"text\" name=\"nombre\" size=\"30\" value=\"".$nombre."\">");
            echo ("</p>");
            echo ("</td></tr>");

            echo ("<tr style=\"height: 35px;\"><td>");
            echo ("<p style=\"font-size: 15px; color: #c58768; \">");
            echo ("<b>Email </b> ");
            echo ("</td><td>");
            echo ("<input type=\"text\" name=\"email\" size=\"50\" value=\"".$email."\">");
            echo ("</p>");
            echo ("</td></tr>");

            echo ("<tr style=\"height: 35px;\"><td width=\"70px;\">");
            echo ("<span style=\"font-size: 15px; color: #c58768; \">");
            echo ("<b>Public email </b>");
            echo ("</td><td>");
            echo ("<select name=\"email_publico\">");
            if ($email_publico == 1)
            {
              echo ("<option value=\"1\" selected=\"selected\">Yes</option>");
              echo ("<option value=\"2\">No</option>");
            } else {
              echo ("<option value=\"1\">Yes</option>");
              echo ("<option value=\"2\" selected=\"selected\">No</option>");
            }
            echo ("</select>");
            echo ("</span>");
            echo ("</td></tr>");

            echo ("<tr style=\"height: 35px;\"><td width=\"70px;\">");
            echo ("<span style=\"font-size: 15px; color: #c58768; \">");
            echo ("<b>Language </b>");
            echo ("</td><td>");
            echo ("<select name=\"lang\">");
            if ($lang == 'en')
            {
              echo ("<option value=\"en\" selected=\"selected\">English</option>");
              echo ("<option value=\"es\">Espa&ntilde;ol</option>");
            } else {
              echo ("<option value=\"en\">English</option>");
              echo ("<option value=\"es\" selected=\"selected\">Espa&ntilde;ol</option>");
            }
            echo ("</select>");
            echo ("</span>");
            echo ("</td></tr>");


            echo ("<tr style=\"height: 35px;\"><td style=\"vertical-align: top;\">");
            echo ("<p style=\"font-size: 15px; color: #c58768; \">");
            echo ("<b>Welcome text </b> ");
            echo ("</td><td>");
            echo ("<textarea cols=\"50\" rows=\"12\" name=\"texto_presentacion\">".$texto_presentacion."</textarea>");
            echo ("</p>");
            echo ("</td></tr>");


	    // TERMINOS DE USO
            echo ("<tr style=\"height: 35px;\"><td style=\"vertical-align: top;\" colspan=\"2\">");
            echo ("<br/>");
            echo ("<p style=\"font-size: 15px; color: #c58768; \">");
            echo ("<b>I accept the terms and conditions described in ");
            echo ("<a href=\"http://www.geneoverload.com/terms_en.htm\" target=\"_blank\">");
            echo ("Terms of Use");
            echo ("</a>");
            echo ("</b> ");
//            echo ("</td>");
//            echo ("<td>");
            echo ("<input type=\"checkbox\" name=\"terms_of_use\" value=\"1\">");
            echo ("</td>");
            echo ("</tr>");


	    // CLAUSULA DE PROTECCION DE DATOS
            echo ("<tr style=\"height: 35px;\"><td style=\"vertical-align: top;\" colspan=\"2\">");
            echo ("<p style=\"font-size: 15px; color: #c58768; \">");
            echo ("<b>I accept the terms and conditions described in the ");
            echo ("<a href=\"http://www.geneoverload.com/privacy_en.htm\" target=\"_blank\">");
            echo ("Data Protection Clause");
            echo ("</a>");
            echo ("</b> ");
//            echo ("</td>");
//            echo ("<td>");
            echo ("<input type=\"checkbox\" name=\"privacy\" value=\"1\">");
            echo ("</td>");
            echo ("</tr>");




            echo ("<tr><td colspan=\"2\">");
            echo ("<br/>");
            echo ("<br/>");
            echo ("<input type=\"submit\" value=\"Register\">");
            echo ("</td></tr>");


            echo ("</table>");

          } else {

            echo ("<br/>");
            echo ("<br/>");
            echo ("<p style=\"font-size: 15px; color: #c58768; \">");
            echo ("Registr&aacute;ndote con el c&oacute;digo <b>".$betacode."</b>. Por favor, rellena estos datos: ");
            echo ("</p>");
            echo ("<br/>");
            echo ("<br/>");

            echo ("<table style=\"padding: 10px;
                        \"
                        >");


            echo ("<tr style=\"height: 35px;\"><td style=\"width: 145px;\">");
            echo ("<p style=\"font-size: 15px; color: #c58768; \">");
            echo ("<b>Nombre de usuario (Login) </b> ");
            echo ("</td><td>");
            echo ("<input type=\"text\" name=\"login\" size=\"20\" value=\"".$login."\">");
            echo ("</p>");
            echo ("</td></tr>");

            echo ("<tr style=\"height: 35px;\"><td style=\"width: 115px;\">");
            echo ("<p style=\"font-size: 15px; color: #c58768; \">");
            echo ("<b>Clave </b> ");
            echo ("</td><td>");
            echo ("<input type=\"password\" name=\"clave\" size=\"20\">");
            echo ("</p>");
            echo ("</td></tr>");

            echo ("<tr style=\"height: 35px;\"><td style=\"width: 115px;\">");
            echo ("<p style=\"font-size: 15px; color: #c58768; \">");
            echo ("<b>Clave (de nuevo) </b> ");
            echo ("</td><td>");
            echo ("<input type=\"password\" name=\"clave1\" size=\"20\">");
            echo ("</p>");
            echo ("</td></tr>");


            echo ("<tr style=\"height: 35px;\"><td>");
            echo ("<p style=\"font-size: 15px; color: #c58768; \">");
            echo ("<b>Nombre real</b> ");
            echo ("</td><td>");
            echo ("<input type=\"text\" name=\"nombre\" size=\"30\" value=\"".$nombre."\">");
            echo ("</p>");
            echo ("</td></tr>");

            echo ("<tr style=\"height: 35px;\"><td>");
            echo ("<p style=\"font-size: 15px; color: #c58768; \">");
            echo ("<b>Email </b> ");
            echo ("</td><td>");
            echo ("<input type=\"text\" name=\"email\" size=\"50\" value=\"".$email."\">");
            echo ("</p>");
            echo ("</td></tr>");

            echo ("<tr style=\"height: 35px;\"><td width=\"70px;\">");
            echo ("<span style=\"font-size: 15px; color: #c58768; \">");
            echo ("<b>Email p&uacute;blico </b>");
            echo ("</td><td>");
            echo ("<select name=\"email_publico\">");
            if ($email_publico == 1)
            {
              echo ("<option value=\"1\" selected=\"selected\">Si</option>");
              echo ("<option value=\"2\">No</option>");
            } else {
              echo ("<option value=\"1\">Si</option>");
              echo ("<option value=\"2\" selected=\"selected\">No</option>");
            }
            echo ("</select>");
            echo ("</span>");
            echo ("</td></tr>");

            echo ("<tr style=\"height: 35px;\"><td width=\"70px;\">");
            echo ("<span style=\"font-size: 15px; color: #c58768; \">");
            echo ("<b>Lenguaje </b>");
            echo ("</td><td>");
            echo ("<select name=\"lang\">");
            if ($lang == 'en')
            {
              echo ("<option value=\"en\" selected=\"selected\">English</option>");
              echo ("<option value=\"es\">Espa&ntilde;ol</option>");
            } else {
              echo ("<option value=\"en\">English</option>");
              echo ("<option value=\"es\" selected=\"selected\">Espa&ntilde;ol</option>");
            }
            echo ("</select>");
            echo ("</span>");
            echo ("</td></tr>");


            echo ("<tr style=\"height: 35px;\"><td style=\"vertical-align: top;\">");
            echo ("<p style=\"font-size: 15px; color: #c58768; \">");
            echo ("<b>Texto presentaci&oacute;n </b> ");
            echo ("</td><td>");
            echo ("<textarea cols=\"50\" rows=\"12\" name=\"texto_presentacion\">".$texto_presentacion."</textarea>");
            echo ("</p>");
            echo ("</td></tr>");



            // TERMINOS DE USO
            echo ("<tr style=\"height: 35px;\"><td style=\"vertical-align: top;\" colspan=\"2\">");
            echo ("<br/>");
            echo ("<p style=\"font-size: 15px; color: #c58768; \">");
            echo ("<b>Acepto los t&eacute;rminos y condiciones descritos en los ");
            echo ("<a href=\"http://www.geneoverload.com/terms_es.htm\" target=\"_blank\">");
            echo ("T&eacute;rminos de Uso");
            echo ("</a>");
            echo ("</b> ");
//            echo ("</td>");
//            echo ("<td>");
            echo ("<input type=\"checkbox\" name=\"terms_of_use\" value=\"1\">");
            echo ("</td>");
            echo ("</tr>");


	    // CLAUSULA DE PROTECCION DE DATOS
            echo ("<tr style=\"height: 35px;\"><td style=\"vertical-align: top;\" colspan=\"2\">");
            echo ("<p style=\"font-size: 15px; color: #c58768; \">");
            echo ("<b>Acepto los t&eacute;rminos y condiciones descritos en la ");
            echo ("<a href=\"http://www.geneoverload.com/privacy_es.htm\" target=\"_blank\">");
            echo ("Cl&aacute;usula de Protecci&oacute;n de Datos");
            echo ("</a>");
            echo ("</b> ");
//            echo ("</td>");
//            echo ("<td>");
            echo ("<input type=\"checkbox\" name=\"privacy\" value=\"1\">");
            echo ("</td>");
            echo ("</tr>");





            echo ("<tr><td colspan=\"2\">");
            echo ("<br/>");
            echo ("<br/>");
            echo ("<input type=\"submit\" value=\"Registrarse\">");
            echo ("</td></tr>");


            echo ("</table>");

          }
        ?>
          </form>
        <?php
        
//        $invitacion->UsarAdmin($codigo, $email, $idusuario); // El $idusuario es el id de usuario nuevo
      }


    } else {
      ?>
	<br/>
	<br/>
	<br/>
	<br/>
	<p class="error" style="font-size: 17px;">
	<b>Error : </b>
	<?php
          if ($lang == 'en')
          {
            echo ("The code you sent is invalid.");
          } else {
            echo ("El c&oacute;digo introducido no es v&aacute;lido.");
          }
	?>
	</p>
	<br/>
	<br/>
	<br/>
	<br/>
      <?php
    }

  }


?>








