<?php

class Noticia
{


  var $id;
  var $fecha;
  var $titular;
  var $entradilla;
  var $texto;
  var $titular_en;
  var $entradilla_en;
  var $texto_en;
  var $rutaimagen;
  var $publicada;



  // *************************************
  //     despublicar una noticia
  // *************************************

  function Despublicar($link_w, $idelemento)
  {
    $string = "UPDATE noticia
		SET publicada = 0
		WHERE id = $idelemento
		";
    $query = mysql_query($string, $link_w);
  }

  // *************************************
  //     publicar una noticia
  // *************************************

  function Publicar($link_w, $idelemento)
  {
    $string = "UPDATE noticia
		SET publicada = 1
		WHERE id = $idelemento
		";
    $query = mysql_query($string, $link_w);
  }


  // *************************************
  //     alterar datos de una noticia
  // *************************************

  function Alterar($link_w, $idelemento)
  {
//echo ("XXXXXXXXXXX");
    $string = "UPDATE noticia
		SET fecha = '$this->fecha',
		titular = '$this->titular',
		entradilla = '$this->entradilla',
		texto = '$this->texto',
		titular_en = '$this->titular_en',
		entradilla_en = '$this->entradilla_en',
		texto_en = '$this->texto_en'
		WHERE id = $idelemento
		";
//echo $string;
    $query = mysql_query($string, $link_w);
  }


  // *************************************
  //     Eliminar una noticia
  // *************************************

  function Eliminar($link_w, $idelemento)
  {
    $string = "DELETE FROM noticia
		WHERE id = $idelemento
		";
    $query = mysql_query($string, $link_w);
  }

  // *************************************
  //     Sacar datos de una noticia
  // *************************************

  function Insertar($link_w)
  {
    $string = "INSERT INTO noticia
		(fecha, titular, entradilla, texto, rutaimagen, publicada, titular_en, entradilla_en, texto_en)
		VALUES
		('$this->fecha', '$this->titular', '$this->entradilla',
		'$this->texto', null, 0,
		'$this->titular_en', '$this->entradilla_en', '$this->texto_en'
		)
		";
//echo $string;
    $query = mysql_query($string, $link_w);
  }


  // *************************************
  //     Sacar datos de una noticia
  // *************************************

  function SacarDatos($link_r, $idnoticia)
  {
    $string = "SELECT id, fecha, titular, entradilla, texto, rutaimagen, publicada, titular_en, entradilla_en, texto_en
		FROM noticia
		WHERE id = $idnoticia
	";
    $query = mysql_query($string, $link_r);
    if ($unquery = mysql_fetch_array($query))
    {
      $this->id = $unquery['id'];
      $this->fecha = $unquery['fecha'];
      $this->titular = $unquery['titular'];
      $this->entradilla = $unquery['entradilla'];
      $this->texto = $unquery['texto'];
      $this->rutaimagen = $unquery['rutaimagen'];
      $this->publicada = $unquery['publicada'];
      $this->titular_en = $unquery['titular_en'];
      $this->entradilla_en = $unquery['entradilla_en'];
      $this->texto_en = $unquery['texto_en'];
      return true;
    } else {
      return false;
    }
  }


  // *************************************
  //     Sacar noticias
  // *************************************

  function ContarNoticias($link_r)
  {
    $string = "SELECT id
		FROM noticia
	";
//		WHERE publicada = 1
    $query = mysql_query($string, $link_r);
    return mysql_num_rows($query);
  }

  // *************************************
  //     Sacar noticias
  // *************************************

  function BuscarNoticias($link_r, $limit, $offset)
  {
    $string = "SELECT id, fecha, titular, entradilla, publicada,
		titular_en, entradilla_en
		FROM noticia
		ORDER BY fecha DESC, id DESC
		LIMIT $limit OFFSET $offset
	";
//		WHERE publicada = 1
    $query = mysql_query($string, $link_r);
    $i = 0;
    while ($unquery = mysql_fetch_array($query))
    {
      $i++;
      $array[$i]['id'] = $unquery['id'];
      $array[$i]['publicada'] = $unquery['publicada'];
      $array[$i]['fecha'] = $unquery['fecha'];
      $array[$i]['entradilla'] = $unquery['entradilla'];
      $array[$i]['titular'] = $unquery['titular'];
      $array[$i]['entradilla_en'] = $unquery['entradilla_en'];
      $array[$i]['titular_en'] = $unquery['titular_en'];
    }
    return $array;
  }


  // *************************************
  //     Sacar noticias publicadas
  // *************************************

  function ContarNoticiasPublicadas($link_r)
  {
    $string = "SELECT id
		FROM noticia
		WHERE publicada = 1
	";
    $query = mysql_query($string, $link_r);
    return mysql_num_rows($query);
  }

  // *************************************
  //     Sacar noticias
  // *************************************

  function BuscarNoticiasPublicadas($link_r, $limit, $offset)
  {
    $string = "SELECT id, fecha, titular, entradilla, publicada, texto, texto_en,
		titular_en, entradilla_en
		FROM noticia
		WHERE publicada = 1
		ORDER BY fecha DESC, id DESC
		LIMIT $limit OFFSET $offset
	";
//echo $string;
    $query = mysql_query($string, $link_r);
    $i = 0;
    while ($unquery = mysql_fetch_array($query))
    {
      $i++;
      $array[$i]['id'] = $unquery['id'];
      $array[$i]['texto'] = $unquery['texto'];
      $array[$i]['texto_en'] = $unquery['texto_en'];
      $array[$i]['publicada'] = $unquery['publicada'];
      $array[$i]['fecha'] = $unquery['fecha'];
      $array[$i]['entradilla'] = $unquery['entradilla'];
      $array[$i]['titular'] = $unquery['titular'];
      $array[$i]['entradilla_en'] = $unquery['entradilla_en'];
      $array[$i]['titular_en'] = $unquery['titular_en'];
    }
    return $array;
  }



}

?>
