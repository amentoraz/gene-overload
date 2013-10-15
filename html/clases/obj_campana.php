<?php

  class Campana
  {

    var $id;
    var $nombre;
    var $descripcion;
    var $nombre_en;
    var $descripcion_en;
    var $fecha_inicio;
    var $fecha_fin;
    var $beta;
    var $dinero_inicial;
    var $cantidad_profundidades;
    var $cantidad_bosque;
    var $cantidad_volcan;



    // *************************************
    //   Cambiar datos de una campana
    // *************************************

    function Crear($link_w)
    {
      $string = "INSERT INTO campana
		(nombre, nombre_en, descripcion, descripcion_en, fecha_inicio, fecha_fin)
		VALUES
		('$this->nombre', '$this->nombre_en', '$this->descripcion',
		'$this->descripcion_en', '$this->fecha_inicio', '$this->fecha_fin')
		";
//echo $string;
      $query = mysql_query($string, $link_w);
    }

    // *************************************
    //   Eliminar una campana
    // *************************************

    function Eliminar($link_w, $idelemento)
    {
      $string = "DELETE FROM campana
		WHERE id = $idelemento
		";
      $query = mysql_query($string, $link_w);
    }


    // *************************************
    //   Cambiar datos de una campana
    // *************************************

    function Alterar($link_w, $idelemento)
    {
      $string = "UPDATE campana
		SET nombre = '$this->nombre',
		nombre_en = '$this->nombre_en',
		descripcion = '$this->descripcion',
		descripcion_en = '$this->descripcion_en',
		fecha_inicio = '$this->fecha_inicio',
		fecha_fin = '$this->fecha_fin'
		WHERE id = $idelemento
		";
      $query = mysql_query($string, $link_w);
    }

    // *************************************
    //   Saca datos de una campana
    // *************************************

    function SacarDatos($link_r, $idelemento)
    {
      $string = "SELECT id, nombre, descripcion, fecha_inicio, fecha_fin,
			nombre_en, descripcion_en, dinero_inicial, beta,
			cantidad_profundidades, cantidad_bosque, cantidad_volcan,
			niveles_arbol
			FROM campana
			WHERE id = $idelemento
		";
      $query = mysql_query($string, $link_r);
      if($unquery = mysql_fetch_array($query))
      {
 	$this->id = $unquery['id'];
 	$this->nombre = $unquery['nombre'];
 	$this->descripcion = $unquery['descripcion'];
 	$this->nombre_en = $unquery['nombre_en'];
 	$this->descripcion_en = $unquery['descripcion_en'];
 	$this->fecha_inicio = $unquery['fecha_inicio'];
 	$this->fecha_fin = $unquery['fecha_fin'];
 	$this->dinero_inicial = $unquery['dinero_inicial'];
 	$this->beta = $unquery['beta'];
 	$this->cantidad_profundidades = $unquery['cantidad_profundidades'];
 	$this->cantidad_bosque = $unquery['cantidad_bosque'];
 	$this->cantidad_volcan = $unquery['cantidad_volcan'];
 	$this->niveles_arbol = $unquery['niveles_arbol'];
      } else {
	return -1;
      }
    }


    // *************************************
    //   Lista las campanyas
    // *************************************

    function ListarCampanas($link_r)
    {
      $string = "SELECT
		id, nombre, descripcion, fecha_inicio, fecha_fin,
		nombre_en, descripcion_en
		FROM campana
		ORDER BY fecha_inicio";
      $query = mysql_query($string, $link_r);
      $j = 0;
      while ($unquery = mysql_fetch_array($query))
      {
        $j++;
        $array[$j]['id'] = $unquery['id'];
        $array[$j]['nombre_en'] = $unquery['nombre_en'];
        $array[$j]['nombre'] = $unquery['nombre'];
        $array[$j]['descripcion'] = $unquery['descripcion'];
        $array[$j]['descripcion_en'] = $unquery['descripcion_en'];
        $array[$j]['fecha_inicio'] = $unquery['fecha_inicio'];
        $array[$j]['fecha_fin'] = $unquery['fecha_fin'];
      }
      return $array;
    }


    // *************************************
    //   Lista las campanyas activas (fecha_fin mayor que la actual)
    // *************************************

    function ListarCampanasActivas($link_r)
    {
      $string = "SELECT id, nombre, descripcion, fecha_inicio, fecha_fin,
			nombre_en, descripcion_en
			FROM campana
			WHERE fecha_fin >= NOW()
			ORDER BY fecha_inicio
			";
      $query = mysql_query($string, $link_r);
      $j = 0;
      while ($unquery = mysql_fetch_array($query))
      {
        $j++;
        $array[$j]['id'] = $unquery['id'];
        $array[$j]['nombre'] = $unquery['nombre'];
        $array[$j]['descripcion'] = $unquery['descripcion'];
        $array[$j]['nombre_en'] = $unquery['nombre_en'];
        $array[$j]['descripcion_en'] = $unquery['descripcion_en'];
        $array[$j]['fecha_inicio'] = $unquery['fecha_inicio'];
        $array[$j]['fecha_fin'] = $unquery['fecha_fin'];
      }
      return $array;
    }


  }


?>
