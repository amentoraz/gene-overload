<?php

    echo ("<div id=\"atacar\"
                style=\"display: none;
                \"
                >
                ");


//        resultado(datos, destino);
//          error: alert('error'),
//alert(datos+' [testing, thank you for your patience]');

//alert(html);

?>
    <script>
      function atacar(datos, parametros, destino)
      {
        dinerito = document.getElementById('dinerito').innerHTML;
        dinerito = dinerito - 1;
        document.getElementById('dinerito').innerHTML = dinerito;
	$.ajax({
	  type: 'GET',
	  url: datos,
	  data: parametros,
	  success: function(html) {
 			document.getElementById('div_ataque').innerHTML = html;
		},
	});
      }

    </script>

<?php



    // Navegacion
   if ($lang == 'en')
   {
     PintarNavegacion('atacar', 'centro_mando', '', 'Main command center', '');
   } else {
     PintarNavegacion('atacar', 'centro_mando', '', 'Centro de mando principal', '');
   }

   echo ("<br/>");
   echo ("<p style=\"color: #ffdb00; font-size: 13px;\">");
   if ($lang == 'en')
   {
     echo ("<b>Capture the Flag</b>");
   } else {
     echo ("<b>Capturar la Bandera</b>");
   }
   echo ("</p>");

   echo ("<br/>");
   $token = new Token();
   $token->SacarDatos($link_r, $idcampana);
   $idjugadorbandera = $token->idjugador;
   $jugador_bandera = new Jugador();
   $jugador_bandera->SacarDatos($link_r, $idjugadorbandera);
   $jugador_campana->SacarDatos($link_r, $idjugador, $idcampana);


   echo ("<div id=\"div_opciones\">");
   // Ahora tenemos en $token->idjugador quien es el jugador que tiene el token
   if ($lang == 'en')
   {
//     echo ("<p style=\"color: #ffdb00; font-size: 13px;\">");
//     echo ("In Capture the Flag mode, one player holds the flag. If you beat its best specimen you will hold it. When you hold the flag, you earn triple funding.");
//     echo ("</p>");
     echo ("<p style=\"color: #ffdb00; font-size: 13px;\">");
     if ($idjugadorbandera == $idjugador)
     {
       echo ("The flag is currently held by you!");
       echo ("</p>");
     } else {
       echo ("The flag is currently held by ");
       echo ("<a href=\"index.php?catid=5&accion=ver&idcampana=".$idcampana."&idelemento=".$idjugadorbandera."\" target=\"_blank\">");
       echo $jugador_bandera->login;
       echo ("</a>.");
       echo ("</p>");

       echo ("<br/><br/>");
       if ($jugador_campana->dinero > 0)
       {
         echo ("<a href=\"javascript:atacar('ajax_formularios.php', 'accion=atacar&idcampana=".$idcampana."','div_ataque')\">");
         ?>

          <img src="img/espadas1.gif"
                onmouseover="javascript:this.src='img/espadas2.gif';" 
                onmouseout="javascript:this.src='img/espadas1.gif';"
          >
          Click to attack the current flag owner (cost: 1<img src="img/goldcoin.gif" style="align: middle;">)
         <?php
         echo ("</a>");
         echo ("</p>");
       } else {
         echo ("You need 1 <img src=\"img/goldcoin.gif\" style=\"align: middle;\"> to fight for the flag");
         echo ("</p>");
       }
     }
   } else {
//     echo ("<p style=\"color: #ffdb00; font-size: 13px;\">");
//     echo ("En el modo de Capturar la Bandera, un jugador tiene la bandera. Si vences contra su mejor especimen la obtendr&aacute;s. Cuando tienes la bandera, ganas el triple de financiaci&oacute;n.");
     echo ("<p style=\"color: #ffdb00; font-size: 13px;\">");
     if ($idjugadorbandera == $idjugador)
     {
       echo ("&iexcl;La bandera ahora la tienes t&uacute;!");
       echo ("</p>");
     } else {
       if ($lang == 'en')
       {
         echo ("The flag is currently held by ");
       } else {
         echo ("La bandera la tiene ahora ");
       }
       echo ("<a href=\"index.php?catid=5&accion=ver&idcampana=".$idcampana."&idelemento=".$idjugadorbandera."\" target=\"_blank\">");
       echo $jugador_bandera->login;
       echo ("</a>.");
       echo ("</p>");
       echo ("<br/><br/>");
//       echo ("<a href=\"javascript:atacar('ajax_formularios.php?accion=atacar&idcampana=".$idcampana."&idjugador=".$idjugador."','div_ataque')\">");
       if ($jugador_campana->dinero > 0)
       {
         echo ("<a href=\"javascript:atacar('ajax_formularios.php', 'accion=atacar&idcampana=".$idcampana."','div_ataque')\">");
         ?>

          <img src="img/espadas1.gif"
                  onmouseover="javascript:this.src='img/espadas2.gif';" 
                  onmouseout="javascript:this.src='img/espadas1.gif';"
          >
	  <?php
          if ($lang == 'en')
          {
            ?>
            Click to attack the current flag owner (cost: 1<img src="img/goldcoin.gif" style="align: middle;">)
            <?php
          } else {
            ?>
            Click para atacar al actual due&ntilde;o de la bandera (coste: 1<img src="img/goldcoin.gif" style="align: middle;">)
            <?php
          }
          ?>
         <?php
         echo ("</a>");
       } else {
         if ($lang == 'en')
         {
           echo ("You need 1 <img src=\"img/goldcoin.gif\" style=\"align: middle;\"> to fight for the flag");
         } else {
           echo ("Necesitas 1 <img src=\"img/goldcoin.gif\" style=\"align: middle;\"> para luchar por la bandera");
         }
         echo ("</p>");
       }
     }
   }
   echo ("</div>");

   echo ("<br/>");
   echo ("<br/>");
   echo ("<div id=\"div_ataque\">");
   echo ("</div>");



   // Te informa de tus estadisticas
   echo ("<br/>");
   echo ("<br/>");
   $segundos = $jugador_campana->segundos_con_bandera;
   // Si es el poseedor actual, deberia sumarlo tambien
   if ($idjugadorbandera == $idjugador)
   {
     $segundos = $segundos + $token->CalcularSegundos($link_r, $idcampana);
   }
   if ($segundos > 0)
   {
     echo ("<p style=\"color: #55ff55; font-size: 13px;\"><i>");

         if ($segundos > 60)
         {
           $minutos = ($segundos - ($segundos % 60)) / 60;
           $segundos = $segundos % 60;
           if ($minutos > 60)
           {
             $horas = ($minutos - ($minutos % 60)) / 60;
             $minutos = $minutos % 60;
             if ($horas > 24)
             {
               $dias = ($horas - ($horas % 24)) / 24;
               $horas = $horas % 24;
               $sec_mostrar = ($dias."d ".$horas."h ".$minutos."' ".$segundos."''");
             } else {
               $sec_mostrar = ($horas."h ".$minutos."' ".$segundos."''");
             } 
           } else {
             $sec_mostrar =  ($minutos."' ".$segundos."''");
           }
         } else {
           $secmostrar = ($segundos."''");
         }


     if ($idjugadorbandera == $idjugador)
     {
       if ($lang == 'en')
       {
         echo ("You've held the flag for an overall of <span id=\"segundos\">".$sec_mostrar."</span> ");
       } else {
         echo ("Has sostenido la bandera durante un total de <span id=\"segundos\">".$sec_mostrar."</span> ");
       }
     } else {
       if ($lang == 'en')
       {
//         echo ("You've held the flag for ".$sec_mostrar." seconds.");
         echo ("You've held the flag for an overall of ".$sec_mostrar."");
       } else {
         echo ("Has sostenido la bandera durante un total de ".$sec_mostrar."");
       }
     }


     //  Aqui tiene que haber un javascript que cada segundo incremente la cantidad, PERO
     // que cuando se ha perdido la bandera sea frenado
//       <script>
//         function incrementar_segundos()
//         {
//            document.getElementById('segundos').innerHTML =  document.getElementById('segundos').innerHTML++;
//         }
//         setInterval('incrementar_segundos()', 1000);

   } else {
     echo ("<p style=\"color: #ff5555; font-size: 13px;\"><i>");
     if ($lang == 'en')
     {
       echo ("You've never held the flag.");
     } else {
       echo ("Nunca has sostenido la bandera.");
     }
   }
   echo ("</i></p>");


 echo ("</div>");

?>

