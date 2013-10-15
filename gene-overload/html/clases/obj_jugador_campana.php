<?php

  // *******************************************************
  //
  //   Clase : Jugador_campana
  //
  //   Objetivo : Gestiona la tabla Jugador_campana, es decir, los datos
  //  propios de un jugador en una campanya en particular. En esta tabla
  //  a pesar de haber un "id" especifico, son ambos $idjugador y $idcampana
  //  los que funcionan de manera logica como id de la tabla. Asi, solo 
  //  hay una de estas entradas para cada jugador y campanya.
  //
  // *******************************************************

  class Jugador_campana
  {

    var $id;
    var $dinero;
    var $niveles_arbol;
    var $num_torneos;
    var $num_torneos_victorias;
    var $num_torneos_segundo;
    var $num_torneos_tercero;
    var $num_generaciones_total;
    var $num_generaciones_demes;
    var $num_generaciones_individual;
    var $num_slots_deme_profundidades;
    var $num_slots_deme_bosque;
    var $num_slots_deme_volcan;
    var $ratio_mutacion;
    var $ratio_intensidad_mutacion;
    var $num_sexos;
    var $idescenario;
    var $idjugador;
    var $idcampana;
    var $detalle_fecha;
    var $detalle_veces;

    var $mezcla_activa;
    var $superman1;
    var $superman2;
    var $ratio_mutacion_pendiente;

    var $segundos_con_bandera;

    var $debug_mode;



    // ***********************************************
    //   Graba los segundos que has tenido la bandera
    // ***********************************************

    function ActualizarSegundos($link_w, $idcampana, $idjugador, $segundos)
    {
      $string = "UPDATE jugador_campana
		SET segundos_con_bandera = $segundos
		WHERE idjugador = $idjugador
		AND idcampana = $idcampana
		";
      $query = mysql_query($string, $link_w);
    }


    // ***********************************************
    //   Calculo de la entropia de un deme
    // ***********************************************

    function Entropia($link_r, $idcampana, $idjugador, $iddeme)
    {
      $stringprev = "SELECT niveles_arbol
		FROM jugador_campana
		WHERE idjugador = $idjugador
		AND idcampana = $idcampana
		";
      $queryprev = mysql_query($stringprev, $link_r);
      if ($unqueryprev = mysql_fetch_array($queryprev))
      {
        $niveles_arbol = $unqueryprev['niveles_arbol'];
      }
//echo ("N:".$niveles_arbol);
      switch($niveles_arbol)
      {
        case 3: $operandos = 7; break;
        case 4: $operandos = 21; break;
        case 5: $operandos = 45; break;
        case 6: $operandos = 93; break;
      }

      $string = "SELECT id, arbol
		FROM especimen
		WHERE idpropietario = $idjugador
		AND idcampana = $idcampana
		AND iddeme = $iddeme
		";
      $query = mysql_query($string, $link_r);
      $i = 0;
      while ($unquery = mysql_fetch_array($query))
      {
        $i++;
        $array[$i]['arbol'] = $unquery['arbol'];
        $array[$i]['id'] = $unquery['id'];
      }
      // Vale, ahora tengo todos los del deme, pero tengo que compararlos con el resto.
      // Para cada especimen calculare su entropia respecto al resto. Cuanto mas homogeneo, mas entropia
      $igual = 0;
      $distinto = 0;
      for ($k = 1; $k <= count($array); $k++)
      {
        // Este for indica que vamos a compararlo con TODOS los demas
        for ($n = 1; $n <= count($array); $n++)
        {
          // Hay que comparar cada byte, que dependia del numero de niveles
          if ($k != $n)  // Pero nunca consigo mismo
          {
            $arbol1 = $array[$k]['arbol'];
            $arbol2 = $array[$n]['arbol'];
            for ($l = 1; $l <= $operandos; $l++)
            {
              if ($arbol1[$l] == $arbol2[$l])
              {
                $igual++;
              } else {
                $distinto++;
              }
            }
          }
        }
      }
//      echo ("Iguales : ".$igual."<br/>");
//      echo ("Distintos : ".$distinto."<br/>");
      $cantidad = floor(($igual / ($distinto + $igual)) * 10000);
      $cantidad = $cantidad / 100;
      return $cantidad;
//      return (($distinto / ($distinto + $igual)) * 100);
    }



    // ***********************************************
    //    Devuelve true si puede verlo, y almacena
    //  la nueva situacion
    // ***********************************************

    function Detalle_Comprobar_Permitir($link_w, $idcampana, $idjugador)
    {
      $string = "SELECT id, detalle_veces
		FROM jugador_campana
		WHERE
		idjugador = $idjugador
		AND idcampana = $idcampana
		AND detalle_veces < 5
		";
      $query = mysql_query($string, $link_w);
      if ($unquery = mysql_fetch_array($query))
      {
        // Si no ha llegado a 5 el contador, le sumamos 1 y devolvemos true
        $idjc = $unquery['id'];
        $detalle_veces = $unquery['detalle_veces'];
        $detalle_veces++;
        $string3 = "SELECT a.id
		FROM jugador_campana a
		WHERE a.id = $idjc
		AND a.detalle_fecha >= DATE_SUB(NOW(), INTERVAL 1 DAY)
                ";
//echo $string3."<br/>";
        $query3 = mysql_query($string3, $link_w);
        // Si tiene mas de un dia, lo ponemos a 1.
        if ($unquery3 = mysql_fetch_array($query3))
        {
          // Si no llega a un dia, ponemos el actual +1
          $string = "UPDATE jugador_campana
		SET detalle_veces = $detalle_veces
		WHERE id = $idjc
		";
//echo ("!");
        } else {
          $string = "UPDATE jugador_campana
		SET detalle_veces = 1,
		detalle_fecha = NOW()
		WHERE id = $idjc
		";
        }
//echo $string;
//echo ("#T");
        $query = mysql_query($string, $link_w);
        return true;
      } else {
        // Si ha llegado a 5, tenemos que comprobar la fecha.
        // Tambien si no habia nada asignado, lo ponemos.
        $string2 = "
		(SELECT id
		FROM jugador_campana
		WHERE DATE_SUB(NOW(), INTERVAL 1 DAY) > detalle_fecha
		AND idcampana = $idcampana
		AND idjugador = $idjugador
		AND detalle_veces >= 5
                )
		UNION
		(SELECT id
		FROM jugador_campana
		WHERE detalle_fecha IS NULL
		AND idcampana = $idcampana
		AND idjugador = $idjugador
                )
		";
        $query2 = mysql_query($string2, $link_w);
        if ($unquery2 = mysql_fetch_array($query2))
        {
//echo ("#1");
          // Vamos a poner a 1 las veces, y a grabar la fecha
          $idjc = $unquery2['id'];
          $stringgrabar = "UPDATE jugador_campana
		SET detalle_veces = 1,
		detalle_fecha = NOW()
		WHERE id = $idjc";
          $querygrabar = mysql_query($stringgrabar);
          return true;
        } else {
//echo ("#F");
          return false;
        }
      }
    }



    // ***********************************************
    //    Devuelve true si puede verlo, y almacena
    //  la nueva situacion
    // ***********************************************

    function Detalle_Comprobar_Permitir_Enfrentar($link_w, $idcampana, $idjugador)
    {
      $string = "SELECT id, detalle_veces_enfrentar
		FROM jugador_campana
		WHERE
		idjugador = $idjugador
		AND idcampana = $idcampana
		AND detalle_veces_enfrentar < 3
		";
      $query = mysql_query($string, $link_w);
      if ($unquery = mysql_fetch_array($query))
      {
        // Si no ha llegado a 2 el contador, le sumamos 1 y devolvemos true
        $idjc = $unquery['id'];
        $detalle_veces_enfrentar = $unquery['detalle_veces_enfrentar'];
        $detalle_veces_enfrentar++;
        $string3 = "SELECT a.id
		FROM jugador_campana a
		WHERE a.id = $idjc
		AND a.detalle_fecha_enfrentar >= DATE_SUB(NOW(), INTERVAL 1 DAY)
                ";
//echo $string3."<br/>";
        $query3 = mysql_query($string3, $link_w);
        // Si tiene mas de un dia, lo ponemos a 1.
        if ($unquery3 = mysql_fetch_array($query3))
        {
          // Si no llega a un dia, ponemos el actual +1
          $string = "UPDATE jugador_campana
		SET detalle_veces_enfrentar = $detalle_veces_enfrentar
		WHERE id = $idjc
		";
//echo ("!");
        } else {
          $string = "UPDATE jugador_campana
		SET detalle_veces_enfrentar = 1,
		detalle_fecha_enfrentar = NOW()
		WHERE id = $idjc
		";
        }
//echo $string;
//echo ("#T");
        $query = mysql_query($string, $link_w);
        return true;
      } else {
        // Si ha llegado a 3, tenemos que comprobar la fecha.
        // Tambien si no habia nada asignado, lo ponemos.
        $string2 = "
		(SELECT id
		FROM jugador_campana
		WHERE DATE_SUB(NOW(), INTERVAL 1 DAY) > detalle_fecha_enfrentar
		AND idcampana = $idcampana
		AND idjugador = $idjugador
		AND detalle_veces_enfrentar >= 3
                )
		UNION
		(SELECT id
		FROM jugador_campana
		WHERE detalle_fecha_enfrentar IS NULL
		AND idcampana = $idcampana
		AND idjugador = $idjugador
                )
		";
        $query2 = mysql_query($string2, $link_w);
        if ($unquery2 = mysql_fetch_array($query2))
        {
//echo ("#1");
          // Vamos a poner a 1 las veces, y a grabar la fecha
          $idjc = $unquery2['id'];
          $stringgrabar = "UPDATE jugador_campana
		SET detalle_veces_enfrentar = 1,
		detalle_fecha_enfrentar = NOW()
		WHERE id = $idjc";
          $querygrabar = mysql_query($stringgrabar);
          return true;
        } else {
//echo ("#F");
          return false;
        }
      }
    }




    // ***********************************************
    //    Devuelve sin modificar nada
    // ***********************************************

    function Detalle_Comprobar_Permitir_Enfrentar_SinTocar($link_w, $idcampana, $idjugador)
    {
      $string = "SELECT id, detalle_veces_enfrentar
		FROM jugador_campana
		WHERE
		idjugador = $idjugador
		AND idcampana = $idcampana
		AND detalle_veces_enfrentar < 3
		";
      $query = mysql_query($string, $link_w);
      if ($unquery = mysql_fetch_array($query))
      {
        return $unquery['detalle_veces_enfrentar'];
      } else {
        // Si ha llegado a 3, tenemos que comprobar la fecha.
        // Tambien si no habia nada asignado, lo ponemos.
        $string2 = "
		(SELECT id
		FROM jugador_campana
		WHERE DATE_SUB(NOW(), INTERVAL 1 DAY) > detalle_fecha_enfrentar
		AND idcampana = $idcampana
		AND idjugador = $idjugador
		AND detalle_veces_enfrentar >= 3
                )
		UNION
		(SELECT id
		FROM jugador_campana
		WHERE detalle_fecha_enfrentar IS NULL
		AND idcampana = $idcampana
		AND idjugador = $idjugador
                )
		";
        $query2 = mysql_query($string2, $link_w);
        if ($unquery2 = mysql_fetch_array($query2))
        {
          return 0;
        } else {
//echo ("#F");
          return -1;
        }
      }
    }







    // ***********************************************************************************************
    //   Sumar del que se hace cada 4 horas
    // ***********************************************************************************************

    function SumarTodosVariableHorario($link_w, $idcampana, $dineromin, $dineromax)
    {
      $string = "SELECT a.id, a.dinero, a.idjugador, b.last_login,
		TIMEDIFF(NOW(), b.last_login) AS diferencia
		FROM jugador_campana a, jugador b
		WHERE idcampana = $idcampana
		AND b.id = a.idjugador
		";
echo $string;
      $query = mysql_query($string, $link_w);
      while ($unquery = mysql_fetch_array($query))
      {
        // El dinero que esta en $dienro va a estar entre $dineromax y $dineromin.
        $diferencia = $unquery['diferencia'];
        // La diferencia tiene un aspecto como 05:40:11,  o hasta 838:40:11
echo ("Diferencia : ".$unquery['diferencia']);
//die;

        // Vamos a calcular dinero
        if ( (strlen($diferencia) > 8) || ($diferencia == null))
        {
           $dinero = $dineromin;
//          $calidad = 0;
        } else {
          $horas = substr($diferencia, 0, 2);
          if ($horas <= 24)
          {
            $dinero = $dineromax;
//            $calidad = 3;
          } else {
            if ($horas <= 72)
            {
              $dinero = $dineromax - 1;
//              $calidad = 2;
            } else {
              $dinero = $dineromin;
            }
          }
        }

        $idjugador = $unquery['idjugador'];

        // Ahora hacemos el check en la tabla token
        $stringx = "SELECT id FROM token
		WHERE idjugador = $idjugador
		AND idcampana = $idcampana
		";
echo $stringx;
        $queryx = mysql_query($stringx, $link_w);
        if ($unqueryx = mysql_fetch_array($queryx))
        {
          // Si tiene el token, se multiplica x 3
          echo ("Este tipo tiene el token!!! : ".$dinero);
          $dinero = $dinero * 3;
        }
echo ("Dinero : ".$dinero."<br/>");
 


//        $informe = new Informe();
//        $informe->EnviarSubvencionUno($link_w, $idcampana, $dinero, $idjugador, $calidad);
//echo ("<br/>");
        $idjugadorcampana = $unquery['id'];
        $dinero_ant = $unquery['dinero'];
        $dinero_nuevo = $dinero_ant + $dinero;
echo ("Ant : ".$dinero_ant.", nue: ".$dinero_nuevo);
        $string2 = "UPDATE jugador_campana
		SET dinero = $dinero_nuevo
		WHERE id = $idjugadorcampana
		";
echo $string2;
        $query2 = mysql_query($string2, $link_w);
      }
    }

    // ***********************************************************************************************
    //   Suma en una campanya a todos los jugadores X creditos, 1 menos por dia sin login a minimo 2
    // ***********************************************************************************************

    function SumarTodosVariable($link_w, $idcampana, $dineromax, $dineromin)
    {
      $string = "SELECT a.id, a.dinero, a.idjugador, b.last_login,
		TIMEDIFF(NOW(), b.last_login) AS diferencia
		FROM jugador_campana a, jugador b
		WHERE idcampana = $idcampana
		AND b.id = a.idjugador
		";
echo $string;
      $query = mysql_query($string, $link_w);
      while ($unquery = mysql_fetch_array($query))
      {
        // El dinero que esta en $dienro va a estar entre $dineromax y $dineromin.
        $diferencia = $unquery['diferencia'];
        // La diferencia tiene un aspecto como 05:40:11,  o hasta 838:40:11
echo ("Diferencia : ".$unquery['diferencia']);
//die;

        // Vamos a calcular dinero
        if ( (strlen($diferencia) > 8) || ($diferencia == null))
        { $dinero = 4;
          $calidad = 0;
        } else {
          $horas = substr($diferencia, 0, 2);
          if ($horas <= 24)
          {
            $dinero = 10;
            $calidad = 3;
          } else {
            if ($horas <= 48)
            {
              $dinero = 8;
              $calidad = 2;
            } else {
              if ($horas <= 72)
              {
                $dinero = 6;
                $calidad = 1;
              } else {
                $dinero = 4;
                $calidad = 0;
              }
            }
          }
        }

echo ("Dinero : ".$dinero);


        $idjugador = $unquery['idjugador'];
        $informe = new Informe();
        $informe->EnviarSubvencionUno($link_w, $idcampana, $dinero, $idjugador, $calidad);

echo ("<br/>");
        $idjugadorcampana = $unquery['id'];
        $dinero_ant = $unquery['dinero'];
        $dinero_nuevo = $dinero_ant + $dinero;
echo ("Ant : ".$dinero_ant.", nue: ".$dinero_nuevo);
        $string2 = "UPDATE jugador_campana
		SET dinero = $dinero_nuevo
		WHERE id = $idjugadorcampana
		";
echo $string2;
        $query2 = mysql_query($string2, $link_w);
      }
    }

    // ***********************************************************************************************
    //   Suma en una campanya a todos los jugadores X creditos, 1 menos por dia sin login a minimo 2
    // ***********************************************************************************************

    function SumarTodos($link_w, $idcampana, $dinero)
    {
      $string = "SELECT id, dinero, idjugador
		FROM jugador_campana
		WHERE idcampana = $idcampana
		";
      $query = mysql_query($string, $link_w);
      while ($unquery = mysql_fetch_array($query))
      {
        $idjugador = $unquery['idjugador'];
//        $informe = new Informe();
//        $informe->EnviarSubvencionUno($link_w, $idcampana, $dinero, $idjugador);

echo ("<br/>");
        $idjugadorcampana = $unquery['id'];
        $dinero_ant = $unquery['dinero'];
echo ("Ant : ".$dinero_ant.", nue: ".$dinero_nuevo);
        $dinero_nuevo = $dinero_ant + $dinero;
        $string2 = "UPDATE jugador_campana
		SET dinero = $dinero_nuevo
		WHERE id = $idjugadorcampana
		";
echo $string2;
        $query2 = mysql_query($string2, $link_w);
      }
    }

    // **********************************************************
    //   Suma un nuevo torneo a la cuenta, medalla de oro
    // **********************************************************

    function SumarPosicion1($link_w, $idjugador, $idcampana)
    {
      $string = "SELECT num_torneos_victorias
		FROM jugador_campana
		WHERE idjugador = $idjugador
		AND idcampana = $idcampana
		";
      $query = mysql_query($string, $link_w);
      if ($unquery = mysql_fetch_array($query))
      {
        $n = $unquery['num_torneos_victorias'] + 1;
        $string2 = "UPDATE jugador_campana
		SET num_torneos_victorias = $n
		WHERE idjugador = $idjugador
		AND idcampana = $idcampana
		";
	$query2 = mysql_query($string2, $link_w);
      }
    }

    // **********************************************************
    //   Suma un nuevo torneo a la cuenta, medalla de plata
    // **********************************************************

    function SumarPosicion2($link_w, $idjugador, $idcampana)
    {
      $string = "SELECT num_torneos_segundo
		FROM jugador_campana
		WHERE idjugador = $idjugador
		AND idcampana = $idcampana
		";
      $query = mysql_query($string, $link_w);
      if ($unquery = mysql_fetch_array($query))
      {
        $n = $unquery['num_torneos_segundo'] + 1;
        $string2 = "UPDATE jugador_campana
		SET num_torneos_segundo = $n
		WHERE idjugador = $idjugador
		AND idcampana = $idcampana
		";
	$query2 = mysql_query($string2, $link_w);
      }
    }

    // **********************************************************
    //   Suma un nuevo torneo a la cuenta, medalle de bronce
    // **********************************************************

    function SumarPosicion3($link_w, $idjugador, $idcampana)
    {
      $string = "SELECT num_torneos_tercero
		FROM jugador_campana
		WHERE idjugador = $idjugador
		AND idcampana = $idcampana
		";
      $query = mysql_query($string, $link_w);
      if ($unquery = mysql_fetch_array($query))
      {
        $n = $unquery['num_torneos_tercero'] + 1;
        $string2 = "UPDATE jugador_campana
		SET num_torneos_tercero = $n
		WHERE idjugador = $idjugador
		AND idcampana = $idcampana
		";
	$query2 = mysql_query($string2, $link_w);
      }
    }

    // **********************************************************
    //   Suma un nuevo torneo a la cuenta
    // **********************************************************

    function SumarUnTorneo($link_w, $idjugador, $idcampana)
    {
      $string = "SELECT num_torneos
		FROM jugador_campana
		WHERE idjugador = $idjugador
		AND idcampana = $idcampana
		";
//echo ("<br/> ".$string." - ");
      $query = mysql_query($string, $link_w);
      while ($unquery = mysql_fetch_array($query))
      {
        $n = $unquery['num_torneos'] + 1;
        $string2 = "UPDATE jugador_campana
		SET num_torneos = $n
		WHERE idjugador = $idjugador
		AND idcampana = $idcampana
		";
//echo $string2;
	$query2 = mysql_query($string2, $link_w);
      }
    }



    // **********************************************************
    //   Borrar los buffs despues de una mutacion
    // **********************************************************

    function BorrarBuffs($link_w, $idjugador, $idcampana)
    {
      $string = "UPDATE jugador_campana
		SET superman1 = 0, superman2 = 0, mezcla_activa = 0
		WHERE idjugador = $idjugador
		AND idcampana = $idcampana
		";
      $query = mysql_query($string, $link_w);
    }

    // **********************************************************
    //   Grabar nuevos pendientes para mutar
    // **********************************************************

    function GrabarMutacion($link_w, $idjugador, $idcampana)
    {
      $string = "UPDATE jugador_campana
		SET ratio_mutacion = $this->ratio_mutacion
		WHERE idjugador = $idjugador
		AND idcampana = $idcampana
		";
      $query = mysql_query($string, $link_w);
    }

    // **********************************************************
    //   Grabar nuevos pendientes para mutar
    // **********************************************************

    function GrabarMutacionPendiente($link_w, $idjugador, $idcampana)
    {
      $string = "UPDATE jugador_campana
		SET ratio_mutacion_pendiente = $this->ratio_mutacion_pendiente
		WHERE idjugador = $idjugador
		AND idcampana = $idcampana
		";
      $query = mysql_query($string, $link_w);
    }

    // **********************************************************
    //   Grabar Superman1
    // **********************************************************

    function GrabarSuperman1($link_w, $idjugador, $idcampana)
    {
      $string = "UPDATE jugador_campana
		SET superman1 = $this->superman1
		WHERE idjugador = $idjugador
		AND idcampana = $idcampana
		";
      $query = mysql_query($string, $link_w);
    }

    // **********************************************************
    //   Grabar Superman2
    // **********************************************************

    function GrabarSuperman2($link_w, $idjugador, $idcampana)
    {
      $string = "UPDATE jugador_campana
		SET superman2 = $this->superman2
		WHERE idjugador = $idjugador
		AND idcampana = $idcampana
		";
      $query = mysql_query($string, $link_w);
    }

    // **********************************************************
    //   Grabar la mezcla de demes
    // **********************************************************

    function GrabarMezclaActiva($link_w, $idjugador, $idcampana)
    {
      $string = "UPDATE jugador_campana
		SET mezcla_activa = $this->mezcla_activa
		WHERE idjugador = $idjugador
		AND idcampana = $idcampana
		";
      $query = mysql_query($string, $link_w);
    }


    // **********************************************************
    //   Grabar la intensidad de la mutacion
    // **********************************************************

    function GrabarIntensidadMutacion($link_w, $idjugador, $idcampana)
    {
      $string = "UPDATE jugador_campana
		SET ratio_intensidad_mutacion = $this->ratio_intensidad_mutacion
		WHERE idjugador = $idjugador
		AND idcampana = $idcampana
		";
      $query = mysql_query($string, $link_w);
    }

    // **********************************************************
    //   Grabar la cantidad de sexos
    // **********************************************************

    function GrabarNumSexos($link_w, $idjugador, $idcampana)
    {
      $string = "UPDATE jugador_campana
		SET num_sexos = $this->num_sexos
		WHERE idjugador = $idjugador
		AND idcampana = $idcampana
		";
      $query = mysql_query($string, $link_w);
    }


    // **********************************************************
    //   Grabar las estadisticas de generaciones (globales)
    // **********************************************************

    function GrabarNGenTotal($link_w, $idjugador, $idcampana)
    {
      $string = "UPDATE jugador_campana
		SET num_generaciones_total = $this->num_generaciones_total
		WHERE idjugador = $idjugador
		AND idcampana = $idcampana
		";
      $query = mysql_query($string, $link_w);
    }

    // **********************************************************
    //   Grabar las estadisticas de generaciones (demes)
    // **********************************************************

    function GrabarNGenDemes($link_w, $idjugador, $idcampana)
    {
      $string = "UPDATE jugador_campana
		SET num_generaciones_demes = $this->num_generaciones_demes
		WHERE idjugador = $idjugador
		AND idcampana = $idcampana
		";
      $query = mysql_query($string, $link_w);
    }

    // **********************************************************
    //   Grabar las estadisticas de generaciones (individuales)
    // **********************************************************

    function GrabarNGenInd($link_w, $idjugador, $idcampana)
    {
      $string = "UPDATE jugador_campana
		SET num_generaciones_individual = $this->num_generaciones_individual
		WHERE idjugador = $idjugador
		AND idcampana = $idcampana
		";
      $query = mysql_query($string, $link_w);
    }

    // **********************************************************
    //   Grabar el estado de los niveles
    // **********************************************************

    function GrabarNiveles($link_w, $idjugador, $idcampana)
    {
      $string = "UPDATE jugador_campana
		SET niveles_arbol = $this->niveles_arbol
		WHERE idjugador = $idjugador
		AND idcampana = $idcampana
		";
      $query = mysql_query($string, $link_w);
    }



    // **********************************************************
    //   Grabar el estado de los slots
    // **********************************************************

    function GrabarSlots($link_w, $idjugador, $idcampana)
    {

      $totalslots = $this->num_slots_deme_profundidades + $this->num_slots_deme_volcan + $this->num_slots_deme_bosque;
      $string = "UPDATE jugador_campana
		SET num_slots_deme_profundidades = $this->num_slots_deme_profundidades,
		num_slots_deme_bosque = $this->num_slots_deme_bosque,
		num_slots_deme_volcan = $this->num_slots_deme_volcan,
		num_slots_total = $totalslots
		WHERE idjugador = $idjugador
		AND idcampana = $idcampana
		";
      $query = mysql_query($string, $link_w);
    }

    // **********************************************************
    //   Altera el modo de debug
    // **********************************************************

    function AlterarDebug($link_w, $idjugador, $idcampana, $alterado)
    {
      $string = "UPDATE jugador_campana
		SET debug_mode = $alterado
		WHERE idjugador = $idjugador
		AND idcampana = $idcampana
		";
      $query = mysql_query($string, $link_w);
    }


    // **********************************************************
    //   Suma $cantidad de oro
    // **********************************************************

    function SumarDinero($link_w, $idjugador, $idcampana, $cantidad)
    {
      $string = "SELECT id, dinero
		FROM jugador_campana
		WHERE idjugador = $idjugador
		AND idcampana = $idcampana
		";
//echo $string;
      $query = mysql_query($string, $link_w);
      if ($unquery = mysql_fetch_array($query))
      {
        $dinero_new = $unquery['dinero'] + $cantidad;
        $idcosa = $unquery['id'];
        $stringw = "UPDATE jugador_campana
		SET dinero = $dinero_new
		WHERE id = $idcosa
		";
//echo (" -- ".$stringw);
        $queryw = mysql_query($stringw, $link_w);
      }
    }

    // **********************************************************
    //   Resta $cantidad de oro
    // **********************************************************
    //   Devuelve -1 si no se puede

    function RestarDinero($link_w, $idjugador, $idcampana, $cantidad)
    {
      $string = "SELECT id, dinero
		FROM jugador_campana
		WHERE idjugador = $idjugador
		AND idcampana = $idcampana
		";
      $query = mysql_query($string, $link_w);
      if ($unquery = mysql_fetch_array($query))
      {
        $dinero_new = $unquery['dinero'] - $cantidad;
        if ($dinero_new < 0)
        {
          return -1;
        }
        $idcosa = $unquery['id'];
        $stringw = "UPDATE jugador_campana
		SET dinero = $dinero_new
		WHERE id = $idcosa
		";
        $queryw = mysql_query($stringw, $link_w);
      }
      return 0;
    }


    // **********************************************************
    //   Eliminar un jugador de una campanya
    // **********************************************************

    function Eliminar($link_w, $idjugador, $idcampana)
    {
      $string = "DELETE FROM jugador_campana
		WHERE idjugador = $idjugador
		AND idcampana = $idcampana
		";
      $query = mysql_query($string, $link_w);
    }

    // **********************************************************
    //   Insertar un jugador a una campana
    // **********************************************************

    function SacarDatos($link_r, $idjugador, $idcampana)
    {
      $string = "SELECT id, dinero, num_torneos, niveles_arbol, num_torneos_victorias,
		num_generaciones_total, num_generaciones_demes, num_generaciones_individual,
		num_slots_deme_profundidades, num_slots_deme_bosque, num_slots_deme_volcan,
		ratio_mutacion, ratio_intensidad_mutacion, ratio_mutacion_pendiente,
		num_sexos, idescenario, mezcla_activa, superman1, superman2, num_slots_total,
 		num_torneos_segundo, num_torneos_tercero, detalle_fecha, detalle_veces,
		detalle_fecha_enfrentar, detalle_veces_enfrentar,
		segundos_con_bandera,
		debug_mode
		FROM jugador_campana
		WHERE idjugador = $idjugador
		AND idcampana = $idcampana
		";
      $query = mysql_query($string, $link_r);
      if ($unquery = mysql_fetch_array($query))
      {
        $this->id = $unquery['id'];
//        $this->lang = $unquery['lang'];
        $this->dinero = $unquery['dinero'];
        $this->niveles_arbol = $unquery['niveles_arbol'];
        $this->num_torneos = $unquery['num_torneos'];
        $this->num_torneos_victorias = $unquery['num_torneos_victorias'];
        $this->num_torneos_segundo = $unquery['num_torneos_segundo'];
        $this->num_torneos_tercero = $unquery['num_torneos_tercero'];
        $this->num_generaciones_total = $unquery['num_generaciones_total'];
        $this->num_generaciones_demes = $unquery['num_generaciones_demes'];
        $this->num_generaciones_individual = $unquery['num_generaciones_individual'];
        $this->num_slots_deme_profundidades = $unquery['num_slots_deme_profundidades'];
        $this->num_slots_deme_bosque = $unquery['num_slots_deme_bosque'];
        $this->num_slots_deme_volcan = $unquery['num_slots_deme_volcan'];
        $this->num_slots_total = $unquery['num_slots_total'];
        $this->ratio_mutacion = $unquery['ratio_mutacion'];
        $this->ratio_mutacion_pendiente = $unquery['ratio_mutacion_pendiente'];
        $this->ratio_intensidad_mutacion = $unquery['ratio_intensidad_mutacion'];
        $this->num_sexos = $unquery['num_sexos'];
        $this->idescenario = $unquery['idescenario'];
        $this->mezcla_activa = $unquery['mezcla_activa'];
        $this->superman1 = $unquery['superman1'];
        $this->superman2 = $unquery['superman2'];
        $this->debug_mode = $unquery['debug_mode'];
        $this->detalle_fecha = $unquery['detalle_fecha'];
        $this->detalle_veces = $unquery['detalle_veces'];
        $this->detalle_fecha_enfrentar = $unquery['detalle_fecha_enfrentar'];
        $this->detalle_veces_enfrentar = $unquery['detalle_veces_enfrentar'];
        $this->segundos_con_bandera = $unquery['segundos_con_bandera'];
      } else {
        return -1;
      }
    }


    // **********************************************************
    //   Insertar un jugador a una campana
    // **********************************************************

    function InsertarElemento($link_w, $idjugador, $idcampana, $dinero, $prof, $bosque, $volcan, $niveles_arbol)
    {
      $totalslots = $prof+$bosque+$volcan;
      $string = "INSERT INTO jugador_campana
		(dinero, num_torneos, niveles_arbol, num_torneos_victorias,
		num_generaciones_total, num_generaciones_demes, num_generaciones_individual,
		num_slots_deme_profundidades, num_slots_deme_bosque, num_slots_deme_volcan,
		ratio_mutacion, ratio_intensidad_mutacion,
		num_sexos, idescenario,
		idjugador, idcampana, mezcla_activa, superman1, superman2, num_slots_total,
		num_torneos_segundo, num_torneos_tercero
		)
		VALUES
		($dinero, 0, $niveles_arbol, 0,
		0, 0, 0,
		$prof, $bosque, $volcan,
		30, 2,
		2, 0,
		$idjugador, $idcampana,
		0,0,0,
		$totalslots,
		0, 0
		)
		";
     $query = mysql_query($string, $link_w);
    }

    // **********************************************************
    //   Esta un jugador apuntado a una campanya?
    // **********************************************************

    function EstaCampanaJugador($link_r, $idjugador, $idcampana)
    {
      $string = "SELECT id
			FROM jugador_campana
			WHERE idjugador = $idjugador
			AND idcampana = $idcampana
			";
      $query = mysql_query($string, $link_r);
      return mysql_num_rows($query);
    }

    // **********************************************************
    //   Obtener los datos de un jugador para una campanya
    // **********************************************************

    function ObtenerCampanaJugador($link_r, $idjugador, $idcampana)
    {
      $string = "SELECT
			id, dinero, niveles_arbol,
			num_torneos,
			num_torneos_victorias,
			num_torneos_segundo,
			num_torneos_tercero,
			num_generaciones_total,
			num_generaciones_demes,
			num_generaciones_individual,
			num_slots_deme_profundidades,
			num_slots_deme_bosque,
			num_slots_deme_volcan,
			ratio_mutacion,
			ratio_intensidad_mutacion,
			num_sexos,
			idescenario,
			idjugador,
			idcampana,
			num_slots_total
			FROM jugador_campana
			WHERE idjugador = $idjugador
			AND idcampana = $idcampana
		";
      $query = mysql_query($string, $link_r);
      if ($unquery = mysql_fetch_array($query))
      {
        $this->id = $unquery['id'];
        $this->dinero = $unquery['dinero'];
        $this->niveles_arbol = $unquery['niveles_arbol'];
        $this->num_torneos = $unquery['num_torneos'];
        $this->num_torneos_victorias = $unquery['num_torneos_victorias'];
        $this->num_torneos_segundo = $unquery['num_torneos_segundo'];
        $this->num_torneos_tercero = $unquery['num_torneos_tercero'];
        $this->num_generaciones_total = $unquery['num_generaciones_total'];
        $this->num_generaciones_demes = $unquery['num_generaciones_demes'];
        $this->num_generaciones_individual = $unquery['num_generaciones_individual'];
        $this->num_slots_deme_profundidades = $unquery['num_slots_deme_profundidades'];
        $this->num_slots_deme_bosque = $unquery['num_slots_deme_bosque'];
        $this->num_slots_deme_volcan = $unquery['num_slots_deme_volcan'];
        $this->num_slots_total = $unquery['num_slots_total'];
        $this->ratio_mutacion = $unquery['ratio_mutacion'];
        $this->ratio_intensidad_mutacion = $unquery['ratio_intensidad_mutacion'];
        $this->num_sexos = $unquery['num_sexos'];
        $this->idescenario = $unquery['idescenario'];
        $this->idjugador = $unquery['idjugador'];
        $this->idcampana = $unquery['idcampana'];
      } else {
        return -1;
      }
    }


    // ···························································
    //             Funciones del sistema de ranking
    // ···························································

    function ContarElementosRank($link_r, $idcampana)
    {
      $string = "SELECT id
		FROM jugador_campana
		WHERE idcampana = $idcampana
		";
      $query = mysql_query($string, $link_r);
      return mysql_num_rows($query);
    }

    function BuscarElementosRank($link_r, $idcampana, $limit, $offset, $tipo)
    {
      $string = "SELECT id, dinero, num_torneos, num_torneos_victorias, num_torneos_segundo, num_torneos_tercero,
		num_generaciones_total, num_generaciones_demes, num_generaciones_individual,
		num_slots_deme_profundidades, num_slots_deme_bosque, num_slots_deme_volcan, num_slots_total,
		idjugador, niveles_arbol, segundos_con_bandera
		FROM jugador_campana
		WHERE idcampana = $idcampana
		";
      if ($tipo == 1)
      {
        $string = $string." ORDER BY num_slots_total DESC";
      }
      if ($tipo == 2)
      {
        $string = $string." ORDER BY num_torneos_victorias DESC, num_torneos_segundo DESC, num_torneos_tercero DESC";
      }
      if ($tipo == 3)
      {
        $string = $string." ORDER BY num_generaciones_total DESC";
      }
      if ($tipo == 4)
      {
        $string = $string." ORDER BY dinero DESC";
      }
      if ($tipo == 5)
      {
        $string = $string." ORDER BY segundos_con_bandera DESC";
      }
      $string = $string." LIMIT $limit OFFSET $offset";
//echo $string;
      $query = mysql_query($string, $link_r);
      $i = 0;
      while ($unquery = mysql_fetch_array($query))
      {
        $i++;
        $array[$i]['id'] = $unquery['id'];
        $array[$i]['dinero'] = $unquery['dinero'];
        $array[$i]['num_torneos'] = $unquery['num_torneos'];
        $array[$i]['num_torneos_victorias'] = $unquery['num_torneos_victorias'];
        $array[$i]['num_torneos_segundo'] = $unquery['num_torneos_segundo'];
        $array[$i]['num_torneos_tercero'] = $unquery['num_torneos_tercero'];
        $array[$i]['num_generaciones_total'] = $unquery['num_generaciones_total'];
        $array[$i]['num_generaciones_demes'] = $unquery['num_generaciones_demes'];
        $array[$i]['num_generaciones_individual'] = $unquery['num_generaciones_individual'];
        $array[$i]['num_slots_deme_profundidades'] = $unquery['num_slots_deme_profundidades'];
        $array[$i]['num_slots_deme_bosque'] = $unquery['num_slots_deme_bosque'];
        $array[$i]['num_slots_deme_volcan'] = $unquery['num_slots_deme_volcan'];
        $array[$i]['num_slots_total'] = $unquery['num_slots_total'];
        $array[$i]['idjugador'] = $unquery['idjugador'];
        $array[$i]['niveles_arbol'] = $unquery['niveles_arbol'];
        $array[$i]['segundos_con_bandera'] = $unquery['segundos_con_bandera'];
      }
      return $array;
    }

    // Esta funcion devuelve en que pagina del ranking esta el user
    function BuscarPaginaRank($link_r, $idcampana, $limit, $idjugador, $tipo)
    {
/*
      $string = "SELECT id, dinero, num_torneos, num_torneos_victorias, num_torneos_segundo, num_torneos_tercero,
		num_generaciones_total, num_generaciones_demes, num_generaciones_individual,
		num_slots_deme_profundidades, num_slots_deme_bosque, num_slots_deme_volcan, num_slots_total,
		idjugador, niveles_arbol
		FROM jugador_campana
		WHERE idcampana = $idcampana
		";
*/
      $string = "SELECT id, idjugador
		FROM jugador_campana
		WHERE idcampana = $idcampana
		";
      if ($tipo == 1)
      {
        $string = $string." ORDER BY num_slots_total DESC";
      }
      if ($tipo == 2)
      {
        $string = $string." ORDER BY num_torneos_victorias DESC, num_torneos_segundo DESC, num_torneos_tercero DESC";
//        $string = $string." ORDER BY num_torneos_victorias DESC";
      }
      if ($tipo == 3)
      {
        $string = $string." ORDER BY num_generaciones_total DESC";
      }
      if ($tipo == 4)
      {
        $string = $string." ORDER BY dinero DESC";
      }
      if ($tipo == 5)
      {
        $string = $string." ORDER BY segundos_con_bandera DESC";
      }
//echo $string;
//      $string = $string." LIMIT $limit OFFSET $offset";
      $query = mysql_query($string, $link_r);
      $i = 0;
      $pgaux = 1;
//echo ("while");
      while ($unquery = mysql_fetch_array($query))
      {
//echo ($i.",");
        $i++;
        if ($i > $limit)
        {
//echo ("[pgaux++]|");
          $pgaux++;
          $i = 0;
        }
        if ($unquery['idjugador'] == $idjugador)
        {
//echo ("<br/>Jugador en la pos ".$i.", pag ".$pgaux);
          $pg = $pgaux;
        }
      }
//echo $pg."#";
      if ($pg == null) { $pg = 1; }
      return $pg;
    }







  } // Fin de la clase

?>
