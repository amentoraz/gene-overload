<?php

class Mail
{

  var $from;
  var $dirsmtp;
  var $puerto;
  var $dominio;


        // ######################################################
        //  Envio de correo HTML
        // ######################################################

        function realizar_envio($to, $subject, $body, $auth_smtp_user, $auth_smtp_pwd, $hayauth) {

        $error = 0;
        $timeout = 10;          

        $conectar = fsockopen($this->dirsmtp, $this->puerto, $errno, $errstr, $timeout) or die;
        //Sacamos el contenido de lo que nos devuelve el servidor antes de seguir
        $str_recibe = fgets($conectar,256);
        $str_recibe = fgets($conectar,256);
//echo $str_recibe."<br/>";
        // Ahora el HELO
//        $str_helo = "HELO ".$this->dominio."\r\n";
        $str_helo = "EHLO ".$this->dominio."\r\n";
        fputs($conectar,$str_helo);
        $str_recibe = fgets($conectar,256);
        $str_recibe = fgets($conectar,256);
        $str_recibe = fgets($conectar,256);
        $str_recibe = fgets($conectar,256);
        $str_recibe = fgets($conectar,256);
        $str_recibe = fgets($conectar,256);
        $str_recibe = fgets($conectar,256);
//echo $str_recibe." kthx<br/>";
        if ($str_recibe[0] == "5") { $error = 1; }

        if ($hayauth == 1)
        {
                  // base64_encode sobre el auth
          fputs($conectar,"auth plain AG5vLXJlcGx5QGdlbmVvdmVybG9hZC5jb20AY2h1dGFqdWFuZnJpeA==\r\n");

          $str_recibe = fgets($conectar,256);
//echo "# ".$str_recibe." []<br/>";
        }

        fputs($conectar,"MAIL FROM: ".$this->from."\r\n");
        $str_recibe = fgets($conectar,256);
//echo $str_recibe."<br/>";
        if ($str_recibe[0] == "5") { $error = 1; }
        fputs($conectar,"RCPT TO:".$to."\r\n");
        $str_recibe = fgets($conectar,256);
//echo $str_recibe."<br/>";
        if ($str_recibe[0] == "5") { $error = 1; }
        fputs($conectar,"DATA\r\n");
        fputs($conectar,"FROM: ".$this->from."\r\n");
        fputs($conectar,"To: ".$to."\r\n");
        fputs($conectar,"Subject: ".$subject." \r\n");
        fputs($conectar,"Content-Type: text/html; charset=\"ISO-8859-1\" \r\n\r\n");
        fputs($conectar,"".$body."\r\n");
        fputs($conectar,".\r\n");
        $str_recibe = fgets($conectar,512); if ($str_recibe[0] == "5") { $error = 1; }
//echo $str_recibe."<br/>";

                $str_recibe2 = fgets($conectar,512);
                if ($str_recibe[0] == "5") { $error = 1; }
                                fputs($conectar,"QUIT\t\n");
                $str_recibe2 = fgets($conectar,512);
                if ($str_recibe[0] == "5") { $error = 1; }
                                fclose($conectar);
                
                                return $error;
                
                }



  function enviar_mail($email, $subject, $body, $cabeceras)
  {

    $this->from = 'no-reply@geneoverload.com';
    $this->dirsmtp = 'mail.geneoverload.com';
    $this->puerto = 26;
    $this->dominio = 'geneoverload.com';

//        function realizar_envio($to, $subject, $body, $auth_smtp_user, $auth_smtp_pwd, $hayauth) {
    $this->realizar_envio($email, $subject, $body, '', '', 1);  // juanfrix ftw

  }



}

?>
