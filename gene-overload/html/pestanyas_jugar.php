<div id="header" style="width: 100%!important;">

<ul id="cabecera">
<?php

  $informe2 = new Informe();
  $cuantos = $informe2->ContarNoLeidos($link_r, $idjugador, $idcampana);

  $mensajes2 = new Mensajes_Personales();
  $cuantos_m = $mensajes2->ContarElementosNoLeidos($link_r, $idjugador);

//  echo ("<hr/>");

  if ($catid == 3)
  {
    echo ("<li id=\"current\">");
  } else {
    echo ("<li>");
  }
  echo ("<a href=\"index.php?catid=3&idcampana=".$idcampana."\">");
  echo ("<span>");
  if ($lang == 'en')
  {
    echo ("Command center");
  } else {
    echo ("Centro de mando");
  }
  echo ("</span>");
  echo ("</a>");
  echo ("</li>");

  echo ("<div style=\"width: 3px; float: left;\">&nbsp;</div>");
//  echo ("&nbsp;");


  if ($catid == 4)
  {
    echo ("<li id=\"current\">");
  } else {
    echo ("<li>");
  }
  echo ("<a href=\"index.php?catid=4&idcampana=".$idcampana."\">");
//  echo ("<span>");
  if ($lang == 'en')
  {
    echo ("Shop");
  } else {
    echo ("Tienda");
  }
//  echo ("</span>");
  echo ("</a>");
  echo ("</li>");
  echo ("<div style=\"width: 3px; float: left;\">&nbsp;</div>");


  if ($catid == 9)
  {
    echo ("<li id=\"current\">");
  } else {
    echo ("<li>");
  }
  echo ("<a href=\"index.php?catid=9&idcampana=".$idcampana."\">");
  if ($lang == 'en')
  {
    echo ("Team");
  } else {
    echo ("Equipo");
  }

  if ($tengoclan == true)
  {
    $contar_solicitudes_clan = $miclan->Contar_Solicitudes($miclan->id, $idjugador);
    if ($contar_solicitudes_clan > 0)
    {
      echo ("(".$contar_solicitudes_clan.")");
    }
  }

  echo ("</a>");

  echo ("</li>");
  echo ("<div style=\"width: 3px; float: left;\">&nbsp;</div>");



  if ($catid == 6)
  {
    echo ("<li id=\"current\">");
  } else {
    echo ("<li>");
  }
  echo ("<a href=\"index.php?catid=6&idcampana=".$idcampana."\">");
  if ($lang == 'en')
  {
    echo ("Ranking");
  } else {
    echo ("Ranking");
  }
  echo ("</a>");
  echo ("</li>");
  echo ("<div style=\"width:3px; float: left;\">&nbsp;</div>");


  if ($catid == 5)
  {
    echo ("<li id=\"current\">");
  } else {
    echo ("<li>");
  }
  echo ("<a href=\"index.php?catid=5&idcampana=".$idcampana."\">");
  if ($lang == 'en')
  {
    echo ("Profile");
  } else {
    echo ("Perfil");
  }
  echo ("</a>");
  echo ("</li>");
  echo ("<div style=\"width: 3px; float: left;\">&nbsp;</div>");



  if ($catid == 7)
  {
    echo ("<li id=\"current\">");
  } else {
    echo ("<li>");
  }
  echo ("<a href=\"index.php?catid=7&idcampana=".$idcampana."\">");
  if ($lang == 'en')
  {
    echo ("Reports");
  } else {
    echo ("Informes");
  }
  if ($cuantos > 0)
  {
    $informitos = $cuantos; // Numero de reportes, para actualizar
    echo ("<span id=\"informito\" style=\"color: #f6caad;\" >");
    echo ("(".$cuantos.")");
    echo ("</span>");
  }
  echo ("</a>");

  echo ("</li>");
  echo ("<div style=\"width: 3px; float: left;\">&nbsp;</div>");



  if ($catid == 8)
  {
    echo ("<li id=\"current\">");
  } else {
    echo ("<li>");
  }
  echo ("<a href=\"index.php?catid=8&idcampana=".$idcampana."\">");
  if ($lang == 'en')
  {
    echo ("Messages");
  } else {
    echo ("Mensajes");
  }
  if ($cuantos_m > 0)
  {
    $mensajitos = $cuantos_m; // Numero de reportes, para actualizar
    echo ("<span id=\"mensajito\" style=\"color: #f6caad;\" >");
    echo ("(".$cuantos_m.")");
    echo ("</span>");
  }
  echo ("</a>");

  echo ("</li>");
  
  
  
//  echo ("<div style=\"width: 7px; float: left;\">&nbsp;</div>");






//    echo ("<li>");
//    echo (" [Premium]");
//  echo ("</li>");
  echo ("</ul>");
//  echo ("</div>");

?>



</div>
