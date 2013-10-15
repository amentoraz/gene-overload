<?php

// ##########################################################################
// ##                                                                      ##
// ##  Nombre de la clase:   Invitacion_Extendido                          ##
// ##  Fecha de creacion:    04/may/2011                                   ##
// ##  Ultima version:       04/may/2011                                   ##
// ##  Funcionalidad:        Gestion de los codigos de invitacion          ##
// ##                                                                      ##
// ##########################################################################
//
//
//  En esta clase se encuentran las funciones para invitaciones extendidas.
// De este modo aligeramos un poco la principal de invitacion.
//
//  CREATE TABLE codigo_invitacion_solicitar
//  (
//    id int auto_increment not null,
//    idusuario int,
//    fecha_solicitud datetime,
//    tipo int,
//    idclan int,
//    aceptada tinyint,
//    rechazada tinyiny,
//    PRIMARY KEY (id)
//  );
//
//
//
//  CREATE TABLE codigo_invitacion_extender
//  (
//    id int auto_increment not null,
//    idusuario int,
//    usos_extendidos int,
//    PRIMARY KEY (id)
//  );
//
//
//
//  La tabla de invitacion_solicitar es utilizada por las peticiones de
// usuarios. Se guarda el tipo (1=usuario, 2=clan y 3=admin).
//
//  Al concederse, hay tres casos:
//
//  - Caso de usuario -> Se mete una entrada en invitacion_extender, con el
// idusuario, y con el numero de usos_extendidos. Esto se ha de comprobar
// tambien al ir a ver las pendientes.
//
//  - Caso de clan -> Se aumentan los usos
//
//  - Caso de admin -> Se aumentan los usos
//
//

class Invitacion_Extendido
{

  var $idusuario;
  var $fecha_solicitud;

  var $link_r;
  var $link_w;




  // ****************************************
  //   Aceptar una solicitud
  // ****************************************

  function Marcar_Aceptado($idelemento)
  {
    $string = "UPDATE codigo_invitacion_solicitar
		SET aceptada = 1
		WHERE id = $idelemento
		";
    $query = mysql_query($string, $this->link_w);
  }

  // ****************************************
  //   Rechazar una solicitud
  // ****************************************

  function Marcar_Rechazado($idelemento)
  {
    $string = "UPDATE codigo_invitacion_solicitar
		SET rechazada = 1
		WHERE id = $idelemento
		";
    $query = mysql_query($string, $this->link_w);
  }

  // ****************************************
  //   Proporcionar una extension (usuario)
  // ****************************************

  function Extender_Usuario($idusuario, $cuantos)
  {
    $string = "SELECT id, usos_extendidos
		FROM codigo_invitacion_extender
		WHERE idusuario = $idusuario
		";
//echo $string;
    $query = mysql_query($string, $this->link_r);
    if ($unquery = mysql_fetch_array($query))
    {
      $idcod = $unquery['id'];
      $total_nuevo = $unquery['usos_extendidos'] + $cuantos;
      $string = "UPDATE codigo_invitacion_extender
		SET usos_extendidos = $total_nuevo
		WHERE id = $idcod
		";
//echo $string;
      $query = mysql_query($string, $this->link_w);
    } else {
      $string = "INSERT INTO codigo_invitacion_extender
		(idusuario, usos_extendidos)
		VALUES
		($idusuario, $cuantos)
		";
//echo $string;
      $query = mysql_query($string, $this->link_w);
    }
  }

  // ****************************************
  //   Proporcionar una extension (clan)
  // ****************************************

  function Extender_Clan($idclan, $cuantos)
  {
    $string = "SELECT id, usos
		FROM codigo_invitacion
		WHERE id_clan_origen = $idclan
		";
    $query = mysql_query($string, $this->link_r);
    if ($unquery = mysql_fetch_array($query))
    {
      $idcodigo = $unquery['id'];
      $usos = $unquery['usos'];
      $usos = $usos + $cuantos;
      $string2 = "UPDATE codigo_invitacion
		SET usos = $usos
		WHERE id = $idcodigo
		";
      $query2 = mysql_query($string2, $this->link_w);
    }
  }

  // ****************************************
  //   Proporcionar una extension (admin)
  // ****************************************

  function Extender_Admin($idsolicitud, $cuantos)
  {
    $string = "SELECT id, usos
		FROM codigo_invitacion
		WHERE id = $idsolicitud
		";
    $query = mysql_query($string, $this->link_r);
    if ($unquery = mysql_fetch_array($query))
    {
      $idcodigo = $unquery['id'];
      $usos = $unquery['usos'];
      $usos = $usos + $cuantos;
      $string2 = "UPDATE codigo_invitacion
		SET usos = $usos
		WHERE id = $idcodigo
		";
      $query2 = mysql_query($string2, $this->link_w);
    }
  }



  // ***************************************************
  //     Busca las solicitudes de usuarios y clanes
  // ***************************************************

  function Sacar_Datos($idsolicitud)
  {
    $string = "SELECT id, idusuario, fecha_solicitud,
		tipo, idclan, aceptada, rechazada,
		cuantos
		FROM codigo_invitacion_solicitar
		WHERE id = $idsolicitud
		";
//echo $string;
    $query = mysql_query($string, $this->link_r);
    if ($unquery = mysql_fetch_array($query))
    {
      $array['id'] = $unquery['id'];
      $array['idusuario'] = $unquery['idusuario'];
      $array['idclan'] = $unquery['idclan'];
      $array['fecha_solicitud'] = $unquery['fecha_solicitud'];
      $array['tipo'] = $unquery['tipo'];
      $array['aceptada'] = $unquery['aceptada'];
      $array['rechazada'] = $unquery['rechazada'];
      $array['cuantos'] = $unquery['cuantos'];
    }
    return $array;
  }


  // ***************************************************
  //     Busca las solicitudes de usuarios y clanes
  // ***************************************************

  // El tipo puede ser 0 (todos), 2 (usuario) y 5 (clan)
  function Buscar_Solicitudes($tipo, $atendida, $rechazada)
  {
    $string = "SELECT id, idusuario, fecha_solicitud, tipo, idclan, cuantos
		FROM codigo_invitacion_solicitar
		WHERE 1=1
		";
    if ($tipo != 0)
    {
      $string = $string." AND tipo = ".$tipo." ";
    }
    if ($atendida != 0)
    {
      $string = $string." AND aceptada = 1 ";
    } else {
      if ($rechazada != 0)
      {
        $string = $string." AND rechazada = 1 ";
      } else {
        $string = $string." AND rechazada = 0 AND aceptada = 0 ";
      }
    }
//echo $string;
    $query = mysql_query($string, $this->link_r);
    $i = 0;
    while ($unquery = mysql_fetch_array($query))
    {
      $i++;
      $array[$i]['id'] = $unquery['id'];
      $array[$i]['idusuario'] = $unquery['idusuario'];
      $array[$i]['fecha_solicitud'] = $unquery['fecha_solicitud'];
      $array[$i]['tipo'] = $unquery['tipo'];
      $array[$i]['idclan'] = $unquery['idclan'];
      $array[$i]['cuantos'] = $unquery['cuantos'];
    }
    return $array;
  }


  // ***************************************************
  //     cuentalas solicitudes de usuarios y clanes
  // ***************************************************

  // El tipo puede ser 0 (todos), 2 (usuario) y 5 (clan)
  function Contar_Solicitudes($tipo, $atendida, $rechazada)
  {
    $string = "SELECT id
		FROM codigo_invitacion_solicitar
		WHERE 1=1
		";
    if ($tipo != 0)
    {
      $string = $string." AND tipo = ".$tipo." ";
    }
    if ($atendida != 0)
    {
      $string = $string." AND aceptada = 1 ";
    } else {
      if ($rechazada != 0)
      {
        $string = $string." AND rechazada = 1 ";
      } else {
        $string = $string." AND rechazada = 0 AND aceptada = 0 ";
      }
    }
//echo $string;
    $query = mysql_query($string, $this->link_r);
    return mysql_num_rows($query);
  }


  // ***************************************************
  //     Busca las solicitudes de usuarios y clanes
  // ***************************************************

  // El tipo puede ser 0 (todos), 2 (usuario) y 5 (clan)
  function Buscar_Solicitudes_Limit($tipo, $atendida, $rechazada, $limit, $offset)
  {
    $string = "SELECT id, idusuario, fecha_solicitud, tipo, idclan, cuantos
		FROM codigo_invitacion_solicitar
		WHERE 1=1
		";
    if ($tipo != 0)
    {
      $string = $string." AND tipo = ".$tipo." ";
    }
    if ($atendida != 0)
    {
      $string = $string." AND aceptada = 1 ";
    } else {
      if ($rechazada != 0)
      {
        $string = $string." AND rechazada = 1 ";
      } else {
        $string = $string." AND rechazada = 0 AND aceptada = 0 ";
      }
    }
    $string = $string." LIMIT ".$limit." OFFSET ".$offset;
//echo $string;
    $query = mysql_query($string, $this->link_r);
    $i = 0;
    while ($unquery = mysql_fetch_array($query))
    {
      $i++;
      $array[$i]['id'] = $unquery['id'];
      $array[$i]['idusuario'] = $unquery['idusuario'];
      $array[$i]['fecha_solicitud'] = $unquery['fecha_solicitud'];
      $array[$i]['tipo'] = $unquery['tipo'];
      $array[$i]['idclan'] = $unquery['idclan'];
      $array[$i]['cuantos'] = $unquery['cuantos'];
    }
    return $array;
  }



  // ****************************************
  //     Insertar solicitudes. Esto solo lo pueden hacer usuarios y clanes
  // ****************************************

  function Insertar_Solicitud_Usuario($idelemento, $cuantos)
  {
    $string = "INSERT INTO codigo_invitacion_solicitar
		(idusuario, fecha_solicitud, tipo, idclan, aceptada, rechazada, cuantos)
		VALUES
		($idelemento, NOW(), 2, 0, 0, 0, $cuantos)
		";
    $query = mysql_query($string, $this->link_w);
  }

  function Insertar_Solicitud_Clan($idclan, $idelemento, $cuantos)
  {
    $string = "INSERT INTO codigo_invitacion_solicitar
		(idusuario, fecha_solicitud, tipo, idclan, cuantos)
		VALUES
		($idelemento, NOW(), 5, $idclan, $cuantos)
		";
    $query = mysql_query($string, $this->link_w);
  }



  // ****************************************
  //     Existe ya una solicitud de este usuario?
  // ****************************************

  function Existe_Solicitud_Pendiente_Usuario($idusuario)
  {
    $string = "SELECT id
		FROM codigo_invitacion_solicitar
		WHERE idusuario = $idusuario
		AND tipo = 2
		AND rechazada = 0
		AND aceptada = 0
		";
    $query = mysql_query($string, $this->link_r);
    return mysql_num_rows($query);
  }


  // ****************************************
  //     Existe ya una solicitud de este usuario?
  // ****************************************

  function Existe_Solicitud_Pendiente_clan($idclan)
  {
    $string = "SELECT id
		FROM codigo_invitacion_solicitar
		WHERE idclan = $idclan
		AND tipo = 5
		AND rechazada = 0
		AND aceptada = 0
		";
    $query = mysql_query($string, $this->link_r);
    return mysql_num_rows($query);
  }





}
