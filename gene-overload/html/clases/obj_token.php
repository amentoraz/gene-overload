<?php

class Token
{

  var $id;
  var $idcampana;
  var $idjugador;
  var $fecha_ultimo_cambio;
  var $numcambios;


  // *********************************************
  //    Calcular tiempo desde el ultimo cambio
  // *********************************************

  function CalcularSegundos($link_r, $idcampana)
  {
//select (TIME_TO_SEC(NOW()) - TIME_TO_SEC(fecha_ultimo_cambio)) AS segundos
    $string = "select time_to_sec(timediff(NOW(), fecha_ultimo_cambio)) AS segundos
		FROM token
		WHERE idcampana = ".$idcampana;
    $query = mysql_query($string, $link_r);
    if ($unquery = mysql_fetch_array($query))
    {
      return $unquery['segundos'];
    }
  }

  // *********************************************
  //    Sacar datos del que tiene ahora el token
  // *********************************************

  function SacarDatos($link_r, $idcampana)
  {
    $string = "SELECT id, idcampana, idjugador, fecha_ultimo_cambio, numcambios
		FROM token
		WHERE idcampana = $idcampana
		";
    $query = mysql_query($string, $link_r);
    if ($unquery = mysql_fetch_array($query))
    {
      $this->id = $unquery['id'];
      $this->idcampana = $unquery['idcampana'];
      $this->idjugador = $unquery['idjugador'];
      $this->fecha_ultimo_cambio = $unquery['fecha_ultimo_cambio'];
      $this->numcambios = $unquery['numcambios'];
    }
  }

  // ********************************
  //   Cambiar quien es el jugador que tiene el token
  // ********************************

  function AlterarJugador($link_w, $idcampana, $idjugador)
  {
    $string = "UPDATE token
		SET idjugador = $idjugador,
		fecha_ultimo_cambio = NOW()
		WHERE idcampana = $idcampana
		";
    $query = mysql_query($string, $link_w);
  }




}

?>
