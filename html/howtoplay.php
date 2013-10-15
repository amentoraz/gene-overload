<?php

  include ('clases/obj_texto_web.php');

  $texto_web = new Texto_Web();
  $texto_web->Sacar_Datos_Cat($link_r, $catid);



     if ($lang == 'en')
     {
       echo ("<center>");
       ?>
         <p style="font-size: 13px; color: #f5b798; font-weight: normal;"><i>"Look deep into Nature, and then you will understand everything better". [Albert Einstein]
                </i></p>
       <?php
       echo ("<br/>");
     } else {
       echo ("<center>");
       ?>
         <p style="font-size: 13px; color: #f5b798; font-weight: normal;"><i>"Mira profundamente dentro de la Naturaleza, y lo entender&aacute;s todo mejor". [Albert Einstein]
                </i></p>
       <?php
       echo ("<br/>");
     }
     echo ("</b></p>");
     echo ("</center>");


  if ($lang == 'en')
  {
    echo stripslashes($texto_web->texto_en);
  } else {
    echo stripslashes($texto_web->texto);
  }

  echo ("<br/>");

  // ********************************************
  // Esto es el ir a las proximas paginas:
  if ($catid == 64) // Your first ten minutest
  {
    echo("<table width=\"100%\"><tr>");
    echo("<td style=\"text-align: right; padding-right: 15px;\">");
    echo("<br/><i>");
    if ($lang == 'en')
    {
      echo("Next section: ");
      echo("<a href=\"index.php?catid=60&idcampana=".$idcampana."&lang=".$lang."\">");
      echo("The Command Center");
      echo("</a>");
    } else {
      echo("Secci&oacute;n siguiente: ");
      echo("<a href=\"index.php?catid=60&idcampana=".$idcampana."&lang=".$lang."\">");
      echo("El Centro de Mando");
      echo("</a>");
    }
    echo ("</i>");
    echo ("</td></tr></table>");
  }
  // ********************************************
  if ($catid == 60) // El centro de mando
  {
    echo ("<br/>");
    echo ("<table width=\"100%\"><tr>");
    echo ("<td>");
    echo ("<i>");
    if ($lang == 'en')
    {
      echo("Previous section: ");
      echo("<a href=\"index.php?catid=64&idcampana=".$idcampana."&lang=".$lang."\">");
      echo("Your first 10 minutes");
      echo("</a>");
      echo("</td><td style=\"text-align: right; padding-right: 15px;\">");
      echo("<i>");
      echo("Next section: ");
      echo("<a href=\"index.php?catid=61&idcampana=".$idcampana."&lang=".$lang."\">");
      echo("Combat");
      echo("</a>");
      echo("</i>");
    } else {
      echo("Secci&oacute;n anterior: ");
      echo("<a href=\"index.php?catid=64&idcampana=".$idcampana."&lang=".$lang."\">");
      echo("Tus primeros 10 minutos");
      echo("</a>");
      echo("</td><td style=\"text-align: right; padding-right: 15px;\">");
      echo("<i>");
      echo("Secci&oacute;n siguiente: ");
      echo("<a href=\"index.php?catid=61&idcampana=".$idcampana."&lang=".$lang."\">");
      echo("Combate");
      echo("</a>");
      echo("</i>");
    }
    echo("</i>");
    echo("</td></tr></table>");
  }
  // ********************************************
  if ($catid == 61) // combate
  {
    echo ("<br/>");
    echo ("<table width=\"100%\"><tr>");
    echo ("<td>");
    echo ("<i>");
    if ($lang == 'en')
    {
      echo("Previous section: ");
      echo("<a href=\"index.php?catid=60&idcampana=".$idcampana."&lang=".$lang."\">");
      echo("The command center");
      echo("</a>");
      echo("</td><td style=\"text-align: right; padding-right: 15px;\">");
      echo("<i>");
      echo("Next section: ");
      echo("<a href=\"index.php?catid=65&idcampana=".$idcampana."&lang=".$lang."\">");
      echo("Special abilities");
      echo("</a>");
      echo("</i>");
    } else {
      echo("Secci&oacute;n anterior: ");
      echo("<a href=\"index.php?catid=60&idcampana=".$idcampana."&lang=".$lang."\">");
      echo("El centro de mando");
      echo("</a>");
      echo("</td><td style=\"text-align: right; padding-right: 15px;\">");
      echo("<i>");
      echo("Secci&oacute;n siguiente: ");
      echo("<a href=\"index.php?catid=65&idcampana=".$idcampana."&lang=".$lang."\">");
      echo("Habilidades especiales");
      echo("</a>");
      echo("</i>");
    }
    echo("</i>");
    echo("</td></tr></table>");
  }
  // ********************************************
  if ($catid == 65) // Habilidades especiales
  {
    echo ("<br/>");
    echo ("<table width=\"100%\"><tr>");
    echo ("<td>");
    echo ("<i>");
    if ($lang == 'en')
    {
      echo("Previous section: ");
      echo("<a href=\"index.php?catid=61&idcampana=".$idcampana."&lang=".$lang."\">");
      echo("Combat");
      echo("</a>");
      echo("</td><td style=\"text-align: right; padding-right: 15px;\">");
      echo("<i>");
      echo("Next section: ");
      echo("<a href=\"index.php?catid=62&idcampana=".$idcampana."&lang=".$lang."\">");
      echo("The shop");
      echo("</a>");
      echo("</i>");
    } else {
      echo("Secci&oacute;n anterior: ");
      echo("<a href=\"index.php?catid=61&idcampana=".$idcampana."&lang=".$lang."\">");
      echo("Combate");
      echo("</a>");
      echo("</td><td style=\"text-align: right; padding-right: 15px;\">");
      echo("<i>");
      echo("Secci&oacute;n siguiente: ");
      echo("<a href=\"index.php?catid=62&idcampana=".$idcampana."&lang=".$lang."\">");
      echo("La tienda");
      echo("</a>");
      echo("</i>");
    }
    echo("</i>");
    echo("</td></tr></table>");
  }
  // ********************************************
  // ********************************************
  if ($catid == 62) // La tienda
  {
    echo ("<br/>");
    echo ("<table width=\"100%\"><tr>");
    echo ("<td>");
    echo ("<i>");
    if ($lang == 'en')
    {
      echo("Previous section: ");
      echo("<a href=\"index.php?catid=65&idcampana=".$idcampana."&lang=".$lang."\">");
      echo("Special abilities");
      echo("</a>");
      echo("</td><td style=\"text-align: right; padding-right: 15px;\">");
      echo("<i>");
      echo("Next section: ");
      echo("<a href=\"index.php?catid=63&idcampana=".$idcampana."&lang=".$lang."\">");
      echo("Teams");
      echo("</a>");
      echo("</i>");
    } else {
      echo("Secci&oacute;n anterior: ");
      echo("<a href=\"index.php?catid=65&idcampana=".$idcampana."&lang=".$lang."\">");
      echo("Habilidades especiales");
      echo("</a>");
      echo("</td><td style=\"text-align: right; padding-right: 15px;\">");
      echo("<i>");
      echo("Secci&oacute;n siguiente: ");
      echo("<a href=\"index.php?catid=63&idcampana=".$idcampana."&lang=".$lang."\">");
      echo("Equipos");
      echo("</a>");
      echo("</i>");
    }
    echo("</i>");
    echo("</td></tr></table>");
  }
  // ********************************************
  // ********************************************
  if ($catid == 63) // Equipos
  {
    echo ("<br/>");
    echo ("<table width=\"100%\"><tr>");
    echo ("<td>");
    echo ("<i>");
    if ($lang == 'en')
    {
      echo("Previous section: ");
      echo("<a href=\"index.php?catid=62&idcampana=".$idcampana."&lang=".$lang."\">");
      echo("The Shop");
      echo("</a>");
      echo("</td><td style=\"text-align: right; padding-right: 15px;\">");
      echo("<i>");
      echo("Next section: ");
      echo("<a href=\"index.php?catid=66&idcampana=".$idcampana."&lang=".$lang."\">");
      echo("Evolution");
      echo("</a>");
      echo("</i>");
    } else {
      echo("Secci&oacute;n anterior: ");
      echo("<a href=\"index.php?catid=62&idcampana=".$idcampana."&lang=".$lang."\">");
      echo("La tienda");
      echo("</a>");
      echo("</td><td style=\"text-align: right; padding-right: 15px;\">");
      echo("<i>");
      echo("Secci&oacute;n siguiente: ");
      echo("<a href=\"index.php?catid=66&idcampana=".$idcampana."&lang=".$lang."\">");
      echo("Evoluci&oacute;n");
      echo("</a>");
      echo("</i>");
    }
    echo("</i>");
    echo("</td></tr></table>");
  }
  // ********************************************
  // ********************************************
  if ($catid == 66) // Evolucion
  {
    echo ("<br/>");
    echo ("<table width=\"100%\"><tr>");
    echo ("<td>");
    echo ("<i>");
    if ($lang == 'en')
    {
      echo("Previous section: ");
      echo("<a href=\"index.php?catid=63&idcampana=".$idcampana."&lang=".$lang."\">");
      echo("Teams");
      echo("</a>");
      echo("</td><td style=\"text-align: right; padding-right: 15px;\">");
      echo("<i>");
      echo("Next section: ");
      echo("<a href=\"index.php?catid=67&idcampana=".$idcampana."&lang=".$lang."\">");
      echo("Entropy");
      echo("</a>");
      echo("</i>");
    } else {
      echo("Secci&oacute;n anterior: ");
      echo("<a href=\"index.php?catid=63&idcampana=".$idcampana."&lang=".$lang."\">");
      echo("Equipos");
      echo("</a>");
      echo("</td><td style=\"text-align: right; padding-right: 15px;\">");
      echo("<i>");
      echo("Secci&oacute;n siguiente: ");
      echo("<a href=\"index.php?catid=67&idcampana=".$idcampana."&lang=".$lang."\">");
      echo("Entrop&iacute;a");
      echo("</a>");
      echo("</i>");
    }
    echo("</i>");
    echo("</td></tr></table>");
  }
  // ********************************************
  if ($catid == 67) // Entropy
  {
    echo ("<br/>");
    echo ("<table width=\"100%\"><tr>");
    echo ("<td>");
    echo ("<i>");
    if ($lang == 'en')
    {
      echo("Previous section: ");
      echo("<a href=\"index.php?catid=66&idcampana=".$idcampana."&lang=".$lang."\">");
      echo("Evolution");
      echo("</a>");
    } else {
      echo("Secci&oacute;n anterior: ");
      echo("<a href=\"index.php?catid=66&idcampana=".$idcampana."&lang=".$lang."\">");
      echo("Evoluci&oacute;n");
      echo("</a>");
    }
    echo("</i>");
    echo("</td></tr></table>");
  }
  // ********************************************

  if ($catid == 50)
  {
    if ($lang == 'en')
    {
	?>
	   <br/>
	   <p> - <a href="index.php?catid=64&lang=<?php echo $lang; ?>&idcampana=<?php echo $idcampana; ?>">Your first 10 minutes</a></p>
	   <br/>
	   <p> - <a href="index.php?catid=60&lang=<?php echo $lang; ?>&idcampana=<?php echo $idcampana; ?>">The Command Center</a></p>
	   <br/>
	   <p> - <a href="index.php?catid=61&lang=<?php echo $lang; ?>&idcampana=<?php echo $idcampana; ?>">Combat</a></p>
	   <br/>
	   <p> - <a href="index.php?catid=65&lang=<?php echo $lang; ?>&idcampana=<?php echo $idcampana; ?>">Special abilities</a></p>
	   <br/>
	   <p> - <a href="index.php?catid=62&lang=<?php echo $lang; ?>&idcampana=<?php echo $idcampana; ?>">The Shop</a></p>
	   <br/>
	   <p> - <a href="index.php?catid=63&lang=<?php echo $lang; ?>&idcampana=<?php echo $idcampana; ?>">Teams</a></p>
	   <br/>
	   <br/>
	   <p><b>Advanced topics</b></p>
	   <br/>
	   <p> - <a href="index.php?catid=66&lang=<?php echo $lang; ?>&idcampana=<?php echo $idcampana; ?>">Evolution</a></p>
	   <br/>
	   <p> - <a href="index.php?catid=67&lang=<?php echo $lang; ?>&idcampana=<?php echo $idcampana; ?>">Entropy</a></p>
	   <br/>
	   <p> - <a href="index.php?catid=68&lang=<?php echo $lang; ?>&idcampana=<?php echo $idcampana; ?>">Capture the Flag</a></p>
	   <br/>
        <?php
    } else {
	?>
	   <br/>
	   <p> - <a href="index.php?catid=64&lang=<?php echo $lang; ?>&idcampana=<?php echo $idcampana; ?>">Tus primeros 10 minutos</a></p>
	   <br/>
	   <p> - <a href="index.php?catid=60&lang=<?php echo $lang; ?>&idcampana=<?php echo $idcampana; ?>">El Centro de Mando</a></p>
	   <br/>
	   <p> - <a href="index.php?catid=61&lang=<?php echo $lang; ?>&idcampana=<?php echo $idcampana; ?>">Combate</a></p>
	   <br/>
	   <p> - <a href="index.php?catid=65&lang=<?php echo $lang; ?>&idcampana=<?php echo $idcampana; ?>">Habilidades especiales</a></p>
	   <br/>
	   <p> - <a href="index.php?catid=62&lang=<?php echo $lang; ?>&idcampana=<?php echo $idcampana; ?>">La Tienda</a></p>
	   <br/>
	   <p> - <a href="index.php?catid=63&lang=<?php echo $lang; ?>&idcampana=<?php echo $idcampana; ?>">Equipos</a></p>
	   <br/>
	   <br/>
	   <p><b>Conceptos avanzados</b></p>
	   <br/>
	   <p> - <a href="index.php?catid=66&lang=<?php echo $lang; ?>&idcampana=<?php echo $idcampana; ?>">Evoluci&oacute;n</a></p>
	   <br/>
	   <p> - <a href="index.php?catid=67&lang=<?php echo $lang; ?>&idcampana=<?php echo $idcampana; ?>">Entrop&iacute;a</a></p>
	   <br/>
	   <p> - <a href="index.php?catid=68&lang=<?php echo $lang; ?>&idcampana=<?php echo $idcampana; ?>">Capturar la Bandera</a></p>
	   <br/>
        <?php
    }

  }

?>
