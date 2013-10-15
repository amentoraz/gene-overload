<?php
class Jugador
{

	var $id;
	var $login;
	var $nombre;
	var $email;
	var $email_publico;
	var $texto_presentacion;
	var $avatar;
	var $fecha_alta;
	var $es_premium;
	var $fecha_fin_premium;
	var $baneado;
	var $es_admin;
	var $clave;
        var $idfotoperfil;
	var $envio_emails;
	var $envio_boletines;
	var $diferencia;
        var $lang;
	var $id_tmz;
	var $tutorial;




  // ***********************************
  //   Activa un usuario
  // ***************************************

  function Activar($link_w, $email)
  {
    $string = "UPDATE jugador
		SET activado = 1
		WHERE email = '$email'
		";
    $query = mysql_query($string, $link_w);
  }

  // ***********************************
  //   Existe un usuario sin activar
  // ***************************************

  function ExisteSinActivar($link_r, $email)
  {
    $string = "SELECT activado
		FROM jugador
		WHERE email = '$email'
		";
    $query = mysql_query($string, $link_r);
    if ($unquery = mysql_fetch_array($string))
    {
      return $unquery['activado'];
    } else {
      return -1;
    }
  }


  // ***********************************
  //   Alteramos lo del tutorial
  // ***************************************

  function SacarTutorial($link_r, $idjugador)
  {
    $string = "SELECT tutorial
		FROM jugador
		WHERE id = $idjugador
		";
    $query = mysql_query($string, $link_r);
    if ($unquery = mysql_fetch_array($query))
    {
      $tutorial = $unquery['tutorial'];
      return $tutorial;
    } else {
      return -1;
    }
  }

  // ***********************************
  //   Alteramos lo del tutorial
  // ***************************************

  function AlterarTutorial($link_w, $nuevo_estado, $idjugador)
  {
    $string = "UPDATE jugador
		SET tutorial = $nuevo_estado
		WHERE id = $idjugador
		";
    $query = mysql_query($string, $link_w);
  }

  // ***********************************
  //   Buscar los jugadores premium en 3 dias
  // ***************************************

  function BuscarPremiumFin($link_r, $dias)
  {
    $dias2 = $dias + 1;
    $string = "SELECT id
		FROM jugador
		WHERE fecha_fin_premium >= DATE_ADD(NOW(), INTERVAL $dias DAY)
		AND fecha_fin_premium <= DATE_ADD(NOW(), INTERVAL $dias2 DAY)
		";
//echo $string;
    $query = mysql_query($string, $link_r);
    $i = 0;
    while ($unquery = mysql_fetch_array($query))
    {
      $i++;
      $array[$i]['id'] = $unquery['id'];
    }
    return $array;
  }



  // ***********************************
  //   Cambiar la clave
  // ***************************************

  function CambiarClave($link_w, $idelemento)
  {
    $clave_md5 = md5($this->clave);
    $string = "UPDATE jugador
		SET clave = '$clave_md5'
		WHERE id = $idelemento
		";
//echo $string;
    $query = mysql_query($string, $link_w);
  }

  // ***************************************
  //   Insertar un nuevo usuario
  // ***************************************

  function Insertar($link_w)
  {
    $clave_md5 = md5($this->clave);
    if ($this->id_tmz == '') { $this->id_tmz = '7'; }
    $string = "INSERT INTO jugador
		(login, nombre, email, email_publico,
		texto_presentacion, avatar, fecha_alta,
		es_premium, fecha_fin_premium, baneado,
		es_admin, clave, idfotoperfil,
		envio_emails, envio_boletines, lang,
		id_tmz)
		VALUEs
		('$this->login','$this->nombre', '$this->email', $this->email_publico,
		'$this->texto_presentacion', NULL, NOW(),
		0, null, 0,
		0, '$clave_md5', null,
		1, 1, '$this->lang',
		$this->id_tmz)
		";
//$this->envio_boletines)
//echo $string;
    $query = mysql_query($string, $link_w);
  }



  // ***************************************
  //   Updatea al usuario
  // ***************************************

  function UpdatearImagen($link_w, $idjugador, $idimagen)
  {
    $string = "UPDATE jugador
		SET idfotoperfil = $idimagen
		WHERE id = $idjugador
		";
    $query = mysql_query($string, $link_w);
  }



  // ***************************************
  //   Saca el lenguaje del jugador
  // ***************************************

  function SacarLang($link_r, $idjugador)
  {
    $string = "SELECT lang
		FROM jugador
		WHERE id = $idjugador
		";
    $query = mysql_query($string, $link_r);
    $unquery = mysql_fetch_array($query);
    return $unquery['lang'];
  }

  // ***************************************
  //   Graba datos de perfil
  // ***************************************

  function GrabarDatos($link_w, $id)
  {
    if ($this->id_tmz == '') { $this->id_tmz = '7'; }
    $string = "UPDATE jugador
		SET nombre = '$this->nombre',
		email_publico = '$this->email_publico',
		texto_presentacion = '$this->texto_presentacion',
		lang = '$this->lang',
		envio_emails = $this->envio_emails,
		envio_boletines = $this->envio_boletines,
		id_tmz = $this->id_tmz
		WHERE id = $id
		";
    $query = mysql_query($string, $link_w);
  }


  // ***************************************
  //   Desbanear a un jugador
  // ***************************************

  function Desbanear($link_w, $idelemento)
  {
    $string = "UPDATE jugador
		SET baneado = 0
		WHERE id = $idelemento
		";
    $query = mysql_query($string, $link_w);
  }

  // ***************************************
  //   Banear a un jugador
  // ***************************************

  function Banear($link_w, $idelemento)
  {
    $string = "UPDATE jugador
		SET baneado = 1
		WHERE id = $idelemento
		";
    $query = mysql_query($string, $link_w);
  }


  // ***************************************
  //   Cuenta todos los jugadores
  // ***************************************

  function ContarElementos($link_r, $listbaneados)
  {
    $string = "SELECT id FROM jugador WHERE 1=1 ";
    if ($listbaneados == 2)
    {
      $string = $string." AND baneado = 0";
    }
    if ($listbaneados == 1)
    {
      $string = $string." AND baneado = 1";
    }
    $query = mysql_query($string, $link_r);
    return mysql_num_rows($query);
  }

  // ***************************************
  //   Obtiene todos los jugadores
  // ***************************************

  function BuscarElementos($link_r, $offset, $limit, $listbaneados)
  {
    $string = "SELECT id, login, nombre, email,
		email_publico, texto_presentacion,
		avatar, fecha_alta, es_premium,
		fecha_fin_premium, baneado, es_admin, lang,
		envio_emails, envio_boletines, id_tmz
		FROM jugador
		WHERE 1=1
		";
    if ($listbaneados == 2)
    {
      $string = $string." AND baneado = 0";
    }
    if ($listbaneados == 1)
    {
      $string = $string." AND baneado = 1";
    }
    $string = $string."
		ORDER BY fecha_alta DESC
		LIMIT $limit
		OFFSET $offset
		";
//echo $string;
    $query = mysql_query($string, $link_r);
    $i = 0;
    while ($unquery = mysql_fetch_array($query))
    {
      $i++;
      $array[$i]['id'] = $unquery['id'];
      $array[$i]['login'] = $unquery['login'];
      $array[$i]['nombre'] = $unquery['nombre'];
      $array[$i]['email'] = $unquery['email'];
      $array[$i]['email_publico'] = $unquery['email_publico'];
      $array[$i]['fecha_alta'] = $unquery['fecha_alta'];
      $array[$i]['es_premium'] = $unquery['es_premium'];
      $array[$i]['fecha_fin_premium'] = $unquery['fecha_fin_premium'];
      $array[$i]['baneado'] = $unquery['baneado'];
      $array[$i]['es_admin'] = $unquery['es_admin'];
      $array[$i]['lang'] = $unquery['lang'];
      $array[$i]['envio_emails'] = $unquery['envio_emails'];
      $array[$i]['envio_boletines'] = $unquery['envio_boletines'];
      $array[$i]['id_tmz'] = $unquery['id_tmz'];
    }
    return $array;
  }

  // ***************************************
  //   Sacar datos de un jugador
  // ***************************************

  function SacarDatos($link_r, $id)
  {
    $string = "SELECT id, login, nombre, email,
		email_publico, texto_presentacion,
		avatar, fecha_alta, es_premium,
		fecha_fin_premium, baneado, es_admin, lang,
		envio_emails, envio_boletines, id_tmz,
		DATEDIFF(fecha_fin_premium, NOW()) AS diferencia, tutorial
		FROM jugador
		WHERE id = $id
		";
//echo $string;
    $query = mysql_query($string, $link_r);
    if ($unquery = mysql_fetch_array($query))
    {
      $this->id = $unquery['id'];
      $this->lang = $unquery['lang'];
      $this->login = $unquery['login'];
      $this->nombre = $unquery['nombre'];
      $this->email = $unquery['email'];
      $this->email_publico = $unquery['email_publico'];
      $this->texto_presentacion = $unquery['texto_presentacion'];
      $this->avatar = $unquery['avatar'];
      $this->fecha_alta = $unquery['fecha_alta'];
      $this->es_premium = $unquery['es_premium'];
      $this->fecha_fin_premium = $unquery['fecha_fin_premium'];
      $this->baneado = $unquery['baneado'];
      $this->es_admin = $unquery['es_admin'];
      $this->envio_emails = $unquery['envio_emails'];
      $this->envio_boletines = $unquery['envio_boletines'];
      $this->diferencia = $unquery['diferencia'];
      $this->id_tmz = $unquery['id_tmz'];
      $this->tutorial = $unquery['tutorial'];
    }
  }


  // ***************************************
  //   Sacar datos de un jugador
  // ***************************************

  function SacarDatosDesdeLogin($link_r, $login)
  {
    $string = "SELECT id, login, nombre, email,
		email_publico, texto_presentacion,
		avatar, fecha_alta, es_premium,
		fecha_fin_premium, baneado, es_admin, lang,
		envio_emails, envio_boletines, id_tmz, tutorial
		FROM jugador
		WHERE login = '$login'
		";
    $query = mysql_query($string, $link_r);
    if ($unquery = mysql_fetch_array($query))
    {
      $this->id = $unquery['id'];
      $this->lang = $unquery['lang'];
      $this->login = $unquery['login'];
      $this->nombre = $unquery['nombre'];
      $this->email = $unquery['email'];
      $this->email_publico = $unquery['email_publico'];
      $this->texto_presentacion = $unquery['texto_presentacion'];
      $this->avatar = $unquery['avatar'];
      $this->fecha_alta = $unquery['fecha_alta'];
      $this->es_premium = $unquery['es_premium'];
      $this->fecha_fin_premium = $unquery['fecha_fin_premium'];
      $this->baneado = $unquery['baneado'];
      $this->es_admin = $unquery['es_admin'];
      $this->envio_emails = $unquery['envio_emails'];
      $this->envio_boletines = $unquery['envio_boletines'];
      $this->id_tmz = $unquery['id_tmz'];
      $this->tutorial = $unquery['tutorial'];
      return true;
    } else {
      return false;
    }
  }



  // ***************************************
  //   Sacar datos de un jugador
  // ***************************************

  function SacarDatosDesdeEmail($link_r, $email)
  {
    $string = "SELECT id, login, nombre, email,
		email_publico, texto_presentacion,
		avatar, fecha_alta, es_premium,
		envio_emails, envio_boletines,
		fecha_fin_premium, baneado, es_admin, lang,
		id_tmz, tutorial
		FROM jugador
		WHERE email = '$email'
		";
    $query = mysql_query($string, $link_r);
    if ($unquery = mysql_fetch_array($query))
    {
      $this->id = $unquery['id'];
      $this->lang = $unquery['lang'];
      $this->login = $unquery['login'];
      $this->nombre = $unquery['nombre'];
      $this->email = $unquery['email'];
      $this->email_publico = $unquery['email_publico'];
      $this->texto_presentacion = $unquery['texto_presentacion'];
      $this->avatar = $unquery['avatar'];
      $this->fecha_alta = $unquery['fecha_alta'];
      $this->es_premium = $unquery['es_premium'];
      $this->fecha_fin_premium = $unquery['fecha_fin_premium'];
      $this->baneado = $unquery['baneado'];
      $this->es_admin = $unquery['es_admin'];
      $this->envio_emails = $unquery['envio_emails'];
      $this->envio_boletines = $unquery['envio_boletines'];
      $this->id_tmz = $unquery['id_tmz'];
      $this->tutorial = $unquery['tutorial'];
      return true;
    } else {
      return false;
    }
  }


}

?>
