<?php


include ('clases/obj_mail.php');

  $cadenasalt = "as+.Rd010S4k2#3i!l";


  $debug = $_REQUEST['debug'];
  if (!is_numeric($debug))
  {
    $debug = 0;
  }



// *********************************
//    Reset efectivo
// *********************************

if ($accion == 'resetear_efectivo')
{
  $email = $secure->Sanitizar($_REQUEST['email']);
  $code = $secure->Sanitizar($_REQUEST['code']);
  $clave = $secure->Sanitizar($_REQUEST['clave']);
  $clave2 = $secure->Sanitizar($_REQUEST['clave2']);

  if ($clave == $clave2)
  {
    if ($clave != '')
    {
      // Vale ahora si.
      $jugador = new Jugador();
      $haydatos = $jugador->SacarDatosDesdeEmail($link_r, $email);
      // Ahora en $jugador->id y demas, tenemos todo
      $jugador->clave = $clave;
      $jugador->CambiarClave($link_w, $jugador->id);
      if ($lang == 'en')
      {
        echo ("<p class=\"correcto\">Correct: You have succesfully changed your password on login ".$jugador->login.".</p>");
      } else {
        echo ("<p class=\"correcto\">Correcto: Has cambiado con &eacute;xito la clave del usuario ".$jugador->login.".</p>");
      }
      $accion = null;
    } else {
      if ($lang == 'en')
      {
        echo ("<p class=\"error\">Error: Password is empty.</p>");
      } else {
        echo ("<p class=\"error\">Error: Las claves est&aacute; vac&iacute;a.</p>");
      }
      $accion = "resetear";
    }
  } else {
    if ($lang == 'en')
    {
      echo ("<p class=\"error\">Error: Passwords do not match.</p>");
    } else {
      echo ("<p class=\"error\">Error: Las claves no coinciden.</p>");
    }
    $accion = "resetear";
  }
}


// *********************************
//    Reset
// *********************************

if ($accion == 'resetear')
{

  $email = $secure->Sanitizar($_REQUEST['email']);
  $code = $secure->Sanitizar($_REQUEST['code']);

  $emailsalt = md5($email.$cadenasalt);
  if ($code == $emailsalt)
  {
    // Llega aqui si lo ha hecho bien
    if ($lang == 'en')
    {
      echo ("<br/>");
      echo ("<p class=\"correcto\">Correct code</p>");
      echo ("<br/>");
      echo ("<form method=\"post\" action=\"index.php\">");
      echo ("<input type=\"hidden\" name=\"accion\" value=\"resetear_efectivo\">");
      echo ("<input type=\"hidden\" name=\"code\" value=\"".$code."\">");
      echo ("<input type=\"hidden\" name=\"email\" value=\"".$email."\">");
      echo ("<input type=\"hidden\" name=\"catid\" value=\"".$catid."\">");
      echo ("<br/>");
      echo ("<p style=\"font-size: 13px; font-weight: bold; color: #c58768;\">Write a new password : ");
      echo ("<input type=\"password\" name=\"clave\">");
      echo ("</p>");
      echo ("<br/>");
      echo ("<p style=\"font-size: 13px; font-weight: bold; color: #c58768;\">Confirm password : ");
      echo ("<input type=\"password\" name=\"clave2\">");
      echo ("</p>");
      echo ("<br/>");
      echo ("<br/>");
      echo ("<br/>");
      echo ("<p>");
      echo ("<input type=\"submit\" value=\"Change password\">");
      echo ("</p>");
      echo ("</form>");
      echo ("<br/>");
      echo ("<br/>");
    } else {
      echo ("<p class=\"correcto\">C&oacute;digo correcto</p>");
      echo ("<br/>");
      echo ("<form method=\"post\" action=\"index.php\">");
      echo ("<input type=\"hidden\" name=\"accion\" value=\"resetear_efectivo\">");
      echo ("<input type=\"hidden\" name=\"code\" value=\"".$code."\">");
      echo ("<input type=\"hidden\" name=\"email\" value=\"".$email."\">");
      echo ("<input type=\"hidden\" name=\"catid\" value=\"".$catid."\">");
      echo ("<br/>");
      echo ("<p style=\"font-size: 13px; font-weight: bold; color: #c58768;\">Escribe una nueva clave : ");
      echo ("<input type=\"password\" name=\"clave\">");
      echo ("</p>");
      echo ("<br/>");
      echo ("<p style=\"font-size: 13px; font-weight: bold; color: #c58768;\">Confirma tu clave : ");
      echo ("<input type=\"password\" name=\"clave2\">");
      echo ("</p>");
      echo ("<br/>");
      echo ("<br/>");
      echo ("<br/>");
      echo ("<p>");
      echo ("<input type=\"submit\" value=\"Cambiar clave\">");
      echo ("</p>");
      echo ("</form>");
      echo ("<br/>");
      echo ("<br/>");
    }


  } else {
    if ($lang == 'en')
    {
      echo ("<p class=\"error\">Error: Malformed link</p>");
    } else {
      echo ("<p class=\"error\">Error: Enlace mal formado</p>");
    }
    $accion = null;
  }

}


// *********************************
//    Enviar los datos de reset
// *********************************

if ($accion == 'enviar_reset')
{

  $email = $secure->Sanitizar($_REQUEST['email']);
  $jugador = new Jugador();
  $haydatos = $jugador->SacarDatosDesdeEmail($link_r, $email);
  if ($haydatos == true)
  {

$mail = new Mail();
    if ($lang == 'en')
    {
      $subject = "Gene Overload: Password reset request";
      $body = "<html><body>";
      $body = $body."<br/><center>";
      $body = $body."<img src=\"http://www.geneoverload.com/img/logomail.png\">";
      $body = $body."</center><br/>";
      $body = $body."<p style=\"font-size: 13px;\">";
      $body = $body."Somebody (probably you) has requested a password reset in Gene Overload. ";
      $body = $body."If you want to reset your old password and be given the opportunity to create a new one, just ";
      $body = $body."click in the following link:";
      $body = $body."</p>";
      $emailsalt = md5($email.$cadenasalt);
      $body = $body."<p style=\"font-size: 15px;\">";
      $body = $body."<a href=\"".$ruta_exterior."index.php?catid=".$catid."&lang=".$lang."&accion=resetear&code=".$emailsalt."&email=".$email."\">";
      $body = $body.$ruta_exterior."index.php?catid=".$catid."&lang=".$lang."&accion=resetear&code=".$emailsalt."&email=".$email;
      $body = $body."</a>";
      $body = $body."</p>";
      $body = $body."</body></html>";
      $cabeceras  = 'MIME-Version: 1.0' . "\r\n";
      $cabeceras .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
      $cabeceras .= 'From: Gene Overload <no-reply@geneoverload.com> ' . "\r\n" .
                      'Reply-To: no-reply@geneoverload.com' . "\r\n" .
                      'X-Mailer: PHP/5.x'; // . phpversion();
//      mail($email, $subject, $body, $cabeceras);
$mail->enviar_mail($email, $subject, $body, $cabeceras);

    } else {
      $subject = "Gene Overload: Peticion de nueva clave";
      $body = "<html><body>";
      $body = $body."<br/><center>";
      $body = $body."<img src=\"http://www.geneoverload.com/img/logomail.png\">";
      $body = $body."</center><br/>";
      $body = $body."<p style=\"font-size: 13px;\">";
      $body = $body."Alguien (probablemente tu) ha pedido el reinicio de su clave en Gene Overload. ";
      $body = $body."Si quieres reiniciar tu vieja clave y que se te proporcione la oportunidad de crear una nueva, ";
      $body = $body."tan s&oacute;lo tienes que hacer click en este enlace:";
      $body = $body."</p>";
      $emailsalt = md5($email.$cadenasalt);
      $body = $body."<p style=\"font-size: 15px;\">";
      $body = $body."<a href=\"".$ruta_exterior."index.php?catid=".$catid."&lang=".$lang."&accion=resetear&code=".$emailsalt."&email=".$email."\">";
      $body = $body.$ruta_exterior."index.php?catid=".$catid."&lang=".$lang."&accion=resetear&code=".$emailsalt."&email=".$email;
      $body = $body."</a>";
      $body = $body."</p>";
      $body = $body."</body></html>";
      $cabeceras  = 'MIME-Version: 1.0' . "\r\n";
      $cabeceras .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
      $cabeceras .= 'From: Gene Overload <no-reply@geneoverload.com> ' . "\r\n" .
                      'Reply-To: no-reply@geneoverload.com' . "\r\n" .
                      'X-Mailer: PHP/5.x'; // . phpversion();
//      mail($email, $subject, $body, $cabeceras);
$mail->enviar_mail($email, $subject, $body, $cabeceras);
    }
    if ($lang == 'en')
    {
      echo ("<p class=\"correcto\">Email sent. Soon you will receive an email from us.</p>");
    } else {
      echo ("<p class=\"correcto\">Email enviado. Pronto recibir&aacute;s correo de nosotros</p>");
    }
    $accion = null;


  } else {
    if ($lang == 'en')
    {
      echo ("<p class=\"error\">There are no users registered with this email</p>");
    } else {
      echo ("<p class=\"error\">No hay ning&uacute;n usuario registrado con este email</p>");
    }
    $accion = null;
  }

}


// *********************************
//    Formulario para recordar clave
// *********************************


if ($accion == null)
{

  if ($lang == 'en')
  {
    ?>
    <br/>
    <p style="font-size: 16px; font-weight: bold; color: #c58768;">Recover password</p>
    <br/>
    <p style="font-size: 13px; font-weight: bold; color: #c58768;">If you have forgotten your password,
		please fill your email so we can reset it and you can choose a new one.
    </p>
    <br/>
    <br/>
    <br/>
    <form method="post" action="index.php">
    <input type="hidden" name="accion" value="enviar_reset">
    <input type="hidden" name="lang" value="<?php echo $lang; ?>">
    <input type="hidden" name="catid" value="<?php echo $catid; ?>">
    <br/>
    <p style="font-size: 14px; font-weight: bold; color: #c58768;">
      Email : <input type="text" name="email" size="40">
    </p>
    <br/>
    <br/>
    <p>
      <input type="submit" value="Send recovery email">
    </p>
    </form>
    <br/>
    <br/>
    <br/>
    <br/>
    <p style="font-size: 13px; font-weight: bold; color: #c58768;">
	* Please keep in mind we cannot recover your old password since we cannot decrypt it as a security measure. We can only
       give you the opportunity to reset it and create another one.
    </p>
    <br/>
    <br/>

    <?php
  } else {
    ?>
    <br/>
    <br/>
    <p style="font-size: 16px; font-weight: bold; color: #c58768;">Recuperar clave</p>
    <br/>
    <p style="font-size: 13px; font-weight: bold; color: #c58768;">Si has olvidado tu clave,
		por favor rellena tu email para poder resetearla y escribir una nueva
    </p>
    <br/>
    <br/>
    <br/>

    <form method="post" action="index.php">
    <input type="hidden" name="accion" value="enviar_reset">
    <input type="hidden" name="lang" value="<?php echo $lang; ?>">
    <input type="hidden" name="catid" value="<?php echo $catid; ?>">
    <br/>
    <p style="font-size: 14px; font-weight: bold; color: #c58768;">
      Email : <input type="text" name="email" size="40">
    </p>
    <br/>
    <br/>
    <p>
      <input type="submit" value="Enviar email para recuperar clave">
    </p>
    </form>
    <br/>
    <br/>
    <br/>
    <br/>
    <p style="font-size: 13px; font-weight: bold; color: #c58768;">
	* Por favor, ten en cuenta que no podemos recuperar tu antigua clave, dado que no podemos descifrarla. Esto
	es una medida de seguridad para mantener tu clave secreta. Tan s&oacute;lo te podemos proporcionar
	la oportunidad de borrarlo y crear uno nuevo.
    </p>
    <br/>
    <br/>


    <?php
  }

}


?>
