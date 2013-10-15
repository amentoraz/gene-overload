<?php

class Informe
{

	var $id;
	var $tipo;
	var $subject;
	var $texto;
	var $idjugador;
	var $leido;


   var $textoganadores_es;
   var $textoganadores_en;   

  // $tipo =
  //   1 - subvencion
  //   5 - invitacion
  //   6 - aceptacion de solicitud


  function EliminarAntiguos($link_w)
  {
    $string = "DELETE FROM informe
		WHERE fecha < DATE_SUB(NOW(), INTERVAL 30 DAY)
		AND subject NOT LIKE 'Specimen wins gold medal%'
                AND subject NOT LIKE 'Specimen wins silver medal%'
                AND subject NOT LIKE 'Specimen wins bronze medal%'
                AND subject NOT LIKE 'Especimen consigue medalla de%'
		";
    $query = mysql_query($string, $link_w);
  }


  // **********************************************
  //   Elimina todos los informes de un usuario
  // **********************************************

  function EliminarInformesJugador($link_w, $idjugador, $idcampana)
  {
    $string = "DELETE FROM informe
		WHERE idjugador = $idjugador
		AND idcampana = $idcampana
		";
    $query = mysql_query($string, $link_w);
  }

  // **********************************************
  //   Elimina un informe
  // **********************************************

  function EliminarInforme($link_w, $idinforme)
  {
    $string = "DELETE FROM informe
		WHERE id = $idinforme
		";
    $query = mysql_query($string, $link_w);
  }


  // **********************************************
  //   Devuelve true si el informe pertenece a $idjugador
  // **********************************************

  function PerteneceInforme($link_r, $idinforme, $idjugador)
  {
    $string = "SELECT id FROM informe
		WHERE id = $idinforme
		AND idjugador = $idjugador
		";
    $query = mysql_query($string, $link_r);
    if ($unquery = mysql_fetch_array($query))
    {
      return true;
    } else {
      return false;
    }
  }

  // **********************************************
  //   Crea un informe con los datos de la clase
  // **********************************************

  function EnviarInformeRaw($link_w, $idjugador, $idcampana)
  {
    $string = "INSERT INTO informe
		(tipo, subject, texto, idjugador, leido, fecha, idcampana)
		VALUES
		($this->tipo, '$this->subject', '$this->texto', $idjugador, 0, NOW(), $idcampana)
		";
//echo $string;
    $query = mysql_query($string, $link_w);
  }


  // **********************************************
  //  notifica que se ha enviado el dinero de subvencion
  // **********************************************

  function EnviarSubvencionUno($link_w, $idcampana, $cantidad, $idjugador, $calidad)
  {
    switch ($calidad)
    {
      case 3:
		$cal_en = "outstanding";
		$cal_es = "fant&aacute;stico";
		break;
      case 2:
		$cal_en = "good";
		$cal_es = "buen";
		break;
      case 1:
		$cal_en = "normal";
		$cal_es = "normal";
		break;
      case 0:
		$cal_en = "poor";
		$cal_es = "pobre";
		break;
    }

    $select = "SELECT a.id, a.lang
		FROM jugador a, jugador_campana b
		WHERE b.idcampana = $idcampana
		AND b.idjugador = a.id
		AND a.id = $idjugador
		";
//echo $select."#";
    $query = mysql_query($select, $link_w);
    if ($unquery = mysql_fetch_array($query))
    {
      $lenguaje = $unquery['lang'];
      $idjugador = $unquery['id'];
      if ($lenguaje == 'en')
      {
        $subject = "Subsidy granted by the Scientific Council";
        $texto = "You have received ".$cantidad." credits as a subsidy for your ".$cal_en." work in the Genetic Engineering field.";
      } else {
        $subject = "Subvenci&oacute;n del Consejo Cient&iacute;fico recibida";
        $texto = "Has recibido ".$cantidad." cr&eacute;ditos como subsidio por tu ".$cal_es." trabajo en el campo de la Ingenier&iacute;a Gen&eacute;tica.";
      }
      $string2 = "INSERT INTO
                informe
                (tipo, subject, texto, idjugador, leido, fecha, idcampana)
                VALUES
                (1, '$subject', '$texto', $idjugador, 0, NOW(), $idcampana )
                ";
//echo $string;
      $query2 = mysql_query($string2, $link_w);
    }

  }

  // **********************************************
  //  notifica que se ha enviado el dinero de subvencion
  // **********************************************

  function EnviarSubvencionTodos($link_w, $idcampana, $cantidad)
  {

    $select = "SELECT a.id, a.lang
		FROM jugador a, jugador_campana b
		WHERE b.idcampana = $idcampana
		AND b.idjugador = a.id
		";
//echo $select."#";
    $query = mysql_query($select, $link_w);
    while ($unquery = mysql_fetch_array($query))
    {
      $lenguaje = $unquery['lang'];
      $idjugador = $unquery['id'];
      if ($lenguaje == 'en')
      {
        $subject = "Subsidy granted by the Scientific Council";
        $texto = "You have received ".$cantidad." credits as a subsidy for your outstanding work in the Genetic Engineering field.";
      } else {
        $subject = "Subvenci&oacute;n del Consejo Cient&iacute;fico recibida";
        $texto = "Has recibido ".$cantidad." cr&eacute;ditos como subsidio por tu fant&aacute;stico trabajo en el campo de la Ingenier&iacute;a Gen&eacute;tica.";
      }
      $string2 = "INSERT INTO
                informe
                (tipo, subject, texto, idjugador, leido, fecha, idcampana)
                VALUES
                (1, '$subject', '$texto', $idjugador, 0, NOW(), $idcampana )
                ";
//echo $string;
      $query2 = mysql_query($string2, $link_w);
    }

  }


  // **********************************************
  //  notifica que se ha enviado el dinero de subvencion
  // **********************************************

  function EnviarSubvencionEspecial($link_w, $idcampana, $cantidad)
  {

    $select = "SELECT a.id, a.lang
		FROM jugador a, jugador_campana b
		WHERE b.idcampana = $idcampana
		AND b.idjugador = a.id
		";
echo $select."#";
    $query = mysql_query($select, $link_w);
    while ($unquery = mysql_fetch_array($query))
    {
echo ("#anyade#");
      $lenguaje = $unquery['lang'];
      $idjugador = $unquery['id'];
      if ($lenguaje == 'en')
      {
        $subject = "Subsidy granted by the Scientific Council";
        $texto = "You have received ".$cantidad." credits as a special subsidy from the Scientific Council.<br/><br/>";
        $texto = $texto." This has been granted in order to help you evolve your specimens now that a new technology ";
        $texto = $texto."which makes slots much cheaper has just been developed. So, go to the Shop and multiply your slots!!!";
      } else {
        $subject = "Subvenci&oacute;n del Consejo Cient&iacute;fico recibida";
        $texto = "Has recibido ".$cantidad." cr&eacute;ditos como subsidio especial del Consejo Cient&iacute;fico.<br/><br/>";
        $texto = $texto." Esta subvenci&oacute;n se ha concedido para ayudar a que evoluciones a tus espec&iacute;menes ahora que una nueva tecnolog&iacute;a ";
	$texto = $texto."ha abaratado en gran medida los huecos de deme. As&iacute; que, &iexcl;Ve a la Tienda y multiplica tus huecos!";
      }
echo $texto;
      $string2 = "INSERT INTO
                informe
                (tipo, subject, texto, idjugador, leido, fecha, idcampana)
                VALUES
                (1, '$subject', '$texto', $idjugador, 0, NOW(), $idcampana )
                ";
//echo $string;
      $query2 = mysql_query($string2, $link_w);
    }

  }

  // *********************************************
  //   Grabar el informe como leido
  // *********************************************

  function GrabarLeido($link_w, $idinforme)
  {
    $string = "UPDATE informe
		SET leido = $this->leido
		WHERE id = $idinforme
		";
//echo $string;
    $query = mysql_query($string, $link_w);
  }


  // *********************************************
  //   Sacar los datos
  // *********************************************

  function SacarDatos($link_r, $idinforme)
  {
    $string = "SELECT
		id, tipo, subject, texto, idjugador, leido, fecha
		FROM informe
		WHERE id = $idinforme
		";
    $query = mysql_query($string, $link_r);
    if ($unquery = mysql_fetch_array($query))
    {
      $this->id = $unquery['id'];
      $this->tipo = $unquery['tipo'];
      $this->subject = $unquery['subject'];
      $this->texto = $unquery['texto'];
      $this->idjugador = $unquery['idjugador'];
      $this->leido = $unquery['leido'];
      $this->fecha = $unquery['fecha'];
    }

  }


  // *********************************************
  //   Cuenta los informes
  // *********************************************

  function ContarTodos($link_r, $idjugador, $idcampana)
  {
    $string = "SELECT
		id, tipo, subject, texto, idjugador, leido, fecha
		FROM informe
		WHERE idjugador = $idjugador
		AND idcampana = $idcampana
		";
    $query = mysql_query($string, $link_r);
    return mysql_num_rows($query);
  }


  // *********************************************
  //   Busca todos los informes
  // *********************************************

  function BuscarTodos($link_r, $idjugador, $idcampana, $offset, $limit)
  {
    $string = "SELECT
		id, tipo, subject, texto, idjugador, leido, fecha, idcampana
		FROM informe
		WHERE idjugador = $idjugador
		AND idcampana = $idcampana
		ORDER BY fecha DESC
		LIMIT $limit OFFSET $offset
		";
//echo $string;
    $query = mysql_query($string, $link_r);
    $i = 0;
    while ($unquery = mysql_fetch_array($query))
    {
      $i++;
      $array[$i]['id'] = $unquery['id'];
      $array[$i]['idcampana'] = $unquery['idcampana'];
      $array[$i]['tipo'] = $unquery['tipo'];
      $array[$i]['subject'] = $unquery['subject'];
      $array[$i]['texto'] = $unquery['texto'];
      $array[$i]['idjugador'] = $unquery['idjugador'];
      $array[$i]['leido'] = $unquery['leido'];
      $array[$i]['fecha'] = $unquery['fecha'];
    }
    return $array;
  }


  // *********************************************
  //   Busca los informes que no hayas leido
  // *********************************************

  function ContarNoLeidos($link_r, $idjugador, $idcampana)
  {
    $string = "SELECT
		id
		FROM informe
		WHERE idjugador = $idjugador
		AND idcampana = $idcampana
		AND leido != 1
		";
    $query = mysql_query($string, $link_r);
    return mysql_num_rows($query);
/*
    $i = 0;
    while ($unquery = mysql_fetch_array($query))
    {
      $i++;
      $array[$i]['id'] = $unquery['id'];
      $array[$i]['tipo'] = $unquery['tipo'];
      $array[$i]['subject'] = $unquery['subject'];
      $array[$i]['texto'] = $unquery['texto'];
      $array[$i]['idjugador'] = $unquery['idjugador'];
      $array[$i]['leido'] = $unquery['leido'];
      $array[$i]['fecha'] = $unquery['fecha'];
    }
    return $array;
*/
  }



  // *********************************************
  //    Genera un informe para un jugador
  //   sobre su derrota en un torneo
  // *********************************************

  function GenerarInformeDerrotaTorneo($link_w, $idjugador, $idcampana, $iddeme, $idslot, $puntuacion, $tipo, $umbral)
  {
    $select = "SELECT a.id, a.lang
                FROM jugador a, jugador_campana b
                WHERE b.idcampana = $idcampana
                AND b.idjugador = a.id
		AND a.id = $idjugador
                ";
echo ("<br/>".$select);
    $query = mysql_query($select, $link_w);
    if ($unquery = mysql_fetch_array($query)) { $lang = $unquery['lang']; }

    if ($lang == 'en')
    {
      // El tipo es 0 para todos los demes, y 1 2 y 3 para los otros
      switch ($tipo)
      {
        case 0: $subject = "Specimen eliminated from a tournament"; break;
        case 1: $subject = "Specimen eliminated from an abyssal deme tournament"; break;
        case 2: $subject = "Specimen eliminated from a forest deme tournament"; break;
        case 3: $subject = "Specimen eliminated from a volcano deme tournament"; break;
      }
      $texto = "Your specimen in slot ".$idslot." ";
      switch ($iddeme)
      {
	    case 1: $texto = $texto."from the Abyssal depths deme "; break;
	    case 2: $texto = $texto."from the Forest deme "; break;
	    case 3: $texto = $texto."from the Volcano deme "; break;
      }
      $texto = $texto."has participated in a tournament, and ended up eliminated in the knockout round, making a total of ".$puntuacion." points, and the minimum needed to pass were ".$umbral." points.";
    } else {
      switch ($tipo)
      {
        case 0: $subject = "Especimen eliminado de un torneo"; break;
        case 1: $subject = "Especimen eliminado de un torneo del deme de las profundidades"; break;
        case 2: $subject = "Especimen eliminado de un torneo del deme del bosque"; break;
        case 3: $subject = "Especimen eliminado de un torneo del deme del volc&aacute;n"; break;
      }
      $texto = "Tu especimen en el hueco ".$idslot." ";
      switch ($iddeme)
      {
	    case 1: $texto = $texto."del deme de las profundidades "; break;
	    case 2: $texto = $texto."del deme del bosque "; break;
	    case 3: $texto = $texto."del deme del volc&aacute;n "; break;
      }
      $texto = $texto."ha participado en un torneo, y acab&oacute; eliminado en la ronda eliminatoria, obteniendo un total de ".$puntuacion." puntos, y el m&iacute;nimo necesario para pasar fueron ".$umbral." puntos.";
    }

    $texto = $texto."<br/>";
    $texto = $texto."<br/>";

    if ($lang == 'en')
    {
    	$texto = $texto.$this->textoganadores_en;
    } else {
    	$texto = $texto.$this->textoganadores_es;
    }
    $texto = $texto."<br/>";
    $texto = $texto."<br/>";



    $string = "INSERT INTO
		informe
		(tipo, subject, texto, idjugador, leido, fecha, idcampana)
		VALUES
		(1, '$subject', '$texto', $idjugador, 0, NOW(), $idcampana)
		";
echo ("<br/>");
echo $string;
    $query = mysql_query($string, $link_w);


  }



  // *********************************************
  //    Genera un informe para un jugador
  //   sobre su participacion en un torneo
  // *********************************************

  function GenerarInformeTorneo($link_w, $idcampana, $idjugador, $posicion, $dinero, $iddeme, $idslot, $victorias, $empates, $derrotas, $puntuacion, $puntuacion_antigua, $umbral)
  {
    $select = "SELECT a.id, a.lang
                FROM jugador a, jugador_campana b
                WHERE b.idcampana = $idcampana
                AND b.idjugador = a.id
		AND a.id = $idjugador
                ";
echo ("<br/>".$select);
    $query = mysql_query($select, $link_w);
    if ($unquery = mysql_fetch_array($query)) { $lang = $unquery['lang']; }


    switch ($posicion)
    {
      case 1:
                if ($lang == 'en')
                {
                  $subject = "Specimen wins gold medal in a tournament";
                  $texto = "Your specimen in slot ".$idslot." ";
                  switch ($iddeme)
                  {
		    case 1: $texto = $texto."from the Abyssal depths deme "; break;
		    case 2: $texto = $texto."from the Forest deme "; break;
		    case 3: $texto = $texto."from the Volcano deme "; break;
                  }
                  $texto = $texto."has participated in a tournament, and ended up in the 1st position.";
                } else {
                  $subject = "Especimen consigue medalla de oro en un torneo";
                  $texto = "Tu especimen en el hueco ".$idslot." ";
                  switch ($iddeme)
                  {
		    case 1: $texto = $texto."del Deme de las Profundidades "; break;
		    case 2: $texto = $texto."del Deme del Bosque "; break;
		    case 3: $texto = $texto."del Deme del Volc&aacute;n "; break;
                  }
                  $texto = $texto."ha participado en un torneo, y ha obtenido la primera posici&oacute;n.";
                }
		break;
      case 2:
                if ($lang == 'en')
                {
                  $subject = "Specimen wins silver medal in a tournament";
                  $texto = "Your specimen in slot ".$idslot." ";
                  switch ($iddeme)
                  {
		    case 1: $texto = $texto."from the Abyssal depths deme "; break;
		    case 2: $texto = $texto."from the Forest deme "; break;
		    case 3: $texto = $texto."from the Volcano deme "; break;
                  }
                  $texto = $texto."has participated in a tournament, and ended up in the 2nd position.";
                } else {
                  $subject = "Especimen consigue medalla de plata en un torneo";
                  $texto = "Tu especimen en el hueco ".$idslot." ";
                  switch ($iddeme)
                  {
		    case 1: $texto = $texto."del Deme de las Profundidades "; break;
		    case 2: $texto = $texto."del Deme del Bosque "; break;
		    case 3: $texto = $texto."del Deme del Volc&aacute;n "; break;
                  }
                  $texto = $texto."ha participado en un torneo, y ha obtenido la segunda posici&oacute;n.";
                }
		break;
      case 3:
                if ($lang == 'en')
                {
                  $subject = "Specimen wins bronze medal in a tournament";
                  $texto = "Your specimen in slot ".$idslot." ";
                  switch ($iddeme)
                  {
		    case 1: $texto = $texto."from the Abyssal depths deme "; break;
		    case 2: $texto = $texto."from the Forest deme "; break;
		    case 3: $texto = $texto."from the Volcano deme "; break;
                  }
                  $texto = $texto."has participated in a tournament, and ended up in the 3rd position.";
                } else {
                  $subject = "Especimen consigue medalla de bronce en un torneo";
                  $texto = "Tu especimen en el hueco ".$idslot." ";
                  switch ($iddeme)
                  {
		    case 1: $texto = $texto."del Deme de las Profundidades "; break;
		    case 2: $texto = $texto."del Deme del Bosque "; break;
		    case 3: $texto = $texto."del Deme del Volc&aacute;n "; break;
                  }
                  $texto = $texto."ha participado en un torneo, y ha obtenido la tercera posici&oacute;n.";
                }
		break;
      case 4:
      case 5:
      case 6:
      case 7:
                if ($lang == 'en')
                {
                  $subject = "Specimen has a noteworthy participation in a tournament";
                  $texto = "Your specimen in slot ".$idslot." ";
                  switch ($iddeme)
                  {
		    case 1: $texto = $texto."from the Abyssal depths deme "; break;
		    case 2: $texto = $texto."from the Forest deme "; break;
		    case 3: $texto = $texto."from the Volcano deme "; break;
                  }
                  $texto = $texto."has participated in a tournament, and ended up in the position number ".$posicion.".";
                } else {
                  $subject = "Especimen tiene una participaci&oacute;n notable en un torneo";
                  $texto = "Tu especimen en el hueco ".$idslot." ";
                  switch ($iddeme)
                  {
		    case 1: $texto = $texto."del Deme de las Profundidades "; break;
		    case 2: $texto = $texto."del Deme del Bosque "; break;
		    case 3: $texto = $texto."del Deme del Volc&aacute;n "; break;
                  }
                  $texto = $texto."ha participado en un torneo, y ha quedado en la posici&oacute;n ".$posicion.".";
                }
		break;
      default:
                if ($lang == 'en')
                {
                  $subject = "Specimen participates in a tournament";
                  $texto = "Your specimen in slot ".$idslot." ";
                  switch ($iddeme)
                  {
		    case 1: $texto = $texto."from the Abyssal depths deme "; break;
		    case 2: $texto = $texto."from the Forest deme "; break;
		    case 3: $texto = $texto."from the Volcano deme "; break;
                  }
                  $texto = $texto."has participated in a tournament, and ended up in the position number ".$posicion.".";
                } else {
                  $subject = "Especimen participa en un torneo";
                  $texto = "Tu especimen en el hueco ".$idslot." ";
                  switch ($iddeme)
                  {
		    case 1: $texto = $texto."del Deme de las Profundidades "; break;
		    case 2: $texto = $texto."del Deme del Bosque "; break;
		    case 3: $texto = $texto."del Deme del Volc&aacute;n "; break;
                  }
                  $texto = $texto."ha participado en un torneo, y ha quedado en la posici&oacute;n ".$posicion.".";
                }
		break;
    }

    $total_combates = $victorias + $empates + $derrotas;
    $texto = $texto."<br/>";
    $texto = $texto."<br/>";
    if ($lang == 'en')
    {
      $texto = $texto." After a knockout round it survived, this warrior fought a total of <b>".$total_combates."</b> combats. It won <b>".$victorias."</b>, lost <b>".$derrotas."</b>, and another <b>".$empates."</b> ended up in a tie.";
      $texto = $texto." The score in this final round was <b>".$puntuacion."</b> points.";
      if (($puntuacion_antigua != null) && ($puntuacion_antigua != ''))
      {
        $texto = $texto." The specimen went through the knockout round with <b>".$puntuacion_antigua."</b> points in such round, ";
        $texto = $texto."while the minimum score to qualify for the final round was <b>".$umbral."</b> points. ";
      }
    } else {
      $texto = $texto." Despu&eacute;s de una ronda eliminatoria a la que sobrevivi&oacute;, este guerrero ha luchado en un total de <b>".$total_combates."</b> combates. Ha ganado en <b>".$victorias."</b> ocasiones, ha perdido en <b>".$derrotas."</b>, y ha empatado <b>".$empates."</b> veces.";
      $texto = $texto." La puntuaci&oacute;n en esta ronda final fue de <b>".$puntuacion."</b> puntos.";
      if (($puntuacion_antigua != null) && ($puntuacion_antigua != ''))
      {
        $texto = $texto." El especimen atraves&oacute; la ronda eliminatoria con <b>".$puntuacion_antigua."</b> puntos en esa ronda, ";
        $texto = $texto."mientras que la puntuaci&oacute;n m&iacute;nima para pasar a la ronda final fue de <b>".$umbral."</b> puntos. ";
      }
    }
    $texto = $texto."<br/>";
    $texto = $texto."<br/>";

    if ($lang == 'en')
    {
    	$texto = $texto.$this->textoganadores_en;  
    } else {
    	$texto = $texto.$this->textoganadores_es;  
    }
    $texto = $texto."<br/>";
    $texto = $texto."<br/>";


    if ($dinero > 0)
    {
      if ($lang == 'en')
      {
        $texto = $texto." For this result in the tournament, you have received ".$dinero." credits as a prize.";
        $texto = $texto."<br/><br/>";
        $texto = $texto.$textojugadores_en;
      } else {
        $texto = $texto." Por este resultado en el torneo, has recibido ".$dinero." cr&eacute;ditos como premio.";
        $texto = $texto."<br/><br/>";
        $texto = $texto.$textojugadores_es;
      }
    }
echo ("<br/>".$subject."->".$texto);
    $string = "INSERT INTO
		informe
		(tipo, subject, texto, idjugador, leido, fecha, idcampana)
		VALUES
		(1, '$subject', '$texto', $idjugador, 0, NOW(), $idcampana)
		";
echo ("<br/>");
echo $string;
    $query = mysql_query($string, $link_w);

  }








  // *********************************************
  //    Genera un informe para un jugador
  //   sobre su participacion en un torneo
  // *********************************************

  function GenerarInformeTorneoDeme($link_w, $idcampana, $idjugador, $posicion, $dinero, $iddeme, $idslot, $victorias, $empates, $derrotas, $puntuacion, $puntuacion_antigua, $umbral)
  {
    $select = "SELECT a.id, a.lang
                FROM jugador a, jugador_campana b
                WHERE b.idcampana = $idcampana
                AND b.idjugador = a.id
		AND a.id = $idjugador
                ";
echo ("<br/>".$select);
    $query = mysql_query($select, $link_w);
    if ($unquery = mysql_fetch_array($query)) { $lang = $unquery['lang']; }

    if ($lang == 'en')
    {
      $stringdeme1 = "a ";
    } else {
      $stringdeme1 = "";
    }
    switch ($iddeme)
    {
      case 1:
                if ($lang == 'en')
                {
                  $stringdeme1 = "an abyssal deme ";
                } else {
                  $stringdeme1 = " del deme de las profundidades.";
                }
		break;
      case 2:
                if ($lang == 'en')
                {
                  $stringdeme1 = "a forest deme ";
                } else {
                  $stringdeme1 = " del deme del bosque.";
                }
		break;
      case 3:
                if ($lang == 'en')
                {
                  $stringdeme1 = "a volcano deme ";
                } else {
                  $stringdeme1 = " del deme del volc&aacute;n.";
                }
		break;
    }


    switch ($posicion)
    {
      case 1:
                if ($lang == 'en')
                {
                  $subject = "Specimen wins gold medal in ".$stringdeme1."tournament";
                  $texto = "Your specimen in slot ".$idslot." ";
                  switch ($iddeme)
                  {
		    case 1: $texto = $texto."from the Abyssal depths deme "; break;
		    case 2: $texto = $texto."from the Forest deme "; break;
		    case 3: $texto = $texto."from the Volcano deme "; break;
                  }
                  $texto = $texto."has participated in ".$stringdeme1."tournament, and ended up in the 1st position.";
                } else {
                  $subject = "Especimen consigue medalla de oro en un torneo".$stringdeme1;
                  $texto = "Tu especimen en el hueco ".$idslot." ";
                  switch ($iddeme)
                  {
		    case 1: $texto = $texto."del Deme de las Profundidades "; break;
		    case 2: $texto = $texto."del Deme del Bosque "; break;
		    case 3: $texto = $texto."del Deme del Volc&aacute;n "; break;
                  }
                  $texto = $texto."ha participado en un torneo".$stringdeme1.", y ha obtenido la primera posici&oacute;n.";
                }
		break;
      case 2:
                if ($lang == 'en')
                {
                  $subject = "Specimen wins silver medal in ".$stringdeme1."tournament";
                  $texto = "Your specimen in slot ".$idslot." ";
                  switch ($iddeme)
                  {
		    case 1: $texto = $texto."from the Abyssal depths deme "; break;
		    case 2: $texto = $texto."from the Forest deme "; break;
		    case 3: $texto = $texto."from the Volcano deme "; break;
                  }
                  $texto = $texto."has participated in ".$stringdeme1."tournament, and ended up in the 2nd position.";
                } else {
                  $subject = "Especimen consigue medalla de plata en un torneo".$stringdeme1;
                  $texto = "Tu especimen en el hueco ".$idslot." ";
                  switch ($iddeme)
                  {
		    case 1: $texto = $texto."del Deme de las Profundidades "; break;
		    case 2: $texto = $texto."del Deme del Bosque "; break;
		    case 3: $texto = $texto."del Deme del Volc&aacute;n "; break;
                  }
                  $texto = $texto."ha participado en un torneo".$stringdeme1.", y ha obtenido la segunda posici&oacute;n.";
                }
		break;
      case 3:
                if ($lang == 'en')
                {
                  $subject = "Specimen wins bronze medal in ".$stringdeme1."tournament";
                  $texto = "Your specimen in slot ".$idslot." ";
                  switch ($iddeme)
                  {
		    case 1: $texto = $texto."from the Abyssal depths deme "; break;
		    case 2: $texto = $texto."from the Forest deme "; break;
		    case 3: $texto = $texto."from the Volcano deme "; break;
                  }
                  $texto = $texto."has participated in ".$stringdeme1."tournament, and ended up in the 3rd position.";
                } else {
                  $subject = "Especimen consigue medalla de bronce en un torneo".$stringdeme1;
                  $texto = "Tu especimen en el hueco ".$idslot." ";
                  switch ($iddeme)
                  {
		    case 1: $texto = $texto."del Deme de las Profundidades "; break;
		    case 2: $texto = $texto."del Deme del Bosque "; break;
		    case 3: $texto = $texto."del Deme del Volc&aacute;n "; break;
                  }
                  $texto = $texto."ha participado en un torneo".$stringdeme1.", y ha obtenido la tercera posici&oacute;n.";
                }
		break;
      case 4:
      case 5:
      case 6:
      case 7:
                if ($lang == 'en')
                {
                  $subject = "Specimen has a noteworthy participation in ".$stringdeme1."tournament";
                  $texto = "Your specimen in slot ".$idslot." ";
                  switch ($iddeme)
                  {
		    case 1: $texto = $texto."from the Abyssal depths deme "; break;
		    case 2: $texto = $texto."from the Forest deme "; break;
		    case 3: $texto = $texto."from the Volcano deme "; break;
                  }
                  $texto = $texto."has participated in ".$stringdeme1."tournament, and ended up in the position number ".$posicion.".";
                } else {
                  $subject = "Especimen tiene una participaci&oacute;n notable en un torneo".$stringdeme1;
                  $texto = "Tu especimen en el hueco ".$idslot." ";
                  switch ($iddeme)
                  {
		    case 1: $texto = $texto."del Deme de las Profundidades "; break;
		    case 2: $texto = $texto."del Deme del Bosque "; break;
		    case 3: $texto = $texto."del Deme del Volc&aacute;n "; break;
                  }
                  $texto = $texto."ha participado en un torneo".$stringdeme1.", y ha quedado en la posici&oacute;n ".$posicion.".";
                }
		break;
      default:
                if ($lang == 'en')
                {
                  $subject = "Specimen participates in ".$stringdeme1."tournament";
                  $texto = "Your specimen in slot ".$idslot." ";
                  switch ($iddeme)
                  {
		    case 1: $texto = $texto."from the Abyssal depths deme "; break;
		    case 2: $texto = $texto."from the Forest deme "; break;
		    case 3: $texto = $texto."from the Volcano deme "; break;
                  }
                  $texto = $texto."has participated in ".$stringdeme1."tournament, and ended up in the position number ".$posicion.".";
                } else {
                  $subject = "Especimen participa en un torneo".$stringdeme1;
                  $texto = "Tu especimen en el hueco ".$idslot." ";
                  switch ($iddeme)
                  {
		    case 1: $texto = $texto."del Deme de las Profundidades "; break;
		    case 2: $texto = $texto."del Deme del Bosque "; break;
		    case 3: $texto = $texto."del Deme del Volc&aacute;n "; break;
                  }
                  $texto = $texto."ha participado en un torneo".$stringdeme1.", y ha quedado en la posici&oacute;n ".$posicion.".";
                }
		break;
    }

    $total_combates = $victorias + $empates + $derrotas;
    $texto = $texto."<br/>";
    $texto = $texto."<br/>";
    if ($lang == 'en')
    {
      $texto = $texto." After a knockout round it survived, this warrior fought a total of <b>".$total_combates."</b> combats. It won <b>".$victorias."</b>, lost <b>".$derrotas."</b>, and another <b>".$empates."</b> ended up in a tie.";
      $texto = $texto." The score in this final round was <b>".$puntuacion."</b> points.";
      if (($puntuacion_antigua != null) && ($puntuacion_antigua != ''))
      {
        $texto = $texto." The specimen went through the knockout round with <b>".$puntuacion_antigua."</b> points in such round, ";
        $texto = $texto."while the minimum score to qualify for the final round was <b>".$umbral."</b> points. ";
      }
    } else {
      $texto = $texto." Despu&eacute;s de una ronda eliminatoria a la que sobrevivi&oacute;, este guerrero ha luchado en un total de <b>".$total_combates."</b> combates. Ha ganado en <b>".$victorias."</b> ocasiones, ha perdido en <b>".$derrotas."</b>, y ha empatado <b>".$empates."</b> veces.";
      $texto = $texto." La puntuaci&oacute;n en esta ronda final fue de <b>".$puntuacion."</b> puntos.";
      if (($puntuacion_antigua != null) && ($puntuacion_antigua != ''))
      {
        $texto = $texto." El especimen atraves&oacute; la ronda eliminatoria con <b>".$puntuacion_antigua."</b> puntos en esa ronda, ";
        $texto = $texto."mientras que la puntuaci&oacute;n m&iacute;nima para pasar a la ronda final fue de <b>".$umbral."</b> puntos. ";
      }
    }
    $texto = $texto."<br/>";
    $texto = $texto."<br/>";

    if ($lang == 'en')
    {
    	$texto = $texto.$this->textoganadores_en;  
    } else {
    	$texto = $texto.$this->textoganadores_es;  
    }
    $texto = $texto."<br/>";
    $texto = $texto."<br/>";


    if ($dinero > 0)
    {
      if ($lang == 'en')
      {
        $texto = $texto." For this result in the tournament, you have received ".$dinero." credits as a prize.";
        $texto = $texto."<br/><br/>";
        $texto = $texto.$textojugadores_en;
      } else {
        $texto = $texto." Por este resultado en el torneo, has recibido ".$dinero." cr&eacute;ditos como premio.";
        $texto = $texto."<br/><br/>";
        $texto = $texto.$textojugadores_es;
      }
    }
echo ("<br/>".$subject."->".$texto);
    $string = "INSERT INTO
		informe
		(tipo, subject, texto, idjugador, leido, fecha, idcampana)
		VALUES
		(1, '$subject', '$texto', $idjugador, 0, NOW(), $idcampana)
		";
echo ("<br/>");
echo $string;
    $query = mysql_query($string, $link_w);

  }


}

?>
