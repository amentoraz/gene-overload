<?php
//
//
//  Clase log
//
// CREATE TABLE log
// (
// id int not null auto_increment,
// idjugador int,
// idcampana int,
// tipo_suceso int,
// fecha datetime,
// valor int,
// PRIMARY KEY (id)
// );
//
//
//
//  tipo_suceso:
//
//  1 v Ha evolucionado (valor = 1 individuo, 2 deme, 3 todo)
//  2 v Ha entrenado (valor = 1 individuo, 2 generacion)
//  3 v Ha aumentado niveles del arbol (a valor = $n)
//  4 v Ha aumentado slots (valor = 1 profundidades, 2 bosque, 3 volcan)
//  5 v Ha aumentado 1% ratio de mutacion
//  6 v Ha disminuido 1% ratio de mutacion
//  7 v Ha cambiado sexualidad (valor = numsexos)
//  8 v Ha cambiado intensidad mutacion (valor = 1 suave, 2 media, 3 fuerte)
//  9 v Ha usado superman (valor = 1, 2 depende fuerza)
// 10 v Ha usado mezcla de demes
// 11 v Ha participado en un torneo (valor = posicion)
// 12 vv Se ha unido a un clan (valor = idclan) - tanto si te invitan como si te aprueban la solicitud
// 13 v Ha fundado un clan (valor = idclan)
// 14 v Ha sido baneado de un clan (valor = idclan)
// 15 v Ha dejado un clan (valor = $idclan)
// 16 v Ha disuelto un clan (valor = $idclan)
//



Class Log
{

  var $id;
  var $idjugador;
  var $idcampana;
  var $tipo_suceso;
  var $fecha;
  var $valor;


  // **********************************
  //    Escribe un log
  // **********************************

  function EscribirLog($link_w)
  {
    $string = "INSERT INTO log
		(idjugador, idcampana, tipo_suceso, fecha, valor)
		VALUES
		($this->idjugador, $this->idcampana, $this->tipo_suceso, NOW(), $this->valor)
		";
//echo $string;
    $query = mysql_query($string, $link_w);
  }


  // *********************************
  //    Contar logs de un usuario
  // **********************************

  function Contar_Elementos($link_r, $idusuario, $idcampana)
  {
    $string = "SELECT id
		FROM log
		WHERE 1 = 1
		";
    if ($idusuario > 0)
    {
      $string = $string." AND idjugador = $idusuario";
    }
    if ($idcampana > 0)
    {
      $string = $string." AND idcampana = $idcampana ";
    }
    $query = mysql_query($string, $link_r);
    return mysql_num_rows($query);
  }


  // *********************************
  //    Buscar logs de un usuario
  // **********************************

  function Buscar_Elementos($link_r, $idusuario, $idcampana, $offset, $limitelementos)
  {
    $string = "SELECT id, idjugador, idcampana, tipo_suceso,
		fecha, valor
		FROM log
		WHERE 1 = 1
		";
    if ($idusuario > 0)
    {
      $string = $string." AND idjugador = $idusuario ";
    }
    if ($idcampana > 0)
    {
      $string = $string." AND idcampana = $idcampana ";
    }
    $string = $string." ORDER BY fecha DESC
			LIMIT $limitelementos
			OFFSET $offset
			";
    $query = mysql_query($string, $link_r);
    $i = 0;
    while ($unquery = mysql_fetch_array($query))
    {
      $i++;
      $array[$i]['id'] = $unquery['id'];
      $array[$i]['idjugador'] = $unquery['idjugador'];
      $array[$i]['idcampana'] = $unquery['idcampana'];
      $array[$i]['tipo_suceso'] = $unquery['tipo_suceso'];
      $array[$i]['fecha'] = $unquery['fecha'];
      $array[$i]['valor'] = $unquery['valor'];
    }
    return $array;
  }




}



?>
