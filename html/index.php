<?php

  session_start();



//    <td> style="text-align: center;">

  include ("clases/obj_token.php");
  include ("clases/obj_mensajes_personales.php");
  include ("clases/obj_jugador.php");
  include ("clases/obj_encuesta.php");
  include ("clases/obj_campana.php");
  include ("clases/obj_jugador_campana.php");
  include ("clases/obj_informe.php");
  include ("clases/obj_clan.php");
  include ("clases/obj_log.php");
  include ("clases/obj_tmz.php");
  include ("clases/obj_secure.php");
  $secure = new Secure();

  include ("config/database.php");
  include ("config/values.php");

  include ("functions.php");


  $accion = $secure->Sanitizar($_REQUEST['accion']);

  $idcampana = $secure->Sanitizar($_REQUEST['idcampana']);
  if (!is_numeric($idcampana))
  {
    $idcampana = null;
  }


  $catid = $_REQUEST['catid'];
  if (($catid == null) || ($catid == '')) { $catid = 1; }
    if (!is_numeric($catid))
    {
      $catid = 1;
    }



  function Es_Un_Premium($idjugador, $link_r)
  {
      // ESTO hay que unificarlo
      $string_premium = "SELECT id FROM jugador
			WHERE id = $idjugador
			AND es_premium = 1
			AND fecha_fin_premium > NOW()
			";
//echo $string_premium;
      $query_premium = mysql_query($string_premium, $link_r);
      // Si devuelve 1 fila, $es_premium = 1, y si no = 0
//echo mysql_num_rows($query_premium)."#";
      return mysql_num_rows($query_premium);
  }










// *******************************************************+
//    Cambio de password (propio de perfil pero pasa aqui)
// *******************************************************+

  // **************************************
  //   Cambiar la clave
  // **************************************

  if ($accion == "alterar_clave")
  {

    $jugador = new Jugador();
    $clave = $secure->Sanitizar($_REQUEST['clave']);
    $clave2 = $secure->Sanitizar($_REQUEST['clave2']);
    if ($clave == $clave2)
    {
      if ($clave != '')
      {


        $temp_usuario = $_SESSION['REMOTE_USER'];
        $temp_clave = $_SESSION['REMOTE_PASS'];
        $string ="SELECT * FROM jugador
                        WHERE login = '$temp_usuario'
                        AND clave = '$temp_clave'
                        AND baneado = 0
			AND activado = 1
                        ";
        $query = mysql_query($string, $link_r);
        if (mysql_num_rows($query) > 0)
        {
          $unquery = mysql_fetch_array($query);
          $idjugador = $unquery['id'];
//echo ("!");
          $jugador->clave = $clave;
          $jugador->CambiarClave($link_w, $idjugador);
         // Actualizamos para la sesion o cookies del player
//        $_SESSION['REMOTE_PASS'] = $clave;
//        setcookie("password", $clave, time()+60*60*24*100, "/");
          $_SESSION['REMOTE_PASS'] = md5($clave);
          setcookie("password", md5($clave), time()+60*60*24*100, "/");
        }


        if ($lang == 'en')
        {
          $error = "<p class=\"correctosutil\">Password changed</p>";
        } else {
          $error = "<p class=\"correctosutil\">Clave cambiada</p>";
        }



//echo $_SESSION['REMOTE_PASS']."#".$_COOKIE['password'];


      } else {
        if ($lang == 'en')
        {
          $error = "<p class=\"error\">Error: Password is empty.</p>";
        } else {
          $error = "<p class=\"error\">Error: Las claves est&aacute; vac&iacute;a.</p>";
        }
      }
    } else {
      if ($lang == 'en')
      {
        $error = "<p class=\"error\">Error: Passwords do not match.</p>";
      } else {
        $error = "<p class=\"error\">Error: Las claves no coinciden.</p>";
      }
    }

//    $accion = 'logout';
//    $catid = null;

  }

  // *************************************************************

//echo "<br/>".$_SESSION['REMOTE_PASS']."$".$_COOKIE['password'];







    $s_usuario = $_SESSION['REMOTE_USER'];
    $s_clave = $_SESSION['REMOTE_PASS'];

    $string ="SELECT * FROM jugador
                        WHERE login = '$s_usuario'
                        AND clave = '$s_clave'
			AND baneado = 0
			AND activado = 1
                        ";
    $query = mysql_query($string, $link_r);
    if (mysql_num_rows($query) == 0)
    {
      // No es correcto
      $autenticado = 0;
    } else {
      $autenticado = 1;
      $unquery = mysql_fetch_array($query);
      $idjugador = $unquery['id'];
      $es_admin = $unquery['es_admin'];
      $es_premium = Es_Un_Premium($idjugador, $link_r);
    }


  //  Primero vamos a ver si existe una cookie,
  // y si existe le vamos a logar con ella...


  // Ha querido hacer logout?
  if ($accion == 'logout')
  {
    $_SESSION['REMOTE_USER'] = '';
    $_SESSION['REMOTE_PASS'] = '';

    setcookie("usuario","",time() - 93600);
    setcookie("password","",time() - 93600);
    $autenticado = 0;
  }

  $autenticado_ahora = 0;

  //  Vamos a ver si se ha autenticado aqui, comprobando
  // si tenemos usuario y clave como parametros

  $s_usuario = $secure->Sanitizar($_REQUEST['s_usuario']);
  $s_clave = $secure->Sanitizar($_REQUEST['s_clave']);

  if ($s_usuario && $s_clave)
  {
    // Marcamos para mostrar mensaje
    $autenticado_ahora = 1;
    // Y ahora autenticamos
    $s_clave = md5($s_clave);
    $selectautenticarse = "
                SELECT * FROM jugador
                WHERE login = '$s_usuario'
                AND clave = '$s_clave'
		AND baneado = 0
		AND activado = 1
		";
    $queryautenticarse = mysql_query($selectautenticarse, $link_r);
    if (mysql_num_rows($queryautenticarse) > 0)
    {
      $_SESSION['REMOTE_USER'] = $s_usuario;
      $_SESSION['REMOTE_PASS'] = $s_clave;
      setcookie("usuario", $s_usuario, time()+60*60*24*100, "/");
      setcookie("password", $s_clave, time()+60*60*24*100, "/");
      $autenticado = 1;

      $unquery = mysql_fetch_array($queryautenticarse);
      $idjugador = $unquery['id'];
      $es_admin = $unquery['es_admin'];
      $es_premium = Es_Un_Premium($idjugador, $link_r);
//Verificar_Premium($unquery['es_premium'], $unquery['fecha_fin_premium']);
//      $es_premium = $unquery['es_premium'];

    } else {
      // Si fallo la autenticacion
      $_SESSION['REMOTE_USER'] = '';
      $_SESSION['REMOTE_PASS'] = '';

      setcookie("usuario","",time() - 93600);
      setcookie("password","",time() - 93600);

      $error = 1;
    }
  }

  //  De manera alternativa, vamos a ver si tienen cookies
  // con lo cual tambien les autenticamos

  if (isset($_COOKIE['usuario']) && isset($_COOKIE['password'])
        && !isset($_SESSION['REMOTE_USER']) && !isset($_SESSION['REMOTE_PASS']) 
        )
  {
        $c_usuario = $_COOKIE['usuario'];
        $c_password = $_COOKIE['password'];

        $autenticado_ahora = 1;
        // Y ahora autenticamos
        $queryautenticarse = mysql_query("
                SELECT * FROM jugador
                WHERE login = '$c_usuario'
                AND clave = '$c_password'
		AND activado = 1
                ", $link_r);
        if (mysql_num_rows($queryautenticarse) > 0)
        {
          $_SESSION['REMOTE_USER'] = $c_usuario;
          $_SESSION['REMOTE_PASS'] = $c_password;
          $autenticado = 1;

          $unquery = mysql_fetch_array($queryautenticarse);
          $idjugador = $unquery['id'];
          $es_admin = $unquery['es_admin'];
//          $es_premium = $unquery['es_premium'];
//          $es_premium = Verificar_Premium($unquery['es_premium'], $unquery['fecha_fin_premium']);
          $es_premium = Es_Un_Premium($idjugador, $link_r);
        } else {
          // Si fallo la autenticacion
          $_SESSION['REMOTE_USER'] = '';
          $_SESSION['REMOTE_PASS'] = '';
          $error = 1;
        }

  }

//echo $es_premium;

  // Sacamos el lang del jugador

  $lang = $secure->Sanitizar($_REQUEST['lang']);
  if ($lang == null)
  {
    if ($idjugador != null)
    {
      $jugador_x = new Jugador();
      $lang = $jugador_x->SacarLang($link_r, $idjugador);
    } else {
      $lang = 'en';
    }
  }











// AHORA SI, LA CABECERA

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
   <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
   <meta name="descripcion" content="Gene Overload, the massive multiplayer online ( MMO ) game using Genetic Algorithms from Artificial Intelligence to evolve generations of warriors to fight among each other">
   <meta name="keywords" content="genetic algorithms, MMO, game, artificial intelligence, multiplayer, strategy, evolution, fantasy">
   <title>Gene Overload - MMO - Artificial Intelligence strategy game</title>

    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
    <link href="style/reset.css" rel="stylesheet" type="text/css" />
    <link href="style/main.css" rel="stylesheet" type="text/css" />
    <link href="style/tooltip.css" rel="stylesheet" type="text/css" />

   <script language="javascript" type="text/javascript" src="js/functions.js"></script>

   <script src="js/jquery-1.7.min.js"></script>
   <script src="js/jquery.validate.js"></script>
   <script src="js/jquery.elastic.source.js"></script>


</head>

<body>


<div id="global">

<div id="main">

<!--
<div style="position: relative; width: 100%;">
      <div style="position: relative;" id="content">
         <div style="height: 730px;" id="thecontent">
-->

  <table class="tabla_pagina" height="100%">
  <tr>
  <td colspan="2">

  <div id="para_tabla_login">
<?php





 if ($autenticado == 1)
 {

   //  Actualizamos su ultima visita, "last_login", que va
   // a determinar el dinero que se lleva a diario
   $lselect = "UPDATE jugador
		SET last_login = NOW()
		WHERE id = $idjugador
		";
   $lquery = mysql_query($lselect, $link_w);


//   echo ("<a href=\"index.php\">Ohai</a>");



 } else {

?>

    <table class="tabla_login">
    <tr>
     <td>

     <form method="post" action="index.php">

     <?php
      if ($lang == 'en')
      {
       ?>
       <a href="index.php?catid=<?php echo $catid; ?>&lang=es"><img src="img/flag_spanish.png" style="vertical-align: middle;"></a>
       <?php
        } else {
       ?>
       <a href="index.php?catid=<?php echo $catid; ?>&lang=en"><img src="img/flag_english.png" style="vertical-align: middle;"></a>
       <?php
      }
     ?>

       <a class="titmenu_black" href="index.php?catid=108&lang=<?php echo $lang; ?>"><?php
         if ($lang == 'en')
         {
           echo ("Click to sign up");
         } else {
           echo ("Pulsa para registrarte");
         }
	?></a>
       &nbsp;
       &nbsp;
       &nbsp;
       &nbsp;
       <input type="hidden" name="catid" value="1">

       <span class="titmenu_black">
       <strong><?php
         if ($lang == 'en')
         {
           echo ("Login");
         } else {
           echo ("Usuario");
         }
	?> : </strong>
       <input type="text" name="s_usuario" class="inputstandard_2" size="15">
       &nbsp;&nbsp;&nbsp;
       <strong><?php
         if ($lang == 'en')
         {
           echo ("Password");
         } else {
           echo ("Clave");
         }
	?> : </strong>
       <input type="password" name="s_clave" class="inputstandard_2" size="15">
       &nbsp;&nbsp;&nbsp;
       <input type="hidden" name="recien_autenticado" value="1">

	<?php
         if ($lang == 'en')
         {
	?>
          <input type="submit" value="Sign in">
        <?php
         } else {
	?>
          <input type="submit" value="Entrar">
        <?php
         }
	?>
       &nbsp;
       &nbsp;
       &nbsp;
       &nbsp;
       <a class="titmenu_black" href="index.php?catid=104&lang=<?php echo $lang; ?>"><?php
         if ($lang == 'en')
         {
	   echo ("I forgot my password");
         } else {
           echo ("Olvid&eacute; mi clave");
         }
	?></a>
     </form>

   <?php

  }


  // Insertamos la pagina que sea

  $string = " SELECT fichero, nombre
	FROM pagina_categoria
	WHERE idcategoria = $catid
	";
  $query = mysql_query($string, $link_r);
  $miquery = mysql_fetch_array($query);


  // **********************************************************
  //     Menu superior, logout y datos de campanya si la hay
  // **********************************************************

  if (($idjugador != null) && ($idjugador != '') && ($accion != 'logout'))
  {

    // Sacamos la informacion del clan del jugador
    $miclan = new Clan();
    $miclan->link_r = $link_r;
    $miclan->link_w = $link_w;
//echo $idcampana."#";
    if ($idcampana != null)
    {
      if ($miclan->ObtieneClanJugador($idjugador, $idcampana) == false)
      {
        $tengoclan = false;
      } else {
        $tengoclan = true;
      }
    }


     ?>
    <table class="tabla_login2">
    <tr>
     <td>
     <?php
//    echo ("<table><tr><td align=\"left\">");

    $i_jugador = new Jugador();
    $i_jugador->SacarDatos($link_r, $idjugador);
    echo ("<span style=\"color: #ffbb55; font-weight: normal;\">");
    echo $i_jugador->login; //nombre;
    if (($tengoclan == true) && ($miclan->identificador != null) && ($miclan->identificador != ''))
    {
      echo ("</span>");
      echo ("<span style=\"color: #55ff55\">");
      echo ("[".$miclan->identificador."]");
    }
    echo ("</span>");


    echo ("&nbsp;&nbsp;<a style=\"font-weight: bold;\" class=\"errormenu\" href=\"index.php?accion=logout\">");
    echo ("<img src=\"img/log_out.png\" style=\"vertical-align: middle;\">");
    echo ("</a>&nbsp;&nbsp"); //;-&nbsp;&nbsp;");
    echo ("&nbsp;");
    echo ("&nbsp;");
    echo ("&nbsp;");
    echo ("&nbsp;");

//    if ($idcampana != null)
//    {
    if (($idcampana != null) 
                && !(($catid == 1) && ($accion == 'desapuntarse'))
                && !(($catid == 1) && ($accion == 'apuntarse'))
                )
    {
        $campana_aux = new Campana();
        $jugador_campana_aux = new Jugador_Campana();

        $campana_aux->SacarDatos($link_r, $idcampana);
        echo ("<span style=\"color: #ffbb55\">");
        if ($lang == 'en')
        {
          echo ("<b>Active campaign</b> ".$campana_aux->nombre_en);
        } else {
          echo ("<b>Campa&ntilde;a activa</b> ".$campana_aux->nombre);
        }
        echo ("</span>");

        // SEGUNDA LINEA DE LA PARTE DE ARRIBA

        echo ("<br/>");

        // PRIMERA LINEA DE TORNEO
        echo ("<i>");
        echo ("<span class=\"goldcoin\">");
        // TOMAMOS QUE LA ACTUALIZACION ES A LAS 23:15 HORA LOCAL
        $horanow = date('H');
	$minnow = date('i');
//echo ("".$horanow.":".$minnow." ");    
        $mihora = 23 - $horanow;
        if ($minnow < 15)
        {
          $miminuto = 15 - $minnow;
        } else {
          $miminuto = (60 - $minnow) + 15;
          $mihora = $mihora - 1;
        }
        if ($mihora < 0) { $mihora = $mihora + 24; }
//        $miminuto = 15 - $minnow;
//echo $miminuto."#";
        if ($miminuto < 0) { $miminuto = $miminuto + 60; }

        if ($lang == 'en')
        {
          if ($mihora == 0)
          {
            echo ("Next big tournament in ".$miminuto." minutes");
          } else {
            echo ("Next big tournament in ".$mihora." hours, ".$miminuto." minutes");
          }
        } else {
          if ($mihora == 0)
          {
            echo ("Pr&oacute;ximo gran torneo en ".$miminuto." minutos");
          } else {
            echo ("Pr&oacute;ximo gran torneo en ".$mihora." horas, ".$miminuto." minutos");
          }
        }
        echo ("</i>");
        echo ("</span>");
	// FIN DE MOSTRAR LO QUE QUEDA PARA EL TORNEO


        // SEGUNDA LINEA DE TORNEO
        // Esta es mas jodida porque los de profundidades son L y J, los de bosque M y V, y los de volcan X y S. Todo a las 11:30, 12 horas de diferencia con el grande
        //  Vamos a calcular como antes la distancia, y luego
        // ya veremos lo que toca
        $horanow = date('H');
	$minnow = date('i');
        $mihora = 11 - $horanow;   // Ahora las 11 en vez de las 23
        if ($minnow < 15)
        {
          $miminuto = 15 - $minnow;
        } else {
          $miminuto = (60 - $minnow) + 15;
          $mihora = $mihora - 1;
        }
        if ($mihora < 0) { $mihora = $mihora + 24; }
        if ($miminuto < 0) { $miminuto = $miminuto + 60; }
        // Ahora sabemos cuanto queda, pero:
        //  - Esto no se aplica en domingo (y no basta con comprobar si AHORA es domingo)
        //  - Hay que distinguir que tipo de torneo es el que viene.
        //  Asi que vamos a tomar un timestamp de now
        $momento_torneo = time() + ($miminuto*60) + ($mihora*60*60);
        $dia_semana_torneo = date('w', $momento_torneo);  // De 0 (domingo) a 6 (sabado)
//echo $dia_semana_torneo."#";
        if ($dia_semana_torneo > 0)  // 0 es domingo y no pintamos nada
        {
          switch($dia_semana_torneo)
          {
            case 1:
            case 4:
                $texto_deme = '<span style="color: #3333ff;">profundidades</span>';
                $texto_deme_en = '<span style="color: #3333ff;">abyssal depths</span>';
		break;
            case 2:
            case 5:
                $texto_deme = '<span style="color: #00aa00;">bosque</span>';
                $texto_deme_en = '<span style="color: #00aa00;">forest</span>';
		break;
            case 3:
            case 6:
                $texto_deme = '<span style="color: #aa0000;">volc&aacute;n</span>';
                $texto_deme_en = '<span style="color: #aa0000;">volcano</span>';
		break;
          }
          echo ("<br/>");
          echo ("<i>");
          echo ("<span class=\"goldcoin\">");
          if ($lang == 'en')
          {
            if ($mihora == 0)
            {
              echo ("Next deme tournament (".$texto_deme_en.") in ".$miminuto." minutes");
            } else {
              echo ("Next deme tournament (".$texto_deme_en.") in ".$mihora." hours, ".$miminuto." minutes");
            }
          } else {
            if ($mihora == 0)
            {
             echo ("Pr&oacute;ximo torneo deme (".$texto_deme.") en ".$miminuto." minutos");
            } else {
              echo ("Pr&oacute;ximo torneo deme (".$texto_deme.") en ".$mihora." horas, ".$miminuto." minutos");
            }
          }
        }
        echo ("</i>");
        echo ("</span>");
        // FIN DE MOSTRAR PARA EL TORNEO DE DEME









        echo ("</td><td style=\"text-align: right; padding-right: 15px;\">");



        echo ("&nbsp;");
        echo ("&nbsp;");
        echo ("&nbsp;");

        // Vamos a pintar el token
        $token_up = new Token();
        $token_up->SacarDatos($link_r, $idcampana);
        echo ("<div id=\"div_flag\" style=\"float: left;\">");
        if ($token_up->idjugador == $idjugador)
        {
          echo ("<a href=\"#\"
		class=\"Ntooltip\"
                >");
          echo ("<img src=\"img/flag_captured.png\"  style=\"vertical-align:middle;\">");
          echo ("&nbsp;");
          echo ("&nbsp;");
          echo ("&nbsp;");
          echo ("<span style=\"width: 250px;\">");
          if ($lang == 'en')
          {
             echo ("<table width=\"100%\" class=\"tooltip_interno\"><tr><td>");
             echo ("You currently hold the flag.");
             echo ("</td></tr></table>");
          } else {
             echo ("<table width=\"100%\" class=\"tooltip_interno\"><tr><td>");
             echo ("Eres el due&ntilde;o actual de la bandara.");
             echo ("</td></tr></table>");
          }
          echo ("</span>");
          echo ("</a>");
        } else {
          echo ("&nbsp;");
          echo ("&nbsp;");
          echo ("&nbsp;");
        }
        echo ("&nbsp;");
        echo ("</div>");

        // Ahora vamos a ir con el numero de sexos

        $jugador_campana_aux->SacarDatos($link_r, $idjugador, $idcampana);
        switch ($jugador_campana_aux->num_sexos)
	{
	  case 1:
                echo ("<a href=\"#\"
		class=\"Ntooltip\"
                >");
		echo ("<img src=\"img/minisex1.png\" style=\"vertical-align:middle;\">");
                echo ("<span style=\"width: 150px;\">");
                if ($lang == 'en')
                {
                   echo ("<table width=\"100%\" class=\"tooltip_interno\"><tr><td>");
                   echo ("Asexual reproduction.");
                   echo ("</td></tr></table>");
                } else {
                   echo ("<table width=\"100%\" class=\"tooltip_interno\"><tr><td>");
                   echo ("Reproducci&oacute;n asexual");
                   echo ("</td></tr></table>");
                }
                echo ("</span>");
                echo ("</a>");
		break;
	  case 2:
                echo ("<a href=\"#\"
		class=\"Ntooltipfar\"
                >");
		echo ("<img src=\"img/minisex2.png\" style=\"vertical-align:middle;\">");
                echo ("<span style=\"width: 150px;\">");
                if ($lang == 'en')
                {
                   echo ("<table width=\"100%\" class=\"tooltip_interno\"><tr><td>");
                   echo ("Sexual reproduction.");
                   echo ("</td></tr></table>");
                } else {
                   echo ("<table width=\"100%\" class=\"tooltip_interno\"><tr><td>");
                   echo ("Reproducci&oacute;n sexual");
                   echo ("</td></tr></table>");
                }
                echo ("</span>");
                echo ("</a>");
		break;
	  case 3:
                echo ("<a href=\"#\"
		class=\"Ntooltipfar\"
                >");
		echo ("<img src=\"img/minisex3.png\" style=\"vertical-align:middle;\">");
                echo ("<span style=\"width: 160px;\">");
                if ($lang == 'en')
                {
                   echo ("<table width=\"100%\" class=\"tooltip_interno\"><tr><td>");
                   echo ("Trisexual reproduction.");
                   echo ("</td></tr></table>");
                } else {
                   echo ("<table width=\"100%\" class=\"tooltip_interno\"><tr><td>");
                   echo ("Reproducci&oacute;n trisexual");
                   echo ("</td></tr></table>");
                }
                echo ("</span>");
                echo ("</a>");
		break;
	}

        echo ("&nbsp;");
        echo ("&nbsp;");
        echo ("&nbsp;");
        echo ("&nbsp;");
        echo ("&nbsp;");
        echo ("&nbsp;");

//echo ("<div id=\"dinero\">");
        echo ("<span class=\"goldcoin\" id=\"dinerito\">");
        echo ($jugador_campana_aux->dinero);
        $dinerito = $jugador_campana_aux->dinero;
        echo ("</span>");
//echo ("</div>");
        echo ("&nbsp;");
        echo ("<img src=\"img/goldcoin.gif\" style=\"vertical-align:middle;\">");

        echo ("&nbsp;");
        echo ("&nbsp;");
        echo ("&nbsp;");
        echo ("&nbsp;");
        echo ("&nbsp;");
        echo ("&nbsp;");

        echo ("<span class=\"goldcoin\">");
        echo ($jugador_campana_aux->num_torneos_victorias);
        echo ("</span>");
        echo ("&nbsp;");
        echo ("<img src=\"img/medal_gold.png\" style=\"vertical-align:middle;\">");

        echo ("&nbsp;");
        echo ("&nbsp;");
        echo ("&nbsp;");
        echo ("&nbsp;");
        echo ("&nbsp;");
        echo ("&nbsp;");

        echo ("<span class=\"goldcoin\">");
        echo ($jugador_campana_aux->num_torneos_segundo);
        echo ("</span>");
        echo ("&nbsp;");
        echo ("<img src=\"img/medal_silver.png\" style=\"vertical-align:middle;\">");

        echo ("&nbsp;");
        echo ("&nbsp;");
        echo ("&nbsp;");
        echo ("&nbsp;");
        echo ("&nbsp;");
        echo ("&nbsp;");

        echo ("<span class=\"goldcoin\">");
        echo ($jugador_campana_aux->num_torneos_tercero);
        echo ("</span>");
        echo ("&nbsp;");
        echo ("<img src=\"img/medal_bronze.png\" style=\"vertical-align:middle;\">");

        // SEGUNDA LINEA DE LA PARTE DE ARRIBA

        echo ("<br/>");

        echo ("<i>");
        echo ("<span class=\"goldcoin\">");
 //       2011-03-17 17:39:49 
        // TOMAMOS QUE LA ACTUALIZACION ES A LAS 23:15 HORA LOCAL
        $horanow = date('H');
	$minnow = date('i');
        while($horanow >= 4)
        {
          $horanow = $horanow - 4;
        }
        $diferenciahoras = 4 - $horanow - 1;
        $diferenciamins = 60 - $minnow;
        if (($diferenciamins == 60) && ($diferenciahoras == 3))
        {
          $diferenciamins = 0;
          $diferenciahoras = 4;
        }
        // ################################

        if ($lang == 'en')
        {
          if ($diferenciahoras == 0)
          {
            echo ("Next funding in ".$diferenciamins." minutes");
          } else {
            echo ("Next funding in ".$diferenciahoras." hours, ".$diferenciamins." minutes");
          }
        } else {
          if ($diferenciahoras == 0)
          {
            echo ("Pr&oacute;xima financiaci&oacute;n en ".$diferenciamins." minutos");
          } else {
            echo ("Pr&oacute;xima financiaci&oacute;n en ".$diferenciahoras." horas, ".$diferenciamins." minutos");
          }
        }
        echo ("</i>");
        echo ("&nbsp;");
        echo ("<span class=\"goldcoin\" id=\"flag_dinero\">");
        if ($token_up->idjugador == $idjugador)
        {
          echo ("(<span class=\"correcto\">18</span>");
        } else {
          echo ("(6");
        }
        echo ("<img src=\"img/goldcoin.gif\" style=\"vertical-align:middle;\">)");
        echo ("</span>");


    }

//    echo ("</td></tr></table>");



  }

  ?>

     </td>
    </tr>
   </table>
  </div>



  <div id="espacio" class="espacio">
  </div>

  <?php

  // Aqui termina la zona de menu superior sin pestanyas, es decir, de autenticacion y logout


?>


  </td>
  </tr>
  <tr>
  <td width="200px" style="vertical-align: top;">


<?php

    include ("menu_izquierda.php");


  echo ("</td>");
  echo ("<td width=\"750px\" valign=\"top\">");




    echo ("<div id=\"para_tabla_central\">");

//  if (($idcampana != null) && ($idjugador != null))
    if (($idcampana != null)
                && ($idjugador != null)
                && !(($catid == 1) && ($accion == 'desapuntarse'))
                && !(($catid == 1) && ($accion == 'apuntarse'))
                )
    {

//  {
    ?>


    <?php
//      <table class="tabla_pestanyas" id="tabla_pestanyas">
//      <tr><td style="text-align: center;">
    include("pestanyas_jugar.php");
//      </td></tr></table>
    ?>
    <?php
  }



  echo ("<table id=\"tabla_central\" class=\"tabla_central\">");
  echo ("<tr><td>");
    if (($catid == 3) || ($catid == 4) || ($catid == 5) || ($catid == 6) || ($catid == 7) || ($catid == 8) || ($catid == 9))
    {
      if (  (($idcampana != '') && ($idcampana != null)) &&
            (($idjugador != '') && ($idjugador != null))
         )
      {
        $estajugador = $jugador_campana_aux->EstaCampanaJugador($link_r, $idjugador, $idcampana);
        if ($estajugador == 1)
        {
          include ($miquery['fichero']);
        } else {
          if ($lang == 'en')
          {
            echo ("<p class=\"error\">You are noy playing this campaign</p>");
          } else {
            echo ("<p class=\"error\">No est&aacute;s apuntado a esta campa&ntilde;a</p>");
          }
        }
      } else {
//        die;
      }
    } else {
      if ($miquery['fichero'] != null)
      {
        include ($miquery['fichero']);
      }
    }
  echo ("</td></tr></table>");
  echo ("</div>");

  echo ("</td>");


?>

</tr>
</table>

<?php

/*

*/


      echo ("<div id=\"espacio\" class=\"espacio\">");
      echo ("</div>");


if ($catid == 1)
{
?>

<table style="margin-left: auto;
                margin-right: auto;
                background-color: #000000;
       filter:alpha(opacity=8);
        -moz-opacity:0.8;
        -khtml-opacity: 0.8;
        opacity: 0.8;
                ">
<tr><td width="300px">
<div id="fb-root"></div><script src="http://connect.facebook.net/en_US/all.js#xfbml=1"></script><fb:like-box href="http://www.facebook.com/pages/Gene-Overload/227670527266699" width="292" show_faces="false" border_color="" stream="false" header="true"></fb:like-box>

</td>
</tr>
</table>
<?php
}



      include ("pie.php");
?>



<!--
  </div>
-->

</div>
</div>

<!--
<div style="position: relative; width: 100%;">
      <div style="position: absolute; left: 0px; right: 33%; bottom: 0px; top: 0px; background-color: blue; width: 33%;" id="navbar">nav bar</div>
      <div style="position: relative; left: 33%; width: 66%; background-color: yellow;" id="content">
         <div style="height: 768px;" id="thecontent"></div>
      </div>
</div>
-->



<script type="text/javascript">
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-263785-8']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
</script>



<!-- Con esto ajustamos el dinero si hubo alguna compra -->
<script>
document.getElementById('dinerito').innerHTML = <?php echo $dinerito; ?>;
</script>

<!-- Con esto ajustamos los informes -->
<?php

  if ($informitos > 0)
  {
  ?>
   <script>
    document.getElementById('informito').innerHTML = '<?php echo ("(".$informitos.")"); ?>';
   </script>
  <?php
  } else {
  ?>
   <script>
    document.getElementById('informito').innerHTML = '';
   </script>
  <?php
  }


  if ($mensajitos > 0)
  {
  ?>
   <script>
    document.getElementById('mensajito').innerHTML = '<?php echo ("(".$mensajitos.")"); ?>';
   </script>
  <?php
  } else {
  ?>
   <script>
    document.getElementById('mensajito').innerHTML = '';
   </script>
  <?php
  }
  ?>


<script>
$(".enlace_mov_left").hover(
  function(){
    $(this).animate({"margin-left": "+=10px"}, "fast");
  },
  function(){
    $(this).animate({"margin-left": "-=10px"}, "fast");
  }
);

$(".enlace_mov_right").hover(
  function(){
    $(this).animate({"margin-right": "+=10px"}, "fast");
  },
  function(){
    $(this).animate({"margin-right": "-=10px"}, "fast");
  }
);
</script>

<?php
//        resultado_bis('ajax_formularios.php?accion=comprobar_bandera&idcampana=PHP echo $idcampana; PHP', 'div_flag');
//        resultado('ajax_formularios.php?accion=comprobar_bandera_pasta&idcampana=PHP echo $idcampana; PHP', 'flag_dinero');
//        resultado('ajax_formularios.php?accion=comprobar_jugador&idjugador=PHP echo $idjugador; PHP&idcampana=PHP echo $idcampana; PHP', 'div_opciones');
//	  async: false,
?>

<script>
      function comprobar_bandera1()
      {
        $.ajax({
          type: 'GET',
          url: 'ajax_formularios.php',
          data: 'accion=comprobar_bandera&idcampana=<?php echo $idcampana; ?>',
                  success: function(html) {
                        document.getElementById('div_flag').innerHTML = html;
                  },
        });
      }
      setInterval('comprobar_bandera1()', 4190);
</script>

<script>
      function comprobar_bandera_pasta()
      {
        $.ajax({
          type: 'GET',
          url: 'ajax_formularios.php',
          data: 'accion=comprobar_bandera_pasta&idcampana=<?php echo $idcampana; ?>',
                  success: function(html) {
                        document.getElementById('flag_dinero').innerHTML = html;
                  },

        });
      }
      setInterval('comprobar_bandera_pasta()', 4350);
</script>


<?php
if ($catid == 3)
{
?>
 <script>
      function comprobar_bandera2()
      {
        $.ajax({
          type: 'GET',
          url: 'ajax_formularios.php',
          data: 'accion=comprobar_jugador&idcampana=<?php echo $idcampana; ?>',
                  success: function(html) {
                        document.getElementById('div_opciones').innerHTML = html;
                  },

        });
      }
      setInterval('comprobar_bandera2()', 2110);
 </script>
<?php
}
?>



</body>
</html>
<?php

?>
