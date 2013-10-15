<?php

class Encuesta
{

  // Tabla encuesta_pregunta
  var $id;
  var $pregunta;
  var $pregunta_en;
  var $fecha_inicio;
  var $fecha_fin;

  // Tabla encuesta_respuesta
  var $r_id;
  var $r_respuesta;
  var $r_respuesta_en;
  var $r_idencuesta;
  var $r_posicion;

  // Table encuesta_usuario_responde
  var $u_id;
  var $u_idusuario;
  var $u_idencuesta;
  var $u_fecha_respuesta;


  // ******************************
  //    OBtener la encuesta activa
  // ******************************

  function ObtenerRespuestas($link_r, $idencuesta)
  {
    $string = "SELECT id, respuesta, respuesta_en
		FROM encuesta_respuesta
		WHERE idencuesta = $idencuesta
		ORDER BY posicion ASC
		";
    $query = mysql_query($string, $link_r);
    while ($unquery = mysql_fetch_array($query))
    {
      $i++;
      $array[$i]['id'] = $unquery['id'];
      $array[$i]['respuesta'] = $unquery['respuesta'];
      $array[$i]['respuesta_en'] = $unquery['respuesta_en'];
      $array[$i]['idencuesta'] = $unquery['idencuesta'];
    }
    return $array;
  }


  // ******************************
  //    votos para una respuesta
  // ******************************

  function ObtenerVotosRespuesta($link_r, $idrespuesta)
  {

    $string = "SELECT id
		FROM encuesta_usuario_responde
		WHERE idrespuesta = $idrespuesta
		";
    $query = mysql_query($string, $link_r);
    return mysql_num_rows($query);

  }

  // ******************************
  //    gente que ha votado una respuesta
  // ******************************

  function ObtenerGenteRespuesta($link_r, $idrespuesta)
  {

    $string = "SELECT a.id, b.login
		FROM encuesta_usuario_responde a, jugador b
		WHERE a.idrespuesta = $idrespuesta
		AND a.idusuario = b.id
		";
    $query = mysql_query($string, $link_r);
    while ($unquery = mysql_fetch_array($query))
    {
      if ($texto == '')
      {
        $texto = $texto.$unquery['login'];
      } else {
        $texto = $texto.", ".$unquery['login'];
      }
    }
    return $texto;
  }


  // ******************************
  //    Respuesta marcada
  // ******************************

  function MarcarRespondido($link_w, $idencuesta, $idjugador, $cual)
  {
    $string = "INSERT INTO encuesta_usuario_responde
		(idusuario, idencuesta, fecha_respuesta, idrespuesta)
		VALUES
		($idjugador, $idencuesta, NOW(), $cual)
		";
    $query = mysql_query($string, $link_w);
  }

  // ******************************
  //    Ha respondido a la encuesta?
  // ******************************

  function HaRespondido($link_r, $idencuesta, $idjugador)
  {
    $string = "SELECT id
		FROM encuesta_usuario_responde
		WHERE idusuario = $idjugador
		AND idencuesta = $idencuesta
		";
    $query = mysql_query($string, $link_r);
    return (mysql_num_rows($query));
  }


  // ******************************
  //    OBtener la encuesta activa
  // ******************************

  function ObtenerEncuesta($link_r)
  {
    $string = "SELECT id, pregunta, pregunta_en
		FROM encuesta_pregunta
		WHERE fecha_inicio <= NOW()
		AND fecha_fin >= NOW()
		";
    $query = mysql_query($string, $link_r);
    if ($unquery = mysql_fetch_array($query))
    {
      $this->id = $unquery['id'];
      $this->pregunta = $unquery['pregunta'];
      $this->pregunta_en = $unquery['pregunta_en'];
      $this->fecha_inicio = $unquery['fecha_inicio'];
      $this->fecha_fin = $unquery['fecha_fin'];
      return 0;
    } else {
      return -1;
    }
  }


  // ******************************
  //    Obtiene todas
  // ******************************

  function ObtenerTodasEncuestas($link_r)
  {
    $string = "SELECT id, pregunta, pregunta_en, fecha_inicio, fecha_fin
		FROM encuesta_pregunta
		ORDER BY id ASC
		";
    $query = mysql_query($string, $link_r);
    $i = 0;
    while ($unquery = mysql_fetch_array($query))
    {
      $i++;
      $array[$i]['id'] = $unquery['id'];
      $array[$i]['pregunta'] = $unquery['pregunta'];
      $array[$i]['pregunta_en'] = $unquery['pregunta_en'];
      $array[$i]['fecha_inicio'] = $unquery['fecha_inicio'];
      $array[$i]['fecha_fin'] = $unquery['fecha_fin'];
    }
    return $array;
  }






  


}

