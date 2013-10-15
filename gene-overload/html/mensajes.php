<?php

// include ("clases/obj_mensajes_personales.php");
 include ("clases/obj_mail.php");

 $accion = $secure->Sanitizar($_REQUEST['accion']);



 // Necesitamos un usuario autenticado [ojo q esto no comprueba la auth!!! solo es para verlo]

/*
   $usuario = $_SESSION['REMOTE_USER'];
   if (($usuario != '') && ($usuario != null))
   {
     $stringaveriguar = "SELECT id
                        FROM usuario
                        WHERE usuario = '$usuario'
                        ";
echo $stringaveriguar;
     $queryaveriguar = mysql_query($stringaveriguar);
     $miaveriguar = mysql_fetch_array($queryaveriguar);
     $idusuario = $miaveriguar['id'];
   } else {
     die ("<br/><br/>Necesitas haber iniciado sesi&oacute;n como usuario para acceder aqu&iacute;");
   }
*/






  // ****************************************
  //    Enviar un nuevo mensaje
  // ****************************************
  if ($accion == 'preview')
  {

    $preview = $secure->Sanitizar($_REQUEST['preview']);
    if ($preview == 'yes')
    {


      $destinatario = $secure->Sanitizar($_REQUEST['destinatario']);
      $asunto = $secure->Sanitizar($_REQUEST['asunto']);
      $contenido = $secure->SanitizarMensaje($_REQUEST['contenido']);
      $contenido = strip_tags($contenido,"<b><i><em><u><strong><img><center><sub><sup><a><strike><br>");
      $contenido = AddSlashes($contenido);
      $contenido = nl2br($contenido);
      
      ?>
      	<br/>
              <table width="100%" bgcolor="#221111" cellpadding="0px" cellspacing="0px"
        		style="padding-top: 15px; padding-bottom: 15px; padding-left: 15px; padding-right: 15px;">
        			<tr><td>
      <?php

      if ($lang == 'en')
      {
        echo ("<p class=\"textonormal\">Message <b>preview</b> for '".$destinatario."'</p>");
        echo ("<br/>");
        echo ("<p class=\"textonormal\"><b>Subject : </b>".$asunto."</p>");
        echo ("<br/>");
//        echo ("<p class=\"textonormal\"><strong>Message body :</strong></p>");
      } else {
        echo ("<p class=\"textonormal\"><b>Preview</b> mensaje para '".$destinatario."'</p>");
        echo ("<br/>");
        echo ("<p class=\"textonormal\"><b>Asunto : </b>".$asunto."</p>");
        echo ("<br/>");
//        echo ("<p class=\"textonormal\"><strong>Cuerpo del mensaje :</strong></p>");
      }

	?>
        <table width="100%" bgcolor="#111111" cellpadding="0px" cellspacing="0px"
        style="padding-top: 15px; padding-bottom: 15px; padding-left: 15px; padding-right: 15px;"
        >
          <tr height="8px" valign="top">
          <td width="8px" valign="top">
	<?php
         echo ("<span class=\"textonormal\">".$contenido."</span>");
        ?>
          </td>
          </tr>
          </table>
        <?php

		echo ("</td></tr></table>");
       echo ("<br/>");
       echo ("<br/>");
       $accion = 'componer';
    } else {
       $accion = 'enviar';
    }

  }




  // ****************************************
  //    Enviar un nuevo mensaje
  // ****************************************
  if ($accion == 'enviar')
  {
    $jugador = new Jugador();
    $mail = new Mail();
    $mensajes_personales = new Mensajes_Personales();

    $destinatario = $secure->Sanitizar($_REQUEST['destinatario']);
    $asunto = $secure->Sanitizar($_REQUEST['asunto']);

    $contenido = $secure->SanitizarMensaje($_REQUEST['contenido']);
    $contenido = strip_tags($contenido,"<b><i><em><u><strong><img><center><sub><sup><a><strike><br>");
    $contenido = AddSlashes($contenido);
    $contenido = nl2br($contenido);

    // Ahora tenemos que ver quien es el usuario origen y destino
    $origenid = $secure->Sanitizar($_REQUEST['origenid']);
    if (!is_numeric($origenid))
    {
      $origenid = 0;
    }

    // Si estas respondiendo a alguien...
    $idrespondido = $secure->Sanitizar($_REQUEST['idrespondido']);
    if (!is_numeric($idrespondido))
    {
      $idrespondido = 0;
    }
    // Tambien tenemos que comprobar que respondes a un mensaje dirigido a ti
    if ($idrespondido > 0)
    {
      $mensajes_personales_check = new Mensajes_Personales();
      $mensajes_personales_check->SacarDatos($link_r, $idrespondido);
      if ($mensajes_personales_check->idusuariodestino != $idjugador)
      {
        die;
      }
    }


    $comprobarusuario = $jugador->SacarDatosDesdeLogin($link_r, $destinatario);


    if ($comprobarusuario == true)
    {
      if (($asunto == null) || ($contenido == null))
      {

        if ($lang == 'en')
        {
          echo ("<p class=\"error\"><strong>Could not send:</strong> subject and body cannot be empty</p>");
        } else {
          echo ("<p class=\"error\"><strong>No se pudo enviar:</strong> han de rellenarse asunto y contenido del mensaje</p>");
        }
        $accion = "componer";

      } else {

        // Todo correcto, vamos palla
//        $contenido = AddSlashes(strip_tags($contenido));
        $asunto = AddSlashes(strip_tags($asunto));
        $iddestinatario = $jugador->id; //$unusuario['id'];

        if ((strlen($contenido) < 16384) && (strlen($asunto) < 70))
        {

          $repetidos = $mensajes_personales->ComprobarMensaje($link_r, $idjugador, $iddestinatario, $asunto, $contenido);
          if ($repetidos == 0)
          {

    	    $mensajes_personales->idusuarioorigen = $idjugador;
	    $mensajes_personales->idusuariodestino = $iddestinatario;
	    $mensajes_personales->asunto = $asunto;
    	    $mensajes_personales->contenido = $contenido;
            $mensajes_personales->idrespondido = $idrespondido;
            $mensajes_personales->InsertarMensaje($link_w);
            $idmensajepersonal = mysql_insert_id($link_w);

            // Si hay un origenid, eso es que es respuesta a otro mensaje, el cual marcamos como respondido
            if ($origenid != null)
            {
              $mensajes_personales->MarcarRespondido($link_w, $origenid);
            }

            //  Ahora vamos a enviar un email al destinatario para notificarle que le ha llegado
            // un mensaje

	    $jugador_destino = new Jugador();
            $jugador->SacarDatos($link_r, $idjugador);
            $lang = $jugador->lang;
            $login_origen = $jugador->login;
            $jugador_destino->SacarDatos($link_r, $iddestinatario);
            $login_destino = $jugador_destino->login;

            // PERO SOLO si tiene activo el envio de emails

            if ($jugador_destino->envio_emails == 1)
            {
              $body = "<html><body>";
              $body = $body."<br/><center>";
              $body = $body."<img src=\"http://www.geneoverload.com/img/logomail.png\">";
              $body = $body."</center><br/>";
              if ($lang == 'en')
              {
                $subject = "Gene Overload: You've received a new personal message";
                $body = $body."<p style=\"font-size: 14px; \">You have received a new personal message from user ".$login_origen."</p>";
                $body = $body."<p style=\"font-size: 12px; \">You can read it in your message area, and you can also deactivate notification emails in your profile screen.</p>";
                $body = $body."<p style=\"font-size: 12px; \">Subject : ".$asunto."</p>";
                $body = $body."<p style=\"font-size: 12px; \">Message content :</p>";
                $body = $body."<p style=\"font-size: 11px; background-color: #dedede; line-height: 3em; \">&nbsp;".$contenido."</p>";
              } else {
                $subject = "Gene Overload: Has recibido un nuevo mensaje personal";
                $body = $body."<p style=\"font-size: 14px; \">Has recibido un nuevo mensaje personal del usuario ".$login_origen."</p>";
                $body = $body."<p style=\"font-size: 12px; \">Puedes leerlo en tu &aacute;rea de mensajes, y tambi&eacute;n puedes desactivar las notificaciones a tu email en tu pantalla de perfil.</p>";
                $body = $body."<p style=\"font-size: 12px; \">Asunto : ".$asunto."</p>";
                $body = $body."<p style=\"font-size: 12px; \">Contenido del mensaje :</p>";
                $body = $body."<p style=\"font-size: 11px; background-color: #dedede; line-height: 3em; \">&nbsp;".$contenido."</p>";
              }
              $body = $body."</body></html>";
              $cabeceras  = 'MIME-Version: 1.0' . "\r\n";
              $cabeceras .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
              $cabeceras .= 'From: Gene Overload <no-reply@geneoverload.com> ' . "\r\n" .
                      'Reply-To: no-reply@geneoverload.com' . "\r\n" .
                      'X-Mailer: PHP/5.x'; // . phpversion();
              $email = $jugador_destino->email;
              $mail->enviar_mail($email, $subject, $body, $cabeceras);
            }




            if ($lang == 'en')
            {
              echo ("<p class=\"correcto\">Message sent</p>");
            } else {
              echo ("<p class=\"correcto\">Mensaje enviado</p>");
            }
            echo ("<br/>");
            echo ("<br/>");
            $accion = null;

        } else {
          if ($lang == 'en')
          {
            echo ("<p class=\"error\"><strong>Message not sent:</strong> Spam (do not repeat the same message).</p>");
          } else {
            echo ("<p class=\"error\"><strong>No se pudo enviar:</strong> Spam (no repitas el mismo mensaje).</p>");
          }
          echo ("<br/>");
          echo ("<br/>");
          $accion = "componer";
       }

          } else {
            if ($lang == 'en')
            {
              echo ("<p class=\"error\"><strong>Message not sent:</strong> Body must be less than 16384 characters, subject must be less than 70.</p>");
            } else {
              echo ("<p class=\"error\"><strong>No se pudo enviar:</strong> El cuerpo del mensaje debe tener menos de 16384 caracteres, y el asunto menos de 70.</p>");
            }
            echo ("<br/>");
            echo ("<br/>");
            $accion = "componer";
          }






      } // Cierra el else de que no esten vacios body y asunto

    } else {
      if ($lang == 'en')
      {
        echo ("<p class=\"error\"><strong>Message not sent:</strong> user '".$destinatario."' does not exist.</p>");
      } else {
        echo ("<p class=\"error\"><strong>No se pudo enviar:</strong> el usuario '".$destinatario."' no existe.</p>");
      }
      $accion = "componer";
    }

  }

  // ****************************************
  //    Componer un nuevo mensaje
  // ****************************************

  if ($accion == 'componer')
  {
    $origenid = $secure->Sanitizar($_REQUEST['origenid']);
    if (!is_numeric($origenid))
    {
      $origenid = 0;
    }

    $mensajes_personales = new Mensajes_Personales();
    if ($origenid != 0)
    {
      $mensajes_personales->SacarDatos($link_r, $origenid);
      // Si el mensaje no va dirigido a ti es que estas intentando hacer trampa
      if ($mensajes_personales->idusuariodestino != $idjugador)
      {
        die;
      }
//echo $mensajes_personales->idusuariodestino."#".$idjugador;

      if (($asunto == null) || ($asunto == ''))
      {
        $asunto = "Re: ".$mensajes_personales->asunto;
      }


    }



    // Para componer, lo dificil va a ser como se elige el nombre del usuario
    // De momento que se escriba y punto (elegir un desplegable con los amigos seria otra opcion, o autocompletar)

    if ($lang == 'en')
    {
      echo ("<center>");
      echo ("<p class=\"textonormal\"><a class=\"textonormal\" href=\"index.php?idcampana=".$idcampana."&catid=".$catid."\">Inbox</a> - ");
      echo ("Compose - ");
      echo ("<a class=\"textonormal\" href=\"index.php?catid=".$catid."&idcampana=".$idcampana."&accion=enviados\">Sent</a></p>");
      echo ("</center>");
    } else {
      echo ("<center>");
      echo ("<p class=\"textonormal\"><a class=\"textonormal\" href=\"index.php?idcampana=".$idcampana."&catid=".$catid."\">Bandeja de entrada</a> - ");
      echo ("Componer mensaje - ");
      echo ("<a class=\"textonormal\" href=\"index.php?catid=".$catid."&idcampana=".$idcampana."&accion=enviados\">Mensajes enviados</a></p>");
      echo ("</center>");
    }

    if (($origenid != null) && ($origenid != 0)) 
    {
      $destinatario = $secure->Sanitizar($_REQUEST['destinatario']);

      ?>

	<br/>
              <table width="100%" bgcolor="#221111" class="transparente_9" cellpadding="0px" cellspacing="0px"
        		style="padding-top: 15px; padding-bottom: 15px; padding-left: 15px; padding-right: 15px;">
        		<tr><td>
        		<?php
        if ($lang == 'en')
        {
          echo ("<p class=\"textonormal\">Answering a message from <strong>".$destinatario." :</strong></p>");
        } else {
          echo ("<p class=\"textonormal\">Respondiendo a un mensaje de <strong>".$destinatario." :</strong></p>");
        }

      // Ahora ponemos el cuerpo del mensaje
        ?>


     	</td></tr></table>
        <table width="100%" bgcolor="#181111" cellpadding="0px" cellspacing="0px"
        		style="padding-top: 15px; padding-bottom: 15px; padding-left: 15px; padding-right: 15px;">
          <tr height="8px" valign="top">
          <td width="8px" valign="top">
           <?php
            echo ("<span class=\"textonormal\">".$mensajes_personales->contenido."</span>");
           ?>
          </td>
          </tr>

          </table>
        <?php


    }

    ?>
    <br/>


        <table width="100%" bgcolor="#111111" class="transparente_8" cellpadding="0px" cellspacing="0px"
        		style="padding-top: 15px; padding-bottom: 15px; padding-left: 15px; padding-right: 15px;">
          <tr height="8px" valign="top">
          <td width="8px" valign="top">

    <form method="post" action="index.php">
     <?php
       if ($origenid != null) 
       {
         echo ("<input type=\"hidden\" name=\"origenid\" value=\"".$origenid."\">");
         ?>
           <p class="textonormal"><strong><?php if ($lang == 'en') { echo ("To : "); } else { echo ("Para : "); } ?></strong><?php echo $destinatario; ?></p>
           <input type="hidden" name="destinatario" value="<?php echo $destinatario; ?>">
	   <br/>
         <?php
       } else {
         ?>
 
         <div id="divdestinatario">
           <p class="textonormal"><strong><?php
		if ($lang == 'en')
		{
		  echo ("To :");
		} else {
		  echo ("Para :");
		}
		?>
		</strong><input type="text" name="destinatario" value="<?php echo $destinatario; ?>"
		 class="inputstandard"
		onblur="resultado('ajax_formularios.php?accion=destinatario&lang=<?php echo $lang; ?>&parametro='+this.value,'divdestinatario')"></p>
         </div>
	<br/>
         <?php
       }

     if ($lang == 'en')
     {
       echo ("<p class=\"textonormal\"><strong>Subject : </strong>");
     } else {
       echo ("<p class=\"textonormal\"><strong>Asunto : </strong>");
     }
     ?>
	<input type="text" name="asunto" size="60" value="<?php echo $asunto; ?>" class="inputstandard"></p>
	<br/>
     <?php
     if ($lang == 'en')
     {
       echo ("<p class=\"textonormal\"><strong>Message body : </strong></p>");
     } else {
       echo ("<p class=\"textonormal\"><strong>Cuerpo del mensaje : </strong></p>");
     }
     echo ("<br/>");
     ?>



                        <?php
//theme_advanced_buttons2 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
//theme_advanced_buttons3 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,spellchecker,|,insertfile,insertimage",
//<a href="javascript:setup();">Cargar</a>

//                        $oFCKeditor = new FCKeditor('contenido') ;
//                        $oFCKeditor->BasePath = $PATHBASE_exterior.'fckeditor/' ;
//                        $oFCKeditor->Height = 400 ;
//                        $oFCKeditor->Value = $contenido ;
//                        $oFCKeditor->Config['EditorAreaStyles'] = 'body { background-color: #333333; color: #ffffff }' ;
//                        $oFCKeditor->Create() ;
                        ?>


     <p class="textonormal"><textarea name="contenido" cols="70" rows="7"  class="inputstandard"><?php echo $contenido; ?></textarea></p>
     <?php
     if ($lang == 'en')
     {
       echo ("<p style=\"font-size: 11px;\"><i>( You can use: &lt;b&gt; &lt;i&gt; &lt;em&gt; &lt;u&gt; &lt;strong&gt; &lt;img&gt; &lt;center&gt; &lt;sub&gt; &lt;sup&gt; &lt;a&gt; &lt;strike&gt; )</i></p>");
     } else {
       echo ("<p style=\"font-size: 11px;\"><i>( Puedes usar: &lt;b&gt; &lt;i&gt; &lt;em&gt; &lt;u&gt; &lt;strong&gt; &lt;img&gt; &lt;center&gt; &lt;sub&gt; &lt;sup&gt; &lt;a&gt; &lt;strike&gt; )</i></p>");
     }
     ?>


<script>
	$(document).ready(function(){			
		$('textarea').elastic();
	});
</script>



     <input type="hidden" name="catid" value="<?php echo $catid; ?>">
     <input type="hidden" name="idcampana" value="<?php echo $idcampana; ?>">
     <input type="hidden" name="idrespondido" value="<?php echo $origenid; ?>">
     <input type="hidden" name="accion" value="preview">
<!--     <input type="hidden" name="accion" value="enviar">
-->

     <br/>
     <p class="textonormal">
       <input type="checkbox" name="preview" value="yes">
	<?php
	if ($lang == 'en')
        {
	  echo ("Preview: if you tick this, you will be able to check the message before its sent");
        } else {
	  echo ("Vista previa: si marcas esto podr&aacute;s comprobar el aspecto del mensaje antes de enviarlo");
        }
	?>
     </p>


     <br/>
     <p class="textonormal">
        <?php
	if ($lang == 'en')
        {
        ?>
  	  <input type="submit" name="enviar" value="Send message">
        <?php
        } else {
        ?>
  	  <input type="submit" name="enviar" value="Enviar mensaje">
        <?php
        }
        ?>
     </p>
    </form>
    
    
    </td></tr></table>
    <?php
  }



  // ****************************************
  //     Leer un mensaje (que has enviado)
  // ****************************************

  if ($accion == "leer_enviado")
  {
    $idmensaje = $secure->Sanitizar($_REQUEST['idmensaje']);
    if (!is_numeric($idmensaje))
    {
      $idmensaje = 0;
    }

    $jugador = new Jugador();
    $mensajes_personales = new Mensajes_Personales();
    
    // Primero comprobamos que sea nuestro.
    $permisos_acceso = $mensajes_personales->ExisteYSoyOrigen($link_r, $idjugador, $idmensaje);
    $mensajes_personales->SacarDatos($link_r, $idmensaje);

    // Ahora obtenemos el usuario origen
    $jugador->SacarDatos($link_r, $mensajes_personales->idusuariodestino);
    $nombreusuario = $jugador->login; //$unusuario['login'];
 
    echo ("<center>");
    echo ("<p class=\"textonormal\">");
    if ($lang == 'en')
    {
      echo ("<a class=\"textonormal\" href=\"index.php?catid=".$catid."&idcampana=".$idcampana."\">Inbox</a> - ");
      echo ("<a class=\"textonormal\" href=\"index.php?catid=".$catid."&idcampana=".$idcampana."&accion=componer\">Compose</a> - ");
      echo ("<a class=\"textonormal\" href=\"index.php?catid=".$catid."&idcampana=".$idcampana."&accion=enviados\">Sent</a></p>");
    } else {
      echo ("<a class=\"textonormal\" href=\"index.php?catid=".$catid."&idcampana=".$idcampana."\">Bandeja de entrada</a> - ");
      echo ("<a class=\"textonormal\" href=\"index.php?catid=".$catid."&idcampana=".$idcampana."&accion=componer\">Componer mensaje</a> - ");
      echo ("<a class=\"textonormal\" href=\"index.php?catid=".$catid."&idcampana=".$idcampana."&accion=enviados\">Mensajes enviados</a></p>");
    }
    echo ("</center>");

//    echo ("<table class=\"transparente_8\"><tr><td>");


    if ($permisos_acceso == 1)
    {

       // He respondido
       echo ("<br/>");
       if ($mensajes_personales->leido == 1)
       {
         if ($lang == 'en')
         {
           echo ("<p class=\"correcto\">This message has been opened by its recipient</p>");
         } else {
           echo ("<p class=\"correcto\">Este mensaje ha sido abierto por su destinatario</p>");
         }
         echo ("<br/>");
           // He respondido
           if ($mensajes_personales->respondido == 1)
           {
             if ($lang == 'en')
             {
               echo ("<p class=\"correcto\">The recipient has already answered this message</p><br/>");
             } else {
               echo ("<p class=\"correcto\">El destinatario ha respondido a este mensaje tuyo</p><br/>");
             }
           } else {
             if ($lang == 'en')
             {
               echo ("<p class=\"error\">Recipient has not answered this message</p><br/>");
             } else {
               echo ("<p class=\"error\">El destinatario no ha respondido a este mensaje tuyo</p><br/>");
             }
           }
           echo ("<br/>");

       } else {
         if ($lang == 'en')
         {
           echo ("<p class=\"error\">This message has not been opened by its recipient</p><br/>");
         } else {
           echo ("<p class=\"error\">Este mensaje no ha sido abierto por su destinatario</p><br/>");
         }
         echo ("<br/>");
         echo ("<br/>");
       }


       echo ("<table bgcolor=\"#221111\" width=\"100%\"
        		style=\"padding-left: 15px; padding-right: 15px; padding-top: 15px; padding-bottom: 15px;\">
			<tr><td>");

       $linkuser = "<a href=\"index.php?catid=5&accion=ver&idcampana=".$idcampana."&idelemento=".$mensajes_personales->idusuariodestino."\">";
       // vamos a pintar el mensajito de las narices
       if ($lang == 'en')
       {
         echo ("<p class=\"textonormal\"><b>To :</b> ".$linkuser.$nombreusuario."</a></p>");
         echo ("</td></tr></table>");
         echo ("<table bgcolor=\"#181111\" width=\"100%\"
        		style=\"padding-left: 15px; padding-right: 15px; padding-top: 15px; padding-bottom: 15px;
				\">
			<tr><td>");
         echo ("<p class=\"textonormal\"><strong>Subject : </strong>".$mensajes_personales->asunto."</p>");
//         echo ("<p class=\"textonormal\"><strong>Message body :</strong></p>");
       } else {
         echo ("<p class=\"textonormal\"><b>Enviado a :</b> ".$linkuser.$nombreusuario."</a></p>");
         echo ("</td></tr></table>");
         echo ("<table bgcolor=\"#181111\" width=\"100%\"
        		style=\"padding-left: 15px; padding-right: 15px; padding-top: 15px; padding-bottom: 15px;
				\">
			<tr><td>");
         echo ("<p class=\"textonormal\"><b>Asunto : </b>".$mensajes_personales->asunto."</p>");
//         echo ("<p class=\"textonormal\"><strong>Cuerpo del mensaje :</strong></p>");
       }


        ?>
        </td></tr></table>

        <table width="100%" bgcolor="#111111" cellpadding="0px" cellspacing="0px"
        		style="padding-left: 15px; padding-right: 15px; padding-top: 15px; padding-bottom: 15px;">
          <tr height="8px" valign="top">
          <td width="8px" valign="top">
        <?php
         echo ("<span class=\"textonormal\">".$mensajes_personales->contenido."</span>");
        ?>
          </td>
          </tr>

          </table>
        <?php


       // ********************** PREVIOUSLY IN THIS CONVERSATION *************************
       // Y ahora es cuando te lista el que haya antes...
       $idrespondido = $mensajes_personales->idrespondido;
//       if (($mensajes_personales->idrespondido != 0) && ($mensajes_personales->idrespondido != null))
       if (($idrespondido != 0) && ($idrespondido != null))
       {
         echo ("<br/><br/><br/>");
         echo ("<p style=\"color: #cecece\"><i>");
         if ($lang == 'en')
         {
           echo ("Previously in this conversation :");
         } else {
           echo ("Anteriormente en esta conversaci&oacute;n :");
         }
         echo ("</i></p>");
         echo ("<br/>");
         echo ("<div style=\"width: 650px; height: 250px; overflow: auto; padding: 5px\">");
         while (($idrespondido != 0) && ($idrespondido != null))
         {
           $mensajes_personales->SacarDatos($link_r, $idrespondido);
           $idrespondido = $mensajes_personales->idrespondido;


               
               $jugador->SacarDatos($link_r, $mensajes_personales->idusuarioorigen);
               $nombreusuario = $jugador->login;
               echo ("<table bgcolor=\"#121111\" width=\"100%\"
        		style=\"padding-left: 15px; padding-right: 15px; padding-top: 15px; padding-bottom: 15px;\">
			<tr><td>");
	      // vamos a pintar el mensajito de las narices
               $linkuser = "<a href=\"index.php?catid=5&accion=ver&idcampana=".$idcampana."&idelemento=".$mensajes_personales->idusuarioorigen."\">";
	       if ($lang == 'en')
	       {
	         echo ("<p class=\"textonormal\"><b>From : </b>".$linkuser.$nombreusuario."</a></p>");
	         echo ("</td></tr></table>");
	         echo ("<table bgcolor=\"#081111\" width=\"100%\"
	                        style=\"padding-left: 15px; padding-right: 15px; padding-top: 15px; padding-bottom: 15px;
	                                \">
	                        <tr><td>");
	         echo ("<p class=\"textonormal\"><b>Subject : </b>".$mensajes_personales->asunto."</p>");
	       } else {	
	         echo ("<p class=\"textonormal\"><b>Desde :</b> ".$linkuser.$nombreusuario."</a></p>");
	         echo ("</td></tr></table>");
	         echo ("<table bgcolor=\"#081111\" width=\"100%\"
	                        style=\"padding-left: 15px; padding-right: 15px; padding-top: 15px; padding-bottom: 15px;\">
	                        <tr><td>");
	         echo ("<p class=\"textonormal\"><b>Asunto : </b>".$mensajes_personales->asunto."</p>");
	       }
	
	        ?>
	     </td></tr></table>
	
        	<table width="100%" bgcolor="#011111" cellpadding="0px" cellspacing="0px"
	                        style="padding-left: 15px; padding-right: 15px; padding-top: 15px; padding-bottom: 15px;">
		          <tr height="8px" valign="top">
	          <td width="8px" valign="top">
	        <?php
	         echo ("<span class=\"textonormal\">".$mensajes_personales->contenido."</span>");
	        ?>
        	  </td>
	          </tr>
	          </table>
		<br/>
		<br/>
        	<?php


           
         }
         echo ("</div>");
       }






//       echo ("<table class=\"mensajeforo\" width=\"700px\" cellpadding=\"20px\"  bgcolor=\"#cecece\" cellspacing=\"1px\" >");
//       echo ("<tr><td>");
//         echo ("<p class=\"textonormal\">".$mensajes_personales->contenido."</p>");
//       echo ("</td></tr></table>");


    } else {
      if ($lang == 'en')
      {
        echo ("<p class=\"error\">Error accessing the message</p>");
      } else {
        echo ("<p class=\"error\">Error accediendo al mensaje</p>");
      }
    }

   echo ("<br/>");
	echo ("</td></tr></table>");

  }


  // ****************************************
  //     Leer un mensaje (de otro)
  // ****************************************

  if ($accion == "leer")
  {

    $idmensaje = $secure->Sanitizar($_REQUEST['idmensaje']);
    if (!is_numeric($idmensaje))
    {
      $idmensaje = 0;
    }

    $jugador = new Jugador();
    $mensajes_personales = new Mensajes_Personales();

    // Primero comprobamos que sea nuestro.
    $permisos_acceso = $mensajes_personales->ExisteYSoyDestino($link_r, $idjugador, $idmensaje);
    $mensajes_personales->SacarDatos($link_r, $idmensaje);

    // Me falta sacar el usuario...
    $jugador->SacarDatos($link_r, $mensajes_personales->idusuarioorigen);
    $nombreusuario = $jugador->login;

    echo ("<center>");
    echo ("<p class=\"textonormal\">");
    if ($lang == 'en')
    {
      echo ("<p class=\"textonormal\"><a class=\"textonormal\" href=\"index.php?catid=".$catid."&idcampana=".$idcampana."\">Inbox</a> - ");
      echo ("<a class=\"textonormal\" href=\"index.php?catid=".$catid."&idcampana=".$idcampana."&accion=componer\">Compose</a> - ");
      echo ("<a class=\"textonormal\" href=\"index.php?catid=".$catid."&idcampana=".$idcampana."&accion=enviados\">Sent</a></p>");
    } else {
      echo ("<a class=\"textonormal\" href=\"index.php?catid=".$catid."&idcampana=".$idcampana."\">Bandeja de entrada</a> - ");
      echo ("<a class=\"textonormal\" href=\"index.php?catid=".$catid."&idcampana=".$idcampana."&accion=componer\">Componer mensaje</a> - ");
      echo ("<a class=\"textonormal\" href=\"index.php?catid=".$catid."&idcampana=".$idcampana."&accion=enviados\">Mensajes enviados</a></p>");
    }
    echo ("</center>");
    echo ("<br/>");


    if ($permisos_acceso == 1)
    {

       // He respondido
       if ($mensajes_personales->respondido == 1)
       {
         if ($lang == 'en')
         {
           echo ("<p class=\"correcto\">You've already replied to this message</p><br/><br/>");
         } else {
           echo ("<p class=\"correcto\">Ya has respondido a este mensaje</p><br/><br/>");
         }
       }

       echo ("<table bgcolor=\"#221111\" width=\"100%\"
        		style=\"padding-left: 15px; padding-right: 15px; padding-top: 15px; padding-bottom: 15px;\">
			<tr><td>");



       if ($mensajes_personales->leido == 0)
       {
         $mensajes_personales->UpdatearLeido($link_w, $idmensaje);
         // Tambien actualizamos en pantalla con el javascript
         $mensajitos = $mensajitos - 1;
       }


       $linkuser = "<a href=\"index.php?catid=5&accion=ver&idcampana=".$idcampana."&idelemento=".$mensajes_personales->idusuarioorigen."\">";
       // vamos a pintar el mensajito de las narices
       if ($lang == 'en')
       {
         echo ("<p class=\"textonormal\"><b>From : </b>".$linkuser.$nombreusuario."</a></p>");
         echo ("</td></tr></table>");
         echo ("<table bgcolor=\"#181111\" width=\"100%\"
        		style=\"padding-left: 15px; padding-right: 15px; padding-top: 15px; padding-bottom: 15px;
				\">
			<tr><td>");
         echo ("<p class=\"textonormal\"><b>Subject : </b>".$mensajes_personales->asunto."</p>");
       } else {
         echo ("<p class=\"textonormal\"><b>Desde : </b>".$linkuser.$nombreusuario."</a></p>");
         echo ("</td></tr></table>");
         echo ("<table bgcolor=\"#181111\" width=\"100%\"
        		style=\"padding-left: 15px; padding-right: 15px; padding-top: 15px; padding-bottom: 15px;\">
			<tr><td>");
         echo ("<p class=\"textonormal\"><b>Asunto : </b>".$mensajes_personales->asunto."</p>");
       }

        ?>
     </td></tr></table>

        <table width="100%" bgcolor="#111111" cellpadding="0px" cellspacing="0px"
        		style="padding-left: 15px; padding-right: 15px; padding-top: 15px; padding-bottom: 15px;">
          <tr height="8px" valign="top">
          <td width="8px" valign="top">
        <?php
         echo ("<span class=\"textonormal\">".$mensajes_personales->contenido."</span>");
        ?>
          </td>
          </tr>
          </table>
        <?php


//       echo ("<table class=\"mensajeforo\" width=\"700px\" cellpadding=\"20px\"  bgcolor=\"#cecece\" cellspacing=\"1px\" >");
//       echo ("<tr><td>");
//         echo ("<p class=\"textonormal\">".$mensajes_personales->contenido."</p>");
//       echo ("</td></tr></table>");


       // Posibilidad de responder
       echo ("<p><a class=\"textonormal\" href=\"index.php?catid=".
		$catid."&idcampana=".$idcampana."&accion=componer&destinatario=".$nombreusuario.
		"&origenid=".$mensajes_personales->id."\">");
//		"&idmensaje=".$idmensaje.
       echo ("<br/>");
       echo ("<br/>");

       if ($lang == 'en')
       {
         echo ("Answer this message</a></p>");
       } else {
         echo ("Responder a este mensaje</a></p>");
       }




       // ********************** PREVIOUSLY IN THIS CONVERSATION *************************
       // Y ahora es cuando te lista el que haya antes...
       $idrespondido = $mensajes_personales->idrespondido;
//       if (($mensajes_personales->idrespondido != 0) && ($mensajes_personales->idrespondido != null))
       if (($idrespondido != 0) && ($idrespondido != null))
       {
         echo ("<br/><br/><br/>");
         echo ("<p style=\"color: #cecece\"><i>");
         if ($lang == 'en')
         {
           echo ("Previously in this conversation :");
         } else {
           echo ("Anteriormente en esta conversaci&oacute;n :");
         }
         echo ("</i></p>");
         echo ("<br/>");
         echo ("<div style=\"width: 650px; height: 250px; overflow: auto; padding: 5px\">");
         while (($idrespondido != 0) && ($idrespondido != null))
         {
           $mensajes_personales->SacarDatos($link_r, $idrespondido);
           $idrespondido = $mensajes_personales->idrespondido;

               $jugador->SacarDatos($link_r, $mensajes_personales->idusuarioorigen);
               $nombreusuario = $jugador->login;
               echo ("<table bgcolor=\"#121111\" width=\"100%\"
        		style=\"padding-left: 15px; padding-right: 15px; padding-top: 15px; padding-bottom: 15px;\">
			<tr><td>");
               $linkuser = "<a href=\"index.php?catid=5&accion=ver&idcampana=".$idcampana."&idelemento=".$mensajes_personales->idusuarioorigen."\">";
	      // vamos a pintar el mensajito de las narices
	       if ($lang == 'en')
	       {
	         echo ("<p class=\"textonormal\"><b>From : </b>".$linkuser.$nombreusuario."</a></p>");
	         echo ("</td></tr></table>");
	         echo ("<table bgcolor=\"#081111\" width=\"100%\"
	                        style=\"padding-left: 15px; padding-right: 15px; padding-top: 15px; padding-bottom: 15px;
	                                \">
	                        <tr><td>");
	         echo ("<p class=\"textonormal\"><b>Subject : </b>".$mensajes_personales->asunto."</p>");
	       } else {
	         echo ("<p class=\"textonormal\"><b>Desde :</b> ".$linkuser.$nombreusuario."</a></p>");
	         echo ("</td></tr></table>");
	         echo ("<table bgcolor=\"#081111\" width=\"100%\"
	                        style=\"padding-left: 15px; padding-right: 15px; padding-top: 15px; padding-bottom: 15px;\">
	                        <tr><td>");
	         echo ("<p class=\"textonormal\"><b>Asunto : </b>".$mensajes_personales->asunto."</p>");
	       }
	
	        ?>
	     </td></tr></table>
	
        	<table width="100%" bgcolor="#011111" cellpadding="0px" cellspacing="0px"
	                        style="padding-left: 15px; padding-right: 15px; padding-top: 15px; padding-bottom: 15px;">
		          <tr height="8px" valign="top">
	          <td width="8px" valign="top">
	        <?php
	         echo ("<span class=\"textonormal\">".$mensajes_personales->contenido."</span>");
	        ?>
        	  </td>
	          </tr>
	          </table>
		<br/>
		<br/>
        	<?php


         }
         echo ("</div>");
       }






    } else {
      if ($lang == 'en')
      {
        echo ("<p class=\"error\">Error accessing message</p>");
      } else {
        echo ("<p class=\"error\">Error accediendo al mensaje</p>");
      }
    }
    
//    echo ("</td></tr></table>");    
    

  }





  // ****************************************
  //    Eliminar mensaje enviado
  // ****************************************

  if ($accion == "eliminar")
  {
    $mensajes_personales = new Mensajes_Personales();

    $idmensaje = $secure->Sanitizar($_REQUEST['idmensaje']);
    if (!is_numeric($idmensaje))
    {
      $idmensaje = 0;
    }

//    $esmio = $mensajes_personales->ExisteYSoyDestino($link_r, $idusuario, $idmensaje);
    $esmio = $mensajes_personales->ExisteYSoyDestino($link_r, $idjugador, $idmensaje);
    if ($esmio == 1)
    {

      $mensajes_personales->SacarDatos($link_r, $idmensaje);
      if ($mensajes_personales->leido == 0)
      {
        $mensajitos = $mensajitos - 1;
      }

      $mensajes_personales->BorrarEnDestino($link_w, $idmensaje);
      echo ("<p class=\"correcto\">Mensaje eliminado</p>");
    } else {
      echo ("<p class=\"error\"><strong>Error : </strong>No puedes eliminar este mensaje</p>");
    }
    $accion = null;
  }



  // ****************************************
  //    Eliminar mensajes enviados a otros
  // ****************************************

  if ($accion == "eliminar_enviado")
  {
    $idmensaje = $secure->Sanitizar($_REQUEST['idmensaje']);
    if (!is_numeric($idmensaje))
    {
      $idmensaje = 0;
    }

    $mensajes_personales = new Mensajes_Personales();

//    $esmio = $mensajes_personales->ExisteYSoyOrigen($link_r, $idusuario, $idmensaje);
    $esmio = $mensajes_personales->ExisteYSoyOrigen($link_r, $idjugador, $idmensaje);
    if ($esmio == 1)
    {
      $mensajes_personales->BorrarEnOrigen($link_w, $idmensaje);
      echo ("<p class=\"correcto\">Mensaje eliminado</p>");
    } else {
      echo ("<p class=\"error\"><strong>Error : </strong>No puedes eliminar este mensaje</p>");
    }
    $accion = "enviados";
  }





  // ****************************************
  //    Mensajes enviados a otros
  // ****************************************

  if ($accion == "enviados")
  {

    $mensajes_personales = new Mensajes_Personales();

//    $querymensajes = $mensajes_personales->ObtenerElementosEnviados($link_r, $idjugador);

    if ($lang == 'en')
    {
        ?>
         <script>
           function confirmarEliminar(delUrl) {
            if (confirm("Are you sure you want to delete this?")) {
             document.location = delUrl;
            }
           }
         </script>
       <?php
    } else {
        ?>
         <script>
           function confirmarEliminar(delUrl) {
            if (confirm("Seguro que desea eliminar esta entrada?")) {
             document.location = delUrl;
            }
           }
         </script>
       <?php
    }

    echo ("<center>");
    if ($lang == 'en')
    {
      echo ("<p class=\"textonormal\"><a class=\"textonormal\" href=\"index.php?catid=".$catid."&idcampana=".$idcampana."\">Inbox</a> - ");
      echo ("<a class=\"textonormal\" href=\"index.php?catid=".$catid."&idcampana=".$idcampana."&accion=componer\">Compose</a> - ");
      echo ("Sent</p>");
    } else {
      echo ("<p class=\"textonormal\"><a class=\"textonormal\" href=\"index.php?catid=".$catid."&idcampana=".$idcampana."\">Bandeja de entrada</a> - ");
      echo ("<a class=\"textonormal\" href=\"index.php?catid=".$catid."&idcampana=".$idcampana."&accion=componer\">Componer mensaje</a> - ");
      echo ("Mensajes enviados</p>");
    }
    echo ("</center>");
    echo ("<br/>");





    // Aqui va a ir el paginado
    $limitelementos = $limitmensajes;
    $pg = $_REQUEST['pg'];
    if ($pg == null) { $pg = 1; }
    if (!is_numeric($pg))
    {
      $pg = 1;
    }
    $offset = (($pg - 1) * $limitelementos);
    $querymensajes = $mensajes_personales->ObtenerElementosEnviados($link_r, $idjugador, $limitelementos, $offset);
    $numelementostotal = $mensajes_personales->ContarElementosEnviados($link_r, $idjugador);


//    echo ("<center>");
    // ------------------------------> Paginado <--------------------------------------
    // Pagina $pg de $totpg
    $totpg = floor(($numelementostotal - 1) / $limitelementos) + 1;
    if ($totpg < 1) { $totpg = 1; }
    if ($totpg > 1)
    {
    if ($pg > 1) {
      $pgant = $pg - 1;
      echo ("<a class=\"paginado\" href=\"".$PATHBASE_exterior."index.php?catid=".$catid."&idcampana=".$idcampana."&pg=".$pgant."&accion=enviados\">");
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
        echo (" <a class=\"pginado\" href=\"".$PATHBASE_exterior."index.php?catid=".$catid."&idcampana=".$idcampana."&pg=".$i."&accion=enviados\">".$i."</a> ");
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
        echo (" <a class=\"pginado\" href=\"".$PATHBASE_exterior."index.php?catid=".$catid."&idcampana=".$idcampana."&pg=".$i."&accion=enviados\">".$i."</a> ");
      }

      // Para la ultima pagina
      if (($i == $totpg) && ($pg < ($totpg - 2)))
      {
        echo (" <a class=\"pginado\" href=\"".$PATHBASE_exterior."index.php?catid=".$catid."&idcampana=".$idcampana."&pg=".$i."&accion=enviados\">".$i."</a> ");
      }

    }

    if ( ($offset + $limitelementos) < $numelementostotal) {
//      if ($pgf == 1) { echo ("<span class=\"paginado\"> - </span>"); }
      $pgsig = $pg + 1;
      echo ("<a class=\"paginado\" href=\"".$PATHBASE_exterior."index.php?catid=".$catid."&idcampana=".$idcampana."&pg=".$pgsig."&accion=enviados\">");
      //P&aacute;gina siguiente</a>");
      echo ("<img src=\"img/arrow_right.gif\" style=\"vertical-align: middle;\">");
      echo ("</a>");
    }
    } // if $totpg > 1 (sino no imprime nada)
    // ------------------------------> Paginado <--------------------------------------
//    echo ("</center>");


    echo ("<br/>");






    echo ("<br/>");
    echo ("<table class=\"tabla_mensajes\" align=\"center\" bgcolor=\"#000000\" cellpadding=\"3px\" cellspacing=\"1px\">");
//    echo ("<table class=\"mensajeforo\" align=\"center\" bgcolor=\"#000000\" cellpadding=\"3px\" cellspacing=\"1px\">");
    echo ("<tr>");
    echo ("<th width=\"700px;\" colspan=\"4\">"); // style=\"background-color: #222222\">");
//    echo ("<th width=\"700px;\" colspan=\"4\" style=\"background-color: #222222\">");
    echo ("<br/>");
    if ($lang == 'en')
    {
      echo ("<span class=\"titmenu\"><strong>Sent messages</strong></span>");
    } else {
      echo ("<span class=\"titmenu\"><strong>Mensajes enviados</strong></span>");
    }
    echo ("<br/>");
    echo ("<br/>");
    echo ("</th>");
    echo ("</tr>");
    if (mysql_num_rows($querymensajes) > 0)
    {
      echo ("<tr>");
      echo ("<th width=\"10px\"></th>");
      if ($lang == 'en')
      {
        echo ("<th width=\"25px\">Date</th>");
        echo ("<th width=\"100px\">To</th>");
        echo ("<th width=\"300px\">Subject</th>");
      } else {
        echo ("<th width=\"25px\">Fecha</th>");
        echo ("<th width=\"100px\">Destinatario</th>");
        echo ("<th width=\"300px\">Asunto</th>");
      }
      echo ("</tr>");
      $cuantos = 0;
      while ($unmensaje = mysql_fetch_array($querymensajes))
      {
         $cuantos++;
         if ($unmensaje['leido'] == 1)
         {
           echo("<tr style=\"background-color: #111111\">");
         } else {
           echo("<tr style=\"background-color: #152515\">");
         }
         $mifechaenvio = substr($unmensaje['fecha_envio'],0,10);
         echo ("<td align=\"center\">");
         echo ("<a href=\"javascript:confirmarEliminar('index.php?catid=".$catid."&accion=eliminar_enviado&idmensaje=".$unmensaje['id']."')\">");
         echo ("<img src=\"img/ban.gif\" border=\"0\"> ");
//         echo ("<img src=\"images/delete.gif\" border=\"0\"> ");
         echo ("</a>");
         echo ("</td>");

         if ($unmensaje['leido'] == 1)
         {
           echo ("<td width=\"50px\" align=\"center\" class=\"textonormal\">".$mifechaenvio."</td>");
         } else {
           echo ("<td width=\"50px\" align=\"center\" class=\"textonormal\"><b>".$mifechaenvio."</b></td>");
         }

         echo ("<td class=\"textonormal\">");
         echo ("<a href=\"index.php?catid=5&accion=ver&idcampana=".$idcampana."&idelemento=".$unmensaje['idusuariodestino']."\">");
         if ($unmensaje['leido'] == 1)
         {
           echo ($unmensaje['login']."</td>");
         } else {
           echo ($unmensaje['login']."</b></td>");
         }
         echo ("<td>");
         if ($unmensaje['leido'] == 1)
         {
           if ($unmensaje['respondido'] == 1)
           {
             echo ("<strong><span class=\"correcto\">[R]</span></strong> ");
           }
           echo ("<a class=\"textonormal\" href=\"index.php?catid=".$catid."&idcampana=".$idcampana."&accion=leer_enviado&idmensaje=".$unmensaje['id']."\">");
           echo ($unmensaje['asunto']);
           echo ("</a>");
         } else {
           echo ("<a class=\"textonormal\" href=\"index.php?catid=".$catid."&idcampana=".$idcampana."&accion=leer_enviado&idmensaje=".$unmensaje['id']."\"><b>");
           echo ($unmensaje['asunto']."</b></a>");
         }
         echo ("</td>");
         echo("</tr>");
      }

      // Rellenamos para que no se vea tan feo
      for ($i = 1; $i <= ($limitelementos - $cuantos); $i++)
      {
         if ($i % 2 == 1)
         {
           echo ("<tr style=\"background-color: #111111;\">");
         } else {
           echo ("<tr style=\"background-color: #152515\">");
         }
         echo ("<td colspan=\"4\"></td>");
         echo ("</tr>");
//echo $cuantos;
      }


    } else {
      echo ("<tr style=\"background-color: #111111; font-size: 13px; height: 200px;\">");
      echo ("<td align=\"center\" colspan=\"2\">");
      if ($lang == 'en')
      {
        echo ("<span class=\"error\"><strong>You have not sent any messages</strong></span>");
      } else {
        echo ("<span class=\"error\"><strong>No has enviado mensajes</strong></span>");
      }
      echo ("</td></tr>");
//      echo ("<tr><td align=\"center\" colspan=\"2\">");
//      echo ("<span class=\"errorsutil\"><strong>No has enviado mensajes</strong></span>");
//      echo ("</td></tr>");
    }
    echo ("</table>");


        // JQUERY PARA QUE PINTE LA ROW
        ?>
        <script>
        $('table.tabla_mensajes tr').hover(function(){
          $(this).find('td').addClass('hovered');
        }, function(){
          $(this).find('td').removeClass('hovered');
        });
        </script>
        <?php


  }


  // ****************************************
  //     El standard es el INBOX
  // ****************************************

  if ($accion == null)
  {

    $mensajes_personales = new Mensajes_Personales();

    if ($lang == 'en')
    {
        ?>
         <script>
           function confirmarEliminar(delUrl) {
            if (confirm("Are you sure you want to delete this message?")) {
             document.location = delUrl;
            }
           }
         </script>
       <?php
    } else {
        ?>
         <script>
           function confirmarEliminar(delUrl) {
            if (confirm("Seguro que desea eliminar esta entrada?")) {
             document.location = delUrl;
            }
           }
         </script>
       <?php
    }

    echo ("<center>");
    if ($lang == 'en')
    {
      echo ("<p class=\"textonormal\">Inbox - ");
      echo ("<a class=\"textonormal\" href=\"index.php?catid=".$catid."&idcampana=".$idcampana."&accion=componer\">Compose</a> - ");
      echo ("<a class=\"textonormal\" href=\"index.php?catid=".$catid."&idcampana=".$idcampana."&accion=enviados\">Sent</a></p>");
    } else {
      echo ("<p class=\"textonormal\">Bandeja de entrada - ");
      echo ("<a class=\"textonormal\" href=\"index.php?catid=".$catid."&idcampana=".$idcampana."&accion=componer\">Componer mensaje</a> - ");
      echo ("<a class=\"textonormal\" href=\"index.php?catid=".$catid."&idcampana=".$idcampana."&accion=enviados\">Mensajes enviados</a></p>");
    }
    echo ("</center>");
    echo ("<br/>");


    // Aqui va a ir el paginado
    $limitelementos = $limitmensajes;
    $pg = $_REQUEST['pg'];
    if ($pg == null) { $pg = 1; }
    if (!is_numeric($pg))
    {
      $pg = 1;
    }
    $offset = (($pg - 1) * $limitelementos);
    $querymensajes = $mensajes_personales->ObtenerElementosInbox($link_r, $idjugador, $limitelementos, $offset);
    $numelementostotal = $mensajes_personales->ContarElementosInbox($link_r, $idjugador);




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
//      if ($pgf == 1) { echo ("<span class=\"paginado\"> - </span>"); }
      $pgsig = $pg + 1;
      echo ("<a class=\"paginado\" href=\"".$PATHBASE_exterior."index.php?catid=".$catid."&idcampana=".$idcampana."&pg=".$pgsig."\">");
      //P&aacute;gina siguiente</a>");
      echo ("<img src=\"img/arrow_right.gif\" style=\"vertical-align: middle;\">");
      echo ("</a>");
    }
    } // if $totpg > 1 (sino no imprime nada)
    // ------------------------------> Paginado <--------------------------------------


    echo ("<br/>");
    echo ("<br/>");







    echo ("<table class=\"tabla_mensajes\" align=\"center\" bgcolor=\"#000000\" cellpadding=\"3px\" cellspacing=\"1px\">");
    echo ("<tr>");
    echo ("<th width=\"700px;\" colspan=\"4\">"); // style=\"background-color: #222222\">");
    echo ("<br/>");
    if ($lang == 'en')
    {
      echo ("<span class=\"titmenu\"><strong>Inbox</strong></span>");
    } else {
      echo ("<span class=\"titmenu\"><strong>Bandeja de entrada</strong></span>");
    }
    echo ("<br/>");
    echo ("<br/>");
    echo ("</th>");
    echo ("</tr>");
    if (mysql_num_rows($querymensajes) > 0)
    {
      echo ("<tr>");
      if ($lang == 'en')
      {
        echo ("<th width=\"10px\"></th>");
        echo ("<th width=\"25px\">Date</th>");
        echo ("<th width=\"100px\">From</th>");
        echo ("<th width=\"300px\">Subject</th>");
      } else {
        echo ("<th width=\"10px\"></th>");
        echo ("<th width=\"25px\">Fecha</th>");
        echo ("<th width=\"100px\">Desde</th>");
        echo ("<th width=\"300px\">Asunto</th>");
      }
      echo ("</tr>");
//      $cuantos=0;
      while ($unmensaje = mysql_fetch_array($querymensajes))
      {
         $cuantos++;
         if ($unmensaje['leido'] == 1)
         {
           echo("<tr style=\"background-color: #111111\">");
         } else {
           echo("<tr style=\"background-color: #152515\">");
         }
         $mifechaenvio = substr($unmensaje['fecha_envio'],0,10);

         echo ("<td align=\"center\">");
         echo ("<a href=\"javascript:confirmarEliminar('index.php?catid=".$catid."&idcampana=".$idcampana."&accion=eliminar&idmensaje=".$unmensaje['id']."')\">");
//         echo ("<img src=\"images/delete.gif\" border=\"0\"> ");
         echo ("<img src=\"img/ban.gif\" border=\"0\"> ");
         echo ("</a>");
         echo ("</td>");

         if ($unmensaje['leido'] == 1)
         {
           echo ("<td align=\"center\"  width=\"50px\" class=\"textonormal\">".$mifechaenvio."</td>");
         } else {
           echo ("<td align=\"center\"  width=\"50px\" class=\"textonormal\"><b>".$mifechaenvio."</b></td>");
         } 

         echo ("<td class=\"textonormal\">");
         echo ("<a href=\"index.php?catid=5&accion=ver&idcampana=".$idcampana."&idelemento=".$unmensaje['idusuarioorigen']."\">");
         if ($unmensaje['leido'] == 1)
         {
           echo ($unmensaje['login']);
         } else {
           echo ("<b>".$unmensaje['login']."</b>");
         }
         echo ("</a>");
         echo ("</td>");
         echo ("<td class=\"textonormal\">");
         if ($unmensaje['leido'] == 1)
         {
           if ($unmensaje['respondido'] == 1)
           {
             echo ("<b><span class=\"correcto\">[R]</b></strong> "); 
           }
           echo ("<a class=\"textonormal\" href=\"index.php?catid=".$catid."&accion=leer&idcampana=".$idcampana."&idmensaje=".$unmensaje['id']."\">");
           echo ($unmensaje['asunto']);
           echo ("</a>");
         } else {
           echo ("<a class=\"textonormal\" href=\"index.php?catid=".$catid."&accion=leer&idcampana=".$idcampana."&idmensaje=".$unmensaje['id']."\"><b>");
           echo ($unmensaje['asunto']."</b></a>");
         }
         echo ("</td>");
        echo("</tr>");
      }

      // Rellenamos para que no se vea tan feo
      for ($i = 1; $i <= ($limitelementos - $cuantos); $i++)
      {
        if ($i % 2 == 1)
        {
          echo ("<tr style=\"background-color: #111111;\">");
        } else {
          echo ("<tr style=\"background-color: #152515\">");
        }
        echo ("<td colspan=\"4\"></td>");
        echo ("</tr>");
//echo $cuantos;
      }


        // JQUERY PARA QUE PINTE LA ROW
        ?>
        <script>
        $('table.tabla_mensajes tr').hover(function(){
          $(this).find('td').addClass('hovered');
        }, function(){
          $(this).find('td').removeClass('hovered');
        });
        </script>
        <?php





    } else {
      echo ("<tr style=\"background-color: #111111; font-size: 13px; height: 200px;\">");
      echo ("<td align=\"center\" colspan=\"2\">");
      if ($lang == 'en')
      {
        echo ("<span class=\"error\"><strong>You have no messages</strong></span>");
      } else {
        echo ("<span class=\"error\"><strong>No tienes mensajes</strong></span>");
      }
      echo ("</td></tr>");
    }
    echo ("</table>");


  }

?>
