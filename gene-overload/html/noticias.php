<?php


  include ('clases/obj_noticia.php');
  include ('clases/obj_comentario_noticia.php');



  // *********************************
  //   Nuevo comentario
  // *********************************

  if ($accion == 'anyadir_comentario')
  {

    $idelemento = $_REQUEST['idelemento'];
    if (!is_numeric($idelemento))
    {
      die;
    }
    $texto = $secure->Sanitizar($_REQUEST['texto']);
    $comentario_noticia = new Comentario_Noticia();

    if (strlen($texto) > 300)
    {
      echo ("<p class=\"errorsutil\">");
      if ($lang == 'en')
      {
        echo ("<b>Error : </b>Your comment is ".strlen($texto)." characters long, and the maximum allowed is 300.");
      } else {
        echo ("<b>Error : </b>Se superaron los 300 caracteres (tu comentario tiene ".strlen($texto).")");
      }
      echo ("</p>");
      $accion = 'comentar';
    } else {

      // Comprobar spam
      $hayspam = $comentario_noticia->BuscarSpam($link_r, $idelemento, $texto);
      if ($hayspam == 0)
      {

        // Y por fin, vamos a dejarle comentar
        $comentario_noticia->texto = $texto;
        $comentario_noticia->Insertar($link_w, $idelemento, $idjugador);
        echo ("<p class=\"correctosutil\">");
        if ($lang == 'en')
        {
          echo ("Comment posted");
        } else {
          echo ("Comentario enviado");
        }
        echo ("</p>");
        $accion = 'comentar';

      } else {
        echo ("<p class=\"errorsutil\">");
        if ($lang == 'en')
        {
          echo ("<b>Error : </b>You've already sent this comment.");
        } else {
          echo ("<b>Error : </b>Ya has enviado este mensaje.");
        }
        echo ("</p>");
        $accion = 'comentar';
      }

    }


  }

  // *********************************
  //   Comentar una noticia
  // *********************************

  if ($accion == 'comentar')
  {

    $jugador = new Jugador();
    $texto = $secure->Sanitizar($_REQUEST['texto']);
    $idelemento = $_REQUEST['idelemento'];
    if (!is_numeric($idelemento))
    {
      die;
    }
    $noticia = new Noticia();
    $comentario_noticia = new Comentario_Noticia();

    $noticia->SacarDatos($link_r, $idelemento);

        echo ("<table class=\"campana_title\">");
        echo ("<tr>");
        echo ("<td style=\"text-align: center;\">");
        echo ("<span style=\"font-size: 15px; font-weight: bold;\">");
        if ($lang == 'en')
        {
          echo ($noticia->titular_en);
        } else {
          echo ($noticia->titular);
        }
        echo ("</span>");
        echo ("</td>");
        echo ("</tr>");
        echo ("</table>");


        echo ("<table class=\"campana_subtitle\">");
        echo ("<tr>");
        echo ("<td>");
        echo ("<i>");
        echo (substr($noticia->fecha, 0, 10));
        echo ("</i>");
        echo ("<br/>");
        echo ("<br/>");
        echo ("<i>");
        if ($lang == 'en')
        {
          echo stripslashes($noticia->entradilla_en);
        } else {
          echo stripslashes($noticia->entradilla);
        }
        echo ("</i>");
        echo ("</td>");
        echo ("</tr>");
        echo ("</table>");

        echo ("<table class=\"campana_interior\">");
        echo ("<tr>");
        echo ("<td>");
        if ($lang == 'en')
        {
          echo stripslashes($noticia->texto_en);
        } else {
          echo stripslashes($noticia->texto);
        }
        echo ("</td>");
        echo ("</tr>");
        echo ("</table>");

	$arrayc = $comentario_noticia->BuscarElementos($link_r, $idelemento);
//echo count($arrayc)."$".$idelemento."$";
        if (count($arrayc) > 0)
        {
          echo ("<br/>");
          echo ("<br/>");
          for ($i = 1; $i <= count($arrayc); $i++)
          {

            echo ("<table class=\"campana_interior\" style=\"background-color: #554433\">");
            echo ("<tr>");
            echo ("<td>");
            echo ("<span style=\"font-size: 11px; color: #aaaaaa;\"><i>");
            if ($lang == 'en')
            {
//              $jugador->SacarDatos($link_r, $idjugador);
              $jugador->SacarDatos($link_r, $arrayc[$i]['idjugador']);
              echo ("Comment posted by ".$jugador->login." at ".$arrayc[$i]['fecha']);
            } else {
              $jugador->SacarDatos($link_r, $arrayc[$i]['idjugador']);
              echo ("Comentario enviado por ".$jugador->login." en ".$arrayc[$i]['fecha']);
            }
            echo ("</i></span>");
            echo ("<br/>");
            echo ("<br/>");
            echo ("<span style=\"font-size: 12px; color: #cccccc;\">");
            echo ($arrayc[$i]['texto']);
            echo ("</i></span>");
            echo ("</td>");
            echo ("</tr>");
            echo ("</table>");

          }
        }

        if (($idjugador != '') && ($idjugador != null) && ($idjugador > 0))
        {
          echo ("<p style=\"color: #c58768\">");
  	  if ($lang == 'en')
  	  {
	    echo ("Add a comment (max. 300 characters) </p>");
	  } else {
	    echo ("A&ntilde;adir un comentario (por favor, en ingl&eacute;s) (m&aacute;ximo 300 caracteres) </p>");
	  }
  	  ?>
            <br/>
            <form method="post" action="index.php">
            <input type="hidden" name="catid" value="<?php echo $catid; ?>">
            <input type="hidden" name="lang" value="<?php echo $lang; ?>">
            <input type="hidden" name="accion" value="anyadir_comentario">
            <input type="hidden" name="idelemento" value="<?php echo $idelemento; ?>">
            <input type="hidden" name="idcampana" value="<?php echo $idcampana; ?>">
            <textarea name="texto" cols="70" rows="6"><?php echo stripslashes($texto); ?></textarea>
            <br/>
            <br/>
            <?php
              if ($lang == 'en')
              {
              ?>
                <input type="submit" value="Comment">
              <?php
              } else {
              ?>
                <input type="submit" value="Comentar">
              <?php
              }
              ?>

            </form>
	  <?php
	}



  }


  // *********************************
  //   Por defecto listamos las noticias
  // *********************************

  if ($accion == null)
  {

     echo ("<center>");
//     echo ("<p style=\"font-size: 17px; color: #c58768; \"><b>");
     if ($lang == 'en')
     {
       ?>
         <p style="font-size: 13px; color: #f5b798; font-weight: normal;"><i>"We are the 
accidental result of an unplanned process ... the fragile result of an enormous concatenation 
of improbabilities, not the predictable product of any definite process". [Stephen Jay Gould]
		</i></p>
       <?php
       echo ("<br/>");
       echo ("<br/>");
       echo ("<p style=\"font-size: 22px;\">");
       echo ("News");
//    <p style="font-size: 22px;"><b>Noticias</b></p>
	?>
<br/>
	<?php
//       echo ("News");
     } else {
       ?>
         <p style="font-size: 13px; color: #f5b798; font-weight: normal;"><i>"Somos el resultado
accidental de un proceso no planeado ... el fr&aacute;gil resultado de una enorme concatenaci&oacute;n
de improbabilidades, no el resultado predecible de ning&uacute;n proceso definido". [Stephen Jay Gould]
		</i></p>
       <?php
       echo ("<br/>");
       echo ("<br/>");
       echo ("<p style=\"font-size: 22px;\">");
       echo ("Noticias");
     }
//     echo ("</b></p>");
     echo ("</center>");

    $noticia = new Noticia();
    $comentario_noticia = new Comentario_Noticia();

    $limitelementos = 1;
    $pg = $_REQUEST['pg'];
    if ($pg == null) { $pg = 1; }
    if (!is_numeric($pg))
    {
      $pg = 1;
    }
    $offset = (($pg - 1) * $limitelementos);

    $numelementostotal = $noticia->ContarNoticiasPublicadas($link_r);
    $array = $noticia->BuscarNoticiasPublicadas($link_r, $limitelementos, $offset);

//echo $numelementostotal."$".count($array);
    if ($numelementostotal > 0) {
        // ------------------------------> Paginado <--------------------------------------
    if ($pg > 1) {
                $pgant = $pg - 1;
        if ($lang == 'en')
        {
          echo ("<a class=\"paginado\" href=\"".$PATHBASE_exterior."index.php?catid=".$catid."&lang=".$lang."&estado=".$estado."&tipo=".$tipo."&pg=".$pgant."\">Previous page</a>");
        } else {
          echo ("<a class=\"paginado\" href=\"".$PATHBASE_exterior."index.php?catid=".$catid."&lang=".$lang."&estado=".$estado."&tipo=".$tipo."&pg=".$pgant."\">P&aacute;gina anterior</a>");
        }
        $pgf = 1;
    }

    if ( ($offset + $limitelementos) < $numelementostotal) {
                if ($pgf == 1) { echo ("<span class=\"paginado\"> - </span>"); }
        $pgsig = $pg + 1;
        if ($lang == 'en')
        {
          echo ("<a class=\"paginado\" href=\"".$PATHBASE_exterior."index.php?catid=".$catid."&lang=".$lang."&estado=".$estado."&tipo=".$tipo."&pg=".$pgsig."\">Next page</a>");
        } else {
          echo ("<a class=\"paginado\" href=\"".$PATHBASE_exterior."index.php?catid=".$catid."&lang=".$lang."&estado=".$estado."&tipo=".$tipo."&pg=".$pgsig."\">P&aacute;gina siguiente</a>");
        }
    }


        // Pagina $pg de $totpg
    $totpg = floor(($numelementostotal - 1) / $limitelementos) + 1;
    if ($totpg < 1) { $totpg = 1; }
        // ------------------------------> Paginado <--------------------------------------

//    echo ("<br/>");
//    echo ("<br/>");
//    echo ("<br/>");

    for ($i = 1; $i <= count($array); $i++)
    {
//echo ("#");
        echo ("<table class=\"campana_title\">");
        echo ("<tr>");
        echo ("<td style=\"text-align: center;\">");
        echo ("<span style=\"font-size: 15px; font-weight: bold;\">");
        if ($lang == 'en')
        {
          echo ($array[$i]['titular_en']);
        } else {
          echo ($array[$i]['titular']);
        }
        echo ("</span>");
        echo ("</td>");
        echo ("</tr>");
        echo ("</table>");


        echo ("<table class=\"campana_subtitle\">");
        echo ("<tr>");
        echo ("<td>");
        echo ("<i>");
        echo (substr($array[$i]['fecha'], 0, 10));
        echo ("</i>");
        echo ("<br/>");
        echo ("<br/>");
        echo ("<i>");
        if ($lang == 'en')
        {
          echo stripslashes($array[$i]['entradilla_en']);
        } else {
          echo stripslashes($array[$i]['entradilla']);
        }
        echo ("</i>");
        echo ("</td>");
        echo ("</tr>");
        echo ("</table>");

        echo ("<table class=\"campana_interior\">");
        echo ("<tr>");
        echo ("<td>");
        if ($lang == 'en')
        {
          echo stripslashes($array[$i]['texto_en']);
        } else {
          echo stripslashes($array[$i]['texto']);
        }

        $numcomentarios = $comentario_noticia->ContarElementos($link_r, $array[$i]['id']);
        echo ("<br/>");
        echo ("<br/>");
        echo ("<a href=\"index.php?catid=".$catid."&lang=".$lang."&idcampana=".$idcampana."&idelemento=".$array[$i]['id']."&accion=comentar\">");
        if ($lang == 'en')
	{
          echo ("Click to comment");
	} else {
          echo ("Haz click para comentar");
	}
        echo ("</a>");
        if ($numcomentarios > 0)
        {
          echo ("<span style=\"color: #aaffaa;\"> (");
          if ($lang == 'en')
  	  {
            echo ($numcomentarios." comments");
  	  } else {
            echo ($numcomentarios." comentarios");
  	  }
          echo (")</span>");
        }
        echo ("<br/>");
        echo ("<br/>");

        echo ("</td>");
        echo ("</tr>");
        echo ("</table>");


      }




    }










  }


?>
