<?php

  class Combate
  {


    // Para poder usarlo en el opcode decisorio
    var $total_turnos;

    // Cada uno de estos sera un array con [caracteristica] y [id]
    //  [rapidez, inteligencia, fuerza, constitucion, percepcion, sabiduria, id, iddeme
    var $contrincante1;
    var $contrincante2;

    // Aqui vamos a meter el arbol desglosado en formato Ahnentafel
    var $arbol1;
    var $arbol2;

    var $debug_mode;


    var $debug_premium;
    // Esto es un array que se va rellenando si $debug_premium = 1
    var $array_debug_premium;


  // ***********************************
  //    Constructor
  // **********************************
  //  Inicializa una clase tipo combate

  function __construct($debug_mode){

//echo ("#".$debug_mode);
    $this->debug_mode = $debug_mode;

  }



    // ***********************************************
    //   Elegimos la accion a realizar
    // ***********************************************

    function EligeAccion($ejecutor)
    {

      if ($ejecutor == 1)
      {
        $arbol_propio = $this->arbol1;
        $contrincante_yo = $this->contrincante1;
        $contrincante_otro = $this->contrincante2;
      } else {
        $arbol_propio = $this->arbol2;
        $contrincante_yo = $this->contrincante2;
        $contrincante_otro = $this->contrincante1;
      }

      if ($this->debug_mode == 1)
      {
	echo ("<br/><br/>Arbol a ser evaluado: ");
	print_r($arbol_propio);
	echo ("<br/><br/>");
      }

      $lugar_actual = 0;
      $nivel_actual = 1;

      // Cada arbol tiene nivel_maximo = $this->contrincanteX['niveles_arbol']
      $nivel_maximo = $contrincante_yo['niveles_arbol'];

      if ($this->debug_mode == 1)
      {
        echo "Recorrido: (Nivel ".$nivel_actual." de ".$nivel_maximo." max, nodo ".$lugar_actual.")";
      }

      $decision_tomada = 0;

      while ($decision_tomada == 0)
      {
        $opcode_actual = $arbol_propio[$lugar_actual]['opcode'];
        $valor_actual = $arbol_propio[$lugar_actual]['valor'];

        if ($this->debug_mode == 1)
        {
          echo (" [OP:".$opcode_actual.",V:".$valor_actual."]");
          echo ("(lugar ".$lugar_actual.")");
        }

//echo $opcode_actual;
        switch($opcode_actual)
	{
 		case 1:
			// JL PV,, salta si los PV son menores a $valor_actual
//			if ($contrincante_yo['PVmax'] < $valor_actual)
			if ($contrincante_yo['PV'] < $valor_actual)
                        {
			  // Lugar actual = 2i+1
                          $lugar_actual = (2*$lugar_actual)+1;
                        } else {
			  // Lugar actual = 2i+2
                          $lugar_actual = (2*$lugar_actual)+2;
                        }
			break;
 		case 2:
			// JG PV,, salta si los PV son mayores a $valor_actual
//			if ($contrincante_yo['PVmax'] > $valor_actual)
			if ($contrincante_yo['PV'] > $valor_actual)
                        {
			  // Lugar actual = 2i+1
                          $lugar_actual = (2*$lugar_actual)+1;
                        } else {
			  // Lugar actual = 2i+2
                          $lugar_actual = (2*$lugar_actual)+2;
                        }
			break;
		case 3:
			// JL PP,, salta si la proporcion entre los PV maximos y los actuales es menor a $valor_actual
                        $proporcion = floor(($contrincante_yo['PV'] / $contrincante_yo['PVmax']) * 10);
                        if ($proporcion < $valor_actual)
			{
//echo ("[JL PP si]");
			  // Lugar actual = 2i+1
                          $lugar_actual = (2*$lugar_actual)+1;
                        } else {
//echo ("[JL PP no ".$valor_actual."]");
			  // Lugar actual = 2i+2
                          $lugar_actual = (2*$lugar_actual)+2;
			}
			break;
		case 4:
			// JG PP,, salta si la proporcion entre los PV maximos y los actuales es mayor a $valor_actual
                        $proporcion = floor(($contrincante_yo['PV'] / $contrincante_yo['PVmax']) * 10);
                        if ($proporcion > $valor_actual)
			{
//echo ("[JG PP si]");
			  // Lugar actual = 2i+1
                          $lugar_actual = (2*$lugar_actual)+1;
                        } else {
//echo ("[JG PP no]");
			  // Lugar actual = 2i+2
                          $lugar_actual = (2*$lugar_actual)+2;
			}
			break;
		case 5:
                        // JL PM,, salta si los PM son menores a $valor_actual
//                        if ($contrincante_yo['PMmax'] < $valor_actual)
                        if ($contrincante_yo['PM'] < $valor_actual)
                        {
                          // Lugar actual = 2i+1
                          $lugar_actual = (2*$lugar_actual)+1;
                        } else {
                          // Lugar actual = 2i+2
                          $lugar_actual = (2*$lugar_actual)+2;
                        }
			break;
		case 6:
                        // JG PM,, salta si los PM son menores a $valor_actual
//                        if ($contrincante_yo['PMmax'] > $valor_actual)
                        if ($contrincante_yo['PM'] > $valor_actual)
                        {
                          // Lugar actual = 2i+1
                          $lugar_actual = (2*$lugar_actual)+1;
                        } else {
                          // Lugar actual = 2i+2
                          $lugar_actual = (2*$lugar_actual)+2;
                        }
			break;
		case 7:
			// JL M,, salta si la proporcion entre los PM maximos y el mana actual es menor a $valor_actual
                        $proporcion = floor(($contrincante_yo['PM'] / $contrincante_yo['PMmax']) * 10);
                        if ($proporcion < $valor_actual)
			{
			  // Lugar actual = 2i+1
                          $lugar_actual = (2*$lugar_actual)+1;
                        } else {
			  // Lugar actual = 2i+2
                          $lugar_actual = (2*$lugar_actual)+2;
			}
			break;
		case 8:
			// JG M,, salta si la proporcion entre los PM maximos y el mana actual es mayor a $valor_actual
                        $proporcion = floor(($contrincante_yo['PM'] / $contrincante_yo['PMmax']) * 10);
                        if ($proporcion > $valor_actual)
			{
			  // Lugar actual = 2i+1
                          $lugar_actual = (2*$lugar_actual)+1;
                        } else {
			  // Lugar actual = 2i+2
                          $lugar_actual = (2*$lugar_actual)+2;
			}
			break;
		case 9:
			//  JL EPV. Estima los PV del adversario con percepcion+sabiduria.
			//  Calculamos sesgo = (20 - (percepcion + sabiduria)), y sacamos un
			// rand (0, Sesgo) si este es > 0.
                        //  Esto se lo vamos a anyadir o quitar a los verdaderos PV actuales del
                        // adversario, y ese es el valor que vamos a tener en cuenta en la condicion
                        $sesgo = (20 - ($contrincante_otro['percepcion'] + $contrincante_otro['sabiduria']));
                        if ($sesgo < 0)
			{
			  $sesgo = 0;
                          $valor_estimado = $contrincante_otro['PV'];
			} else {
			  $sesgo_real = rand(0, $sesgo);
			  $sumaresta = rand(0, 1);
                          if ($sumaresta == 1)
                          {
			    $valor_estimado = $contrincante_otro['PV'] + $sesgo_real;
			  } else {
			    $valor_estimado = $contrincante_otro['PV'] - $sesgo_real;
			  }
			}
                        //  Ahora tenemos en $valor_estimado lo que creemos que le queda de vida a nuestro enemigo.
			//  Es menor a lo que pensamos en la condicion?
                        if ($valor_estimado < $valor_actual)
                        {
			  // Lugar actual = 2i+1
                          $lugar_actual = (2*$lugar_actual)+1;
                        } else {
			  // Lugar actual = 2i+2
                          $lugar_actual = (2*$lugar_actual)+2;
                        }
			break;

		case 10:
			//  JG EPV. Estima los PV del adversario con percepcion+sabiduria.
			//  Calculamos sesgo = (20 - (percepcion + sabiduria)), y sacamos un
			// rand (0, Sesgo) si este es > 0.
                        //  Esto se lo vamos a anyadir o quitar a los verdaderos PV actuales del
                        // adversario, y ese es el valor que vamos a tener en cuenta en la condicion
                        $sesgo = (20 - ($contrincante_otro['percepcion'] + $contrincante_otro['sabiduria']));
                        if ($sesgo < 0)
			{
			  $sesgo = 0;
                          $valor_estimado = $contrincante_otro['PV'];
			} else {
			  $sesgo_real = rand(0, $sesgo);
			  $sumaresta = rand(0, 1);
                          if ($sumaresta == 1)
                          {
			    $valor_estimado = $contrincante_otro['PV'] + $sesgo_real;
			  } else {
			    $valor_estimado = $contrincante_otro['PV'] - $sesgo_real;
			  }
			}
                        //  Ahora tenemos en $valor_estimado lo que creemos que le queda de vida a nuestro enemigo.
			//  Es menor a lo que pensamos en la condicion?
                        if ($valor_estimado > $valor_actual)
                        {
			  // Lugar actual = 2i+1
                          $lugar_actual = (2*$lugar_actual)+1;
                        } else {
			  // Lugar actual = 2i+2
                          $lugar_actual = (2*$lugar_actual)+2;
                        }
			break;



		case 11:
			// JE UAE, salta si coincide con la accion del enemigo
                        if ($contrincante_otro['UAP'] == $valor_actual)
                        {
			  // Lugar actual = 2i+1
                          $lugar_actual = (2*$lugar_actual)+1;
                        } else {
			  // Lugar actual = 2i+2
                          $lugar_actual = (2*$lugar_actual)+2;
                        }
			break;

		case 12:
			// JNE UAE, salta si no coincide con la accion del enemigo
                        if ($contrincante_otro['UAP'] != $valor_actual)
                        {
			  // Lugar actual = 2i+1
                          $lugar_actual = (2*$lugar_actual)+1;
                        } else {
			  // Lugar actual = 2i+2
                          $lugar_actual = (2*$lugar_actual)+2;
                        }
			break;

		case 13:
			// JE UAP, salta si coincide con la accion propia
                        if ($contrincante_yo['UAP'] == $valor_actual)
                        {
			  // Lugar actual = 2i+1
                          $lugar_actual = (2*$lugar_actual)+1;
                        } else {
			  // Lugar actual = 2i+2
                          $lugar_actual = (2*$lugar_actual)+2;
                        }
			break;

		case 14:
			// JNE UAP, salta si no coincide con la accion propia
                        if ($contrincante_yo['UAP'] != $valor_actual)
                        {
			  // Lugar actual = 2i+1
                          $lugar_actual = (2*$lugar_actual)+1;
                        } else {
			  // Lugar actual = 2i+2
                          $lugar_actual = (2*$lugar_actual)+2;
                        }
			break;



		case 15:
			 // JE sobre Estado del Enemigo
                         // Cada estado tiene su condicion
                         switch ($valor_actual)
                         {
                            case 1:  // 1 : salta si aturdido
                                if ($contrincante_otro['aturdido'] > 0)
                                {
       	                          // Lugar actual = 2i+1
                                  $lugar_actual = (2*$lugar_actual)+1;
                                } else {
 	                          // Lugar actual = 2i+2
                                  $lugar_actual = (2*$lugar_actual)+2;
                                }
                                break;
                            case 2:  // 2 : salta si aturdido sin parar
                                if ($contrincante_otro['aturdido_sin_parar'] > 0)
                                {
       	                          // Lugar actual = 2i+1
                                  $lugar_actual = (2*$lugar_actual)+1;
                                } else {
 	                          // Lugar actual = 2i+2
                                  $lugar_actual = (2*$lugar_actual)+2;
                                }
                                break;
                            case 3:  // 3 : salta si emboscado
                                if ($contrincante_otro['emboscado'] > 0)
                                {
       	                          // Lugar actual = 2i+1
                                  $lugar_actual = (2*$lugar_actual)+1;
                                } else {
 	                          // Lugar actual = 2i+2
                                  $lugar_actual = (2*$lugar_actual)+2;
                                }
                                break;
                            case 4:  // 4 : salta si esta mejorado por miembros ardientes
                                if ($contrincante_otro['hechizo_v_3_duracion'] > 0)
                                {
       	                          // Lugar actual = 2i+1
                                  $lugar_actual = (2*$lugar_actual)+1;
                                } else {
 	                          // Lugar actual = 2i+2
                                  $lugar_actual = (2*$lugar_actual)+2;
                                }
                                break;
                            case 5:  // 5 : salta si esta mejorado por invulnerable
                                if ($contrincante_otro['hechizo_v_4_duracion'] > 0)
                                {
       	                          // Lugar actual = 2i+1
                                  $lugar_actual = (2*$lugar_actual)+1;
                                } else {
 	                          // Lugar actual = 2i+2
                                  $lugar_actual = (2*$lugar_actual)+2;
                                }
                                break;
                            case 6:  // 6 : salta si esta mejorado por rapidez
                                if ($contrincante_otro['hechizo_v_2_duracion'] > 0)
                                {
       	                          // Lugar actual = 2i+1
                                  $lugar_actual = (2*$lugar_actual)+1;
                                } else {
 	                          // Lugar actual = 2i+2
                                  $lugar_actual = (2*$lugar_actual)+2;
                                }
                                break;
                            case 7:  // 7 : salta si esta mejorado por maldicion
                                if ($contrincante_otro['hechizo_p_4_duracion'] > 0)
                                {
       	                          // Lugar actual = 2i+1
                                  $lugar_actual = (2*$lugar_actual)+1;
                                } else {
 	                          // Lugar actual = 2i+2
                                  $lugar_actual = (2*$lugar_actual)+2;
                                }
                                break;
                            case 8:  // 8 : salta si esta lanzado el ultimate
                                if ($contrincante_otro['ultimate_lanzado'] > 0)
                                {
       	                          // Lugar actual = 2i+1
                                  $lugar_actual = (2*$lugar_actual)+1;
                                } else {
 	                          // Lugar actual = 2i+2
                                  $lugar_actual = (2*$lugar_actual)+2;
                                }
                                break;


                         }
 			 break;

		case 16:
			 // JNE sobre Estado del Enemigo
                         // Cada estado tiene su condicion
                         switch ($valor_actual)
                         {
                            case 1:  // 1 : salta si aturdido
                                if ($contrincante_otro['aturdido'] == 0)
                                {
       	                          // Lugar actual = 2i+1
                                  $lugar_actual = (2*$lugar_actual)+1;
                                } else {
 	                          // Lugar actual = 2i+2
                                  $lugar_actual = (2*$lugar_actual)+2;
                                }
                                break;
                            case 2:  // 2 : salta si aturdido sin parar
                                if ($contrincante_otro['aturdido_sin_parar'] == 0)
                                {
       	                          // Lugar actual = 2i+1
                                  $lugar_actual = (2*$lugar_actual)+1;
                                } else {
 	                          // Lugar actual = 2i+2
                                  $lugar_actual = (2*$lugar_actual)+2;
                                }
                                break;
                            case 3:  // 3 : salta si emboscado
                                if ($contrincante_otro['emboscado'] == 0)
                                {
       	                          // Lugar actual = 2i+1
                                  $lugar_actual = (2*$lugar_actual)+1;
                                } else {
 	                          // Lugar actual = 2i+2
                                  $lugar_actual = (2*$lugar_actual)+2;
                                }
                                break;
                            case 4:  // 4 : salta si esta mejorado por miembros ardientes
                                if ($contrincante_otro['hechizo_v_3_duracion'] == 0)
                                {
       	                          // Lugar actual = 2i+1
                                  $lugar_actual = (2*$lugar_actual)+1;
                                } else {
 	                          // Lugar actual = 2i+2
                                  $lugar_actual = (2*$lugar_actual)+2;
                                }
                                break;
                            case 5:  // 5 : salta si esta mejorado por invulnerable
                                if ($contrincante_otro['hechizo_v_4_duracion'] == 0)
                                {
       	                          // Lugar actual = 2i+1
                                  $lugar_actual = (2*$lugar_actual)+1;
                                } else {
 	                          // Lugar actual = 2i+2
                                  $lugar_actual = (2*$lugar_actual)+2;
                                }
                                break;
                            case 6:  // 6 : salta si esta mejorado por rapidez
                                if ($contrincante_otro['hechizo_v_2_duracion'] == 0)
                                {
       	                          // Lugar actual = 2i+1
                                  $lugar_actual = (2*$lugar_actual)+1;
                                } else {
 	                          // Lugar actual = 2i+2
                                  $lugar_actual = (2*$lugar_actual)+2;
                                }
                                break;
                            case 7:  // 7 : salta si esta mejorado por maldicion
                                if ($contrincante_otro['hechizo_p_4_duracion'] == 0)
                                {
       	                          // Lugar actual = 2i+1
                                  $lugar_actual = (2*$lugar_actual)+1;
                                } else {
 	                          // Lugar actual = 2i+2
                                  $lugar_actual = (2*$lugar_actual)+2;
                                }
                                break;
                            case 8:  // 8 : salta si esta lanzado el ultimate
                                if ($contrincante_otro['ultimate_lanzado'] == 0)
                                {
       	                          // Lugar actual = 2i+1
                                  $lugar_actual = (2*$lugar_actual)+1;
                                } else {
 	                          // Lugar actual = 2i+2
                                  $lugar_actual = (2*$lugar_actual)+2;
                                }
                                break;

                         }
 			 break;


		case 17:
//echo ("JE QUE PASA");
			 // JE sobre Estado Propio
                         // Cada estado tiene su condicion
                         switch ($valor_actual)
                         {
                            case 1:  // 1 : salta si aturdido
                                if ($contrincante_yo['aturdido'] > 0)
                                {
       	                          // Lugar actual = 2i+1
                                  $lugar_actual = (2*$lugar_actual)+1;
                                } else {
 	                          // Lugar actual = 2i+2
                                  $lugar_actual = (2*$lugar_actual)+2;
                                }
                                break;
                            case 2:  // 2 : salta si aturdido sin parar
                                if ($contrincante_yo['aturdido_sin_parar'] > 0)
                                {
       	                          // Lugar actual = 2i+1
                                  $lugar_actual = (2*$lugar_actual)+1;
                                } else {
 	                          // Lugar actual = 2i+2
                                  $lugar_actual = (2*$lugar_actual)+2;
                                }
                                break;
                            case 3:  // 3 : salta si emboscado
                                if ($contrincante_yo['emboscado'] > 0)
                                {
       	                          // Lugar actual = 2i+1
                                  $lugar_actual = (2*$lugar_actual)+1;
                                } else {
 	                          // Lugar actual = 2i+2
                                  $lugar_actual = (2*$lugar_actual)+2;
                                }
                                break;
                            case 4:  // 4 : salta si esta mejorado por miembros ardientes
                                if ($contrincante_yo['hechizo_v_3_duracion'] > 0)
                                {
       	                          // Lugar actual = 2i+1
                                  $lugar_actual = (2*$lugar_actual)+1;
                                } else {
 	                          // Lugar actual = 2i+2
                                  $lugar_actual = (2*$lugar_actual)+2;
                                }
                                break;
                            case 5:  // 5 : salta si esta mejorado por invulnerable
                                if ($contrincante_yo['hechizo_v_4_duracion'] > 0)
                                {
       	                          // Lugar actual = 2i+1
                                  $lugar_actual = (2*$lugar_actual)+1;
                                } else {
 	                          // Lugar actual = 2i+2
                                  $lugar_actual = (2*$lugar_actual)+2;
                                }
                                break;
                            case 6:  // 6 : salta si esta mejorado por rapidez
                                if ($contrincante_yo['hechizo_v_2_duracion'] > 0)
                                {
       	                          // Lugar actual = 2i+1
                                  $lugar_actual = (2*$lugar_actual)+1;
                                } else {
 	                          // Lugar actual = 2i+2
                                  $lugar_actual = (2*$lugar_actual)+2;
                                }
                                break;
                            case 7:  // 7 : salta si esta mejorado por maldicion
                                if ($contrincante_yo['hechizo_p_4_duracion'] > 0)
                                {
       	                          // Lugar actual = 2i+1
                                  $lugar_actual = (2*$lugar_actual)+1;
                                } else {
 	                          // Lugar actual = 2i+2
                                  $lugar_actual = (2*$lugar_actual)+2;
                                }
                                break;
                            case 8:  // 8 : salta si esta lanzado el ultimate
                                if ($contrincante_yo['ultimate_lanzado'] > 0)
                                {
       	                          // Lugar actual = 2i+1
                                  $lugar_actual = (2*$lugar_actual)+1;
                                } else {
 	                          // Lugar actual = 2i+2
                                  $lugar_actual = (2*$lugar_actual)+2;
                                }
                                break;


                         }
 			 break;


		case 18:
			 // JNE sobre Estado del Enemigo
                         // Cada estado tiene su condicion
                         switch ($valor_actual)
                         {
                            case 1:  // 1 : salta si aturdido
                                if ($contrincante_yo['aturdido'] == 0)
                                {
       	                          // Lugar actual = 2i+1
                                  $lugar_actual = (2*$lugar_actual)+1;
                                } else {
 	                          // Lugar actual = 2i+2
                                  $lugar_actual = (2*$lugar_actual)+2;
                                }
                                break;
                            case 2:  // 2 : salta si aturdido sin parar
                                if ($contrincante_yo['aturdido_sin_parar'] == 0)
                                {
       	                          // Lugar actual = 2i+1
                                  $lugar_actual = (2*$lugar_actual)+1;
                                } else {
 	                          // Lugar actual = 2i+2
                                  $lugar_actual = (2*$lugar_actual)+2;
                                }
                                break;
                            case 3:  // 3 : salta si emboscado
                                if ($contrincante_yo['emboscado'] == 0)
                                {
       	                          // Lugar actual = 2i+1
                                  $lugar_actual = (2*$lugar_actual)+1;
                                } else {
 	                          // Lugar actual = 2i+2
                                  $lugar_actual = (2*$lugar_actual)+2;
                                }
                                break;
                            case 4:  // 4 : salta si esta mejorado por miembros ardientes
                                if ($contrincante_yo['hechizo_v_3_duracion'] == 0)
                                {
       	                          // Lugar actual = 2i+1
                                  $lugar_actual = (2*$lugar_actual)+1;
                                } else {
 	                          // Lugar actual = 2i+2
                                  $lugar_actual = (2*$lugar_actual)+2;
                                }
                                break;
                            case 5:  // 5 : salta si esta mejorado por invulnerable
                                if ($contrincante_yo['hechizo_v_4_duracion'] == 0)
                                {
       	                          // Lugar actual = 2i+1
                                  $lugar_actual = (2*$lugar_actual)+1;
                                } else {
 	                          // Lugar actual = 2i+2
                                  $lugar_actual = (2*$lugar_actual)+2;
                                }
                                break;
                            case 6:  // 6 : salta si esta mejorado por rapidez
                                if ($contrincante_yo['hechizo_v_2_duracion'] == 0)
                                {
       	                          // Lugar actual = 2i+1
                                  $lugar_actual = (2*$lugar_actual)+1;
                                } else {
 	                          // Lugar actual = 2i+2
                                  $lugar_actual = (2*$lugar_actual)+2;
                                }
                                break;
                            case 7:  // 7 : salta si esta mejorado por maldicion
                                if ($contrincante_yo['hechizo_p_4_duracion'] == 0)
                                {
       	                          // Lugar actual = 2i+1
                                  $lugar_actual = (2*$lugar_actual)+1;
                                } else {
 	                          // Lugar actual = 2i+2
                                  $lugar_actual = (2*$lugar_actual)+2;
                                }
                                break;
                            case 8:  // 8 : salta si esta lanzado el ultimate
                                if ($contrincante_yo['ultimate_lanzado'] == 0)
                                {
       	                          // Lugar actual = 2i+1
                                  $lugar_actual = (2*$lugar_actual)+1;
                                } else {
 	                          // Lugar actual = 2i+2
                                  $lugar_actual = (2*$lugar_actual)+2;
                                }
                                break;

                         }
 			 break;


		case 19:
			// JL T, salta si el turno es menor a lo del opcode
                        if ($this->total_turnos < $valor_actual)
                        {
			  // Lugar actual = 2i+1
                          $lugar_actual = (2*$lugar_actual)+1;
                        } else {
			  // Lugar actual = 2i+2
                          $lugar_actual = (2*$lugar_actual)+2;
                        }
			break;

		case 20:
			// JG T, salta si el turno es menor a lo del opcode
                        if ($this->total_turnos > $valor_actual)
                        {
			  // Lugar actual = 2i+1
                          $lugar_actual = (2*$lugar_actual)+1;
                        } else {
			  // Lugar actual = 2i+2
                          $lugar_actual = (2*$lugar_actual)+2;
                        }
			break;


		default:
			  // TEMPORAL : Lugar actual = 2i+1
                        $lugar_actual = (2*$lugar_actual)+1;
                        if ($this->debug_mode == 1)
                        {
		  	  echo ("ERROR");
                        }
			break;
	} // Cierra el switch

        $nivel_actual = $nivel_actual + 1;


        if ($this->debug_mode == 1)
        {
          echo ("Opcode actual: ".$opcode_actual.",");
          echo "( nivel actual ".$nivel_actual." de ".$nivel_maximo.",, lugar actual: ".$lugar_actual.")";
        }

        // Si llegamos al nivel ultimo, hemos tomado una decision
        if ($nivel_actual >= $nivel_maximo)
        {
          $decision_tomada = 1;
          // Tomamos la decision
          $decision_final = $arbol_propio[$lugar_actual]['valor'];
          if ($this->debug_mode == 1)
          {
            echo (" Decision tomada : [lugar ".$lugar_actual."],, decision : ".$decision_final);
          }


//echo $lugar_actual."#";
          // Guardamos para luego el debug premium
          if ($ejecutor == 1)
          {
              $this->contrincante1['hoja'] = $lugar_actual;
          } else {
              $this->contrincante2['hoja'] = $lugar_actual;
          }



        }


      } // Cierra el while



      //  Graba la decision como la ultima tomada por este. 
      if ($ejecutor == 1)
      {
        $this->contrincante1['UAP'] = $decision_final;
      } else {
        $this->contrincante2['UAP'] = $decision_final;
      }


      // Devuelve la decision final, que es lo que importa
      return $decision_final;


    } // Cierra la funcion




    // ***********************************************
    //   Usuario ejecuta una accion
    // ***********************************************

    function EjecutaAccion($ejecutor, $n_accion)
    {

      // Para poder operar mejor uno u otro, pasamos los arrays
      if ($ejecutor == 1)
      {
        $arbol_propio = $this->arbol1;
        $contrincante_yo = $this->contrincante1;
        $contrincante_otro = $this->contrincante2;

      } else {
        $arbol_propio = $this->arbol2;
        $contrincante_yo = $this->contrincante2;
        $contrincante_otro = $this->contrincante1;
      }

      // Esta activa la maldicion? A todo lo que necesite una caracteristica, hay que restarle $maldito_xx
      $maldito_yo = 0;
      $maldito_otro = 0;
      if ($contrincante_yo['hechizo_p_4_duracion'] > 0)
      {
        $maldito_yo = 2;
      }
      if ($contrincante_otro['hechizo_p_4_duracion'] > 0)
      {
        $maldito_otro = 2;
      }

      // Si esta emboscado, el sumador al danyo es la diferencia de tiradas * 2
      $sumador_emboscado = 0;
      if ($contrincante_yo['emboscado'] > 0)
      {
        $sumador_emboscado = ($contrincante_yo['emboscado'] * 2);  // Lo hemos pasado de *3 a *2 por ser excesivo        
      }

      // Vamos a usar el que exista el hechizo de "Miembros ardientes" como danyo que se suma a $sumador_emboscado
      if ($contrincante_yo['hechizo_v_3_duracion'] > 0)
      {
        $sumador_emboscado = $sumador_emboscado + 2;
      }
      $sumador_emboscado = $sumador_emboscado / 10; // Porque no va a ser sumador sino multiplicador
      
      if ($this->debug_mode == 1)
      {
        echo ("<br/>Al inicio del turno, el sumador de emboscado es ".$sumador_emboscado);
      }
 


      switch($n_accion)
      {
	case 1:
                // *************************************************
                //                 ATACAR SIN ARMAS
                // *************************************************
                // Si estas aturdido, no puedes, asi que pierdes el turno
                if (($contrincante_yo['aturdido'] > 0) || ($contrincante_yo['aturdido_sin_parar'] > 0))
                {
                  if ($this->debug_mode == 1)
                  {
                    echo ("<br/><u>Aturdido intentando atacar, pierde el turno</u>");
                  }
                  return;
                }

                // La fuerza/2 (fuerza-2 si estas maldito) + 1d6
		          $pupa = (($contrincante_yo['fuerza'] - $maldito_yo) / 2) + rand(1,4);
		          $pupa = $pupa + ($pupa * $sumador_emboscado);

                if ($contrincante_otro['parar'] == 1)
                {
                  $cant_parar = ($contrincante_otro['fuerza'] - $maldito_otro) + rand(1,10);
                  $pupa = $pupa - $cant_parar;
		  if ($pupa < 0) { $pupa = 0; }
                  if ($this->debug_mode == 1)
                  {
                    echo ("<br/><u>Contrincante para con ".$cant_parar.", dejandolo en ".$pupa."</u>");
                  }
                  if ($ejecutor == 1)
                  {
                    if ($this->debug_mode == 1)
                    {
                      echo ("<br/><u>Contrincante 1 queda aturdido</u>");
                    }
                    $this->contrincante1['aturdido'] = $this->contrincante1['aturdido']+2;
                  }
                  if ($ejecutor == 2)
                  {
                    if ($this->debug_mode == 1)
                    {
                      echo ("<br/><u>Contrincante 2 queda aturdido</u>");
                    }
                    $this->contrincante2['aturdido'] = $this->contrincante2['aturdido']+2;
                  }

                }

                    // Si el opositor esta emboscado, el atacante no puede hacer nada
                    if ($ejecutor == 1)
                    {
                      if ($this->contrincante2['emboscado'] > 0)
                      {
                        if ($this->debug_mode == 1)
                        {
                          echo ("<br/><u>El contrario est&aacute; emboscado, y ".$ejecutor." no lo encuentra </u>");
                        }
                        // Deshace el emboscar para sí mismo
                        $this->contrincante1['emboscado'] = 0;
                        return;
                      }
                    } else {
                      if ($this->contrincante1['emboscado'] > 0)
                      {
                        if ($this->debug_mode == 1)
                        {
                          echo ("<br/><u>El contrario est&aacute; emboscado, y ".$ejecutor." no lo encuentra </u>");
                        }
                        // Deshace el emboscar para sí mismo
                        $this->contrincante2['emboscado'] = 0;
                        return;
                      }
                    }

                if ($this->debug_mode == 1)
                {
                  echo ("<br/><u>.".$ejecutor." hiere haciendo ".$pupa."</u>");
                }

                if ($ejecutor == 1)
                {
                  if ($this->contrincante2['hechizo_v_4_duracion'] > 0)
                  {
                    if ($this->debug_mode == 1)
                    {
                      echo ("<br/><u>Contrincante tiene invulnerabilidad</u>");
                    }
                  } else {
                    if ($this->debug_mode == 1)
                    {
                      echo ("<br/><u>Contrincante pasa de ".$this->contrincante2['PV']);
                    }
                    $this->contrincante2['PV'] = ($this->contrincante2['PV'] - $pupa);
                    if ($this->debug_mode == 1)
                    {
                      echo ("a ".$this->contrincante2['PV']." PV</u>");
                    }
                    // Si te quitan de una ostia mas de 1/4 de vida, aturdido 2(1)asl, si es 1/2 el triple
                    if (($pupa / $this->contrincante2['PVmax']) > 0.33)
                    {
                      $this->contrincante2['aturdido'] = $this->contrincante2['aturdido']+2;
                      if ($this->debug_mode == 1)
                      {
                        echo ("<br/><u>Quitado 1/4 de la vida, aturdido</u>");
                      }
                    }
                    if (($pupa / $this->contrincante2['PVmax']) > 0.5)
                    {
                      $this->contrincante2['aturdido_sin_parar'] = $this->contrincante2['aturdido_sin_parar']+2;
                      if ($this->debug_mode == 1)
                      {
                        echo ("<br/><u>Quitado 1/2 de la vida, aturdido</u>");
                      }
                    }

                  }
                } else {
                  if ($this->contrincante1['hechizo_v_4_duracion'] > 0)
                  {
                    if ($this->debug_mode == 1)
                    {
                      echo ("<br/><u>Contrincante tiene invulnerabilidad</u>");
                    }
                  } else {
                    if ($this->debug_mode == 1)
                    {
                      echo ("<br/><u>Contrincante pasa de ".$this->contrincante1['PV']."</u>");
                    }
                    $this->contrincante1['PV'] = ($this->contrincante1['PV'] - $pupa);
                    if ($this->debug_mode == 1)
                    {
                      echo ("a ".$this->contrincante1['PV']." PV");
                    }
                    // Si te quitan de una ostia mas de 1/4 de vida, aturdido 2(1)asl, si es 1/2 el triple
                    if (($pupa / $this->contrincante1['PVmax']) > 0.33)
                    {
                      $this->contrincante1['aturdido'] = $this->contrincante1['aturdido']+2;
                      if ($this->debug_mode == 1)
                      {
                        echo ("<br/><u>Quitado 1/4 de la vida, aturdido</u>");
                      }
                    }
                    if (($pupa / $this->contrincante1['PVmax']) > 0.5)
                    {
                      $this->contrincante1['aturdido_sin_parar'] = $this->contrincante1['aturdido_sin_parar']+2;
//                      $this->contrincante1['aturdido'] = $this->contrincante1['aturdido']+4;
                      if ($this->debug_mode == 1)
                      {
                        echo ("<br/><u>Quitado 1/2 de la vida, aturdido</u>");
                      }
                    }

                  }
                }

                // Deshace el emboscar
                if ($ejecutor == 1)
                {
                  $this->contrincante1['emboscado'] = 0;
                } else {
                  $this->contrincante2['emboscado'] = 0;
                }
		break;

	case 2:
                // *************************************************
                //                       PARAR
                // *************************************************
                if ($contrincante_yo['aturdido_sin_parar'] > 0)
                {
                  if ($this->debug_mode == 1)
                  {
                    echo ("<br/><u>Aturdido sin parar intentando parar, pierde el turno</u>");
                  }
                  return;
                }
		// Parar al enemigo. Tan solo activamos el "parar"
                if ($this->debug_mode == 1)
                {
                  echo ("<br/><u>Parar activado</u>");
                }
                if ($ejecutor == 1)
                {
                  $this->contrincante1['parar'] = 1;
		} else {
                  $this->contrincante2['parar'] = 1;
		}
		break;

	case 3:
                // *************************************************
                //                     CURARSE
                // *************************************************
                if ($contrincante_yo['aturdido_sin_parar'] > 0)
                {
                  if ($this->debug_mode == 1)
                  {
                    echo ("<br/><u>Aturdido sin parar intentando curarse, pierde el turno</u>");
                  }
                  return;
                }


                // Ahora la curacion cuesta 1 punto de vida si no eres verde.
                if (($contrincante_yo['PM'] >= 2.5) || ($contrincante_yo['iddeme'] == 2))
                {
                  // Si es del volcan o de las profundidades, esto cuesta 2 de mana
                  if (($contrincante_yo['iddeme'] == 3) || ($contrincante_yo['iddeme'] == 1))
                  {
                    if ($ejecutor == 1)
                    {
                      $this->contrincante1['PM'] = $this->contrincante1['PM'] - 2.5;
                    } else {
                      $this->contrincante2['PM'] = $this->contrincante2['PM'] - 2.5;
                    }
                  }

  		  // Primera version curaba 5 + Sabiduría + 1d10 puntos, demasiado poco (10 de base, + sab)
		  // Segunda version curaba (Sabiduria * 2) + 3d6 puntos, demasiado mucho (9 de base + (sab * 2))
		  // Hacemos que sea (sabiduria * 1.5) + 2d6
                  if (($contrincante_yo['iddeme'] == 3) || ($contrincante_yo['iddeme'] == 1))
                  {   // Curacion con demes del volcan y profundidades
                    $cant_curar = (($contrincante_yo['sabiduria'] - $maldito_yo) * 1) + rand(1,6) + rand(1,6);
                  } else {   // Curacion con el deme del bosque
                    $cant_curar = (($contrincante_yo['sabiduria'] - $maldito_yo) * 2) + rand(1,6) + rand(1,6);
                  }
                  if ($this->debug_mode == 1)
                  {
                    echo ("<br/><u>Curacion de ".$cant_curar." PV, pasa de </u>");
                  }
                  if ($ejecutor == 1)
                  {
                    if ($this->debug_mode == 1)
                    {
                      echo ($this->contrincante1['PV']);
                    }
                    $this->contrincante1['PV'] = ($this->contrincante1['PV'] + $cant_curar);
		    // Si se pasa del maximo en puntos de vida, reducimos
                    if ($this->contrincante1['PV'] > $this->contrincante1['PVmax'])
                      {
                        $this->contrincante1['PV'] = $this->contrincante1['PVmax'];
		      }
                    if ($this->debug_mode == 1)
                    {
                      echo ("<u> a ".$this->contrincante1['PV']."</u>");
                    }
                  } else {
                    if ($this->debug_mode == 1)
                    {
                      echo ($this->contrincante2['PV']);
                    }
                    $this->contrincante2['PV'] = ($this->contrincante2['PV'] + $cant_curar);
		    // Si se pasa del maximo en puntos de vida, reducimos
                    if ($this->contrincante2['PV'] > $this->contrincante2['PVmax'])
                      {
                        $this->contrincante2['PV'] = $this->contrincante2['PVmax'];
		      }
                    if ($this->debug_mode == 1)
                    {
                    echo ("<u> a ".$this->contrincante2['PV']."</u>");
                    }
                  }

                  // Deshace el emboscar
                  if ($ejecutor == 1)
                  {
                    $this->contrincante1['emboscado'] = 0;
                  } else {
                    $this->contrincante2['emboscado'] = 0;
                  }

                } // No hay else. Si no puedes pues no puedes
                break;

	case 4:
                // ****************************
                //   HECHIZO DE NIVEL   1
                // ****************************

                // Si estas aturdido, no puedes, asi que pierdes el turno
                if (($contrincante_yo['aturdido'] > 0) || ($contrincante_yo['aturdido_sin_parar'] > 0))
                {
                  if ($this->debug_mode == 1)
                  {
                    echo ("<br/><u>Aturdido intentando lanzar hechizo N1, pierde el turno</u>");
                  }
                  return;
                }

		// ~~~~~~~~~~~~~~~~~~~ Deme de las profundidades ~~~~~~~~~~~~~~~~~~~~~
                // =-=-=-=-=-=-=-=-=-=-=-=-=-=- DOLOR -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
                //
                if ($contrincante_yo['iddeme'] == 1)
                {
                  // Dolor, causa inteligencia +1d6 danyo. Gasta 5 PM
                  if ($contrincante_yo['PM'] >= 5)
                  {

                    $dolor = (($contrincante_yo['inteligencia'] / 2) - $maldito_yo) + rand(1,6);
                    $dolor = $dolor + ($dolor * $sumador_emboscado);
                    if ($this->debug_mode == 1)
                    {
                      echo ("<br/><u>Deme de las profundidades: Dolor. Causando ".$dolor." de da&ntilde;o</u>");
                    }

                    // Si el opositor esta emboscado, el atacante no puede hacer nada
                    if ($ejecutor == 1)
                    {
                      if ($this->contrincante2['emboscado'] > 0)
                      {
                        if ($this->debug_mode == 1)
                        {
                          echo ("<br/><u>El contrario est&aacute; emboscado, y ".$ejecutor." no lo encuentra</u>");
                        }
                        // Deshace el emboscar para sí mismo
                        $this->contrincante1['emboscado'] = 0;
                        return;
                      }
                    } else {
                      if ($this->contrincante1['emboscado'] > 0)
                      {
                        if ($this->debug_mode == 1)
                        {
                          echo ("<br/><u>El contrario est&aacute; emboscado, y ".$ejecutor." no lo encuentra</u>");
                        }
                        // Deshace el emboscar para sí mismo
                        $this->contrincante2['emboscado'] = 0;
                        return;
                      }
                    }

                    if ($ejecutor == 1)
                    {
                      // Si el otro contendiente tiene activo el reflejar, el lanzador se come el danyo
                      if ($this->contrincante2['reflejar'] == 1)
                      {
                        if ($this->debug_mode == 1)
                        {
                          echo (" Reflejado!");
                        }
                        $this->contrincante2['reflejar'] = 0;
                        $this->contrincante1['PV'] = $this->contrincante1['PV'] - $dolor;
                      } else {
                        // Si el otro contendiente tiene activo contrahechizo, nadie se come el danyo
                        if ($this->contrincante2['contrahechizo'] == 1)
                        {
                          if ($this->debug_mode == 1)
                          {
  			    echo ("Contrahechizo!");
                          }
                          // Hace gastar 4 (el doble) + 3 = 7 puntos
                          $this->contrincante1['PM'] = $this->contrincante1['PM'] - 9;  // Pierde 5 + 2*2
			  // y recupera sus dos
                          $this->contrincante2['PM'] = $this->contrincante1['PM'] + 2;
			} else {
                          if ($this->contrincante2['hechizo_v_4_duracion'] > 0)
                          {
                            if ($this->debug_mode == 1)
                            {
    			      echo (" Invulnerable!");
                            }
                            $this->contrincante1['PM'] = $this->contrincante1['PM'] - 5;
                          } else {


	  		    // El hechizo sale como debiera
                            $this->contrincante1['PM'] = $this->contrincante1['PM'] - 5;
                            $this->contrincante2['PV'] = $this->contrincante2['PV'] - $dolor;

                            // Si te quitan de una ostia mas de 1/4 de vida, aturdido 2(1)asl, si es 1/2 el triple
                            if (($dolor / $this->contrincante2['PVmax']) > 0.33)
                            {
                              $this->contrincante2['aturdido'] = $this->contrincante2['aturdido']+2;
                              if ($this->debug_mode == 1)
                              {
                                echo ("<br/><u>Quitado 1/4 de la vida, aturdido</u>");
                              }
                            }
                            if (($dolor / $this->contrincante2['PVmax']) > 0.5)
                            {
//                              $this->contrincante2['aturdido'] = $this->contrincante2['aturdido']+4;
                                $this->contrincante2['aturdido_sin_parar'] = $this->contrincante2['aturdido_sin_parar']+2;
                              if ($this->debug_mode == 1)
                              {
                                echo ("<br/><u>Quitado 1/2 de la vida, aturdido</u>");
                              }
                            }

                          }
			}
                      }
                    } else {
                      // Si el otro contendiente tiene activo el reflejar, el lanzador se come el danyo
                      if ($this->contrincante1['reflejar'] == 1)
                      {
                        if ($this->debug_mode == 1)
                        {
                          echo (" Reflejado!");
                        }
                        $this->contrincante1['reflejar'] = 0;
                        $this->contrincante2['PV'] = $this->contrincante2['PV'] - $dolor;
                      } else {
                        // Si el otro contendiente tiene activo contrahechizo, nadie se come el danyo
                        if ($this->contrincante1['contrahechizo'] == 1)
                        {
                          if ($this->debug_mode == 1)
                          {
			    echo (" Contrahechizo!");
                          }
                          $this->contrincante2['PM'] = $this->contrincante2['PM'] - 9;   // Pierde 5 + 2*2
                          $this->contrincante1['PM'] = $this->contrincante1['PM'] + 2;
                        } else {
                          if ($this->contrincante1['hechizo_v_4_duracion'] > 0)
                          {
                            if ($this->debug_mode == 1)
                            {
    			      echo (" Invulnerable!");
                            }
                            $this->contrincante2['PM'] = $this->contrincante2['PM'] - 5;
                          } else {
  			    // El hechizo sale como debiera
                            $this->contrincante2['PM'] = $this->contrincante2['PM'] - 5;
                            $this->contrincante1['PV'] = $this->contrincante1['PV'] - $dolor;

                            // Si te quitan de una ostia mas de 1/4 de vida, aturdido 2(1)asl, si es 1/2 el triple
                            if (($dolor / $this->contrincante1['PVmax']) > 0.33)
                            {
                              $this->contrincante1['aturdido'] = $this->contrincante1['aturdido']+2;
                              if ($this->debug_mode == 1)
                              {
                                echo ("<br/><u>Quitado 1/4 de la vida, aturdido</u>");
                              }
                            }
                            if (($dolor / $this->contrincante1['PVmax']) > 0.5)
                            {
//                              $this->contrincante1['aturdido'] = $this->contrincante1['aturdido']+4;
                              $this->contrincante1['aturdido_sin_parar'] = $this->contrincante1['aturdido_sin_parar']+2;
                              if ($this->debug_mode == 1)
                              {
                                echo ("<br/><u>Quitado 1/2 de la vida, aturdido</u>");
                              }
                            }


                          }
                        }
                      }
                    }
                  } else {
                    if ($ejecutor == 1)
                    {
                      $this->contrincante1['PM'] = $this->contrincante1['PM'] - 5;
                    } else {
                      $this->contrincante2['PM'] = $this->contrincante2['PM'] - 5;
                    }
                    if ($this->debug_mode == 1)
                    {
                      echo ("<br/><u>Deme de las profundidades: Dolor. No tiene mana (".$contrincante_yo['PM'].") y pierde el hechizo</u>");
                    }
                  }

       		}

		// ~~~~~~~~~~~~~~~~~~~~~~~~ Deme del bosque ~~~~~~~~~~~~~~~~~~~~~~~~
                // ******************************* CURACION *****************************
		// Deme del bosque
                if ($contrincante_yo['iddeme'] == 2)
                {
                  // Curacion, cura 12 + Inteligencia + 4d6 danyo. Gasta 4 PM
                  if ($contrincante_yo['PM'] >= 4)
                  {
                    $curacion = ($contrincante_yo['inteligencia'] - $maldito_yo) + 12 + rand(1,6) + rand(1,6) + rand(1,6) + rand(1,6);
                    if ($this->debug_mode == 1)
                    {
                      echo ("<br/><u>Deme del bosque: Curar. Curando ".$curacion." de vida</u>");
                    }
                    if ($ejecutor == 1)
                    {
                      $this->contrincante1['PM'] = $this->contrincante1['PM'] - 4;
                      $this->contrincante1['PV'] = $this->contrincante1['PV'] + $curacion;
                      if ($this->contrincante1['PV'] > $this->contrincante1['PVmax'])
                      {
                        $this->contrincante1['PV'] = $this->contrincante1['PVmax'];
	              }
                    } else {
                      $this->contrincante2['PM'] = $this->contrincante2['PM'] - 4;
                      $this->contrincante2['PV'] = $this->contrincante2['PV'] + $curacion;
                      if ($this->contrincante2['PV'] > $this->contrincante2['PVmax'])
                      {
                        $this->contrincante2['PV'] = $this->contrincante2['PVmax'];
	              }
                    }
                  } else {
                    if ($ejecutor == 1)
                    {
                      $this->contrincante1['PM'] = $this->contrincante1['PM'] - 4;
                    } else {
                      $this->contrincante2['PM'] = $this->contrincante2['PM'] - 4;
                    }
                    if ($this->debug_mode == 1)
                    {
                      echo ("<br/><u>Deme del bosque: Curacion. No tiene mana (".$contrincante_yo['PM'].") y pierde el hechizo</u>");
                    }
                  }
       		}

		// ~~~~~~~~~~~~~~~~~~~~~~~~ Deme del volcan ~~~~~~~~~~~~~~~~~~~~~~~~
                // ******************************* LLAMARADA *****************************
		// Deme del volcan
                if ($contrincante_yo['iddeme'] == 3)
                {
                  // Llamarada, causa 2d6+2 danyo. Gasta 4 PM
                  if ($contrincante_yo['PM'] >= 4)
                  {

//                    $dolor = 2 + rand(1,6) + rand(1,6);
                    $dolor = rand(1,6) + rand(1,6);   // 2d6
                    $dolor = $dolor + ($dolor * $sumador_emboscado);
                    if ($this->debug_mode == 1)
                    {
                      echo ("<br/><u>Deme del volc&aacute;n: Llamarada. Causando ".$dolor." de da&ntilde;o</u>");
                    }

                    // Si el opositor esta emboscado, el atacante no puede hacer nada
                    if ($ejecutor == 1)
                    {
                      if ($this->contrincante2['emboscado'] > 0)
                      {
                        if ($this->debug_mode == 1)
                        {
                          echo ("<br/><u>El contrario est&aacute; emboscado, y ".$ejecutor." no lo encuentra</u>");
                        }
                        // Deshace el emboscar para sí mismo
                        $this->contrincante1['emboscado'] = 0;
                        return;
                      }
                    } else {
                      if ($this->contrincante1['emboscado'] > 0)
                      {
                        if ($this->debug_mode == 1)
                        {
                          echo ("<br/><u>El contrario est&aacute; emboscado, y ".$ejecutor." no lo encuentra</u>");
                        }
                        // Deshace el emboscar para sí mismo
                        $this->contrincante2['emboscado'] = 0;
                        return;
                      }
                    }

                    if ($ejecutor == 1)
                    {
                      // Si el otro contendiente tiene activo el reflejar, el lanzador se come el danyo
                      if ($this->contrincante2['reflejar'] == 1)
                      {
                        if ($this->debug_mode == 1)
                        {
                          echo (" Reflejado!");
                        }
                        $this->contrincante2['reflejar'] = 0;
                        $this->contrincante1['PV'] = $this->contrincante1['PV'] - $dolor;
                      } else {
                        // Si el otro contendiente tiene activo contrahechizo, nadie se come el danyo, pierde 8, el otro recupera 2
                        if ($this->contrincante2['contrahechizo'] == 1)
                        {
                          if ($this->debug_mode == 1)
                          {
  			    echo (" Contrahechizo!");
                          }
                          $this->contrincante1['PM'] = $this->contrincante1['PM'] - 8;
                          $this->contrincante2['PM'] = $this->contrincante2['PM'] + 2;
			} else {
                          if ($this->contrincante2['hechizo_v_4_duracion'] > 0)
                          {
                            if ($this->debug_mode == 1)
                            {
  			      echo (" Invulnerable!");
                            }
                            $this->contrincante1['PM'] = $this->contrincante1['PM'] - 4;
                          } else {
                            $this->contrincante1['PM'] = $this->contrincante1['PM'] - 4;
                            $this->contrincante2['PV'] = $this->contrincante2['PV'] - $dolor;
                            // Si te quitan de una ostia mas de 1/4 de vida, aturdido 2(1)asl, si es 1/2 el triple
                            if (($dolor / $this->contrincante2['PVmax']) > 0.33)
                            {
                              $this->contrincante2['aturdido'] = $this->contrincante2['aturdido']+2;
                              if ($this->debug_mode == 1)
                              {
                                echo ("<br/><u>Quitado 1/4 de la vida, aturdido</u>");
                              }
                            }
                            if (($dolor / $this->contrincante2['PVmax']) > 0.5)
                            {
//                              $this->contrincante2['aturdido'] = $this->contrincante2['aturdido']+4;
                              $this->contrincante2['aturdido_sin_parar'] = $this->contrincante2['aturdido_sin_parar']+2;
                              if ($this->debug_mode == 1)
                              {
                                echo ("<br/><u>Quitado 1/2 de la vida, aturdido</u>");
                              }
                            }

                          }
			}
                      }
                    } else {
                      if ($this->contrincante1['reflejar'] == 1)
                      {
                        if ($this->debug_mode == 1)
                        {
                          echo (" Reflejado!");
                        }
                        $this->contrincante1['reflejar'] = 0;
                        $this->contrincante2['PV'] = $this->contrincante2['PV'] - $dolor;
                      } else {
                        // Si el otro contendiente tiene activo contrahechizo, nadie se come el danyo, pierde 8, el otro recupera 2
                        if ($this->contrincante1['contrahechizo'] == 1)
                        {
                          if ($this->debug_mode == 1)
                          {
  			    echo (" Contrahechizo!");
                          }
                          $this->contrincante2['PM'] = $this->contrincante2['PM'] - 8;
                          $this->contrincante1['PM'] = $this->contrincante1['PM'] + 2;
                        } else {
                          if ($this->contrincante1['hechizo_v_4_duracion'] > 0)
                          {
                            if ($this->debug_mode == 1)
                            {
    			      echo (" Invulnerable!");
                            }
                            $this->contrincante2['PM'] = $this->contrincante2['PM'] - 4;
                          } else {
                            $this->contrincante2['PM'] = $this->contrincante2['PM'] - 4;
                            $this->contrincante1['PV'] = $this->contrincante1['PV'] - $dolor;
                            // Si te quitan de una ostia mas de 1/4 de vida, aturdido 2(1)asl, si es 1/2 el triple
                            if (($dolor / $this->contrincante1['PVmax']) > 0.33)
                            {
                              $this->contrincante1['aturdido'] = $this->contrincante1['aturdido']+2;
                              if ($this->debug_mode == 1)
                              {
                                echo ("<br/><u>Quitado 1/4 de la vida, aturdido</u>");
                              }
                            }
                            if (($dolor / $this->contrincante1['PVmax']) > 0.5)
                            {
//                              $this->contrincante1['aturdido'] = $this->contrincante1['aturdido']+4;
                              $this->contrincante1['aturdido_sin_parar'] = $this->contrincante1['aturdido_sin_parar']+2;
                              if ($this->debug_mode == 1)
                              {
                                echo ("<br/><u>Quitado 1/2 de la vida, aturdido</u>");
                              }
                            }

                          }
                        }
                      }
                    }
                  } else {
                    if ($ejecutor == 1)
                    {
                      $this->contrincante1['PM'] = $this->contrincante1['PM'] - 4;
                    } else {
                      $this->contrincante2['PM'] = $this->contrincante2['PM'] - 4;
                    }
                    if ($this->debug_mode == 1)
                    {
                      echo ("<br/><u>Deme de las profundidades: Dolor. No tiene mana (".$contrincante_yo['PM'].") y pierde el hechizo</u>");
                    }
                  }
       		}

                // Deshace el emboscar
                if ($ejecutor == 1)
                {
                  $this->contrincante1['emboscado'] = 0;
                } else {
                  $this->contrincante2['emboscado'] = 0;
                }
		break;


	case 5:
                // ****************************
                //   HECHIZO DE NIVEL   2
                // ****************************

                // ******************************* DRENAR MANA *****************************
                // Si estas aturdido, no puedes, asi que pierdes el turno
                if (($contrincante_yo['aturdido'] > 0) || ($contrincante_yo['aturdido_sin_parar'] > 0))
                {
                  if ($this->debug_mode == 1)
                  {
                    echo ("<br/><u>Aturdido intentando lanzar hechizo N2, pierde el turno</u>");
                  }
                  return;
                }

		// Deme de las profundidades
                if ($contrincante_yo['iddeme'] == 1)
                {
                  // Drenar mana, causa inteligencia + 2d6 de resta a los PM. Gasta 4 PM
                  if ($contrincante_yo['PM'] >= 4)
                  {

                    $dolor = ($contrincante_yo['inteligencia'] - $maldito_yo) + rand(1,6) + rand(1,6);
                    if ($this->debug_mode == 1)
                    {
                      echo ("<br/><u>Deme de las profundidades: 2.Drenar Mana. Causando ".$dolor." de mana drain</u>");
                    }

                    // Si el opositor esta emboscado, el atacante no puede hacer nada
                    if ($ejecutor == 1)
                    {
                      if ($this->contrincante2['emboscado'] > 0)
                      {
                        if ($this->debug_mode == 1)
                        {
                          echo ("<br/><u>El contrario est&aacute; emboscado, y ".$ejecutor." no lo encuentra</u>");
                        }
                        // Deshace el emboscar para sí mismo
                        $this->contrincante1['emboscado'] = 0;                        
                        return;
                      }
                    } else {
                      if ($this->contrincante1['emboscado'] > 0)
                      {
                        if ($this->debug_mode == 1)
                        {
                          echo ("<br/><u>El contrario est&aacute; emboscado, y ".$ejecutor." no lo encuentra</u>");
                        }
                        // Deshace el emboscar para sí mismo
                        $this->contrincante2['emboscado'] = 0;                        
                        return;
                      }
                    }

                    if ($ejecutor == 1)
                    {
                      if ($this->contrincante2['reflejar'] == 1)
                      {
                        if ($this->debug_mode == 1)
                        {
                          echo (" Reflejado!");
                        }
                        $this->contrincante2['reflejar'] = 0;
                        $this->contrincante1['PM'] = $this->contrincante1['PM'] - $dolor;
                      } else {
                        // Si el otro contendiente tiene activo contrahechizo, pierde 8 de mana y el otro recupera 2
                        if ($this->contrincante2['contrahechizo'] == 1)
                        {
                          if ($this->debug_mode == 1)
                          {
  			    echo (" Contrahechizo!");
                          }
                          $this->contrincante1['PM'] = $this->contrincante1['PM'] - 8;
                          $this->contrincante2['PM'] = $this->contrincante2['PM'] + 2;
                        } else {
                          if ($this->contrincante2['hechizo_v_4_duracion'] > 0)
                          {
                            if ($this->debug_mode == 1)
                            {
  			      echo (" Invulnerable!");
                            }
                            $this->contrincante1['PM'] = $this->contrincante1['PM'] - 4;
                          } else {
                            $this->contrincante1['PM'] = $this->contrincante1['PM'] - 4;
                            $this->contrincante2['PM'] = $this->contrincante2['PM'] - $dolor;
                          }
                        }
                      }
                    } else {
                      if ($this->contrincante1['reflejar'] == 1)
                      {
                        if ($this->debug_mode == 1)
                        {
                          echo (" Reflejado!");
                        }
                        $this->contrincante1['reflejar'] = 0;
                        $this->contrincante2['PM'] = $this->contrincante2['PM'] - $dolor;
                      } else {
			// Si contrahechizo, pierde 8 de mana y el otro recupera 2
                        if ($this->contrincante1['contrahechizo'] == 1)
                        {
                          if ($this->debug_mode == 1)
                          {
  			    echo (" Contrahechizo!");
                          }
                          $this->contrincante2['PM'] = $this->contrincante2['PM'] - 8;
                          $this->contrincante1['PM'] = $this->contrincante1['PM'] + 2;
                        } else {
                          if ($this->contrincante1['hechizo_v_4_duracion'] > 0)
                          {
                            if ($this->debug_mode == 1)
                            {
    			      echo (" Invulnerable!");
                            }
                            $this->contrincante2['PM'] = $this->contrincante2['PM'] - 4;
                          } else {
                            $this->contrincante2['PM'] = $this->contrincante2['PM'] - 4;
                            $this->contrincante1['PM'] = $this->contrincante1['PM'] - $dolor;
                          }
                        }
                      }
                    }
                  } else {
                    if ($ejecutor == 1)
                    {
                      $this->contrincante1['PM'] = $this->contrincante1['PM'] - 4;
                    } else {
                      $this->contrincante2['PM'] = $this->contrincante2['PM'] - 4;
                    }
                    if ($this->debug_mode == 1)
                    {
                      echo ("<br/><u>Deme de las profundidades: 2.Drenar Mana. No tiene mana (".$contrincante_yo['PM'].") y pierde el hechizo</u>");
                    }
                  }


       		}


		// Deme del bosque
		//
                // ******************************* ESPEJO *****************************
		// Hechizo de espejo, hace que el proximo hechizo que reciba del adversario le rebote
                if ($contrincante_yo['iddeme'] == 2)
                {
                  if ($contrincante_yo['PM'] >= 5)
                  {
                    if ($this->debug_mode == 1)
                    {
                      echo ("<br/><u>Deme del bosque: 2.Reflejar hechizo activado</u>");
                    }
                    if ($ejecutor == 1)
                    {
                      $this->contrincante1['PM'] = $this->contrincante1['PM'] - 5;
                      $this->contrincante1['reflejar'] = 1;
                    } else {
                      $this->contrincante2['PM'] = $this->contrincante2['PM'] - 5;
                      $this->contrincante2['reflejar'] = 1;
                    }
		  } else {
                    if ($ejecutor == 1)
                    {
                      $this->contrincante1['PM'] = $this->contrincante1['PM'] - 5;
                    } else {
                      $this->contrincante2['PM'] = $this->contrincante2['PM'] - 5;
                    }
                    if ($this->debug_mode == 1)
                    {
                      echo ("<br/><u>Deme del bosque: 2.Reflejar hechizo. No tiene mana (".$contrincante_yo['PM'].") y pierde el hechizo</u>");
                    }
		  }
       		}


		// Deme del volcan
		//
                // ******************************* RAPIDEZ *****************************
		// Hechizo de rapidez. Anyade 1d6+3 de rapidez durante 2+1d4 movimientos. Cuesta 5 puntos de mana
                if ($contrincante_yo['iddeme'] == 3)
                {
                  if ($contrincante_yo['PM'] >= 5)
                  {
                    if ($this->debug_mode == 1)
                    {
                      echo ("<br/><u>Deme del volcan: 2.Velocidad imparable</u>");
                    }
                    if ($ejecutor == 1)
                    {
                      $this->contrincante1['PM'] = $this->contrincante1['PM'] - 5;
                      $this->contrincante1['hechizo_v_2_rapidez'] = (rand(1,6) + 3);
                      $this->contrincante1['hechizo_v_2_duracion'] = (rand(1,4) + 2);
                    } else {
                      $this->contrincante2['PM'] = $this->contrincante2['PM'] - 5;
                      $this->contrincante2['hechizo_v_2_rapidez'] = (rand(1,6) + 3);
                      $this->contrincante2['hechizo_v_2_duracion'] = (rand(1,4) + 2);
                    }
                  } else {
                    if ($ejecutor == 1)
                    {
                      $this->contrincante1['PM'] = $this->contrincante1['PM'] - 5;
                    } else {
                      $this->contrincante2['PM'] = $this->contrincante2['PM'] - 5;
                    }
                    if ($this->debug_mode == 1)
                    {
                      echo ("<br/><u>Deme del volcan: 2.Velocidad imparable. No tiene mana (".$contrincante_yo['PM'].") y pierde el hechizo</u>");
                    }
                  }
       		}

                // Deshace el emboscar
                if ($ejecutor == 1)
                {
                  $this->contrincante1['emboscado'] = 0;
                } else {
                  $this->contrincante2['emboscado'] = 0;
                }
		break;


        // ****************************************

	case 6:
                // ****************************
                //   HECHIZO DE NIVEL   3
                // ****************************

                // Si estas aturdido, no puedes, asi que pierdes el turno
                if (($contrincante_yo['aturdido'] > 0) || ($contrincante_yo['aturdido_sin_parar'] > 0))
                {
                  if ($this->debug_mode == 1)
                  {
                    echo ("<br/><u>Aturdido intentando lanzar hechizo N3, pierde el turno</u>");
                  }
                  return;
                }


		// Deme de las profundidades
		//
                // ******************** POLVO ZOMBI *******************
		// Hechizo de polvo zombi. Aturde 1d4 asaltos al enemigo. Gasta 10 de mana.
                if ($contrincante_yo['iddeme'] == 1)
                {
                  if ($contrincante_yo['PM'] >= 10)
                  {
                    if ($this->debug_mode == 1)
                    {
                      echo ("<br/><u>Deme de las profundidades: 3.Polvo Zombi</u>");
                    }

                    // Si el opositor esta emboscado, el atacante no puede hacer nada
                    if ($ejecutor == 1)
                    {
                      if ($this->contrincante2['emboscado'] > 0)
                      {
                        if ($this->debug_mode == 1)
                        {
                          echo ("<br/><u>El contrario est&aacute; emboscado, y ".$ejecutor." no lo encuentra</u>");
                        }
                        // Deshace el emboscar para sí mismo
                        $this->contrincante1['emboscado'] = 0;
                        return;
                      }
                    } else {
                      if ($this->contrincante1['emboscado'] > 0)
                      {
                        if ($this->debug_mode == 1)
                        {
                          echo ("<br/><u>El contrario est&aacute; emboscado, y ".$ejecutor." no lo encuentra</u>");
                        }
                        // Deshace el emboscar para sí mismo
                        $this->contrincante2['emboscado'] = 0;                        
                        return;
                      }
                    }


                    if ($ejecutor == 1)
                    {
                      if ($this->contrincante2['reflejar'] == 1)
                      {
                        if ($this->debug_mode == 1)
                        {
                          echo (" Reflejado!");
                        }
                        $this->contrincante2['reflejar'] = 0;
                        $this->contrincante1['PM'] = $this->contrincante1['PM'] - 10;
                        $this->contrincante1['aturdido'] = ($this->contrincante1['aturdido'] + rand(1,4) + 2);
                      } else {
                        // Si el otro contendiente tiene activo contrahechizo, pierde 10+4 de mana y el otro recupera 2
                        if ($this->contrincante2['contrahechizo'] == 1)
                        {
                          if ($this->debug_mode == 1)
                          {
			    echo (" Contrahechizo!");
                          }
                          $this->contrincante1['PM'] = $this->contrincante1['PM'] - 14;  // 10 del hechizo y 2*2 de mana
                          $this->contrincante2['PM'] = $this->contrincante2['PM'] + 2;
                        } else {
                          if ($this->contrincante2['hechizo_v_4_duracion'] > 0)
                          {
                            if ($this->debug_mode == 1)
                            {
    			      echo (" Invulnerable!");
                            }
                            $this->contrincante1['PM'] = $this->contrincante1['PM'] - 10;
                          } else {
                            $this->contrincante1['PM'] = $this->contrincante1['PM'] - 10;
                            $this->contrincante2['aturdido'] = ($this->contrincante2['aturdido'] + rand(1,4) + 2);
                          }
                        }
                      }
                    } else {
                      if ($this->contrincante1['reflejar'] == 1)
                      {
                        if ($this->debug_mode == 1)
                        {
                          echo (" Reflejado!");
                        }
                        $this->contrincante1['reflejar'] = 0;
                        $this->contrincante2['PM'] = $this->contrincante2['PM'] - 10;
                        $this->contrincante2['aturdido'] = ($this->contrincante2['aturdido'] + rand(1,4) + 2);
                      } else {
                        // Si el otro contendiente tiene activo contrahechizo, pierde 10+4 de mana y el otro recupera 2
                        if ($this->contrincante1['contrahechizo'] == 1)
                        {
                          if ($this->debug_mode == 1)
                          {
                            echo (" Contrahechizo!");
                          }
                          $this->contrincante2['PM'] = $this->contrincante2['PM'] - 14;  // 10 del hechizo y 2*2 de mana
                          $this->contrincante1['PM'] = $this->contrincante1['PM'] + 2;
                        } else {
                          if ($this->contrincante1['hechizo_v_4_duracion'] > 0)
                          {
                            if ($this->debug_mode == 1)
                            {
    			      echo (" Invulnerable!");
                            }
                            $this->contrincante2['PM'] = $this->contrincante2['PM'] - 10;
                          } else {
                            $this->contrincante2['PM'] = $this->contrincante2['PM'] - 10;
                            $this->contrincante1['aturdido'] = ($this->contrincante1['aturdido'] + rand(1,4) + 2);
                          }
                        }
                      }
                    }
                  } else {
                    if ($ejecutor == 1)
                    {
                      $this->contrincante1['PM'] = $this->contrincante1['PM'] - 10;
                    } else {
                      $this->contrincante2['PM'] = $this->contrincante2['PM'] - 10;
                    }
                    if ($this->debug_mode == 1)
                    {
                      echo ("<br/><u>Deme de las profundidades: 3.Polvo zombi. No tiene mana (".$contrincante_yo['PM'].") y pierde el hechizo</u>");
                    }
                  }

       		}

		// Deme del bosque
                // ******************** RESTAURAR *******************
                if ($contrincante_yo['iddeme'] == 2)
                {
		  // Restaurar. Cura la vida hasta el maximo, y elimina todo lo negativo, anyade tb +5 superando los PVmax
                  if ($contrincante_yo['PM'] >= 12)
                  {
                    $curacion = $contrincante_yo['PVmax'] - $contrincante_yo['PV'] + 5; //$contrincante_yo['inteligencia'] + 10 + rand(1,10);
                    if ($this->debug_mode == 1)
                    {
                      echo ("<br/><u>Deme del bosque: 3.Restaurar. Curando ".$curacion." de vida, eliminando todo lo negativo</u>");
                    }
                    if ($ejecutor == 1)
                    {
                      $this->contrincante1['PM'] = $this->contrincante1['PM'] - 12;
                      $this->contrincante1['PV'] = $this->contrincante1['PV'] + $curacion;
                      $this->contrincante1['hechizo_p_4_duracion'] = 0;
                    } else {
                      $this->contrincante2['PM'] = $this->contrincante2['PM'] - 12;
                      $this->contrincante2['PV'] = $this->contrincante2['PV'] + $curacion;
                      $this->contrincante2['hechizo_p_4_duracion'] = 0;
                    }
                  } else {
                    if ($ejecutor == 1)
                    {
                      $this->contrincante1['PM'] = $this->contrincante1['PM'] - 12;
                    } else {
                      $this->contrincante2['PM'] = $this->contrincante2['PM'] - 12;
                    }
                    if ($this->debug_mode == 1)
                    {
                      echo ("<br/><u>Deme del bosque: 3.Restaurar. No tiene mana (".$contrincante_yo['PM'].") y pierde el hechizo</u>");
                    }
                  }
       		}


                // ******************** MIEMBROS ARDIENTES *******************
		// Deme del volcan
                if ($contrincante_yo['iddeme'] == 3)
                {
		  // Miembros ardientes. Te suma +2 a la fuerza, y +2 al danyo
                  if ($contrincante_yo['PM'] >= 10)
                  {
                    $duracion = (rand(1,6)+3);
                    if ($this->debug_mode == 1)
                    {
                      echo ("<br/><u>Deme del volcan: 3.Miembros ardientes. Anyadiendo ".$duracion." asaltos de efecto.</u>");
                    }
                    if ($ejecutor == 1)
                    {
                      $this->contrincante1['PM'] = $this->contrincante1['PM'] - 10;
                      $this->contrincante1['hechizo_v_3_duracion'] = $this->contrincante1['hechizo_v_3_duracion'] + $duracion;
                    } else {
                      $this->contrincante2['PM'] = $this->contrincante2['PM'] - 10;
                      $this->contrincante2['hechizo_v_3_duracion'] = $this->contrincante2['hechizo_v_3_duracion'] + $duracion;
                    }
                  } else {
                    if ($ejecutor == 1)
                    {
                      $this->contrincante1['PM'] = $this->contrincante1['PM'] - 10;
                    } else {
                      $this->contrincante2['PM'] = $this->contrincante2['PM'] - 10;
                    }
                    if ($this->debug_mode == 1)
                    {
                      echo ("<br/><u>Deme del volcan: 3.Miembros ardientes. No tiene mana (".$contrincante_yo['PM'].") y pierde el hechizo</u>");
                    }
                  }
       		}

                // Deshace el emboscar
                if ($ejecutor == 1)
                {
                  $this->contrincante1['emboscado'] = 0;
                } else {
                  $this->contrincante2['emboscado'] = 0;
                }
		break;


	case 7:
                // ****************************
                //   HECHIZO DE NIVEL   4
                // ****************************

                // Si estas aturdido, no puedes, asi que pierdes el turno
                if (($contrincante_yo['aturdido'] > 0) || ($contrincante_yo['aturdido_sin_parar'] > 0))
                {
                  if ($this->debug_mode == 1)
                  {
                    echo ("<br/><u>Aturdido intentando lanzar hechizo N4, pierde el turno</u>");
                  }
                  return;
                }

                if ($contrincante_yo['ultimate_lanzado'] == 1)
                {
                  if ($this->debug_mode == 1)
                  {
                    echo ("<br/><u>Ya se ha lanzado un ultimate, pierde el turno</u>");
                  }
                  return;
                }


                // ******************** MALDICION *******************
		// Deme de las profundidades
                if ($contrincante_yo['iddeme'] == 1)
                {
		  // Maldicion, resta 2 a todas las caracteristicas durante 2d6+3 asaltos
                  if ($contrincante_yo['PM'] >= 15)
                  {
                    $duracion = (rand(1,6)+rand(1,6)+3);
                    if ($this->debug_mode == 1)
                    {
                      echo ("<br/><u>Deme de las profundidades: 4. Maldicion. Anyadiendo ".$duracion." asaltos de efecto.</u>");
                    }

                    // Si el opositor esta emboscado, el atacante no puede hacer nada
                    if ($ejecutor == 1)
                    {
                      if ($this->contrincante2['emboscado'] > 0)
                      {
                        if ($this->debug_mode == 1)
                        {
                          echo ("<br/><u>El contrario est&aacute; emboscado, y ".$ejecutor." no lo encuentra</u>");
                        }
                        // Deshace el emboscar para sí mismo
                        $this->contrincante1['emboscado'] = 0;                        
                        return;
                      }
                    } else {
                      if ($this->contrincante1['emboscado'] > 0)
                      {
                        if ($this->debug_mode == 1)
                        {
                          echo ("<br/><u>El contrario est&aacute; emboscado, y ".$ejecutor." no lo encuentra</u>");
                        }
                        // Deshace el emboscar para sí mismo
                        $this->contrincante2['emboscado'] = 0;                          
                        return;
                      }
                    }

                    if ($ejecutor == 1)
                    {
                      $this->contrincante1['ultimate_lanzado'] = 1;  // Ultimate lanzado, como sea que resulte
                      if ($this->contrincante2['reflejar'] == 1)
                      {
                        if ($this->debug_mode == 1)
                        {
                          echo (" Reflejado!");
                        }
                        $this->contrincante1['PM'] = $this->contrincante1['PM'] - 15;
                        $this->contrincante1['hechizo_p_4_duracion'] = $this->contrincante1['hechizo_p_4_duracion'] + $duracion;
                        $this->contrincante2['reflejar'] = 0;
                      } else {
                        // Si el otro contendiente tiene activo contrahechizo, pierde 15+4 de mana y el otro recupera 2
                        if ($this->contrincante2['contrahechizo'] == 1)
                        {
                          if ($this->debug_mode == 1)
                          {
			    echo (" Contrahechizo!");
                          }
                          $this->contrincante2['PM'] = $this->contrincante2['PM'] +2;
                          $this->contrincante1['PM'] = $this->contrincante1['PM'] - (15 + 4);
                        } else {
                          if ($this->contrincante2['hechizo_v_4_duracion'] > 0)
                          {
                            if ($this->debug_mode == 1)
                            {
  			      echo (" Invulnerable!");
                            }
                            $this->contrincante1['PM'] = $this->contrincante1['PM'] - 15;
                          } else {
                            $this->contrincante1['PM'] = $this->contrincante1['PM'] - 15;
                            $this->contrincante2['hechizo_p_4_duracion'] = $this->contrincante2['hechizo_p_4_duracion'] + $duracion;
                          }
                        }
                      }
                    } else {
                      $this->contrincante2['ultimate_lanzado'] = 1;  // Ultimate lanzado, como sea que resulte
                      if ($this->contrincante1['reflejar'] == 1)
                      {
                        if ($this->debug_mode == 1)
                        {
                          echo (" Reflejado!");
                        }
                        $this->contrincante2['PM'] = $this->contrincante2['PM'] - 15;
                        $this->contrincante2['hechizo_p_4_duracion'] = $this->contrincante2['hechizo_p_4_duracion'] + $duracion;
                        $this->contrincante1['reflejar'] = 0;
                      } else {
                        // Si el otro contendiente tiene activo contrahechizo, pierde 15+4 de mana y el otro recupera 2
                        if ($this->contrincante1['contrahechizo'] == 1)
                        {
                          if ($this->debug_mode == 1)
                          {
			    echo (" Contrahechizo!");
                          }
                          $this->contrincante1['PM'] = $this->contrincante1['PM'] +2;
                          $this->contrincante2['PM'] = $this->contrincante2['PM'] - (15 + 4);
                        } else {
                          if ($this->contrincante1['hechizo_v_4_duracion'] > 0)
                          {
                            if ($this->debug_mode == 1)
                            {
  			      echo (" Invulnerable!");
                            }
                            $this->contrincante2['PM'] = $this->contrincante2['PM'] - 15;
                          } else {
                            $this->contrincante2['PM'] = $this->contrincante2['PM'] - 15;
                            $this->contrincante1['hechizo_p_4_duracion'] = $this->contrincante1['hechizo_p_4_duracion'] + $duracion;
                          }
                        }
                      }
                    } // cierra el if de ejecutor
                  } else { // si no tiene mana
                    if ($ejecutor == 1)
                    {
                      $this->contrincante1['PM'] = $this->contrincante1['PM'] - 15;
                    } else {
                      $this->contrincante2['PM'] = $this->contrincante2['PM'] - 15;
                    }
                    if ($this->debug_mode == 1)
                    {
                      echo ("<br/><u>Deme de las profundidades: 4.Maldicion. No tiene mana (".$contrincante_yo['PM'].") y pierde el hechizo</u>");
                    }
                  }
       		}


                // ******************** INVULNERABILIDAD *******************
		// Deme del bosque
                if ($contrincante_yo['iddeme'] == 2)
                {
		  // Invulnerable durante 1d4+2 asaltos
                  if ($contrincante_yo['PM'] >= 15)
                  {
                    $duracion = (rand(0,4)+6);
                    if ($this->debug_mode == 1)
                    {
                      echo ("<br/><u>Deme del bosque: 4. Invulnerabilidad. Anyadiendo ".$duracion." asaltos de efecto.</u>");
                    }
                    if ($ejecutor == 1)
                    {
                      $this->contrincante1['ultimate_lanzado'] = 1;  // Ultimate lanzado, como sea que resulte
                      $this->contrincante1['PM'] = $this->contrincante1['PM'] - 15;
                      $this->contrincante1['hechizo_v_4_duracion'] = $this->contrincante1['hechizo_v_4_duracion'] + $duracion;
                    } else {
                      $this->contrincante2['ultimate_lanzado'] = 1;  // Ultimate lanzado, como sea que resulte
                      $this->contrincante2['PM'] = $this->contrincante2['PM'] - 15;
                      $this->contrincante2['hechizo_v_4_duracion'] = $this->contrincante2['hechizo_v_4_duracion'] + $duracion;
                    }
                  } else {
                    if ($ejecutor == 1)
                    {
                      $this->contrincante1['PM'] = $this->contrincante1['PM'] - 15;
                    } else {
                      $this->contrincante2['PM'] = $this->contrincante2['PM'] - 15;
                    }
                    if ($this->debug_mode == 1)
                    {
                      echo ("<br/><u>Deme de las profundidades: 4.Maldicion. No tiene mana (".$contrincante_yo['PM'].") y pierde el hechizo</u>");
                    }
                  }
       		}


                // ******************** DANZA DE FUEGO *******************
		// Deme del volcan
                if ($contrincante_yo['iddeme'] == 3)
                {
                  // Danza de fuego. Se causa 3+1d6, causa 4 veces esa cantidad, y se aturde a si mismo 1 asalto
                  if ($contrincante_yo['PM'] >= 15)
                  {
                    $dolor1 = 3 + rand(1,6);
                    $dolor2 = ($dolor1 * 3.5);
                    $dolor2 = $dolor2 + ($dolor2 * $sumador_emboscado);  // No sumamos el emboscado al que recibes sino solo al causado
                    if ($this->debug_mode == 1)
                    {
                      echo ("<br/><u>Deme del volc&aacute;n: Danza de fuego. Causando ".$dolor1." a si y ".$dolor2." a su objetivo de da&ntilde;o</u>");
                    }

                    // Si el opositor esta emboscado, el atacante no puede hacer nada
                    if ($ejecutor == 1)
                    {
                      $this->contrincante1['ultimate_lanzado'] = 1;  // Ultimate lanzado, como sea que resulte
                      if ($this->contrincante2['emboscado'] > 0)
                      {
                        if ($this->debug_mode == 1)
                        {
                          echo ("<br/><u>El contrario est&aacute; emboscado, y ".$ejecutor." no lo encuentra, y solo se autoda&ntilde;a</u>");
                        }
                        $this->contrincante1['PV'] = $this->contrincante1['PV'] - $dolor1;
                        // Deshace el emboscar para sí mismo
                        $this->contrincante1['emboscado'] = 0;  
                        return;
                      }
                    } else {
                      $this->contrincante2['ultimate_lanzado'] = 1;  // Ultimate lanzado, como sea que resulte
                      if ($this->contrincante1['emboscado'] > 0)
                      {
                        if ($this->debug_mode == 1)
                        {
                          echo ("<br/><u>El contrario est&aacute; emboscado, y ".$ejecutor." no lo encuentra, y solo se autoda&ntilde;a</u>");
                        }
                        $this->contrincante2['PV'] = $this->contrincante2['PV'] - $dolor1;
                        // Deshace el emboscar para sí mismo
                        $this->contrincante2['emboscado'] = 0;                          
                        return;
                      }
                    }

                    if ($ejecutor == 1)
                    {
                      // Si el otro contendiente tiene activo el reflejar, el lanzador se come el danyo
                      if ($this->contrincante2['reflejar'] == 1)
                      {
                        if ($this->debug_mode == 1)
                        {
                          echo (" Reflejado!");
                        }
                        $this->contrincante2['reflejar'] = 0;
                        $this->contrincante1['PV'] = $this->contrincante1['PV'] - $dolor1 - $dolor2;
                      } else {
                        // Si el otro contendiente tiene activo contrahechizo, nadie se come el danyo, pierde 8, el otro recupera 2
                        if ($this->contrincante2['contrahechizo'] == 1)
                        {
                          if ($this->debug_mode == 1)
                          {
			    echo (" Contrahechizo!");
                          }
                          $this->contrincante1['PM'] = $this->contrincante1['PM'] - 19;
                          $this->contrincante2['PM'] = $this->contrincante2['PM'] + 2;
			} else {
                          if ($this->contrincante2['hechizo_v_4_duracion'] > 0)
                          {
                            if ($this->debug_mode == 1)
                            {
    			      echo (" Invulnerable!");
                            }
                            $this->contrincante1['PM'] = $this->contrincante1['PM'] - 15;
                          } else {
                            $this->contrincante1['PM'] = $this->contrincante1['PM'] - 15;
                            $this->contrincante1['PV'] = $this->contrincante1['PV'] - $dolor1;
                            $this->contrincante2['PV'] = $this->contrincante2['PV'] - $dolor2;
                            $this->contrincante1['aturdido'] = $this->contrincante1['aturdido'] + 2; // 2 para que sea 1 en realidad
                            // Si te quitan de una ostia mas de 1/4 de vida, aturdido 2(1)asl, si es 1/2 el triple
                            if (($dolor2 / $this->contrincante2['PVmax']) > 0.33)
                            {
                              $this->contrincante2['aturdido'] = $this->contrincante2['aturdido']+2;
                              if ($this->debug_mode == 1)
                              {
                                echo ("<br/><u>Quitado 1/4 de la vida, aturdido</u>");
                              }
                            }
                            if (($dolor2 / $this->contrincante2['PVmax']) > 0.5)
                            {
//                              $this->contrincante2['aturdido'] = $this->contrincante2['aturdido']+4;
                              $this->contrincante2['aturdido_sin_parar'] = $this->contrincante2['aturdido_sin_parar']+2;
                              if ($this->debug_mode == 1)
                              {
                                echo ("<br/><u>Quitado 1/2 de la vida, aturdido</u>");
                              }
                            }


                          }
			}
                      }
                    } else {
                      if ($this->contrincante1['reflejar'] == 1)
                      {
                        if ($this->debug_mode == 1)
                        {
			  echo (" Reflejado!");
                        }
                        $this->contrincante1['reflejar'] = 0;
                        $this->contrincante2['PV'] = $this->contrincante2['PV'] - $dolor1 - $dolor2;
                      } else {
                        // Si el otro contendiente tiene activo contrahechizo, nadie se come el danyo, pierde 8, el otro recupera 2
                        if ($this->contrincante1['contrahechizo'] == 1)
                        {
                          if ($this->debug_mode == 1)
                          {
			    echo (" Contrahechizo!");
                          }
                          $this->contrincante2['PM'] = $this->contrincante2['PM'] - 19;
                          $this->contrincante1['PM'] = $this->contrincante1['PM'] + 2;
                        } else {
                          if ($this->contrincante1['hechizo_v_4_duracion'] > 0)
                          {
                            if ($this->debug_mode == 1)
                            {
  			      echo (" Invulnerable!");
                            }
                            $this->contrincante2['PM'] = $this->contrincante2['PM'] - 15;
                          } else {
                            $this->contrincante2['PM'] = $this->contrincante2['PM'] - 15;
                            $this->contrincante2['PV'] = $this->contrincante2['PV'] - $dolor1;
                            $this->contrincante1['PV'] = $this->contrincante1['PV'] - $dolor2;
                            $this->contrincante2['aturdido'] = $this->contrincante2['aturdido'] + 2; // 2 para que sea 1 en realidad

                            // Si te quitan de una ostia mas de 1/4 de vida, aturdido 2(1)asl, si es 1/2 el triple
                            if (($dolor2 / $this->contrincante1['PVmax']) > 0.33)
                            {
                              $this->contrincante1['aturdido'] = $this->contrincante1['aturdido']+2;
                              if ($this->debug_mode == 1)
                              {
                                echo ("<br/><u>Quitado 1/4 de la vida, aturdido</u>");
                              }
                            }
                            if (($dolor2 / $this->contrincante1['PVmax']) > 0.5)
                            {
//                              $this->contrincante1['aturdido'] = $this->contrincante1['aturdido']+4;
                              $this->contrincante1['aturdido_sin_parar'] = $this->contrincante1['aturdido_sin_parar']+2;
                              if ($this->debug_mode == 1)
                              {
                                echo ("<br/><u>Quitado 1/2 de la vida, aturdido</u>");
                              }
                            }


                          }
                        }
                      }
                    }
                  } else {
                    if ($ejecutor == 1)
                    {
                      $this->contrincante1['PM'] = $this->contrincante1['PM'] - 15;
                    } else {
                      $this->contrincante2['PM'] = $this->contrincante2['PM'] - 15;
                    }
                    if ($this->debug_mode == 1)
                    {
                      echo ("<br/><u>Deme del volcan: 4.Danza del fuego. No tiene mana (".$contrincante_yo['PM'].") y pierde el hechizo</u>");
                    }
                  }
       		}

                // Deshace el emboscar
                if ($ejecutor == 1)
                {
                  $this->contrincante1['emboscado'] = 0;
                } else {
                  $this->contrincante2['emboscado'] = 0;
                }
		break;

	case 8:
                // ****************************
                //   CONTRAHECHIZO
                // ****************************

                // Si estas aturdido, no puedes, asi que pierdes el turno
                if (($contrincante_yo['aturdido'] > 0) || ($contrincante_yo['aturdido_sin_parar'] > 0))
                {
                  if ($this->debug_mode == 1)
                  {
                    echo ("<br/><u>Aturdido intentando lanzar contrahechizo, pierde el turno</u>");
                  }
                  return;
                }

		// Contrahechizo
                if ($this->debug_mode == 1)
                {
                  echo ("<br/><u>Activado contrahechizo</u>");
                }
                if ($ejecutor == 1)
                {
                  $this->contrincante1['contrahechizo'] = 1;
		} else {
                  $this->contrincante2['contrahechizo'] = 1;
		}

                // Deshace el emboscar
                if ($ejecutor == 1)
                {
                  $this->contrincante1['emboscado'] = 0;
                } else {
                  $this->contrincante2['emboscado'] = 0;
                }
		break;

	case 9:
                // ****************************
                //   EMBOSCAR
                // ****************************

                // Si estas aturdido, no puedes, asi que pierdes el turno
                if (($contrincante_yo['aturdido'] > 0) || ($contrincante_yo['aturdido_sin_parar'] > 0))
                {
                  if ($this->debug_mode == 1)
                  {
                    echo ("<br/><u>Aturdido intentando emboscar, pierde el turno</u>");
                  }
                  return;
                }

                // Primero vamos a tirar mi Rapidez+1d6 contra la Percepcion del enemigo
                $tirada1 = $contrincante_yo['rapidez'] + rand(1,6) - $maldito_yo;
                $tirada2 = $contrincante_otro['percepcion'] - $maldito_otro;
                // Vemos si hay un modificador a la rapidez (V2)
                if ($contrincante_yo['hechizo_v_2_duracion'] > 0) { $tirada1 = $tirada1 + $contrincante_yo['hechizo_v_2_rapidez']; }
                if ($tirada1 > $tirada2)
                {
                  $diferencia = $tirada1 - $tirada2;
                  // Ha tenido exito la emboscada
                  if ($this->debug_mode == 1)
                  {
                    echo ("<br/><u>Emboscado con &eacute;xito, diferencia ".$diferencia."</u>");
                  }
                  if ($ejecutor == 1)
                  {
                    $this->contrincante1['emboscado'] = $diferencia;
                  } else {
                    $this->contrincante2['emboscado'] = $diferencia;
                  }
                } else {
                  if ($this->debug_mode == 1)
                  {
                    echo ("<br/><u>Emboscada fallida</u>");
                  }
                }

		break;
      }

    }




    // ***********************************************
    //   funciones para despues de turno 2 quitar cosas del 1 y viceversa
    // ***********************************************

    function QuitarPararContrahechizo1()
    {
          $this->contrincante1['parar'] = 0;
          $this->contrincante1['contrahechizo'] = 0;
    }

    function QuitarPararContrahechizo2()
    {
          $this->contrincante2['parar'] = 0;
          $this->contrincante2['contrahechizo'] = 0;
    }

    // ***************************************************
    //   Funciones para disminuir el aturdimiento
    // ***************************************************

    function BajarAturdimiento1()
    {
        // Primero bajan los de sin parar, luego los normales
        if ($this->contrincante1['aturdido_sin_parar'] > 0)
        {
          $this->contrincante1['aturdido_sin_parar'] = $this->contrincante1['aturdido_sin_parar'] - 1;
          if ($this->debug_mode == 1)
          {
            echo ("<br/>Bajando aturdimiento sin parar player 1");
          }
        } else {
          if ($this->contrincante1['aturdido'] > 0)
          {
            $this->contrincante1['aturdido'] = $this->contrincante1['aturdido'] - 1;
            if ($this->debug_mode == 1)
            {
              echo ("<br/>Bajando aturdimiento player 1");
            }
          }
        }
    }

    function BajarAturdimiento2()
    {
        if ($this->contrincante2['aturdido_sin_parar'] > 0)
        {
          $this->contrincante2['aturdido_sin_parar'] = $this->contrincante2['aturdido_sin_parar'] - 1;
          if ($this->debug_mode == 1)
          {
            echo ("<br/>Bajando aturdimiento sin parar player 2");
          }
        } else{
          if ($this->contrincante2['aturdido'] > 0)
  	  {
            $this->contrincante2['aturdido'] = $this->contrincante2['aturdido'] - 1;
            if ($this->debug_mode == 1)
            {
              echo ("<br/>Bajando aturdimiento player 2");
            }
          }
        }
    }


    // ***********************************************
    //   Reduce en un turno o elimina el efecto del hechizo de rapidez, si esta activo
    // ***********************************************

    function ReducirRapidez()
    {
      if ($this->contrincante1['hechizo_v_2_duracion'] > 0)
      {
        $this->contrincante1['hechizo_v_2_duracion'] = $this->contrincante1['hechizo_v_2_duracion'] - 1;
      }
      if ($this->contrincante2['hechizo_v_2_duracion'] > 0)
      {
        $this->contrincante2['hechizo_v_2_duracion'] = $this->contrincante2['hechizo_v_2_duracion'] - 1;
      }
    }

    // ***********************************************
    //   Reduce en un turno o elimina el efecto del hechizo de rapidez, si esta activo
    // ***********************************************

    function ReducirMiembrosArdientes()
    {
      if ($this->contrincante1['hechizo_v_3_duracion'] > 0)
      {
        $this->contrincante1['hechizo_v_3_duracion'] = $this->contrincante1['hechizo_v_3_duracion'] - 1;
      }
      if ($this->contrincante2['hechizo_v_3_duracion'] > 0)
      {
        $this->contrincante2['hechizo_v_3_duracion'] = $this->contrincante2['hechizo_v_3_duracion'] - 1;
      }
    }

    // ***********************************************
    //   Reduce en un turno o elimina el efecto del hechizo de maldicion, si esta activo
    // ***********************************************

    function ReducirMaldicion()
    {
      if ($this->contrincante1['hechizo_p_4_duracion'] > 0)
      {
        $this->contrincante1['hechizo_p_4_duracion'] = $this->contrincante1['hechizo_p_4_duracion'] - 1;
      }
      if ($this->contrincante2['hechizo_p_4_duracion'] > 0)
      {
        $this->contrincante2['hechizo_p_4_duracion'] = $this->contrincante2['hechizo_p_4_duracion'] - 1;
      }
    }

    // ***********************************************
    //   Reduce en un turno o elimina el efecto del hechizo de invulnerabilidad, si esta activo
    // ***********************************************

    function ReducirInvulnerabilidad()
    {
      if ($this->contrincante1['hechizo_v_4_duracion'] > 0)
      {
        $this->contrincante1['hechizo_v_4_duracion'] = $this->contrincante1['hechizo_v_4_duracion'] - 1;
      }
      if ($this->contrincante2['hechizo_v_4_duracion'] > 0)
      {
        $this->contrincante2['hechizo_v_4_duracion'] = $this->contrincante2['hechizo_v_4_duracion'] - 1;
      }
    }

    // ***********************************************
    //   Comprueba que los PV y PM no se pasen de la raya
    //  si hay una maldicion en curso, y que no bajen de -10
    // ***********************************************

    function AjustarPVPM()
    {
      // Que no nos pasemos de los puntos de mana por abajo
      if ($this->contrincante1['PM'] < -10) { $this->contrincante1['PM'] = -10; }
      if ($this->contrincante2['PM'] < -10) { $this->contrincante2['PM'] = -10; }

      // JUGADOR 1
      // Que no nos pasemos de los PV por arriba
      $maximoPV1 = $this->contrincante1['PVmax'];
      // Como los PVmax se calculan mediante "constitucion * 5 + 10", si restamos 2, el maximo se reduce en 10.
      if ($this->contrincante1['hechizo_p_4_duracion'] > 0) { $maximoPV1 = $maximoPV1 - 10; }
      if ($this->contrincante1['PV'] > $maximoPV1) { $this->contrincante1['PV'] = $maximoPV1; }

      // Que no nos pasemos de los PM por arriba
      $maximoPM1 = $this->contrincante1['PMmax'];
      // Como los PVmax se calculan mediante "inteligencia * 4 + sabiduria", si restamos 2, el maximo se reduce en 10.
      if ($this->contrincante1['hechizo_p_4_duracion'] > 0) { $maximoPM1 = $maximoPM1 - 10; }
      if ($this->contrincante1['PM'] > $maximoPM1) { $this->contrincante1['PM'] = $maximoPM1; }


      // JUGADOR 2
      // Que no nos pasemos de los PV por arriba
      $maximoPV2 = $this->contrincante2['PVmax'];
      // Como los PVmax se calculan mediante "constitucion * 5 + 10", si restamos 2, el maximo se reduce en 10.
      if ($this->contrincante2['hechizo_p_4_duracion'] > 0) { $maximoPV2 = $maximoPV2 - 10; }
      if ($this->contrincante2['PV'] > $maximoPV2) { $this->contrincante2['PV'] = $maximoPV2; }

      // Que no nos pasemos de los PM por arriba
      $maximoPM2 = $this->contrincante2['PMmax'];
      // Como los PVmax se calculan mediante "inteligencia * 4 + sabiduria", si restamos 2, el maximo se reduce en 10.
      if ($this->contrincante2['hechizo_p_4_duracion'] > 0) { $maximoPM2 = $maximoPM2 - 10; }
      if ($this->contrincante2['PM'] > $maximoPM2) { $this->contrincante2['PM'] = $maximoPM2; }


    }


    // ***********************************************
    //   Comprueba sin alguno de los jugadores tiene una caracteristica por debajo de 0
    //  debido a modificaciones que hagan que deba morir automaticamente
    // ***********************************************

    function ComprobarMuerteModificaciones()
    {
      $muerto1 = 0;

      // Para la rapidez tenemos que comprobar la maldicion (P4) y el hechizo de rapidez (V2)
      $rapidez1 = $this->contrincante1['rapidez'];
      if ($this->contrincante1['hechizo_p_4_duracion'] > 0) { $rapidez1 = $rapidez1 - 2; }
      if ($this->contrincante1['hechizo_v_2_duracion'] > 0) { $rapidez1 = $rapidez1 + $this->contrincante1['hechizo_v_2_rapidez']; }
      if ($rapidez1 <= 0) { $muerto1 = 1; }

      $inteligencia1 = $this->contrincante1['inteligencia'];
      if ($this->contrincante1['hechizo_p_4_duracion'] > 0) { $inteligencia1 = $inteligencia1 - 2; }
      if ($inteligencia1 <= 0) { $muerto1 = 1; }

      // Para la fuerza ademas de la maldicion (P4) esta el hechizo de Miembros ardientes (V3)
      $fuerza1 = $this->contrincante1['fuerza'];
      if ($this->contrincante1['hechizo_p_4_duracion'] > 0) { $fuerza1 = $fuerza1 - 2; }
      if ($this->contrincante1['hechizo_v_3_duracion'] > 0) { $fuerza1 = $fuerza1 + 2; }
      if ($fuerza1 <= 0) { $muerto1 = 1; }

      $constitucion1 = $this->contrincante1['constitucion'];
      if ($this->contrincante1['hechizo_p_4_duracion'] > 0) { $constitucion1 = $constitucion1 - 2; }
      if ($constitucion1 <= 0) { $muerto1 = 1; }

      $percepcion1 = $this->contrincante1['percepcion'];
      if ($this->contrincante1['hechizo_p_4_duracion'] > 0) { $percepcion1 = $percepcion1 - 2; }
      if ($percepcion1 <= 0) { $muerto1 = 1; }

      $sabiduria1 = $this->contrincante1['sabiduria'];
      if ($this->contrincante1['hechizo_p_4_duracion'] > 0) { $sabiduria1 = $sabiduria1 - 2; }
      if ($sabiduria1 <= 0) { $muerto1 = 1; }



      $muerto2 = 0;

      // Para la rapidez tenemos que comprobar la maldicion (P4) y el hechizo de rapidez (V2)
      $rapidez2 = $this->contrincante2['rapidez'];
      if ($this->contrincante2['hechizo_p_4_duracion'] > 0) { $rapidez2 = $rapidez2 - 2; }
      if ($this->contrincante2['hechizo_v_2_duracion'] > 0) { $rapidez2 = $rapidez2 + $this->contrincante2['hechizo_v_2_rapidez']; }
      if ($rapidez2 <= 0) { $muerto2 = 1; }

      $inteligencia2 = $this->contrincante2['inteligencia'];
      if ($this->contrincante2['hechizo_p_4_duracion'] > 0) { $inteligencia2 = $inteligencia2 - 2; }
      if ($inteligencia2 <= 0) { $muerto2 = 1; }

      // Para la fuerza ademas de la maldicion (P4) esta el hechizo de Miembros ardientes (V3)
      $fuerza2 = $this->contrincante2['fuerza'];
      if ($this->contrincante2['hechizo_p_4_duracion'] > 0) { $fuerza2 = $fuerza2 - 2; }
      if ($this->contrincante2['hechizo_v_3_duracion'] > 0) { $fuerza2 = $fuerza2 + 2; }
      if ($fuerza2 <= 0) { $muerto2 = 1; }

      $constitucion2 = $this->contrincante2['constitucion'];
      if ($this->contrincante2['hechizo_p_4_duracion'] > 0) { $constitucion2 = $constitucion2 - 2; }
      if ($constitucion2 <= 0) { $muerto2 = 1; }

      $percepcion2 = $this->contrincante2['percepcion'];
      if ($this->contrincante2['hechizo_p_4_duracion'] > 0) { $percepcion2 = $percepcion2 - 2; }
      if ($percepcion2 <= 0) { $muerto2 = 1; }

      $sabiduria2 = $this->contrincante2['sabiduria'];
      if ($this->contrincante2['hechizo_p_4_duracion'] > 0) { $sabiduria2 = $sabiduria2 - 2; }
      if ($sabiduria2 <= 0) { $muerto2 = 1; }


      if ($muerto1 == 1)
      {
          $contrincante1['PV'] = -1;
      }

      if ($muerto2 == 1)
      {
          $contrincante2['PV'] = -1;
      }

    }


    // ***********************************************
    //   Calcula los efectos de la edad
    // ***********************************************

    function AfectarEdad()
    {

      // Primero hacemos ajuste, just in case
      if ($this->contrincante1['edad'] == null) { $this->contrincante1['edad'] = 0; }
      if ($this->contrincante2['edad'] == null) { $this->contrincante2['edad'] = 0; }

      // Si es "inmaduro", tiene un -1 a algunas caracteristicas
      if ($this->contrincante1['edad'] < 8)
      {
        if ($this->debug_mode == 1)
        {
          echo ("Contrincante 1 afectado por la edad [".$this->contrincante1['edad']."]");
        }
        $this->contrincante1['constitucion'] = $this->contrincante1['constitucion'] - 1;
        $this->contrincante1['sabiduria'] = $this->contrincante1['sabiduria'] - 2;
      }
      if ($this->contrincante2['edad'] < 8)
      {
        if ($this->debug_mode == 1)
        {
          echo ("Contrincante 2 afectado por la edad [".$this->contrincante2['edad']."]");
        }
        $this->contrincante2['constitucion'] = $this->contrincante2['constitucion'] - 1;
        $this->contrincante2['sabiduria'] = $this->contrincante2['sabiduria'] - 2;
      }

      // Si es "madurito", tiene un -1 a algunas caracteristicas
      if (($this->contrincante1['edad'] > 30) && ($this->contrincante1['edad'] < 40))
      {
        if ($this->debug_mode == 1)
        {
          echo ("Contrincante 1 afectado por la edad [".$this->contrincante1['edad']."]");
        }
        $this->contrincante1['constitucion'] = $this->contrincante1['constitucion'] - 1;
        $this->contrincante1['fuerza'] = $this->contrincante1['fuerza'] - 1;
        $this->contrincante1['rapidez'] = $this->contrincante1['rapidez'] - 1;
        $this->contrincante1['sabiduria'] = $this->contrincante1['sabiduria'] + 1;        
      }
      if (($this->contrincante2['edad'] > 30) && ($this->contrincante2['edad'] < 40))
      {
        if ($this->debug_mode == 1)
        {
          echo ("Contrincante 2 afectado por la edad [".$this->contrincante2['edad']."]");
        }
        $this->contrincante2['constitucion'] = $this->contrincante2['constitucion'] - 1;
        $this->contrincante2['fuerza'] = $this->contrincante2['fuerza'] - 1;
        $this->contrincante2['rapidez'] = $this->contrincante2['rapidez'] - 1;
        $this->contrincante2['sabiduria'] = $this->contrincante2['sabiduria'] + 1;
      }

      // Si es "viejo", tiene un -1 a algunas caracteristicas
      if ($this->contrincante1['edad'] >= 40)
      {
        if ($this->debug_mode == 1)
        {
          echo ("Contrincante 1 afectado por la edad [".$this->contrincante1['edad']."]");
        }
        $this->contrincante1['constitucion'] = $this->contrincante1['constitucion'] - 2;
        $this->contrincante1['fuerza'] = $this->contrincante1['fuerza'] - 2;
        $this->contrincante1['rapidez'] = $this->contrincante1['rapidez'] - 3;
        $this->contrincante1['inteligencia'] = $this->contrincante1['inteligencia'] - 1;
        $this->contrincante1['percepcion'] = $this->contrincante1['percepcion'] - 2;
        $this->contrincante1['sabiduria'] = $this->contrincante1['sabiduria'] + 2;
      }
      if ($this->contrincante2['edad'] >= 40)
      {
        if ($this->debug_mode == 1)
        {
          echo ("Contrincante 2 afectado por la edad [".$this->contrincante2['edad']."]");
        }
        $this->contrincante2['constitucion'] = $this->contrincante2['constitucion'] - 2;
        $this->contrincante2['fuerza'] = $this->contrincante2['fuerza'] - 2;
        $this->contrincante2['rapidez'] = $this->contrincante2['rapidez'] - 3;
        $this->contrincante2['inteligencia'] = $this->contrincante2['inteligencia'] - 1;
        $this->contrincante2['percepcion'] = $this->contrincante2['percepcion'] - 2;
        $this->contrincante2['sabiduria'] = $this->contrincante2['sabiduria'] + 2;
      }

      // Ajustamos a 1 como minimo
      if ($this->contrincante1['constitucion'] < 1) { $this->contrincante1['constitucion'] = 1; }
      if ($this->contrincante1['fuerza'] < 1) { $this->contrincante1['fuerza'] = 1; }
      if ($this->contrincante1['rapidez'] < 1) { $this->contrincante1['rapidez'] = 1; }
      if ($this->contrincante1['inteligencia'] < 1) { $this->contrincante1['inteligencia'] = 1; }
      if ($this->contrincante1['percepcion'] < 1) { $this->contrincante1['percepcion'] = 1; }
      if ($this->contrincante1['sabiduria'] < 1) { $this->contrincante1['sabiduria'] = 1; }

      if ($this->contrincante2['constitucion'] < 1) { $this->contrincante2['constitucion'] = 1; }
      if ($this->contrincante2['fuerza'] < 1) { $this->contrincante2['fuerza'] = 1; }
      if ($this->contrincante2['rapidez'] < 1) { $this->contrincante2['rapidez'] = 1; }
      if ($this->contrincante2['inteligencia'] < 1) { $this->contrincante2['inteligencia'] = 1; }
      if ($this->contrincante2['percepcion'] < 1) { $this->contrincante2['percepcion'] = 1; }
      if ($this->contrincante2['sabiduria'] < 1) { $this->contrincante2['sabiduria'] = 1; }

    }

    // ***********************************************
    //   Inicializa los valores para el combate
    // ***********************************************

    function Inicializar()
    {

      // Antes de nada vamos a pasarle la edad
      $this->AfectarEdad();

      $this->contrincante1['PVmax'] = 10 + ($this->contrincante1['constitucion'] * 5);
      $this->contrincante1['PMmax'] = 5 + ($this->contrincante1['inteligencia'] * 4) + $this->contrincante1['sabiduria'];
      $this->contrincante1['PV'] = 10 + ($this->contrincante1['constitucion'] * 5);
      $this->contrincante1['PM'] = 5 + ($this->contrincante1['inteligencia'] * 4) + $this->contrincante1['sabiduria'];
      $this->contrincante1['UAP'] = 0;

      $this->contrincante1['emboscado'] = 0;
      $this->contrincante1['aturdido'] = 0;
      $this->contrincante1['aturdido_sin_parar'] = 0;
//      $this->contrincante1['afectado'] = 0;
      $this->contrincante1['invulnerable'] = 0;
//      $this->contrincante1['normal'] = 1;
      $this->contrincante1['parar'] = 0;
      $this->contrincante1['reflejar'] = 0;
      $this->contrincante1['contrahechizo'] = 0;

      $this->contrincante2['PVmax'] = 10 + ($this->contrincante2['constitucion'] * 5);
      $this->contrincante2['PMmax'] = 5 + ($this->contrincante2['inteligencia'] * 4) + $this->contrincante2['sabiduria'];
      $this->contrincante2['PV'] = 10 + ($this->contrincante2['constitucion'] * 5);
      $this->contrincante2['PM'] = 5 + ($this->contrincante2['inteligencia'] * 4) + $this->contrincante2['sabiduria'];
      $this->contrincante2['UAP'] = 0;

      $this->contrincante2['aturdido'] = 0;
      $this->contrincante2['aturdido_sin_parar'] = 0;
//      $this->contrincante2['afectado'] = 0;
      $this->contrincante2['invulnerable'] = 0;
//      $this->contrincante2['normal'] = 1;
      $this->contrincante2['parar'] = 0;
      $this->contrincante2['reflejar'] = 0;  // indetectable!
      $this->contrincante2['contrahechizo'] = 0;

      // Efectos de hechizos

      // Los ultimate solo se pueden lanzar una vez.
      $this->contrincante1['ultimate_lanzado'] = 0;
      $this->contrincante2['ultimate_lanzado'] = 0;

      // Hechizo de acelerar de volcan
      $this->contrincante1['hechizo_v_2_rapidez'] = 0;
      $this->contrincante1['hechizo_v_2_duracion'] = 0;
      $this->contrincante2['hechizo_v_2_rapidez'] = 0;
      $this->contrincante2['hechizo_v_2_duracion'] = 0;

      // Hechizo de miembros ardientes
      $this->contrincante1['hechizo_v_3_duracion'] = 0;
      $this->contrincante2['hechizo_v_3_duracion'] = 0;

      // Hechizo de maldicion
      $this->contrincante1['hechizo_p_4_duracion'] = 0;
      $this->contrincante2['hechizo_p_4_duracion'] = 0;

      // Hechizo de invulnerabilidad
      $this->contrincante1['hechizo_v_4_duracion'] = 0;
      $this->contrincante2['hechizo_v_4_duracion'] = 0;

    }


    // ********************************************************************************
    // ***********************************************
    //   Desglosa a $contrincante para prepararlo
    // ***********************************************

    function Pelear($escenario, $turnos_totales_combate)
    {

      // Tenemos:
      //
      // $contrincante1 : Array de caracteristicas del primer especimen
      // $contrincante2 : Array de caracteristicas del segundo especimen
      // $arbol1 : Arbol del primer especimen en formato Ahnentafel
      // $arbol2 : Arbol del segundo especimen en formato Ahnentafel
      //

      // Vamos a calcular valores iniciales

      $this->Inicializar();

        // Antes de nada, si esta debug de premium activado, guardamos los datos iniciales
        if ($this->debug_premium == 1)
        {
          $this->array_debug_premium[0]['PV_1'] = $this->contrincante1['PV'];
          $this->array_debug_premium[0]['PV_2'] = $this->contrincante2['PV'];
          $this->array_debug_premium[0]['PM_1'] = $this->contrincante1['PM'];
          $this->array_debug_premium[0]['PM_2'] = $this->contrincante2['PM'];
          $this->array_debug_premium[0]['UAP_1'] = $this->contrincante1['UAP'];
          $this->array_debug_premium[0]['UAP_2'] = $this->contrincante2['UAP'];
        }



      if ($this->debug_mode == 1)
      {
        echo ("<br/>");
        echo ("<br/>");
        echo ("Se enfrenta 1 (".$this->contrincante1['PV'].") a 2 (".$this->contrincante2['PV'].")");
      }


      // Estaremos dentro del while mientras que los puntos de alguno no sean cero

      $turno = 1;
      $total_turnos = 0;
      while (  (($this->contrincante1['PV'] > 0) && ($this->contrincante2['PV'] > 0)) &&
		($total_turnos < $turnos_totales_combate))  // Este $turnos_totales_combate es el total, 80 normalmente
      {

        $total_turnos++;
        $this->total_turnos = $total_turnos;
        if ($this->debug_mode == 1)
        {
          echo ("<br/>TURNO ".$total_turnos." ,, [".$this->contrincante1['PV']."/".$this->contrincante1['PVmax']);
          echo ("|".$this->contrincante2['PV']."/".$this->contrincante2['PVmax']."]");
        }


        // Turno ira alternando entre 0 y 1... o si?
        $turno = ($turno + 1) % 2;

        // El resultado final es el opcode perteneciente a una hoja
        if ($turno == 0)
        {
          $n_accion = $this->EligeAccion(1);
          $this->EjecutaAccion(1, $n_accion);
          // Cuando termina el turno de [1], se desactiva el parar y el contrahechizo de [2]
          $this->QuitarPararContrahechizo2();
          $this->BajarAturdimiento1();
          $this->ReducirRapidez();
          $this->ReducirMiembrosArdientes();
          $this->ReducirMaldicion();
          $this->ReducirInvulnerabilidad();
          $this->ComprobarMuerteModificaciones();
          $this->AjustarPVPM();

        } else {
          $n_accion = $this->EligeAccion(2);

          $this->EjecutaAccion(2, $n_accion);
          // Cuando termina el turno de [2], se desactiva el parar y el contrahechizo de [1]
          $this->QuitarPararContrahechizo1();
          $this->BajarAturdimiento2();
          // Ademas, se disminuye en un turno la duracion de rapidez, si la hay
          $this->ReducirRapidez();
          $this->ReducirMiembrosArdientes();
          $this->ReducirMaldicion();
          $this->ReducirInvulnerabilidad();
          $this->ComprobarMuerteModificaciones();
          $this->AjustarPVPM();

        }

        // Despues del turno, si esta debug de premium activado, guardamos los datos de este turno
        if ($this->debug_premium == 1)
        {
          $this->array_debug_premium[$total_turnos]['hoja1'] = $this->contrincante1['hoja'];
          $this->array_debug_premium[$total_turnos]['hoja2'] = $this->contrincante2['hoja'];

          $this->array_debug_premium[$total_turnos]['PV_1'] = $this->contrincante1['PV'];
          $this->array_debug_premium[$total_turnos]['PV_2'] = $this->contrincante2['PV'];
          $this->array_debug_premium[$total_turnos]['PM_1'] = $this->contrincante1['PM'];
          $this->array_debug_premium[$total_turnos]['PM_2'] = $this->contrincante2['PM'];
          $this->array_debug_premium[$total_turnos]['UAP_1'] = $this->contrincante1['UAP'];
          $this->array_debug_premium[$total_turnos]['UAP_2'] = $this->contrincante2['UAP'];

          // Info anyadida
          $this->array_debug_premium[$total_turnos]['emboscado_1'] = $this->contrincante1['emboscado'];
          $this->array_debug_premium[$total_turnos]['emboscado_2'] = $this->contrincante2['emboscado'];
          $this->array_debug_premium[$total_turnos]['aturdido_1'] = $this->contrincante1['aturdido'];
          $this->array_debug_premium[$total_turnos]['aturdido_2'] = $this->contrincante2['aturdido'];
          $this->array_debug_premium[$total_turnos]['aturdido_sin_parar_1'] = $this->contrincante1['aturdido_sin_parar'];
          $this->array_debug_premium[$total_turnos]['aturdido_sin_parar_2'] = $this->contrincante2['aturdido_sin_parar'];

          // Info de magias activas
          $this->array_debug_premium[$total_turnos]['invulnerable_1'] = $this->contrincante1['hechizo_v_4_duracion'];
          $this->array_debug_premium[$total_turnos]['invulnerable_2'] = $this->contrincante2['hechizo_v_4_duracion'];
          $this->array_debug_premium[$total_turnos]['maldito_1'] = $this->contrincante1['hechizo_p_4_duracion'];
          $this->array_debug_premium[$total_turnos]['maldito_2'] = $this->contrincante2['hechizo_p_4_duracion'];
          $this->array_debug_premium[$total_turnos]['rapidez_1'] = $this->contrincante1['hechizo_v_2_duracion'];
          $this->array_debug_premium[$total_turnos]['rapidez_2'] = $this->contrincante2['hechizo_v_2_duracion'];
          $this->array_debug_premium[$total_turnos]['miembros_ardientes_1'] = $this->contrincante1['hechizo_v_3_duracion'];
          $this->array_debug_premium[$total_turnos]['miembros_ardientes_2'] = $this->contrincante2['hechizo_v_3_duracion'];
          $this->array_debug_premium[$total_turnos]['reflejar_1'] = $this->contrincante1['reflejar'];
          $this->array_debug_premium[$total_turnos]['reflejar_2'] = $this->contrincante2['reflejar'];

          // Info de parada
          $this->array_debug_premium[$total_turnos]['parar_1'] = $this->contrincante1['parar'];
          $this->array_debug_premium[$total_turnos]['parar_2'] = $this->contrincante2['parar'];
          $this->array_debug_premium[$total_turnos]['contrahechizo_1'] = $this->contrincante1['contrahechizo'];
          $this->array_debug_premium[$total_turnos]['contrahechizo_2'] = $this->contrincante2['contrahechizo'];

        }

      }

      // La formula de la puntuacion obtenida es la siguiente. Se suma:
      //  - Se suman los puntos de danyo causados al adversario
      //  - Se restan los puntos de danyo recibidos / 2
      //  - +25 si se acabo con el otro
      //  - -25 si el otro acabo contigo
      //  - -15 si nadie murio
      //  - 0 si murieron ambos

      if (($this->contrincante1['PV'] > 0) && ($this->contrincante2['PV'] > 0))
      {
        $puntos1 = -15;
        $puntos2 = -15;
        $arrayfinal['resultado'] = 0;
        if ($this->debug_mode == 1)
        {
          echo ("<br/><br/>ES UN EMPATE !!!!!<br/><br/>");
        }
      }
      if (($this->contrincante1['PV'] > 0) && ($this->contrincante2['PV'] <= 0))
      {
        $puntos1 = 25;
        $puntos2 = -25;
        $arrayfinal['resultado'] = 1;
        if ($this->debug_mode == 1)
        {
          echo ("<br/><br/>GANA EL PRIMER LUCHADOR !!!!!<br/><br/>");
        }
      }
      if (($this->contrincante1['PV'] <= 0) && ($this->contrincante2['PV'] > 0))
      {
        $puntos1 = -25;
        $puntos2 = 25;
        $arrayfinal['resultado'] = 2;
        if ($this->debug_mode == 1)
        {
          echo ("<br/><br/>GANA EL SEGUNDO LUCHADOR !!!!!<br/><br/>");
        }
      }
      if (($this->contrincante1['PV'] <= 0) && ($this->contrincante2['PV'] <= 0))
      {
        $puntos1 = 0;
        $puntos2 = 0;
        $arrayfinal['resultado'] = -1;
        if ($this->debug_mode == 1)
        {
          echo ("<br/><br/>AMBOS LUCHADORES MUEREN !!!!!<br/><br/>");
        }
      }

      $puntos1 = $puntos1 + ($this->contrincante2['PVmax'] - $this->contrincante2['PV']);
      $puntos1 = $puntos1 - (($this->contrincante1['PVmax'] - $this->contrincante1['PV']) / 2);

      $puntos2 = $puntos2 + ($this->contrincante1['PVmax'] - $this->contrincante1['PV']);
      $puntos2 = $puntos2 - (($this->contrincante2['PVmax'] - $this->contrincante2['PV']) / 2);

      $arrayfinal['puntos1'] = $puntos1;
      $arrayfinal['puntos2'] = $puntos2;

      // Resumen de este
      if ($this->debug_premium == 1)
      {
        $this->array_debug_premium['resultado'] = $arrayfinal['resultado'];
        $this->array_debug_premium['puntos1'] = $arrayfinal['puntos1'];
        $this->array_debug_premium['puntos2'] = $arrayfinal['puntos2'];
      }



      return $arrayfinal;

    }


    // ***********************************************
    //   Evaluar a $evaluado contra $contrincante
    // ***********************************************

    function Puntuar($evaluado, $contrincante, $arbol1, $arbol2, $turnos_totales_combate = 80, $debug_premium = 0)
    {

//echo ("#".$contrincante['edad']."#");
      $this->contrincante1 = $evaluado;
      $this->contrincante2 = $contrincante;
//echo "->".$this->contrincante1['edad']."<-";
//die;

      $this->arbol1 = $arbol1;
      $this->arbol2 = $arbol2;

      $this->debug_premium = $debug_premium;

      $arraypelea = $this->Pelear(0, $turnos_totales_combate);

      return $arraypelea;
    }

  }

?>
