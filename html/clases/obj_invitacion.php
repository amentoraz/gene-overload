<?php

// ##########################################################################
// ##                                                                      ##
// ##  Nombre de la clase:   Invitacion                                    ##
// ##  Fecha de creacion:    07/apr/2011                                   ##
// ##  Ultima version:       07/apr/2011                                   ##
// ##  Funcionalidad:        Gestion de los codigos de invitacion     c    ##
// ##                                                                      ##
// ##########################################################################
//
//
//    Gestiona las siguientes tablas:
//
// tipo_origen: 1 usuario, 2 clan (redundante, pero facilita la búsqueda), 3 admin
//
// CREATE TABLE codigo_invitacion
// {
//   id int not null auto_increment,
//   tipo_origen int,
//   id_usuario_origen int,
//   id_clan_origen int,
//   fecha_expedicion datetime,
//   usos int,
//   codigo CHAR(20),
//   envio_admin tinyint,
//   email_enviado_admin tinyint,
//   email_destinatario_admin VARCHAR(255),
//   nombre_destinatario_admin VARCHAR(255),
//   PRIMARY KEY (id)
// }
//
// CREATE TABLE codigo_invitacion_usado
// {
//   id int not null auto_increment,
//   email VARCHAR(255),
//   idusuario int,
//   fecha_uso datetime,
//   id_codigo_invitacion int,
//   PRIMARY KEY (id)
// }
//
//
// CREATE TABLE codigo_invitacion_pendiente
// {
//   id int not null auto_increment,
//   email VARCHAR(255),
//   id_codigo_invitacion int,
//   fecha datetime,
//   reenvios_num int DEFAULT 0,
//   reenvios_fecha datetime,
//   PRIMARY KEY (id)
// }
//
//
// CREATE TABLE codigo_invitacion_parametros
// {
//   id int not null auto_increment,
//   nombre VARCHAR(255),
//   valor int,
//   PRIMARY KEY (id)
// }
//
//
//
//
//
//    Gestiona el sistema de invitaciones para la beta, y posiblemente de manera posterior
//
//
//
//    Metodos proporcionados por esta clase :
//
//    * __construct : Constructor, inicializa el log
//
//
//    * Puedo_Reenviar($idsolicitud) : Decide si puede volver a enviarse una invitacion
//    * Guarda_Reenvio($idsolicitud) : Almacena un nuevo reenvio y su fecha
//    * EliminarInvitacion($idusuario, $idinvitacion)
//
//    Metodos para validar invitaciones :
//
//    * UsarAdmin($codigo, $email, $idusuario) -> Utiliza un codigo de tipo admin e inserta los datos apropiados en codigo_invitacion_usados
//    * Usar($codigo, $email, $idusuario) -> Utiliza un codigo de tipo usuario/clan e inserta los datos apropiados en codigo_invitacion_usados
//    * SacarDatosValidar($codigo) -> Saca los datos de una entrada de aqui desde el codigo (realmente igual que Sacar_Datos_Desde_Codigo
//    * Validar($codigo, $email) -> Comprueba si es valido email/codigo (tipos 1 y 2) o codigo (tipo 3). Si lo es devuelve true y copia el pendiente a usado
//
//    Metodos para generar invitaciones :
//
//    * Crear_Codigo_Nuevo() -> Genera un nuevo codigo a partir de /dev/urandom
//    * Comprobar_Codigo($codigo) -> Comprueba contra la base de datos si ya se ha creado un codigo asi. Devuelve -1 si existe.
//    * Generar_Codigo() -> Crea y devuelve un nuevo codigo usando los metodos Crear_Codigo_Nuevo y Comprobar_Codigo_Nuevo
//
//
//    Metodos para sacar datos de un codigo
//
//    * Sacar_Datos($id)
//    * Sacar_Datos_Desde_Codigo($codigo)
//    * Sacar_Datos_Desde_Clan($idclan)
//    * Sacar_Datos_Pendientes($id)
//    * Sacar_Datos_Usado($id)
//
//
//    Metodos para insertar :
//
//    * Inserta_Codigo_Userzone
//    * Inserta_Pendiente
//    * Inserta_Codigo_Userzone_Usuario($idusuario, $email)
//    * Inserta_Codigo_Userzone_Clan($idclan, $email)
//    * Aumenta_Codigo_Userzone_Clan($idclan, $email)
//    * Inserta_Codigo_Admin() -> Inserta un codigo con los parametros que haya en la clase
//
//
//
//    Metodos para comprobar si se puede invitar :
//
//    ** Existe_Codigo_Clan($idclan)
//    * Puede_Invitar_Clan($idclan, $cuantos_generar) -> Se le pasa el clan y cuanto se quiere generar, y devuelve true o false
//    * Puede_Invitar_Usuario($idusuario, $cuantos_generar) -> Se le pasa el usuario y cuanto se quiere generar, y devuelve true o false
//
//
//    Metodos de conteo de invitaciones usadas :
//
//    * Grabar_Invitaciones_Maximas($nombre, $valor) -> Graba un parametro de nombre $nombre y valor $valor en la tabla de parametros de invitacion
//    * Grabar_Invitaciones_Maximas_Clan($valor) -> Graba en la tabla de parametros un nuevo valor para MAXCLAN
//    * Grabar_Invitaciones_Maximas_Usuario($valor) -> Graba en la tabla de parametros un nuevo valor para MAXUSUARIO
//
//    * Obtener_Invitaciones_Enviadas_SinUsar_Clan($idclan) -> Obtiene cuantas invitaciones ha enviado y estan sin usar
//    * Obtener_Invitaciones_Enviadas_Exito_Clan($idclan) -> Obtiene cuantas invitaciones ha enviado y usado un clan
//    * Obtener_Invitaciones_Enviadas_Clan($idclan) -> Obtiene cuantas invitaciones ha enviado un clan
//
//    * Obtener_Invitaciones_Enviadas_SinUsar_Usuario($idusuario) -> Obtiene cuantas invitaciones ha enviado y estan sin usar
//    * Obtener_Invitaciones_Enviadas_Exito_Usuario($idusuario) -> Obtiene cuantas invitaciones ha enviado y usado un usuario
//    * Obtener_Invitaciones_Enviadas_Usuario($idusuario) -> Obtiene cuantas invitaciones ha enviado un usuario
//
//
//
//
//
//
//

class Invitacion
{

  public $totalclaves = 20;

  var $log;
  var $link_r;
  var $link_w;

  // Tabla codigo_invitacion
  var $id;
  var $tipo_origen;
  var $id_usuario_origen;
  var $id_clan_origen;
  var $fecha;
  var $usos;
  var $codigo;
  var $envio_admin;
  var $email_enviado_admin;
  var $email_destinatario_admin;
  var $nombre_destinatario_admin;

  // Tabla codigo_invitacion_usado

  var $ciu_id;
  var $ciu_email;
  var $ciu_idusuario;
  var $ciu_fecha_uso;
  var $ciu_id_codigo_invitacion;

  // Tabla codigo_invitacion_pendiente

  var $cip_id;
  var $cip_email;
  var $cip_id_codigo_invitacion;

  // Tabla codigo_invitacion_parametros

  var $cip2_id;
  var $cip2_nombre;
  var $cip2_valor;




  // ***********************************
  //    Constructor
  // ***********************************
  //  Inicializa el log

  function __construct(){

    // Vamos a crear un objeto log y a inicializarlo para esta clase.
//    $this->log = new Log(get_class($this));

  }



  // *****************************************
  //    Puedo reenviar una solicitud de pendientes?
  // *****************************************

  function Puedo_Reenviar($idsolicitud)
  {
    // O bien la fecha de reenvio no existe, o es de hace + de un dia
    $string = "
		(SELECT id, email, id_codigo_invitacion,
		fecha, reenvios_num, reenvios_fecha
		FROM codigo_invitacion_pendiente
		WHERE id = $idsolicitud
		AND reenvios_fecha IS NULL)
		UNION
		(SELECT id, email, id_codigo_invitacion,
		fecha, reenvios_num, reenvios_fecha
		FROM codigo_invitacion_pendiente
		WHERE id = $idsolicitud
		AND DATE_SUB(NOW(), INTERVAL 1 DAY) > reenvios_fecha)
		";
//echo $string;
    $query = mysql_query($string, $this->link_r);
    if ($unquery = mysql_fetch_array($query))
    {
      // La solicitud pendiente existe y es primer reenvio o ha pasado al menos un dia
      if ($unquery['reenvios_num'] >= 5)
      {
        // Si el total de reenvios es ya de 5, false
        return -1;
      } else {
        return 0;
      }
    } else {
      return -2;
    }
  }

  // *****************************************
  //   Guardar los datos de un reenvio
  // *****************************************

  function Guarda_Reenvio($idsolicitud)
  {
    $string = "SELECT id, reenvios_num, reenvios_fecha
		FROM codigo_invitacion_pendiente
		WHERE id = $idsolicitud
		";
    $query = mysql_query($string, $this->link_r);
    if ($unquery = mysql_fetch_array($query))
    {
      $reenvios_num = $unquery['reenvios_num'];
      $reenvios_num++;
      $update = "UPDATE codigo_invitacion_pendiente
		SET reenvios_num = $reenvios_num,
		reenvios_fecha = NOW()
		WHERE id = $idsolicitud
		";
//echo $update;
      $queryup = mysql_query($update, $this->link_w);
//      echo ($reenvios_num."#");
    }
  }



  // *****************************************
  //    Eliminar una invitacion de tipo beta
  // *****************************************

  function EliminarInvitacion($idusuario, $idinvitacion)
  {
    $string = "SELECT id
		FROM codigo_invitacion
		WHERE id = $idinvitacion
		AND id_usuario_origen = $idusuario
		";
//echo "#".$string."#";
    $query = mysql_query($string, $this->link_r);
    if ($unquery = mysql_fetch_array($query))
    {
      $idinvitacion = $unquery['id'];
      $delete1 = "DELETE FROM
		codigo_invitacion
		WHERE id = $idinvitacion
		AND id_usuario_origen = $idusuario
		";
//echo "#".$delete1."#";
      $query1 = mysql_query($delete1, $this->link_w);

      $delete2 = "DELETE FROM
		codigo_invitacion_pendiente
		WHERE id_codigo_invitacion = $idinvitacion
		";
//echo "#".$delete2."#";
      $query2 = mysql_query($delete2, $this->link_w);
      return true;
    } else {
      return false;
    }
  }


  // *****************************************
  //    Eliminar una invitacion de tipo beta
  // *****************************************

  function EliminarInvitacionPendiente($idusuario, $idinvitacionpendiente)
  {
    $string = "SELECT a.id
		FROM codigo_invitacion a, codigo_invitacion_pendiente b
		WHERE a.id_usuario_origen = $idusuario
		AND b.id_codigo_invitacion = a.id
                AND b.id = $idinvitacionpendiente
		";
//echo "#".$string."#";
    $query = mysql_query($string, $this->link_r);
    if ($unquery = mysql_fetch_array($query))
    {
      $idinvitacion = $unquery['id'];
      $delete1 = "DELETE FROM
		codigo_invitacion
		WHERE id = $idinvitacion
		AND id_usuario_origen = $idusuario
		";
//echo "#".$delete1."#";
      $query1 = mysql_query($delete1, $this->link_w);

      $delete2 = "DELETE FROM
		codigo_invitacion_pendiente
		WHERE id_codigo_invitacion = $idinvitacion
		";
//echo "#".$delete2."#";
      $query2 = mysql_query($delete2, $this->link_w);
      return true;
    } else {
      return false;
    }
  }


  // ****************************************
  //          Invitacion pendiente
  // ****************************************

  function InvitacionPendiente($email, $idemisor)
  {
    // Hay ya alguien que haya enviado una invitacion a este usuario?
    $string = "SELECT a.id
		FROM codigo_invitacion_pendiente a, codigo_invitacion b
		WHERE a.id_codigo_invitacion = b.id
		AND b.id_usuario_origen = $idemisor
		AND a.email = '$email'
		";
//echo $string;
//    $this->log->Escribir_Log("SELECT InvitacionPendiente ".$string, DEBUG);
    $query = @mysql_query($string, $this->link_r);
    if (isset($php_errormsg) && ($php_errormsg != null))
    {
//      $this->log->Escribir_Log("ERROR InvitacionPendiente : ".$php_errormsg, WARN);
    }
//echo ("#".mysql_num_rows($query)."#");

    return @mysql_num_rows($query);
  }






  // ····································································
  //               Metodos para validar invitaciones
  // ····································································


  // *******************************************
  //     Pasa el codigo usado a la tabla apropiada
  // *******************************************

  function UsarAdmin($codigo, $email, $idusuario)
  {
    $string = "SELECT a.id
		FROM codigo_invitacion a
		WHERE
		a.codigo = '$codigo'
		";
//echo $string;
//    $this->log->Escribir_Log("SELECT UsarAdmin: ".$string, DEBUG);
    $query = @mysql_query($string, $this->link_r);
//    if (isset($php_errormsg) && ($php_errormsg != null))
//    {
//      $this->log->Escribir_Log("ERROR Usar_Admin : ".$php_errormsg, WARN);
//    }
    if ($unquery = @mysql_fetch_array($query))
    {
      // Ahora hacemos el insert
      $id_codigo_invitacion = $unquery['id'];
      $string_insertar = "INSERT INTO codigo_invitacion_usado
		(email, idusuario, fecha_uso, id_codigo_invitacion)
		VALUES
		('$email', $idusuario, NOW(), $id_codigo_invitacion)
		";
//      $this->log->Escribir_Log("INSERT UsarAdmin: ".$string_insertar, DEBUG);
//echo $string_insertar;
      $query_insertar = @mysql_query($string_insertar, $this->link_w);
//      if (isset($php_errormsg) && ($php_errormsg != null))
//      {
//        $this->log->Escribir_Log("ERROR Usar_Admin : ".$php_errormsg, WARN);
//      }
    } else {
      return false;
    }
  }

  // *******************************************
  //     Pasa el codigo usado a la tabla apropiada
  // *******************************************

  function Usar($codigo, $email, $idusuario)
  {
    $string = "SELECT b.id, b.id_codigo_invitacion
		FROM codigo_invitacion_pendiente b, codigo_invitacion a
		WHERE b.id_codigo_invitacion = a.id
		AND a.codigo = '$codigo'
		";
//		AND b.email = '$email'   // No hace falta, solo va a haber uno
//    $this->log->Escribir_Log("SELECT Usar: ".$string, DEBUG);
    $query = @mysql_query($string, $this->link_r);
//    if (isset($php_errormsg) && ($php_errormsg != null))
//    {
//      $this->log->Escribir_Log("ERROR Usar : ".$php_errormsg, WARN);
//    }
    if ($unquery = @mysql_fetch_array($query))
    {
      $idpendiente = $unquery['id'];
      // Vale, tenemos el id, y el de la invitacion en la tabla original
      $string_borrar = "DELETE FROM codigo_invitacion_pendiente
		WHERE id = $idpendiente
		";
//      $this->log->Escribir_Log("DELETE Usar: ".$string_borrar, DEBUG);
      $query_borrar = @mysql_query($string_borrar, $this->link_w);
//      if (isset($php_errormsg) && ($php_errormsg != null))
//      {
//        $this->log->Escribir_Log("ERROR Usar : ".$php_errormsg, WARN);
//      }

      // Ahora hacemos el insert
      $id_codigo_invitacion = $unquery['id_codigo_invitacion'];
      $string_insertar = "INSERT INTO codigo_invitacion_usado
		(email, idusuario, fecha_uso, id_codigo_invitacion)
		VALUES
		('$email', $idusuario, NOW(), $id_codigo_invitacion)
		";
//      $this->log->Escribir_Log("INSERT Usar: ".$string, DEBUG);
      $query_insertar = @mysql_query($string_insertar, $this->link_w);
//      if (isset($php_errormsg) && ($php_errormsg != null))
//      {
//        $this->log->Escribir_Log("ERROR Usar : ".$php_errormsg, WARN);
//      }
    } else {
      return false;
    }
  }

  // *******************************************
  //     Pasa el codigo usado a la tabla apropiada
  // *******************************************

  function UsarClan($codigo, $email, $idusuario)
  {
//    $string = "SELECT b.id, b.id_codigo_invitacion
//		FROM codigo_invitacion_pendiente b, codigo_invitacion a
//		WHERE b.id_codigo_invitacion = a.id
//		AND a.codigo = '$codigo'
//		";
    $string = "SELECT a.id
		FROM codigo_invitacion a
		WHERE a.codigo = '$codigo'
		";
//    $this->log->Escribir_Log("SELECT UsarClan: ".$string, DEBUG);
    $query = @mysql_query($string, $this->link_r);
//    if (isset($php_errormsg) && ($php_errormsg != null))
//    {
//      $this->log->Escribir_Log("ERROR UsarClan : ".$php_errormsg, WARN);
//    }
    if ($unquery = @mysql_fetch_array($query))
    {
//      $idpendiente = $unquery['id'];
      // Vale, tenemos el id, y el de la invitacion en la tabla original
//      $string_borrar = "DELETE FROM codigo_invitacion_pendiente
//		WHERE id = $idpendiente
//		";
//      $query_borrar = @mysql_query($string_borrar, $this->link_w);

      // Ahora hacemos el insert
//      $id_codigo_invitacion = $unquery['id_codigo_invitacion'];
      $id_codigo_invitacion = $unquery['id'];
      $string_insertar = "INSERT INTO codigo_invitacion_usado
		(email, idusuario, fecha_uso, id_codigo_invitacion)
		VALUES
		('$email', $idusuario, NOW(), $id_codigo_invitacion)
		";
//      $this->log->Escribir_Log("INSERT UsarClan: ".$string, DEBUG);
      $query_insertar = @mysql_query($string_insertar, $this->link_w);
//      if (isset($php_errormsg) && ($php_errormsg != null))
//      {
//         $this->log->Escribir_Log("ERROR UsarClan : ".$php_errormsg, WARN);
//      }
    } else {
      return false;
    }
  }

  // *******************************************
  //    Sacar datos para hacer la validacion
  // *******************************************

  function SacarDatosValidar($codigo)
  {
    $string = "SELECT a.id, a.tipo_origen, a.usos,
		a.id_usuario_origen, a.id_clan_origen, a.fecha_expedicion,
		a.email_enviado_admin, a.email_destinatario_admin,
		a.nombre_destinatario_admin, a.codigo
		FROM codigo_invitacion a
		WHERE a.codigo = '$codigo'
		";
//    $this->log->Escribir_Log("SELECT SacarDatosValidar: ".$string, DEBUG);
    $query = @mysql_query($string, $this->link_r);
//    if (isset($php_errormsg) && ($php_errormsg != null))
//    {
//      $this->log->Escribir_Log("ERROR SacarDatosValidar : ".$php_errormsg, WARN);
//    }
    if ($unquery = @mysql_fetch_array($query))
    {
	return serialize($unquery);
    } else {
	return false;
    }
  }

  // ***********************************
  //    Validar una invitacion
  // ***********************************

  function Validar($codigo) //, $email)
  {
    $string = "SELECT a.id, a.tipo_origen, a.usos
		FROM codigo_invitacion a
		WHERE a.codigo = '$codigo'
		";
//    $this->log->Escribir_Log("SELECT Validar: ".$string, DEBUG);
    $query = @mysql_query($string, $this->link_r);
//    if (isset($php_errormsg) && ($php_errormsg != null))
//    {
//      $this->log->Escribir_Log("ERROR Validar : ".$php_errormsg, WARN);
//    }
    if ($unquery = @mysql_fetch_array($query))
    {
      $tipo_origen = $unquery['tipo_origen'];
      if (($tipo_origen == 3) || ($tipo_origen == 2)) // Si es de tipo clan o admin, vale con ver que existe el codigo para validar
      {
        //  $tipo_origen 3 es generado por el admin. Aqui no hace falta
        // trasladar nada, pero el count de la tabla de usados no puede
        // superar al numero de usos
        $idcodigo = $unquery['id'];
        $string_usos = "SELECT id
			FROM codigo_invitacion_usado
			WHERE id_codigo_invitacion = $idcodigo
			";
//        $this->log->Escribir_Log("SELECT Validar: ".$string_usos, DEBUG);
        $query_usos = @mysql_query($string_usos, $this->link_r);
//        if (isset($php_errormsg) && ($php_errormsg != null))
//        {
//          $this->log->Escribir_Log("ERROR Validar : ".$php_errormsg, WARN);
//        }
        $cuantos_usos = @mysql_num_rows($query_usos);
//echo $cuantos_usos."-".$unquery['usos'];
        // Los usos extendidos de clan se asumen aqui automaticamente
        if ($cuantos_usos < $unquery['usos'])
        {
          return true;
        } else {
          return false;
        }

      } else {
        //  Si es de tipo_origen 1, tenemos que comprobar que
        // exista algo en la tabla de pendientes
        $idinvitar = $unquery['id'];
        $string2 = "SELECT id
		FROM codigo_invitacion_pendiente
		WHERE id_codigo_invitacion = $idinvitar
		";
//        $this->log->Escribir_Log("SELECT Validar: ".$string2, DEBUG);
//		AND email = '$email'
        $query2 = @mysql_query($string2, $this->link_r);
//        if (isset($php_errormsg) && ($php_errormsg != null))
//        {
//          $this->log->Escribir_Log("ERROR Validar : ".$php_errormsg, WARN);
//        }
        if (@mysql_num_rows($query2) > 0)
        {
	  return true;
        } else {
	  return false;
        }
      }
    } else {
      return false;
    }

  }



  // ····································································
  //               Funciones para generar invitaciones
  // ····································································

  // ***********************************
  //    Crear un nuevo codigo
  // ***********************************

  function Crear_Codigo_Nuevo()
  {

      // Crea un codigo nuevo tirando de /dev/urandom

      if(($fhandle = fopen('/dev/urandom','rb')) != FALSE)
      {

        for ($j = 1; $j <= $this->totalclaves ; $j++)
        {


                // En ASCII tenemos los valores:
                //   48-57: numeros 0-9
                //   65-90: Letras mayusculas (26)
                //   97-122: letras minusculas (26)

                // Sacamos el valor inicial

              $valorcorrecto = 0;
              while ($valorcorrecto == 0)
              {

                $val = ord(fgetc($fhandle));

                //  Tenemos que hacer ese valor X MOD 62. Para que no se descompense,
                // deberia estar entre:
                //  0 - 61
                // 62 - 123
                // 124 - 185
                // 186 - 247
                //  Luego si es mayor que 247, repetimos
                // Tb repetimos si es la 'i', 'l', 'I', '0' o 'O'
                //  Lo cual son los valores 105, 108, 073, 048, 079
                //  Lo cual en $val es '0', 

                if ($val <= 247)
                {
                  $val = $val % 62;

                  // Ahora tenemos un numero entre 0 y 61

                  if ($val < 10)
                  {
                    $final = 48 + $val;
                  } else {
                    $val = $val - 10;
                    if ($val < 26)
                    {
                      $final = 65 + $val;
                    } else {
                      $val = $val - 26;
                      $final = 97 + $val;
                    } 

                  }

                  if (($final != 105) && ($final != 108) && ($final != 73) && ($final != 48) && ($final != 79))
                  {
                    $valorcorrecto = 1;
                  }


                }

              }

            $clavetotal = $clavetotal.chr($final);

          }
//          $this->log->Escribir_Log("Codigo Creado : ".$clavetotal, DEBUG);
          return $clavetotal;


        } else {
          echo ("<p class=\"error\"><strong>Error grave :</strong> No se pudo abrir /dev/urandom</p>");
          return 0;
        }


  }


  // ***********************************
  //    Comprobar si el codigo esta repetido
  // ***********************************

  function Comprobar_Codigo($codigo)
  {

	$string = "SELECT id
		FROM codigo_invitacion
		WHERE codigo = '$codigo'
		";
//echo ("<br>".$string);
//        $this->log->Escribir_Log("SELECT Comprobar_Codigo: ".$string, DEBUG);
	$query = @mysql_query($string, $this->link_r);
//        if (isset($php_errormsg) && ($php_errormsg != null))
//        {
//          $this->log->Escribir_Log("ERROR Comprobar_Codigo : ".$php_errormsg, WARN);
//        }
	if (@mysql_num_rows($query) > 0)
	{
//echo ("ENCUENTRA");
	  return false;
	} else {
//echo ("GUAY!!!");
	  return true;
	}

  }


  // ***********************************
  //    Generar un codigo
  // ***********************************

  function Generar_Codigo()
  {
    $correcto = false;
    while ($correcto == false)
    {
      $codigo = $this->Crear_Codigo_Nuevo();
      if ($this->Comprobar_Codigo($codigo) == true)
      {
        $correcto = true;
      }
    }
    return $codigo;

  }


  // *************************************
  //   Sacar datos de un codigo
  // *************************************

  function Sacar_Datos($id)
  {
    $string = "SELECT id, tipo_origen, id_usuario_origen,
		id_clan_origen, fecha_expedicion,
		usos, codigo, envio_admin,
		email_enviado_admin, email_destinatario_admin,
		nombre_destinatario_admin
		FROM codigo_invitacion
		WHERE id = $id
		";
//echo "#".$string."#-->";
//    $this->log->Escribir_Log("SELECT Sacar_Datos: ".$string, DEBUG);
    $query = @mysql_query($string, $this->link_r);
//    if (isset($php_errormsg) && ($php_errormsg != null))
//    {
//      $this->log->Escribir_Log("ERROR Sacar_Datos : ".$php_errormsg, WARN);
//    }
    if ($unquery = @mysql_fetch_array($query))
    {
//echo ("SI!-->".$unquery['codigo']."###");
      $this->id = $unquery['id'];
      $this->tipo_origen = $unquery['tipo_origen'];
      $this->id_usuario_origen = $unquery['id_usuario_origen'];
      $this->id_clan_origen = $unquery['id_clan_origen'];
      $this->fecha_expedicion = $unquery['fecha_expedicion'];
      $this->usos = $unquery['usos'];
      $this->codigo = $unquery['codigo'];
      $this->envio_admin = $unquery['envio_admin'];
      $this->email_enviado_admin = $unquery['email_enviado_admin'];
      $this->email_destinatario_admin = $unquery['email_destinatario_admin'];
      $this->nombre_destinatario_admin = $unquery['nombre_destinatario_admin'];
    }
  }


  // *************************************
  //   Sacar datos de un codigo
  // *************************************

  function Sacar_Datos_Desde_Codigo($codigo)
  {
    $string = "SELECT id, tipo_origen, id_usuario_origen,
		id_clan_origen, fecha_expedicion,
		usos, codigo, envio_admin,
		email_enviado_admin, email_destinatario_admin,
		nombre_destinatario_admin
		FROM codigo_invitacion
		WHERE codigo = '$codigo'
		";
//    $this->log->Escribir_Log("SELECT Sacar_Datos_Desde_Codigo: ".$string, DEBUG);
    $query = @mysql_query($string, $this->link_r);
//    if (isset($php_errormsg) && ($php_errormsg != null))
//    {
//      $this->log->Escribir_Log("ERROR Sacar_Datos_Desde_Codigo : ".$php_errormsg, WARN);
//    }
    if ($unquery = @mysql_fetch_array($query))
    {
      $this->id = $unquery['id'];
      $this->tipo_origen = $unquery['tipo_origen'];
      $this->id_usuario_origen = $unquery['id_usuario_origen'];
      $this->id_clan_origen = $unquery['id_clan_origen'];
      $this->fecha_expedicion = $unquery['fecha_expedicion'];
      $this->usos = $unquery['usos'];
      $this->codigo = $unquery['codigo'];
      $this->envio_admin = $unquery['envio_admin'];
      $this->email_enviado_admin = $unquery['email_enviado_admin'];
      $this->email_destinatario_admin = $unquery['email_destinatario_admin'];
      $this->nombre_destinatario_admin = $unquery['nombre_destinatario_admin'];
    }
  }

  // *************************************
  //   Grabamos los usos
  // *************************************

  function Grabar_Usos($id, $usos)
  {
    $string = "UPDATE codigo_invitacion
		SET usos = $usos
		WHERE id = $id
		";
    $query = mysql_query($string, $this->link_w);
  }


  // *************************************
  //   Sacar datos de un codigo
  // *************************************

  function Sacar_Datos_Desde_Clan($idclan)
  {
    $string = "SELECT id, tipo_origen, id_usuario_origen,
		id_clan_origen, fecha_expedicion,
		usos, codigo, envio_admin,
		email_enviado_admin, email_destinatario_admin,
		nombre_destinatario_admin
		FROM codigo_invitacion
		WHERE id_clan_origen = $idclan
		";
//    $this->log->Escribir_Log("SELECT Sacar_Datos_Desde_Clan: ".$string, DEBUG);
    $query = @mysql_query($string, $this->link_r);
//    if (isset($php_errormsg) && ($php_errormsg != null))
//    {
//      $this->log->Escribir_Log("ERROR Sacar_Datos_Desde_Clan : ".$php_errormsg, WARN);
//    }
    if ($unquery = @mysql_fetch_array($query))
    {
      $this->id = $unquery['id'];
      $this->tipo_origen = $unquery['tipo_origen'];
      $this->id_usuario_origen = $unquery['id_usuario_origen'];
      $this->id_clan_origen = $unquery['id_clan_origen'];
      $this->fecha_expedicion = $unquery['fecha_expedicion'];
      $this->usos = $unquery['usos'];
      $this->codigo = $unquery['codigo'];
      $this->envio_admin = $unquery['envio_admin'];
      $this->email_enviado_admin = $unquery['email_enviado_admin'];
      $this->email_destinatario_admin = $unquery['email_destinatario_admin'];
      $this->nombre_destinatario_admin = $unquery['nombre_destinatario_admin'];
    }
  }

  // *************************************
  //   Sacar datos de un codigo
  // *************************************

  function Sacar_Datos_Pendientes($id)
  {
    $string = "SELECT id, email, fecha
		FROM codigo_invitacion_pendiente
		WHERE id_codigo_invitacion = $id
		ORDER BY fecha DESC
		";
//    $this->log->Escribir_Log("SELECT Sacar_Datos_Pendientes: ".$string, DEBUG);
    $query = @mysql_query($string, $this->link_r);
//    if (isset($php_errormsg) && ($php_errormsg != null))
//    {
//      $this->log->Escribir_Log("ERROR Sacar_Datos_Pendientes : ".$php_errormsg, WARN);
//    }
    $i = 0;
    while ($unquery = @mysql_fetch_array($query))
    {
      $i++;
      $array[$i]['fecha'] = $unquery['fecha'];
      $array[$i]['id'] = $unquery['id'];
      $array[$i]['email'] = $unquery['email'];
    }
    return serialize($array);
  }

  // *************************************
  //   Sacar datos de un idusuario
  // *************************************

  function Sacar_Datos_Pendientes_idusuario($idusuario)
  {
    $string = "SELECT a.id, a.email, a.fecha, a.id_codigo_invitacion
		FROM codigo_invitacion_pendiente a, codigo_invitacion b
		WHERE a.id_codigo_invitacion = b.id
		AND b.id_usuario_origen = $idusuario
		";
//id_codigo_invitacion = $id
//    $this->log->Escribir_Log("SELECT Sacar_Datos_Pendientes: ".$string, DEBUG);
    $query = @mysql_query($string, $this->link_r);
//    if (isset($php_errormsg) && ($php_errormsg != null))
//    {
//      $this->log->Escribir_Log("ERROR Sacar_Datos_Pendientes : ".$php_errormsg, WARN);
//    }
    $i = 0;
    while ($unquery = @mysql_fetch_array($query))
    {
//echo $unquery['fecha'];
      $i++;
      $array[$i]['fecha'] = $unquery['fecha'];
      $array[$i]['id'] = $unquery['id'];
      $array[$i]['id_codigo_invitacion'] = $unquery['id_codigo_invitacion'];
      $array[$i]['email'] = $unquery['email'];
    }
    return serialize($array);
  }


  // *************************************
  //   Sacar datos de un idusuario, para pendientes
  // *************************************

  function Sacar_Datos_Pendientes_idusuario_conemail($idusuario)
  {
    $string = "SELECT a.id, a.email, a.fecha, a.id_codigo_invitacion
		FROM codigo_invitacion_pendiente a, codigo_invitacion b
		WHERE a.id_codigo_invitacion = b.id
		AND b.id_usuario_origen = $idusuario
		AND a.email IS NOT NULL
		AND a.email != ''
		";
//id_codigo_invitacion = $id
//    $this->log->Escribir_Log("SELECT Sacar_Datos_Pendientes: ".$string, DEBUG);
    $query = @mysql_query($string, $this->link_r);
//    if (isset($php_errormsg) && ($php_errormsg != null))
//    {
//      $this->log->Escribir_Log("ERROR Sacar_Datos_Pendientes : ".$php_errormsg, WARN);
//    }
    $i = 0;
    while ($unquery = @mysql_fetch_array($query))
    {
//echo $unquery['fecha'];
      $i++;
      $array[$i]['fecha'] = $unquery['fecha'];
      $array[$i]['id'] = $unquery['id'];
      $array[$i]['id_codigo_invitacion'] = $unquery['id_codigo_invitacion'];
      $array[$i]['email'] = $unquery['email'];
    }
    return serialize($array);
  }


  // *************************************
  //   Sacar datos de un idusuario, para pendientes
  // *************************************

  function Sacar_Datos_Pendientes_idusuario_beta($idusuario)
  {
    $string = "(
		SELECT a.id, a.email, a.fecha, b.codigo, a.id_codigo_invitacion
		FROM codigo_invitacion_pendiente a, codigo_invitacion b
		WHERE a.id_codigo_invitacion = b.id
		AND b.id_usuario_origen = $idusuario
		AND a.email = ''
		) UNION (
		SELECT a.id, a.email, a.fecha, b.codigo, a.id_codigo_invitacion
		FROM codigo_invitacion_pendiente a, codigo_invitacion b
		WHERE a.id_codigo_invitacion = b.id
		AND b.id_usuario_origen = $idusuario
		AND a.email IS NULL
		)
		";
//id_codigo_invitacion = $id
//    $this->log->Escribir_Log("SELECT Sacar_Datos_Pendientes: ".$string, DEBUG);
    $query = @mysql_query($string, $this->link_r);
//    if (isset($php_errormsg) && ($php_errormsg != null))
//    {
//      $this->log->Escribir_Log("ERROR Sacar_Datos_Pendientes : ".$php_errormsg, WARN);
//    }
    $i = 0;
    while ($unquery = @mysql_fetch_array($query))
    {
//echo $unquery['fecha'];
      $i++;
      $array[$i]['fecha'] = $unquery['fecha'];
      $array[$i]['id'] = $unquery['id'];
      $array[$i]['id_codigo_invitacion'] = $unquery['id_codigo_invitacion'];
      $array[$i]['codigo'] = $unquery['codigo'];
    }
    return serialize($array);
  }



  // *************************************
  //   Sacar datos de un codigo
  // *************************************

  function Sacar_Datos_Usado($id)
  {
    $string = "SELECT id, email, idusuario, fecha_uso, id_codigo_invitacion
		FROM codigo_invitacion_usado
		WHERE id_codigo_invitacion = $id
		ORDER BY fecha_uso DESC
		";
//    $this->log->Escribir_Log("SELECT Sacar_Datos_Usado: ".$string, DEBUG);
    $query = @mysql_query($string, $this->link_r);
//    if (isset($php_errormsg) && ($php_errormsg != null))
//    {
//      $this->log->Escribir_Log("ERROR Sacar_Datos_Usado : ".$php_errormsg, WARN);
//    }
    $i = 0;
    while ($unquery = @mysql_fetch_array($query))
    {
      $i++;
      $array[$i]['id'] = $unquery['id'];
      $array[$i]['email'] = $unquery['email'];
      $array[$i]['idusuario'] = $unquery['idusuario'];
      $array[$i]['fecha_uso'] = $unquery['fecha_uso'];
      $array[$i]['id_codigo_invitacion'] = $unquery['id_codigo_invitacion'];
    }
    return serialize($array);
  }





  // ***********************************
  //    Mete el codigo en la base de datos
  // ***********************************

//   id int not null auto_increment,
//   tipo_origen int,
//   id_usuario_origen int,
//   id_clan_origen int,
//   fecha_expedicion datetime,
//   usos int,
//   codigo CHAR(20),
//   envio_admin tinyint,
//   email_enviado_admin tinyint,
//   email_destinatario_admin VARCHAR(255),
//   nombre_destinatario_admin VARCHAR(255),

  function Inserta_Codigo_Userzone()
  {
    $string = "INSERT INTO codigo_invitacion
		(tipo_origen, id_usuario_origen,
		id_clan_origen, fecha_expedicion,
		usos, codigo,
		envio_admin, email_enviado_admin,
		email_destinatario_admin, nombre_destinatario_admin
		)
		VALUES
		($this->tipo_origen, $this->id_usuario_origen,
		$this->id_clan_origen,NOW(),
		$this->usos, '$this->codigo',
		0, 0, '', ''
		)
		";
//echo "#".$string."#";
//    $this->log->Escribir_Log("INSERT Inserta_Codigo_Userzone: ".$string, DEBUG);
    $query = @mysql_query($string, $this->link_w);
//    if (isset($php_errormsg) && ($php_errormsg != null))
//    {
//      $this->log->Escribir_Log("ERROR Inserta_Codigo_Userzone : ".$php_errormsg, WARN);
//    }
  }



  function Inserta_Pendiente()
  {
    $string = "INSERT INTO codigo_invitacion_pendiente
		(email, id_codigo_invitacion, fecha)
		VALUES
		('$this->cip_email', $this->cip_id_codigo_invitacion, NOW())
		";
//echo "#".$string."#";
//    $this->log->Escribir_Log("INSERT Inserta_Pendiente: ".$string, DEBUG);
    $query = @mysql_query($string, $this->link_w);
//    if (isset($php_errormsg) && ($php_errormsg != null))
//    {
//      $this->log->Escribir_Log("ERROR Inserta_Pendiente : ".$php_errormsg, WARN);
//    }
  }


  // *********************************************
  //   Funcion principal de insercion de codigo
  // *********************************************
  //  Aqui se puede llamar directamente para insertar uno

  function Inserta_Codigo_Userzone_Usuario($idusuario, $email)
  {
    $this->tipo_origen = 1;   // Ponemos el tipo a usuario
    $this->id_usuario_origen = $idusuario;
    $this->id_clan_origen = 0;
    $this->usos = 1;
    $codigo = $this->Generar_Codigo(); // Esto escribe el codigo en $this->codigo
    $this->codigo = $codigo;

    // Insertamos el codigo en su tabla
    $this->Inserta_Codigo_Userzone();
    $nuevoid = mysql_insert_id($this->link_w);

    // Y ahora generamos una entrada en la tabla de pendientes
    $this->cip_id_codigo_invitacion = $nuevoid;
    $this->cip_email = $email;
    $this->Inserta_Pendiente($email);

  }

  // ***********************************************+
  //   Insercion de codigo en clanes
  // ***********************************************+

/* ESTA FUNCION NO SE APLICA, PORQUE SOLO SE GENERA UN CODIGO / CLAN CON MAXCLAN USOS
  function Aumenta_Codigo_Userzone_Clan($idclan, $email)
  {
    $select = "SELECT id, usos
		FROM codigo_invitacion
		WHERE id_clan_origen = $idclan
		";
    $query = @mysql_query($select, $this->link_r);
    if ($unquery = @mysql_num_rows($query))
    {
      $idcodigo = $unquery['id'];
      $usos = $unquery['usos'];
      $usos++;
      $string = "UPDATE codigo_invitacion
		SET usos = $usos
		WHERE id = $idcodigo
		";
      $query2 = @mysql_query($string, $this->link_w);

      // Y ahora lo aumentamos creando una entrada en pendiente
      $this->cip_email = $email;
      $this->cip_id_codigo_invitacion = $idcodigo;
      $this->Inserta_Pendiente();
    }
  }
*/
  function Inserta_Codigo_Userzone_Clan($idusuario, $idclan, $email)
  {
    $this->tipo_origen = 2;   // Ponemos el tipo a usuario
    $this->id_usuario_origen = $idusuario;
    $this->id_clan_origen = $idclan; 
// $this->usos = 1;
    $this->usos = 50;   // Generamos uno con 50 usos y palante
    $codigo = $this->Generar_Codigo(); // Esto escribe el codigo en $this->codigo
    $this->codigo = $codigo;

    // Insertamos el codigo en su tabla
    $this->Inserta_Codigo_Userzone();
    $nuevoid = mysql_insert_id($this->link_w);

    // Y ahora generamos una entrada en la tabla de pendientes
    $this->cip_id_codigo_invitacion = $nuevoid;
    $this->cip_email = $email;
//    $this->Inserta_Pendiente($email);
    $this->Inserta_Pendiente();

  }


  //*************************************************************

  function Inserta_Codigo_Admin()
  {
    $string = "INSERT INTO codigo_invitacion
		(tipo_origen, id_usuario_origen,
		id_clan_origen, fecha_expedicion,
		usos, codigo,
		envio_admin, email_enviado_admin,
		email_destinatario_admin, nombre_destinatario_admin
		)
		VALUES
		(3, 0,
		0, NOW(),
		$this->usos, '$this->codigo',
		1, $this->email_enviado_admin,
		'$this->email_destinatario_admin', '$this->nombre_destinatario_admin'
		)
		";
//    $this->log->Escribir_Log("INSERT Inserta_Codigo_Admin: ".$string, DEBUG);
//echo $string;
    $query = @mysql_query($string, $this->link_w);
//    if (isset($php_errormsg) && ($php_errormsg != null))
//    {
//      $this->log->Escribir_Log("ERROR Inserta_Codigo_Admin : ".$php_errormsg, WARN);
//    }
    $idcodigo = mysql_insert_id($this->link_w);
    return $idcodigo;
  }




  // ····································································
  //           Funciones para comprobar si se puede invitar
  // ····································································


  function Existe_Codigo_Clan($idclan)
  {
    $string = "SELECT id FROM
		codigo_invitacion
		WHERE id_clan_origen = $idclan
		";
//    $this->log->Escribir_Log("SELECT Existe_Codigo_Clan: ".$string, DEBUG);
    $query = @mysql_query($string, $this->link_r);
//    if (isset($php_errormsg) && ($php_errormsg != null))
//    {
//      $this->log->Escribir_Log("ERROR Existe_Codigo_Clan : ".$php_errormsg, WARN);
//    }
    if (@mysql_num_rows($query) > 0)
    {
      return true;
    } else {
      return false;
    }
  }

  // ***********************************************
  //            Puede invitar un clan?
  // ***********************************************

  function Puede_Invitar_Clan($idclan) //, $cuantos_generar)
  {
    //  Esta funcion esta simplificada, ahora solo queremos saber
    // si hay una entrada, pq solo se usa un codigo para el clan. Asi,
    // ahora es, hay codigo_invitacion tipo=2 del clan ��$idclan? Si lo hay false,
    // y sino, true

    // Primero buscamos cuantos hay por usuario
//    $cantidad_max = $this->Obtener_Invitaciones_Maximas_Clan();

    $string = "SELECT id, usos FROM
		codigo_invitacion
		WHERE id_clan_origen = $idclan
		AND tipo_origen = 2
		";
//    $this->log->Escribir_Log("SELECT Puede_Invitar_Clan: ".$string, DEBUG);
    $query = @mysql_query($string, $this->link_r);
//    if (isset($php_errormsg) && ($php_errormsg != null))
//    {
//      $this->log->Escribir_Log("ERROR Puede_Invitar_Clan : ".$php_errormsg, WARN);
//    }
    $total = 0;
//    while ($unquery = @mysql_num_rows($query))
//    {
//      $total = $total + $unquery['usos'];
//    }
    //  Si el total con usos de este clan + los solicitados en $cuantos_generar
    // suman mas que la cantidad maxima, no lo permite y devuelve false
//    if ($total + $cuantos_generar > $cantidad_max)

    if (@mysql_num_rows($query) > 0)
    {
      return false;
    } else {
      return true;
    }

  }


  // ***********************************************
  //            Puede invitar un usuario?
  // ***********************************************

  function Puede_Invitar_Usuario($idusuario, $cuantos_generar)
  {
    // Primero buscamos cuantos hay por usuario
    $cantidad_max = $this->Obtener_Invitaciones_Maximas_Usuario();

    // Ahora vamos a ver todas las que se han generado, pendietnes o no
    $string = "SELECT id, usos FROM
		codigo_invitacion
		WHERE id_usuario_origen = $idusuario
		AND tipo_origen = 1
		";
    // Ahora le restamos las que hay que haya usado ya.
//    $this->log->Escribir_Log("SELECT Puede_Invitar_Usuario: ".$string, DEBUG);
    $query = @mysql_query($string, $this->link_r);
//    if (isset($php_errormsg) && ($php_errormsg != null))
//    {
//      $this->log->Escribir_Log("ERROR Puede_Invitar_Usuario : ".$php_errormsg, WARN);
//    }
    $total = 0;
    while ($unquery = @mysql_fetch_array($query))
    {
      $total = $total + $unquery['usos'];
    }


    // Ahora con las extensiones, tendremos que buscar lo que se ha extendido
    // para este usuario, y sumarsela a $cantidad_max
    $string2 = "SELECT id, usos_extendidos
		FROM codigo_invitacion_extender
		WHERE idusuario = $idusuario
		";
    $query2 = mysql_query($string2, $this->link_r);
    $total_ext = 0;
    while ($unquery2 = @mysql_fetch_array($query2))
    {
      $total_ext = $total_ext + $unquery2['usos_extendidos'];
    }
    $cantidad_max = $cantidad_max + $total_ext;


    //  Si el total con usos de este usuario + los solicitados en $cuantos_generar
    // suman mas que la cantidad maxima, no lo permite y devuelve false
    if (($total + $cuantos_generar) <= $cantidad_max)
    {
      return true;
    } else {
      return false;
    }

  }


  // ····································································
  //        Funciones especificas del ADMIN
  // ····································································

  // ************************************************
  //    Contamos cuantas invitaciones hay de un tipo
  // ************************************************

  function Borrar_Invitacion($idelemento)
  {
    $string = "DELETE
		FROM codigo_invitacion
		WHERE id = $idelemento
		";
//    $this->log->Escribir_Log("DELETE Borrar_Invitacion: ".$string, DEBUG);
//echo $string;
    $query = @mysql_query($string, $this->link_w);
//    if (isset($php_errormsg) && ($php_errormsg != null))
//    {
//      $this->log->Escribir_Log("ERROR Borrar_Invitacion : ".$php_errormsg, WARN);
//    }

    $string = "DELETE
		FROM codigo_invitacion_pendiente
		WHERE id_codigo_invitacion = $idelemento
		";
//    $this->log->Escribir_Log("DELETE Borrar_Invitacion: ".$string, DEBUG);
//echo $string;
    $query = @mysql_query($string, $this->link_w);
//    if (isset($php_errormsg) && ($php_errormsg != null))
//    {
//      $this->log->Escribir_Log("ERROR Borrar_Invitacion : ".$php_errormsg, WARN);
//    }

    $string = "DELETE
		FROM codigo_invitacion_usado
		WHERE id_codigo_invitacion = $idelemento
		";
//    $this->log->Escribir_Log("DELETE Borrar_Invitacion: ".$string, DEBUG);
//echo $string;
    $query = @mysql_query($string, $this->link_w);
//    if (isset($php_errormsg) && ($php_errormsg != null))
//    {
//      $this->log->Escribir_Log("ERROR Borrar_Invitacion : ".$php_errormsg, WARN);
//    }

  }


  // ************************************************
  //    Contamos cuantas invitaciones hay de un tipo
  // ************************************************

  function Contar_Elementos($tipo)
  {
    // Del tipo admin
    $string = "SELECT id
		FROM codigo_invitacion
		WHERE tipo_origen = $tipo
		";
//    $this->log->Escribir_Log("SELECT Contar_Elementos: ".$string, DEBUG);
    $query = @mysql_query($string, $this->link_r);
//    if (isset($php_errormsg) && ($php_errormsg != null))
//    {
//      $this->log->Escribir_Log("ERROR Contar_Elementos : ".$php_errormsg, WARN);
//    }
    return serialize(mysql_num_rows($query));
  }

  // ************************************************
  //    Buscamos cuantas invitaciones hay de un tipo
  // ************************************************

  function Buscar_Elementos($tipo, $offset, $limit)
  {
    // Del tipo admin
    $string = "SELECT id, usos, id_usuario_origen,
		id_clan_origen, fecha_expedicion,
		usos, codigo, envio_admin,
		email_enviado_admin, email_destinatario_admin,
		nombre_destinatario_admin
		FROM codigo_invitacion
		WHERE tipo_origen = $tipo
		ORDER BY fecha_expedicion DESC
		LIMIT $limit OFFSET $offset
		";
//    $this->log->Escribir_Log("SELECT Buscar_Elementos: ".$string, DEBUG);

    $query = mysql_query($string, $this->link_r);
//    if (isset($php_errormsg) && ($php_errormsg != null))
//    {
//      $this->log->Escribir_Log("ERROR Buscar_Elementos : ".$php_errormsg, WARN);
//    }
    $i = 0;
    while ($unquery = mysql_fetch_array($query))
    {
      $i++;
      $array[$i]['id'] = $unquery['id'];
      $array[$i]['usos'] = $unquery['usos'];
      $array[$i]['id_usuario_origen'] = $unquery['id_usuario_origen'];
      $array[$i]['id_clan_origen'] = $unquery['id_clan_origen'];
      $array[$i]['fecha_expedicion'] = $unquery['fecha_expedicion'];
      $array[$i]['usos'] = $unquery['usos'];
      $array[$i]['codigo'] = $unquery['codigo'];
      $array[$i]['envio_admin'] = $unquery['envio_admin'];
      $array[$i]['email_enviado_admin'] = $unquery['email_enviado_Admin'];
      $array[$i]['email_destinatario_admin'] = $unquery['email_destinatario_admin'];
      $array[$i]['nombre_destinatario_admin'] = $unquery['nombre_destinatario_admin'];
    }
    return serialize($array);
  }


  // ····································································
  //        Funciones de conteo de invitaciones enviadas/usadas
  // ····································································


  // ******************************************
  //   Grabar un parametro de la web
  // ******************************************

  function Grabar_Invitaciones_Maximas($nombre, $valor)
  {
    $string = "UPDATE codigo_invitacion_parametros
		SET valor = $valor
		WHERE nombre = '$nombre'
		";
//    $this->log->Escribir_Log("UPDATE Grabar_Invitaciones_Maximas: ".$string, DEBUG);
    $query = @mysql_query($string, $this->link_w);
//    if (isset($php_errormsg) && ($php_errormsg != null))
//    {
//      $this->log->Escribir_Log("ERROR Grabar_Invitaciones_Maximas : ".$php_errormsg, WARN);
//    }
  }


  // ******************************************
  //   Obtener invitaciones maximas clan
  // ******************************************

  function Obtener_Invitaciones_Maximas_Clan()
  {
    $string = "SELECT a.valor
		FROM codigo_invitacion_parametros a
		WHERE a.nombre = 'MAXCLAN'
		";
//    $this->log->Escribir_Log("SELECT Obtener_Invitaciones_Maximas_Clan: ".$string, DEBUG);
    $query = @mysql_query($string, $this->link_r);
//    if (isset($php_errormsg) && ($php_errormsg != null))
//    {
//      $this->log->Escribir_Log("ERROR Obtener_Invitaciones_Maximas_Clan : ".$php_errormsg, WARN);
//    }
    if ($unquery = @mysql_fetch_array($query))
    {
      return $unquery['valor'];
    } else {
      return -1;
    }
  }

  // ******************************************
  //   Obtener invitaciones maximas usuario
  // ******************************************

  function Obtener_Invitaciones_Maximas_Usuario()
  {
    $string = "SELECT a.valor
		FROM codigo_invitacion_parametros a
		WHERE a.nombre = 'MAXUSUARIO'
		";
//echo $string;
//    $this->log->Escribir_Log("SELECT Obtener_Invitaciones_Maximas_Usuario: ".$string, DEBUG);
    $query = @mysql_query($string, $this->link_r);
//    if (isset($php_errormsg) && ($php_errormsg != null))
//    {
//      $this->log->Escribir_Log("ERROR Obtener_Invitaciones_Maximas_Usuario : ".$php_errormsg, WARN);
//    }
    if ($unquery = @mysql_fetch_array($query))
    {
//echo ("#".$unquery['valor']."#");
      return $unquery['valor'];
    } else {
//echo ("#-1#");
      return -1;
    }
  }


  // ******************************************
  //   Obtener cuantas invitaciones ha hecho un clan
  // ******************************************

  function Obtener_Invitaciones_Enviadas_Exito_Clan($idclan)
  {
    $string = "SELECT b.id
		FROM codigo_invitacion a, codigo_invitacion_usado b
		WHERE a.tipo_origen = 2
		AND a.id_clan_origen = $idclan
		AND b.id_codigo_invitacion = a.id
		";
//    $this->log->Escribir_Log("SELECT Obtener_Invitaciones_Enviadas_Exito_Clan: ".$string, DEBUG);
    $query = @mysql_query($string, $this->link_r);
//    if (isset($php_errormsg) && ($php_errormsg != null))
//    {
//      $this->log->Escribir_Log("ERROR Obtener_Invitaciones_Enviadas_Exito_Clan : ".$php_errormsg, WARN);
//    }
    return @mysql_num_rows($query);
  }

  // ******************************************
  //   Obtener cuantas invitaciones ha hecho un clan
  // ******************************************

  function Obtener_Invitaciones_Enviadas_SinUsar_Clan($idclan)
  {
    $string = "SELECT b.id
		FROM codigo_invitacion a, codigo_invitacion_pendiente b
		WHERE a.tipo_origen = 2
		AND a.id_clan_origen = $idclan
		AND b.id_codigo_invitacion = a.id
		";
//    $this->log->Escribir_Log("SELECT Obtener_Invitaciones_Enviadas_SinUsar_Clan: ".$string, DEBUG);
    $query = @mysql_query($string, $this->link_r);
//    if (isset($php_errormsg) && ($php_errormsg != null))
//    {
//      $this->log->Escribir_Log("ERROR Obtener_Invitaciones_Enviadas_SinUsar_Clan : ".$php_errormsg, WARN);
//    }
    return @mysql_num_rows($query);
  }

  // ******************************************
  //   Obtener cuantas invitaciones ha hecho un clan, con o sin exito
  // ******************************************
  // ******************************************

  function Obtener_Invitaciones_Enviadas_Clan($idclan)
  {
    $string = "(SELECT b.id
		FROM codigo_invitacion a, codigo_invitacion_usado b
		WHERE a.tipo_origen = 2
		AND a.id_clan_origen = $idclan
		AND b.id_codigo_invitacion = a.id
		) UNION (
		SELECT b.id
		FROM codigo_invitacion a, codigo_invitacion_pendiente b
		WHERE a.tipo_origen = 2
		AND a.id_clan_origen = $idclan
		AND b.id_codigo_invitacion = a.id
		)
		";
//echo $string;
//    $this->log->Escribir_Log("SELECT Obtener_Invitaciones_Enviadas_Clan: ".$string, DEBUG);
    $query = @mysql_query($string, $this->link_r);
//    if (isset($php_errormsg) && ($php_errormsg != null))
//    {
//      $this->log->Escribir_Log("ERROR Obtener_Invitaciones_Enviadas_Clan : ".$php_errormsg, WARN);
//    }
    return @mysql_num_rows($query);
  }

  // ******************************************
  //   Obtener cuantas invitaciones ha hecho un usuario
  // ******************************************

  function Obtener_Invitaciones_Enviadas_Usuario($idusuario)
  {
    $string = "(SELECT b.id
		FROM codigo_invitacion a, codigo_invitacion_usado b
		WHERE a.tipo_origen = 1
		AND a.id_usuario_origen = $idusuario
		AND b.id_codigo_invitacion = a.id
		) UNION (
		SELECT b.id
		FROM codigo_invitacion a, codigo_invitacion_pendiente b
		WHERE a.tipo_origen = 1
		AND a.id_usuario_origen = $idusuario
		AND b.id_codigo_invitacion = a.id
		)
		";
//echo $string;
//    $this->log->Escribir_Log("SELECT Obtener_Invitaciones_Enviadas_Usuario: ".$string, DEBUG);
    $query = @mysql_query($string, $this->link_r);
    return @mysql_num_rows($query);
  }



  // ******************************************
  //   Obtener cuantas invitaciones le quedan a un luser
  // ******************************************

  function Obtener_Cuantas_Invitaciones_Pendientes($idusuario)
  {
    $cuantas_max = $this->Obtener_Invitaciones_Maximas_Usuario();

    // Ahora con las extensiones, tendremos que buscar lo que se ha extendido
    // para este usuario, y sumarsela a $cantidad_max
    $string2 = "SELECT id, usos_extendidos
                FROM codigo_invitacion_extender
                WHERE idusuario = $idusuario
                ";
    $query2 = mysql_query($string2, $this->link_r);
    $total_ext = 0;
    while ($unquery2 = @mysql_fetch_array($query2))
    {
      $total_ext = $total_ext + $unquery2['usos_extendidos'];
    }

    $cuantas_usadas = $this->Obtener_Invitaciones_Enviadas_Usuario($idusuario);

    $pendientes = $cuantas_max + $total_ext - $cuantas_usadas;
//    $pendientes = $cuantas_max - $cuantas_usadas;
    return $pendientes;
  }

  // ******************************************
  //   Obtener cuantas invitaciones le quedan a un clan
  // ******************************************

//  function Obtener_Cuantas_Invitaciones_Pendientes_Clan($idclan)
//  {
//    $cuantas_max = $this->Obtener_Invitaciones_Maximas_Clan();
//    $cuantas_usadas = $this->Obtener_Invitaciones_Enviadas_Clan($idclan);
//    $pendientes = $cuantas_max - $cuantas_usadas;
//    return $pendientes;
//  }


  // ******************************************
  //   Obtener cuantas invitaciones ha hecho un usuario sin usar
  // ******************************************

  function Obtener_Invitaciones_Enviadas_SinUsar_Usuario($idusuario)
  {
    $string = "SELECT b.id
		FROM codigo_invitacion a, codigo_invitacion_pendiente b
		WHERE a.tipo_origen = 1
		AND a.id_usuario_origen = $idusuario
		AND b.id_codigo_invitacion = a.id
		";
//    $this->log->Escribir_Log("SELECT Obtener_Invitaciones_Enviadas_SinUsar_Usuario: ".$string, DEBUG);
    $query = @mysql_query($string, $this->link_r);
//    if (isset($php_errormsg) && ($php_errormsg != null))
//    {
//      $this->log->Escribir_Log("ERROR Obtener_Invitaciones_Enviadas_SinUsar_Usuario : ".$php_errormsg, WARN);
//    }
    return @mysql_num_rows($query);
  }

  // ******************************************
  //   Obtener cuantas invitaciones ha hecho un usuario con exito
  // ******************************************

  function Obtener_Invitaciones_Enviadas_Exito_Usuario($idusuario)
  {
    $string = "SELECT b.id
		FROM codigo_invitacion a, codigo_invitacion_usado b
		WHERE a.tipo_origen = 1
		AND a.id_usuario_origen = $idusuario
		AND b.id_codigo_invitacion = a.id
		";
//    $this->log->Escribir_Log("SELECT Obtener_Invitaciones_Enviadas_Exito_Usuario: ".$string, DEBUG);
    $query = @mysql_query($string, $this->link_r);
//    if (isset($php_errormsg) && ($php_errormsg != null))
//    {
//      $this->log->Escribir_Log("ERROR Obtener_Invitaciones_Enviadas_Exito_Usuario : ".$php_errormsg, WARN);
//    }
    return @mysql_num_rows($query);
  }




}

?>
