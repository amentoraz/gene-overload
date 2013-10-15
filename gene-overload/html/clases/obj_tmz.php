<?php

class TMZ
{


  var $id;
  var $tmz_hour;
  var $tmz_min;
  var $desc_es;
  var $desc_en;



  // ***************************************
  //    Insertar en TMZ
  // ***************************************

  function BorrarElemento($link_w, $idelemento)
  {
    $string = "DELETE FROM tmz
		WHERE id = $idelemento
		";
    $query = mysql_query($string, $link_w);
  }

  // ***************************************
  //    Insertar en TMZ
  // ***************************************

  function CrearElemento($link_w)
  {
    $string = "INSERT INTO tmz
		(tmz_hour, tmz_min, desc_es, desc_en)
		VALUES
		($this->tmz_hour, $this->tmz_min, '$this->desc_es', '$this->desc_en')
		";
    $query = mysql_query($string, $link_w);
  }


  // ***************************************
  //    Buscar los tMZ
  // ***************************************

  function SacarDatos($link_r, $idtmz)
  {
    $string = "SELECT tmz_hour, tmz_min, desc_es, desc_en, id
		FROM tmz
		WHERE id = $idtmz
		";
    $query = mysql_query($string, $link_r);
    if ($unquery = mysql_fetch_array($query))
    {
      $this->id = $unquery['id'];
      $this->tmz_hour = $unquery['tmz_hour'];
      $this->tmz_min = $unquery['tmz_min'];
      $this->desc_es = $unquery['desc_es'];
      $this->desc_en = $unquery['desc_en'];
    }
    return $array;
  }


  // ***************************************
  //    Buscar los tMZ
  // ***************************************

  function BuscarElemento($link_r)
  {
    $string = "SELECT tmz_hour, tmz_min, desc_es, desc_en, id
		FROM tmz
		ORDER BY tmz_hour ASC, tmz_min ASC
		";
    $query = mysql_query($string, $link_r);
    $i = 0;
    while ($unquery = mysql_fetch_array($query))
    {
      $i++;
      $array[$i]['id'] = $unquery['id'];
      $array[$i]['tmz_hour'] = $unquery['tmz_hour'];
      $array[$i]['tmz_min'] = $unquery['tmz_min'];
      $array[$i]['desc_es'] = $unquery['desc_es'];
      $array[$i]['desc_en'] = $unquery['desc_en'];
    }
    return $array;
  }





}
