<?php

  class Evolucion
  {

	var $iddeme;
	var $idslot;
	var $mezcla;
	var $superman1;
	var $superman2;

	var $jugador_campana;
	var $especimen;

	var $nuevo_rapidez;
	var $nuevo_inteligencia;
	var $nuevo_fuerza;
	var $nuevo_constitucion;
	var $nuevo_sabiduria;
	var $nuevo_percepcion;
	var $nuevo_arbol;


        var $debug_mode;
        var $es_premium;
        var $informe_premium;
        var $lang;

	var $num_origenes;
	var $num_origenes_1;
	var $num_origenes_2;
	var $num_origenes_3;


  // ***********************************
  //    Constructor
  // ***********************************
  //  Inicializa una clase tipo Evolucion

  function __construct($debug_mode, $es_premium, $lang){

    $this->jugador_campana = new Jugador_Campana();
    $this->especimen = new Especimen();

    $this->debug_mode = $debug_mode;
    $this->es_premium = $es_premium;
    $this->lang = $lang;

  }


  // ***********************************
  //    Realiza la mutacion de un opcode de un nodo
  // ***********************************

  function MutarOpcode($idnodo)
  {
    $opcode = ord($this->nuevo_arbol[$idnodo*2]);
    $valor = ord($this->nuevo_arbol[($idnodo*2) + 1]);
    if ($this->debug_mode == 1)
    {
      echo ("<br/>[OP:".$opcode."|V:".$valor."]");
    }

    $variar_rand = rand(1,(2*2)) - 2;
    $opcode = $opcode + $variar_rand;
    if ($opcode < 1) { $opcode = 1; }
    if ($opcode > 20) { $opcode = 20; }

    // Ahora tenemos que idear un nuevo valor, dependiendo del opcode
    switch($opcode)
    {
      case 1:
      case 2:
	$valor = rand(1,60);
	break;


      case 5:
      case 6:
	$valor = rand(1,55);
	break;

      case 9:
      case 10:
	$valor = rand(1,60);
	break;

      case 3:
      case 4:
      case 7:
      case 8:
	$valor = rand(1,10);
	break;

      case 11:
      case 12:
	$valor = rand(1,6);
	break;

      case 13:
      case 14:
	$valor = rand(1,9);
	break;

      case 15:
      case 16:
      case 17:
      case 18:
	$valor = rand(1,8);
	break;

      case 19:
      case 20:
	$valor = rand(1,80);
	break;

    }



    if ($this->debug_mode == 1)
    {
      echo ("[N|OP:".$opcode."|V:".$valor."]");
    }

    // Grabamos el nuevo opcode
    $this->nuevo_arbol[$idnodo*2] = chr($opcode);
    $this->nuevo_arbol[($idnodo*2) + 1] = chr($valor) ;

  }


  // ***********************************
  //    Realiza la mutacion de un valor de un nodo
  // ***********************************

  function MutarNodo($idnodo)
  {
    // Tenemos el arbol ya en $this->nuevo_arbol
    $opcode = ord($this->nuevo_arbol[$idnodo*2]);
    $valor = ord($this->nuevo_arbol[($idnodo*2) + 1]);
    if ($this->debug_mode == 1)
    {
      echo ("<br/>[OP:".$opcode."|V:".$valor."]");
    }

    // Ahora, todo depende del opcode
    switch($opcode)
    {
      case 1:
      case 2:
      case 9:
      case 10:
	$cantidad_alterar = rand(1,(10*2)) - 10;
        $valor = $valor + $cantidad_alterar;
        if ($valor < 1)
        {
          $valor = 1;
        }
        if ($valor > 60)
        {
          $valor = 60;
        }
	break;

      case 5:
      case 6:
	$cantidad_alterar = rand(1,(10*2)) - 10;
        $valor = $valor + $cantidad_alterar;
        if ($valor < 1)
        {
          $valor = 1;
        }
        if ($valor > 55)
        {
          $valor = 55;
        }
	break;


      case 3:
      case 4:
      case 7:
      case 8:
	$cantidad_alterar = rand(1,(4*2)) - 4;
        $valor = $valor + $cantidad_alterar;
        if ($valor < 1)
        {
          $valor = 1;
        }
        if ($valor > 10)
        {
          $valor = 10;
        }
	break;

      case 11:
      case 12:
      case 13:
      case 14:
	$cantidad_alterar = rand(1,(4*2)) - 4;
        $valor = $valor + $cantidad_alterar;
        if ($valor < 1)
        {
          $valor = 1;
        }
        if ($valor > 9)
        {
          $valor = 9;
        }
	break;

      case 15:
      case 16:
      case 17:
      case 18:
	$cantidad_alterar = rand(1,(4*2) - 2);
        $valor = $valor + $cantidad_alterar;
        if ($valor < 1)
        {
          $valor = 1;
        }
        if ($valor > 8)
        {
          $valor = 8;
        }
	break;

      case 19:
      case 20:
	$cantidad_alterar = rand(1,(10*2)) - 10;
        $valor = $valor + $cantidad_alterar;
        if ($valor < 1)
        {
          $valor = 1;
        }
        if ($valor > 80)
        {
          $valor = 80;
        }
	break;


    }

    if ($this->debug_mode == 1)
    {
      echo (" -> ".$cantidad_alterar);
      echo ("[N|OP:".$opcode."|V:".$valor."]");
    }


    $this->nuevo_arbol[$idnodo*2] = chr($opcode);
    $this->nuevo_arbol[($idnodo*2) + 1] = chr($valor) ;

  }



  // ***********************************
  //    Realiza una mutacion de una hoja
  // ***********************************

  function MutarHoja($cual, $numnodos)
  {
      $retorna = 0;

      // Tenemos el arbol ya en $this->nuevo_arbol
      //  Si nuevo_arbol[0-1] tiene el primer opcode, nuevo_arbol[2-3] y [4-5] los siguientes con
      // tres niveles, entonces la hoja $cual=1 esta en [6], es decir, ($numnodos*2 + $cual - 1)
      $opcode = ord($this->nuevo_arbol[(($numnodos*2) + $cual - 1)]);
      if ($this->debug_mode == 1)
      {
        echo ("<br/>[OP:".$opcode."]");
      }
      // Cuanto lo variamos?
      $variar = rand(1,3);
      switch($variar)
      {
        case 1: $opcode--; if ($opcode < 1) { $opcode = 1; $retorna = -1; } break;
        case 2: $opcode++; if ($opcode > 9) { $opcode = 9; $retorna = -1; } break;
      }


//echo ("|hoja ".$cual.".".ord($this->nuevo_arbol[(($numnodos*2) + $cual - 1)])."#".$opcode."#");
//echo ("#<b>".$opcode."</b>#");

      $this->nuevo_arbol[(($numnodos*2) + $cual)] = chr($opcode);
      if ($this->debug_mode == 1)
      {
        echo ("->[OP:".$opcode."]");
      }

      return $retorna;

  }



  // ***********************************
  //    Realiza una mutacion
  // ***********************************

  function RealizarMutacion ()
  {
      $aleatorio_mutar = rand(1, 100);
      if ($aleatorio_mutar <= $this->jugador_campana->ratio_mutacion)
      {

        // Lo metemos en el informe
        if ($this->es_premium == 1)
        {
          if ($this->lang == 'en')
          {
            $this->informe_premium = $this->informe_premium." The new specimen also experienced random mutations in its creation. ";
          } else {
            $this->informe_premium = $this->informe_premium." El nuevo especimen tambi&eacute;n experiment&oacute; mutaciones aleatorias en su creaci&oacute;n. ";
          }
        }

        if ($this->debug_mode == 1)
        {
          echo ("<br/><b>Entramos en mutacion</b> (probabilidad ".$this->jugador_campana->ratio_mutacion." %, obtenido ".$aleatorio_mutar.": ");
        }

        // Ahora procedemos a la mutacion

        // ------------------------------
        //    NIVEL SUAVE DE MUTACION
        // ------------------------------
        if ($this->jugador_campana->ratio_intensidad_mutacion == 1)
        {
          if ($this->debug_mode == 1)
          {
            echo ("nivel suave)");
          }

          // ******** 1. MUTACION DE NODOS *******

          // Sabemos el numero de nodos a partir de los niveles
          for ($n=1; $n < $this->jugador_campana->niveles_arbol; $n++)
          {
            // En cada nivel hay 2^(n-1) nodos. Nivel 1 hay 1, nivel 2 hay 2, nivel 3 hay 4, nivel 4 hay 8, etc.
            $numnodos = $numnodos + pow(2, $n - 1);
          }
          if ($this->debug_mode == 1)
          {
            echo (" (".$numnodos." nodos)");
          }

          // Afectamos a ($niveles_arbol - 2) nodos
//          for ($j = 1; $j <= ($this->jugador_campana->niveles_arbol - 2); $j++)
          for ($j = 1; $j <= pow(2, ($this->jugador_campana->niveles_arbol - 2)); $j++)
          {
            $nodoafectar = rand(1, $numnodos);
            $nodoafectar--; // ARREGLO PORQUE SINO EMPEZAMOS POR [2] Y ACABAMOS MUTANDO UNA HOJA :P
            if ($this->debug_mode == 1)
            {
              echo ("<br/>Afectando al nodo ".$nodoafectar);
            }
            $this->MutarNodo($nodoafectar);
          }

        }

        // ------------------------------
        //    NIVEL MEDIO DE MUTACION
        // ------------------------------

        if ($this->jugador_campana->ratio_intensidad_mutacion == 2)
        {
          if ($this->debug_mode == 1)
          {
            echo ("nivel medio)");
          }

          // ******** 1. MUTACION DE VALORES DE NODOS *******

          // Sabemos el numero de nodos a partir de los niveles
          for ($n=1; $n < $this->jugador_campana->niveles_arbol; $n++)
          {
            // En cada nivel hay 2^(n-1) nodos. Nivel 1 hay 1, nivel 2 hay 2, nivel 3 hay 4, nivel 4 hay 8, etc.
            $numnodos = $numnodos + pow(2, $n - 1);
          }
          if ($this->debug_mode == 1)
          {
            echo (" (".$numnodos." nodos)");
          }

          // Afectamos a ($niveles_arbol - 2) * 2 nodos
          for ($j = 1; $j <= pow(2, ($this->jugador_campana->niveles_arbol - 1)); $j++)
//          for ($j = 1; $j <= (($this->jugador_campana->niveles_arbol - 2) * ($this->jugador_campana->niveles_arbol)); $j++)
          {
            $nodoafectar = rand(1, $numnodos);
            if ($this->debug_mode == 1)
            {
              echo ("<br/>Mutando valor: Afectando al nodo ".$nodoafectar);
            }
            $nodoafectar--; // ARREGLO PORQUE SINO EMPEZAMOS POR [2] Y ACABAMOS MUTANDO UNA HOJA :P
            $this->MutarNodo($nodoafectar);
          }

          // ******** 2. MUTACION DE OPCODES *******
          // ya conocemos numnodos :P
          // Afectamos a ($niveles_arbol - 2) nodos
//          for ($j = 1; $j <= ($this->jugador_campana->niveles_arbol - 2); $j++)
          for ($j = 1; $j <= pow(2, ($this->jugador_campana->niveles_arbol - 2)); $j++)
          {
            $nodoafectar = rand(1, $numnodos);
            if ($this->debug_mode == 1)
            {
              echo ("<br/>Mutando opcode: Afectando al nodo ".$nodoafectar);
            }
            $nodoafectar--; // ARREGLO PORQUE SINO EMPEZAMOS POR [2] Y ACABAMOS MUTANDO UNA HOJA :P
            $this->MutarOpcode($nodoafectar);
          }

	  // ******** 3. MUTACION DE CARACTERISTICAS ********
	  // Muta 1d4 el valor
          $cuantomutar = rand(1,6)+2;
          for ($c = 1; $c <= $cuantomutar; $c++)
          {
            $elige1 = rand(1,6);
            // Elegimos 2 caracteristicas distintas
            $elige2 = $elige1;
            while ($elige2 == $elige1)
            {
              $elige2 = rand(1,6);
            }
            // Sumamos a una, restamos a otra
            if (($elige1 < 10) && ($elige2 > 2))
            {
              if ($this->debug_mode == 1)
              {
                echo ("<br/>Mutando caracteristica: +1 a ".$elige1." y -1 a ".$elige2);
              }
                switch ($elige1)
		{
		  case 1: if ($this->nuevo_inteligencia < 10) { $this->nuevo_inteligencia++; } break;
		  case 2: if ($this->nuevo_fuerza < 10) { $this->nuevo_fuerza++; } break;
		  case 3: if ($this->nuevo_percepcion < 10) { $this->nuevo_percepcion++; } break;
		  case 4: if ($this->nuevo_constitucion < 10) { $this->nuevo_constitucion++; } break;
		  case 5: if ($this->nuevo_rapidez < 10) { $this->nuevo_rapidez++; } break;
		  case 6: if ($this->nuevo_sabiduria < 10) { $this->nuevo_sabiduria++; } break;
                }
                switch ($elige2)
		{
		  case 1: if ($this->nuevo_inteligencia > 2) { $this->nuevo_inteligencia--; } break;
		  case 2: if ($this->nuevo_fuerza > 2) { $this->nuevo_fuerza--; } break;
		  case 3: if ($this->nuevo_percepcion > 2) { $this->nuevo_percepcion--; } break;
		  case 4: if ($this->nuevo_constitucion > 2) { $this->nuevo_constitucion--; } break;
		  case 5: if ($this->nuevo_rapidez > 2) { $this->nuevo_rapidez--; } break;
		  case 6: if ($this->nuevo_sabiduria > 2) { $this->nuevo_sabiduria--; } break;
                }
            } else {
		$c--; // Si no se puede, no cuenta
            } // Cierra el if de los $elige

          } // Cierra el for de cuantomutar

	  // ******** 4. MUTACION DE HOJAS ********
	  // Muta +/-1 el valor en 1d2 rondas
          //$cuantomutar = rand(1,2);
          // Ahora para el cuantomutar, vemos los niveles que tiene. Para 3 es entre 1-2, para 4 entre 1-4, etc
          $cuantomutar = rand(1, pow(2, ($this->jugador_campana->niveles_arbol - 2)));
          if ($this->debug_mode == 1)
          {
            echo ("<br/> Mutando ".$c." de (".$numhojas." hojas)");
          }
          $numhojas = pow(2, ($this->jugador_campana->niveles_arbol - 1));
          for ($c = 1; $c <= $cuantomutar; $c++)
          {
            // Elegiremos una hoja
            // Sabemos el numero de hojas a partir de los niveles
            // En cada nivel hay 2^(n-1) nodos. Nivel 1 hay 1, nivel 2 hay 2, nivel 3 hay 4, nivel 4 hay 8, etc.
            $cual = rand(1, $numhojas);
            $valor = $this->MutarHoja($cual, $numnodos);
            if ($valor == -1) { $c--; } // si ha fallado repetimos
          }


        } // Cierra el if de intensidad de mutacion

        // ------------------------------
        //    NIVEL FUERTE DE MUTACION
        // ------------------------------

        if ($this->jugador_campana->ratio_intensidad_mutacion == 3)
        {
          if ($this->debug_mode == 1)
          {
	    echo ("nivel fuerte)");
          }

          // ******** 1. MUTACION DE VALORES DE NODOS *******

          // Sabemos el numero de nodos a partir de los niveles
          for ($n=1; $n < $this->jugador_campana->niveles_arbol; $n++)
          {
            // En cada nivel hay 2^(n-1) nodos. Nivel 1 hay 1, nivel 2 hay 2, nivel 3 hay 4, nivel 4 hay 8, etc.
            $numnodos = $numnodos + pow(2, $n - 1);
          }
          if ($this->debug_mode == 1)
          {
            echo (" (".$numnodos." nodos)");
          }

          // Afectamos a ($niveles_arbol - 2) * 2 nodos
//          for ($j = 1; $j <= (($this->jugador_campana->niveles_arbol - 2) * 2 * ($this->jugador_campana->niveles_arbol)); $j++)
          for ($j = 1; $j <= pow(2, ($this->jugador_campana->niveles_arbol)); $j++)
          {
            $nodoafectar = rand(1, $numnodos);
            $nodoafectar--; // ARREGLO PORQUE SINO EMPEZAMOS POR [2] Y ACABAMOS MUTANDO UNA HOJA :P
            if ($this->debug_mode == 1)
            {
              echo ("<br/>Mutando valor: Afectando al nodo ".$nodoafectar);
            }
            $this->MutarNodo($nodoafectar);
          }

          // ******** 2. MUTACION DE OPCODES *******
          // ya conocemos numnodos :P
          // Afectamos a ($niveles_arbol - 2) nodos
//          for ($j = 1; $j <= (($this->jugador_campana->niveles_arbol - 2) * 2); $j++)
          for ($j = 1; $j <= pow(2, ($this->jugador_campana->niveles_arbol - 1)); $j++)
          {
            $nodoafectar = rand(1, $numnodos);
            $nodoafectar--; // ARREGLO PORQUE SINO EMPEZAMOS POR [2] Y ACABAMOS MUTANDO UNA HOJA :P
            if ($this->debug_mode == 1)
            {
              echo ("<br/>Mutando opcode: Afectando al nodo ".$nodoafectar);
            }
            $this->MutarOpcode($nodoafectar);
          }

	  // ******** 3. MUTACION DE CARACTERISTICAS ********
	  // Muta 1d4 el valor
          $cuantomutar = rand(1,6) + rand(1,6) + 4;
          for ($c = 1; $c <= $cuantomutar; $c++)
          {
            $elige1 = rand(1,6);
            // Elegimos 2 caracteristicas distintas
            $elige2 = $elige1;
            while ($elige2 == $elige1)
            {
              $elige2 = rand(1,6);
            }
            // Sumamos a una, restamos a otra
            if (($elige1 < 10) && ($elige2 > 2))
            {
              if ($this->debug_mode == 1)
              {
                echo ("<br/>Mutando caracteristica: +1 a ".$elige1." y -1 a ".$elige2);
              }
                switch ($elige1)
		{
		  case 1: if ($this->nuevo_inteligencia < 10) { $this->nuevo_inteligencia++; } break;
		  case 2: if ($this->nuevo_fuerza < 10) { $this->nuevo_fuerza++; } break;
		  case 3: if ($this->nuevo_percepcion < 10) { $this->nuevo_percepcion++; } break;
		  case 4: if ($this->nuevo_constitucion < 10) { $this->nuevo_constitucion++; } break;
		  case 5: if ($this->nuevo_rapidez < 10) { $this->nuevo_rapidez++; } break;
		  case 6: if ($this->nuevo_sabiduria < 10) { $this->nuevo_sabiduria++; } break;
                }
                switch ($elige2)
		{
		  case 1: if ($this->nuevo_inteligencia > 2) { $this->nuevo_inteligencia--; } break;
		  case 2: if ($this->nuevo_fuerza > 2) { $this->nuevo_fuerza--; } break;
		  case 3: if ($this->nuevo_percepcion > 2) { $this->nuevo_percepcion--; } break;
		  case 4: if ($this->nuevo_constitucion > 2) { $this->nuevo_constitucion--; } break;
		  case 5: if ($this->nuevo_rapidez > 2) { $this->nuevo_rapidez--; } break;
		  case 6: if ($this->nuevo_sabiduria > 2) { $this->nuevo_sabiduria--; } break;
                }
            } else {
		$c--; // Si no se puede, no cuenta
            }

          }

	  // ******** 4. MUTACION DE HOJAS ********
	  // Muta +/-1 el valor en 1d4+1 rondas
//          $cuantomutar = rand(1,4) + 1;
          // Ahora para el cuantomutar, vemos los niveles que tiene. Para 3 es entre 1-4, para 4 entre 1-8, etc
          $cuantomutar = rand(1, pow(2, ($this->jugador_campana->niveles_arbol - 1)));
          if ($this->debug_mode == 1)
          {
            echo ("<br/> Mutando ".$c." de (".$numhojas." hojas)");
          }
          $numhojas = pow(2, ($this->jugador_campana->niveles_arbol - 1));
          for ($c = 1; $c <= $cuantomutar; $c++)
          {
            // Elegiremos una hoja
            // Sabemos el numero de hojas a partir de los niveles
            // En cada nivel hay 2^(n-1) nodos. Nivel 1 hay 1, nivel 2 hay 2, nivel 3 hay 4, nivel 4 hay 8, etc.
            $cual = rand(1, $numhojas);
            $valor = $this->MutarHoja($cual, $numnodos);
            if ($valor == -1) { $c--; } // si ha fallado repetimos
          }

          //


        } // cierra el IF de mutacion fuerte




      } else {

        // Lo metemos en el informe
        if ($this->es_premium == 1)
        {
          if ($this->lang == 'en')
          {
            $this->informe_premium = $this->informe_premium." The new specimen did not mutate in the process.";
          } else {
            $this->informe_premium = $this->informe_premium." El nuevo especimen no sufri&oacute; mutaciones en el proceso.";
          }
        }

        if ($this->debug_mode == 1)
        {
          echo ("<br/><b>No hay fase de mutacion</b> (probabilidad ".$this->jugador_campana->ratio_mutacion." %, obtenido ".$aleatorio_mutar.")");
        }

      }

  }






    // ******************************************
    //  CRUZAR ARBOLES: FUNCION RECURSIVA A PARTIR DE UN NODO
    // ******************************************

    function CruzarArboles($numnodo, $arbol_origen)
    {

      // Primero vamos a ver en que nivel esta numnodo
      $numnodo_aux = $numnodo;
      $total = 0;
      $j = 0;
      while ($numnodo_aux > 0)
      {
        // Le restamos los elementos que hay en el nivel que estamos considerando
        $numnodo_aux = $numnodo_aux - pow(2, $j);
        $total = $total + pow(2, $j);
        $j++;
      } // Nodo  resulta $j = 1, nodo 2, 3 resulta $j = 2, y 4,5,6,7 resultan $j=3,...

      // Total de nodos en el penultimo nivel considerado
      $totalpenultimo = $total - pow(2, ($j - 1)); // en el nivel donde esta el nodo actual

        // Si este es el ultimo nivel y estamos transfiriendo una hoja
      if ($this->especimen->niveles_arbol == $j)
      {
        if ($this->debug_mode == 1)
        {
          echo ("<br/>Cruzando hoja ".$numnodo.", de [OP:".ord($arbol_origen[$totalpenultimo*2 + ($numnodo - $totalpenultimo) - 1]));
          echo ("se graba en [OP:".ord($this->especimen->arbol[$totalpenultimo*2 + ($numnodo - $totalpenultimo) - 1]));
        }
//                $nuevo_arbol = $this->CruzarArboles($this->especimen->arbol, $especimen2->arbol, $numnodo);
        $this->especimen->arbol[($totalpenultimo*2 + ($numnodo - $totalpenultimo) - 1)] = $arbol_origen[($totalpenultimo*2 + ($numnodo - $totalpenultimo) - 1)];
        return;
      } else {
 //       $arbol1[$numnodo*2+1] = $arbol2[$numnodo*2+1];
        if ($this->debug_mode == 1)
        {
          echo ("<br/>Cruzando nodo ".$numnodo.", [OP:".ord($arbol_origen[($numnodo*2) - 2])."|V:".ord($arbol_origen[($numnodo*2) - 1]));
          echo ("-> [OP:".ord($this->especimen->arbol[($numnodo*2) - 2]).",V:".ord($this->especimen->arbol[($numnodo*2) - 1])."]");
        }
        $this->especimen->arbol[($numnodo*2) - 2] = $arbol_origen[($numnodo*2) - 2];
        $this->especimen->arbol[($numnodo*2) - 1] = $arbol_origen[($numnodo*2) - 1];
        // Si este no es el ultimo nivel, y estamos transfiriendo un nodo
        $this->CruzarArboles($numnodo*2, $arbol_origen);
        $this->CruzarArboles(($numnodo*2)+1, $arbol_origen);
        return;
      }
      // Ahora, en $j tenemos el numero de nivel en el que se encuentra nuestro nodo
//                Del acrual :   $this->especimen->niveles_arbol - 1
    }





    // *******************************************************
    //    Funcion auxiliar de parametros de entrada
    // *******************************************************

    function GrabarParametros($iddeme, $idslot, $mezcla, $superman1, $superman2)
    {
      $this->iddeme = $iddeme;
      $this->idslot = idslot;
//	   $this->idslot = $idslot;
      $this->mezcla = mezcla;
      $this->superman1 = superman1;
//      $this->superman1 = $superman1;
  $this->superman2 = superman2;
//      $this->superman2 = $superman2;

    }


    // *******************************************************
    //    Elegir ejemplar
    // *******************************************************

    function ElegirEjemplar($link_r, $idjugador, $idcampana, $iddeme, $mezcla_activa, $idclan)
    {

      if (($idclan != 0) && ($idclan != '') && ($idclan != null))
      {
        if ($mezcla_activa == 1)
        {
          $array = $this->especimen->ObtenerPuntosEvaluacionClan($link_r, $idjugador, $idcampana, $idclan);
          $this->num_origenes = count($array);
        } else {
          $array = $this->especimen->ObtenerPuntosEvaluacionDemeClan($link_r, $idjugador, $idcampana, $iddeme, $idclan);
          switch($iddeme)
          {
            case 1: $this->num_origenes_1 = count($array); break;
            case 2: $this->num_origenes_2 = count($array); break;
            case 3: $this->num_origenes_3 = count($array); break;
          }
        }
      } else {
        if ($mezcla_activa == 1)
        {
          $array = $this->especimen->ObtenerPuntosEvaluacion($link_r, $idjugador, $idcampana);
        } else {
          $array = $this->especimen->ObtenerPuntosEvaluacionDeme($link_r, $idjugador, $idcampana, $iddeme);
        }
      }

      //  Lo primero que vamos a hacer es, si alguno esta sin puntuar, ponerle -25.
      // Asi aunque a ellos no les dejemos evolucionar sin evaluar, nosotros podemos hacerlo con la vejez
      for ($j = 1; $j <= count($array); $j++)
      {
        if ($array[$j]['puntos_evaluacion'] == null) { $array[$j]['puntos_evaluacion'] = -25; }
      }


      // Primero vamos a normalizar las puntuaciones
      $menorpuntos = 9999999;
      for ($j = 1; $j <= count($array); $j++)
      {
        if ($array[$j]['puntos_evaluacion'] < $menorpuntos) { $menorpuntos = $array[$j]['puntos_evaluacion']; }
      }
      $menorpuntos--; // Para que todos tengan al menos 1 punto y sean elegibles
      $totalpuntos = 0;
      for ($j = 1; $j <= count($array); $j++)
      {
        $array[$j]['puntos_evaluacion'] = $array[$j]['puntos_evaluacion'] - $menorpuntos;
        if ($this->debug_mode == 1)
        {
          echo ("<br/>Ejemplar ".$j.": ".$array[$j]['puntos_evaluacion'].", id: ".$array[$j]['id'].", puntos: ".$array[$j]['puntos_evaluacion']);
        }
        $totalpuntos = $totalpuntos + $array[$j]['puntos_evaluacion'];
      }
      // Elegiremos con esto entre todos
      $eligiendo = rand(0, $totalpuntos);

      if ($this->debug_mode == 1)
      {
        echo ("<br/>Eligiendo: ".$eligiendo);
      }

      // Recorremos y vemos a cual le ha tocado
      for ($j = 1; $j <= count($array); $j++)
      {
        $eligiendo = $eligiendo - $array[$j]['puntos_evaluacion'];
        if ($eligiendo <= 0)
        {
	          $idelemento = $array[$j]['id'];

          // Ya esta hecho asi que devolvemos el id
          if ($this->debug_mode == 1)
          {
            echo ("<br/>Totalpuntos: ".$totalpuntos.", menor: ".$menorpuntos.", elegido: id ".$idelemento);
          }
          return $idelemento;
        }
      }

      return -1;

    }




   // _______________________________________________________________________________________________________________
   //
   //                                       FUNCIONES BASE DE EVOLUCION
   // _______________________________________________________________________________________________________________

    // *******************************************************
    //    Funcion de evolucion asexual
    // *******************************************************

    function EvolucionarEspecimen($link_r, $link_w, $idjugador, $idcampana, $iddeme, $idslot, $numsexos, $evolucion_total, $idclan)
    {

      global $array_silabas_profundidades;
      global $array_silabas_bosque;
      global $array_silabas_volcan;

      switch ($iddeme)
      {
              case 1: $array_s = $array_silabas_profundidades; break;
              case 2: $array_s = $array_silabas_bosque; break;
              case 3: $array_s = $array_silabas_volcan; break;
              default : $array_s = $array_silabas_volcan; break;
      }
      $longitud_s = count($array_s);

//echo $longitud_s;


      // Comenzamos el informe premium, si procede
//echo ("#".$this->es_premium);
      if ($this->es_premium == 1)
      {
//echo ("#".$this->lang);
        if ($this->lang == 'en')
        {
          $this->informe_premium = '- Slot number '.$idslot;
          switch ($iddeme)
          {
            case 1:
                    $this->informe_premium = $this->informe_premium." from the Abyssal Depths Deme has evolved ";
  		  break;
            case 2:
                    $this->informe_premium = $this->informe_premium." from the Forest Deme has evolved ";
  		  break;
            case 3:
                    $this->informe_premium = $this->informe_premium." from the Vulcano Deme has evolved ";
		  break;
          }
        } else {
          $this->informe_premium = '- Se ha evolucionado la posici&oacute;n '.$idslot;
          switch ($iddeme)
          {
            case 1:
                  $this->informe_premium = $this->informe_premium." del Deme de las Profundidades ";
                  break;
            case 2:
                  $this->informe_premium = $this->informe_premium." del Deme del Bosque ";
                   break;
            case 3:
                  $this->informe_premium = $this->informe_premium." del Deme del Volc&aacute;n ";
                  break;
          }
        } // Cierre if lenguaje
      }
//echo ("#>".$this->informe_premium);



      if ($this->debug_mode == 1)
      {
        echo ("<br/><br/><b>Evolucionando deme ".$iddeme.", slot ".$idslot."</b>");
      }

      $this->jugador_campana->SacarDatos($link_r, $idjugador, $idcampana);

      // Mezcla activa y los superman1 y superman2 solo funcionan al evolucionar TODO
      if ($evolucion_total == 1)
      {
        $superman1 = $this->jugador_campana->superman1;
        $superman2 = $this->jugador_campana->superman2;
        $mezcla_activa = $this->jugador_campana->mezcla_activa;
      } else {
        $superman1 = 0;
        $superman2 = 0;
        $mezcla_activa = 0;
      }
      $slots1 = $this->jugador_campana->num_slots_deme_profundidades;
      $slots2 = $this->jugador_campana->num_slots_deme_bosque;
      $slots3 = $this->jugador_campana->num_slots_deme_volcan;

//echo ("#SUPERMAN 1 ".$superman1."|SUPERMAN2:".$superman2);

      // Sabemos que tenemos un deme en particular y un slot en particular
      $this->GrabarParametros($iddeme, $idslot, $mezcla_activa, $superman1, $superman2);


      //  Vamos a escoger al azar un $idslot del $iddeme,... a no ser que tengamos $mezcla_activa = 1
      // en cuyo caso elegimos tambien al azar un $idslot
//      while ($idelemento == -1)
//      {
//      if ($idclan == 0)
//      {
        $idelemento = $this->ElegirEjemplar($link_r, $idjugador, $idcampana, $iddeme, $mezcla_activa, $idclan);
//      } else {
        // Si tiene un clan, tendra que elegir un ejemplar entre muchos mas
//        $idelemento = $this->ElegirEjemplarClan($link_r, $idjugador, $idcampana, $iddeme, $mezcla_activa, $idclan);
//      }
//      }
      $this->especimen->SacarDatosPorId($link_r, $idelemento);
if ($idelemento == -1)
{
echo ("PUM! Please contact an admin");
die;
}



      if ($this->debug_mode == 1)
      {
        echo ("<br/>Orig: [RAP: ".$this->especimen->rapidez.",INT:".$this->especimen->inteligencia.",FUE:".$this->especimen->fuerza);
        echo (",CON:".$this->especimen->constitucion.",PER:".$this->especimen->percepcion.",SAB:".$this->especimen->sabiduria);
        echo ("NUMSEXOS : ".$numsexos);
      }


      //  Si $numsexos = 1, las caracteristicas basicas son las mismas,
      // y tampoco varia el arbol

      switch($numsexos)
      {
        case 1:
                $nuevo_rapidez = $this->especimen->rapidez;
                $nuevo_inteligencia = $this->especimen->inteligencia;
                $nuevo_fuerza = $this->especimen->fuerza;
                $nuevo_constitucion = $this->especimen->constitucion;
                $nuevo_percepcion = $this->especimen->percepcion;
                $nuevo_sabiduria = $this->especimen->sabiduria;

                $total_puntos_base = TOTAL_PUNTOS_BASE;

                // Las nuevas silabas propias de nombre son en este caso las del unico progenitor
                $temp1 = rand(0,1);
                if ($temp1 == 0)
                {
		  $nuevo_silaba1 = $this->especimen->silaba1;
                } else {
                  $randnd = rand(0,($longitud_s - 1));
		  $nuevo_silaba1 = $array_s[$randnd];
                }
                $temp1 = rand(0,1);
                if ($temp1 == 0)
                {
		  $nuevo_silaba2 = $this->especimen->silaba2;
                } else {
                  $randnd = rand(0,($longitud_s - 1));
		  $nuevo_silaba2 = $array_s[$randnd];
                }
                $temp1 = rand(0,1);
                if ($temp1 == 0)
                {
		  $nuevo_silaba3 = $this->especimen->silaba3;
                } else {
                  $randnd = rand(0,($longitud_s - 1));
		  $nuevo_silaba3 = $array_s[$randnd];
                }


                // Vamos a ajustar las caracteristicas
                $ajuste = $total_puntos_base - $nuevo_rapidez - $nuevo_inteligencia - $nuevo_fuerza
				- $nuevo_constitucion - $nuevo_percepcion - $nuevo_sabiduria;
                if ($superman2 > 0)
                {
                  $ajuste = $ajuste + ($superman2 * (rand(1,6) + rand(1,4)) );
                } else {
                  $ajuste = $ajuste + ($superman1 * rand(1,6));
                }
                if ($this->debug_mode == 1)
                {
                  echo ("<br/>Ajustar por: ".$ajuste."#");
                }
                while ($ajuste > 0) // Si el ajuste > 0, entonces quedan puntos por repartir
                {
                  $ajuste_c = rand(1,6);
                  switch ($ajuste_c) {
                        case 1: if ($nuevo_rapidez < 10) { $nuevo_rapidez++; $ajuste--; } break;
                        case 2: if ($nuevo_inteligencia < 10) { $nuevo_inteligencia++; $ajuste--; } break;
                        case 3: if ($nuevo_fuerza < 10) { $nuevo_fuerza++; $ajuste--; } break;
                        case 4: if ($nuevo_constitucion < 10) { $nuevo_constitucion++; $ajuste--; } break;
                        case 5: if ($nuevo_percepcion < 10) { $nuevo_percepcion++; $ajuste--; } break;
                        case 6: if ($nuevo_sabiduria < 10) { $nuevo_sabiduria++; $ajuste--; } break;
                  }
                }
                while ($ajuste < 0) // Si el ajuste > 0, entonces sobran puntos en las caracteristicas
                {
                  $ajuste_c = rand(1,6);
                  switch ($ajuste_c) {
                        case 1: if ($nuevo_rapidez > 2) { $nuevo_rapidez--; $ajuste++; } break;
                        case 2: if ($nuevo_inteligencia > 2) { $nuevo_inteligencia--; $ajuste++; } break;
                        case 3: if ($nuevo_fuerza > 2) { $nuevo_fuerza--; $ajuste++; } break;
                        case 4: if ($nuevo_constitucion > 2) { $nuevo_constitucion--; $ajuste++; } break;
                        case 5: if ($nuevo_percepcion > 2) { $nuevo_percepcion--; $ajuste++; } break;
                        case 6: if ($nuevo_sabiduria > 2) { $nuevo_sabiduria--; $ajuste++; } break;
                  }
                }

                $this->nuevo_rapidez = $nuevo_rapidez;
                $this->nuevo_inteligencia = $nuevo_inteligencia;
                $this->nuevo_fuerza = $nuevo_fuerza;
                $this->nuevo_constitucion = $nuevo_constitucion;
                $this->nuevo_percepcion = $nuevo_percepcion;
                $this->nuevo_sabiduria = $nuevo_sabiduria;



                //  El arbol (en origen) va a ser tambien exactamente el mismo
                $this->nuevo_arbol = $this->especimen->arbol;
                if ($this->debug_mode == 1)
                {
                  echo ("<br/>Viejo arbol: ".$this->especimen->arbol.", Nuevo: ".$this->nuevo_arbol);
                }

                // Comenzamos el informe premium, si procede
                if ($this->es_premium == 1)
                {

                    if (($idclan != 0) && ($idclan != '') && ($idclan != null))
                    {
                      $idespecimen = $this->especimen->id;
                      $select = "SELECT a.login, a.id FROM
				jugador a, jugador_campana b, especimen c
				WHERE
				b.idjugador = a.id
				AND b.idcampana = $idcampana
				AND c.idpropietario = a.id
				AND c.idcampana = $idcampana
				AND c.id = $idespecimen
				";
                      $query = mysql_query($select, $link_r);
                      $esp_user = mysql_fetch_array($query);
                    }


                  if ($this->lang == 'en')
                  {
                    if (($idclan != 0) && ($idclan != '') && ($idclan != null))
                    {
                      $this->informe_premium = $this->informe_premium.' through asexual reproduction from the specimen in slot '.$this->especimen->idslot.
				' from player <a href="index.php?catid=5&accion=ver&idcampana='.$idcampana.'&idelemento='.$esp_user['id'].'">'.$esp_user['login'].
				'</a>, in the ';
                    } else {
                      $this->informe_premium = $this->informe_premium.' through asexual reproduction from the specimen in slot '.$this->especimen->idslot.' in the ';
                    }
                    switch ($this->especimen->iddeme)
                    {
                      case 1:
                          $this->informe_premium = $this->informe_premium." Abyssal Depths Deme. ";
                          break;
                      case 2:
                          $this->informe_premium = $this->informe_premium." Forest Deme. ";
                          break;
                      case 3:
                          $this->informe_premium = $this->informe_premium." Vulcano Deme. ";
                          break;
                    }
                  } else {
                    if (($idclan != 0) && ($idclan != '') && ($idclan != null))
                    {
//                      $this->informe_premium = $this->informe_premium.' through asexual reproduction from the specimen in slot '.$this->especimen->idslot.' (player '.$esp_user['login'].') in the ';
                      $this->informe_premium = $this->informe_premium.' mediante reproducci&oacute;n asexual a partir del especimen en el slot '.$this->especimen->idslot.
				' del jugador <a href="index.php?catid=5&accion=ver&idcampana='.$idcampana.'&idelemento='.$esp_user['id'].'">'.$esp_user['login'].
				'</a>, del ';
	            } else {
                      $this->informe_premium = $this->informe_premium.' mediante reproducci&oacute;n asexual a partir del especimen en el slot '.$this->especimen->idslot.' del ';
                    }
                    switch ($this->especimen->iddeme)
                    {
                      case 1:
                          $this->informe_premium = $this->informe_premium." Deme de las Profundidades. ";
                          break;
                      case 2:
                          $this->informe_premium = $this->informe_premium." Deme del Bosque. ";
                          break;
                      case 3:
                          $this->informe_premium = $this->informe_premium." Deme del Volc&aacute;n. ";
                          break;
                    }
                  } // Cierre if lenguaje
                }


		break;

        case 2:
                // En el caso de tener dos sexos, vamos a
                // 1. Elegir otro con el que mezclarlo que no sea el mismo
                // 2. Mezclar los arboles
                // 3. Mezclar las caracteristicas

                $idelemento2 = $idelemento;
                while ($idelemento2 == $idelemento)
                {
                  if ($this->debug_mode == 1)
                  {
                    echo ("<br/>Eligiendo segundo ejemplar.");
                  }
                  $idelemento2 = $this->ElegirEjemplar($link_r, $idjugador, $idcampana, $iddeme, $mezcla_activa, $idclan);
                }
                $especimen2 = new Especimen();
                $especimen2->SacarDatosPorId($link_r, $idelemento2);


                // ASIGNACION DE NUEVAS SILABAS DE NOMBRE
                // Las nuevas silabas propias de nombre vamos a elegirlas aleatoriamente
                $rand_silaba1 = rand(0,2);
                if ($rand_silaba1 == 0) { $nuevo_silaba1 = $this->especimen->silaba1; }
                if ($rand_silaba1 == 1) { $nuevo_silaba1 = $especimen2->silaba1; }
		if ($rand_silaba1 == 2) { $randnd = rand(0,($longitud_s - 1)); $nuevo_silaba1 = $array_s[$randnd]; }

                $rand_silaba2 = rand(0,2);
                if ($rand_silaba2 == 0) {
                  if ($this->especimen->silaba2 == $nuevo_silaba1)
                  {
    		    $nuevo_silaba2 = $especimen2->silaba2;
                  } else {
    		    $nuevo_silaba2 = $this->especimen->silaba2;
                  }
		}
                if ($rand_silaba2 == 1) {
                  if ($this->especimen2->silaba2 == $nuevo_silaba1)
                  {
    		    $nuevo_silaba2 = $this->especimen->silaba2;
                  } else {
    		    $nuevo_silaba2 = $especimen2->silaba2;
                  }
		}
		if ($rand_silaba2 == 2) { $randnd = rand(0,($longitud_s - 1)); $nuevo_silaba2 = $array_s[$randnd]; }
//echo ("!"); }
                $rand_silaba3 = rand(0,2);
                if ($rand_silaba3 == 0)
                {
                  // Estos if anidados son para intentar que no se repitan silabas
                  if ($this->especimen->silaba3 == $nuevo_silaba2)
                  {
    		    $nuevo_silaba3 = $especimen2->silaba3;
                  } else {
    		    $nuevo_silaba3 = $this->especimen->silaba3;
                  }
                }
                if ($rand_silaba3 == 1)
                {
                  // Estos if anidados son para intentar que no se repitan silabas
                  if ($this->especimen2->silaba3 == $nuevo_silaba2)
                  {
    		    $nuevo_silaba3 = $this->especimen->silaba3;
                  } else {
    		    $nuevo_silaba3 = $especimen2->silaba3;
                  }
                }
                if ($rand_silaba3 == 2) { $randnd = rand(0,($longitud_s - 1)); $nuevo_silaba3 = $array_s[$randnd]; }
                // ASIGNACION DE NUEVAS SILABAS DE NOMBRE [FIN]




                    if (($idclan != 0) && ($idclan != '') && ($idclan != null))
                    {
                      $idespecimen = $this->especimen->id;
                      $select = "SELECT a.login, a.id FROM
                                jugador a, jugador_campana b, especimen c
                                WHERE
                                b.idjugador = a.id
                                AND b.idcampana = $idcampana
                                AND c.idpropietario = a.id
                                AND c.idcampana = $idcampana
                                AND c.id = $idespecimen
                                ";
                      $query = mysql_query($select, $link_r);
                      $esp_user = mysql_fetch_array($query);

                      $select2 = "SELECT a.login, a.id FROM
                                jugador a, jugador_campana b, especimen c
                                WHERE
                                b.idjugador = a.id
                                AND b.idcampana = $idcampana
                                AND c.idpropietario = a.id
                                AND c.idcampana = $idcampana
                                AND c.id = $idelemento2
                                ";
                      $query2 = mysql_query($select2, $link_r);
                      $esp_user2 = mysql_fetch_array($query2);
                    }



                // Comenzamos el informe premium, si procede
                if ($this->es_premium == 1)
                {
                  if ($this->lang == 'en')
                  {
                    if (($idclan != 0) && ($idclan != '') && ($idclan != null))
                    {
                      $this->informe_premium = $this->informe_premium.' through sexual reproduction from the specimen in slot '.$this->especimen->idslot.
			' from player <a href="index.php?catid=5&accion=ver&idcampana='.$idcampana.'&idelemento='.$esp_user['id'].'">'.$esp_user['login'].
                                '</a> in the ';
                    } else {
                      $this->informe_premium = $this->informe_premium.' through sexual reproduction from the specimen in slot '.$this->especimen->idslot.' in the ';
                    }
                    switch ($this->especimen->iddeme)
                    {
                      case 1:
                          $this->informe_premium = $this->informe_premium." Abyssal Depths Deme, ";
                          break;
                      case 2:
                          $this->informe_premium = $this->informe_premium." Forest Deme, ";
                          break;
                      case 3:
                          $this->informe_premium = $this->informe_premium." Vulcano Deme, ";
                          break;
                    }
                    if (($idclan != 0) && ($idclan != '') && ($idclan != null))
                    {
                      $this->informe_premium = $this->informe_premium.' and the specimen in slot '.$especimen2->idslot.
			' from player <a href="index.php?catid=5&accion=ver&idcampana='.$idcampana.'&idelemento='.$esp_user2['id'].'">'.$esp_user2['login'].
			'</a>, in the ';
                    } else {
                      $this->informe_premium = $this->informe_premium.' and the specimen in slot '.$especimen2->idslot.' in the ';
                    }
                    switch ($especimen2->iddeme)
                    {
                      case 1:
                          $this->informe_premium = $this->informe_premium." Abyssal Depths Deme. ";
                          break;
                      case 2:
                          $this->informe_premium = $this->informe_premium." Forest Deme. ";
                          break;
                      case 3:
                          $this->informe_premium = $this->informe_premium." Vulcano Deme. ";
                          break;
                    }
                  } else {
                    if (($idclan != 0) && ($idclan != '') && ($idclan != null))
                    {
                      $this->informe_premium = $this->informe_premium.' mediante reproducci&oacute;n sexual a partir del especimen en el slot '.$this->especimen->idslot.
			' del jugador <a href="index.php?catid=5&accion=ver&idcampana='.$idcampana.'&idelemento='.$esp_user['id'].'">'.$esp_user['login'].
                                '</a> y del ';
                    } else {
                      $this->informe_premium = $this->informe_premium.' mediante reproducci&oacute;n sexual a partir del especimen en el slot '.$this->especimen->idslot.' y del ';
                    }
                    switch ($this->especimen->iddeme)
                    {
                      case 1:
                          $this->informe_premium = $this->informe_premium." Deme de las Profundidades, ";
                          break;
                      case 2:
                          $this->informe_premium = $this->informe_premium." Deme del Bosque, ";
                          break;
                      case 3:
                          $this->informe_premium = $this->informe_premium." Deme del Volc&aacute;n, ";
                          break;
                    }
                    if (($idclan != 0) && ($idclan != '') && ($idclan != null))
                    {
                      $this->informe_premium = $this->informe_premium.' y el especimen en el slot '.$especimen2->idslot.
			' del jugador <a href="index.php?catid=5&accion=ver&idcampana='.$idcampana.'&idelemento='.$esp_user2['id'].'">'.$esp_user2['login'].
                                '</a>, en el ';
                    } else {
                      $this->informe_premium = $this->informe_premium.' y el especimen en el slot '.$especimen2->idslot.' en el ';
                    }
//                      $this->informe_premium = $this->informe_premium.' mediante reproducci&oacute;n sexual a partir del especimen en el slot '.$this->especimen->idslot.' del ';
                    switch ($especimen2->iddeme)
                    {
                      case 1:
                          $this->informe_premium = $this->informe_premium." Deme de las Profundidades. ";
                          break;
                      case 2:
                          $this->informe_premium = $this->informe_premium." Deme del Bosque. ";
                          break;
                      case 3:
                          $this->informe_premium = $this->informe_premium." Deme del Volc&aacute;n. ";
                          break;
                    }
                  } // Cierre if lenguaje
                }



                // ************************************************** CRUZAR CARACTERISTICAS
                // Ahora podemos primero sacar la media de las caracteristicas
                $nuevo_rapidez = floor(($especimen2->rapidez + $this->especimen->rapidez) / 2);
                $nuevo_inteligencia = floor(($especimen2->inteligencia + $this->especimen->inteligencia) / 2);
                $nuevo_fuerza = floor(($especimen2->fuerza + $this->especimen->fuerza) / 2);
                $nuevo_constitucion = floor(($especimen2->constitucion + $this->especimen->constitucion) / 2);
                $nuevo_percepcion = floor(($especimen2->percepcion + $this->especimen->percepcion) / 2);
                $nuevo_sabiduria = floor(($especimen2->sabiduria + $this->especimen->sabiduria) / 2);

		$total_puntos_base = TOTAL_PUNTOS_BASE;
                // Vamos a ajustar las caracteristicas
                $ajuste = $total_puntos_base - $nuevo_rapidez - $nuevo_inteligencia - $nuevo_fuerza
				- $nuevo_constitucion - $nuevo_percepcion - $nuevo_sabiduria;
                if ($superman2 > 0)
                {
                  $ajuste = $ajuste + ($superman2 * (rand(1,6) + rand(1,4)) );
                } else {
                  $ajuste = $ajuste + ($superman1 * rand(1,6));
                }
//                $ajuste = $ajuste + ($superman1 * rand(1,6)) + ($superman2 * (rand(1,6) + rand(1,6)) );
                if ($this->debug_mode == 1)
                {
                  echo ("<br/>Ajustar por: ".$ajuste."#");
                }
                while ($ajuste > 0) // Si el ajuste > 0, entonces quedan puntos por repartir
                {
                  $ajuste_c = rand(1,6);
                  switch ($ajuste_c) {
			case 1: if ($nuevo_rapidez < 10) { $nuevo_rapidez++; $ajuste--; } break;
			case 2: if ($nuevo_inteligencia < 10) { $nuevo_inteligencia++; $ajuste--; } break;
			case 3: if ($nuevo_fuerza < 10) { $nuevo_fuerza++; $ajuste--; } break;
			case 4: if ($nuevo_constitucion < 10) { $nuevo_constitucion++; $ajuste--; } break;
			case 5: if ($nuevo_percepcion < 10) { $nuevo_percepcion++; $ajuste--; } break;
			case 6: if ($nuevo_sabiduria < 10) { $nuevo_sabiduria++; $ajuste--; } break;
                  }
                }
                while ($ajuste < 0) // Si el ajuste > 0, entonces sobran puntos en las caracteristicas
                {
                  $ajuste_c = rand(1,6);
                  switch ($ajuste_c) {
			case 1: if ($nuevo_rapidez > 2) { $nuevo_rapidez--; $ajuste++; } break;
			case 2: if ($nuevo_inteligencia > 2) { $nuevo_inteligencia--; $ajuste++; } break;
			case 3: if ($nuevo_fuerza > 2) { $nuevo_fuerza--; $ajuste++; } break;
			case 4: if ($nuevo_constitucion > 2) { $nuevo_constitucion--; $ajuste++; } break;
			case 5: if ($nuevo_percepcion > 2) { $nuevo_percepcion--; $ajuste++; } break;
			case 6: if ($nuevo_sabiduria > 2) { $nuevo_sabiduria--; $ajuste++; } break;
                  }
                }

                $this->nuevo_rapidez = $nuevo_rapidez;
                $this->nuevo_inteligencia = $nuevo_inteligencia;
                $this->nuevo_fuerza = $nuevo_fuerza;
                $this->nuevo_constitucion = $nuevo_constitucion;
                $this->nuevo_percepcion = $nuevo_percepcion;
                $this->nuevo_sabiduria = $nuevo_sabiduria;


                //  Averiguamos sobre que nodo lo vamos a cruzar. Sera un random entre todos los nodos a partir 
		// del 2 (no puedes cambiar el raiz) y hasta la ultima hoja.
                $totalnodoshojas = 0;
                for ($n = 0; $n <= ($this->especimen->niveles_arbol - 1); $n++)
                {
                  $totalnodoshojas = $totalnodoshojas + pow(2, $n);
                }
                $numnodo = rand(2, $totalnodoshojas);

                if ($this->debug_mode == 1)
                {
                  echo ("<br/>Sustituyendo nodo ".$numnodo." con el del ejemplar ".$idelemento2.", con ".$totalnodoshojas." nodos+hojas");

                  echo ("<br/><b>Arbol original :</b> ");
                  $totalmenos = pow(2, ($this->especimen->niveles_arbol - 1));
                  for ($i = 1; $i <= ($totalnodoshojas - $totalmenos); $i++){
                    echo ord($this->especimen->arbol[($i*2)-2]);
                    echo (".");
                    echo ord($this->especimen->arbol[($i*2)-1]);
                    echo (";");
                  }
                  for ($i = 1; $i <= $totalmenos; $i++){
                    echo ord($this->especimen->arbol[(($totalnodoshojas - $totalmenos) * 2) + ($i - 1)]);
                    echo (":");
                  }
                  echo ("<br/><b>Arbol con el que se cruza</b> : ".$especimen2->arbol);
                  for ($i = 1; $i <= ($totalnodoshojas - $totalmenos); $i++){
                    echo ord($especimen2->arbol[($i*2)-2]);
                    echo (".");
                    echo ord($especimen2->arbol[($i*2)-1]);
                    echo (";");
                  }
                  for ($i = 1; $i <= $totalmenos; $i++){
                    echo ord($especimen2->arbol[(($totalnodoshojas - $totalmenos) * 2) + ($i - 1)]);
                    echo (":");
                  }
                }

		$this->CruzarArboles($numnodo, $especimen2->arbol);
                $this->nuevo_arbol = $this->especimen->arbol;

                if ($this->debug_mode == 1)
                {
                  echo ("<br/><b>Arbol resultado :</b> ");
                  $totalmenos = pow(2, ($this->especimen->niveles_arbol - 1));
                  for ($i = 1; $i <= ($totalnodoshojas - $totalmenos); $i++){
                    echo ord($this->especimen->arbol[($i*2)-2]);
                    echo (".");
                    echo ord($this->especimen->arbol[($i*2)-1]);
                    echo (";");
                  }
                  for ($i = 1; $i <= $totalmenos; $i++){
                    echo ord($this->especimen->arbol[(($totalnodoshojas - $totalmenos) * 2) + ($i - 1)]);
                    echo (":");
                  }
                }


		break;

        case 3:

                // En el caso de tener dos sexos, vamos a
                // 1. Elegir otroS con el que mezclarlo que no seaN el mismo
                // 2. Mezclar los arboles
                // 3. Mezclar las caracteristicas

                $idelemento2 = $idelemento;
                while ($idelemento2 == $idelemento)
                {
                  $idelemento2 = $this->ElegirEjemplar($link_r, $idjugador, $idcampana, $iddeme, $mezcla_activa, $idclan);
                }
                $idelemento3 = $idelemento; // Sacamos el segundo elemento
                while (($idelemento2 == $idelemento3) || ($idelemento == $idelemento3))
                {
                  $idelemento3 = $this->ElegirEjemplar($link_r, $idjugador, $idcampana, $iddeme, $mezcla_activa, $idclan);
                }

                $especimen2 = new Especimen();
                $especimen2->SacarDatosPorId($link_r, $idelemento2);

                $especimen3 = new Especimen();
                $especimen3->SacarDatosPorId($link_r, $idelemento3);



                // ASIGNACION DE NUEVAS SILABAS DE NOMBRE
                // Las nuevas silabas propias de nombre vamos a elegirlas aleatoriamente
                $rand_silaba1 = rand(0,3);
                if ($rand_silaba1 == 0)
                {
		  $nuevo_silaba1 = $this->especimen->silaba1;
                }
                if ($rand_silaba1 == 1)
                {
		  $nuevo_silaba1 = $especimen2->silaba1;
                }
                if ($rand_silaba1 == 2)
                {
		  $nuevo_silaba1 = $especimen3->silaba1;
                }
                if ($rand_silaba1 == 3) { $randnd = rand(0,($longitud_s - 1)); $nuevo_silaba1 = $array_s[$randnd]; }

                $rand_silaba2 = rand(0,3);
                if ($rand_silaba2 == 0)
                {
		  $nuevo_silaba2 = $this->especimen->silaba2;
                  if ($nuevo_silaba1 == $nuevo_silaba2) { $nuevo_silaba2 = $especimen2->silaba2; }
                }
                if ($rand_silaba2 == 1)
                {
		  $nuevo_silaba2 = $especimen2->silaba2;
                  if ($nuevo_silaba1 == $nuevo_silaba2) { $nuevo_silaba2 = $especimen3->silaba2; }
                }
                if ($rand_silaba2 == 2)
                {
		  $nuevo_silaba2 = $especimen3->silaba2;
                  if ($nuevo_silaba1 == $nuevo_silaba2) { $nuevo_silaba2 = $this->especimen->silaba2; }
                }
                if ($rand_silaba2 == 3) { $randnd = rand(0,($longitud_s - 1)); $nuevo_silaba2 = $array_s[$randnd]; }


                $rand_silaba3 = rand(0,3);
                if ($rand_silaba3 == 0)
                {
		  $nuevo_silaba3 = $this->especimen->silaba3;
                  if ($nuevo_silaba2 == $nuevo_silaba3) { $nuevo_silaba3 = $especimen2->silaba2; }
                }
                if ($rand_silaba3 == 1)
                {
		  $nuevo_silaba3 = $especimen2->silaba3;
                  if ($nuevo_silaba2 == $nuevo_silaba3) { $nuevo_silaba3 = $especimen3->silaba2; }
                }
                if ($rand_silaba3 == 2)
                {
		  $nuevo_silaba3 = $especimen3->silaba3;
                  if ($nuevo_silaba2 == $nuevo_silaba3) { $nuevo_silaba3 = $this->especimen1->silaba2; }
                }
                if ($rand_silaba3 == 3) { $randnd = rand(0,($longitud_s - 1)); $nuevo_silaba3 = $array_s[$randnd]; }

                // ASIGNACION DE NUEVAS SILABAS DE NOMBRE [FIN]




                    if (($idclan != 0) && ($idclan != '') && ($idclan != null))
                    {
                      $idespecimen = $this->especimen->id;
                      $select = "SELECT a.login, a.id FROM
                                jugador a, jugador_campana b, especimen c
                                WHERE
                                b.idjugador = a.id
                                AND b.idcampana = $idcampana
                                AND c.idpropietario = a.id
                                AND c.idcampana = $idcampana
                                AND c.id = $idespecimen
                                ";
                      $query = mysql_query($select, $link_r);
                      $esp_user = mysql_fetch_array($query);

                      $select2 = "SELECT a.login, a.id FROM
                                jugador a, jugador_campana b, especimen c
                                WHERE
                                b.idjugador = a.id
                                AND b.idcampana = $idcampana
                                AND c.idpropietario = a.id
                                AND c.idcampana = $idcampana
                                AND c.id = $idelemento2
                                ";
                      $query2 = mysql_query($select2, $link_r);
                      $esp_user2 = mysql_fetch_array($query2);

                      $select3 = "SELECT a.login, a.id FROM
                                jugador a, jugador_campana b, especimen c
                                WHERE
                                b.idjugador = a.id
                                AND b.idcampana = $idcampana
                                AND c.idpropietario = a.id
                                AND c.idcampana = $idcampana
                                AND c.id = $idelemento3
                                ";
                      $query3 = mysql_query($select3, $link_r);
                      $esp_user3 = mysql_fetch_array($query3);

                    }




                // Comenzamos el informe premium, si procede
                if ($this->es_premium == 1)
                {
                  if ($this->lang == 'en')
                  {
                    if (($idclan != 0) && ($idclan != '') && ($idclan != null))
                    {
                      $this->informe_premium = $this->informe_premium.' through trisexual reproduction from the specimen in slot '.$this->especimen->idslot.
                        ' from player <a href="index.php?catid=5&accion=ver&idcampana='.$idcampana.'&idelemento='.$esp_user['id'].'">'.$esp_user['login'].
                                '</a> in the ';
                    } else {
                      $this->informe_premium = $this->informe_premium.' through trisexual reproduction from the specimen in slot '.$this->especimen->idslot.' in the ';
                    }
//                    $this->informe_premium = $this->informe_premium.' through trisexual reproduction from the specimen in slot '.$this->especimen->idslot.' in the ';
                    switch ($this->especimen->iddeme)
                    {
                      case 1:
                          $this->informe_premium = $this->informe_premium." Abyssal Depths Deme, ";
                          break;
                      case 2:
                          $this->informe_premium = $this->informe_premium." Forest Deme, ";
                          break;
                      case 3:
                          $this->informe_premium = $this->informe_premium." Vulcano Deme ";
                          break;
                    }
                    if (($idclan != 0) && ($idclan != '') && ($idclan != null))
                    {
                      $this->informe_premium = $this->informe_premium.' the specimen in slot '.$especimen2->idslot.
                        ' from player <a href="index.php?catid=5&accion=ver&idcampana='.$idcampana.'&idelemento='.$esp_user2['id'].'">'.$esp_user2['login'].
                        '</a> in the ';
                    } else {
                      $this->informe_premium = $this->informe_premium.' the specimen in slot '.$especimen2->idslot.' in the ';
                    }
//                    $this->informe_premium = $this->informe_premium.", the specimen in slot ".$especimen2->idslot." in the ";
                    switch ($especimen2->iddeme)
                    {
                      case 1:
                          $this->informe_premium = $this->informe_premium." Abyssal Depths Deme, ";
                          break;
                      case 2:
                          $this->informe_premium = $this->informe_premium." Forest Deme, ";
                          break;
                      case 3:
                          $this->informe_premium = $this->informe_premium." Vulcano Deme, ";
                          break;
                    }
                    if (($idclan != 0) && ($idclan != '') && ($idclan != null))
                    {
                      $this->informe_premium = $this->informe_premium.' and the specimen in slot '.$especimen3->idslot.
                        ' from player <a href="index.php?catid=5&accion=ver&idcampana='.$idcampana.'&idelemento='.$esp_user3['id'].'">'.$esp_user3['login'].
                        '</a>, in the ';
                    } else {
                      $this->informe_premium = $this->informe_premium.' and the specimen in slot '.$especimen3->idslot.' in the ';
                    }
//                    $this->informe_premium = $this->informe_premium." and the specimen in slot ".$especimen3->idslot." in the ";
                    switch ($especimen3->iddeme)
                    {
                      case 1:
                          $this->informe_premium = $this->informe_premium." Abyssal Depths Deme. ";
                          break;
                      case 2:
                          $this->informe_premium = $this->informe_premium." Forest Deme. ";
                          break;
                      case 3:
                          $this->informe_premium = $this->informe_premium." Vulcano Deme. ";
                          break;
                    }
                  } else {

                    if (($idclan != 0) && ($idclan != '') && ($idclan != null))
                    {
                      $this->informe_premium = $this->informe_premium.' mediante reproducci&oacute;n trisexual a partir del especimen en el slot '.$this->especimen->idslot.
                        ' del jugador <a href="index.php?catid=5&accion=ver&idcampana='.$idcampana.'&idelemento='.$esp_user['id'].'">'.$esp_user['login'].
                                '</a> del ';
                    } else {
                      $this->informe_premium = $this->informe_premium.' mediante reproducci&oacute;n trisexual a partir del especimen en el slot '.$this->especimen->idslot.' del ';
                    }
//                    $this->informe_premium = $this->informe_premium.' mediante reproducci&oacute;n sexual a partir del especimen en el slot '.$this->especimen->idslot.' del ';
                    switch ($this->especimen->iddeme)
                    {
                      case 1:
                          $this->informe_premium = $this->informe_premium." Deme de las Profundidades, ";
                          break;
                      case 2:
                          $this->informe_premium = $this->informe_premium." Deme del Bosque, ";
                          break;
                      case 3:
                          $this->informe_premium = $this->informe_premium." Deme del Volc&aacute;n, ";
                          break;
                    }
                    if (($idclan != 0) && ($idclan != '') && ($idclan != null))
                    {
                      $this->informe_premium = $this->informe_premium.'el especimen en el slot '.$especimen2->idslot.
                        ' del jugador <a href="index.php?catid=5&accion=ver&idcampana='.$idcampana.'&idelemento='.$esp_user2['id'].'">'.$esp_user2['login'].
                                '</a> en el ';
                    } else {
                      $this->informe_premium = $this->informe_premium.'el especimen en el slot '.$especimen2->idslot.' en el ';
                    }
//                    $this->informe_premium = $this->informe_premium.", el especimen en el slot ".$especimen2->idslot." en el ";
                    switch ($especimen2->iddeme)
                    {
                      case 1:
                          $this->informe_premium = $this->informe_premium." Deme de las Profundidades, ";
                          break;
                      case 2:
                          $this->informe_premium = $this->informe_premium." Deme del Bosque, ";
                          break;
                      case 3:
                          $this->informe_premium = $this->informe_premium." Deme del Volc&aacute;n, ";
                          break;
                    }
                    if (($idclan != 0) && ($idclan != '') && ($idclan != null))
                    {
                      $this->informe_premium = $this->informe_premium.' y el especimen en el slot '.$especimen3->idslot.
                        ' del jugador <a href="index.php?catid=5&accion=ver&idcampana='.$idcampana.'&idelemento='.$esp_user3['id'].'">'.$esp_user3['login'].
                                '</a>, en el ';
                    } else {
                      $this->informe_premium = $this->informe_premium.' y el especimen en el slot '.$especimen3->idslot.' en el ';
                    }
//                    $this->informe_premium = $this->informe_premium." y el especimen en el slot ".$especimen3->idslot." en el ";
                    switch ($especimen3->iddeme)
                    {
                      case 1:
                          $this->informe_premium = $this->informe_premium." Deme de las Profundidades. ";
                          break;
                      case 2:
                          $this->informe_premium = $this->informe_premium." Deme del Bosque. ";
                          break;
                      case 3:
                          $this->informe_premium = $this->informe_premium." Deme del Volc&aacute;n. ";
                          break;
                    }
                  } // Cierre if lenguaje
                }




                // ************************************************** CRUZAR CARACTERISTICAS
                // Ahora podemos primero sacar la media de las caracteristicas
                $nuevo_rapidez = floor(($especimen3->rapidez + $especimen2->rapidez + $this->especimen->rapidez) / 3);
                $nuevo_inteligencia = floor(($especimen3->inteligencia + $especimen2->inteligencia + $this->especimen->inteligencia) / 3);
                $nuevo_fuerza = floor(($especimen3->fuerza + $especimen2->fuerza + $this->especimen->fuerza) / 3);
                $nuevo_constitucion = floor(($especimen3->constitucion + $especimen2->constitucion + $this->especimen->constitucion) / 3);
                $nuevo_percepcion = floor(($especimen3->percepcion + $especimen2->percepcion + $this->especimen->percepcion) / 3);
                $nuevo_sabiduria = floor(($especimen3->sabiduria + $especimen2->sabiduria + $this->especimen->sabiduria) / 3);

		$total_puntos_base = TOTAL_PUNTOS_BASE;
                // Vamos a ajustar las caracteristicas
                $ajuste = $total_puntos_base - $nuevo_rapidez - $nuevo_inteligencia - $nuevo_fuerza
				- $nuevo_constitucion - $nuevo_percepcion - $nuevo_sabiduria;
                if ($superman2 > 0)
                {
                  $ajuste = $ajuste + ($superman2 * (rand(1,6) + rand(1,4)) );
                } else {
                  $ajuste = $ajuste + ($superman1 * rand(1,6));
                }
//                $ajuste = $ajuste + ($superman1 * rand(1,6)) + ($superman2 * (rand(1,6) + rand(1,6)) );
                if ($this->debug_mode == 1)
                {
                  echo ("<br/>Ajustar por: ".$ajuste."#");
                }
                while ($ajuste > 0) // Si el ajuste > 0, entonces quedan puntos por repartir
                {
                  $ajuste_c = rand(1,6);
                  switch ($ajuste_c) {
			case 1: if ($nuevo_rapidez < 10) { $nuevo_rapidez++; $ajuste--; } break;
			case 2: if ($nuevo_inteligencia < 10) { $nuevo_inteligencia++; $ajuste--; } break;
			case 3: if ($nuevo_fuerza < 10) { $nuevo_fuerza++; $ajuste--; } break;
			case 4: if ($nuevo_constitucion < 10) { $nuevo_constitucion++; $ajuste--; } break;
			case 5: if ($nuevo_percepcion < 10) { $nuevo_percepcion++; $ajuste--; } break;
			case 6: if ($nuevo_sabiduria < 10) { $nuevo_sabiduria++; $ajuste--; } break;
                  }
                }
                while ($ajuste < 0) // Si el ajuste > 0, entonces sobran puntos en las caracteristicas
                {
                  $ajuste_c = rand(1,6);
                  switch ($ajuste_c) {
			case 1: if ($nuevo_rapidez > 2) { $nuevo_rapidez--; $ajuste++; } break;
			case 2: if ($nuevo_inteligencia > 2) { $nuevo_inteligencia--; $ajuste++; } break;
			case 3: if ($nuevo_fuerza > 2) { $nuevo_fuerza--; $ajuste++; } break;
			case 4: if ($nuevo_constitucion > 2) { $nuevo_constitucion--; $ajuste++; } break;
			case 5: if ($nuevo_percepcion > 2) { $nuevo_percepcion--; $ajuste++; } break;
			case 6: if ($nuevo_sabiduria > 2) { $nuevo_sabiduria--; $ajuste++; } break;
                  }
                }

                $this->nuevo_rapidez = $nuevo_rapidez;
                $this->nuevo_inteligencia = $nuevo_inteligencia;
                $this->nuevo_fuerza = $nuevo_fuerza;
                $this->nuevo_constitucion = $nuevo_constitucion;
                $this->nuevo_percepcion = $nuevo_percepcion;
                $this->nuevo_sabiduria = $nuevo_sabiduria;


                //  Averiguamos sobre que nodo lo vamos a cruzar. Sera un random entre todos los nodos a partir 
		// del 2 (no puedes cambiar el raiz) y hasta la ultima hoja.
                $totalnodoshojas = 0;
                for ($n = 0; $n <= ($this->especimen->niveles_arbol - 1); $n++)
                {
                  $totalnodoshojas = $totalnodoshojas + pow(2, $n);
                }
		$numnodo1 = 0;
		$numnodo2 = 0;
                while ($numnodo1 == $numnodo2)
                {
                  $numnodo1 = rand(2, $totalnodoshojas);
                  $numnodo2 = rand(2, $totalnodoshojas);
                }
		// Los ordenamos de menor a mayor, para que los cambios tengan sentido
                if ($numnodo1 > $numnodo2) { $numnodo = $numnodo2; $numnodo2 = $numnodo1; } else { $numnodo = $numnodo1; }
		// Los definitivos seran $numnodo y $numnodo2


                // DEBUG DEL PRIMER CRUCE
                if ($this->debug_mode == 1)
                {
                  echo ("<br/>Sustituyendo nodo ".$numnodo." con el del ejemplar ".$idelemento2.", con ".$totalnodoshojas." nodos+hojas");

                  echo ("<br/><b>Arbol original :</b> ");
                  $totalmenos = pow(2, ($this->especimen->niveles_arbol - 1));
                  for ($i = 1; $i <= ($totalnodoshojas - $totalmenos); $i++){
                    echo ord($this->especimen->arbol[($i*2)-2]);
                    echo (".");
                    echo ord($this->especimen->arbol[($i*2)-1]);
                    echo (";");
                  }
                  for ($i = 1; $i <= $totalmenos; $i++){
                    echo ord($this->especimen->arbol[(($totalnodoshojas - $totalmenos) * 2) + ($i - 1)]);
                    echo (":");
                  }

                  echo ("<br/><b>Arbol con el que se cruza :</b> ".$especimen2->arbol);
                  for ($i = 1; $i <= ($totalnodoshojas - $totalmenos); $i++){
                    echo ord($especimen2->arbol[($i*2)-2]);
                    echo (".");
                    echo ord($especimen2->arbol[($i*2)-1]);
                    echo (";");
                  }
                  for ($i = 1; $i <= $totalmenos; $i++){
                    echo ord($especimen2->arbol[(($totalnodoshojas - $totalmenos) * 2) + ($i - 1)]);
                    echo (":");
                  }
                }

		$this->CruzarArboles($numnodo, $especimen2->arbol);


	// AHORA A POR EL SEGUNDO CRUCE

                if ($this->debug_mode == 1)
                {
                  echo ("<br/>Sustituyendo nodo ".$numnodo2." con el del ejemplar ".$idelemento3.", con ".$totalnodoshojas." nodos+hojas");
                  echo ("<br/><b>Arbol original :</b> ");
                  $totalmenos = pow(2, ($this->especimen->niveles_arbol - 1));
                  for ($i = 1; $i <= ($totalnodoshojas - $totalmenos); $i++){
                    echo ord($this->especimen->arbol[($i*2)-2]);
                    echo (".");
                    echo ord($this->especimen->arbol[($i*2)-1]);
                    echo (";");
                  }
                  for ($i = 1; $i <= $totalmenos; $i++){
                    echo ord($this->especimen->arbol[(($totalnodoshojas - $totalmenos) * 2) + ($i - 1)]);
                    echo (":");
                  }

                  echo ("<br/><b>Arbol con el que se cruza :</b> ".$especimen3->arbol);
                  for ($i = 1; $i <= ($totalnodoshojas - $totalmenos); $i++){
                    echo ord($especimen3->arbol[($i*2)-2]);
                    echo (".");
                    echo ord($especimen3->arbol[($i*2)-1]);
                    echo (";");
                  }
                  for ($i = 1; $i <= $totalmenos; $i++){
                    echo ord($especimen3->arbol[(($totalnodoshojas - $totalmenos) * 2) + ($i - 1)]);
                    echo (":");
                  }
                }

		$this->CruzarArboles($numnodo2, $especimen3->arbol);

                $this->nuevo_arbol = $this->especimen->arbol;


                if ($this->debug_mode == 1)
                {
                  echo ("<br/><b>Arbol resultado :</b> ");
                  $totalmenos = pow(2, ($this->especimen->niveles_arbol - 1));
                  for ($i = 1; $i <= ($totalnodoshojas - $totalmenos); $i++){
                    echo ord($this->especimen->arbol[($i*2)-2]);
                    echo (".");
                    echo ord($this->especimen->arbol[($i*2)-1]);
                    echo (";");
                  }
                  for ($i = 1; $i <= $totalmenos; $i++){
                    echo ord($this->especimen->arbol[(($totalnodoshojas - $totalmenos) * 2) + ($i - 1)]);
                    echo (":");
                  }
                }

		break;
      }


      //  Ahora llega la fase de mutacion!!!! Y todo depende de su gravedad

      if ($this->debug_mode == 1)
      {
        echo ("<br/>Nuevas_antes_mutacion: [RAP: ".$this->nuevo_rapidez.",INT:".$this->nuevo_inteligencia.",FUE:".$this->nuevo_fuerza);
        echo (",CON:".$this->nuevo_constitucion.",PER:".$this->nuevo_percepcion.",SAB:".$this->nuevo_sabiduria);
      }

      $this->RealizarMutacion();

      if ($this->debug_mode == 1)
      {
        echo ("<br/>Nuevas: [RAP: ".$this->nuevo_rapidez.",INT:".$this->nuevo_inteligencia.",FUE:".$this->nuevo_fuerza);
        echo (",CON:".$this->nuevo_constitucion.",PER:".$this->nuevo_percepcion.",SAB:".$this->nuevo_sabiduria);
        echo ("<br/>");
      }



      // SITUAMOS LAS LETRAS APROPIADAS PARA LA SILABACAR
      $silaba_car = '';
      if ($iddeme == 1)
      {
         // RAPIDEZ
         if(($this->especimen->rapidez) <= 4){        $silaba_car = $silaba_car."g"; }
         if((($this->especimen->rapidez) <= 7) && ($this->especimen->rapidez > 4)) {     $silaba_car = $silaba_car."z"; }
         if(($this->especimen->rapidez) > 7) {        $silaba_car = $silaba_car."k"; }            
         // FUERZA
         if(($this->especimen->fuerza) <= 4) { $silaba_car = $silaba_car."i"; }
         if((($this->especimen->fuerza) <= 7) && ($this->especimen->fuerza > 4)) { $silaba_car = $silaba_car."e"; }
         if(($this->especimen->fuerza) > 7) { $silaba_car = $silaba_car."u"; }       
         // INTELIGENCIA
         if(($this->especimen->inteligencia) <= 4) {  $silaba_car = $silaba_car."t"; }
         if((($this->especimen->inteligencia) <= 7) && ($this->especimen->inteligencia > 4)) { $silaba_car = $silaba_car."k"; }
         if(($this->especimen->inteligencia) > 7) {   $silaba_car = $silaba_car."d"; }          
         // CONSTITUCION
         if(($this->especimen->constitucion) <= 4) { $silaba_car = $silaba_car."g"; }
         if((($this->especimen->constitucion) <= 7) && ($this->especimen->constitucion > 4)) { $silaba_car = $silaba_car."n"; }      
         if(($this->especimen->constitucion) > 7) {   $silaba_car = $silaba_car."k"; }           
         // PERCEPCION
         if(($this->especimen->percepcion) <= 4) { $silaba_car = $silaba_car."o"; }
         if((($this->especimen->percepcion) <= 7) && ($this->especimen->percepcion > 4)) {       $silaba_car = $silaba_car."u"; }
         if(($this->especimen->percepcion) > 7) { $silaba_car = $silaba_car."y"; }     
         // SABIDURIA
         if(($this->especimen->sabiduria) <= 4) {     $silaba_car = $silaba_car."j"; }
         if((($this->especimen->sabiduria) <= 7) && ($this->especimen->sabiduria > 4)) { $silaba_car = $silaba_car."h"; }
         if(($this->especimen->sabiduria) > 7) {      $silaba_car = $silaba_car."k"; }
      }
      if($iddeme == 2)
      {
        // RAPIDEZ
        if(($this->especimen->rapidez) <= 4){        $silaba_car = $silaba_car."t"; }
        if((($this->especimen->rapidez) <= 7) && ($this->especimen->rapidez > 4)) {     $silaba_car = $silaba_car."f"; }
        if(($this->especimen->rapidez) > 7) {        $silaba_car = $silaba_car."l"; }            
        // FUERZA
        if(($this->especimen->fuerza) <= 4) { $silaba_car = $silaba_car."a"; }
        if((($this->especimen->fuerza) <= 7) && ($this->especimen->fuerza > 4)) { $silaba_car = $silaba_car."e"; }
        if(($this->especimen->fuerza) > 7) { $silaba_car = $silaba_car."i"; }       
        // INTELIGENCIA
        if(($this->especimen->inteligencia) <= 4) {  $silaba_car = $silaba_car."r"; }
        if((($this->especimen->inteligencia) <= 7) && ($this->especimen->inteligencia > 4)) { $silaba_car = $silaba_car."n"; }
        if(($this->especimen->inteligencia) > 7) {   $silaba_car = $silaba_car."s"; }          
        // CONSTITUCION
        if(($this->especimen->constitucion) <= 4) { $silaba_car = $silaba_car."n"; }
        if((($this->especimen->constitucion) <= 7) && ($this->especimen->constitucion > 4)) { $silaba_car = $silaba_car."l"; }      
        if(($this->especimen->constitucion) > 7) {   $silaba_car = $silaba_car."f"; }           
        // PERCEPCION
        if(($this->especimen->percepcion) <= 4) { $silaba_car = $silaba_car."e"; }
        if((($this->especimen->percepcion) <= 7) && ($this->especimen->percepcion > 4)) {       $silaba_car = $silaba_car."i"; }
        if(($this->especimen->percepcion) > 7) { $silaba_car = $silaba_car."a"; }     
        // SABIDURIA
        if(($this->especimen->sabiduria) <= 4) {     $silaba_car = $silaba_car."l"; }
        if((($this->especimen->sabiduria) <= 7) && ($this->especimen->sabiduria > 4)) { $silaba_car = $silaba_car."r"; }
        if(($this->especimen->sabiduria) > 7) {      $silaba_car = $silaba_car."n"; }
      }
      if ($iddeme == 3)
      {
        // RAPIDEZ
        if(($this->especimen->rapidez) <= 4){        $silaba_car = $silaba_car."s"; }
        if((($this->especimen->rapidez) <= 7) && ($this->especimen->rapidez > 4)) {     $silaba_car = $silaba_car."t"; }
        if(($this->especimen->rapidez) > 7) {        $silaba_car = $silaba_car."r"; }            
        // FUERZA
        if(($this->especimen->fuerza) <= 4) { $silaba_car = $silaba_car."a"; }
        if((($this->especimen->fuerza) <= 7) && ($this->especimen->fuerza > 4)) { $silaba_car = $silaba_car."e"; }
        if(($this->especimen->fuerza) > 7) { $silaba_car = $silaba_car."o"; }       
        // INTELIGENCIA
        if(($this->especimen->inteligencia) <= 4) {  $silaba_car = $silaba_car."t"; }
        if((($this->especimen->inteligencia) <= 7) && ($this->especimen->inteligencia > 4)) { $silaba_car = $silaba_car."f"; }
        if(($this->especimen->inteligencia) > 7) {   $silaba_car = $silaba_car."m"; }          
        // CONSTITUCION
        if(($this->especimen->constitucion) <= 4) { $silaba_car = $silaba_car."r"; }
        if((($this->especimen->constitucion) <= 7) && ($this->especimen->constitucion > 4)) { $silaba_car = $silaba_car."s"; }      
        if(($this->especimen->constitucion) > 7) {   $silaba_car = $silaba_car."p"; }           
        // PERCEPCION
        if(($this->especimen->percepcion) <= 4) { $silaba_car = $silaba_car."o"; }
        if((($this->especimen->percepcion) <= 7) && ($this->especimen->percepcion > 4)) {       $silaba_car = $silaba_car."e"; }
        if(($this->especimen->percepcion) > 7) { $silaba_car = $silaba_car."a"; }     
        // SABIDURIA
        if(($this->especimen->sabiduria) <= 4) {     $silaba_car = $silaba_car."r"; }
        if((($this->especimen->sabiduria) <= 7) && ($this->especimen->sabiduria > 4)) { $silaba_car = $silaba_car."m"; }
        if(($this->especimen->sabiduria) > 7) {      $silaba_car = $silaba_car."n"; }
      }
      $this->especimen->silabacar = $silaba_car;




      // Finalmente, vamos a grabar todo esto en un nuevo especimen que va a ir a la posicion $iddeme-$idslot
      $this->especimen->rapidez = $this->nuevo_rapidez;
      $this->especimen->inteligencia = $this->nuevo_inteligencia;
      $this->especimen->fuerza = $this->nuevo_fuerza;
      $this->especimen->constitucion = $this->nuevo_constitucion;
      $this->especimen->percepcion = $this->nuevo_percepcion;
      $this->especimen->sabiduria = $this->nuevo_sabiduria;
      $this->especimen->arbol = $this->nuevo_arbol;

//echo ("#".$nuevo_silaba1."#");
      $this->especimen->silaba1 = $nuevo_silaba1;
      $this->especimen->silaba2 = $nuevo_silaba2;
      $this->especimen->silaba3 = $nuevo_silaba3;

      $this->especimen->InsertarEspecimen($link_w, $iddeme, $idjugador, $idcampana, $idslot);



       // Ahora grabamos las silabas


    }


    // *******************************************************
    //    Funcion de evolucion sexual a dos bandas
    // *******************************************************

    function EvolucionarEspecimenSexual2($link_r, $idjugador, $idcampana, $iddeme, $idslot)
    {

      $this->jugador_campana->SacarDatos($link_r, $idjugador, $idcampana);
      $mezcla_activa = $this->jugador_campana->mezcla_activa;
      $slots1 = $this->jugador_campana->num_slots_deme_profundidades;
      $slots2 = $this->jugador_campana->num_slots_deme_bosque;
      $slots3 = $this->jugador_campana->num_slots_deme_volcan;

      //  Obtenemos un especimen aleatorio que no sea el actual. Si mezcla_activa esta
      // activado sale de cualquier deme, y sino, solo del suyo
      if ($mezcla_activa == 1)
      {
        $totalslots = $slots1 + $slots2 + $slots3;
        $mezcla_deme = $iddeme;
        $mezcla_slot = $idslot;
        while (($mezcla_deme == $iddeme) && ($mezcla_slot == $idslot))
        {
          $nuevoorigen = rand(1, $totalslots);
          if ($nuevoorigen > ($slots1 + $slots2))
          {
            $mezcla_deme = 3;
            $mezcla_slot = $nuevoorigen - ($slots1 + $slots2);
          } else {
            if ($nuevoorigen > ($slots1))
            {
              $mezcla_deme = 2;
              $mezcla_slot = $nuevoorigen - ($slots1);
            } else {
              $mezcla_deme = 1;
              $mezcla_slot = $nuevoorigen;
            }
          }
        }
      } else {
        // Si la mezcla no esta activa, solo coge un rand dentro de su deme
        $mezcla_slot = $idslot;
        $mezcla_deme = $iddeme;
        while ($mezcla_slot == $idslot)
        {
          if ($iddeme == 1)
          {
            $mezcla_slot = rand(1,$slots1);
          }
          if ($iddeme == 2)
          {
            $mezcla_slot = rand(1,$slots2);
          }
          if ($iddeme == 3)
          {
            $mezcla_slot = rand(1,$slots3);
          }
        } // Sale del while cuando ya no es simismo
      }

      //  Ya tenemos con quien vamos a realizar la mezcla,
      // identificado pro $mezcla_deme y $mezcla_slot respecto a
      // $iddeme y $idslot. Solo nos falta mezclarlos.



    }




  }

?>
