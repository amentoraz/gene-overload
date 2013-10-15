<?php

  include ('clases/obj_ayuda_ticket.php');
  include ('clases/obj_ayuda_comentario.php');


  $accion = $secure->Sanitizar($_REQUEST['accion']);




  // ***************************************
  //          Gestionar un ticket
  // ***************************************

  if ($accion == "comentar")
  {
    $idelemento = $_REQUEST['idelemento'];
    if (!is_numeric($idelemento))
    {
      $die;
    }
    $texto = $secure->Sanitizar($_REQUEST['texto']);

    $ayuda_ticket = new Ayuda_Ticket();
    $ayuda_ticket->SacarDatos($link_r, $idelemento);
    if ($ayuda_ticket->idjugador != $idjugador)
    {
      die;
    }

    $por_tiempo = $ayuda_ticket->ComprobarSiPuedeAbrir($link_r, $idjugador);
    if (($ayuda_ticket->archivada != 1) && ($por_tiempo == 1))
    {

      $ayuda_comentario = new Ayuda_Comentario();
      $ayuda_comentario->idticket = $idelemento;
      $ayuda_comentario->escribe_admin = 0;
      $ayuda_comentario->idjugador = $idjugador;
      $ayuda_comentario->texto = $texto;
      $ayuda_comentario->InsertarElemento($link_w);

      if ($lang == 'en')
      {
        echo ("<p class=\"correctosutil\">Comment inserted<p>");
      } else {
        echo ("<p class=\"correctosutil\">Comentario insertado<p>");
      }
      echo ("<br/>");
      $accion = "gestionar";

    }


  }

  // ***************************************
  //          Gestionar un ticket
  // ***************************************

  if ($accion == "gestionar")
  {

    $idelemento = $_REQUEST['idelemento'];
    if (!is_numeric($idelemento))
    {
      $die;
    }

    // Que no puedas acceder a la ayuda de otro
    $ayuda_ticket = new Ayuda_Ticket();
    $ayuda_ticket->SacarDatos($link_r, $idelemento);
    if ($ayuda_ticket->idjugador != $idjugador)
    {
      die;
    }


    if ($lang == 'en')
    {
      ?>
        <br/>
        <p style="font-size: 14px; color: #ffbb55; "><b>Your help request</b></p>
        <br/>
        <br/>
        <p style="font-size: 12px; color: #ffbb55; ">Request sent on <?php

        $anyo = substr($ayuda_ticket->fecha, 0, 4);
        $mes = substr($ayuda_ticket->fecha, 5, 2);
        $dia = substr($ayuda_ticket->fecha, 8, 2);
        $hora = substr($ayuda_ticket->fecha, 11, 5);
          if ($dia == '01') { echo ("1st "); } else {
            if ($dia == '02') { echo ("2nd "); } else {
              if ($dia == '02') { echo ("3rd "); } else {
                echo (abs($dia)."th ");
              }
            }
          }
          echo (" of ".$array_months[(abs($mes))].", ".$anyo.", ".$hora);
	?>.</p>
        <br/>

        <table width="600px">
         <tr>
          <td width="80px">
           <p style="font-size: 12px; color: #ffbb55; ">
            <b>Subject :</b>
           </p>
          </td>
          <td>
           <p style="font-size: 12px; color: #ffbb55; ">
            <?php echo $ayuda_ticket->subject;
		?>
           </p>
          </td>
         </tr>
        </table>
        <br/>

        <table width="600px">
         <tr>
          <td width="80px">
           <p style="font-size: 12px; color: #ffbb55; ">
            <b>Text :</b>
           </p>
          </td>
          <td>
           <p style="font-size: 12px; color: #ffbb55; ">
            <?php echo $ayuda_ticket->texto;
		?>
           </p>
          </td>
         </tr>
        </table>

      <br/>
      <br/>
      <p style="font-size: 13px; color: #ffbb55; "><b>Comments</b></p>
      <br/>

      <?php
    } else {
      ?>
        <br/>
        <br/>
        <br/>
        <p style="font-size: 14px; color: #ffbb55; "><b>Tu petici&oacute;n de ayuda</b></p>
        <br/>
        <p style="font-size: 12px; color: #ffbb55; ">Petici&oacute;n enviada en <?php 


        $anyo = substr($ayuda_ticket->fecha, 0, 4);
        $mes = substr($ayuda_ticket->fecha, 5, 2);
        $dia = substr($ayuda_ticket->fecha, 8, 2);
        $hora = substr($ayuda_ticket->fecha, 11, 5);
        echo (abs($dia)." de ".$array_meses[(abs($mes))]." de ".$anyo.", ".$hora);

	?>.</p>
        <br/>

        <table width="600px">
         <tr>
          <td width="80px">
           <p style="font-size: 12px; color: #ffbb55; ">
            <b>Asunto :</b>
           </p>
          </td>
          <td>
           <p style="font-size: 12px; color: #ffbb55; ">
            <?php echo $ayuda_ticket->subject;
		?>
           </p>
          </td>
         </tr>
        </table>
        <br/>

        <table width="600px">
         <tr>
          <td width="80px">
           <p style="font-size: 12px; color: #ffbb55; ">
            <b>Contenido :</b>
           </p>
          </td>
          <td>
           <p style="font-size: 12px; color: #ffbb55; ">
            <?php echo $ayuda_ticket->texto;
		?>
           </p>
          </td>
         </tr>
        </table>

      <br/>
      <br/>
      <p style="font-size: 13px; color: #ffbb55; "><b>Comentarios</b></p>
      <br/>

      <?php
    }

    // Ahora vamos a listar todos los comentarios
    $ayuda_comentario = new Ayuda_Comentario();
    $array = $ayuda_comentario->BuscarComentarios($link_r, $idelemento);

    for ($i = 1; $i <= count($array); $i++)
    {
      ?>
      <table style="background-color: #222222;
		line-height: 3em;
		padding: 5px;
		" width="90%">
       <tr>
        <td>
         <?php
           echo ($array[$i]['texto']);
           echo ("<br/>");
           echo ("<span style=\"color: #cecece; font-size: 12px;\"><i>");
           if ($array[$i]['escribe_admin'] == 1)
           {
             if ($lang == 'en')
             {
               echo ("written by Admin on ".$array[$i]['fecha']);
             } else {
               echo ("escrito por Admin en ".$array[$i]['fecha']);
             }
           } else {
             if ($lang == 'en')
             {
               echo ("written by you on ".$array[$i]['fecha']);
             } else {
               echo ("escrito por you en ".$array[$i]['fecha']);
             }
           }
           echo ("</i></span>");

         ?>
        <td>
       <tr>
      </table>
      <br/>
      <?php
    }

    //  Ahora sacamos el formulario para que escribas siempre que
    // no este cerrado

    $por_tiempo = $ayuda_ticket->ComprobarSiPuedeAbrir($link_r, $idjugador);
    if (($ayuda_ticket->archivada != 1) && ($por_tiempo == 1))
    {



      if ($lang == 'en')
      {
      ?>
        <p style="font-size: 14px; color: #ffbb55; "><b>Post a comment</b></p>
      <?php
      } else {
      ?>
        <p style="font-size: 14px; color: #ffbb55; "><b>Escribe un comentario</b></p>
      <?php
      }
      ?>

        <br/>
        <form method="post" action="index.php">
	  <input type="hidden" name="catid" value="<?php echo $catid; ?>">
	  <input type="hidden" name="accion" value="comentar">
	  <input type="hidden" name="idelemento" value="<?php echo $idelemento; ?>">
          <p style="font-size: 13px; color: #ffbb55;">
            </p>
          <p><textarea name="texto" rows="12" cols="70"></textarea>
	  </p>

      <br/>
      <p>
      <?php
        if ($lang == 'en')
	{
	  echo ("<input type=\"submit\" value=\"Send comment\">");
	} else {
	  echo ("<input type=\"submit\" value=\"Enviar cometnario\">");
	}
      ?>
      </p>

        </form>

      <?php




    }



  }


  // ***************************************
  //          Anyadir un ticket
  // ***************************************

  if ($accion == "anyadir")
  {

    $ayuda_ticket = new Ayuda_Ticket();
    $subject = $secure->Sanitizar($_REQUEST['subject']);
    $texto = $secure->Sanitizar($_REQUEST['texto']);

    $permitir = $ayuda_ticket->ComprobarSiPuedeAbrir($link_r, $idjugador);
    if ($permitir == 0)
    {
      if ($lang == 'en')
      {
        echo ("<p class=\"errorsutil\"><b>Sorry, you've recently opened a help ticket. Please, wait for a while.</b></p>");
      } else {
        echo ("<p class=\"errorsutil\"><b>Lo siento, has abierto recientemente un ticket de ayuda. Por favor, espera un poco.</b></p>");
      }

    } else {

      $ayuda_ticket->subject = $subject;
      $ayuda_ticket->texto = $texto;
      $ayuda_ticket->idjugador = $idjugador;
      $ayuda_ticket->InsertarElemento($link_w);

      if ($lang == 'en')
      {
        echo ("<p class=\"correctosutil\"><b>Your ticket has been opened. Soon you will receive an answer.</b></p>");
      } else {
        echo ("<p class=\"correctosutil\"><b>Tu ticket ha sido abierto. Pronto recibir&aacute;s una respuesta.</b></p>");
      }

    }
    echo ("<br/>");

    $accion = null;

  }





  // ***************************************
  //          Ayuda
  // ***************************************

  if ($accion == null)
  {

    // Primero vamos a mostrar los tickets abiertos que tienes


/*
    if ($lang == 'en')
    {
      echo ("Your help requests");
    } else {
      echo ("Tus peticiones de ayuda");
    }

    echo ("<br/>");
    echo ("<br/>");
*/
    $limitelementos = 10;
    $pg = $_REQUEST['pg'];
    if (!is_numeric($pg))
    {
      $pg = 1;
    }
    if ($pg == null) { $pg = 1; }
    $offset = (($pg - 1) * $limitelementos);

    $ayuda_ticket = new Ayuda_Ticket();
    $ayuda_comentario = new Ayuda_Comentario();
//    $array = $ayuda_ticket->BuscarElementosJugador($link_r, $idjugador, $offset, $limitelementos);


    $numelementostotal = $ayuda_ticket->ContarElementosJugador($link_r, $idjugador);
    $array = $ayuda_ticket->BuscarElementosJugador($link_r, $idjugador, $offset, $limitelementos);





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
    } // if $totpg > 1 (sino no imprime nada)
    // ------------------------------> Paginado <--------------------------------------



    echo ("<div id=\"espacio\" class=\"espacio\">");
    echo ("</div>");

    echo ("<table id=\"tabla_informe\" class=\"tabla_centro_control\">");



    if ($lang == 'en')
    {
      echo ("<tr><th colspan=\"5\">Your help requests</th></tr>");
    } else {
      echo ("<tr><th colspan=\"5\">Tus peticiones de ayuda</th></tr>");
    }

    echo ("<tr>");
    if ($lang == 'en')
    {
      echo ("<th width=\"250px\">Date & Time</th>");
      echo ("<th width=\"350px\">Subject</th>");
      echo ("<th width=\"100px\">Answers</th>");
      echo ("<th width=\"100px\">Archived</th>");
      echo ("<th width=\"100px\">Manage</th>");
    } else {
      echo ("<th width=\"200px\">Fecha y hora</th>");
      echo ("<th width=\"350px\">Asunto</th>");
      echo ("<th width=\"100px\">Respuestas</th>");
      echo ("<th width=\"100px\">Archivada</th>");
      echo ("<th width=\"100px\">Gestionar</th>");
    }
    echo ("</tr>");

    if ($numelementostotal > 0)
    {
      for ($j = 1; $j <= $numelementostotal; $j++)
      {
        if ($j % 2 == 1)
        {
          echo ("<tr style=\"background-color: #111111; font-size: 13px;\">");
        } else {
          echo ("<tr style=\"background-color: #333333; font-size: 13px;\">");
        }

        // FECHA
        echo ("<td>");
        $anyo = substr($array[$j]['fecha'], 0, 4);
        $mes = substr($array[$j]['fecha'], 5, 2);
        $dia = substr($array[$j]['fecha'], 8, 2);
        $hora = substr($array[$j]['fecha'], 11, 5);
        if ($lang == 'en')
        {
          if ($dia == '01') { echo ("1st "); } else {
            if ($dia == '02') { echo ("2nd "); } else {
              if ($dia == '02') { echo ("3rd "); } else {
                echo (abs($dia)."th ");
              }
            }
          }
          echo (" of ".$array_months[(abs($mes))].", ".$anyo.", ".$hora);
        } else {
          echo (abs($dia)." de ".$array_meses[(abs($mes))]." de ".$anyo.", ".$hora);
        }
        echo ("</td><td>");
        echo ($array[$j]['subject']);
        echo ("</td><td>");
        $numcomentarios = $ayuda_comentario->ContarComentarios($link_r, $array[$j]['id']);
        echo $numcomentarios;
        echo ("</td><td>");
        if ($array[$j]['archivada'] == 1)
        {
          echo ("<img src=\"img/founder.gif\">");
        } else {
          echo ("<img src=\"img/abierto.gif\">");
        }
        echo ("</td><td>");
        echo ("<a href=\"index.php?catid=".$catid."&idelemento=".$array[$j]['id']."&idcampana=".$idcampana."&accion=gestionar\">");
        echo ("<img src=\"img/radiation.png\">");
        echo ("</a>");
        echo ("</td></tr>");
      }
    } else {
      echo ("<tr style=\"background-color: #111111; font-size: 13px;\">");
      echo ("<td colspan=\"5\">");
      echo ("<br/>");
      if ($lang == 'en')
      {
        echo ("<p class=\"errorsutil\"><b>You have posted no help requests.</b></p>");
      } else {
        echo ("<p class=\"errorsutil\"><b>No has escrito ninguna petici&oacute;n de ayuda.</b></p>");
      }
      echo ("<br/>");
      echo ("</td></tr>");
    }
    echo ("</table>");


    // Ahora le metemos el formulario para que anyadan un ticket

    if ($lang == 'en')
    {
      ?>
        <br/>
        <br/>
	<br/>
        <p style="font-size: 14px; color: #ffbb55; "><b>Post a help request</b></p>
        <br/>
        <p style="font-size: 12px; color: #ffbb55; ">Please, do not overuse this system. It is meant to provide help for any bugs/errors found in the website.</p>
	<br/>
        <form method="post" action="index.php">
          <input type="hidden" name="accion" value="anyadir">
          <input type="hidden" name="catid" value="<?php echo $catid; ?>">
          <input type="hidden" name="idcampana" value="<?php echo $idcampana; ?>">
          <table width="650px" style="padding: 10px;">
           <tr height="40px">
            <td>
             <p style="font-size: 12px; color: #ffbb55; ">
		<b>Subject :</b> 
	     </p>
		</td><td>
		<input type="text" name="subject" size="60">
		</td>
  	      </tr>
  	      <tr>
		<td style="vertical-align: top;">
             <p style="font-size: 12px; color: #ffbb55; ">
		<b>Text :</b> 
	     </p>
		</td><td>
                <textarea name="texto" cols="70" rows="14"></textarea>
            </td>
           </tr>
          </table>
	  <br/>
          <p>
            <input type="submit" value="Send help request">
          </p>
        </form>
      <?php
    } else {
      ?>
        <br/>
        <br/>
	<br/>
        <p style="font-size: 14px; color: #ffbb55; "><b>Env&iacute;a una petici&oacute;n de ayuda</b></p>
        <br/>
        <p style="font-size: 12px; color: #ffbb55; ">Por favor, no sobreutilices este sistema. Pretende proporcionar ayuda para cualquier bug/error que haya en el sistema.</p>
	<br/>
        <form method="post" action="index.php">
          <input type="hidden" name="accion" value="anyadir">
          <input type="hidden" name="catid" value="<?php echo $catid; ?>">
          <input type="hidden" name="idcampana" value="<?php echo $idcampana; ?>">
          <table width="650px" style="padding: 10px;">
           <tr height="40px">
            <td>
             <p style="font-size: 12px; color: #ffbb55; ">
		<b>Tema :</b> 
	     </p>
		</td><td>
		<input type="text" name="subject" size="60">
		</td>
  	      </tr>
  	      <tr>
		<td style="vertical-align: top;">
             <p style="font-size: 12px; color: #ffbb55; ">
		<b>Texto :</b> 
	     </p>
		</td><td>
                <textarea name="texto" cols="70" rows="14"></textarea>
            </td>
           </tr>
          </table>
	  <br/>
          <p>
            <input type="submit" value="Enviar petici&oacute;n de ayuda">
          </p>
        </form>
      <?php
    }


  }





?>
