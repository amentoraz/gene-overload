<?php

class Pagina_Categoria
{

  function Listar_Categorias($link_r)
  {

    $string = "SELECT id, idcategoria, fichero, nombre
		FROM pagina_categoria
		ORDER BY idcategoria
		";
    $query = mysql_query($string, $link_r);
    $i = 0;
    while ($unquery = mysql_fetch_array($query))
    {
      $i++;
      $array[$i]['id'] = $unquery['id'];
      $array[$i]['idcategoria'] = $unquery['idcategoria'];
      $array[$i]['fichero'] = $unquery['fichero'];
      $array[$i]['nombre'] = $unquery['nombre'];
    }
    return $array;

  }

}

?>
