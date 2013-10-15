<?php

class Mensajes_Personales {

 
	var $id;
	var $idrespondido;
	var $idusuarioorigen;
	var $idusuariodestino;
 	var $asunto;
	var $contenido;
	var $fecha_envio;
	var $leido;
	var $fecha_apertura;
	var $respondido;
	var $borradoorigen;
	var $borradodestinatario;


   // *****************************************************
   //   Comprueba si se esta reenviando el mismo ultimo mensaje
   // *****************************************************

   function ComprobarMensaje($link_r, $idjugador, $idusuariodestino, $asunto, $contenido)
   {
     $string = "SELECT id
		FROM mensaje
		WHERE idusuarioorigen = $idjugador
		AND idusuariodestino = $idusuariodestino
		AND asunto = '$asunto'
		AND contenido = '$contenido'
		AND fecha_envio >= DATE_SUB(NOW(), INTERVAL 1 HOUR)
		";
//echo $string;
     $query = mysql_query($string, $link_r);
     return mysql_num_rows($query);
   }


   // *****************************************************
   //   Ponemos un mensaje como leido
   // *****************************************************

   function MarcarRespondido($link_w, $origenid)
   {
          $updatearrespondido = mysql_query("UPDATE mensaje
                                        SET respondido = 1
                                        WHERE id = $origenid
                                        ", $link_w);
   }


   // *****************************************************
   //   Ponemos un mensaje como leido
   // *****************************************************

   function InsertarMensaje($link_w)
   {
      if ($this->idrespondido == '') { $this->idrespondido = 0; }

      $insertarusuario = mysql_query("INSERT INTO mensaje
                                (idusuarioorigen, idusuariodestino, asunto,
                                contenido, fecha_envio, leido,
                                fecha_apertura, respondido,
                                borradoorigen, borradodestinatario,
				idrespondido)
                                VALUES
                                ($this->idusuarioorigen, $this->idusuariodestino, '$this->asunto',
                                '$this->contenido', NOW(), 0,
                                null, 0,
                                0,0,
				$this->idrespondido)
                                ", $link_w);


   }



   // *****************************************************
   //   Ponemos un mensaje como leido
   // *****************************************************

   function UpdatearLeido($link_w, $idmensaje)
   {

         $updatearleido = mysql_query("UPDATE mensaje
                        SET leido = 1,
                        fecha_apertura = NOW()
                        WHERE id = $idmensaje
                        ", $link_w);

   }

   // *****************************************************
   //   Sacar los datos de un mensaje
   // *****************************************************

   function SacarDatos($link_r, $idmensaje) 
   {

         $querymensajes = mysql_query("SELECT a.id, a.idusuarioorigen, a.idusuariodestino,
                      a.asunto, a.contenido, a.fecha_envio,
                      a.leido, a.fecha_apertura, a.respondido, a.idrespondido
                      FROM mensaje a
                      WHERE a.id = $idmensaje
                      ", $link_r);
         if ($unmensaje = mysql_fetch_array($querymensajes))
         {
           $this->id = $unmensaje['id'];
           $this->idusuarioorigen = $unmensaje['idusuarioorigen'];
           $this->idusuariodestino = $unmensaje['idusuariodestino'];
           $this->asunto = $unmensaje['asunto'];
           $this->contenido = $unmensaje['contenido'];
           $this->fecha_envio = $unmensaje['fecha_envio'];
           $this->leido = $unmensaje['leido'];
           $this->fecha_apertura = $unmensaje['fecha_apertura'];
           $this->respondido = $unmensaje['respondido'];
           $this->idrespondido = $unmensaje['idrespondido'];

         } else {
           return 0;
         }


   }

   // *****************************************************
   //     "Borrar" un mensaje en su destinatario
   // *****************************************************

   function BorrarEnDestino($link_w, $idmensaje) 
   {

      $updatemensajes = mysql_query("UPDATE mensaje
                      SET borradodestinatario = 1
                      WHERE id = $idmensaje
                      ", $link_w);
   }


   // *****************************************************
   //     "Borrar" un mensaje en su origen
   // *****************************************************

   function BorrarEnOrigen($link_w, $idmensaje) 
   {
      $updatemensajes = mysql_query("UPDATE mensaje
                      SET borradoorigen = 1
                      WHERE id = $idmensaje
                      ", $link_w);
   }


   // *****************************************************
   //     Existe este mensaje del que soy destinatario
   // *****************************************************

   function ExisteYSoyDestino($link_r, $idusuario, $idmensaje)
   {
      $querycheck = mysql_query("SELECT id FROM mensaje
                        WHERE idusuariodestino = $idusuario
                        AND id = $idmensaje
                        ", $link_r);
      if (mysql_num_rows($querycheck) > 0)
      {
        return 1;
      } else {
        return 0;
      }
   }

   // *****************************************************
   //     Existe este mensaje del que soy origen?
   // *****************************************************

   function ExisteYSoyOrigen($link_r, $idusuario, $idmensaje)
   {

      $querymensajes = mysql_query("SELECT id FROM mensaje
                      WHERE idusuarioorigen = $idusuario
                      AND id = $idmensaje
                      ", $link_r);
      if (mysql_num_rows($querymensajes) > 0)
      {
        return 1;
      } else {
        return 0;
      }
   }

   // *****************************************************
   //     Query para obtener todos los mensajes de la carpeta de enviados
   // *****************************************************

   function ObtenerElementosEnviados($link_r, $idusuario, $limit, $offset)
   {

      $querymensajes = mysql_query("SELECT a.id, a.idusuarioorigen, a.idusuariodestino,
                        a.asunto, a.contenido, a.fecha_envio, a.idrespondido,
                        a.leido, a.fecha_apertura, a.respondido, b.login
                        FROM mensaje a, jugador b
                        WHERE a.idusuarioorigen = $idusuario
                        AND b.id = a.idusuariodestino
                        AND a.borradoorigen = 0
                        ORDER BY fecha_envio DESC
			LIMIT $limit
			OFFSET $offset
                        ", $link_r);
      return $querymensajes;

   }


   // *****************************************************
   //     Query para obtener todos los mensajes de la carpeta de enviados
   // *****************************************************

   function ContarElementosEnviados($link_r, $idusuario)
   {

      $querymensajes = mysql_query("SELECT a.id
                        FROM mensaje a, jugador b
                        WHERE a.idusuarioorigen = $idusuario
                        AND b.id = a.idusuariodestino
                        AND a.borradoorigen = 0
                        ORDER BY fecha_envio DESC
                        ", $link_r);
      return mysql_num_rows($querymensajes);

   }

   // *****************************************************
   //     Query para contar los mensajes del Inbox
   // *****************************************************

   function ContarElementosInbox($link_r, $idusuario)
   {

     $stringmensajes = "SELECT a.id
                        FROM mensaje a, jugador b
                        WHERE a.idusuariodestino = $idusuario
                        AND b.id = a.idusuarioorigen
                        AND a.borradodestinatario = 0
                        ORDER BY fecha_envio DESC
                        ";
//echo $stringmensajes;
     $querymensajes = mysql_query($stringmensajes, $link_r);
     return mysql_num_rows($querymensajes);

   }

   // *****************************************************
   //     Query para obtener todos los mensajes del Inbox
   // *****************************************************

   function ObtenerElementosInbox($link_r, $idusuario, $limit, $offset)
   {

     $stringmensajes = "SELECT a.id, a.idusuarioorigen, a.idusuariodestino,
                        a.asunto, a.contenido, a.fecha_envio, a.idrespondido,
                        a.leido, a.fecha_apertura, a.respondido, b.login
                        FROM mensaje a, jugador b
                        WHERE a.idusuariodestino = $idusuario
                        AND b.id = a.idusuarioorigen
                        AND a.borradodestinatario = 0
                        ORDER BY fecha_envio DESC
			LIMIT $limit
			OFFSET $offset
                        ";
//echo $stringmensajes;
     $querymensajes = mysql_query($stringmensajes, $link_r);
     return $querymensajes;

   }



   // *****************************************************
   //     Query para contar los mensajes del Inbox
   // *****************************************************

   function ContarElementosNoLeidos($link_r, $idusuario)
   {

     $stringmensajes = "SELECT a.id
                        FROM mensaje a, jugador b
                        WHERE a.idusuariodestino = $idusuario
                        AND b.id = a.idusuarioorigen
                        AND a.borradodestinatario = 0
			AND leido = 0
                        ORDER BY fecha_envio DESC
                        ";
//echo $stringmensajes;
     $querymensajes = mysql_query($stringmensajes, $link_r);
     return mysql_num_rows($querymensajes);

   }




}

?>
