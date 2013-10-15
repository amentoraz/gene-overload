<?php

//  include("clases/obj_jugador_campana.php");
//  include("clases/obj_informe.php");
//  include("clases/obj_jugador.php");


  $debug = $_REQUEST['debug'];
    if (!is_numeric($debug))
    {
      $debug = 0;
    }


  // **************************************
  //   Lee un informe
  // **************************************

  if ($accion == "leer_reporte")
  {
    $informe = new Informe();
    $idinforme = $_REQUEST['idinforme'];
    if (!is_numeric($idinforme))
    {
      die;
    }
    $idcampana = $_REQUEST['idcampana'];
    if (!is_numeric($idcampana))
    {
      die;
    }

    // Vamos a sacar el siguiente y el anterior
    $idinformeanterior = 0;
    $idinformesiguiente = 0;
    $numelementostotal = $informe->ContarTodos($link_r, $idjugador, $idcampana);
    $array = $informe->BuscarTodos($link_r, $idjugador, $idcampana, 0, 555555555);
    for ($k = 1; $k <= count($array); $k++)
    {
      if ($array[$k]['id'] == $idinforme)
      {
        if ($k > 1) // Hay un informe anterior?
        {
          $idinformeanterior = $array[($k - 1)]['id'];
        }
        if ($k < (count($array))) // Hay un informe anterior?
        {
          $idinformesiguiente = $array[($k + 1)]['id'];
        }
      }
    }
    echo ("<center>");
    if ($idinformeanterior != 0)
    {
      echo ("<a href=\"index.php?catid=".$catid."&idinforme=".$idinformeanterior."&idcampana=".$idcampana."&accion=leer_reporte\">");
      if ($lang == 'en')
      {
        echo ("Previous report");
      } else {
        echo ("Informe anterior");
      }
      echo ("</a>");
    }
    if (($idinformeanterior != 0) && ($idinformesiguiente != 0))
    {
      echo (" - ");
    }
    if ($idinformesiguiente != 0)
    {
      echo ("<a href=\"index.php?catid=".$catid."&idinforme=".$idinformesiguiente."&idcampana=".$idcampana."&accion=leer_reporte\">");
      if ($lang == 'en')
      {
        echo ("Next report");
      } else {
        echo ("Informe siguiente");
      }
      echo ("</a>");
    }
    echo ("</center>");
    if (($idinformeanterior != 0) || ($idinformesiguiente != 0))
    {
      echo ("<br/>");
      echo ("<br/>");
    }
    // FIN DE SACAR SIGUIETNE Y ANTERIOR





    $informe->SacarDatos($link_r, $idinforme);

    if ($informe->PerteneceInforme($link_r, $idinforme, $idjugador))
    {

      if ($informe->leido == 0)
      {
        $informe->leido = 1;
        $informe->GrabarLeido($link_w, $idinforme);
        $informitos = $informitos - 1;
//echo ("#".$informitos."#");
      }

      echo ("<table id=\"tabla_mensaje\" class=\"tabla_mensaje\" width=\"100%\">");
      echo ("<tr style=\"background-color: #111111;\">");
      echo ("<td style=\"text-align: center;\">");
      if ($lang == 'en')
      {
//      echo ("<b>Report subject : </b>");
      } else {
//      echo ("<b>Tema del informe : </b>");
      }
echo ("<b>");
      echo ($informe->subject);
echo ("</b>");
      echo ("</td>");
      echo ("</tr>");

      echo ("<tr style=\"background-color: #111111;\">");
//    echo ("<tr>");
      echo ("<td><i>");
      if ($lang == 'en')
      {
        echo ("Report issued in ".$informe->fecha);
      } else {
        echo ("Informe generado en ".$informe->fecha);
      }
      echo ("</i></td>");
      echo ("</tr>");

      echo ("<tr style=\"background-color: #222222;\">");
//    echo ("<tr style=\"background-color: #333333;\">");
//    echo ("<tr>");
      echo ("<td>");
      echo ("<br/>");
      echo ($informe->texto);
      echo ("<br/>");
      echo ("<br/>");
      echo ("</td>");
      echo ("</tr>");
      echo ("</table>");

    } else {
      if ($lang == 'en')
      {
        echo ("Access denied");
      } else {
        echo ("Acceso denegado");
      }
    }

  }




  // ***********************
  //   Eliminar varios informes
  // ***********************

  if ($accion == 'eliminar_informes')
  {


    $informe = new Informe();

    $idborrar = $_REQUEST['idborrar'];
//    if (!is_numeric($idborrar))
//    {
//      die;
//    }

    // Seran diversos valores para $idborrar
     if (!empty($_REQUEST['idborrar'])) {
//     if (!empty($idborrar)) {
	
//print_r ($_REQUEST['idborrar']);
//echo ("CURRENTLY TESTING. THANKS FOR YOUR PATIENCE");
//die;

       $aLista=array_keys($_REQUEST['idborrar']);
//       $aLista=array_keys($idborrar);
       for ($i = 0 ; $i < count($aLista) ; $i++){

	 if ($informe->PerteneceInforme($link_r, $aLista[$i], $idjugador))
         {

           if (is_numeric($aLista[$i]))
           {
             $informe->EliminarInforme($link_w, $aLista[$i]);
           }

         } else {
           echo ("Access denied");
	   die;
         }

       } // termina el for
       echo ("<br/><p class=\"correctosutil\">");
       if ($lang == 'en')
       {
         echo ("Reports deleted.");
       } else {
         echo ("Informes eliminados.");
       }
       echo ("</p><br/>");

       // Volvemos a averiguar los no leidos
       $informitos = $informe->ContarNoLeidos($link_r, $idjugador, $idcampana);


     } else { // Termina el if not empty
       if ($lang == 'en')
       {
         echo ("You have not selected any report.");
       } else {
         echo ("No has seleccionado ning&uacute;n informe.");
       }
     }
    $accion = null;

  }


  // ***********************
  //   Funcion principal
  // ***********************


  if ($accion == null)
  {

//       for (i = 0; i < document.form_informes.length; i++)
//       {   		
//	      document.form_informes..checked = true ;
//	    } theForm.length
// 	&& theForm[z].name != 'checkall')
// alert (document.form_informes.length);
	
     ?>
     <script>
     function checkAll()
     { 	 	
     	 for(z=0; z < document.form_informes.length ;z++)
     	 {
        if(document.form_informes[z].type == 'checkbox')
        {
	       document.form_informes[z].checked = true;
	     }
	    }
	  }

function uncheckAll(field)
{
       for(z=0; z < document.form_informes.length ;z++)
     	 {
        if(document.form_informes[z].type == 'checkbox')
        {
	       document.form_informes[z].checked = false;
	     }
	    }

}
 </script>
 
     <?php

    $informe = new Informe();

    $limitelementos = 15;
    $pg = $_REQUEST['pg'];
    if (!is_numeric($pg))
    {
      $pg = 1;
    }

    if ($pg == null) { $pg = 1; }
    $offset = (($pg - 1) * $limitelementos);

    $numelementostotal = $informe->ContarTodos($link_r, $idjugador, $idcampana);
    $array = $informe->BuscarTodos($link_r, $idjugador, $idcampana, $offset, $limitelementos);


   echo '<div class="caja-text-long corner-top">';
   if ($lang == 'en')
   {
     echo '<p class="textonormal left" style="width: 150px"><b>Total:</b> '.$numelementostotal.' reports.</p>';
   } else {
     echo '<p class="textonormal left" style="width: 150px"><b>Total:</b> '.$numelementostotal.' informes.</p>';
   }
//   echo '<p class="textonormal left" style="width: 150px">Total: </p>';
//   echo '<p class="textonormal left" style="margin-left: 10px;"><b>'.$numelementostotal.' informes</b></p>';
   echo '<p class="clear-both"></p>';
   echo '</div>';
   echo ("<br/>");





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

    if ( ($offset + $limitelementos) < $numelementostotal) {
//      if ($pgf == 1) { echo ("<span class=\"paginado\"> - </span>"); }
      $pgsig = $pg + 1;
      echo ("<a class=\"paginado\" href=\"".$PATHBASE_exterior."index.php?catid=".$catid."&idcampana=".$idcampana."&pg=".$pgsig."\">");
      //P&aacute;gina siguiente</a>");
      echo ("<img src=\"img/arrow_right.gif\" style=\"vertical-align: middle;\">");
      echo ("</a>");
    }

    } // if $totpg > 1 (sino no imprime nada)
    // ------------------------------> Paginado <--------------------------------------




    ?>
      <form method="post" action="index.php" name="form_informes">

    <?php
      if ($lang == 'en')
      {
    ?>
		<input type="button" name="CheckAll" value="Check All" onClick="checkAll()">
		<input type="button" name="CheckAll" value="Uncheck All" onClick="uncheckAll()">
    <?php
      } else {
    ?>	
    	<input type="button" name="CheckAll" value="Seleccionar todos" onClick="checkAll()">
		<input type="button" name="CheckAll" value="Seleccionar ninguno" onClick="uncheckAll()">	
    <?php
      } 
    ?>		
		
		

       <input type="hidden" name="catid" value="<?php echo $catid;?>">
       <input type="hidden" name="idcampana" value="<?php echo $idcampana;?>">
       <input type="hidden" name="accion" value="eliminar_informes">

    <?php


    echo ("<div id=\"espacio\" class=\"espacio\">");
    echo ("</div>");

     echo ("<table id=\"tabla_informe\" class=\"tabla_informe\">");
      echo ("<tr>");
      echo ("<th width=\"40px\"></th>");
      if ($lang == 'en')
      {
        echo ("<th width=\"250px\">Date & Time</th>");
        echo ("<th width=\"450px\">Subject</th>");
      } else {
        echo ("<th width=\"200px\">Fecha y hora</th>");
        echo ("<th width=\"450px\">Asunto</th>");
      }
    echo ("</tr>");
    echo ("<tr>");



    if ($numelementostotal > 0)
    {

      $jugador_tmz = new Jugador();
      $jugador_tmz->SacarDatos($link_r, $idjugador);
      $id_tmz = $jugador_tmz->id_tmz;
      $tmz = new TMZ();
      $tmz->SacarDatos($link_r, $id_tmz);
      $hora_servidor = -6;
      $min_servidor = 0;
      $diferencia_hora = $tmz->tmz_hour - $hora_servidor;
      $diferencia_min = $tmz->tmz_min - $min_servidor;

      $mostrar = ($numelementostotal - $offset);
      if ($mostrar > $limitelementos){ $mostrar = $limitelementos; }

      // Vamos a mostrar las cosas
      for ($i = 1; $i <= $mostrar; $i++)
      {

        if ($i % 2 == 1)
        {
          echo ("<tr style=\"background-color: #111111;\">");
        } else {
          echo ("<tr style=\"background-color: #222222;\">");
        }



      // Eliminar
      echo ("<td width=\"40px\">");
      echo ("<input type=\"checkbox\" name=\"idborrar[".$array[$i]['id']."]\" value=\"".$array[$i]['id']."\">");
      echo ("</td>");

        // Sacamos la fecha
        echo ("<td>");
        $anyo = substr($array[$i]['fecha'], 0, 4);
        $mes = substr($array[$i]['fecha'], 5, 2);
        $dia = substr($array[$i]['fecha'], 8, 2);
        $hora = substr($array[$i]['fecha'], 11, 2);
        $min = substr($array[$i]['fecha'], 14, 2);
        // Ajustamos la fecha con el TMZ
        $hora = $hora + $diferencia_hora;
        $min = $min + $diferencia_min;
        $arrayf = AjustarFecha($anyo, $mes, $dia, $hora, $min);
        $anyo = $arrayf['anyo']; $mes = $arrayf['mes']; $dia = $arrayf['dia']; $hora = $arrayf['hora']; $min = $arrayf['min'];

        if ($lang == 'en')
        {
          if ($dia == '01') { echo ("1st "); } else {
            if ($dia == '02') { echo ("2nd "); } else {
              if ($dia == '02') { echo ("3rd "); } else {
                echo (abs($dia)."th ");
              }
            }
          }
          echo (" of ".$array_months[(abs($mes))].", ".$anyo.", ".$hora.":".$min);
        } else {
          echo (abs($dia)." de ".$array_meses[(abs($mes))]." de ".$anyo.", ".$hora.":".$min);
        }
        echo ("</td>");
        echo ("<td style=\"text-align: left\"> &nbsp;&nbsp;");
        echo ("<a ");
//        if ($i % 2 == 1) { echo (" style=\"background-color: #111111;\" ");
//        			} else {
//        				 echo (" style=\"background-color: #222222;\" ");
//        			}
        echo ("
        		href=\"index.php?catid=".$catid."&idinforme=".$array[$i]['id']."&idcampana=".$idcampana."&accion=leer_reporte\">");
        if ($array[$i]['leido'] == 0)
        {
          echo ("<b>".$array[$i]['subject']."</b>");
        } else {
          echo ($array[$i]['subject']);
        }
        echo ("</a>");
        echo ("</td>");
        echo ("</tr>");
      }


     // Rellenamos para que no se vea tan feo
     for ($i = 1; $i <= ($limitelementos - $mostrar); $i++)
     {
        if ($i % 2 == 1)
        {
          echo ("<tr style=\"background-color: #111111;\">");
        } else {
          echo ("<tr style=\"background-color: #222222;\">");
        }
	echo ("<td colspan=\"3\"></td>");
	echo ("</tr>");
//	echo ("<tr>");
//	echo ("<td colspan=\"3\"></td>");
//	echo ("</tr>");
     }

	?>





	</table>

        <script>
        $('table.tabla_informe tr').hover(function(){
          $(this).find('td').addClass('hovered');
        }, function(){
          $(this).find('td').removeClass('hovered');
        });
        </script>




	<br/>
        <?php
          if ($lang == 'en')
          {
          ?>
	    <input type="submit" value="Delete">
          <?php
          } else {
          ?>
	    <input type="submit" value="Borrar">
          <?php
          }
          ?>
	</form>
	<?php

    } else {

     // Rellenamos para que no se vea tan feo
     for ($i = 1; $i <= 10; $i++)
     {
        if ($i % 2 == 1)
        {
          echo ("<tr style=\"background-color: #111111;\">");
        } else {
          echo ("<tr style=\"background-color: #222222;\">");
        }
	echo ("<td colspan=\"3\"></td>");
	echo ("</tr>");
	echo ("<tr>");
	echo ("<td colspan=\"3\"></td>");
	echo ("</tr>");
     }


      echo ("<p class=\"errorsutil\">");
      if ($lang == 'en')
      {
        echo ("There are no unread reports.");
      } else {
        echo ("No hay reportes sin leer.");
      }
      echo ("<p>");
      echo ("<br/>");
      echo ("<br/>");
      echo ("</table>");
      echo ("<br/>");
    }

  }


?>
