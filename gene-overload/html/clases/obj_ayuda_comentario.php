<?php

class Ayuda_Comentario
{

  var $id;
  var $idticket;
  var $fecha;
  var $texto;
  var $escribe_admin;
  var $idjugador;


  // *************************************
  //    Meter un comentario
  // *************************************

  function InsertarElemento($link_w)
  {
    $string = "INSERT INTO ayuda_comentario
	(idticket, fecha, texto, escribe_admin, idjugador)
	VALUES
	($this->idticket, NOW(), '$this->texto', $this->escribe_admin, $this->idjugador)
	";
    $query = mysql_query($string, $link_w);
  }


  // *************************************
  //    Contar el numero de comentarios
  // *************************************

  function ContarComentarios($link_r, $idticket)
  {
    $string = "SELECT id
		FROM ayuda_comentario
		WHERE idticket = $idticket
		";
    $query = mysql_query($string, $link_r);
    return mysql_num_rows($query);
  }


  // *************************************
  //    Buscar los comentarios
  // *************************************

  function BuscarComentarios($link_r, $idticket)
  {
    $string = "SELECT id, idticket, fecha,
		texto, escribe_admin, idjugador
		FROM ayuda_comentario
		WHERE idticket = $idticket
		ORDER BY fecha ASC
		";
    $query = mysql_query($string, $link_r);
    $i = 0;
    while ($unquery = mysql_fetch_array($query))
    {
      $i++;
      $array[$i]['id'] = $unquery['id'];
      $array[$i]['idticket'] = $unquery['idticket'];
      $array[$i]['fecha'] = $unquery['fecha'];
      $array[$i]['texto'] = $unquery['texto'];
      $array[$i]['escribe_admin'] = $unquery['escribe_admin'];
      $array[$i]['idjugador'] = $unquery['idjugador'];
    }
    return $array;
  }

}



?>
