<?php

  include ('clases/obj_peticion_externa.php');
  include ('clases/obj_mail.php');



  // *********************************************
  //    Solicitar la beta (efectivo)
  // *********************************************

  if ($accion == 'solicitar')
  {

    $tipo = $_REQUEST['tipo'];
    $email = $_REQUEST['email'];
    $URL = $_REQUEST['URL'];
    $nmiembros = $_REQUEST['nmiembros'];

    $peticion_externa = new Peticion_Externa();
    $peticion_externa->link_r = $link_r;
    $peticion_externa->link_w = $link_w;
    $peticion_externa->email = $email;
    $peticion_externa->URL = $URL;
    $peticion_externa->lang = $lang;
    $peticion_externa->nmiembros = $nmiembros;

    echo ("<br/>");
    echo ("<br/>");
    echo ("<br/>");
    echo ("<br/>");
    echo ("<br/>");
    echo ("<br/>");
    echo ("<br/>");
    echo ("<br/>");
    echo ("<br/>");
    echo ("<br/>");
    echo ("<br/>");
    echo ("<br/>");
    echo ("<br/>");

    $existe = $peticion_externa->Comprobar_si_existe();
    if ($existe == 0)
    {

      ?>

<!-- Google Code for Registrarse para beta Conversion Page -->
<script type="text/javascript">
/* <![CDATA[ */
var google_conversion_id = 1045128587;
var google_conversion_language = "en";
var google_conversion_format = "2";
var google_conversion_color = "ffffff";
var google_conversion_label = "djOzCMXloQIQi8ut8gM";
var google_conversion_value = 0;
/* ]]> */
</script>
<script type="text/javascript" src="http://www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt="" src="http://www.googleadservices.com/pagead/conversion/1045128587/?label=djOzCMXloQIQi8ut8gM&amp;guid=ON&amp;script=0"/>
</div>
</noscript>




      <?php



      if ($tipo == 1)
      {
        $peticion_externa->Insertar_Peticion_Usuario();
      }
      if ($tipo == 2)
      {
        $peticion_externa->Insertar_Peticion_Pagina();
      }

      echo ("<center>");
      echo ("<p style=\"font-size: 15px; color: #c58768; \">");
$mail = new Mail();
      if ($lang == 'en')
      {
        echo ("Your beta request has been saved. Thank you very much for your interest.");
        echo ("</p>");
        echo ("<br/>");
        echo ("<br/>");
        echo ("<p style=\"font-size: 14px; color: #c58768; \">");
        echo ("Please check out your email (and if you don't find anything, your spam folder), since we have sent you an email confirming your request. We will send your code from the same no-reply address.");
        echo ("</p>");

        $subject = "Gene Overload: Beta code request";
        $body = "<html><body>";
        $body = $body."<br/><center>";
        $body = $body."<img src=\"http://www.geneoverload.com/img/logomail.png\">";
        $body = $body."</center><br/>";
        $body = $body."<p style=\"font-size: 13px;\">";
        $body = $body."Somebody (probably you) has just requested a beta code for Gene Overload with this email. ";
        $body = $body."We will write you again if you are selected for the closed beta with a code you can use. ";
        $body = $body."</p>";
        $body = $body."</body></html>";
        $cabeceras  = 'MIME-Version: 1.0' . "\r\n";
        $cabeceras .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $cabeceras .= 'From: Gene Overload <no-reply@geneoverload.com> ' . "\r\n" .
                      'Reply-To: no-reply@geneoverload.com' . "\r\n" .
                      'X-Mailer: PHP/5.x'; // . phpversion();
        $mail->enviar_mail($email, $subject, $body, $cabeceras);
      }
      if ($lang == 'es')
      {
        echo ("Tu petici&oacute;n de beta ha sigo grabada. Muchas gracias por tu inter&eacute;s.");
        echo ("</p>");
        echo ("<br/>");
        echo ("<br/>");
        echo ("<p style=\"font-size: 14px; color: #c58768; \">");
        echo ("Por favor comprueba tu correo (y tu direcci&oacute;n de spam si no encuentras nada), ya que te hemos enviado un correo confirmando tu petici&oacute;n. Te enviaremos tu c&oacute;digo desde la misma cuenta de correo no-reply.");
        echo ("</p>");

        $subject = "Gene Overload: Peticion de beta";
        $body = "<html><body>";
        $body = $body."<br/><center>";
        $body = $body."<img src=\"http://www.geneoverload.com/img/logomail.png\">";
        $body = $body."</center><br/>";
        $body = $body."<p style=\"font-size: 13px;\">";
        $body = $body."Alguien (probablemente tu) ha pedido un c&oacute;digo de beta para Gene Overload con este email. ";
        $body = $body."Te escribiremos de nuevo si eres seleccionado para la beta cerrada con un c&oacute;digo que puedas utilizar. ";
        $body = $body."</p>";
        $body = $body."</body></html>";
        $cabeceras  = 'MIME-Version: 1.0' . "\r\n";
        $cabeceras .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $cabeceras .= 'From: Gene Overload <no-reply@geneoverload.com> ' . "\r\n" .
                      'Reply-To: no-reply@geneoverload.com' . "\r\n" .
                      'X-Mailer: PHP/5.x'; // . phpversion();
        $mail->enviar_mail($email, $subject, $body, $cabeceras);

      }
      echo ("</p>");
      echo ("</center>");



    } else {
      echo ("<center>");
      echo ("<p style=\"font-size: 15px; color: #ff0000; \">");
      if ($lang == 'en')
      {
        echo ("<b>Error :</b> This email has already requested a beta code.");
      } else {
        echo ("<b>Error :</b> Ya se ha pedido un c&oacute;digo de beta desde esta direcci&oacute;n de correo electr&oacute;nico.");
      }
      echo ("</p>");
      echo ("</center>");
    }
    echo ("<br/>");
    echo ("<br/>");
    echo ("<br/>");
    echo ("<br/>");
    echo ("<br/>");
    echo ("<br/>");
    echo ("<br/>");
    echo ("<br/>");
    echo ("<br/>");
    echo ("<br/>");
    echo ("<br/>");
    echo ("<br/>");


  }


  // *********************************************
  //    Solicitar tu propia entrada
  // *********************************************

  if ($accion == null)
  {

    ?>
      <form method="post" action="index.php">
       <input type="hidden" name="catid" value="<?php echo $catid; ?>">
       <input type="hidden" name="lang" value="<?php echo $lang; ?>">
       <input type="hidden" name="accion" value="solicitar">
    <?php

    if ($lang == 'en')
    {

      echo ("<p style=\"font-size: 15px; color: #c58768; \">");
      echo ("<b>Beta request for Gene Overload</b>");
      echo ("</p>");
      echo ("<br/>");
      echo ("<p style=\"font-size: 15px; color: #c58768; \">");
      echo ("Please fill your data below");
      echo ("</p>");
      echo ("<br/>");
      echo ("<br/>");
      echo ("<br/>");

      echo ("<table style=\"padding: 10px;
			\"
			>");
      echo ("<tr style=\"height: 35px;\"><td width=\"70px;\">");
      echo ("<span style=\"font-size: 15px; color: #c58768; \">");
      echo ("<b>Type </b>");
      echo ("</td><td>");
      echo ("<select name=\"tipo\">");
      echo ("<option value=\"1\">User</option>");
      echo ("<option value=\"2\">Webpage</option>");
      echo ("</select>");
      echo ("</span>");
      echo ("</td></tr>");

      echo ("<tr style=\"height: 35px;\"><td>");
      echo ("<p style=\"font-size: 15px; color: #c58768; \">");
      echo ("<b>URL </b> ");
      echo ("</td><td>");
      echo ("<input type=\"text\" name=\"URL\" size=\"60\">");
      echo ("</p>");
      echo ("</td></tr>");

      echo ("<tr style=\"height: 35px;\"><td>");
      echo ("<p style=\"font-size: 15px; color: #c58768; \">");
      echo ("<b>Amount</b> ");
      echo ("</td><td>");
      echo ("<input type=\"text\" name=\"nmiembros\" size=\"5\" value=\"1\">");
      echo ("</p>");
      echo ("</td></tr>");

      echo ("<tr style=\"height: 35px;\"><td colspan=\"2\">");
      echo ("<p style=\"font-size: 15px; color: #c58768; \">");
      echo ("<i>(change this value if you want to request more than 1 beta code)</i>");
      echo ("</p>");
      echo ("</td></tr>");

      echo ("<tr style=\"height: 35px;\"><td>");
      echo ("<p style=\"font-size: 15px; color: #c58768; \">");
      echo ("<b>Email ");
      echo ("</td><td>");
      echo ("<input type=\"text\" name=\"email\" size=\"50\">");
      echo ("</p>");
      echo ("</td></tr>");

      echo ("<tr style=\"height: 35px;\"><td colspan=\"2\">");
      echo ("<p style=\"font-size: 15px; color: #c58768; \">");
      echo ("<i>(We need your email so we can send you the code when we open the game for more accounts. We promise will not use it for anything else, we hate spam as much as you do)</i>");
      echo ("</p>");
      echo ("</td></tr>");


      echo ("<tr><td colspan=\"2\">");
      echo ("<br/>");
      echo ("<br/>");
      echo ("<input type=\"submit\" value=\"Request Beta\">");
      echo ("</td></tr>");

      echo ("</table>");

    } else {

      echo ("<p style=\"font-size: 15px; color: #c58768; \">");
      echo ("<b>Petici&oacute;n de Beta para Gene Overload</b>");
      echo ("</p>");
      echo ("<br/>");
      echo ("<p style=\"font-size: 15px; color: #c58768; \">");
      echo ("Por favor rellene sus datos");
      echo ("</p>");
      echo ("<br/>");
      echo ("<br/>");
      echo ("<br/>");

      echo ("<table style=\"padding: 10px;
			\"
			>");
      echo ("<tr style=\"height: 35px;\"><td width=\"70px;\">");
      echo ("<span style=\"font-size: 15px; color: #c58768; \">");
      echo ("<b>Tipo </b>");
      echo ("</td><td>");
      echo ("<select name=\"tipo\">");
      echo ("<option value=\"1\">Usuario</option>");
      echo ("<option value=\"2\">P&aacute;gina web</option>");
      echo ("</select>");
      echo ("</span>");
      echo ("</td></tr>");

      echo ("<tr style=\"height: 35px;\"><td>");
      echo ("<p style=\"font-size: 15px; color: #c58768; \">");
      echo ("<b>URL ");
      echo ("</td><td>");
      echo ("<input type=\"text\" name=\"URL\" size=\"60\">");
      echo ("</p>");
      echo ("</td></tr>");

      echo ("<tr style=\"height: 35px;\"><td>");
      echo ("<p style=\"font-size: 15px; color: #c58768; \">");
      echo ("<b>Cantidad</b> ");
      echo ("</td><td>");
      echo ("<input type=\"text\" name=\"nmiembros\" size=\"5\" value=\"1\">");
      echo ("</p>");
      echo ("</td></tr>");

      echo ("<tr style=\"height: 35px;\"><td colspan=\"2\">");
      echo ("<p style=\"font-size: 15px; color: #c58768; \">");
      echo ("<i>(cambia este valor si quieres pedir m&aacute;s de un c&oacute;digo de beta)</i>");
      echo ("</p>");
      echo ("</td></tr>");

      echo ("<tr style=\"height: 35px;\"><td>");
      echo ("<p style=\"font-size: 15px; color: #c58768; \">");
      echo ("<b>Email ");
      echo ("</td><td>");
      echo ("<input type=\"text\" name=\"email\" size=\"50\">");
      echo ("</p>");
      echo ("</td></tr>");

      echo ("<tr style=\"height: 35px;\"><td colspan=\"2\">");
      echo ("<p style=\"font-size: 15px; color: #c58768; \">");
      echo ("<i>(Necesitamos tu email para poder enviarte el c&oacute;digo cuando abramos el juego para m&aacute;s cuentas. Prometemos no utilizarlo para nada m&aacute;s. Odiamos el spam tanto como t&uacute;)</i>");
      echo ("</p>");
      echo ("</td></tr>");

      echo ("<tr><td colspan=\"2\">");
      echo ("<br/>");
      echo ("<br/>");
      echo ("<input type=\"submit\" value=\"Pedir Beta\">");
      echo ("</td></tr>");

      echo ("</table>");

    }

  }



?>
