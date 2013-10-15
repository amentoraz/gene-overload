<?php


  include ("config/database.php");


// El proceso es:
//
//  1. PayPal te envia un mensaje que te notifica de lo qeu ha pasado
//
//  2. Le envias el mismo mensaje sin alterar a PayPal
//
//  3. Te dice VERIFIED o INVALID
//
//  4. Profit??? Creo que tienes que responder
//
//
//  - Espera 30 segundos a tu respuesta
//  - Ojo, no se puede dar de alta la transaccion hasta haber recibido la confirmacion
//    -> para evitar duplicados tienen un ID de transaccion
//
//


  error_reporting(E_ALL ^ E_NOTICE);
  $email = $_GET['ipn_email'];
  $header = "";
  $emailtext = "";

  $email = 'amentoraz@gmail.com';


  // Read the post from PayPal and add 'cmd'
  $req = 'cmd=_notify-validate';
  if(function_exists('get_magic_quotes_gpc'))
  {
    $get_magic_quotes_exists = true;
  }

  // Handle escape characters, which depends on setting of magic quotes
  foreach ($_POST as $key => $value)
  {
    if($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1){
      $value = urlencode(stripslashes($value));
    } else {
      $value = urlencode($value);
    }
    $req .= "&$key=$value";
  }

  // Primer email para comprobar
 mail($email, "IPN primer request, fp", $fp . "\n\n" . $req);



  // Post back to PayPal to validate

  $header .= "POST /cgi-bin/webscr HTTP/1.0\r\n";
  $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
  $header .= "Content-Length: " . strlen($req) . "\r\n\r\n";


  // ESTO DEBE CAMBIAR PARA EL REAL

  $fp = fsockopen ('ssl://www.paypal.com', 443, $errno, $errstr, 30);
//  $fp = fsockopen ('ssl://www.sandbox.paypal.com', 443, $errno, $errstr, 30);


  // Process validation from PayPal
  // TODO: This sample does not test the HTTP response code. All
  // HTTP response codes must be handles or you should use an HTTP
  // library, such as cUrl

  if (!$fp) { // HTTP ERROR

  } else {

    // NO HTTP ERROR
    fputs ($fp, $header . $req);
    while (!feof($fp)) {
      $res = fgets ($fp, 2048);

      if (strcmp ($res, "VERIFIED") == 0)
      {

        // *********** VERIFIED **********

	$todo_correcto = 1;


	// Comprobamos si el payment_status esta completed
        $payment_status = $_REQUEST['payment_status'];
        if ($payment_status != "Completed")
	{
	  $todo_correcto = 0;
	}

        // Ahora, que no hayamos procesado ya esta transaccion... como?
        // Check that txn_id has not been previously processed
	//  P.ej, txn_id = 37951025.
	//  Hay que meterle una tabla para que no procese los repes
        // txn_id, LO GENERAN ELLOS O LO GENERO YO????
        $txn_id = $_REQUEST['txn_id'];
        $string = "SELECT id
		FROM registro_ipn
		WHERE txn_id = '$txn_id'
		";
        $query = mysql_query($string, $link_r);
        $existe = mysql_num_rows($query);
        if ($existe > 0)
        {
          $todo_correcto = 0;
        } else {
          $idjugador = $_REQUEST['custom'];
          $string2 = "INSERT INTO registro_ipn
		(txn_id, idjugador, fecha)
		VALUES
		('$txn_id', $idjugador, NOW())
		";
          $query2 = mysql_query($string2, $link_w);
        }


        // Check that receiver_email is your Primary PayPal email
        $receiver_email = $_REQUEST['receiver_email'];
        if (($receiver_email != 'amentoraz@gmail.com')
            &&
	    ($receiver_email != 'heruh4_1314964152_biz@gmail.com')
            )
	{
	  $todo_correcto = 0;
	}

        // Check that payment_amount/payment_currency are correct
        // payment_currency son euros, payment_amount deben ser 3, 8 o 15
        $mc_currency = $_REQUEST['mc_currency'];
        if ($mc_currency != EUR)
        {
	  $todo_correcto = 0;
        }
        // mc_gross es el precio grosso, el mc_fee es lo que se queda paypal
        $mc_gross = $_REQUEST['mc_gross'];
        $mc_fee = $_REQUEST['mc_fee'];
        // Vamos a sacar tambien los meses de suscripcion que le metemos automatizados
        if ($mc_gross == 3)
        {
          $nmeses = 1;
        }
        if ($mc_gross == 8)
        {
          $nmeses = 3;
        }
        if ($mc_gross == 15)
        {
          $nmeses = 6;
        }
        if (($mc_gross != 3) && ($mc_gross != 8) && ($mc_gross != 15))
        {
          $nmeses = 0;
	  $todo_correcto = 0;
        }


        // Process payment
        // Ahora, si $todo_correcto == 1, vamos a meterle el premium que tenemos que meterle.
        // En la clase "jugador" hay que anyadirle N meses, y meterle que premium sea = 1
        if ($todo_correcto == 1)
        {
          // Sacamos la fecha en la que acababa su premium (caso de tenerlo)
          $idjugador = $_REQUEST['custom'];
          $string = "SELECT fecha_fin_premium, DATEDIFF(fecha_fin_premium, NOW()) AS diferencia
		FROM jugador
		WHERE id = $idjugador
		";
          $query = mysql_query($string, $link_r);
	  $unquery = mysql_fetch_array($query);
          $fecha_fin_premium = $unquery['fecha_fin_premium'];
          $diferencia = $unquery['diferencia'];

          //  Ahora tengo que comprobar si esa fecha es >= que la actual
          //  No puedo sumarla a secas, porque si se le hubiera acabado hace 3 dias, los X meses
          // deben contar desde hoy.
          if ($diferencia > 0)
          {
            //  Asi, si diferencia > 0, los sumo
            $string2 = "UPDATE jugador
		SET fecha_fin_premium = DATE_ADD('$fecha_fin_premium', INTERVAL $nmeses MONTH),
		es_premium = 1
		WHERE id = $idjugador
		";
          } else {
            //  Si no da este resultado, es hoy + $nmeses
            $string2 = "UPDATE jugador
		SET fecha_fin_premium = DATE_ADD(NOW(), INTERVAL $nmeses MONTH),
		es_premium = 1
		WHERE id = $idjugador
		";
          }
          $query2 = mysql_query($string2, $link_w);
        }





        // If 'VERIFIED', send an email of IPN variables and values to the
        // specified email address

        foreach ($_POST as $key => $value){
          $emailtext .= $key . " = " .$value ."\n\n";
        }

        if ($todo_correcto == 1)
        {
          mail($email, "Paypal VERIFIED CORRECTO: ".$res." IPN", $emailtext . "\n\n" . $req . "\n\n" . $string2 . "\n\n" . $diferencia );
        } else {
          mail($email, "Paypal VERIFIED ERRONEO: ".$res." IPN", $emailtext . "\n\n" . $req . "\n\n" . $string2 . "\n\n" . $diferencia );
        }




      } else if (strcmp ($res, "INVALID") == 0) {
	// If 'INVALID', send an email. TODO: Log for manual investigation.
        foreach ($_POST as $key => $value)
        {
          $emailtext .= $key . " = " .$value ."\n\n";
        }
        mail($email, "Paypal: ".$res." IPN", $emailtext . "\n\n" . $req);
      }
    }

  }
  fclose ($fp);






?>
