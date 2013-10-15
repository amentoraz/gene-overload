<?php

  include("clases/obj_sabias_que.php");
  include("clases/obj_noticia.php");
  include("clases/obj_arbol.php");
  include("clases/obj_texto_web.php");
  include("clases/obj_especimen.php");
  include("clases/obj_especimen_torneo.php");

//  $accion = $_REQUEST['accion'];



  if ($lang == 'en')
  {
?>
   <center>
    <p style="font-size: 13px; color: #f5b798;"><i>"If you wish to make an apple pie from scratch, you must first invent the Universe". [Carl Sagan]</i></p>
   </center>
<?php
  } else {
?>
   <center>
    <p style="font-size: 13px; color: #f5b798;"><i>"Si quieres hacer una tarta de manzana desde cero, tienes que inventar primero el Universo". [Carl Sagan]</i></p>
   </center>
<?php
  }
?>
   <br/>



	<p style="text-align: center;">
	<img src="img/logo.png">
	</p>

<!--
  <table width="90%" class="campana_title">
  <tr>
  <td style="padding: 10px;">
<?php
  $texto_web = new Texto_Web();
  $texto_web->Sacar_Datos_Cat($link_r, $catid);

  if ($lang == 'en')
  {
    echo $texto_web->texto_en;
  } else {
    echo $texto_web->texto;
  }
?>
 </td>
 <td style="padding: 10px;">
  <img src="img/engranajes.jpg" style="vertical-align: top;">
 </td>
 </tr>
 </table>
 <br/>
-->


 <center>
 <?php
 if (($idjugador == null) || ($idjugador == '') || ($accion == 'logout'))
 {
  if ($lang == 'en')
  {
//   <img src="img/signupbut.jpg">
  ?>
   <br/>
   <br/>
<!--
   <br/>
   <a href="index.php?catid=100&lang=<?php echo $lang; ?>"><img src="img/signupbeta.jpg"
onmouseover="javascript:this.src='img/signupbeta2.jpg';" 
onmouseout="javascript:this.src='img/signupbeta.jpg';"
></a>
   <br/>
   <br/>
   <br/>
-->
   <br/>
   <br/>
   <br/>

<!--   <p style="font-size: 18px; font-weight: bold; color: #c58768;">Register now for free. Click here</p> -->

   <a style="font-size: 24px; font-weight: bold;" href="index.php?catid=108&lang=<?php echo $lang; ?>">
      Register now for free. Click here
   </a>

<!--
   <form method="post" action="index.php">
   <input type="hidden" name="catid" value="105">
   <input type="hidden" name="lang" value="<?php echo $lang; ?>">
   <p style="font-size: 14px; font-weight: bold; color: #c58768;">Insert beta code :
     <input type="text" name="betacode" size="50" value="46GyjDvos8SbZeQHvLVB">
     <br/>
     <br/><i><span class="registrar">(an automated code has been generated, since there is enough slots for registration)</span></i><br/>
	<br/>
     <input type="submit" value="Send and register">
   </p>
   </form>
-->
  <?php
  } else {
//  <img src="img/signupbut_es.jpg">
  ?>
   <br/>


<!--
   <br/>
   <br/>
   <a href="index.php?catid=100&lang=<?php echo $lang; ?>"><img src="img/signupbeta_es.jpg"
onmouseover="javascript:this.src='img/signupbeta_es2.jpg';" 
onmouseout="javascript:this.src='img/signupbeta_es.jpg';">
</a>
   <br/>
-->
   <br/>
   <br/>
   <br/>
   <br/>

   <a style="font-size: 24px; font-weight: bold;" href="index.php?catid=108&lang=<?php echo $lang; ?>">
     Reg&iacute;strate gratis. Haz click aqu&iacute;
   </a>

<!--
   <br/>
   <br/>
   <br/>
   <form method="post" action="index.php">
   <input type="hidden" name="catid" value="105">
   <input type="hidden" name="lang" value="<?php echo $lang; ?>">
   <p style="font-size: 14px; font-weight: bold; color: #c58768;">Insertar c&oacute;digo de beta :
     <input type="text" name="betacode" size="50" value="46GyjDvos8SbZeQHvLVB">
     <br/>
     <br/><i><span style="font-size: 13px;">(se ha generado un c&oacute;digo autom&aacute;tico, ya que hay suficiente espacio para el registro)</span></i>
	<br/>
	<br/>
         <input type="submit" value="Enviar y registrarse">
   </p>
   </form>
-->
  <?php
  }
 }
  ?>
 </center>
 <br/>
 <br/>


<br/>


<?php
if ($idjugador != null)
{


  // **************************************************
  //    Desapuntarte de una campanya
  // **************************************************

  if ($accion == 'desapuntarse')
  {
    $especimen_torneo = new Especimen_Torneo();
    $idcampana = $_REQUEST['idcampana'];
    if (!is_numeric($idcampana))
    {
      die;
    }

    $especimen = new Especimen();
    $jugador_campana = new Jugador_campana();

    $estajugador = $jugador_campana->EstaCampanaJugador($link_r, $idjugador, $idcampana);

    if ($estajugador == 1)
    {
      // Borramos los especimenes de este jugador
      $especimen->JugadorEliminaCampana($link_w, $idjugador, $idcampana);

      // Borramos de los torneos la referencia
      for ($d = 0; $d <= 3; $d++)
      {
          $especimen_torneo->DesapuntarTorneo($link_w, $idjugador, $idcampana, $d);
      }

      // Lo borramos de la campanya
      $jugador_campana->Eliminar($link_w, $idjugador, $idcampana);

      if ($lang == 'en')
      {
        echo ("<p class=\"correctosutil\">You have left this campaign!</p>");
      } else {
        echo ("<p class=\"correctosutil\">Quedas desapuntado de esta campa&ntilde;a!</p>");
      }

      // Vamos a borrar tambien los informes de este usuario
      $informe = new Informe();
      $informe->EliminarInformesJugador($link_w, $idjugador, $idcampana);

    } else {
      if ($lang == 'en')
      {
        echo ("<p class=\"errorsutil\">Sorry but you were unrelated with this campaign</p>");
      } else {
        echo ("<p class=\"errorsutil\">Lo siento, no estabas apuntado a esta campa&ntilde;a</p>");
      }
    }

    $accion = null;

  }


  // **************************************************
  //    Apuntarte a una campanya
  // **************************************************

  if ($accion == 'apuntarse')
  {

    $idcampana = $_REQUEST['idcampana'];
    if (!is_numeric($idcampana))
    {
      die;
    }

    $arbol = new Arbol();
    $especimen = new Especimen();
    $especimen_torneo = new Especimen_Torneo();
    $jugador_campana = new Jugador_campana();

    $estajugador = $jugador_campana->EstaCampanaJugador($link_r, $idjugador, $idcampana);


    if ($estajugador == 1)
    {
      if ($lang == 'en')
      {
        echo ("<p class=\"errorsutil\">Sorry, you already belong to this campaign</p><br/>");
      } else {
        echo ("<p class=\"errorsutil\">Lo siento, ya perteneces a esta campa&ntilde;a</p><br/>");
      }
    } else {
      if ($lang == 'en')
      {
        echo ("<p class=\"correctosutil\">Now you're joined this campaign!</p>");
      } else {
        echo ("<p class=\"correctosutil\">Quedas apuntado a esta campa&ntilde;a!</p>");
      }


      $campana = new Campana();
      $campana->SacarDatos($link_r, $idcampana);

      $dinero_inicial = $campana->dinero_inicial;
      $cantidad_profundidades = $campana->cantidad_profundidades;
      $cantidad_bosque = $campana->cantidad_bosque;
      $cantidad_volcan = $campana->cantidad_volcan;
      $cantidad_niveles_arbol = $campana->niveles_arbol;

      // 15 de dinero inicial, 7 slots por deme y 3 niveles de profundidad en el arbol lo ponemos como lo basico.
//      $jugador_campana->InsertarElemento($link_w, $idjugador, $idcampana, 18, 7, 7, 7, 3);
      $jugador_campana->InsertarElemento($link_w, $idjugador, $idcampana, $dinero_inicial, $cantidad_profundidades, $cantidad_bosque, $cantidad_volcan, $cantidad_niveles_arbol);

      // Ahora tenemos que crear los especimenes.
      // Primer deme (profundidades)
      for ($i = 1; $i <= $cantidad_profundidades; $i++)
      {
//echo ($i."#");
        $especimen->CrearEspecimen($link_w, TOTAL_PUNTOS_BASE, 1, $idjugador, $idcampana, $i, 3);
        $idarbol = mysql_insert_id($link_w);
        // Generamos un arbol con 3 niveles
        $string_arbol = $arbol->GenerarArbolInicial(3);
        $especimen->arbol = $string_arbol;
        $especimen->ActualizarArbol($link_w, $idarbol);
      }
      // Segundo deme (bosque)
      for ($i = 1; $i <= $cantidad_bosque; $i++)
      {
//echo ($i."$");
        $especimen->CrearEspecimen($link_w, TOTAL_PUNTOS_BASE, 2, $idjugador, $idcampana, $i, 3);
        $idarbol = mysql_insert_id($link_w);
        // Generamos un arbol con 3 niveles
//        $arbol->GenerarArbolInicial(3);
        // Generamos un arbol con 3 niveles
        $string_arbol = $arbol->GenerarArbolInicial(3);
        $especimen->arbol = $string_arbol;
        $especimen->ActualizarArbol($link_w, $idarbol);
      }
      // Tercer deme (fuego)
      for ($i = 1; $i <= $cantidad_volcan; $i++)
      {
//echo ($i."|");
        $especimen->CrearEspecimen($link_w, TOTAL_PUNTOS_BASE, 3, $idjugador, $idcampana, $i, 3);
        $idarbol = mysql_insert_id($link_w);
        // Generamos un arbol con 3 niveles
//        $arbol->GenerarArbolInicial(3);
        // Generamos un arbol con 3 niveles
        $string_arbol = $arbol->GenerarArbolInicial(3);
        $especimen->arbol = $string_arbol;
        $especimen->ActualizarArbol($link_w, $idarbol);
      }

//echo ("#");
      // Una vez esta todo creado, ponemos por defecto para torneo
      $especimen->SacarDatos($link_r, 1, 1, $idjugador, $idcampana); // los dos 1s son iddeme e idslot respectivamente
      $especimen_torneo->ApuntarTorneo($link_w, $especimen->id, 0);

      // Tambien para cada deme
      for ($d = 1; $d <= 3; $d++)
      {
        $especimen->SacarDatos($link_r, $d, 1, $idjugador, $idcampana); // el primero es iddeme, el segundo idslot
        $especimen_torneo->ApuntarTorneo($link_w, $especimen->id, $d);
      }





      //  Ahora tambien habria que enviarle un informe de prueba, para
      // que no se queden atascados en el tutorial.

      $informe = new Informe();
      $informe->tipo = 5;
      if ($lang == 'en')
      {
        $informe->subject = 'Welcome to '.$campana->nombre_en;
        $informe->texto = 'The Scientific Council welcomes you to '.$campana->nombre_en.'.<br/><br/> We wish you good luck,';
        $informe->texto = $informe->texto.' and hope you make the breakthroughs that lead to a new era in the field of genetic engineering.<br/><br/>';
        $informe->texto = $informe->texto." We've given you a basic amount of money and we will regularly subsidize you so that you can go ";
        $informe->texto = $informe->texto." on with your studies. Please use it well! ";
      } else {
        $informe->subject = 'Bienvenido a '.$campana->nombre;
        $informe->texto = 'El Consejo Cient&iacute;fico te da la bienvenida a '.$campana->nombre.'.<br/><br/> Te deseamos buena suerte,';
        $informe->texto = $informe->texto.' y esperamos que sean tuyos los avances que nos lleven a una nueva era en el campo de la ingenier&iacute;a gen&eacute;tica.<br/><br/>';
        $informe->texto = $informe->texto." Te hemos dado una cantidad b&aacute;sica de dinero, y te entregaremos regularmente un subsidio para que puedas ";
        $informe->texto = $informe->texto." continuar con tus estudios. &iexcl;Por favor, &uacute;salo bien! ";
      }
      $informe->EnviarInformeRaw($link_w, $idjugador, $idcampana);


    }



    $accion = null;

  }

  // **************************************************
  //    Listar las campanyas disponibles y apuntadas
  // **************************************************

  if ($accion == null)
  {


    ?>

     <script>
       function ConfirmarEliminarEn(delUrl) {
        if (confirm("Are you sure you want to leave this campaign? Everything you've done there will be WIPED OUT if you do!!!")) {
         document.location = delUrl;
        }
       }
     </script>

     <script>
       function ConfirmarEliminarEs(delUrl) {
        if (confirm("Seguro que desea dejar esta campana? Todo lo que ha hecho alli sera BORRADO si lo hace!!!")) {
         document.location = delUrl;
        }
       }
     </script>


    <?php


    echo ("<table class=\"campana_title\">");
    echo ("<tr>");
    echo ("<td>");
    $sabias_que = new Sabias_Que();
    $sabias_que->ObtenerAleatorio($link_r);
    if ($lang == 'en')
    {
      echo ("<b><center>Tip of the day</center></b><br/>");
      echo ("<i>");
      echo $sabias_que->texto_en;
      echo ("</i>");
    } else {
      echo ("<b><center>Pista del d&iacute;a</center></b><br/>");
      echo ("<i>");
      echo $sabias_que->texto_es;
      echo ("</i>");
    }
    echo ("</td></tr></table>");



//    if ($lang == 'en')
//    {
//      echo (" Current campaigns :");
//    } else {
//      echo (" Campa&ntilde;as en curso :");
//    }

    $campana = new Campana();
    $arraycampanas = $campana->ListarCampanasActivas($link_r);

    $jugador_campana = new Jugador_campana();
    for ($i = 1; $i <= count($arraycampanas); $i++)
    {
    	// Cuanta gente hay en esta campaña
      $cuantos_campana = $jugador_campana->ContarElementosRank($link_r, $arraycampanas[$i]['id']);    	
    	
//      echo ("<hr>");

      echo ("<table class=\"campana_title\">");
      echo ("<tr>");
      echo ("<td>");

//      echo ("<p>");
      if ($lang == 'en')
      {
        echo ("Campaign");
      } else {
	echo ("Campa&ntilde;a");
      }
      if ($lang == 'en')
      {
        echo (" <b>".$arraycampanas[$i]['nombre_en']."</b>");
      } else {
        echo (" <b>".$arraycampanas[$i]['nombre']."</b>");
      }
//      echo ("</p>");

      echo ("</td><td style=\"text-align: right; color:#6bc78e;\"><i>");
      if ($lang == 'en')
      {
        echo ("<b>".$cuantos_campana."</b> players");
      } else {
        echo ("<b>".$cuantos_campana."</b> jugadores");      	
     	}
      echo ("</i></td></tr></table>");


      echo ("<table class=\"campana_subtitle\">");
      echo ("<tr>");
      echo ("<td colspan=\"2\">");
      echo ("<p><i>");
//      echo (" (".$arraycampanas[$i]['fecha_inicio']." - ".$arraycampanas[$i]['fecha_fin'].")");
      $anyo = substr($arraycampanas[$i]['fecha_fin'], 0, 4);
      $mes = substr($arraycampanas[$i]['fecha_fin'], 5, 2);
      $dia = substr($arraycampanas[$i]['fecha_fin'], 8, 2);
      $hora = substr($arraycampanas[$i]['fecha_fin'], 11, 5);
      if ($lang == 'en')
      {
        echo ("This campaign ends in the ");
        if ($dia == '01') { echo ("1st "); } else {
          if ($dia == '02') { echo ("2nd "); } else {
            if ($dia == '02') { echo ("3rd "); } else {
              echo (abs($dia)."th ");
            }
          }
        }
        echo (" of ".$array_months[(abs($mes))].", ".$anyo);
      } else {
        echo ("Esta campa&ntilde;a termina el ");
        echo (abs($dia)." de ".$array_meses[(abs($mes))]." de ".$anyo);
      }
      echo ("</i></p>");

      echo ("</td>");
      echo ("</tr>");

echo ("</table>");


      echo ("<table class=\"campana_interior\">");
      echo ("<tr>");
      echo ("<td colspan=\"2\">");

      echo ("<p>");
      if ($lang == 'en')
      {
        echo ("<i>".$arraycampanas[$i]['descripcion_en']."</i>");
      } else {
        echo ("<i>".$arraycampanas[$i]['descripcion']."</i>");
      }
      echo ("</p>");

      echo ("</td>");
      echo ("</tr>");
      echo ("<tr>");

      $estajugador = $jugador_campana->EstaCampanaJugador($link_r, $idjugador, $arraycampanas[$i]['id']);

      if ($estajugador == 1)
      {
        echo ("<td width=\"350px\">");
        echo ("<br/>");
//        echo ("<p>");
        echo ("<span style=\"align: left;\">");
        echo ("<a href=\"index.php?catid=3&idcampana=".$arraycampanas[$i]['id']."\"
		class=\"enlace_mov_left\"
		>");
        echo ("<b>");
        if ($lang == 'en')
        {
          echo ("Play this campaign");
        } else {
          echo ("Jugar a esta campa&ntilde;a");
        }
        echo ("</b>");
        echo ("</a>");
        echo ("</span>");
        echo ("</td><td width=\"200px\" style=\"align: right;\">");


        echo ("<br/>");
        echo ("<span style=\"align: right;\">");
        if ($lang == 'en')
        {
          echo ("<a href=\"javascript:ConfirmarEliminarEn('index.php?catid=1&accion=desapuntarse&idcampana=".$arraycampanas[$i]['id']."')\"
		class=\"enlace_mov_left\"
		>");
        } else {
          echo ("<a href=\"javascript:ConfirmarEliminarEs('index.php?catid=1&accion=desapuntarse&idcampana=".$arraycampanas[$i]['id']."')\"
		class=\"enlace_mov_left\"
		>");
        }
        echo ("<b>");
        if ($lang == 'en')
        {
          echo ("Leave this campaign");
        } else {
          echo ("Desapuntarte de esta campa&ntilde;a");
        }
        echo ("</b>");
        echo ("</a>");
        echo ("</span>");
//}
      } else {
//        echo ("<td colspan=\"2\">");
        echo ("<td colspan=\"2\">");
        echo ("<br/>");
        echo ("<p>");
        echo ("<a href=\"index.php?catid=1&accion=apuntarse&idcampana=".$arraycampanas[$i]['id']."\"
		class=\"enlace_mov_left\"
		>");
        echo ("<b>");
        if ($lang == 'en')
        {
          echo ("Join this campaign");
        } else {
          echo ("Apuntarte a esta campa&ntilde;a");
        }
        echo ("</b>");
        echo ("</a>");
        echo ("</p>");
      }
      echo ("</td></tr></table>");
    }

  }
  echo ("<br/>");
  echo ("<br/>");

} else {// Si $idjugador

?>

<br/>
<br/>


<?php

}


//echo ("x");
    echo ("<br/>");

//    MOSTRAMOS LAS ULTIAMS NOTICIAS
    $noticia = new Noticia();
    $array = $noticia->BuscarNoticiasPublicadas($link_r, 5, 0);
    echo ("<table width=\"90%\" class=\"tabla_centro_control\">");
    echo ("<tr>");
    echo ("<td style=\"font-size: 17px;\">");
    echo ("<center>");
    echo ("<b>");
    if ($lang == 'en')
    {
      echo ("Latest news");
    } else {
      echo ("&Uacute;ltimas noticias");
    }
    echo ("</b>");
    echo ("</center>");
    echo ("<br/>");
    echo ("</td>");
    echo ("</tr>");
    for ($i = 1; $i <= count($array); $i++)
    {
//      echo ("<tr>");
      if ($i % 2 == 1)
      {
        echo ("<tr style=\"background-color: #111111; font-size: 13px;\">");
      } else {
        echo ("<tr style=\"background-color: #333333; font-size: 13px;\">");
      }
      echo ("<td style=\"text-align: left;\">");
      echo (substr($array[$i]['fecha'], 0, 10)." - ");
      echo ("<a href=\"index.php?catid=53&lang=".$lang."&idcampana=".$idcampana."&idelemento=".$array[$i]['id']."&accion=comentar\">");
      if ($lang == 'en')
      {
        echo ($array[$i]['titular_en']);
      } else {
        echo ($array[$i]['titular']);
      }
      echo ("</a>");
      echo ("</td>");
      echo ("</tr>");
    }
    echo ("</table>");

/*
Acceso a :
<br/>
<a href="index.php?catid=2">Selecci&oacute;n de campa&ntilde;a</a>
<?php
}
*/


// echo ("<br/>");
// echo ("<br/>");
// echo ("<center>");
//  echo ("<span style=\"font-size: 15px;\">");
// if ($lang == 'en')
// {
//   echo ("Become our friend in <b><a href=\"http://www.uberchar.com\" target=\"_blank\">Uberchar</a></b>, the MMO Social Network!<br/>"); 
//    echo ("<br/>");
//   echo ("Redeem this exclusive closed Beta code at <a href=\"http://www.uberchar.com\" target=\"_blank\">http://www.uberchar.com</a> : <b>E4PYurfrFSVLzuusVU3y</b><br/>");
// } else {
//   echo ("H&aacute;zte nuestro amigo en <b><a href=\"http://www.uberchar.com\" target=\"_blank\">Uberchar</a></b>, la Red Social MMO!<br/>");
//    echo ("<br/>"); 
//   echo ("Redime este c&oacute;digo de Beta exclusivo en <a href=\"http://www.uberchar.com\" target=\"_blank\">http://www.uberchar.com</a> : <b>E4PYurfrFSVLzuusVU3y</b><br/>");
// }
//  echo ("</span>");
 echo ("<br/>");
 echo ("<br/>");
 echo ("</center>");

  

?>

