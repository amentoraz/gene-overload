<?php



  // **********************************************************
  //  Comprobamos el email para activar la cuenta
  // **********************************************************

  if ($accion == "comprobaremail")
  {

    $lang = $secure->Sanitizar($_REQUEST['lang']);
    $clave = $secure->Sanitizar($_REQUEST['clave']);
    $email = $secure->Sanitizar($_REQUEST['email']);

    $jugador = new Jugador();

    // Que no nos trapicheen... usamos nuestra $claveregistro

    $comprobar = md5($email.$claveregistro);

    if ($comprobar == $clave)
    {

      if ($jugador->ExisteSinActivar($link_r, $email) == 0)
      {

        echo ("<p class=\"error\"><strong>Error : </strong>C&oacute;digo incorrecto</p>");

      } else {

        $jugador->Activar($link_w, $email);
        if ($lang == 'en')
        {
          echo ("<p class=\"correcto\"><strong>Registration process correctly finished : </strong>You can now enter your login and password</p>");

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
               echo ("<input type=\"submit\" value=\"Sign in\">");
               echo ("</form>");


        } else {
          echo ("<p class=\"correcto\"><strong>Registro conclu&iacute;do : </strong>Ya puedes escribir tu login y password</p>");

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
               echo ("<input type=\"submit\" value=\"Entrar\">");
               echo ("</form>");

        }

      }

    } else {
      echo ("<p class=\"error\"><strong>Error : </strong>C&oacute;digo incorrecto</p>");
    }
    
  }



  // ************************************************
  //   Aqui validamos la password y suputamadre
  // ************************************************

  if ($accion == 'validar')
  {

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

             // Una vez creado el usuario, marcamos el codigo como utilizado
             $idusuario = mysql_insert_id($link_w);
//             $invitacion->UsarAdmin($betacode, $email, $idusuario); // El $idusuario es el id de usuario nuevo

             echo ("<br/>");
             echo ("<br/>");
             echo ("<br/>");
             echo ("<br/>");
             if ($lang == 'en')
             {
               echo ("<p class=\"correcto\"><b>Registration successful</b></p>");
               echo ("<br/>");
               echo ("<p class=\"correcto\">You will soon receive a confirmation message in your email. Follow the link there to activate your account.</p>");
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
               echo ("<input type=\"submit\" value=\"Sign in\">");
               echo ("</form>");

             } else {

               echo ("<p class=\"correcto\"><b>Registro finalizado con &eacute;xito</b></p>");
               echo ("<br/>");
               echo ("<p class=\"correcto\">Pronto recibir&aacute;s en tu correo electr&oacute;nico un email de confirmaci&oacute;n. Sigue el enlace para activar tu cuenta.</p>");
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
               echo ("<input type=\"submit\" value=\"Entrar\">");
               echo ("</form>");
             }
             echo ("<br/>");
             echo ("<br/>");
             echo ("<br/>");


             // ***************************************************
             //   Y ahora es cuando hay que mandarle un email :S

             // Preparamos el body con el enlace. Se trata de un md5 de la direccion + "registrame"
             $registrame = md5($email.$claveregistro);

             $body = "<html><body>";
             $body = $body."<br/><center>";
             $body = $body."<img src=\"http://www.geneoverload.com/img/logomail.png\">";
             $body = $body."</center><br/>";
             if ($lang == 'en')
             {
               $subject = "Gene Overload account activation";
               $body = $body."<p style=\"font-size: 12px; \">Gene Overload registration process</p>";
               $body = $body."<p style=\"font-size: 10px; \"><strong>User :</strong>".$login."</p>";
               $body = $body."<p style=\"font-size: 10px; \"><strong>Password :</strong> ".$clave."</p>";
               $body = $body."<br/><br/>";
               $body = $body."<p style=\"font-size: 10px; \"><strong> To confirm your email and activate your Gene Overload account, click in the link : </strong></p>";
               $body = $body."<p style=\"font-size: 10px; \"><strong><a href=\"";
               $body = $body."http://www.geneoverload.com/index.php?accion=comprobaremail&catid=".$catid."&clave=".$registrame."&email=".$email."&lang=".$lang."\">";
//               $body = $body.$PATHBASE_exterior;
               $body = $body."http://www.geneoverload.com/index.php?accion=comprobaremail&catid=".$catid."&clave=";
               $body = $body.$registrame;
               $body = $body."&email=".$email;
               $body = $body."</a>";
               $body = $body."</strong></p>";
               $body = $body."<br/><br/>";
               $body = $body."<p style=\"font-size: 10px; \"><strong>(If you can't click, copy and paste the address in your internet browser address bar)</strong></p>";
               $body = $body."</body></html>";
               $cabeceras  = 'MIME-Version: 1.0' . "\r\n";
               $cabeceras .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
               $cabeceras .= 'From: no-reply@geneoverload.com' . "\r\n" .
    'Reply-To: no-reply@geneoverload.com' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();
             } else {
               $subject = "Registro y activacion en Gene Overload";
               $body = $body."<p style=\"font-size: 12px; \">Proceso de registro en Gene Overload</p>";
               $body = $body."<p style=\"font-size: 10px; \"><strong>Usuario :</strong>".$login."</p>";
               $body = $body."<p style=\"font-size: 10px; \"><strong>Contrase&ntilde;a :</strong> ".$clave."</p>";
               $body = $body."<br/><br/>";
               $body = $body."<p style=\"font-size: 10px; \"><strong> Para confirmar tu direcci&oacute;n de correo electr&oacute;nico y activar tu cuenta, pulsa en el enlace : </strong></p>";
               $body = $body."<p style=\"font-size: 10px; \"><strong><a href=\"";
               $body = $body."http://www.geneoverload.com/index.php?accion=comprobaremail&catid=".$catid."&clave=".$registrame."&email=".$email."&lang=".$lang."\">";
//               $body = $body."index.php?accion=comprobaremail&catid=".$catid."&clave=".$registrame."&email=".$email."&lang=".$lang."\">";
//               $body = $body.$PATHBASE_exterior;
               $body = $body."http://www.geneoverload.com/index.php?accion=comprobaremail&catid=".$catid."&clave=";
               $body = $body.$registrame;
               $body = $body."&email=".$email;
               $body = $body."</a>";
               $body = $body."</strong></p>";
               $body = $body."<br/><br/>";
               $body = $body."<p style=\"font-size: 10px; \"><strong>(Si no puedes hacer click, copia y pega la direcci&oacute;n en tu navegador)</strong></p>";
               $body = $body."</body></html>";
               $cabeceras  = 'MIME-Version: 1.0' . "\r\n";
               $cabeceras .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
               $cabeceras .= 'From: no-reply@geneoverload.com' . "\r\n" .
    'Reply-To: no-reply@geneoverload.com' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();
             }
             mail ($email, $subject, $body, $cabeceras);


           } else {
             if ($lang == 'en')
             {
               echo ("<p class=\"errorsutil\"><b>Email is wrong or already used.</b></p>");
             } else {
               echo ("<p class=\"errorsutil\"><b>El email es err&oacute;neo o ya ha sido utilizado.</b></p>");
             }
             $accion = null;
           } // Email erroneo

         } else {
           if ($lang == 'en')
           {
             echo ("<p class=\"errorsutil\"><b>Login name is already used</b></p>");
           } else {
             echo ("<p class=\"errorsutil\"><b>El nombre de usuario ya existe</b></p>");
           }
           $accion = null;
         } // Login ya usado

       } else {
         if ($lang == 'en')
         {
           echo ("<p class=\"errorsutil\"><b>Passwords do not match</b></p>");
         } else {
           echo ("<p class=\"errorsutil\"><b>Las claves no coinciden</b></p>");
         }
         $accion = null;
       }
     } else {
       if ($lang == 'en')
       {
         echo ("<p class=\"errorsutil\"><b>Password cannot be empty</b></p>");
       } else {
         echo ("<p class=\"errorsutil\"><b>La clave no puede estar vac&iacute;a</b></p>");
       }
       $accion = null;
     }
   } else {
     if ($lang == 'en')
     {
       echo ("<p class=\"errorsutil\"><b>Terms of Use and Data Protection Clause must be accepted</b></p>");
     } else {
       echo ("<p class=\"errorsutil\"><b>Debes aceptar los T&eacute;rminos de Uso y la Cl&aacute;usula de Protecci&oacute;n de Datos</b></p>");
     }
     $accion = null;
   }
   echo ("<br/>");


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


//    $betacode = trim($secure->Sanitizar($_REQUEST['betacode'])); 

//    $invitacion = new Invitacion();
//    $invitacion->link_r = $link_r;
//    $invitacion->link_w = $link_w;

    //  Validamos que es un codigo correcto. Esto es, que coincide con un
    // codigo cuando es tipo 3 (admin), o con codigo+email en tabla de pendientes

    // cuando es tipo 1 (usuario) o tipo 2 (clan)
//    if ($invitacion->Validar($betacode))
//    {
                // Este array es de un sacar datos
//      $array_validar = unserialize($invitacion->SacarDatosValidar($betacode));

//      if ($array_validar['tipo_origen'] == 3)
//      {
//          <form method="post" action="index.php">
//            <input type="hidden" name="catid" value="$catid">
//            <input type="hidden" name="betacode" value="$betacode;">
           ?>


	  <script>
	  $(document).ready(function(){

          $("#formulario").validate({
		rules: {
			login: {
				required: true,
				minlength: 3
			},

			email: {
				required: true,
				email: true
			},
			clave: {
				required: true,
				minlength: 5
			},
			clave1: {
				required: true,
				minlength: 5,
				equalTo: "#clave"
			},
			terms_of_use: "required",
			privacy: "required",
		},
		<?php

		if ($lang == 'en')
		{
		?>
		messages: {
			login: {
                                required: "Login name is required",
                                minlength: "Login name must be at least 3 characters"
                        },
			email: {
				required: "<br/><br/>Email is required<br/><br/>",
				email: "<br/><br/>Your email address must be correct<br/><br/>"
			},
			clave: {
				required: "Password is required",
				minlength: "Your password must have at least 5 characters",
			},
			clave1: {
				required: "Password is required",
				minlength: "Your password must have at least 5 characters",
				equalTo: "Passwords do not match"
			},
			terms_of_use: "<br/><br/>You must accept the Terms of Use<br/><br/>",
			privacy: "<br/><br/>You must accept the Data Protection Clause<br/><br/>"
 		<?php
		} else {
//Your password must be at least 5 characters long"
                ?>
		messages: {
			login: {
			        required: "Es obligatorio escribir un login",
                                minlength: "Tu nombre debe tener al menos 3 caracteres"
                        },
			email: {
				required: "<br/><br/>Por favor, introduzca su email<br/><br/>",
				email: "<br/><br/>Su direcci&oacute;n de correo debe ser v&aacute;lida<br/><br/>"
			},
			clave: {
				required: "Por favor, introduzca su clave",
				minlength: "Su clave debe tener al menos 5 caracteres",
			},
			clave1: {
				required: "Por favor, introduzca su clave",
				minlength: "Su clave debe tener al menos 5 caracteres",
				equalTo: "Las claves no coinciden"
			},
			terms_of_use: "<br/><br/>Debe aceptar los t&eacute;rminos de uso<br/><br/>",
			privacy: "<br/><br/>Debe aceptar la Cl&aacute;usula de Protecci&oacute;n de Datos<br/><br/>"
 		<?php
		}
//				equalTo: "Las claves no coinciden"

                ?>
            }
	});



	  });
	  </script>

            <form method="post" action="index.php" id="formulario">
             <input type="hidden" name="catid" value="<?php echo $catid;?>">
             <input type="hidden" name="accion" value="validar">
	   <?php

          if ($lang == 'en')
          {

	    echo ("<center>");
            echo ("<p style=\"font-size: 18px; font-weight: bold; color: #c58768;\">Gene Overload registration</p>");
	    echo ("</center>");

            echo ("<br/>");
            echo ("<br/>");
//            echo ("<p style=\"font-size: 15px; color: #c58768; \">");
//            echo ("Registering with code <b>".$betacode."</b>. Please fill the following data: ");
//            echo ("</p>");
            echo ("<br/>");
            echo ("<br/>");

            echo ("<table style=\"padding: 10px;
                        \"
                        >");


            echo ("<tr style=\"height: 35px;\"><td style=\"width: 135px;\">");
            echo ("<p style=\"font-size: 15px; color: #c58768; \">");
            echo ("<b>Login name </b> ");
            echo ("</td><td>");
            echo ("<input type=\"text\" name=\"login\" class=\"required\" size=\"20\" value=\"".$login."\">");
            echo ("</p>");
            echo ("</td></tr>");

            echo ("<tr style=\"height: 35px;\"><td style=\"width: 115px;\">");
            echo ("<p style=\"font-size: 15px; color: #c58768; \">");
            echo ("<b>Password </b> ");
            echo ("</td><td>");
            echo ("<input type=\"password\" name=\"clave\" size=\"20\" id=\"clave\">");
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
            echo ("</p>");
            echo ("</td><td>");
            echo ("<input type=\"text\" name=\"email\" size=\"50\" value=\"".$email."\">");
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
            echo ("<textarea cols=\"50\" rows=\"12\" name=\"texto_presentacion\">".stripslashes($texto_presentacion)."</textarea>");
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

	    echo ("<center>");
            echo ("<p style=\"font-size: 18px; font-weight: bold; color: #c58768;\">Registro en Gene Overload</p>");
	    echo ("</center>");

            echo ("<br/>");
            echo ("<br/>");
//            echo ("<p style=\"font-size: 15px; color: #c58768; \">");
//            echo ("Registr&aacute;ndote con el c&oacute;digo <b>".$betacode."</b>. Por favor, rellena estos datos: ");
//            echo ("</p>");
            echo ("<br/>");
            echo ("<br/>");
            echo ("<table style=\"
			padding: 10px;
                        \"
                        >");


            echo ("<tr style=\"height: 35px;\"><td style=\"width: 145px;\">");
            echo ("<span style=\"font-size: 15px; color: #c58768; \">");
            echo ("<b>Nombre de usuario (Login) </b> ");
            echo ("</td><td>");
            echo ("<input type=\"text\" name=\"login\" class=\"required\" size=\"20\" value=\"".$login."\">");
            echo ("</span>");
            echo ("</td></tr>");

            echo ("<tr style=\"height: 35px;\"><td style=\"width: 115px;\">");
            echo ("<p style=\"font-size: 15px; color: #c58768; \">");
            echo ("<b>Clave </b> ");
            echo ("</td><td>");
            echo ("<input type=\"password\" name=\"clave\" size=\"20\" id=\"clave\">");
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
            echo ("</p>");
            echo ("</td><td>");
            echo ("<input type=\"text\" name=\"email\" size=\"50\" value=\"".$email."\">");
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
            echo ("<textarea cols=\"50\" rows=\"12\" name=\"texto_presentacion\">".stripslashes($texto_presentacion)."</textarea>");
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
//      }

/*
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
*/
  }



?>
