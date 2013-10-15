<?php

  session_start();



//    <td> style="text-align: center;">

  include ("clases/obj_jugador.php");
  include ("clases/obj_campana.php");
  include ("clases/obj_jugador_campana.php");
  include ("clases/obj_informe.php");
  include ("clases/obj_clan.php");
  include ("clases/obj_log.php");
  include ("clases/obj_secure.php");
  $secure = new Secure();

  include ("config/database.php");
  include ("config/values.php");


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

        $jugador->clave = $clave;
        $jugador->CambiarClave($link_w, $idjugador);


        if ($lang == 'en')
        {
          $error = "<p class=\"correctosutil\">Password changed</p>";
        } else {
          $error = "<p class=\"correctosutil\">Clave cambiada</p>";
        }

        // Actualizamos para la sesion o cookies del player
//        $_SESSION['REMOTE_PASS'] = $clave;
//        setcookie("password", $clave, time()+60*60*24*100, "/");
        $_SESSION['REMOTE_PASS'] = md5($clave);
        setcookie("password", md5($clave), time()+60*60*24*100, "/");


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


</head>

<body>


<?php
die;

?>
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

       <a class="titmenu_black" href="index.php?catid=100&lang=<?php echo $lang; ?>"><?php
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

        echo ("</td><td style=\"text-align: right; padding-right: 15px;\">");



        echo ("&nbsp;");
        echo ("&nbsp;");
        echo ("&nbsp;");
        echo ("&nbsp;");
        echo ("&nbsp;");
        echo ("&nbsp;");

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

        echo ("<span class=\"goldcoin\">");
        echo ($jugador_campana_aux->dinero);
        echo ("</span>");
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




</body>
</html>
<?php

?>
