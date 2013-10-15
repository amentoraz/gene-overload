<?php

//CREATE TABLE sugerencia (
//id int auto_increment not null,
//fecha datetime,
//archivada tinyint,
//texto text,
//idjugador int,
//PRIMARY KEY (id)
//);

class Sugerencia
{


  var $id;
  var $fecha;
  var $archivada;
  var $texto;
  var $idjugador;



  // *************************************
  //     Archivar
  // *************************************

  function Archivar($link_w, $idelemento)
  {
    $string = "UPDATE sugerencia
                SET archivada = 1
                WHERE id = $idelemento
                ";
    $query = mysql_query($string, $link_w);
  }


  // *************************************
  //     Eliminar
  // *************************************

  function Eliminar($link_w, $idelemento)
  {
    $string = "DELETE FROM sugerencia
                WHERE id = $idelemento
                ";
    $query = mysql_query($string, $link_w);
  }




  // *************************************
  //     Insertar una sugerencia
  // *************************************

  function Insertar($link_w)
  {
    $string = "INSERT INTO sugerencia
                (fecha, archivada, texto, idjugador)
                VALUES
                (NOW(), 0, '$this->texto', $this->idjugador)
                ";
//echo $string;
    $query = mysql_query($string, $link_w);
  }


  // ***************************
  //   Sacar datos de una
  // ***************************

  function SacarDatos($link_r, $idelemento)
  {
    $string = "SELECT id, fecha, archivada, texto, idjugador
                FROM sugerencia
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
    }
  }

  // ***************************
  //   Contar sugerencias
  // ***************************


  function ContarElementos($link_r, $archivada)
  {
    $string = "SELECT id
                FROM sugerencia
        ";
    if (($archivada != '') && ($archivada != null))
    {
      $string = $string." WHERE archivada = $archivada ";
    }
    $query = mysql_query($string, $link_r);
    return mysql_num_rows($query);
  }

  // ***************************
  //   Listar sugerencias
  // ***************************

  function BuscarElementos($link_r, $limit, $offset, $archivada)
  {
    $string = "SELECT id, fecha, archivada, texto, idjugador
                FROM sugerencia
		";
    if (($archivada != '') && ($archivada != null))
    {
      $string = $string." WHERE archivada = $archivada ";
    }

    $string = $string."
                ORDER BY fecha DESC, id DESC
                LIMIT $limit OFFSET $offset
        ";
//echo $string;
//              WHERE publicada = 1
    $query = mysql_query($string, $link_r);
    $i = 0;
    while ($unquery = mysql_fetch_array($query))
    {
      $i++;
      $array[$i]['id'] = $unquery['id'];
      $array[$i]['idjugador'] = $unquery['idjugador'];
      $array[$i]['fecha'] = $unquery['fecha'];
      $array[$i]['archivada'] = $unquery['archivada'];
      $array[$i]['texto'] = $unquery['texto'];
    }
    return $array;
  }





}
