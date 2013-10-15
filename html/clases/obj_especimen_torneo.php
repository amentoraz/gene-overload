<?php
class Especimen_torneo
{

	var $id;
	var $idespecimen;
	var $idtorneo;
	var $posicion;






  // ***************************************************
  //   Grabamos la puntuacion de un competidor
  // ***************************************************

  function GrabarPuntuacion($link_w, $idespecimen, $posicion, $idtorneo)
  {
    $string = "UPDATE especimen_torneo
		SET posicion = $posicion
		WHERE idespecimen = $idespecimen
		AND idtorneo = $idtorneo
		";
    $query = mysql_query($string, $link_w);
  }

  // ***************************************************
  //   Cambia el idtorneo de uno a otro (normalmente de 0 al existente)
  // ***************************************************

  function TrasladarEspecimenesTorneo($link_w, $id_torneo_origen, $id_torneo_destino)
  {
    $string = "SELECT id, idespecimen, idtorneo, posicion
		FROM especimen_torneo
		WHERE idtorneo = $id_torneo_origen
		";
    $query = mysql_query($string, $link_w);
    while ($unquery = mysql_fetch_array($query))
    {
      $idespecimen = $unquery['idespecimen'];
      $id = $unquery['id'];
      $idtorneo = $unquery['idtorneo'];
      $posicion = $unquery['posicion'];

      $string2 = "INSERT INTO especimen_torneo
		(idespecimen, idtorneo, posicion)
		VALUES
		($idespecimen, $id_torneo_destino, $posicion)
		";
      $query2 = mysql_query($string2);
    }
  }


  // **************************************************
  //   Contar y obtener especimenes de un torneo
  // **************************************************
  //
  //  Obtener los especimenes que tenemos apuntados a un torneo.
  // Solo hay que pasarle $idtorneo = 0 para que sea sobre el proximo
  //

  function ContarEspecimenesTorneo($link_r, $idtorneo, $idcampana)
  {
    $string = "SELECT a.id
		FROM especimen_torneo a, especimen b
		WHERE a.idespecimen = b.id
		AND a.idtorneo = $idtorneo
		AND b.idcampana = $idcampana
		";
    $query = mysql_query($string, $link_r);
    $cuantos = mysql_num_rows($query);
    return $cuantos;

  }

  function BuscarEspecimenesTorneo($link_r, $idtorneo, $idcampana)
  {
    $string = "SELECT a.id, b.id AS idespecimen, b.idpropietario AS idjugador
		FROM especimen_torneo a, especimen b
		WHERE a.idespecimen = b.id
		AND a.idtorneo = $idtorneo
		AND b.idcampana = $idcampana
		";
    $query = mysql_query($string, $link_r);
    $i = 0;
    while ($unquery = mysql_fetch_array($query))
    {
      $i++;
      $array[$i]['id'] = $unquery['id'];
      $array[$i]['idespecimen'] = $unquery['idespecimen'];
      $array[$i]['idjugador'] = $unquery['idjugador'];
    }
    return $array;

  }



  // **************************************************
  //   Obtiene el especimen apuntado al proximo torneo del jugador
  // **************************************************

  function ObtenEspecimenTorneo($link_r, $tipotorneo, $idjugador)
  {
    $string = "SELECT a.id, b.id AS idespecimen
		FROM especimen_torneo a, especimen b
		WHERE a.idespecimen = b.id
		AND a.idtorneo = $tipotorneo
		AND b.idpropietario = $idjugador
		";
//echo $string;
    $query = mysql_query($string, $link_r);
    $i = 0;
    if ($unquery = mysql_fetch_array($query))
    {
      $this->id = $unquery['id'];
      $this->idespecimen = $unquery['idespecimen'];
    }
    return mysql_num_rows($query);

  }


  // **************************************************
  //   Desapunta todos los especimenes de un jugador
  // **************************************************
  //
  //  Los desapunta (aunque solo deberia de haber a lo sumo uno)

  function DesapuntarTorneo($link_w, $idjugador, $idcampana, $tipotorneo)
  {

    $string = "SELECT a.id
		FROM especimen_torneo a, especimen b
		WHERE a.idespecimen = b.id
		AND b.idpropietario = $idjugador
		AND a.idtorneo = $tipotorneo
		AND b.idcampana = $idcampana
		";
//echo $string;
    $query = mysql_query($string, $link_w);
    while ($unquery = mysql_fetch_array($query))
    {
      $id_especimen_torneo = $unquery['id'];
      // Como no hay torneo generado de por si, le vamos a apuntar al torneo 0
      $string2 = "DELETE FROM especimen_torneo
		WHERE id = $id_especimen_torneo
		";
//echo $string2;
      $query2 = mysql_query($string2, $link_w);
    }


  }


  // **************************************************
  //   Contar los especimenes que tiene un pollo en un torneo
  // **************************************************
  //
  //   Saca el num de especimenes en torneo si lo tiene

  function ContarJugadorTorneo($link_r, $idjugador, $tipotorneo)
  {

    $string = "SELECT a.id
		FROM especimen_torneo a, especimen b
		WHERE a.idespecimen = b.id
		AND b.idpropietario = $idjugador
		AND a.idtorneo = $tipotorneo
		";
//echo $string;
    $query = mysql_query($string, $link_r);
    return mysql_num_rows($query);

  }



  // **************************************************
  //        Apunta a un especimen a un torneo
  // **************************************************

  function ApuntarTorneo($link_w, $idespecimen, $tipotorneo)
  {

    // Como no hay torneo generado de por si, le vamos a apuntar al torneo 0
    $string = "INSERT INTO especimen_torneo
		(idespecimen, idtorneo, posicion)
		VALUES
		($idespecimen, $tipotorneo, NULL)
		";
//echo $string;
    $query = mysql_query($string, $link_w);


  }



}
