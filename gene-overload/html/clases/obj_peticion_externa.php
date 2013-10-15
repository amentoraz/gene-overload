<?php

class Peticion_Externa
{

// **************************
//
//
//  Peticiones externas de Beta
//
//  CREATE TABLE peticion_externa
//  (
//    id int auto_increment not_null,
//    tipo int,
//    email varchar(255),
//    URL varchar(512),
//    nmiembros int,
//    fecha datetime,
//    aceptado tinyint,
//    rechazado tinyint,
//    idcodigo int,
//    PRIMARY KEY (id)
//  );
//
//  * tipo: usuario 2, clan 5, pagina 10
//
//  * idcodigo: id en la tabla de codigo_invitacion, para poder trackearlo
//
//  * URL: vale tanto para clan como para pagina, pero no se rellena con usuario
//
//  * nmiembros: Vale tanto para clan (miembros del clan) como pagina (gente que accede a ella).
//
//
//
//
//  Insertar_Peticion_Usuario() : Se carga el email en la clase antes
//  Insertar_Peticion_Clan() : Se cargan email, URL y nmiembros
//  Insertar_Peticion_Pagina() : Se cargan email, URL y nmiembros
//
//
//


  var $id;
  var $idcodigo;
  var $tipo;
  var $email;
  var $URL;
  var $nmiembros;
  var $fecha;
  var $aceptado;
  var $rechazado;
  var $lang;


  var $link_r;
  var $link_w;





  // *********************************************
  //    Contar todas las peticiones
  // *********************************************

  function Contar_Elementos($aceptado, $rechazado, $tipo)
  {
    $string = "SELECT id
		FROM peticion_externa
		WHERE aceptado = $aceptado
		AND rechazado = $rechazado
		";
    if ($tipo > 0)
    {
      $string = $string." AND tipo = $tipo ";
    }
//echo $string;

    $query = mysql_query($string, $this->link_r);
    return mysql_num_rows($query);
  }



  // *********************************************
  //    Asignar codigo
  // *********************************************

  function Asignar_Codigo($idelemento, $idcodigo)
  {
    $string = "UPDATE peticion_externa
		SET idcodigo = $idcodigo
		WHERE id = $idelemento
		";
    $query = mysql_query($string, $this->link_w);
  }




  // *********************************************
  //    Aceptar una solicitud
  // *********************************************

  function Aceptar($idelemento)
  {
    $string = "UPDATE peticion_externa
		SET aceptado = 1
		WHERE id = $idelemento
		";
    $query = mysql_query($string, $this->link_w);
  }


  // *********************************************
  //    Rechazar una solicitud
  // *********************************************

  function Rechazar($idelemento)
  {
    $string = "UPDATE peticion_externa
		SET rechazado = 1
		WHERE id = $idelemento
		";
    $query = mysql_query($string, $this->link_w);
  }


  // *********************************************
  //    Datos de un elemento
  // *********************************************

  function Sacar_Datos($idelemento)
  {
    $string = "SELECT id, tipo, email, URL, nmiembros,
		fecha, idcodigo, aceptado, rechazado, lang
		FROM peticion_externa
		WHERE id = $idelemento
		";
    $query = mysql_query($string, $this->link_r);
    if ($unquery = mysql_fetch_array($query))
    {
      $this->id = $unquery['id'];
      $this->tipo = $unquery['tipo'];
      $this->email = $unquery['email'];
      $this->URL = $unquery['URL'];
      $this->nmiembros = $unquery['nmiembros'];
      $this->fecha = $unquery['fecha'];
      $this->rechazado = $unquery['rechazado'];
      $this->aceptado = $unquery['aceptado'];
      $this->idcodigo = $unquery['idcodigo'];
      $this->lang = $unquery['lang'];
      return true;
    } else {
      return false;
    }

  }


  // *********************************************
  //    Buscar todas las peticiones
  // *********************************************

  function Buscar_Elementos($aceptado, $rechazado, $tipo, $offset, $limit)
  {
    $string = "SELECT id, tipo, email, URL, nmiembros,
		fecha, idcodigo
		FROM peticion_externa
		WHERE aceptado = $aceptado
		AND rechazado = $rechazado
		";
    if ($tipo > 0)
    {
      $string = $string." AND tipo = $tipo ";
    }
    $string = $string." ORDER BY fecha DESC
			LIMIT $limit
			OFFSET $offset
			";
//echo $string;
    $query = mysql_query($string, $this->link_r);
    $i = 0;
    while ($unquery = mysql_fetch_array($query))
    {
      $i++;
      $array[$i]['id'] = $unquery['id'];
      $array[$i]['tipo'] = $unquery['tipo'];
      $array[$i]['email'] = $unquery['email'];
      $array[$i]['URL'] = $unquery['URL'];
      $array[$i]['nmiembros'] = $unquery['nmiembros'];
      $array[$i]['fecha'] = $unquery['fecha'];
      $array[$i]['idcodigo'] = $unquery['idcodigo'];
    }
    return $array;
  }




  // *********************************************
  //    Comprobar si ya se ha pedido con esta dire
  // *********************************************

  function Comprobar_si_existe()
  {
    $string = "SELECT id
		FROM peticion_externa
		WHERE email = '$this->email'
		";
    $query = mysql_query($string, $this->link_r);
    return mysql_num_rows($query);
  }


  // *********************************************
  //    Insertar peticion de usuario
  // *********************************************
  //
  //   Solo hay que cargar el email en la clase

  function Insertar_Peticion_Usuario()
  {
    $string = "INSERT INTO peticion_externa
		(tipo, email, URL, nmiembros,
		fecha, aceptado, rechazado, idcodigo, lang)
		VALUES
		(2, '$this->email', '$this->URL', $this->nmiembros,
		NOW(), 0, 0, null, '$this->lang')
		";
//echo $string;
    $query = mysql_query($string, $this->link_w);
  }


  // *********************************************
  //    Insertar peticion de clan
  // *********************************************
  //
  //  Cargamos en la clase :
  //
  //  * Email
  //  * URL del clan
  //  * nmiembros del clan
  //

  function Insertar_Peticion_Clan()
  {
    $string = "INSERT INTO peticion_externa
		(tipo, email, URL, nmiembros,
		fecha, aceptado, rechazado, idcodigo, lang)
		VALUES
		(5, '$this->email', '$this->URL', $this->nmiembros,
		NOW(), 0, 0, null, '$this->lang')
		";
    $query = mysql_query($string, $this->link_w);
  }



  // *********************************************
  //    Insertar peticion de pagina
  // *********************************************
  //
  //  Cargamos en la clase :
  //
  //  * Email
  //  * URL de la pagina
  //  * visitas / gente que pueda venir
  //

  function Insertar_Peticion_Pagina()
  {
    $string = "INSERT INTO peticion_externa
		(tipo, email, URL, nmiembros,
		fecha, aceptado, rechazado, idcodigo, lang)
		VALUES
		(10, '$this->email', '$this->URL', $this->nmiembros,
		NOW(), 0, 0, null, '$this->lang')
		";
    $query = mysql_query($string, $this->link_w);
  }





}

?>
