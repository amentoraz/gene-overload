<?php

class Clan
{

	// de la tabla clan
	var $id;
	var $nombre;
	var $presentacion;
	var $fecha_fundacion;
	var $idavatar;
        var $nmiembros;
	var $activo;
        var $identificador;
	var $ruta_avatar;

	// de la tabla clan_jugador
	var $j_id;
	var $j_idjugadorcampana;
	var $j_idclan;
	var $j_fecha_union;
	var $j_baneado;
	var $j_solicitado;
	var $j_solicitado_declinado;
	var $j_invitado;
	var $j_invitado_declinado;
	var $j_aceptado;
	var $j_fundador;
	var $j_administrador;

	// variables de la bbdd
	var $link_r;
	var $link_w;


	// auxiliares
	var $lang;
	var $idjugador;


  // ***********************************************
  //   QuitarAdmin
  // ***********************************************

  function QuitarAdmin($idjugadorcampana, $idclan)
  {
    $string = "UPDATE
		clan_jugador
		SET administrador = 0
		WHERE idjugadorcampana = $idjugadorcampana
		AND idclan = $idclan
		";
    $query = mysql_query($string, $this->link_w);
  }

  // ***********************************************
  //   HacerAdmin
  // ***********************************************

  function HacerAdmin($idjugadorcampana, $idclan)
  {
    $string = "UPDATE
		clan_jugador
		SET administrador = 1
		WHERE idjugadorcampana = $idjugadorcampana
		AND idclan = $idclan
		";
    $query = mysql_query($string, $this->link_w);
  }


  // ***********************************************
  //   Disolver el equipo
  // ***********************************************

  function DisolverClan($idclan)
  {
    $string = "DELETE
		FROM clan_jugador
		WHERE idclan = $idclan
		";
    $query = mysql_query($string, $this->link_w);

    $string = "DELETE
		FROM clan
		WHERE id = $idclan
		";
    $query = mysql_query($string, $this->link_w);
  }


  // ***********************************************
  //   Dejar un clan
  // ***********************************************

  function DejarClan($idjugadorcampana, $idclan)
  {
    $string = "DELETE
		FROM clan_jugador
		WHERE idjugadorcampana = $idjugadorcampana
		AND idclan = $idclan
		";
    $query = mysql_query($string, $this->link_w);
  }


  // ***********************************************
  //   Obtiene quienes son los jefes de un clan
  // ***********************************************

  function ObtenerJefes($idclan)
  {
    $string = "(
		SELECT a.idjugador
		FROM jugador_campana a, clan_jugador b
		WHERE b.idclan = $idclan
		AND b.administrador = 1
		AND b.idjugadorcampana = a.id
		) UNION (
		SELECT a.idjugador
		FROM jugador_campana a, clan_jugador b
		WHERE b.idclan = $idclan
		AND b.fundador = 1
		AND b.idjugadorcampana = a.id
		)
		";
    $query = mysql_query($string, $this->link_r);
    $i = 0;
    while ($unquery = mysql_fetch_array($query))
    {
      $i++;
      $array[$i]['idjugador'] = $unquery['idjugador'];
    }
    return $array;
  }



  // ***********************************************
  //   Acepta una invitacion
  // ***********************************************

  function Aceptar_Invitacion($idsolicitud, $idclan)
  {

    // Lo ponemos como invitado Y tambien aumentamos el nmiembros del clan
    $string = "UPDATE clan_jugador
		SET aceptado = 1
		WHERE id = $idsolicitud
		";
    $query = mysql_query($string, $this->link_w);

    // Ahora aumentamos los nmiembros
    $this->IncrementarMiembros($idclan);

  }

  // ***********************************************
  //   Rechaza una invitacion
  // ***********************************************

  function Rechazar_Invitacion($idsolicitud, $idclan)
  {

    // Lo ponemos como invitado_declinado
    $string = "UPDATE clan_jugador
		SET invitado_declinado = 1
		WHERE id = $idsolicitud
		";
    $query = mysql_query($string, $this->link_w);

  }

  // ***********************************************
  //   Esta invitado este jugador a este clan?
  // ***********************************************

  function EstaInvitado($idjugador, $idcampana, $idclan)
  {
    $string = "SELECT a.id
		FROM clan_jugador a, jugador_campana b
		WHERE a.idjugadorcampana = b.id
		AND b.idjugador = $idjugador
		AND b.idcampana = $idcampana
		AND a.idclan = $idclan
		AND a.invitado = 1
		AND a.invitado_declinado = 0
		AND a.aceptado = 0
		";
    $query = mysql_query($string, $this->link_r);
    if ($unquery = mysql_fetch_array($query))
    {
      return $unquery['id'];
    } else {
      return -1;
    }
  }

  // ***********************************************
  //   Invita a este jugador
  // ***********************************************

  function InvitarUsuario($idjugadorcampana, $idclan)
  {
    $string = "INSERT INTO clan_jugador
		(idjugadorcampana, idclan, fecha_union,
		baneado, solicitado, invitado,
		invitado_declinado, aceptado, fundador,
		administrador, solicitado_declinado)
		VALUES
		($idjugadorcampana, $idclan, NOW(),
		0, 0, 1,
		0, 0, 0,
		0, 0)
		";
    $query = mysql_query($string, $this->link_w);
  }

  // ***********************************************
  //   A este jugador se le puede invitar?
  // ***********************************************

  function EsInvitable($idjugador, $idcampana, $idclan)
  {
    // Vamos a comprobar...
    $string = "SELECT a.baneado, a.solicitado,
		a.invitado, a.invitado_declinado,
		a.aceptado, a.fundador, a.administrador,
		a.solicitado_declinado, a.idclan, a.idjugadorcampana
		FROM clan_jugador a, jugador_campana b
		WHERE b.idjugador = $idjugador
		AND b.idcampana = $idcampana
		AND a.idjugadorcampana = b.id
		AND a.idclan = $idclan
		";
//echo $string;
    $query = mysql_query($string, $this->link_r);
    if (mysql_num_rows($query) > 0)
    {

      return false;
      // Las condiciones para que se le pueda invitar son:
      //
      // * No puede estar aceptado y no baneado en ningun clan
      // * No puede haber solicitado la entrada en este clan
      // * No puede haber sido ya invitado a este clan
//      $resultado = true;
//      while ($unquery = mysql_fetch_array($query))
//      {
//        if (($unquery['aceptado'] == 1) && ($unquery['baneado'] == 0)) { $resultado = false; }
//        if (($unquery['solicitado'] == 1) && ($unquery['idclan'] == $idclan)) { $resultado = false; }
//        if (($unquery['invitado'] == 1) && ($unquery['idclan'] == $idclan)) { $resultado = false; }
//      }
//      return $resultado;
    } else {
      // Si esta limpio, si es invitable
      return true;
    }
  }


  // ***********************************************
  //   Banear a un jugador
  // ***********************************************

  function Banear($idjugadorcampana, $idclan)
  {
    $string = "UPDATE
		clan_jugador
		SET baneado = 1
		WHERE idclan = $idclan
		AND idjugadorcampana = $idjugadorcampana
		";
    $query = mysql_query($string, $this->link_w);

      // Pero ademas, debemos coger el numero de miembros del clan y restarle uno
   // STOP esto se hace fuera
//    $this->DecrementarMiembros($idclan);

  }


  // ***********************************************
  //   Listar los miembros de un clan
  // ***********************************************

  function Listar_Miembros($idclan)
  {
  	 $string = "SELECT a.id, a.idjugadorcampana, a.idclan, b.niveles_arbol,
  	 		a.fecha_union, c.login, c.email, c.es_premium,
  	 		a.fundador, a.administrador, c.id AS idjugador
  	 		FROM clan_jugador a, jugador_campana b, jugador c
  	 		WHERE a.idjugadorcampana = b.id
  	 		AND b.idjugador = c.id
  	 		AND a.idclan = $idclan
  	 		AND a.baneado = 0
  	 		AND a.aceptado = 1
  	 		AND a.invitado_declinado = 0
  	 		AND a.solicitado_declinado = 0
  	 		";

  	 $query = mysql_query($string, $this->link_r);
  	 $i = 0;
  	 while ($unquery = mysql_fetch_array($query))
  	 {  	 	
 
  	 	$i++;
  	 	$array[$i]['id'] = $unquery['id'];
  	 	$array[$i]['idjugadorcampana'] = $unquery['idjugadorcampana'];
  	 	$array[$i]['idclan'] = $unquery['idclan'];
  	 	$array[$i]['niveles_arbol'] = $unquery['niveles_arbol'];
  	 	$array[$i]['fecha_union'] = $unquery['fecha_union'];
  	 	$array[$i]['login'] = $unquery['login'];  	 	
  	 	$array[$i]['email'] = $unquery['email'];
  	 	$array[$i]['es_premium'] = $unquery['es_premium'];
  	 	$array[$i]['fundador'] = $unquery['fundador'];
  	 	$array[$i]['administrador'] = $unquery['administrador'];
  	 	$array[$i]['idjugador'] = $unquery['idjugador'];
  	 }
  	 return $array;	
  }

  // ***********************************************
  //   Lista las solicitudes de entrada
  // ***********************************************

  function DecrementarMiembros($idclan)
  {
  	 $string = "SELECT nmiembros
  	 				FROM clan
  	 				WHERE id = $idclan
  	 					";

  	 $query = mysql_query($string, $this->link_r);
  	 if ($unquery=mysql_fetch_array($query))
  	 {
  	 	$nmiembros = $unquery['nmiembros'];
  	 	$nmiembros--;
  	 	$string2 = "UPDATE clan
  	 		SET nmiembros = $nmiembros
			WHERE id = $idclan
			";
//echo $unquery['nmiembros']."-".$string2;
	   $query2 = mysql_query($string2, $this->link_w);
  	 }
  }
  
  // ***********************************************
  //   Incrementa las solicitudes de entrada
  // ***********************************************

  function IncrementarMiembros($idclan)
  {
  	 $string = "SELECT nmiembros
  	 				FROM clan
  	 				WHERE id = $idclan
  	 					";

  	 $query = mysql_query($string, $this->link_r);
  	 if ($unquery=mysql_fetch_array($query))
  	 {
  	 	$nmiembros = $unquery['nmiembros'];
  	 	$nmiembros++;
  	 	$string2 = "UPDATE clan
  	 		SET nmiembros = $nmiembros
			WHERE id = $idclan
			";
			
	   $query2 = mysql_query($string2, $this->link_w);
  	 }
  }
  
  // ***********************************************
  //   Borra las solicitudes
  // ***********************************************

  function BorrarSolicitudes($idjugadorcampana)
  {
  	 $string = "DELETE FROM clan_jugador
  	 		WHERE idjugadorcampana = $idjugadorcampana
  	 		AND solicitado = 1
  	 		AND aceptado = 0
			AND baneado = 0
  	 		";
//echo $string;
  	 $query = mysql_query($string, $this->link_w);

         // Y tambien las invitaciones
  	 $string = "DELETE FROM clan_jugador
  	 		WHERE idjugadorcampana = $idjugadorcampana
  	 		AND invitado = 1
			AND aceptado = 0
			AND baneado = 0
  	 		";
//echo $string;
  	 $query = mysql_query($string, $this->link_w);
  }


  
  // ***********************************************
  //   Lista las solicitudes de entrada
  // ***********************************************

  function AprobarSolicitud($idsolicitud, $idclan)
  {
    $string3 = "SELECT idjugadorcampana
    		FROM clan_jugador
    		WHERE id = $idsolicitud
    		";
    $query3 = mysql_query($string3, $this->link_r);
    if ($unquery3 = mysql_fetch_array($query3))
    {  	
  	
  	   // Lo primero es darle como aceptado
      $string = "UPDATE clan_jugador
		  SET aceptado = 1,
		  fecha_union = NOW()
		  WHERE id = $idsolicitud
		  ";

      $query = mysql_query($string, $this->link_w);

    
      // Pero ademas, debemos coger el numero de miembros del clan y sumarle uno
      $this->IncrementarMiembros($idclan);
    
      // Y por ultimo, borrar las demás solicitudes que tuviera este jugador
      $idjugadorcampana = $unquery3['idjugadorcampana'];
      $this->BorrarSolicitudes($idjugadorcampana);
      
      
      
    } else {
    	echo ("Error: broken 1384");
    }

  }

  // ***********************************************
  //   Lista las solicitudes de entrada
  // ***********************************************

  function RechazarSolicitud($idsolicitud, $idclan)
  {
    $string3 = "SELECT idjugadorcampana
    		FROM clan_jugador
    		WHERE id = $idsolicitud
    		";
    $query3 = mysql_query($string3, $this->link_r);
    if ($unquery3 = mysql_fetch_array($query3))
    {  	
  	
  	   // Lo primero es darle como aceptado
      $string = "UPDATE clan_jugador
		  SET solicitado_declinado = 1,
		  fecha_union = NOW()
		  WHERE id = $idsolicitud
		  ";

      $query = mysql_query($string, $this->link_w);

    } else {
    	echo ("Error: broken 1385");
    }

  }

  // ***********************************************
  //   Lista las solicitudes de entrada
  // ***********************************************

  function ComprobarAprobar($idclan, $idcampana, $idjugador, $idsolicitud)
  {
    // Primero si soy admin
    $string = "
		(SELECT b.id
		FROM clan_jugador b, jugador_campana c
		WHERE b.idclan = $idclan
		AND b.idjugadorcampana = c.id
		AND c.idjugador = $idjugador
		AND c.idcampana = $idcampana
                AND b.administrador = 1
		)
		UNION
		(SELECT b.id
		FROM clan_jugador b, jugador_campana c
		WHERE b.idclan = $idclan
		AND b.idjugadorcampana = c.id
		AND c.idjugador = $idjugador
		AND c.idcampana = $idcampana
                AND b.fundador = 1
		)
		";
//echo $string;
    $query = mysql_query($string, $this->link_r);
    if ($unquery = mysql_fetch_array($query))
    {
      // Luego si la del solicitante es apropiada
      $string2 = "SELECT b.id
		FROM clan_jugador b
		WHERE b.solicitado = 1
		AND b.solicitado_declinado = 0
		AND b.aceptado = 0
		AND b.id = $idsolicitud
		";
      $query2 = mysql_query($string2, $this->link_r);
      if ($unquery2 = mysql_fetch_array($query2))
      {
        return true;
      } else {
        return false;
      }
    } else {
      return false;
    }

  }


  // ***********************************************
  //   Cuenta las solicitudes de entrada
  // ***********************************************

  function Contar_Solicitudes($idclan, $idjugador)
  {

    $string = "SELECT a.id
		FROM clan_jugador a, jugador_campana b, jugador c
		WHERE a.solicitado = 1
		AND a.solicitado_declinado = 0
		AND a.aceptado = 0
		AND a.idjugadorcampana = b.id
		AND b.idjugador = c.id
		AND a.idclan = $idclan
		";
    $query = mysql_query($string, $this->link_r);
//    return (mysql_num_rows($query));
    $numsolicitudes = mysql_num_rows($query);

    $array_jefes = $this->ObtenerJefes($idclan);
    for ($i = 1; $i <= count($array_jefes); $i++)
    {
      if ($array_jefes[$i]['idjugador'] == $idjugador)
      {
        return $numsolicitudes;
      }
    }
    return 0;


  }


  // ***********************************************
  //   Lista las solicitudes de entrada
  // ***********************************************

  function Listar_Solicitudes($idclan)
  {

    $string = "SELECT a.id, a.fecha_union, c.login, b.idjugador
		FROM clan_jugador a, jugador_campana b, jugador c
		WHERE a.solicitado = 1
		AND a.solicitado_declinado = 0
		AND a.aceptado = 0
		AND a.idjugadorcampana = b.id
		AND b.idjugador = c.id
		AND a.idclan = $idclan
		";
//echo $string;
    $query = mysql_query($string, $this->link_r);
    $i = 0;
    while ($unquery = mysql_fetch_array($query))
    {
      $i++;
      $array[$i]['fecha_union'] = $unquery['fecha_union'];
      $array[$i]['id'] = $unquery['id'];
      $array[$i]['idjugador'] = $unquery['idjugador'];
      $array[$i]['login'] = $unquery['login'];
    }
    return $array;
  }


  // ***********************************************
  //   Inserta una solicitud al clan
  // ***********************************************

  function InsertarSolicitud($idjugador, $idcampana, $idclan)
  {
    $string = "SELECT a.id
		FROM jugador_campana a
		WHERE idjugador = $idjugador
		AND idcampana = $idcampana
		";
    $query = mysql_query($string, $this->link_r);
    if ($unquery = mysql_fetch_array($query))
    {
      $idjugadorcampana = $unquery['id'];
      $string2 = "INSERT INTO clan_jugador
		(idjugadorcampana, idclan, fecha_union, baneado,
		solicitado, invitado, invitado_declinado, aceptado,
		fundador, administrador, solicitado_declinado)
		VALUES
		($idjugadorcampana, $idclan, NOW(), 0,
		1, 0, 0, 0,
		0, 0, 0)
		";
      $query2 = mysql_query($string2, $this->link_w);
      return true;
    } else {
      return false;
    }
  }

  // ***********************************************
  //   Crear un clan
  // ***********************************************

  function VerRelacion($idjugador, $idcampana, $idclan)
  {
    $string = "SELECT a.id, a.idjugadorcampana,
		a.idclan, a.fecha_union,
		a.baneado, a.solicitado, a.invitado,
		a.invitado_declinado, a.aceptado, a.fundador, a.administrador, a.solicitado_declinado
		FROM clan_jugador a, jugador_campana b
		WHERE a.idjugadorcampana = b.id
		AND b.idjugador = $idjugador
		AND b.idcampana = $idcampana
		AND a.idclan = $idclan
		";
    $query = mysql_query($string, $this->link_r);
    if ($unquery = mysql_fetch_array($query))
    {
      $this->j_id = $unquery['id'];
      $this->j_idjugadorcampana = $unquery['idjugadorcampana'];
      $this->j_idclan = $unquery['idclan'];
      $this->j_fecha_union = $unquery['fecha_union'];
      $this->j_baneado = $unquery['baneado'];
      $this->j_solicitado = $unquery['solicitado'];
      $this->j_solicitado_declinado = $unquery['solicitado_declinado'];
      $this->j_invitado = $unquery['invitado'];
      $this->j_invitado_declinado = $unquery['invitado_declinado'];
      $this->j_aceptado = $unquery['aceptado'];
      $this->j_fundador = $unquery['fundador'];
      $this->j_administrador = $unquery['administrador'];
      return true;
    } else {
      return false;
    }
  }

  // ***********************************************
  //   Crear un clan
  // ***********************************************

  function CrearClan($idjugador, $idcampana)
  {
    $string = "INSERT INTO clan
		(nombre, presentacion, fecha_fundacion, nmiembros, activo, identificador, idcampana)
		VALUES
		('$this->nombre', '$this->presentacion', NOW(), 1, 1, '$this->identificador', $idcampana)
		";
    $query = mysql_query($string, $this->link_w);
    $idclan = mysql_insert_id($this->link_w);

    // Ahora hay que meter a jugadorcampana
    $string2 = "SELECT id FROM
		jugador_campana
		WHERE idjugador = $idjugador
		AND idcampana = $idcampana
		";
    $query2 = mysql_query($string2, $this->link_r);
    if ($unquery2 = mysql_fetch_array($query2))
    {
      $idjugadorcampana = $unquery2['id'];

      $string3 = "INSERT INTO
		clan_jugador
		(idjugadorcampana, idclan, fecha_union,
		baneado, solicitado, solicitado_declinado, invitado, invitado_declinado,
		aceptado, fundador, administrador)
		VALUES
		($idjugadorcampana, $idclan, NOW(),
		0, 0, 0, 0, 0,
		1, 1, 0)
		";
      $query3 = mysql_query($string3, $this->link_w);
    }
    return $idclan;

  }




  // ***********************************************
  //   Contar todos los clanes
  // ***********************************************

  function ContarTodos($idjugador, $idcampana)
  {
    $string = "SELECT a.id FROM clan a
		WHERE a.idcampana = $idcampana
		";
    $query = mysql_query($string, $this->link_r);
    return mysql_num_rows($query);
  }

  // ***********************************************
  //   buscar todos los clanes
  // ***********************************************

  function BuscarTodos($idjugador, $idcampana, $offset, $limitelementos)
  {
    $string = "SELECT a.id, a.nombre, a.presentacion, a.fecha_fundacion, a.idavatar, a.nmiembros, a.ruta_avatar
		FROM clan a
		WHERE a.activo = 1
		AND a.idcampana = $idcampana
		ORDER BY a.nmiembros DESC, a.nombre ASC
		";
    $query = mysql_query($string, $this->link_r);
    $i = 0;
    while ($unquery = mysql_fetch_array($query))
    {
      $i++;
      $array[$i]['id'] = $unquery['id'];
      $array[$i]['nombre'] = $unquery['nombre'];
      $array[$i]['presentacion'] = $unquery['presentacion'];
      $array[$i]['fecha_fundacion'] = $unquery['fecha_fundacion'];
      $array[$i]['idavatar'] = $unquery['idavatar'];
      $array[$i]['nmiembros'] = $unquery['nmiembros'];
      $array[$i]['ruta_avatar'] = $unquery['ruta_avatar'];
    }
    return $array;
  }

  // ***********************************************
  //   A partir de un ID de jugador y de campana, obtiene si pertenece
  //  a algun clan, y a cual
  // ***********************************************

  function ObtieneClanJugador($idjugador, $idcampana)
  {
    $string = "SELECT a.id, a.nombre, a.presentacion, a.fecha_fundacion, a.idavatar, a.nmiembros, b.idclan, a.identificador, a.ruta_avatar,
		b.fecha_union, b.baneado, b.solicitado, b.invitado, b.invitado_declinado, b.aceptado, b.fundador, b.administrador, b.solicitado_declinado
		FROM clan a, clan_jugador b, jugador_campana c
		WHERE b.idjugadorcampana = c.id
		AND a.id = b.idclan
		AND b.aceptado = 1
		AND b.baneado = 0
		AND c.idjugador = $idjugador
		AND c.idcampana = $idcampana
		AND a.idcampana = $idcampana
		";
//		AND a.idcampana = $idcampana  <-- esto lo he metido un poco a pelo

//echo $string;
    $query = mysql_query($string, $this->link_r);
    if ($unquery = mysql_fetch_array($query))
    {
      $this->id = $unquery['id'];
      $this->identificador = $unquery['identificador'];
      $this->ruta_avatar = $unquery['ruta_avatar'];
      $this->idclan = $unquery['idclan'];
      $this->nombre = $unquery['nombre'];
      $this->presentacion = $unquery['presentacion'];
      $this->fecha_fundacion = $unquery['fecha_fundacion'];
      $this->idavatar = $unquery['idavatar'];
      $this->nmiembros = $unquery['nmiembros'];
      $this->fecha_union = $unquery['fecha_union'];
      $this->baneado = $unquery['baneado'];
      $this->solicitado = $unquery['solicitado'];
      $this->solicitado_declinado = $unquery['solicitado_declinado'];
      $this->invitado = $unquery['invitado'];
      $this->invitado_declinado = $unquery['invitado_declinado'];
      $this->aceptado = $unquery['aceptado'];
      $this->fundador = $unquery['fundador'];
      $this->administrador = $unquery['administrador'];
      return true;
    } else {
      return false;
    }
  }


  // ***********************************************
  //   A partir de un ID de jugador y de campana, obtiene si pertenece
  //  a algun clan, y a cual
  // ***********************************************

  function SacarDatosClanJugador($idjugadorcampana, $idclan)
  {
  	 if (($idclan == '') || ($idclan == null))
  	 {
  	 	return false;
  	 }
    $string = "SELECT a.id, a.nombre, a.presentacion, a.fecha_fundacion, a.idavatar, a.nmiembros,
		b.fecha_union, b.baneado, b.solicitado, b.invitado, b.invitado_declinado, b.aceptado, b.fundador, b.administrador, b.solicitado_declinado,
		c.idjugador
		FROM clan a, clan_jugador b, jugador_campana c
		WHERE b.idjugadorcampana = $idjugadorcampana
		AND a.id = b.idclan
		AND b.idclan = $idclan
		AND c.id = b.idjugadorcampana
		";
//echo $string;
    $query = mysql_query($string, $this->link_r);
    if ($unquery = mysql_fetch_array($query))
    {
      $this->id = $unquery['id'];
      $this->nombre = $unquery['nombre'];
      $this->presentacion = $unquery['presentacion'];
      $this->fecha_fundacion = $unquery['fecha_fundacion'];
      $this->idavatar = $unquery['idavatar'];
      $this->nmiembros = $unquery['nmiembros'];
      $this->fecha_union = $unquery['fecha_union'];
      $this->baneado = $unquery['baneado'];
      $this->solicitado = $unquery['solicitado'];
      $this->solicitado_declinado = $unquery['solicitado_declinado'];
      $this->invitado = $unquery['invitado'];
      $this->invitado_declinado = $unquery['invitado_declinado'];
      $this->aceptado = $unquery['aceptado'];
      $this->fundador = $unquery['fundador'];
      $this->administrador = $unquery['administrador'];
      $this->idjugador = $unquery['idjugador'];
      return true;
    } else {
      return false;
    }
  }


  // ***********************************************
  //   Cambia datos del logo del clan
  // ***********************************************

  function AlterarDatosLogo($idclan, $ruta_avatar)
  {
    $string = "UPDATE clan
		SET ruta_avatar = '$ruta_avatar'
		WHERE id = $idclan
		";
//echo $string;
    $query = mysql_query($string, $this->link_w);
  }

  // ***********************************************
  //   Cambia datos del clan
  // ***********************************************

  function AlterarDatos($idclan)
  {
    $string = "UPDATE clan
		SET presentacion = '$this->presentacion',
		identificador = '$this->identificador'
		WHERE id = $idclan
		";
    $query = mysql_query($string, $this->link_w);
  }


  // ***********************************************
  //   Saca datos de un clan a partir de un id
  // ***********************************************

  function SacarDatos($idclan)
  {
    $string = "SELECT a.id, a.nombre, a.presentacion, a.fecha_fundacion, a.idavatar, a.nmiembros, a.identificador, a.ruta_avatar
		FROM clan a
		WHERE a.id = $idclan
		AND a.activo = 1
		";
//echo $string;
    $query = mysql_query($string, $this->link_r);
    if ($unquery = mysql_fetch_array($query))
    {
      $this->id = $unquery['id'];
      $this->nombre = $unquery['nombre'];
      $this->presentacion = $unquery['presentacion'];
      $this->fecha_fundacion = $unquery['fecha_fundacion'];
      $this->idavatar = $unquery['idavatar'];
      $this->nmiembros = $unquery['nmiembros'];
      $this->identificador = $unquery['identificador'];
      $this->ruta_avatar = $unquery['ruta_avatar'];
      return true;
    } else {
      return false;
    }
  }


  // ***********************************************
  //   Saca datos de un clan a partir de un id
  // ***********************************************

  function SacarDatosSolicitud($idsolicitud)
  {
    $string = "SELECT
		c.idjugador, b.fecha_union, b.baneado, b.solicitado, b.invitado, b.invitado_declinado, b.aceptado, b.fundador, b.administrador, b.solicitado_declinado,
		d.lang
		FROM clan_jugador b, jugador_campana c, jugador d
		WHERE b.id = $idsolicitud
		AND b.idjugadorcampana = c.id
		AND c.idjugador = d.id
		";
//echo $string."<br/>";
    $query = mysql_query($string, $this->link_r);
    if ($unquery = mysql_fetch_array($query))
    {
//echo ("!");
      $this->idjugador = $unquery['idjugador'];
      $this->fecha_union = $unquery['fecha_union'];
      $this->baneado = $unquery['baneado'];
      $this->solicitado = $unquery['solicitado'];
      $this->solicitado_declinado = $unquery['solicitado_declinado'];
      $this->invitado = $unquery['invitado'];
      $this->invitado_declinado = $unquery['invitado_declinado'];
      $this->aceptado = $unquery['aceptado'];
      $this->fundador = $unquery['fundador'];
      $this->administrador = $unquery['administrador'];
      $this->lang = $unquery['lang'];
      return true;
    } else {
      return false;
    }
  }



  // **********************************
  //    Contamos los equipos del ranking
  // **********************************

  function ContarElementosRank($link_r, $idcampana)
  {
    $string = "SELECT id
		FROM clan
		WHERE idcampana = $idcampana
		";
    $query = mysql_query($string, $this->link_r);
    return (mysql_num_rows($query));
  }

  // **********************************
  //    Buscamos y ordenamos los equipos del ranking
  // **********************************

  function BuscarElementosRank($link_r, $idcampana, $limit, $offset)
  {
    $string = "
		SELECT SUM(b.num_torneos_victorias) AS victorias, SUM(b.num_torneos_segundo) AS segundo, SUM(b.num_torneos_tercero) AS tercero,
			SUM(b.num_generaciones_total) AS g_total, SUM(b.num_generaciones_demes) AS g_deme, SUM(b.num_generaciones_individual) AS g_individual, a.idclan, c.nombre,
			c.identificador
		FROM clan_jugador a, jugador_campana b, clan c
		WHERE a.idjugadorcampana = b.id
		AND a.idclan = c.id
		GROUP BY a.idclan
		ORDER BY victorias DESC, segundo DESC, tercero DESC
		LIMIT $limit OFFSET $offset
		";
    $query = mysql_query($string, $this->link_r);
    $i = 0;
    while ($unquery = mysql_fetch_array($query))
    {
      $i++;
      $array[$i]['victorias'] = $unquery['victorias'];
      $array[$i]['segundo'] = $unquery['segundo'];
      $array[$i]['tercero'] = $unquery['tercero'];
      $array[$i]['g_total'] = $unquery['g_total'];
      $array[$i]['g_deme'] = $unquery['g_deme'];
      $array[$i]['g_individual'] = $unquery['g_individual'];
      $array[$i]['idclan'] = $unquery['idclan'];
      $array[$i]['nombre'] = $unquery['nombre'];
      $array[$i]['identificador'] = $unquery['identificador'];
    }
    return $array;
  }



    // Esta funcion devuelve en que pagina del ranking esta el user
    function BuscarPaginaRank($link_r, $idcampana, $limit, $idjugador, $idclan)
    {
      $string = "
		SELECT SUM(b.num_torneos_victorias) AS victorias, SUM(b.num_torneos_segundo) AS segundo, SUM(b.num_torneos_tercero) AS tercero,
			SUM(b.num_generaciones_total) AS g_total, SUM(b.num_generaciones_demes) AS g_deme, SUM(b.num_generaciones_individual) AS g_individual, a.idclan, c.nombre
		FROM clan_jugador a, jugador_campana b, clan c
		WHERE a.idjugadorcampana = b.id
		AND a.idclan = c.id
		GROUP BY a.idclan
		ORDER BY victorias DESC, segundo DESC, tercero DESC
		";
      $query = mysql_query($string, $this->link_r);
      $i = 0;
      $pagina_actual = 1;
      $pagina_final = 1;
      while ($unquery = mysql_fetch_array($query))
      {
        $i++;
        if ($i > $limit)
        {
          $pagina_actual++;
        }
        if ($unquery['idclan'] == $idclan)
        {
          $pagina_final = $pagina_actual;
        }
      }
      return $pagina_final;
    }






}



?>
