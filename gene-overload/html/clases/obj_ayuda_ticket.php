<?php

class Ayuda_Ticket
{

  var $id;
  var $fecha;
  var $archivada;
  var $texto;
  var $idjugador;
  var $subject;




  // *********************************
  //   Archivar (esto solo cuando desde el admin se ve como definitivo)
  // *********************************

  function Archivar($link_w, $idticket, $archivada)
  {
    $string = "UPDATE ayuda_ticket
		SET archivada = $archivada
		WHERE id = $idticket
		";
    $query = mysql_query($string, $link_w);
  }

  // *********************************
  //   Comprobar si puede escribir
  // *********************************

  function ComprobarSiPuedeAbrir($link_r, $idjugador)
  {
    $string = "SELECT id
		FROM ayuda_comentario
		WHERE idjugador = $idjugador
		AND DATE_SUB(NOW(), INTERVAL 10 MINUTE) < fecha
		";
//echo $string;
    $query = mysql_query($string, $link_r);
    if (mysql_num_rows($query) > 0)
    {
      return 0;
    } else {
      return 1;
    }


  }


  // *********************************
  //   Busca los elementos de un player
  // *********************************

  function InsertarElemento($link_w)
  {
    $string = "INSERT INTO ayuda_ticket
		(fecha, archivada, texto, subject, idjugador)
		VALUES
		(NOW(), 0, '$this->texto', '$this->subject', $this->idjugador)
		";
    $query = mysql_query($string, $link_w);
  }

  // *********************************
  //   Cuenta los elementos de un player
  // *********************************

  function ContarElementosJugador($link_r, $idjugador)
  {
    $string = "SELECT id
		FROM ayuda_ticket
		WHERE idjugador = $idjugador
		ORDER BY archivada ASC,
		fecha DESC
		";
    $query = mysql_query($string, $link_r);
    return mysql_num_rows($query);
  }

  // *********************************
  //   Busca los elementos de un player
  // *********************************

  function BuscarElementosJugador($link_r, $idjugador, $offset, $limit)
  {
    $string = "SELECT id, fecha, archivada, texto, idjugador, subject
		FROM ayuda_ticket
		WHERE idjugador = $idjugador
		ORDER BY archivada ASC,
		fecha DESC
		LIMIT $limit OFFSET $offset
		";
    $query = mysql_query($string, $link_r);
    $i = 0;
    while ($unquery = mysql_fetch_array($query))
    {
      $i++;
      $array[$i]['id'] = $unquery['id'];
      $array[$i]['subject'] = $unquery['subject'];
      $array[$i]['fecha'] = $unquery['fecha'];
      $array[$i]['archivada'] = $unquery['archivada'];
      $array[$i]['texto'] = $unquery['texto'];
      $array[$i]['idjugador'] = $unquery['idjugador'];
    }
    return $array;

  }


  // *********************************
  //   Cuenta los elementos de un player
  // *********************************

  function ContarElementos($link_r, $archivada)
  {
    $string = "SELECT id
		FROM ayuda_ticket
		WHERE archivada = $archivada
		ORDER BY archivada ASC,
		fecha DESC
		";
    $query = mysql_query($string, $link_r);
    return mysql_num_rows($query);
  }

  // *********************************
  //   Busca los elementos de un player
  // *********************************

  function BuscarElementos($link_r, $archivada, $offset, $limit)
  {
    $string = "SELECT a.id, a.fecha, a.archivada, a.texto, a.idjugador, a.subject, b.login
		FROM ayuda_ticket a, jugador b
		WHERE a.archivada = $archivada
		AND a.idjugador = b.id
		ORDER BY archivada ASC,
		fecha DESC
		LIMIT $limit OFFSET $offset
		";
    $query = mysql_query($string, $link_r);
    $i = 0;
    while ($unquery = mysql_fetch_array($query))
    {
      $i++;
      $array[$i]['login'] = $unquery['login'];
      $array[$i]['id'] = $unquery['id'];
      $array[$i]['subject'] = $unquery['subject'];
      $array[$i]['fecha'] = $unquery['fecha'];
      $array[$i]['archivada'] = $unquery['archivada'];
      $array[$i]['texto'] = $unquery['texto'];
      $array[$i]['idjugador'] = $unquery['idjugador'];
    }
    return $array;

  }



  // *******************************************+
  //   Obtiene datos de uno
  // *******************************************+

  function SacarDatos($link_r, $idelemento)
  {
    $string = "SELECT id, fecha, archivada, texto, idjugador, subject
		FROM ayuda_ticket
		WHERE id = $idelemento
		";
    $query = mysql_query($string, $link_r);
    if ($unquery = mysql_fetch_array($query))
    {
      $this->id = $unquery['id'];
      $this->fecha = $unquery['fecha'];
      $this->archivada = $unquery['archivada'];
      $this->texto = $unquery['texto'];
      $this->idjugador = $unquery['idjugador'];
      $this->subject = $unquery['subject'];
    }

  }







}


?>
