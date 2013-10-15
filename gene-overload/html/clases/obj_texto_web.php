<?php


class Texto_Web
{

  var $id;
  var $id_categoria;
  var $texto;
  var $texto_en;

  var $nombre;



  // **************************************
  //      Eliminar un texto
  // **************************************

  function Eliminar($link_w, $idelemento)
  {
    $string = "DELETE FROM texto_web
		WHERE id = $idelemento
		";
    $query = mysql_query($string, $link_w);
  }


  // **************************************
  //      Alteramos un texto
  // **************************************

  function Alterar($link_w, $idelemento)
  {
    $string = "UPDATE texto_web
		SET texto = '$this->texto',
		texto_en = '$this->texto_en'
		WHERE id = $idelemento
		";
//echo $string;
    $query = mysql_query($string, $link_w);
  }


  // **************************************
  //      Sacamos un texto
  // **************************************

  function Sacar_Texto($link_r, $idelemento)
  {
    $string = "SELECT a.id, a.texto, a.texto_en, a.id_categoria, b.nombre
		FROM texto_web a, pagina_categoria b
		WHERE a.id_categoria = b.idcategoria
		AND a.id = $idelemento
		";
    $query = mysql_query($string, $link_r);
    if ($unquery = mysql_fetch_array($query))
    {
      $this->id = $unquery['id'];
      $this->texto = $unquery['texto'];
      $this->texto_en = $unquery['texto_en'];
      $this->nombre = $unquery['nombre'];
      $this->id_categoria = $unquery['id_categoria'];
      return true;
    } else {
      return false;
    }
  }


  // **************************************
 //      Sacamos datos x cat
  // **************************************

  function Sacar_Datos_Cat($link_r, $catid)
  {
    $string = "SELECT a.id, a.texto, a.texto_en, a.id_categoria, b.nombre
		FROM texto_web a, pagina_categoria b
		WHERE a.id_categoria = b.idcategoria
		AND a.id_categoria = $catid
		";
    $query = mysql_query($string, $link_r);
    if ($unquery = mysql_fetch_array($query))
    {
      $this->id = $unquery['id'];
      $this->texto = $unquery['texto'];
      $this->texto_en = $unquery['texto_en'];
      $this->nombre = $unquery['nombre'];
      $this->id_categoria = $unquery['id_categoria'];
      return true;
    } else {
      return false;
    }
  }


  // **************************************
  //      Sacamos todos los textos
  // **************************************

  function Buscar_Textos($link_r)
  {
    $string = "SELECT a.id, b.nombre
		FROM texto_web a, pagina_categoria b
		WHERE a.id_categoria = b.idcategoria
		";
//echo $string;
    $query = mysql_query($string, $link_r);
    $i = 0;
    while ($unquery = mysql_fetch_array($query))
    {
      $i++;
      $array[$i]['id'] = $unquery['id'];
      $array[$i]['nombre'] = $unquery['nombre'];
    }
    return $array;
  }



  // **************************************
  //       Insertamos un texto
  // **************************************

  function Insertar_Texto($link_w)
  {
    $string = "INSERT INTO texto_web
		(id_categoria, texto, texto_en)
		VALUES
		($this->idcategoria, '$this->texto', '$this->texto_en')
		";
//echo $string;
    $query = mysql_query($string, $link_w);
  }




}

?>
