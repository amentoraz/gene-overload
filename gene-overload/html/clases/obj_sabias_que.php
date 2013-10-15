<?php

class Sabias_Que
{

        var $id;
        var $fecha;
        var $texto_en;
        var $texto_es;



  // *********************************
  //      Insertar un elemento
  // *********************************

  function InsertarElemento($link_w)
  {
    $string = "INSERT INTO sabias_que
		(texto_es, texto_en, fecha)
		VALUES
		('$this->texto_es', '$this->texto_en', NOW())
		";
    $query = mysql_query($string, $link_w);
  }

  // *********************************
  //      Eliminaar un elemento
  // *********************************

  function EliminarElemento($link_w, $idelemento)
  {
    $string = "DELETE FROM sabias_que
		WHERE id = $idelemento
		";
    $query = mysql_query($string, $link_w);
  }



  // *********************************
  //      Insertar un elemento
  // *********************************

  function ObtenerElementos($link_r)
  {
    $string = "SELECT id, texto_es, texto_en, fecha
		FROM sabias_que
		ORDER BY fecha DESC
		";
    $query = mysql_query($string, $link_r);
    $i = 0;
    while ($unquery = mysql_fetch_array($query))
    {
      $i++;
      $array[$i]['id'] = $unquery['id'];
      $array[$i]['texto_es'] = $unquery['texto_es'];
      $array[$i]['texto_en'] = $unquery['texto_en'];
      $array[$i]['fecha'] = $unquery['fecha'];
    }
    return $array;
  }

  // *********************************
  //      Insertar un elemento
  // *********************************

  function ObtenerAleatorio($link_r)
  {
    $stringc = "SELECT id
		FROM sabias_que
		";
    $queryc = mysql_query($stringc, $link_r);
    $numc = mysql_num_rows($queryc);
//echo $numc;
    // Ahora que sabemos cuantos, elijamos un aleatorio
    $rand = rand(0, ($numc - 1));

    $string = "SELECT texto_es, texto_en, fecha
		FROM sabias_que
		LIMIT 1 OFFSET $rand
		";
//echo $string;
    $query = mysql_query($string, $link_r);
    if ($unquery = mysql_fetch_array($query))
    {
      $this->texto_es = $unquery['texto_es'];
      $this->texto_en = $unquery['texto_en'];
      $this->fecha = $unquery['fecha'];
    }
  }



}
