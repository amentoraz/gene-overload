<?php

class Resolucion_Torneo
{

  var $id;



  // ************************************************** 
  //   Contar y obtener especimenes de un torneo
  // **************************************************
  //
  //  Obtener los especimenes que tenemos apuntados a un torneo.
  // Solo hay que pasarle $idtorneo = 0 para que sea sobre el proximo
  //

  function ContarEspecimenesTorneo($link_r, $idcampana)
  {
    //  El numero de especimenes participantes en un torneo es el mismo que
    // el numero de jugadores apuntados a la campanya.
    $string = "SELECT id
		FROM jugador_campana
		WHERE idcampana = $idcampana
		";
//echo $string;
    $query = mysql_query($string, $link_r);
    $cuantos = mysql_num_rows($query);
    return $cuantos;

  }


  // **********************************************
  //    Sacamos el mejor puntuado de cada deme
  // **********************************************

  function BuscarEspecimenesTorneoDeme($link_r, $iddeme, $idcampana)
  {
    // Primero vamos a seleccionar a los jugadores
    $string = "SELECT idjugador
		FROM jugador_campana
		WHERE idcampana = $idcampana
		";
    $query = mysql_query($string, $link_r);
    $i = 0;
    while ($unquery = mysql_fetch_array($query))
    {
      $idjugador = $unquery['idjugador'];
      // Ahora para el jugador especifico, buscamos en el deme
      $string2 = "SELECT a.id
 		FROM especimen a
		WHERE a.idcampana = $idcampana
		AND a.iddeme = $iddeme
		AND a.idpropietario = $idjugador
		ORDER BY puntos_evaluacion DESC
		LIMIT 1
		";
      $query2 = mysql_query($string2, $link_r);
      if ($unquery2 = mysql_fetch_array($query2))
      {
        $i++;
        $array[$i]['idespecimen'] = $unquery2['id'];
        $array[$i]['idjugador'] = $idjugador;
      }
    }
    return $array;

  }









}

?>
