<?php

  function AjustarFecha($anyo, $mes, $dia, $hora, $min)
  {
//echo $dia."#";
        if ($min >= 60) { $hora++; $min = $min - 60; }
        if ($min < 0) { $hora--; $min = $min + 60; }
        if ($hora >= 24) { $dia++; $hora = $hora - 24;}
        if ($hora < 0) { $dia--; $hora = $hora + 24;}
        // Ahora dia con el mes
        if ($dia < 0) {
          $mes--;
          if ($mes < 0) { $mes = 12; $anyo--; }
          switch ($mes)
          {
                case 1: $dia = 31; break;
                case 2: if (($anyo % 4) == 0) { $dia = 28; } else { $dia = 29; } break;
                case 3: $dia = 31; break;
                case 4: $dia = 30; break;
                case 5: $dia = 31; break;
                case 6: $dia = 30; break;
                case 7: $dia = 31; break;
                case 8: $dia = 31; break;
                case 9: $dia = 30; break;
                case 10: $dia = 31; break;
                case 11: $dia = 30; break;
                case 12: $dia = 31; break;
          }
        }
        // Y comprobamos si nos hemos pasado de dia del mes
        switch ($mes)
        {
                case 1: if ($dia > 31) { $dia = 1; $mes++; } ; break;
                case 2: if (  ((($anyo % 4) == 0) && ($dia > 28)) || ((($anyo % 4) != 0) && ($dia > 29)) ) { $dia = 1; $mes++; } break;
                case 3: if ($dia > 31) { $dia = 1; $mes++; } ; break;
                case 4: if ($dia > 30) { $dia = 1; $mes++; } ; break;
                case 5: if ($dia > 31) { $dia = 1; $mes++; } ; break;
                case 6: if ($dia > 30) { $dia = 1; $mes++; } ; break;
                case 7: if ($dia > 31) { $dia = 1; $mes++; } ; break;
                case 8: if ($dia > 31) { $dia = 1; $mes++; } ; break;
                case 9: if ($dia > 30) { $dia = 1; $mes++; } ; break;
                case 10: if ($dia > 31) { $dia = 1; $mes++; } ; break;
                case 11: if ($dia > 30) { $dia = 1; $mes++; } ; break;
                case 12: if ($dia > 31) { $dia = 1; $mes = 1; $anyo++; } ; break;
         }
    $arrayf['anyo'] = $anyo;
    $arrayf['mes'] = $mes;
    if ($dia < 10) { $dia = '0'.$dia; }
    $arrayf['dia'] = $dia;
    if ($hora < 10) { $hora = '0'.$hora; }
    $arrayf['hora'] = $hora;
    if ($min < 10) { $min = '0'.$min; }
    $arrayf['min'] = $min;
//print_r($arrayf);
    return $arrayf;
  }





function realip(){
        if ($_SERVER) {
                if ( $_SERVER[HTTP_X_FORWARDED_FOR] ) {
                        $realip = $_SERVER["HTTP_X_FORWARDED_FOR"];
                        } elseif ( $_SERVER["HTTP_CLIENT_IP"] ) {
                                $realip = $_SERVER["HTTP_CLIENT_IP"];
                        } else {
                                $realip = $_SERVER["REMOTE_ADDR"];
                        }
                } else {
                        if ( getenv( 'HTTP_X_FORWARDED_FOR' ) ) {
                                $realip = getenv( 'HTTP_X_FORWARDED_FOR' );
                                } elseif ( getenv( 'HTTP_CLIENT_IP' ) ) {
                                        $realip = getenv( 'HTTP_CLIENT_IP' );
                        } else {
                                $realip = getenv( 'REMOTE_ADDR' );
                }
        }
        return $realip;
}

//if (realip() != '80.88.225.141'){
if ((realip() == '127.0.0.1')
                        || (realip() == '87.216.167.252')
                        || (realip() == '83.52.236.143') 
        || (realip() == '89.131.177.181')        
        || (realip() == '66.147.242.154')
        || (realip() == '83.32.138.219')         
        || (realip() == '192.168.0.23') )
{

  include ("../config/database.php");
  include ("../config/values.php");
//  include ("../functions.php");

  session_start();


  include ("../clases/obj_especimen_torneo.php");
  include ("../clases/obj_especimen.php");
  include ("../clases/obj_jugador.php");
  include ("../clases/obj_jugador_campana.php");
  include ("../clases/obj_combate.php");
  include ("../clases/obj_arbol.php");
  include ("../clases/obj_torneo.php");
  include ("../clases/obj_campana.php");
  include ("../clases/obj_informe.php");
  include ("../clases/obj_log.php");
  include ("../clases/obj_mail.php");
  include ("../clases/obj_tmz.php");

  echo ("Verificaci&oacute;n de IP correcta...");



  $accion = $_REQUEST['accion'];


  if ($accion == 'enviar_email')
  {

    $mail = new Mail();
    $jugador = new Jugador();

    $dias = 3;
    $array = $jugador->BuscarPremiumFin($link_r, $dias);
//print_r($array);
//echo ("<br/>");
//echo ("<br/>");
    for ($i = 1; $i <= count($array); $i++)
    {

      $lang = $jugador->SacarLang($link_r, $array[$i]['id']);
      $jugador->SacarDatos($link_r, $array[$i]['id']);

      $id_tmz = $jugador->id_tmz;
      $tmz = new TMZ();
      $tmz->SacarDatos($link_r, $id_tmz);
      $hora_servidor = -6;
      $min_servidor = 0;
      $diferencia_hora = $tmz->tmz_hour - $hora_servidor;
      $diferencia_min = $tmz->tmz_min - $min_servidor;
      $anyo = substr($jugador->fecha_fin_premium, 0, 4);
      $mes = substr($jugador->fecha_fin_premium, 5, 2);
      $dia = substr($jugador->fecha_fin_premium, 8, 2);
      $hora = substr($jugador->fecha_fin_premium, 11, 2);
      $min = substr($jugador->fecha_fin_premium, 14, 2);
      // Ajustamos la fecha con el TMZ
echo $hora."#";
      $hora = $hora + $diferencia_hora;
echo $hora."|";
echo $min."#";
      $min = $min + $diferencia_min;
echo $min."|";


      $body = "<html><body>";
      $body = $body."<br/><center>";
      $body = $body."<img src=\"http://www.geneoverload.com/img/logomail.png\">";
      $body = $body."</center><br/>";
      if ($lang == 'en')
      {

        $arrayf = AjustarFecha($anyo, $mes, $dia, $hora, $min);
echo ("<br/>xx");
print_r($arrayf);
echo ("<br/>");
        $anyo = $arrayf['anyo']; $mes = $arrayf['mes']; $dia = $arrayf['dia']; $hora = $arrayf['hora']; $min = $arrayf['min'];
        if ($dia == '01') { $textofecha = ("1st "); } else {
          if ($dia == '02') { $textofecha = ("2nd "); } else {
            if ($dia == '02') { $textofecha = ("3rd "); } else {
              $textofecha = (abs($dia)."th ");
            }
          }
        }
        $textofecha = $textofecha." of ".$array_months[(abs($mes))].", ".$anyo.", at ".$hora.":".$min;

        $subject = "Gene Overload: Your premium account is about to expire";
        $body = $body."<p style=\"font-size: 14px; \">Your premium account will expire in 3 days!</p>";
        $body = $body."<p style=\"font-size: 12px; \">This email is to remind you that your premium account will expire on the ".$textofecha."</p>";
        $body = $body."<p style=\"font-size: 12px; \">You can easily renew your subscription by going to the Premium section and using Paypal.</p>";
        $body = $body."<p style=\"font-size: 12px; \">Whatever you decide to do, Gene Overload sincerely thanks you for your interest and cooperation with this game.</p>";
      } else {

        $arrayf = AjustarFecha($anyo, $mes, $dia, $hora, $min);
echo ("<br/>xx");
print_r($arrayf);
echo ("<br/>");
        $anyo = $arrayf['anyo']; $mes = $arrayf['mes']; $dia = $arrayf['dia']; $hora = $arrayf['hora']; $min = $arrayf['min'];
        $textofecha = (abs($dia)." de ".$array_meses[(abs($mes))]." de ".$anyo.", a las ".$hora.":".$min);


        $subject = "Gene Overload: Tu cuenta premium esta cerca de expirar";
        $body = $body."<p style=\"font-size: 14px; \">Tu cuenta premium dejar&aacute; de serlo en 3 d&iacute;as!</p>";
        $body = $body."<p style=\"font-size: 12px; \">Este email es para recordarte que tu cuenta premium se terminar&aacute; en ".$textofecha."</p>";
        $body = $body."<p style=\"font-size: 12px; \">Puedes renovar f&aacute;cilmente tu suscripci&oacute;n yendo a la secci&oacute;n de Premium y utilizando Paypal.</p>";
        $body = $body."<p style=\"font-size: 12px; \">Sea lo que sea que decidas hacer, Gene Overload te agradece sinceramente tu inter&eacute;s y cooperaci&oacute;n con este juego.</p>";
      }
      $body = $body."</body></html>";
      $cabeceras  = 'MIME-Version: 1.0' . "\r\n";
      $cabeceras .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
      $cabeceras .= 'From: Gene Overload <no-reply@geneoverload.com> ' . "\r\n" .
                      'Reply-To: no-reply@geneoverload.com' . "\r\n" .
                      'X-Mailer: PHP/5.x'; // . phpversion();

      $email = $jugador->email;
      echo ("#enviando mail a: ".$email."<br/>");
      $mail->enviar_mail($email, $subject, $body, $cabeceras);


      echo $array[$i]['id']." -> ".$email." - ";
    }

  }




}

?>
