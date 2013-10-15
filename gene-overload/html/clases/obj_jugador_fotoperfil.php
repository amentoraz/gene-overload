<?php

class Jugador_Fotoperfil
{

//
// Clase de fotos de perfil, ya sea las predeterminadas o las premium
//

  var $id;
  var $ruta;
  var $es_standard;



  // ********************************************
  //    Obtiene todas las imagenes standard
  // ********************************************

  function Obtener_Standard($link_r)
  {
    $string = "SELECT id, ruta
		FROM jugador_fotoperfil
		WHERE es_standard = 1
		";
    $query = mysql_query($string, $link_r);
    $i=0;
    while ($unquery = mysql_fetch_array($query))
    {
      $i++;
      $array[$i]['id'] = $unquery['id'];
      $array[$i]['ruta'] = $unquery['ruta'];
    }
    return $array;
  }


  // *******************************************
  //   Grabar la imagen de un jugador
  // *******************************************

  function Grabar_Imagen_Jugador_Standard($link_w, $idjugador, $idimagen)
  {
    // Primero nos aseguramos de que esta todo correcto y es standar
    $string = "SELECT id, ruta, es_standard
		FROM jugador_fotoperfil
		WHERE es_standard = 1
		AND id = $idimagen
		";
//echo $string;
    $query = mysql_query($string, $link_w);
    if ($unquery = mysql_fetch_array($query))
    {
//      $this->id = $unquery['id'];
//      $this->ruta = $unquery['ruta'];
//      $this->es_standard = $unquery['es_standard'];
      $string2 = "UPDATE jugador
		SET idfotoperfil = $idimagen
		WHERE id = $idjugador
		";
//echo ("<br/>".$string2);
      $query2 = mysql_query($string2, $link_w);
      return true;
    } else {
      return false;
    }
  }


  // *******************************************
  //   Grabar la imagen de un jugador
  // *******************************************

  function Grabar_Imagen_Jugador_NoStandard($link_w, $idjugador, $idimagen)
  {
    // Primero nos aseguramos de que esta todo correcto y es standar
      $string2 = "UPDATE jugador
		SET idfotoperfil = $idimagen
		WHERE id = $idjugador
		";
      $query2 = mysql_query($string2, $link_w);
      return true;
  }

  // *******************************************
  //   Obtiene la imagen de un jugador
  // *******************************************

  function Obtener_Imagen_Jugador($link_r, $idjugador)
  {
    $string = "SELECT a.id, a.ruta, a.es_standard
		FROM jugador_fotoperfil a, jugador b
		WHERE b.idfotoperfil = a.id
		AND b.id = $idjugador
		";
    $query = mysql_query($string, $link_r);
    if ($unquery = mysql_fetch_array($query))
    {
      $this->id = $unquery['id'];
      $this->ruta = $unquery['ruta'];
      $this->es_standard = $unquery['es_standard'];
      return true;
    } else {
      return false;
    }
  }


  // *******************************************
  //   Inserta una imagen
  // *******************************************

  function Insertar_Imagen($link_w, $ruta, $es_standard)
  {
    $string = "INSERT INTO jugador_fotoperfil
		(ruta, es_standard) VALUES ('$ruta', $es_standard)
		";
    $query = mysql_query($string, $link_w);
  }






}


?>
