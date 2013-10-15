<?php

  class Comentario_Noticia
  {

    var $id;
    var $fecha;
    var $idjugador;
    var $idnoticia;
    var $texto;


    // *********************************
    //        Insertar
    // *********************************

    function Insertar($link_w, $idnoticia, $idjugador)
    {
      $string = "INSERT INTO comentario_noticia
		(fecha, idjugador, idnoticia, texto)
		VALUES
		(NOW(), $idjugador, $idnoticia, '$this->texto')
		";
      $query = mysql_query($string, $link_w);
    }

    // *********************************
    //        Contar comentarios de una noticia
    // *********************************

    function ContarElementos($link_r, $idelemento)
    {
      $string = "SELECT id
		FROM comentario_noticia
		WHERE idnoticia = $idelemento
		";
//echo $string;
      $query = mysql_query($string, $link_r);
      return mysql_num_rows($query);
    }

    // *********************************
    //        Listar comentarios de una noticia
    // *********************************

    function BuscarElementos($link_r, $idelemento)
    {
      $string = "SELECT id, fecha, idjugador, idnoticia, texto
		FROM comentario_noticia
		WHERE idnoticia = $idelemento
                ORDER BY fecha DESC, id DESC
		";
      $query = mysql_query($string, $link_r);
      $i = 0;
      while ($unquery = mysql_fetch_array($query))
      {
        $i++;
        $array[$i]['id'] = $unquery['id'];
        $array[$i]['fecha'] = $unquery['fecha'];
        $array[$i]['idnoticia'] = $unquery['idnoticia'];
        $array[$i]['idjugador'] = $unquery['idjugador'];
        $array[$i]['texto'] = $unquery['texto'];
      }
      return $array;

    }

    // *********************************
    //        Para evitar que sea repetido
    // *********************************

    function BuscarSpam($link_r, $idelemento, $texto)
    {
      $string = "SELECT id
		FROM comentario_noticia
		WHERE idnoticia = $idelemento
		AND texto = '$texto'
		";
//echo $string;
      $query = mysql_query($string, $link_r);
      return mysql_num_rows($query);
    }





  }

?>
