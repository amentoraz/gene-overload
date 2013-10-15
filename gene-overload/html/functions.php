<?php

  function AjustarFecha($anyo, $mes, $dia, $hora, $min)
  {
//echo $dia."#";
        if ($min >= 60) { $hora++; $min = $min - 60; }
        if ($min < 0) { $hora--; $min = $min + 60; }
        if ($hora >= 24) { $dia++; $hora = $hora - 24;}
        if ($hora < 0) { $dia--; $hora = $hora + 24;}
        // Ahora dia con el mes
        if ($dia < 0) {
          $mes--;
          if ($mes < 0) { $mes = 12; $anyo--; }
          switch ($mes)
          {
                case 1: $dia = 31; break;
                case 2: if (($anyo % 4) == 0) { $dia = 28; } else { $dia = 29; } break;
                case 3: $dia = 31; break;
                case 4: $dia = 30; break;
                case 5: $dia = 31; break;
                case 6: $dia = 30; break;
                case 7: $dia = 31; break;
                case 8: $dia = 31; break;
                case 9: $dia = 30; break;
                case 10: $dia = 31; break;
                case 11: $dia = 30; break;
                case 12: $dia = 31; break;
          }
        }
        // Y comprobamos si nos hemos pasado de dia del mes
        switch ($mes)
        {
                case 1: if ($dia > 31) { $dia = 1; $mes++; } ; break;
                case 2: if (  ((($anyo % 4) == 0) && ($dia > 28)) || ((($anyo % 4) != 0) && ($dia > 29)) ) { $dia = 1; $mes++; } break;
                case 3: if ($dia > 31) { $dia = 1; $mes++; } ; break;
                case 4: if ($dia > 30) { $dia = 1; $mes++; } ; break;
                case 5: if ($dia > 31) { $dia = 1; $mes++; } ; break;
                case 6: if ($dia > 30) { $dia = 1; $mes++; } ; break;
                case 7: if ($dia > 31) { $dia = 1; $mes++; } ; break;
                case 8: if ($dia > 31) { $dia = 1; $mes++; } ; break;
                case 9: if ($dia > 30) { $dia = 1; $mes++; } ; break;
                case 10: if ($dia > 31) { $dia = 1; $mes++; } ; break;
                case 11: if ($dia > 30) { $dia = 1; $mes++; } ; break;
                case 12: if ($dia > 31) { $dia = 1; $mes = 1; $anyo++; } ; break;
         }
    $arrayf['anyo'] = $anyo;
    $arrayf['mes'] = $mes;
//    if ($dia < 10) { $dia = '0'.$dia; }
    if (strlen($dia) < 2) { $dia = '0'.$dia; }
    $arrayf['dia'] = $dia;
//    if ($hora < 10) { $hora = '0'.$hora; }
    if (strlen($hora) < 2) { $hora = '0'.$hora; }
    $arrayf['hora'] = $hora;
//    if ($min < 10) { $min = '0'.$min; }
    if (strlen($min) < 2) { $min = '0'.$min; }
    $arrayf['min'] = $min;
//print_r($arrayf);
    return $arrayf;
  }

?>
