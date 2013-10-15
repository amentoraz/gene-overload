<?php

class Torneo
{

	var $id;
	var $nombre;
	var $fecha_realizacion;
	var $num_premios;
	var $idescenario;
	var $unix_begin;
	var $unix_end;
	var $arbol_oro;
	var $arbol_plata;
	var $arbol_bronce;
	var $niveles_oro;
	var $niveles_plata;
	var $niveles_bronce;
	var $idjugador_oro;
	var $idjugador_plata;
	var $idjugador_bronce;



	// ***********************************************
        //      Sacar datos del ultimo torneo celebrado
	// ***********************************************

        function SacarDatosUltima($link_r, $idcampana)
        {
          $string = "SELECT id, nombre, fecha_realizacion,
		num_premios, unix_begin, unix_end,
		arbol_oro, arbol_plata, arbol_bronce,
		niveles_oro, niveles_plata, niveles_bronce,
		idjugador_oro, idjugador_plata, idjugador_bronce,
		iddeme_oro, iddeme_plata, iddeme_bronce
		FROM torneo
		WHERE idcampana = $idcampana
		ORDER BY id DESC
		";
//echo $string;		
          $query = mysql_query($string, $link_r);
          if ($unquery = mysql_fetch_array($query))
          {
	    $this->id = $unquery['id'];
	    $this->nombre = $unquery['nombre'];
	    $this->fecha_realizacion = $unquery['fecha_realizacion'];
	    $this->num_premios = $unquery['num_premios'];
	    $this->unix_begin = $unquery['unix_begin'];
	    $this->unix_end = $unquery['unix_end'];
	    $this->arbol_oro = $unquery['arbol_oro'];
	    $this->arbol_plata = $unquery['arbol_plata'];
	    $this->arbol_bronce = $unquery['arbol_bronce'];
	    $this->niveles_oro = $unquery['niveles_oro'];
	    $this->niveles_plata = $unquery['niveles_plata'];
	    $this->niveles_bronce = $unquery['niveles_bronce'];
	    $this->idjugador_oro = $unquery['idjugador_oro'];
	    $this->idjugador_plata = $unquery['idjugador_plata'];
	    $this->idjugador_bronce = $unquery['idjugador_bronce'];
	    $this->iddeme_oro = $unquery['iddeme_oro'];
	    $this->iddeme_plata = $unquery['iddeme_plata'];
	    $this->iddeme_bronce = $unquery['iddeme_bronce'];
          } else {
            return -1;
          }
        }

	// ***********************************************
        //               Contar torneos
	// ***********************************************

        function SacarDatos($link_r, $idelemento)
        {
          $string = "SELECT id, nombre, fecha_realizacion,
		num_premios, unix_begin, unix_end,
		arbol_oro, arbol_plata, arbol_bronce,
		niveles_oro, niveles_plata, niveles_bronce,
		idjugador_oro, idjugador_plata, idjugador_bronce,
		iddeme_oro, iddeme_plata, iddeme_bronce
		FROM torneo
		WHERE id = $idelemento
		";
          $query = mysql_query($string, $link_r);
          if ($unquery = mysql_fetch_array($query))
          {
	    $this->id = $unquery['id'];
	    $this->nombre = $unquery['nombre'];
	    $this->fecha_realizacion = $unquery['fecha_realizacion'];
	    $this->num_premios = $unquery['num_premios'];
	    $this->unix_begin = $unquery['unix_begin'];
	    $this->unix_end = $unquery['unix_end'];
	    $this->arbol_oro = $unquery['arbol_oro'];
	    $this->arbol_plata = $unquery['arbol_plata'];
	    $this->arbol_bronce = $unquery['arbol_bronce'];
	    $this->niveles_oro = $unquery['niveles_oro'];
	    $this->niveles_plata = $unquery['niveles_plata'];
	    $this->niveles_bronce = $unquery['niveles_bronce'];
	    $this->idjugador_oro = $unquery['idjugador_oro'];
	    $this->idjugador_plata = $unquery['idjugador_plata'];
	    $this->idjugador_bronce = $unquery['idjugador_bronce'];
	    $this->iddeme_oro = $unquery['iddeme_oro'];
	    $this->iddeme_plata = $unquery['iddeme_plata'];
	    $this->iddeme_bronce = $unquery['iddeme_bronce'];
          }
        }

	// ***********************************************
        //               Contar torneos
	// ***********************************************

        function BuscarTorneos($link_r, $limit, $offset)
        {
          $string = "SELECT id, nombre, fecha_realizacion,
		num_premios, unix_begin, unix_end
		FROM torneo
		ORDER BY id DESC
		LIMIT $limit OFFSET $offset
		";
          $query = mysql_query($string, $link_r);
          $i = 0;
          while ($unquery = mysql_fetch_array($query))
          {
            $i++;
            $array[$i]['id'] = $unquery['id'];
            $array[$i]['nombre'] = $unquery['nombre'];
            $array[$i]['fecha_realizacion'] = $unquery['fecha_realizacion'];
            $array[$i]['num_premios'] = $unquery['num_premios'];
            $array[$i]['unix_begin'] = $unquery['unix_begin'];
            $array[$i]['unix_end'] = $unquery['unix_end'];
          }
          return $array;
        }

	// ***********************************************
        //               Contar torneos
	// ***********************************************

        function ContarTorneos($link_r)
        {
          $string = "SELECT id
			FROM torneo
			";
          $query = mysql_query($string, $link_r);
          return mysql_num_rows($query);
        }




	// ***********************************************
        //            Grabar demes de los cargos
	// ***********************************************

        function GrabarDemesTorneo($link_w, $iddeme_oro, $iddeme_plata, $iddeme_bronce, $idtorneo)
        {
          $string = "UPDATE torneo
		SET iddeme_oro = $iddeme_oro,
		iddeme_plata = $iddeme_plata,
		iddeme_bronce = $iddeme_bronce
		WHERE id = $idtorneo
		";
echo $string;
          $query = mysql_query($string, $link_w);
        }

	// ***********************************************
        //            Grabar propietarios de los arboles
	// ***********************************************

        function GrabarPropietariosTorneo($link_w, $idjugador_oro, $idjugador_plata, $idjugador_bronce, $idtorneo)
        {
          $string = "UPDATE torneo
		SET idjugador_oro = $idjugador_oro,
		idjugador_plata = $idjugador_plata,
		idjugador_bronce = $idjugador_bronce
		WHERE id = $idtorneo
		";
echo $string;
          $query = mysql_query($string, $link_w);
        }


	// ***********************************************
        //              Niveles
	// ***********************************************

        function GrabarNivelesTorneo($link_w, $niveles_oro, $niveles_plata, $niveles_bronce, $idtorneo)
        {
          $string = "UPDATE torneo
		SET niveles_oro = $niveles_oro,
		niveles_plata = $niveles_plata,
		niveles_bronce = $niveles_bronce
		WHERE id = $idtorneo
		";
echo $string;
          $query = mysql_query($string, $link_w);
        }

	// ***********************************************
        //               Fechas del torneo
	// ***********************************************

	function GrabarArbolesTorneo($link_w, $arbol_oro, $arbol_plata, $arbol_bronce, $idtorneo)
	{
          $string = "UPDATE torneo
		SET arbol_oro = '".mysql_real_escape_string($arbol_oro, $link_w)."',
		  arbol_plata = '".mysql_real_escape_string($arbol_plata, $link_w)."',
		  arbol_bronce = '".mysql_real_escape_string($arbol_bronce, $link_w)."'
		WHERE id = $idtorneo
		";
echo $string;
          $query = mysql_query($string, $link_w);
        }


	// ***********************************************
        //               Fechas del torneo
	// ***********************************************

	function GrabarTiempoTorneo($link_w, $unix_begin, $unix_end, $idtorneo)
	{
	  $string = "UPDATE torneo
		SET unix_begin = '$unix_begin',
		unix_end = '$unix_end'
		WHERE id = $idtorneo
		";
echo $string;
          $query = mysql_query($string, $link_w);
	}




	// ***********************************************
        //               Torneo standard
	// ***********************************************

	function InsertarTorneoStandard($link_w, $idcampana)
	{
          $string = "INSERT INTO torneo
		(nombre, fecha_realizacion, num_premios, idescenario, idcampana)
		VALUES
		('Torneo standard', NOW(), 1, null, $idcampana)
		";
	  $query = mysql_query($string, $link_w);
	}


}

?>
