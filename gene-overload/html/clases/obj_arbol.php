<?php

  class Arbol
  {

    // **********************************
    //    Convertir un arbol a formato Ahnentafel desde el propio
    // **********************************

    function Desglosar($arbol, $niveles)
    {
      // Tenemos en $arbol un arbol comprimido
      // es una coleccion de chars
      // Tenemos en $niveles cuantos son

      // Vamos a sacar los nodos
      $numnodo_origen = 0;
      $numnodo_destino = 0;
      for ($nivel_actual = 1; $nivel_actual <= ($niveles - 1); $nivel_actual++)
      {

        // Cuantos nodos tiene cada nivel?
        //  1o - 1
        //  2o - 2
        //  3o - 4
        //  4o - 8
        //  5o - 16

        $total_este_nivel = pow(2, ($nivel_actual - 1));
//echo ("Estenivel: ".$total_este_nivel);
        for ($nodo_actual = 1; $nodo_actual <= $total_este_nivel; $nodo_actual++)
        {

          $opcode = ord(substr($arbol, $numnodo_origen, 1));
          $valor = ord(substr($arbol, $numnodo_origen + 1, 1));

//echo ("N:".$numnodo."O:".$opcode." V:".$valor."(to:".$numnodo_destino.")"."||");
          $array_arbol[$numnodo_destino]['opcode'] = $opcode;
          $array_arbol[$numnodo_destino]['valor'] = $valor;

          $numnodo_origen = $numnodo_origen + 2;
          $numnodo_destino = $numnodo_destino + 1;


        }
//        echo ("--");
      }


//        echo ("HH-->>> ");
      // Y ahora las hojas
      for ($hoja_actual = 1; $hoja_actual <= (pow(2,($niveles - 1))); $hoja_actual++)
      {
        $valor = ord(substr($arbol, $numnodo_origen, 1));

//echo ("RV".$hoja_actual.":".$valor."||");
        $array_arbol[$numnodo_destino]['valor'] = $valor;

        $numnodo_origen = $numnodo_origen + 1;
        $numnodo_destino = $numnodo_destino + 1;

      }

//echo "$".count($array_arbol)."$";
//echo ("<br/>");
//echo ("<br/>");

      return $array_arbol;


    }


    // **********************************
    //    Genera una hoja
    // **********************************

    function GenerarHoja()
    {
      $opcode = rand(1,9);
      return $opcode;
    }

    // **********************************
    //    Genera un nodo
    // **********************************

    function GenerarNodo()
    {

      $opcode = rand(1,20);
      switch ($opcode)
      {
        case 1:
                // JL PV
		// Comprueba si los PV son inferiores a $valor
                $valor = rand (1,60);
 		break;
	case 2:
		// JG PV
		// Comprueba si los PV son superiores a $valor
                $valor = rand (0,60);
 		break;
	case 3:
		// JL PP
		// Comprueba la proporcion entre PV actuales y maximos
                $valor = rand (0,10);
 		break;
	case 4:
		// JG PP
		// Comprueba la proporcion entre PV actuales y maximos
                $valor = rand (0,10);
 		break;
        case 5:
                // JL M
		// Comprueba si el mana es inferior a $valor
                $valor = rand (1,55);
 		break;
	case 6:
		// JG M
		// Comprueba si el mana es superior a $valor
                $valor = rand (0,55);
 		break;
	case 7:
		// JL MP
		// Comprueba la proporcion entre Mana actual y maximo
                $valor = rand (0,10);
 		break;
	case 8:
		// JG PP
		// Comprueba la proporcion entre Mana actual y maximo
                $valor = rand (0,10);
 		break;
	case 9:
                // JL EPV
		// Comprueba si los PV del advresario (estimados) son inferiores a $valor
                $valor = rand (1,60);
 		break;
	case 10:
		// JG EPV
		// Comprueba si los PV del adversario (estimados) son superiores a $valor
                $valor = rand (0,60);
 		break;
	case 11:
		// JE UAE
		// Ultima accion del enemigo
                $valor = rand (1,9);   // 1 al 9
 		break;
	case 12:
		// JNE UAE
		// Ultima accion del enemigo
                $valor = rand (1,9);   // 1 al 9!! esto se pensó como 1 al 6 pero mejor saber q hechizo exactamente
 		break;
	case 13:
		// JE UAP
		// Ultima accion propia
                $valor = rand (1,9);
 		break;
	case 14:
		// JNE UAP
		// Ultima accion propia
                $valor = rand (1,9);
 		break;
	case 15:
		// JE EE
		// Estado del enemigo
                $valor = rand (1,8);
 		break;
	case 16:
		// JNE EE
		// Estado del enemigo
                $valor = rand (1,8);
 		break;
	case 17:
		// JE EP
		// Estado del enemigo
                $valor = rand (1,8);
 		break;
	case 18:
		// JNE EP
		// Estado del enemigo
                $valor = rand (1,8);
 		break;
	case 19:
		// JL T
		// Turno
                $valor = rand (1,80);
 		break;
	case 20:
		// JG T
		// Turno
                $valor = rand (1,80);
 		break;


      }


      $nodo[1] = $opcode;
      $nodo[2] = $valor;
      return $nodo;

    }

    // ***************************************************
    //   Generamos un arbol totalmente vacio con $niveles de profundidad
    // ***************************************************

    function GenerarArbolInicial($niveles)
    {

      // Generacion de un arbol totalmente aleatorio con $niveles de profundidad

      $nivel_actual = 1;
      $string_arbol = '';

  
//echo ("MIERDA ".$niveles);
    // Este for afecta a todos los niveles de nodos
      for ($nivel_actual = 1; $nivel_actual <= ($niveles - 1); $nivel_actual++)
      {

        // Cuantos nodos tiene cada nivel?
        //  1o - 1
        //  2o - 2
        //  3o - 4
        //  4o - 8
        //  5o - 16

        $total_este_nivel = pow(2, ($nivel_actual - 1)); // ($nivel_actual - 1) * 2;
//echo ("Nivel ".$nivel_actual." : ".$total_este_nivel."---");
//        if ($nivel_actual == 1) { $total_este_nivel = 1; }
//        for ($nodo_actual = 1; $nodo_actual <= ($nivel_actual * 2); $nodo_actual++)
        for ($nodo_actual = 1; $nodo_actual <= $total_este_nivel; $nodo_actual++)
        {
          // Creamos un nodo aqui
          $nodo = $this->GenerarNodo();
          $string_arbol = $string_arbol.chr($nodo[1]).chr($nodo[2]);
        }

      }

      // Y ahora las hojas
//      for ($hoja_actual = 1; $hoja_actual <= ($niveles * 2); $hoja_actual++)
      for ($hoja_actual = 1; $hoja_actual <= pow(2, $niveles); $hoja_actual++)
      {
        $hoja = $this->GenerarHoja();
        $string_arbol = $string_arbol.chr($hoja);
      }

      // Devolvemos el arbol en formato comprimido
      return $string_arbol;

    }

  }



?>
