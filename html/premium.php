<?php

  include ('clases/obj_texto_web.php');
//  include ('clases/obj_sanitizar.php');


  $accion = $secure->Sanitizar($_REQUEST['accion']);





  // ***********************************
  //   Aqui viene el cliente despues de pagar
  // ***********************************

  if ($accion == 'completado')
  {

    ?>
      <br/>
      <br/>
      <br/>
      <p class="correctosutil">Has realizado el pago correctamente</p>
      <br/>
      <br/>
      <br/>
    <?php

      $jugador = new Jugador();
      $jugador->SacarDatos($link_r, $idjugador);

      if ($jugador->es_premium == 1)
      {
        if ($jugador->diferencia > 0)
	{
          echo ("<p class=\"correctosutil\">");
          if ($jugador->lang == 'en')
	  {
	    echo ("Your account type is now <b>premium</b>. It will expire at ".$jugador->fecha_fin_premium." and become a free account again.");
	  } else {
	    echo ("Tu cuenta es ahora tipo <b>premium</b>. Seguir&aacute; si&eacute;ndolo hasta ".$jugador->fecha_fin_premium.", cuando se convertir&aacute; en una cuenta gratu&iacute;ta de nuevo.");
	  }
          echo ("</p>");
	} else {
          echo ("<p class=\"errorsutil\">");
          if ($jugador->lang == 'en')
	  {
	    echo ("Your account type is <b>free account</b>, though it was premium once.");
	  } else {
	    echo ("Tu tipo de cuenta es <b>cuenta gratu&iacute;ta</b>, aunque una vez fue premium.");
	  }
          echo ("</p>");
        }
      } else {
          echo ("<p class=\"errorsutil\">");
          if ($jugador->lang == 'en')
	  {
	    echo ("Your account type is <b>free account</b>.");
	  } else {
	    echo ("Tu tipo de cuenta es <b>cuenta gratu&iacute;ta</b>");
	  }
          echo ("</p>");
      }
      echo ("<br/>");
      echo ("<br/>");


  }







  // ***********************************
  //   Especifico features premium
  // ***********************************

  if ($accion == 'features')
  {

      $texto_web = new Texto_Web();
      $texto_web->Sacar_Datos_Cat($link_r, $catid);

      if ($lang == 'en')
      {
        echo $texto_web->texto_en;
      } else {
        echo $texto_web->texto;
      }

  }


  // ********************************************************
  //      Compra de tipo TEST
  // ********************************************************

  if ($accion == 'test')
  {

  ?>


<br/>
<br/>
TEST


<?php
  if ($jugador->diferencia < 180)
  {
?>

<br/>
<br/>
<br/>1 MONTH
<br/>

<form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="return" value="http://www.geneoverload.com/index.php?catid=52&accion=completado">
<input type="hidden" name="custom" value="<?php echo $idjugador; ?>">
<input type="hidden" name="notify_url" value="http://www.geneoverload.com/ipn.php">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="EQ7TLGJADQBBS">
<input type="image" src="https://www.sandbox.paypal.com/en_US/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.sandbox.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>

<?php
  }

  if ($jugador->diferencia < 90)
  {
?>


<br/>
<br/>
<br/>3 MONTHS
<br/>

<form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="return" value="http://www.geneoverload.com/index.php?catid=52&accion=completado">
<input type="hidden" name="custom" value="<?php echo $idjugador; ?>">
<input type="hidden" name="notify_url" value="http://www.geneoverload.com/ipn.php">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="TTNBLPHVQEWQW">
<input type="image" src="https://www.sandbox.paypal.com/en_US/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.sandbox.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>


<?php
  }

  if ($jugador->diferencia < 30)
  {
?>

<br/>
<br/>
<br/>6 MONTHS
<br/>

<form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="return" value="http://www.geneoverload.com/index.php?catid=52&accion=completado">
<input type="hidden" name="custom" value="<?php echo $idjugador; ?>">
<input type="hidden" name="notify_url" value="http://www.geneoverload.com/ipn.php">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="PWX99J57LY34A">
<input type="image" src="https://www.sandbox.paypal.com/en_US/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.sandbox.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>


<?php
  }
?>


<br/>
<br/>
NO-TEST
<br/>
<br/>

<?php

  }

  // ***********************************
  //          Pagina principal
  // ***********************************


  if ($accion == null)
  {

    if (($idjugador == null) || ($idjugador == 0))
    {

      $texto_web = new Texto_Web();
      $texto_web->Sacar_Datos_Cat($link_r, $catid);

      if ($lang == 'en')
      {
        echo $texto_web->texto_en;
      } else {
        echo $texto_web->texto;
      }

    } else {

      // Esta es la zona para contratar premium, que te aparece cuando eres usuario

      $jugador = new Jugador();
      $jugador->SacarDatos($link_r, $idjugador);

//echo $jugador->diferencia."###";
      if ($jugador->es_premium == 1)
      {
        if ($jugador->diferencia > 0)
	{
          $fecha_fin_premium = $jugador->fecha_fin_premium;

        // PREPARAMOS LOS AJUSTES DE HORA
//                $jugador_aux = new Jugador($link_r);
//                $jugador_aux->SacarDatos($link_r, $idjugador);
                $id_tmz = $jugador->id_tmz;
                $tmz = new TMZ();
                $tmz->SacarDatos($link_r, $id_tmz);
                $hora_servidor = -6;
                $min_servidor = 0;
                $diferencia_hora = $tmz->tmz_hour - $hora_servidor;
                $diferencia_min = $tmz->tmz_min - $min_servidor;
        // PREPARAMOS LOS AJUSTES DE HORA (FIN)

                $anyo = substr($fecha_fin_premium, 0, 4);
                $mes = substr($fecha_fin_premium, 5, 2);
                $dia = substr($fecha_fin_premium, 8, 2);
                $hora = substr($fecha_fin_premium, 11, 2);
                $min = substr($fecha_fin_premium, 14, 2);
                // Ajustamos la fecha con el TMZ
                $hora = $hora + $diferencia_hora;
                $min = $min + $diferencia_min;
                $arrayf = AjustarFecha($anyo, $mes, $dia, $hora, $min);
                $anyo = $arrayf['anyo']; $mes = $arrayf['mes']; $dia = $arrayf['dia']; $hora = $arrayf['hora']; $min = $arrayf['min'];
		$fecha_fin_premium = $anyo."-".$mes."-".$dia." ".$hora.":".$min;



          echo ("<p class=\"correctosutil\">");
          if ($jugador->lang == 'en')
	  {
	    echo ("Your account type is <b>premium</b>. It will expire at ".$fecha_fin_premium." and become a free account again.");
	  } else {
	    echo ("Tu cuenta es tipo <b>premium</b>. Seguir&aacute; si&eacute;ndolo hasta ".$fecha_fin_premium.", cuando se convertir&aacute; en una cuenta gratu&iacute;ta de nuevo.");
	  }
          echo ("</p>");
	} else {
          echo ("<p class=\"errorsutil\">");
          if ($jugador->lang == 'en')
	  {
	    echo ("Your account type is <b>free account</b>, though it was premium once.");
	  } else {
	    echo ("Tu tipo de cuenta es <b>cuenta gratu&iacute;ta</b>, aunque una vez fue premium.");
	  }
          echo ("</p>");
        }
      } else {
          echo ("<p class=\"errorsutil\">");
          if ($jugador->lang == 'en')
	  {
	    echo ("Your account type is <b>free account</b>.");
	  } else {
	    echo ("Tu tipo de cuenta es <b>cuenta gratu&iacute;ta</b>");
	  }
          echo ("</p>");
      }
      echo ("<br/>");
      echo ("<br/>");


      if ($lang == 'en')
      {
        ?>
        <p style="font-size: 15px;"><b>Premium</b></p>
	  <br/>
  	  <br/>
	  <p style="font-size: 13px; color: #ffff00;"><b>Low cost premium accounts for a deeper game experience</b></p>
	  <br/>
          <p>Gene Overload features premium accounts at a very low cost. Our idea has been to make premium options as "extras" which will be worth if you are really interested in the game, yet not anything that will unbalance the game towards premium users.</p>
	  <br/>
	  <p>We believe the existence of two gaming modes (free & premium) should not hinder the game experience from free players, yet we also want to provide with interesting features for premium players.</p>
	  <br/>
          <p>If you are interested in a premium account, you can check the features it brings in <a href="index.php?catid=<?php echo $catid;
		?>&idcampana=<?php echo $idcampana; ?>&accion=features">this link</a>.
          <br/>
          <br/>
          <br/>
	  <?php
          if ($jugador->diferencia <= 0)
   	  {
          ?>
  	    <p style="font-size: 14px; color: #ffff00;"><b>Get your premium account now!</b></p>
          <?php
          }
          ?>
          <br/>

        <?php
      } else {
        ?>
        <p style="font-size: 15px;"><b>Premium</b></p>
	  <br/>
  	  <br/>
	  <p style="font-size: 13px; color: #ffff00;"><b>Cuentas premium a bajo coste para una experiencia de juego m&aacute;s profunda</b></p>
	  <br/>
          <p>En Gene Overload existen cuentas premium a un coste muy bajo. Nuestra idea es que las opciones de premium sean unos "extras" que merezcan la pena si realmente est&aacute;s interesado en el juego, pero nada que pueda desequilibrar el juego hacia los jugadores premium.</p>
	  <br/>
	  <p>Creemos que la existencia de dos formas de jugar (gratis y premium) no deber&iacute;an entorpecer la experiencia de juego de los jugadores gratis, pero aun as&iacute; queremos dar cosas interesantes a los jugadores premium.</p>
	  <br/>
          <p>Si est&aacute;s interesado en una cuenta premium, puedes comprobar las caracter&iacute;sticas en <a href="index.php?catid=<?php echo $catid;
		?>&idcampana=<?php echo $idcampana; ?>&accion=features">este enlace</a>.
          <br/>
          <br/>
          <br/>
	  <?php
          if ($jugador->diferencia <= 0)
   	  {
          ?>
  	    <p style="font-size: 14px; color: #ffff00;"><b>&iexcl;Consigue tu cuenta premium!</b></p>
          <?php
          }
          ?>
          <br/>

        <?php
      }

      ?>

      <br/>
      <br/>

<table>
<tr><td><input type="hidden" name="on0" value="Cuenta Premium"><span style="color: #ffff00;">
<?php



  // NO DEJAMOS CONTRATAR SI TIENE MAS DE 6 MESES PENDIENTE
  if ($jugador->diferencia < 180)
  {



  if ($lang == 'en')
  {
  ?>
    <b>Choose how many months you want to become Premium</b>
    <br/>
    <br/>
    <i>Please read the <a href="http://www.geneoverload.com/terms_en.htm" target="_blank">Terms and Conditions</a> you already accepted when signing up for Gene Overload
regarding your subscription and Premium account, specifically "7.- Usage fees".</i>
  <?php
  } else {
  ?>
    <b>Elige la cantidad de tiempo para el que quieres adquirir tu Cuenta Premium</b>
    <br/>
    <br/>
    <i>Por favor, lee las <a href="http://www.geneoverload.com/terms_es.htm" target="_blank">Condiciones Generales de Uso</a> que ya aceptaste al dar de alta
tu cuenta en Gene Overload acerca de tu suscripci&oacute;n y cuentas Premium, en particular el punto "7.- Retribuciones por uso y modo Premium"</i>
  <?php
  }
  ?>

  </span>
  <br/>
  <br/>
  <br/>
  <?php

  if ($jugador->diferencia < 180)
  {

    if ($lang == 'en')
    {
      echo ("<p><span style=\"color: #00bb00;\"><b>Option 1: </b> 1 month subscription (3 &euro;)</span></p>");
    } else {
      echo ("<p><span style=\"color: #00bb00;\"><b>Opci&oacute;n 1: </b> Suscripci&oacute;n de un mes (3 &euro;)</span></p>");
    }

?>


  <br/>
    <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
     <input type="hidden" name="return" value="http://www.geneoverload.com/index.php?catid=52&accion=completado">
     <input type="hidden" name="custom" value="<?php echo $idjugador; ?>">
     <input type="hidden" name="notify_url" value="http://www.geneoverload.com/ipn.php">
     <input type="hidden" name="cmd" value="_s-xclick">
     <input type="hidden" name="hosted_button_id" value="2RDA35NNVHRJU">
     <?php
     if ($lang == 'en')
     {
     ?>
       <input type="image" src="https://www.sandbox.paypal.com/en_US/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal. The fast and secure way to pay in the Internet.">
     <?php
     } else {
     ?>
       <input type="image" src="https://www.paypalobjects.com/es_ES/ES/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal. La forma rápida y segura de pagar en Internet.">
     <?php
     }
     ?>
     <img alt="" border="0" src="https://www.paypalobjects.com/es_ES/i/scr/pixel.gif" width="1" height="1">
    </form>
  <br/>
  <br/>
  <br/>
  <br/>

<?php
  }

  if ($jugador->diferencia < 90)
  {

    if ($lang == 'en')
    {
      echo ("<p><span style=\"color: #00bb00;\"><b>Option 2: </b> 3 months subscription (8 &euro;)</span></p>");
    } else {
      echo ("<p><span style=\"color: #00bb00;\"><b>Opci&oacute;n 2: </b> Suscripci&oacute;n de 3 meses (8 &euro;)</span></p>");
    }

?>
  <br/>
    <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
     <input type="hidden" name="return" value="http://www.geneoverload.com/index.php?catid=52&accion=completado">
     <input type="hidden" name="custom" value="<?php echo $idjugador; ?>">
     <input type="hidden" name="notify_url" value="http://www.geneoverload.com/ipn.php">
     <input type="hidden" name="cmd" value="_s-xclick">
     <input type="hidden" name="hosted_button_id" value="USGRLELPEL4PA">
     <?php
     if ($lang == 'en')
     {
     ?>
       <input type="image" src="https://www.sandbox.paypal.com/en_US/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal. The fast and secure way to pay in the Internet.">
     <?php
     } else {
     ?>
       <input type="image" src="https://www.paypalobjects.com/es_ES/ES/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal. La forma rápida y segura de pagar en Internet.">
     <?php
     }
//     <input type="image" src="https://www.paypalobjects.com/es_ES/ES/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal. La forma rápida y segura de pagar en Internet.">
     ?>
     <img alt="" border="0" src="https://www.paypalobjects.com/es_ES/i/scr/pixel.gif" width="1" height="1">
    </form>
  <br/>
  <br/>
  <br/>

<?php
  }

  if ($jugador->diferencia < 30)
  {

    if ($lang == 'en')
    {
      echo ("<p><span style=\"color: #00bb00;\"><b>Option 3: </b> 6 months subscription (15 &euro;)</span></p>");
    } else {
      echo ("<p><span style=\"color: #00bb00;\"><b>Opci&oacute;n 3: </b> Suscripci&oacute;n de 6 meses (15 &euro;)</span></p>");
    }

?>
  <br/>
    <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
     <input type="hidden" name="return" value="http://www.geneoverload.com/index.php?catid=52&accion=completado">
     <input type="hidden" name="custom" value="<?php echo $idjugador; ?>">
     <input type="hidden" name="notify_url" value="http://www.geneoverload.com/ipn.php">
     <input type="hidden" name="cmd" value="_s-xclick">
     <input type="hidden" name="hosted_button_id" value="E2JBQQH28YYW2">
     <?php
     if ($lang == 'en')
     {
     ?>
       <input type="image" src="https://www.sandbox.paypal.com/en_US/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal. The fast and secure way to pay in the Internet.">
     <?php
     } else {
     ?>
       <input type="image" src="https://www.paypalobjects.com/es_ES/ES/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal. La forma rápida y segura de pagar en Internet.">
     <?php
     }
//     <input type="image" src="https://www.paypalobjects.com/es_ES/ES/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal. La forma rápida y segura de pagar en Internet.">
     ?>
     <img alt="" border="0" src="https://www.paypalobjects.com/es_ES/i/scr/pixel.gif" width="1" height="1">
    </form>



<?php
  }




 } // fin del limite de contratacion

  ?>


<br/>
<br/>

</table>


<?php



    }

  }



?>
