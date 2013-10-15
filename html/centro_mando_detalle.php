<?php

  if ($accion == 'detalle')
  {

    $idespecimen = $_REQUEST['idespecimen'];
    if (!is_numeric($idespecimen))
    {
      die;
    }

    $especimen = new Especimen();
    if ($especimen->SacarDatosPorId($link_r, $idespecimen))
    {
      if ($especimen->idpropietario == $idjugador)
      {

        $jugador_campana = new Jugador_campana();
        $sepuede = 0;
        if (($es_premium == 1) || ($es_admin == 1))
        {
          $sepuede = 1;
        } else {
          // Si no es premium, puede ver esta seccion 5 veces al dia
          if ($jugador_campana->Detalle_Comprobar_Permitir($link_w, $idcampana, $idjugador) == true)
          {
            $sepuede = 1;
          }
        }



        if ($sepuede == 1)
        {

          // Ahora vamos a ver si PUEDE verlo por premium
          $jugador_campana->SacarDatos($link_r, $idjugador, $idcampana);
          if (($es_premium != 1) && ($es_admin != 1))
          {
            echo ("<br/>");
            $detalle_veces = $jugador_campana->detalle_veces;
            if ($lang == 'en')
            {
              echo ("You have used ".$detalle_veces." out of 5 daily accesses to the detailed specimen information. This limit dissapears for ");
              echo ("<a href=\"index.php?catid=52&lang=".$lang."&idcampana=".$idcampana."\">");
              echo ("premium users");
              echo ("</a>.");
            } else {
              echo ("Has utilizado ".$detalle_veces." de los 5 accesos diarios a la informaci&oacute;n detallada de especimen. Este l&iacute;mite desaparece para ");
              echo ("<a href=\"index.php?catid=52&lang=".$lang."&idcampana=".$idcampana."\">");
              echo ("usuarios premium");
              echo ("</a>.");
            }
            echo ("<br/>");
            echo ("<br/>");
            echo ("<br/>");
          }

        } // Cerramos el primer se puede





          // ········································
  	  // Vamos a pintar algo mas decente
          // ········································
          echo ("<table id=\"tabla_centro_control\" class=\"tabla_centro_control\">");
          echo ("<tr>");
          if ($lang == 'en')
          {
            echo ("<th width=\"80px\">Deme</th>");
            echo ("<th width=\"80px\">Slot</th>");
            echo ("<th width=\"140px\">Cortex capacity</th>");
            echo ("<th><img src=\"img/medal_gold.png\"></th>");
            echo ("<th><img src=\"img/medal_silver.png\"></th>");
            echo ("<th><img src=\"img/medal_bronze.png\"></th>");
            echo ("<th width=\"60\">Age</th>");
          } else {
            echo ("<th width=\"80px\">Deme</th>");
            echo ("<th width=\"80px\">Slot</th>");
            echo ("<th width=\"170px\">Capacidad cerebral</th>");
            echo ("<th><img src=\"img/medal_gold.png\"></th>");
            echo ("<th><img src=\"img/medal_silver.png\"></th>");
            echo ("<th><img src=\"img/medal_bronze.png\"></th>");
            echo ("<th width=\"60\">Edad</th>");
          }
          echo ("</tr>");
          echo ("<tr style=\"background-color: #333333; font-size: 13px;\">");
//          echo ("<tr>");
          echo ("<td>");
          if ($especimen->iddeme == 1)
          {
            echo ("<img src=\"img/shop_deme_abyss.png\" style=\"vertical-align: middle;\">");
          }
          if ($especimen->iddeme == 2)
          {
            echo ("<img src=\"img/shop_deme_forest.png\" style=\"vertical-align: middle;\">");
          }
          if ($especimen->iddeme == 3)
          {
            echo ("<img src=\"img/shop_deme_volcano.png\" style=\"vertical-align: middle;\">");
          }
          echo ("</td>");
          echo ("<td>");
          echo $especimen->idslot;
          echo ("</td>");
          echo ("<td>");
          echo $especimen->niveles_arbol;
          echo ("</td>");
          echo ("<td>");
          echo $especimen->oro;
          echo ("</td>");
          echo ("<td>");
          echo $especimen->plata;
          echo ("</td>");
          echo ("<td>");
          echo $especimen->bronce;
          echo ("</td>");
          echo ("<td>");
          echo $especimen->edad;
          if ($lang == 'en')
          {
            echo (" aeons");
          } else {
            echo (" eones");
          }
          echo ("</td>");
          echo ("</tr>");
          echo ("</table>");

          echo ("<br/>");
          echo ("<br/>");



        if ($sepuede == 1)
        {


          // Ahora vamos a recorrer los nodos uno a uno
          $arbol = new Arbol();
          $array_arbol = $arbol->Desglosar($especimen->arbol, $especimen->niveles_arbol);
          echo ("<table id=\"tabla_centro_control\" class=\"tabla_centro_control\">");
          echo ("<tr style=\"font-size: 14px;\">");
          if ($lang == 'en')
          {
            echo ("<th width=\"30px\" style=\"font-size: 13px;\">Node</th>");
            echo ("<th width=\"50px\" style=\"font-size: 13px;\">Type</th>");
            echo ("<th width=\"250px\" style=\"font-size: 13px;\">Condition</th>");
            echo ("<th width=\"70px\" style=\"font-size: 13px;\">Destination if condition met</th>");
            echo ("<th width=\"70px\" style=\"font-size: 13px;\">Destination if condition not met</th>");
          } else {
            echo ("<th width=\"30px\" style=\"font-size: 13px;\">Nodo</th>");
            echo ("<th width=\"50px\" style=\"font-size: 13px;\">Tipo</th>");
            echo ("<th width=\"250px\" style=\"font-size: 13px;\">Condici&oacute;n</th>");
            echo ("<th width=\"70px\" style=\"font-size: 13px;\">Destino si cumple condici&oacute;n</th>");
            echo ("<th width=\"70px\" style=\"font-size: 13px;\">Destino si no cumple condici&oacute;n</th>");
          }
          echo ("</tr>");
          $total_hojas = pow(2,($especimen->niveles_arbol - 1));
          $total_ramas = count($array_arbol) - $total_hojas;
          for ($i = 0; $i < count($array_arbol); $i++)
          {
            if ($i % 2 == 1)
            {
              echo ("<tr style=\"background-color: #111111; font-size: 13px;\">");
            } else {
              echo ("<tr style=\"background-color: #333333; font-size: 13px;\">");
            }
            echo ("<td>");
            echo ($i);
            echo ("</td>");
            echo ("<td>");
            if ($i < $total_ramas)
            {
              if ($i == 0)
              {
                if ($lang == 'en')
                {
                  echo ("Root");
                } else {
                  echo ("Ra&iacute;z");
                }
              } else {
                if ($lang == 'en')
                {
                  echo ("Node");
                } else {
                  echo ("Nodo");
                }
              }
            } else {
              if ($lang == 'en')
              {
                echo ("Leaf");
              } else {
                echo ("Hoja");
              }
            }
            echo ("</td>");

            echo ("<td>");
            // La operacion si es una rama
            if ($i < $total_ramas)
            {
              switch($array_arbol[$i]['opcode'])
              {
                case 1:
	    	if ($lang == 'en')
		{
  		  echo ("Jumps if Life < ".$array_arbol[$i]['valor']);
		} else {
		  echo ("Salta si PV < ".$array_arbol[$i]['valor']);
		}
		break;
                case 2:
		if ($lang == 'en')
		{
  		  echo ("Jumps if Life > ".$array_arbol[$i]['valor']);
		} else {
		  echo ("Salta si PV > ".$array_arbol[$i]['valor']);
		}
		break;
                case 3:
		if ($lang == 'en')
		{
  		  echo ("Jumps if Life/LifeMax proportion < ".($array_arbol[$i]['valor'] / 10));
		} else {
		  echo ("Salta si la proporci&oacute;n de PV/PVmax < ".($array_arbol[$i]['valor'] / 10));
		}
		break;
                case 4:
		if ($lang == 'en')
		{
  		  echo ("Jumps if Life/LifeMax proportion > ".($array_arbol[$i]['valor'] / 10));
		} else {
		  echo ("Salta si la proporci&oacute;n de PV/PVmax > ".($array_arbol[$i]['valor'] / 10));
		}
		break;
                case 5:
		if ($lang == 'en')
		{
  		  echo ("Jumps if Mana < ".$array_arbol[$i]['valor']);
		} else {
		  echo ("Salta si Mana < ".$array_arbol[$i]['valor']);
		}
		break;
                case 6:
  		if ($lang == 'en')
		{
  		  echo ("Jumps if Mana > ".$array_arbol[$i]['valor']);
		} else {
		  echo ("Salta si Mana > ".$array_arbol[$i]['valor']);
		}
		break;
                case 7:
		if ($lang == 'en')
		{
  		  echo ("Jumps if Mana/ManaMax proportion < ".($array_arbol[$i]['valor'] / 10));
		} else {
		  echo ("Salta si la proporci&oacute;n de Mana/ManaMax < ".($array_arbol[$i]['valor'] / 10));
		}
		break;
                case 8:
		if ($lang == 'en')
		{
  		  echo ("Jumps if Mana/ManaMax proportion > ".($array_arbol[$i]['valor'] / 10));
		} else {
		  echo ("Salta si la proporci&oacute;n de Mana/ManaMax > ".($array_arbol[$i]['valor'] / 10));
		}
		break;
   	        case 9:
		if ($lang == 'en')
		{
  		  echo ("Jumps if estimated enemy Life < ".$array_arbol[$i]['valor']);
		} else {
		  echo ("Salta si los PV estimados del oponente < ".$array_arbol[$i]['valor']);
		}
		break;
	        case 10:
		if ($lang == 'en')
		{
  		  echo ("Jumps if estimated enemy Life > ".$array_arbol[$i]['valor']);
		} else {
		  echo ("Salta si los PV estimados del oponente > ".$array_arbol[$i]['valor']);
		}
		break;
	        case 11:
		if ($lang == 'en')
		{
  		  echo ("Jumps if latest enemy action was '");
                  switch ($array_arbol[$i]['valor'])
                  {
		    case 1: echo("Physical attack"); break;
		    case 2: echo("Block"); break;
		    case 3: echo("Heal"); break;
		    case 4: echo("Use level 1 ability"); break;
		    case 5: echo("Use level 2 ability"); break;
		    case 6: echo("Use level 3 ability"); break;
		    case 7: echo("Use level 4 ability"); break;
		    case 8: echo("Counterspell"); break;
		    case 9: echo("Ambush"); break;
                  }
                  echo ("'");
		} else {
  		  echo ("Salta si la &uacute;ltima acci&oacute;n del enemigo fue '");
                  switch ($array_arbol[$i]['valor'])
                  {
		    case 1: echo("Ataque f&iacute;sico"); break;
		    case 2: echo("Parar"); break;
		    case 3: echo("Curarse"); break;
		    case 4: echo("Usar habilidad nivel 1"); break;
		    case 5: echo("Usar habilidad nivel 2"); break;
		    case 6: echo("Usar habilidad nivel 3"); break;
		    case 7: echo("Usar habilidad nivel 4"); break;
		    case 8: echo("Contrahechizo"); break;
		    case 9: echo("Emboscar"); break;
                  }
                  echo ("'");
		}
		break;
	        case 12:
		if ($lang == 'en')
		{
// echo $array_arbol[$i]['valor'];
  		  echo ("Jumps if latest enemy action was not '");
                  switch ($array_arbol[$i]['valor'])
                  {
		    case 1: echo("Physical attack"); break;
		    case 2: echo("Block"); break;
		    case 3: echo("Heal"); break;
		    case 4: echo("Use Level 1 ability"); break;
		    case 5: echo("Use Level 2 ability"); break;
		    case 6: echo("Use Level 3 ability"); break;
		    case 7: echo("Use Level 4 ability"); break;
		    case 8: echo("Counterspell"); break;
		    case 9: echo("Ambush"); break;
                  }
                  echo ("'");
		} else {
  		  echo ("Salta si la &uacute;ltima acci&oacute;n del enemigo no fue '");
                  switch ($array_arbol[$i]['valor'])
                  {
		    case 1: echo("Ataque f&iacute;sico"); break;
		    case 2: echo("Parar"); break;
		    case 3: echo("Curarse"); break;
		    case 4: echo("Utilizar habilidad Nivel 1"); break;
		    case 5: echo("Utilizar habilidad Nivel 2"); break;
		    case 6: echo("Utilizar habilidad Nivel 3"); break;
		    case 7: echo("Utilizar habilidad Nivel 4"); break;
		    case 8: echo("Contrahechizo"); break;
		    case 9: echo("Emboscar"); break;
                  }
                  echo ("'");
		}
		break;
	        case 13:
		if ($lang == 'en')
		{
  		  echo ("Jumps if latest specimen action was '");
                  switch ($array_arbol[$i]['valor'])
                  {
		    case 1: echo("Physical attack"); break;
		    case 2: echo("Block"); break;
		    case 3: echo("Heal"); break;
		    case 4: echo("Use Level 1 ability"); break;
		    case 5: echo("Use Level 2 ability"); break;
		    case 6: echo("Use Level 3 ability"); break;
		    case 7: echo("Use Level 4 ability"); break;
		    case 8: echo("Counterspell"); break;
		    case 9: echo("Ambush"); break;
                  }
                  echo ("'");
		} else {
  		  echo ("Salta si la &uacute;ltima acci&oacute;n propia fue '");
                  switch ($array_arbol[$i]['valor'])
                  {
		    case 1: echo("Ataque f&iacute;sico"); break;
		    case 2: echo("Parar"); break;
		    case 3: echo("Curarse"); break;
		    case 4: echo("Utilizar habilidad Nivel 1"); break;
		    case 5: echo("Utilizar habilidad Nivel 2"); break;
		    case 6: echo("Utilizar habilidad Nivel 3"); break;
		    case 7: echo("Utilizar habilidad Nivel 4"); break;
		    case 8: echo("Contrahechizo"); break;
		    case 9: echo("Emboscar"); break;
                  }
                  echo ("'");
		}
		break;

	        case 14:
		if ($lang == 'en')
		{
  		  echo ("Jumps if latest specimen action was not '");
                  switch ($array_arbol[$i]['valor'])
                  {
		    case 1: echo("Physical attack"); break;
		    case 2: echo("Block"); break;
		    case 3: echo("Heal"); break;
		    case 4: echo("Use Level 1 ability"); break;
		    case 5: echo("Use Level 2 ability"); break;
		    case 6: echo("Use Level 3 ability"); break;
		    case 7: echo("Use Level 4 ability"); break;
		    case 8: echo("Counterspell"); break;
		    case 9: echo("Ambush"); break;
                  }
                  echo ("'");
		} else {
  		  echo ("Salta si la &uacute;ltima acci&oacute;n propia no fue '");
                  switch ($array_arbol[$i]['valor'])
                  {
		    case 1: echo("Ataque f&iacute;sico"); break;
		    case 2: echo("Parar"); break;
		    case 3: echo("Curarse"); break;
		    case 4: echo("Utilizar habilidad Nivel 1"); break;
		    case 5: echo("Utilizar habilidad Nivel 2"); break;
		    case 6: echo("Utilizar habilidad Nivel 3"); break;
		    case 7: echo("Utilizar habilidad Nivel 4"); break;
		    case 8: echo("Contrahechizo"); break;
		    case 9: echo("Emboscar"); break;
                  }
                  echo ("'");
		}
		break;


	        case 15:
		if ($lang == 'en')
		{
  		  echo ("Jumps if enemy ");
                  switch ($array_arbol[$i]['valor'])
                  {
		    case 1: echo("is stunned"); break;
		    case 2: echo("is stunned and can't block"); break;
		    case 3: echo("is ambushed (hidden)"); break;
		    case 4: echo("is affected by 'Burning Limbs' (lvl 3 volcano deme ability)"); break;
		    case 5: echo("is affected by 'Invulnerability' (lvl 4 forest deme ability)"); break;
		    case 6: echo("is affected by 'Speed' (lvl 2 volcano deme ability)"); break;
		    case 7: echo("is affected by 'Curse' (lvl 4 abyssal deme ability)"); break;
		    case 8: echo("has already launched Ultimate (lvl 4) spell"); break;
                  }
		} else {
  		  echo ("Salta si el enemigo '");
                  switch ($array_arbol[$i]['valor'])
                  {
		    case 1: echo("est&aacute; aturdido"); break;
		    case 2: echo("est&aacute; aturdido sin poder parar"); break;
		    case 3: echo("est&aacute; emboscado (oculto)"); break;
		    case 4: echo("est&aacute; afectado por 'Miembros Ardientes' (habilidad nivel 3 volc&aacute;n)"); break;
		    case 5: echo("est&aacute; afectado por 'Invulnerabilidad' (habilidad nivel 4 bosque)"); break;
		    case 6: echo("est&aacute; afectado por 'Velocidad' (habilidad nivel 2 volc&aacute;n)"); break;
		    case 7: echo("est&aacute; afectado por 'Maldici&oacute;n' (habilidad nivel 4 profundidades)"); break;
		    case 8: echo("ya ha lanzado su hechizo Ultimate (nivel 4)"); break;
                  }
                  echo ("'");
		}
		break;

	        case 16:
		if ($lang == 'en')
		{
  		  echo ("Jumps if enemy ");
                  switch ($array_arbol[$i]['valor'])
                  {
		    case 1: echo("is not stunned"); break;
		    case 2: echo("is not stunned and can't block"); break;
		    case 3: echo("is not hidden (ambush)"); break;
		    case 4: echo("is not affected by 'Burning Limbs' (lvl 3 volcano deme ability)"); break;
		    case 5: echo("is not affected by 'Invulnerability' (lvl 4 forest deme ability)"); break;
		    case 6: echo("is not affected by 'Speed' (lvl 2 volcano deme ability)"); break;
		    case 7: echo("is not affected by 'Curse' (lvl 4 abyssal deme ability)"); break;
		    case 8: echo("has not already launched Ultimate (lvl 4) spell"); break;
                  }
		} else {
  		  echo ("Salta si el enemigo '");
                  switch ($array_arbol[$i]['valor'])
                  {
		    case 1: echo("no est&aacute; aturdido"); break;
		    case 2: echo("no est&aacute; aturdido sin poder parar"); break;
		    case 3: echo("no est&aacute; emboscado (oculto)"); break;
		    case 4: echo("no est&aacute; afectado por 'Miembros Ardientes' (habilidad nivel 3 volc&aacute;n)"); break;
		    case 5: echo("no est&aacute; afectado por 'Invulnerabilidad' (habilidad nivel 4 bosque)"); break;
		    case 6: echo("no est&aacute; afectado por 'Velocidad' (habilidad nivel 2 volc&aacute;n)"); break;
		    case 7: echo("no est&aacute; afectado por 'Maldici&oacute;n' (habilidad nivel 4 profundidades)"); break;
		    case 8: echo("no ha lanzado a&uacute;n su hechizo Ultimate (nivel 4)"); break;
                  }
                  echo ("'");
		}
		break;


	        case 17:
		if ($lang == 'en')
		{
  		  echo ("Jumps if self ");
                  switch ($array_arbol[$i]['valor'])
                  {
		    case 1: echo("is stunned"); break;
		    case 2: echo("is stunned and can't block"); break;
		    case 3: echo("is ambushed (hidden)"); break;
		    case 4: echo("is affected by 'Burning Limbs' (lvl 3 volcano deme ability)"); break;
		    case 5: echo("is affected by 'Invulnerability' (lvl 4 forest deme ability)"); break;
		    case 6: echo("is affected by 'Speed' (lvl 2 volcano deme ability)"); break;
		    case 7: echo("is affected by 'Curse' (lvl 4 abyssal deme ability)"); break;
		    case 8: echo("has already launched Ultimate (lvl 4) spell"); break;
                  }
		} else {
  		  echo ("Salta si su propio estado es '");
                  switch ($array_arbol[$i]['valor'])
                  {
		    case 1: echo("aturdido"); break;
		    case 2: echo("aturdido sin poder parar"); break;
		    case 3: echo("emboscado (oculto)"); break;
		    case 4: echo("afectado por 'Miembros Ardientes' (habilidad nivel 3 volc&aacute;n)"); break;
		    case 5: echo("afectado por 'Invulnerabilidad' (habilidad nivel 4 bosque)"); break;
		    case 6: echo("afectado por 'Velocidad' (habilidad nivel 2 volc&aacute;n)"); break;
		    case 7: echo("afectado por 'Maldici&oacute;n' (habilidad nivel 4 profundidades)"); break;
		    case 8: echo("que ya ha lanzado su hechizo Ultimate"); break;
                  }
                  echo ("'");
		}
		break;

	        case 18:
		if ($lang == 'en')
		{
  		  echo ("Jumps if self ");
                  switch ($array_arbol[$i]['valor'])
                  {
		    case 1: echo("is not stunned"); break;
		    case 2: echo("is not stunned and can't block"); break;
		    case 3: echo("is not ambushed (hidden)"); break;
		    case 4: echo("is not affected by 'Burning Limbs' (lvl 3 volcano deme ability)"); break;
		    case 5: echo("is not affected by 'Invulnerability' (lvl 4 forest deme ability)"); break;
		    case 6: echo("is not affected by 'Speed' (lvl 2 volcano deme ability)"); break;
		    case 7: echo("is not affected by 'Curse' (lvl 4 abyssal deme ability)"); break;
		    case 8: echo("has not already launched Ultimate (lvl 4) ability"); break;
                  }
		} else {
  		  echo ("Salta si su propio estado '");
                  switch ($array_arbol[$i]['valor'])
                  {
		    case 1: echo("no es aturdido"); break;
		    case 2: echo("no es aturdido sin poder parar"); break;
		    case 3: echo("no es emboscado (oculto)"); break;
		    case 4: echo("no es afectado por 'Miembros Ardientes' (habilidad nivel 3 volc&aacute;n)"); break;
		    case 5: echo("no es afectado por 'Invulnerabilidad' (habilidad nivel 4 bosque)"); break;
		    case 6: echo("no es afectado por 'Velocidad' (habilidad nivel 2 volc&aacute;n)"); break;
		    case 7: echo("no es afectado por 'Maldici&oacute;n' (habilidad nivel 4 profundidades)"); break;
		    case 8: echo("es que no ha lanzado a&uacute;n su habilidad Ultimate (nivel 4)"); break;
                  }
                  echo ("'");
		}
		break;

                case 19:
		if ($lang == 'en')
		{
  		  echo ("Jumps if current turn < ".$array_arbol[$i]['valor']);
		} else {
		  echo ("Salta si turno actual es < ".$array_arbol[$i]['valor']);
		}
		break;

                case 20:
		if ($lang == 'en')
		{
  		  echo ("Jumps if current turn > ".$array_arbol[$i]['valor']);
		} else {
		  echo ("Salta si turno actual es > ".$array_arbol[$i]['valor']);
		}
		break;

              }
            } else {
            // La operacion si es una hoja
              if ($lang == 'en')
              {
                switch ($array_arbol[$i]['valor'])
                {
                  case 1: echo ("Physical attack"); break;
                  case 2: echo ("Block attack"); break;
                  case 3: echo ("Heal self"); break;
		  case 4: echo ("Use Level 1 ability"); break;
		  case 5: echo ("Use Level 2 ability"); break;
		  case 6: echo ("Use Level 3 ability"); break;
		  case 7: echo ("Use Level 4 ability"); break;
                  case 8: echo ("Counterspell"); break;
                  case 9: echo ("Ambush"); break;
                }
              } else {
               switch ($array_arbol[$i]['valor'])
               {
                 case 1: echo ("Ataque f&iacute;sico"); break;
                 case 2: echo ("Bloquear ataque"); break;
                 case 3: echo ("Curarse"); break;
                 case 4: echo ("Utilizar habilidad Nivel 1"); break;
	         case 5: echo ("Utilizar habilidad Nivel 2"); break;
	         case 6: echo ("Utilizar habilidad Nivel 3"); break;
	         case 7: echo ("Utilizar habilidad Nivel 4"); break;
                 case 8: echo ("Contrahechizo"); break;
                 case 9: echo ("Emboscar"); break;
               }
             }
//echo ("#".$array_arbol[$i]['valor']."#");
           }
           echo ("</td>");

           echo ("<td>");
           if ($i < $total_ramas)
           {
            echo ((2*$i) + 1);
           } else { echo ("-"); }
           echo ("</td>");
           echo ("<td>");
           if ($i < $total_ramas)
           {
             echo ((2*$i) + 2);
           } else { echo ("-"); }
           echo ("</td>");

           echo ("</tr>");

           if ($i == ($total_ramas - 1))
           {
             echo ("<tr>");
             if ($lang == 'en')
             {
               echo ("<th width=\"30px\" style=\"font-size: 13px;\">Node</th>");
               echo ("<th width=\"50px\" style=\"font-size: 13px;\">Type</th>");
               echo ("<th width=\"350px\" style=\"font-size: 13px;\">Action</th>");
               echo ("<th width=\"70px\" style=\"font-size: 13px;\">Destination (condition met)</th>");
               echo ("<th width=\"70px\" style=\"font-size: 13px;\">Destination (condition not met)</th>");
             } else {
               echo ("<th width=\"30px\" style=\"font-size: 13px;\">Nodo</th>");
               echo ("<th width=\"50px\" style=\"font-size: 13px;\">Tipo</th>");
               echo ("<th width=\"350px\" style=\"font-size: 13px;\">Acci&oacute;n</th>");
               echo ("<th width=\"70px\" style=\"font-size: 13px;\">-</th>");
               echo ("<th width=\"70px\" style=\"font-size: 13px;\">-</th>");
//        echo ("<th width=\"70px\" style=\"font-size: 13px;\">Destino (no cumple condici&oacute;n)</th>");
             }
//          echo ("<td colspan=\"5\"></td>");
            echo ("</tr>");
           }
         }
         echo ("</table>");

       } else {

         if ($lang == 'en')
         {
           echo ("<p class=\"error\">Error: You can't access to the detail screen on more than 5 specimens daily if you are not a premium user.</p>");
         } else {
           echo ("<p class=\"error\">Error: No puedes acceder al detalle de m&aacute;s de 5 espec&iacute;menes al d&iacute;a si no eres usuario premium.</p>");
         }
       }


     } else {
       if ($lang == 'en')
       {
         echo ("<p class=\"errorsutil\">Error: Specimen is not yours</p>");
       } else {
         echo ("<p class=\"errorsutil\">Error: El especimen no es tuyo</p>");
       }
     }
    } else {
       if ($lang == 'en')
       {
         echo ("<p class=\"errorsutil\">Error: Specimen does not exist</p>");
       } else {
         echo ("<p class=\"errorsutil\">Error: El especimen no existe</p>");
       }
    }


  }

?>
