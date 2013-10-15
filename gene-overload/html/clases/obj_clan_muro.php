<?php

 class Clan_Muro
 {

   var $id;
   var $idusuario;
   var $idclan;
   var $fecha;
   var $texto;



   // ***********************************
   //    Buscar entradas en el muro
   // ***********************************

   function Escribir_Muro($link_w, $idclan, $idusuario, $texto)
   {
     $string = "INSERT INTO clan_muro
		(idjugador, idclan, fecha, texto)
		VALUES
		($idusuario, $idclan, NOW(), '$texto')
		";
//echo $string;
     $query = mysql_query($string, $link_w);
   }


   // ***********************************
   //    Contar total de entradas en el muro
   // ***********************************

   function Contar_Muro($link_r, $idclan)
   {
     $string = "SELECT id, idjugador, fecha, texto
		FROM clan_muro
		WHERE idclan = $idclan
		";
//echo $string;
     $query = mysql_query($string, $link_r);
     return mysql_num_rows($query);
   }

   // ***********************************
   //    Buscar entradas en el muro
   // ***********************************

   function Buscar_Muro($link_r, $idclan, $limit, $offset)
   {
     $string = "SELECT a.id, a.idjugador, a.fecha, a.texto, b.login
		FROM clan_muro a, jugador b
		WHERE a.idclan = $idclan
		AND a.idjugador = b.id
		ORDER BY a.fecha DESC
		LIMIT $limit OFFSET $offset
		";
     $query = mysql_query($string, $link_r);
     $i = 0;
     while ($unquery = mysql_fetch_array($query))
     {
       $i++;
       $array[$i]['login'] = $unquery['login'];
       $array[$i]['id'] = $unquery['id'];
       $array[$i]['idjugador'] = $unquery['idjugador'];
       $array[$i]['fecha'] = $unquery['fecha'];
       $array[$i]['texto'] = $unquery['texto'];
     }
     return $array;
   }




 }


?>
