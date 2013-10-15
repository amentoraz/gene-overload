<?php

  $debug = $_REQUEST['debug'];
  if (!is_numeric($debug))
  {
    $debug = 0;
  }


// Necesitamos que este en una campanya para luego meterle la pasta
if (($idjugador != 0) && ($idcampana != ''))
{


  $accion_encuesta = $secure->Sanitizar($_REQUEST['accion_encuesta']);
  $encuesta = new Encuesta();

  //  Si no hay accion sobre la encuesta, vamos a ver si hay alguna
  // en el momento actual y que no haya respondido el usuario.

  $hayencuesta = $encuesta->ObtenerEncuesta($link_r);
  if ($hayencuesta == 0)
  {
    if ($accion_encuesta == null)
    {
      // Hay una encuesta. Ahora tendriamos que ver si ya la ha respondido,
      // ya que la accion == null. En caso de que haya respuesta, no pintamos nada
      $idencuesta = $encuesta->id;
      if ($encuesta->HaRespondido($link_r, $idencuesta, $idjugador) == 0)
      {
//echo ($idencuesta);
          ?>
	        <div class="espacio"></div>
	        <table width="100%" style="
                        color: #cdbd00;
                        padding: 2px;
                        border: 1px solid #000;
                        text-align: center;
                        ">
	         <tr>
	          <td>
	           <table width="100%"
	                style="  text-align: center;
                        background-color: #000;
                        padding: 7px; 
	                ">
	            <tr><td>

        <form method="post" action="index.php">
          <input type="hidden" name="catid" value="<?php echo $catid; ?>">
          <input type="hidden" name="accion_encuesta" value="respondida">
          <input type="hidden" name="idcampana" value="<?php echo $idcampana; ?>">
          <input type="hidden" name="idencuesta" value="<?php echo $idencuesta; ?>">
          <?php

        if ($lang == 'en')
        {
          echo "<center><b>Survey</b></center><br/>";
        echo ("<span style=\"font-size: 11px;\">");
          echo "<center><i>Win 5<img src=\"img/goldcoin.gif\"> for answering</i></center><br/>";
          echo $encuesta->pregunta_en."<br/><br/>";
        } else {
          echo "<center><b>Encuesta</b></center><br/>";
        echo ("<span style=\"font-size: 11px;\">");
          echo "<center><i>Gana 5<img src=\"img/goldcoin.gif\"> por responder</i></center><br/>";
          echo $encuesta->pregunta."<br/><br/>";
        }
        // Ahora vamos a sacar las respuestas y a pintarlas
        $array = $encuesta->ObtenerRespuestas($link_r, $idencuesta);
//print_r($array);
        for ($i = 1; $i <= count($array); $i++)
        {
		?>
		<?php
		echo ("<input type=\"radio\" name=\"respuesta\" value=\"".$array[$i]['id']."\"");
                if ($i == 1) { echo (" checked"); }
                if ($lang == 'en')
                {
                  echo (">".$array[$i]['respuesta_en']);
                } else {
                  echo (">".$array[$i]['respuesta']);
                }
		?>
		<br/>
		<?php
        }
	?>
	<br/>
	</span>
        <input type="submit" value="Entrar">
        </form>
                    </td></tr>
                    </table>
                   </td>
                  </tr>
                 </table>
	  <?php



      }
    }

    if ($accion_encuesta == "respondida")
    {
      // Primero vemos que sea la primera vez
      $idencuesta = $_REQUEST['idencuesta'];
      if (!is_numeric($idencuesta))
      {
        die;
      }
      if ($encuesta->HaRespondido($link_r, $idencuesta, $idjugador) == 0)
      {
        $cual = $_REQUEST['respuesta'];
        if (!is_numeric($cual))
        {
          die;
        }
        $encuesta->MarcarRespondido($link_w, $idencuesta, $idjugador, $cual);

        $jugador_tutorial_camp = new Jugador_Campana();
        $jugador_tutorial_camp->SumarDinero($link_w, $idjugador, $idcampana, 5);
        $dinerito = $dinerito + 5;
        ?>

	        <div class="espacio"></div>
	        <table width="100%" style="
                        color: #cdbd00;
                        padding: 2px;
                        border: 1px solid #000;
                        text-align: center;
                        ">
	         <tr>
	          <td>
	           <table width="100%"
	                style="  text-align: center;
                        background-color: #000;
                        padding: 7px; 
	                ">
	            <tr><td>

		    <?php
                    if ($lang == 'en')
                    {
                      echo "<center><b>Survey</b></center><br/>";
                      echo ("<span style=\"font-size: 11px;\">Thank you for answering this survey! You've won 5<img src=\"img/goldcoin.gif\"></span>");
                    } else {
                      echo "<center><b>Encuesta</b></center><br/>";
                      echo ("<span style=\"font-size: 11px;\">&iexcl;Gracias por responder a esta encuesta! Has ganado 5<img src=\"img/goldcoin.gif\"></span>");
                    }
                    ?>
			<br/>
                    </td></tr>
                    </table>
                   </td>
                  </tr>
                 </table>

        <?php

      }
    }

  }

}


?>
