<?php

   class Objeto
   {

     var $id;
     var $tipo;
     var $cantidad;
     var $idjugadorcampana;


/*
    // **********************************************************
    //   Reordenar el inventario
    // **********************************************************

    function ReordenarInventario($link_w, $idjugador, $idcampana)
    {
      $string = "SELECT a.id
		FROM objeto a, jugador_campana b
		WHERE a.idjugadorcampana = b.id
		AND b.idjugador = $idjugador
		AND b.idcampana = $idcampana
		ORDER BY 
                ";
      $query = mysql_query($string, $link_w);
      while ($unquery = mysql_fetch_array($query))
      {
        
      }
    }
*/

    // **********************************************************
    //   Eliminar un objeto
    // **********************************************************

    function EliminarElemento($link_w, $idobjeto)
    {
      $string = "DELETE FROM objeto
                WHERE id = $idobjeto
                ";
      $query = mysql_query($string, $link_w);
    }


     // ****************************************************
     //    Mete un nuevo objeto
     // ****************************************************

     function InsertarObjeto($link_w, $idjugadorcampana)
     {
       $string = " INSERT INTO objeto
			(tipo, cantidad, idjugadorcampana)
			VALUES
			($this->tipo, $this->cantidad, $idjugadorcampana)
		";
       $query = mysql_query($string, $link_w);
     }


     // ****************************************************
     //         Numero de elementos en tu inventario
     // ****************************************************

     function ContarInventario($link_r, $idjugadorcampana)
     {
       $string = "SELECT id, tipo, cantidad
		FROM objeto
		WHERE idjugadorcampana = $idjugadorcampana
		";
       $query = mysql_query($string, $link_r);
       return mysql_num_rows($query);
     }



     // ****************************************************
     //         Numero de elementos en tu inventario
     // ****************************************************

     function SacarDatos($link_r, $idelemento)
     {
       $string = "SELECT id, tipo, cantidad, idjugadorcampana
		FROM objeto
		WHERE id = $idelemento
		";
       $query = mysql_query($string, $link_r);
       if ($unquery = mysql_fetch_array($query))
       {
         $this->idjugadorcampana = $unquery['idjugadorcampana'];
         $this->id = $unquery['id'];
         $this->tipo = $unquery['tipo'];
         $this->cantidad = $unquery['cantidad'];
       }
     }


     // ****************************************************
     //               Obtienes tu inventario
     // ****************************************************

     function ObtenerInventario($link_r, $idjugadorcampana)
     {
       $string = "SELECT id, tipo, cantidad
		FROM objeto
		WHERE idjugadorcampana = $idjugadorcampana
		ORDER BY id ASC
		";
       $query = mysql_query($string, $link_r);
       $i = 0;
       while ($unquery = mysql_fetch_array($query))
       {
         $i++;
         $array[$i]['id'] = $unquery['id'];
         $array[$i]['tipo'] = $unquery['tipo'];
         $array[$i]['cantidad'] = $unquery['cantidad'];
       }
       return $array;
     }

   }

?>
