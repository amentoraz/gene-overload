<?php

  class Especimen
  {

    var $id;
    var $niveles_arbol;

    var $iddeme;
    var $rapidez;
    var $inteligencia;
    var $fuerza;
    var $constitucion;
    var $percepcion;
    var $sabiduria;

    var $arbol;
    var $idpropietario;
    var $idcampana;
    var $idslot;
    var $puntos_evaluacion;

    var $oro;
    var $plata;
    var $bronce;

    var $edad;

    var $puntos_vida;
    var $puntos_magia;

	 var $silaba1;
	 var $silaba2;
	 var $silaba3;
	 var $silabacar;


    // ****************************************
    //   Le creamos el nombre a un especimen, venga
    //  del sitio que venga
    // ****************************************

    function CrearNombre()
    {
      global $array_silabas_profundidades;
      global $array_silabas_bosque;
      global $array_silabas_volcan;

    	// Tenemos que tener $this->iddeme y $this->rapidez, int, etc
    	$iddeme = $this->iddeme;

    	switch ($iddeme)
    	{
    		case 1: $array = $array_silabas_profundidades; break;
    		case 2: $array = $array_silabas_bosque; break;    		
    		case 3: $array = $array_silabas_volcan; break;
    		default : $array = $array_silabas_volcan; break;
    	}

    	// Primero vamos a seleccionar 3 silabas aleatorias
    	$longitud = count($array);
    	$silaba1 = rand(0,($longitud - 1));
    	$silaba2 = $silaba1;

    	while ($silaba1 == $silaba2)
    	{
    		$silaba2 = rand(0,($longitud - 1));
    	}

    	$silaba3 = $silaba2;
    	while (($silaba1 == $silaba3) || ($silaba2 == $silaba3))
    	{
    		$silaba3 = rand(0, ($longitud - 1));
    	}


      // Aqui ya lo trasladamos
      $silaba1 = $array[$silaba1];
      $silaba2 = $array[$silaba2];
      $silaba3 = $array[$silaba3];

    	// Vale, ahora los tenemos todos, asi que...
    	// Vamos a grabarlos en silaba1, silaba2, silaba3
    	// ESTO PARA LUEGO
    	// Y ahora vamos a generar a partir de sus caracteristicas
    	$silaba_car = '';
    if ($this->iddeme == 1)
    {
    	// RAPIDEZ
      if(($this->rapidez) <= 4){	$silaba_car = $silaba_car."g"; }
      if((($this->rapidez) <= 7) && ($this->rapidez > 4)) {	$silaba_car = $silaba_car."z"; }
      if(($this->rapidez) > 7) {	$silaba_car = $silaba_car."k"; }            
    	// FUERZA
    	if(($this->fuerza) <= 4) { $silaba_car = $silaba_car."i"; }
      if((($this->fuerza) <= 7) && ($this->fuerza > 4)) { $silaba_car = $silaba_car."e"; }
      if(($this->fuerza) > 7) { $silaba_car = $silaba_car."u"; }       
    	// INTELIGENCIA
      if(($this->inteligencia) <= 4) {	$silaba_car = $silaba_car."t"; }
      if((($this->inteligencia) <= 7) && ($this->inteligencia > 4)) { $silaba_car = $silaba_car."k"; }
      if(($this->inteligencia) > 7) {	$silaba_car = $silaba_car."d"; }          
    	// CONSTITUCION
      if(($this->constitucion) <= 4) { $silaba_car = $silaba_car."g"; }
      if((($this->constitucion) <= 7) && ($this->constitucion > 4)) { $silaba_car = $silaba_car."n"; }      
      if(($this->constitucion) > 7) {	$silaba_car = $silaba_car."k"; }           
    	// PERCEPCION
      if(($this->percepcion) <= 4) { $silaba_car = $silaba_car."o"; }
      if((($this->percepcion) <= 7) && ($this->percepcion > 4)) {	$silaba_car = $silaba_car."u"; }
      if(($this->percepcion) > 7) { $silaba_car = $silaba_car."y"; }     
    	// SABIDURIA
      if(($this->sabiduria) <= 4) {	$silaba_car = $silaba_car."j"; }
      if((($this->sabiduria) <= 7) && ($this->sabiduria > 4)) { $silaba_car = $silaba_car."h"; }
      if(($this->sabiduria) > 7) {	$silaba_car = $silaba_car."k"; }                  	       	
    }      
    if ($this->iddeme == 2)
    {
    	// RAPIDEZ
      if(($this->rapidez) <= 4){	$silaba_car = $silaba_car."t"; }
      if((($this->rapidez) <= 7) && ($this->rapidez > 4)) {	$silaba_car = $silaba_car."f"; }
      if(($this->rapidez) > 7) {	$silaba_car = $silaba_car."l"; }            
    	// FUERZA
    	if(($this->fuerza) <= 4) { $silaba_car = $silaba_car."a"; }
      if((($this->fuerza) <= 7) && ($this->fuerza > 4)) { $silaba_car = $silaba_car."e"; }
      if(($this->fuerza) > 7) { $silaba_car = $silaba_car."i"; }       
    	// INTELIGENCIA
      if(($this->inteligencia) <= 4) {	$silaba_car = $silaba_car."r"; }
      if((($this->inteligencia) <= 7) && ($this->inteligencia > 4)) { $silaba_car = $silaba_car."n"; }
      if(($this->inteligencia) > 7) {	$silaba_car = $silaba_car."s"; }          
    	// CONSTITUCION
      if(($this->constitucion) <= 4) { $silaba_car = $silaba_car."n"; }
      if((($this->constitucion) <= 7) && ($this->constitucion > 4)) { $silaba_car = $silaba_car."l"; }      
      if(($this->constitucion) > 7) {	$silaba_car = $silaba_car."f"; }           
    	// PERCEPCION
      if(($this->percepcion) <= 4) { $silaba_car = $silaba_car."e"; }
      if((($this->percepcion) <= 7) && ($this->percepcion > 4)) {	$silaba_car = $silaba_car."i"; }
      if(($this->percepcion) > 7) { $silaba_car = $silaba_car."a"; }     
    	// SABIDURIA
      if(($this->sabiduria) <= 4) {	$silaba_car = $silaba_car."l"; }
      if((($this->sabiduria) <= 7) && ($this->sabiduria > 4)) { $silaba_car = $silaba_car."r"; }
      if(($this->sabiduria) > 7) {	$silaba_car = $silaba_car."n"; }                  	       	
    }      
    if ($this->iddeme == 3)
    {
    	// RAPIDEZ
      if(($this->rapidez) <= 4){	$silaba_car = $silaba_car."s"; }
      if((($this->rapidez) <= 7) && ($this->rapidez > 4)) {	$silaba_car = $silaba_car."t"; }
      if(($this->rapidez) > 7) {	$silaba_car = $silaba_car."r"; }            
    	// FUERZA
    	if(($this->fuerza) <= 4) { $silaba_car = $silaba_car."a"; }
      if((($this->fuerza) <= 7) && ($this->fuerza > 4)) { $silaba_car = $silaba_car."e"; }
      if(($this->fuerza) > 7) { $silaba_car = $silaba_car."o"; }       
    	// INTELIGENCIA
      if(($this->inteligencia) <= 4) {	$silaba_car = $silaba_car."t"; }
      if((($this->inteligencia) <= 7) && ($this->inteligencia > 4)) { $silaba_car = $silaba_car."f"; }
      if(($this->inteligencia) > 7) {	$silaba_car = $silaba_car."m"; }          
    	// CONSTITUCION
      if(($this->constitucion) <= 4) { $silaba_car = $silaba_car."r"; }
      if((($this->constitucion) <= 7) && ($this->constitucion > 4)) { $silaba_car = $silaba_car."s"; }      
      if(($this->constitucion) > 7) {	$silaba_car = $silaba_car."p"; }           
    	// PERCEPCION
      if(($this->percepcion) <= 4) { $silaba_car = $silaba_car."o"; }
      if((($this->percepcion) <= 7) && ($this->percepcion > 4)) {	$silaba_car = $silaba_car."e"; }
      if(($this->percepcion) > 7) { $silaba_car = $silaba_car."a"; }     
    	// SABIDURIA
      if(($this->sabiduria) <= 4) {	$silaba_car = $silaba_car."r"; }
      if((($this->sabiduria) <= 7) && ($this->sabiduria > 4)) { $silaba_car = $silaba_car."m"; }
      if(($this->sabiduria) > 7) {	$silaba_car = $silaba_car."n"; }                  	       	
    }              
      
      $this->silaba1 = $silaba1;
      $this->silaba2 = $silaba2;
      $this->silaba3 = $silaba3;
      $this->silabacar = $silaba_car;
    } 


    // ****************************************
    //   Resta 2d10 anyos a los especimenes
    // ****************************************

    function Pocion_Rejuvejece($link_w, $idjugador, $idcampana)
    {
      $string = "SELECT id, edad
		FROM especimen
		WHERE idpropietario = $idjugador
		AND idcampana = $idcampana
		";
      $query = mysql_query($string, $link_w);
      while ($unquery = mysql_fetch_array($query))
      {
        $edad = $unquery['edad'];
        $edad = $edad - rand(1,10) - rand(1,10);
        if ($edad < 0) { $edad = 0; }
        $this->Envejece($link_w, $unquery['id'], $edad);
      }
    }

    // ****************************************
    //   Anyade $n anyos a un especimen
    // ****************************************

    function Envejece($link_w, $idespecimen, $edad)
    {
      $string = "UPDATE especimen
		SET edad = $edad
		WHERE id = $idespecimen
		";
      $query = mysql_query($string, $link_w);
    }


    // ****************************************
    //   Suma una medalla de oro a un especimen
    // ****************************************

    function SumarOro($link_w, $idespecimen)
    {
      $string = "SELECT oro FROM especimen WHERE id = $idespecimen";
      $query = mysql_query($string, $link_w);
      if ($unquery = mysql_fetch_array($query))
      {
        $oro = $unquery['oro'];
        if ($oro == null) { $oro = 1; } else { $oro++; }
        $string2 = "UPDATE especimen
		SET oro = $oro
		WHERE id = $idespecimen
		";
        $query2 = mysql_query($string2, $link_w);
      }
    }


    // ****************************************
    //   Suma una medalla de plata a un especimen
    // ****************************************

    function SumarPlata($link_w, $idespecimen)
    {
      $string = "SELECT plata FROM especimen WHERE id = $idespecimen";
      $query = mysql_query($string, $link_w);
      if ($unquery = mysql_fetch_array($query))
      {
        $plate = $unquery['plata'];
        if ($plata == null) { $plata = 1; } else { $plata++; }
        $string2 = "UPDATE especimen
		SET plata = $plata
		WHERE id = $idespecimen
		";
        $query2 = mysql_query($string2, $link_w);
      }
    }


    // ****************************************
    //   Suma una medalla de bronce a un especimen
    // ****************************************

    function SumarBronce($link_w, $idespecimen)
    {
      $string = "SELECT bronce FROM especimen WHERE id = $idespecimen";
      $query = mysql_query($string, $link_w);
      if ($unquery = mysql_fetch_array($query))
      {
        $bronce = $unquery['bronce'];
        if ($bronce == null) { $bronce = 1; } else { $bronce++; }
        $string2 = "UPDATE especimen
		SET bronce = $bronce
		WHERE id = $idespecimen
		";
        $query2 = mysql_query($string2, $link_w);
      }
    }


    // *********************************************
    //     Comprobar que todos esten evaluados
    // ********************************************

    function ComprobarEvaluadosDeme($link_r, $idjugador, $idcampana, $iddeme)
    {
      $string = "SELECT id
		FROM especimen
		WHERE idpropietario = $idjugador
		AND idcampana = $idcampana
		AND puntos_evaluacion IS NULL
		AND iddeme = $iddeme
		";
//echo $string;
      $query = mysql_query($string, $link_r);
      if (mysql_num_rows($query) > 0)
      {
        return 0;
      } else {
        return 1; // Este es el resultado correcto, no hay nada sin evaluar
      }
    }


    // *********************************************
    //     Comprobar que todos esten evaluados
    // ********************************************

    function ComprobarEvaluados($link_r, $idjugador, $idcampana)
    {
      $string = "SELECT id
		FROM especimen
		WHERE idpropietario = $idjugador
		AND idcampana = $idcampana
		AND puntos_evaluacion IS NULL
		";
      $query = mysql_query($string, $link_r);
      if (mysql_num_rows($query) > 0)
      {
        return 0;
      } else {
        return 1; // Este es el resultado correcto, no hay nada sin evaluar
      }
    }



    // *********************************************
    //     Obtener por puntos evaluacion
    // ********************************************

    function ObtenerPuntosEvaluacionDeme($link_r, $idjugador, $idcampana, $iddeme)
    {
	$string = "SELECT id, puntos_evaluacion, iddeme
		FROM especimen
		WHERE idpropietario = $idjugador
		AND idcampana = $idcampana
		AND iddeme = $iddeme
		AND old = 1
		";
	$query = mysql_query($string, $link_r);
        $i = 0;
        while ($unquery = mysql_fetch_array($query))
        {
          $i++;
          $array[$i]['id'] = $unquery['id'];
          $array[$i]['iddeme'] = $unquery['iddeme'];
          $array[$i]['puntos_evaluacion'] = $unquery['puntos_evaluacion'];
        }
        return $array;
    }

    // *********************************************
    //     Obtener por puntos evaluacion
    // ********************************************

    function ObtenerPuntosEvaluacion($link_r, $idjugador, $idcampana)
    {
	$string = "SELECT id, puntos_evaluacion, iddeme
		FROM especimen
		WHERE idpropietario = $idjugador
		AND idcampana = $idcampana
		AND old = 1
		";
	$query = mysql_query($string, $link_r);
        $i = 0;
        while ($unquery = mysql_fetch_array($query))
        {
          $i++;
          $array[$i]['id'] = $unquery['id'];
          $array[$i]['iddeme'] = $unquery['iddeme'];
          $array[$i]['puntos_evaluacion'] = $unquery['puntos_evaluacion'];
        }
        return $array;
    }



    // *********************************************
    //     Obtener por puntos evaluacion (clan)
    // ********************************************

    function ObtenerPuntosEvaluacionDemeClan($link_r, $idjugador, $idcampana, $iddeme, $idclan)
    {
        // Primero sacamos el numero de niveles
        $string_n = "SELECT niveles_arbol
		FROM jugador_campana
		WHERE idjugador = $idjugador
		AND idcampana = $idcampana
		";
        $query_n = mysql_query($string_n, $link_r);
        if ($unquery = mysql_fetch_array($query_n))
        {
          $niveles_arbol = $unquery['niveles_arbol'];
        }
//echo ("NIVELES : ".$niveles_arbol);

        // Ahora obtenemos todos los especimenes de tu equipo con esos niveles
	$string = "SELECT a.id, a.puntos_evaluacion, a.iddeme
		FROM especimen a, jugador_campana b, clan_jugador c
		WHERE 1=1
		AND a.idcampana = $idcampana
		AND a.iddeme = $iddeme
		AND b.idjugador = a.idpropietario
		AND b.idcampana = a.idcampana
		AND c.idjugadorcampana = b.id
		AND c.idclan = $idclan
		AND c.aceptado = 1
		AND c.baneado = 0
		AND a.puntos_evaluacion IS NOT null
                AND a.niveles_arbol = $niveles_arbol
		";
//		AND a.idpropietario = $idjugador
//echo $string;
	$query = mysql_query($string, $link_r);
        $i = 0;
        while ($unquery = mysql_fetch_array($query))
        {
          $i++;
          $array[$i]['id'] = $unquery['id'];
          $array[$i]['iddeme'] = $unquery['iddeme'];
          $array[$i]['puntos_evaluacion'] = $unquery['puntos_evaluacion'];
        }
//echo ($i." en este deme##");
        return $array;
    }

    // *********************************************
    //     Obtener por puntos evaluacion
    // ********************************************

    function ObtenerPuntosEvaluacionClan($link_r, $idjugador, $idcampana, $idclan)
    {
        // Primero sacamos el numero de niveles
        $string_n = "SELECT niveles_arbol
		FROM jugador_campana
		WHERE idjugador = $idjugador
		AND idcampana = $idcampana
		";
        $query_n = mysql_query($string_n, $link_r);
        if ($unquery = mysql_fetch_array($query_n))
        {
          $niveles_arbol = $unquery['niveles_arbol'];
        }

        // Ahora obtenemos todos los especimenes de tu equipo con esos niveles
	$string = "SELECT a.id, a.puntos_evaluacion, a.iddeme
		FROM especimen a, jugador_campana b, clan_jugador c
		WHERE 1=1
		AND a.idcampana = $idcampana
		AND b.idjugador = a.idpropietario
		AND b.idcampana = a.idcampana
		AND c.idjugadorcampana = b.id
		AND c.idclan = $idclan
		AND a.puntos_evaluacion IS NOT null
		AND c.aceptado = 1
		AND c.baneado = 0
                AND a.niveles_arbol = $niveles_arbol
		";
	$query = mysql_query($string, $link_r);
        $i = 0;
        while ($unquery = mysql_fetch_array($query))
        {
          $i++;
          $array[$i]['id'] = $unquery['id'];
          $array[$i]['iddeme'] = $unquery['iddeme'];
          $array[$i]['puntos_evaluacion'] = $unquery['puntos_evaluacion'];
        }
        return $array;
    }


    // ********************************************
    //     Hace que un individuo se vuelva "viejo"
    // ********************************************

    function HacerGeneracionOldIndividuo($link_w, $idjugador, $idcampana, $iddeme, $idslot)
    {
	$string = "UPDATE especimen
		SET old = 1
		WHERE idpropietario = $idjugador
		AND idcampana = $idcampana
		AND iddeme = $iddeme
		AND idslot = $idslot
		";
	$query = mysql_query($string, $link_w);
    }

    // ********************************************
    //     Borra un individuo "viejo"
    // ********************************************

    function BorrarGeneracionOldIndividuo($link_w, $idjugador, $idcampana, $iddeme, $idslot)
    {
	$string = "DELETE FROM especimen
		WHERE idpropietario = $idjugador
		AND idcampana = $idcampana
		AND old = 1
		AND iddeme = $iddeme
		AND idslot = $idslot
		";
	$query = mysql_query($string, $link_w);
    }


    // ********************************************
    //     Hace que una generacion se vuelva "vieja"
    // ********************************************

    function HacerGeneracionOldDeme($link_w, $idjugador, $idcampana, $iddeme)
    {
	$string = "UPDATE especimen
		SET old = 1
		WHERE idpropietario = $idjugador
		AND idcampana = $idcampana
		AND iddeme = $iddeme
		";
	$query = mysql_query($string, $link_w);
    }


    // ********************************************
    //     Hace que un deme se recupere como generacion (para la gen individual)
    // ********************************************

    function DesHacerGeneracionOldDeme($link_w, $idjugador, $idcampana, $iddeme)
    {
	$string = "UPDATE especimen
		SET old = 0
		WHERE idpropietario = $idjugador
		AND idcampana = $idcampana
		AND iddeme = $iddeme
		";
	$query = mysql_query($string, $link_w);
    }

    //     Hace que un deme se vuelva "viejo"
    // ********************************************

    function BorrarGeneracionOldDeme($link_w, $idjugador, $idcampana, $iddeme)
    {
	$string = "DELETE FROM especimen
		WHERE idpropietario = $idjugador
		AND idcampana = $idcampana
		AND old = 1
		AND iddeme = $iddeme
		";
	$query = mysql_query($string, $link_w);
    }

    //     Hace que una generacion se vuelva "vieja"
    // ********************************************

    function HacerGeneracionOld($link_w, $idjugador, $idcampana)
    {
	$string = "UPDATE especimen
		SET old = 1
		WHERE idpropietario = $idjugador
		AND idcampana = $idcampana
		";
	$query = mysql_query($string, $link_w);
    }

    // *********************************************
    //     Hace que una generacion se vuelva "vieja"
    // ********************************************

    function BorrarGeneracionOld($link_w, $idjugador, $idcampana)
    {
	$string = "DELETE FROM especimen
		WHERE idpropietario = $idjugador
		AND idcampana = $idcampana
		AND old = 1
		";
	$query = mysql_query($string, $link_w);
    }

    // *********************************************
    //     Guardar la puntuacion de un especimen
    // ********************************************

    function GuardarPuntuacion($link_w, $idespecimen, $puntos_media)
    {
      $string = "UPDATE especimen
		SET puntos_evaluacion = $puntos_media
		WHERE id = $idespecimen
		";
//echo $string;
      $query = mysql_query($string, $link_w);
    }

    // *********************************************
    //     Buscar el numeroN
    // ********************************************

    function Obtener_Por_Numero($link_r, $idcampana, $idjugador, $num_real)
    {
      $string = "SELECT id, rapidez, inteligencia, fuerza, constitucion, percepcion, sabiduria, arbol, niveles_arbol, iddeme,
		oro, plata, bronce, edad
		FROM especimen
		WHERE idpropietario = $idjugador
		AND idcampana = $idcampana
		LIMIT 1 OFFSET $num_real
		";
      $query = mysql_query($string, $link_r);
//      $i = 0;
      if ($unquery = mysql_fetch_array($query))
      {
//        $i++;
        $array['id'] = $unquery['id'];
        $array['iddeme'] = $unquery['iddeme'];
        $array['rapidez'] = $unquery['rapidez'];
        $array['inteligencia'] = $unquery['inteligencia'];
        $array['fuerza'] = $unquery['fuerza'];
        $array['constitucion'] = $unquery['constitucion'];
        $array['percepcion'] = $unquery['percepcion'];
        $array['sabiduria'] = $unquery['sabiduria'];
        $array['arbol'] = $unquery['arbol'];
        $array['niveles_arbol'] = $unquery['niveles_arbol'];
        $array['oro'] = $unquery['oro'];
        $array['plata'] = $unquery['plata'];
        $array['bronce'] = $unquery['bronce'];
        $array['edad'] = $unquery['edad'];
      }
      return $array;
    }

    // *********************************************
    //     Buscar el que tenga X id
    // ********************************************

    function Obtener_Por_Id($link_r, $idespecimen)
    {
      $string = "SELECT id, rapidez, inteligencia, fuerza, constitucion, percepcion, sabiduria, arbol, niveles_arbol, iddeme, edad
		FROM especimen
		WHERE id = $idespecimen
		";
      $query = mysql_query($string, $link_r);
      if ($unquery = mysql_fetch_array($query))
      {
        $array['id'] = $unquery['id'];
        $array['iddeme'] = $unquery['iddeme'];
        $array['rapidez'] = $unquery['rapidez'];
        $array['inteligencia'] = $unquery['inteligencia'];
        $array['fuerza'] = $unquery['fuerza'];
        $array['constitucion'] = $unquery['constitucion'];
        $array['percepcion'] = $unquery['percepcion'];
        $array['sabiduria'] = $unquery['sabiduria'];
        $array['arbol'] = $unquery['arbol'];
        $array['niveles_arbol'] = $unquery['niveles_arbol'];
        $array['edad'] = $unquery['edad'];
      }
      return $array;
    }


    // *********************************************
    //     Buscar el que tenga X id
    // ********************************************

    function Obtener_Por_Deme_Slot($link_r, $iddeme, $idslot, $idcampana, $idjugador)
    {
      $string = "SELECT id, rapidez, inteligencia, fuerza, constitucion, percepcion, sabiduria, arbol, niveles_arbol, iddeme,
		silaba1, silaba2, silaba3, silabacar
		FROM especimen
		WHERE iddeme = $iddeme
		AND idslot = $idslot
		AND idpropietario = $idjugador
		AND idcampana = $idcampana
		";
      $query = mysql_query($string, $link_r);
      if ($unquery = mysql_fetch_array($query))
      {
        $array['id'] = $unquery['id'];
        $array['iddeme'] = $unquery['iddeme'];
        $array['rapidez'] = $unquery['rapidez'];
        $array['inteligencia'] = $unquery['inteligencia'];
        $array['fuerza'] = $unquery['fuerza'];
        $array['constitucion'] = $unquery['constitucion'];
        $array['percepcion'] = $unquery['percepcion'];
        $array['sabiduria'] = $unquery['sabiduria'];
        $array['arbol'] = $unquery['arbol'];
        $array['niveles_arbol'] = $unquery['niveles_arbol'];
        $array['silaba1'] = $unquery['silaba1'];
        $array['silaba2'] = $unquery['silaba2'];
        $array['silaba3'] = $unquery['silaba3'];
        $array['silabacar'] = $unquery['silabacar'];
      }
      return $array;
    }

    // *********************************************
    //     Buscar las que tengan puntos_evaluacion vacio
    // ********************************************

    function BuscarSinEvaluar($link_r, $idcampana, $idjugador)
    {
      $string = "SELECT id, rapidez, inteligencia, fuerza, constitucion, percepcion, sabiduria, arbol, niveles_arbol, iddeme
		FROM especimen
		WHERE puntos_evaluacion IS null
		AND idpropietario = $idjugador
		AND idcampana = $idcampana
		";
      $query = mysql_query($string, $link_r);
      $i = 0;
      while ($unquery = mysql_fetch_array($query))
      {
        $i++;
        $array[$i]['id'] = $unquery['id'];
        $array[$i]['iddeme'] = $unquery['iddeme'];
        $array[$i]['rapidez'] = $unquery['rapidez'];
        $array[$i]['inteligencia'] = $unquery['inteligencia'];
        $array[$i]['fuerza'] = $unquery['fuerza'];
        $array[$i]['constitucion'] = $unquery['constitucion'];
        $array[$i]['percepcion'] = $unquery['percepcion'];
        $array[$i]['sabiduria'] = $unquery['sabiduria'];
        $array[$i]['arbol'] = $unquery['arbol'];
        $array[$i]['niveles_arbol'] = $unquery['niveles_arbol'];
      }
      return $array;
    }

    // *********************************************
    //     Buscar las que tengan puntos_evaluacion vacio
    // ********************************************

    function BuscarTodosEvaluar($link_r, $idcampana, $idjugador)
    {
      $string = "SELECT id, rapidez, inteligencia, fuerza, constitucion, percepcion, sabiduria, arbol, niveles_arbol, iddeme, edad
		FROM especimen
		WHERE
		idpropietario = $idjugador
		AND idcampana = $idcampana
		";
      $query = mysql_query($string, $link_r);
      $i = 0;
      while ($unquery = mysql_fetch_array($query))
      {
        $i++;
        $array[$i]['id'] = $unquery['id'];
        $array[$i]['iddeme'] = $unquery['iddeme'];
        $array[$i]['rapidez'] = $unquery['rapidez'];
        $array[$i]['inteligencia'] = $unquery['inteligencia'];
        $array[$i]['fuerza'] = $unquery['fuerza'];
        $array[$i]['constitucion'] = $unquery['constitucion'];
        $array[$i]['percepcion'] = $unquery['percepcion'];
        $array[$i]['sabiduria'] = $unquery['sabiduria'];
        $array[$i]['arbol'] = $unquery['arbol'];
        $array[$i]['niveles_arbol'] = $unquery['niveles_arbol'];
        $array[$i]['edad'] = $unquery['edad'];
      }
      return $array;
    }

    // *********************************************
    //     Obtiene los datos de un especimen (por id)
    // ********************************************

    function SacarDatosPorId($link_r, $idelemento)
    {
      $string = "SELECT id, niveles_arbol, iddeme, idslot,
		rapidez, inteligencia, fuerza,
		constitucion, percepcion, sabiduria,
		arbol, puntos_evaluacion, idpropietario, 
		silaba1, silaba2, silaba3, silabacar,
		oro, plata, bronce,
		edad
		FROM especimen
		WHERE id = $idelemento
		";
//echo $string;
      $query = mysql_query($string, $link_r);
      if ($unquery = mysql_fetch_array($query))
      {
        $this->id = $unquery['id'];
        $this->edad = $unquery['edad'];
        $this->idslot = $unquery['idslot'];
        $this->niveles_arbol = $unquery['niveles_arbol'];
        $this->iddeme = $unquery['iddeme'];
        $this->rapidez = $unquery['rapidez'];
        $this->inteligencia = $unquery['inteligencia'];
        $this->fuerza = $unquery['fuerza'];
        $this->constitucion = $unquery['constitucion'];
        $this->percepcion = $unquery['percepcion'];
        $this->sabiduria = $unquery['sabiduria'];
        $this->arbol = $unquery['arbol'];
        $this->puntos_evaluacion = $unquery['puntos_evaluacion'];
        $this->idpropietario = $unquery['idpropietario'];

        $this->oro = $unquery['oro'];
        $this->plata = $unquery['plata'];
        $this->bronce = $unquery['bronce'];
        
        $this->silaba1 = $unquery['silaba1'];
        $this->silaba2 = $unquery['silaba2'];
        $this->silaba3 = $unquery['silaba3'];
        $this->silabacar = $unquery['silabacar'];

        $constitucion_calc = $this->constitucion;
        $inteligencia_calc = $this->inteligencia;
        $sabiduria_calc = $this->sabiduria;
        
        if ($this->edad < 8)
        {
        	 $sabiduria_calc = $sabiduria_calc - 2;
        	 $constitucion_calc = $constitucion_calc - 1;
        }
        if (($this->edad > 30) && ($this->edad < 40))
        {
        	 $sabiduria_calc = $sabiduria_calc + 1;
        	 $constitucion_calc = $constitucion_calc - 1;
        }
        if ($this->edad >= 40)
        {
        	 $sabiduria_calc = $sabiduria_calc + 2;
        	 $constitucion_calc = $constitucion_calc - 2;
        	 $inteligencia_calc = $inteligencia_calc - 1;
        }        
        
        $this->puntos_vida = (10 + ($constitucion_calc * 5));
        //$this->puntos_vida = (10 + ($this->constitucion * 5));
        $this->puntos_magia = 5 + ($inteligencia_calc * 4) + ($sabiduria_calc);
        //$this->puntos_magia = 5 + ($this->inteligencia * 4) + ($this->sabiduria);
        return true;
      } else {
        return false;
      }
    }

    // *********************************************
    //     Obtiene los datos de un especimen
    // ********************************************

    function SacarDatos($link_r, $iddeme, $idslot, $idjugador, $idcampana)
    {
      $string = "SELECT id, niveles_arbol, iddeme,
		rapidez, inteligencia, fuerza,
		constitucion, percepcion, sabiduria,
		arbol, puntos_evaluacion,
		oro, plata, bronce,
		silaba1, silaba2, silaba3, silabacar,
		edad
		FROM especimen
		WHERE iddeme = $iddeme
		AND idslot = $idslot
		AND idpropietario = $idjugador
		AND idcampana = $idcampana
		";
      $query = mysql_query($string, $link_r);
      $unquery = mysql_fetch_array($query);
      $this->id = $unquery['id'];
      $this->niveles_arbol = $unquery['niveles_arbol'];
      $this->iddeme = $unquery['iddeme'];
      $this->rapidez = $unquery['rapidez'];
      $this->inteligencia = $unquery['inteligencia'];
      $this->fuerza = $unquery['fuerza'];
      $this->constitucion = $unquery['constitucion'];
      $this->percepcion = $unquery['percepcion'];
      $this->sabiduria = $unquery['sabiduria'];
      $this->arbol = $unquery['arbol'];
      $this->puntos_evaluacion = $unquery['puntos_evaluacion'];

      $this->oro = $unquery['oro'];
      $this->plata = $unquery['plata'];
      $this->bronce = $unquery['bronce'];
      $this->edad = $unquery['edad'];

      $this->puntos_vida = (10 + ($this->constitucion * 5));
      $this->puntos_magia = 5 + ($this->inteligencia * 4) + ($this->sabiduria);
      
      $this->silaba1 = $unquery['silaba1'];
      $this->silaba2 = $unquery['silaba2'];
      $this->silaba3 = $unquery['silaba3'];
      $this->silabacar = $unquery['silabacar'];

    }



    // *********************************************
    //     Introduce el arbol creado en un bicho
    // ********************************************

    function ActualizarArbol($link_w, $idarbol)
    {
      $string = "UPDATE especimen
		SET arbol = '".mysql_real_escape_string($this->arbol, $link_w)."'
		WHERE id = $idarbol
		";
      $query = mysql_query($string, $link_w);
    }

    // *********************************************
    //     Elimina los especimenes de un jugador en una campanya
    // *********************************************

    function JugadorEliminaCampana($link_w, $idjugador, $idcampana)
    {
      $string = "DELETE FROM especimen
		WHERE idcampana = $idcampana
		AND idpropietario = $idjugador
		";
      $query = mysql_query($string, $link_w);
    }

    // *********************************************
    //               Crea un especimen
    // *********************************************

    function CrearEspecimen($link_w, $total_puntos, $iddeme, $idjugador, $idcampana, $idslot, $niveles_arbol)
    {

      // Normalmente "$totalpuntos" es 36, pero es mejor que la funcion sea independiente de esto

      $rapidez = 1;
      $inteligencia = 1;
      $fuerza = 1;
      $constitucion = 1;
      $percepcion = 1;
      $sabiduria = 1;

      //  La base son 1 en cada caracteristica. Vamos a empezar por repartir
      // lo repartible

      $parcial_puntos = $total_puntos - 6;
      for ($i = 1; $i <= $parcial_puntos; $i++)
      {
        $subir = rand(1,6);
        switch($subir)
	{
          case 1:
                if ($rapidez < 10)
                {
                  $rapidez++;
                } else {
                  $i--;
                }
		break;
          case 2:
                if ($inteligencia < 10)
                {
                  $inteligencia++;
                } else {
                  $i--;
                }
		break;
          case 3:
                if ($fuerza < 10)
                {
                  $fuerza++;
                } else {
                  $i--;
                }
		break;
          case 4:
                if ($constitucion < 10)
                {
                  $constitucion++;
                } else {
                  $i--;
                }
		break;
          case 5:
                if ($percepcion < 10)
                {
                  $percepcion++;
                } else {
                  $i--;
                }
		break;
          case 6:
                if ($sabiduria < 10)
                {
                  $sabiduria++;
                } else {
                  $i--;
                }
		break;
	}
      }

      $this->iddeme = $iddeme;
      $this->sabiduria = $sabiduria;
      $this->fuerza = $fuerza;
      $this->constitucion = $constitucion;
      $this->percepcion = $percepcion;
      $this->rapidez = $rapidez;
      $this->inteligencia = $inteligencia;
      $this->CrearNombre();


      $string = "INSERT INTO
		especimen
		(iddeme, niveles_arbol,
		rapidez, inteligencia, fuerza, constitucion, percepcion, sabiduria,
		arbol, idpropietario, idcampana, idslot, oro, plata, bronce,
		silaba1, silaba2, silaba3, silabacar)
		VALUES
		($iddeme, $niveles_arbol,
		$rapidez, $inteligencia, $fuerza, $constitucion, $percepcion, $sabiduria,
		null, $idjugador, $idcampana, $idslot, 0, 0, 0,
		'$this->silaba1','$this->silaba2','$this->silaba3','$this->silabacar')
		";
      $query = mysql_query($string, $link_w);


    }


    // *********************************************
    //        Inserta un especimen
    // *********************************************

    function InsertarEspecimen($link_w, $iddeme, $idjugador, $idcampana, $idslot)
    {
      $string = "INSERT INTO
		especimen
		(iddeme, niveles_arbol,
                rapidez, inteligencia, fuerza, constitucion, percepcion, sabiduria,
		arbol, idpropietario, idcampana, idslot, old, oro, plata, bronce,
		silaba1, silaba2, silaba3, silabacar)
		VALUES
		($iddeme, $this->niveles_arbol,
                $this->rapidez, $this->inteligencia, $this->fuerza, $this->constitucion, $this->percepcion, $this->sabiduria,
		'".mysql_real_escape_string($this->arbol, $link_w)."', $idjugador, $idcampana, $idslot, 0, 0, 0, 0,
		'$this->silaba1', '$this->silaba2', '$this->silaba3', '$this->silabacar'
		)";
//echo ("<br/>");
//echo $string;
      $query = mysql_query($string, $link_w);
    }



  }


?>
