<?php

  // DEBUG ******* PROTECCION PARA QUE NO SE VEA
//  if ($es_admin == 1)
//  {

    if (($idjugador != null) && ($idjugador != '') && ($accion != 'logout'))
    {
      $jugador_tutorial = new Jugador();
      $tutorial = $jugador_tutorial->SacarTutorial($link_r, $idjugador);

      // Si tutorial = -1 es que esta completado
      // Si tutorial = 15 es que el jugador lo ha acabado
      if (($tutorial >= 0) && ($tutorial < 15))
      {

        // Vamos con las condiciones para darte paso al siguiente
        $paso_completado = 0;  // Esta variable almacena si en esta carga de la pagina se ha completado un paso
        switch($tutorial)
        {
          case 0:
		// En el primer caso, si llega al centro de mando le pasamos al 1
                if (($catid == 3) && ($idcampana != '') && ($idcampana != null) && ($accion == ''))
                {
                  $tutorial = 1;
                  $jugador_tutorial->AlterarTutorial($link_w, $tutorial, $idjugador);
                  $paso_completado = 1;
                }
                break;
          case 1:
		// En el segundo caso, si evalua a sus especimenes le pasamos al 2
                if (($catid == 3) && ($idcampana != '') && ($idcampana != null) && ($accion == 'evaluar'))
                {
                  $tutorial = 2;
                  $jugador_tutorial->AlterarTutorial($link_w, $tutorial, $idjugador);
                  $paso_completado = 1;
                }
                break;
          case 2:
		// Si evalua individualmente a un especimen
                if (($catid == 3) && ($idcampana != '') && ($idcampana != null) && ($accion == 'evaluar'))
                {
//                if (($catid == 3) && ($idcampana != '') && ($idcampana != null) && ($accion == 'reevaluar_individuo'))
//                {
                  $tutorial = 3;
                  $jugador_tutorial->AlterarTutorial($link_w, $tutorial, $idjugador);
                  $paso_completado = 1;
                }
                break;
          case 3:
		// Si evoluciona una generacion
                if (($catid == 3) && ($idcampana != '') && ($idcampana != null) && ($accion == 'evolucionar_generacion'))
                {
                  $tutorial = 4;
                  $jugador_tutorial->AlterarTutorial($link_w, $tutorial, $idjugador);
                  $paso_completado = 1;
                }
                break;
          case 4:
		// Si compra una movida de mutacion
                $t_idobjeto = $_REQUEST['idobjeto'];
                if (($catid == 4) && ($idcampana != '') && ($idcampana != null) && ($accion == 'comprar_objeto') && ($t_idobjeto == 4))
                {
                  $tutorial = 5;
                  $jugador_tutorial->AlterarTutorial($link_w, $tutorial, $idjugador);
                  $paso_completado = 1;
                }
                break;
          case 5:
		// Si consume el objeto
                if (($catid == 4) && ($idcampana != '') && ($idcampana != null) && ($accion == 'consumir_objeto'))
                {
                  $tutorial = 6;
                  $jugador_tutorial->AlterarTutorial($link_w, $tutorial, $idjugador);
                  $paso_completado = 1;
                }
                break;
          case 6:
		// Si realiza la expansion de algun deme
                $t_idobra = $_REQUEST['idobra'];
                if (($catid == 4) && ($idcampana != '') && ($idcampana != null) && ($accion == 'comprar_obra') && ($t_idobra < 4))
                {
                  $tutorial = 7;
                  $jugador_tutorial->AlterarTutorial($link_w, $tutorial, $idjugador);
                  $paso_completado = 1;
                }
                break;
          case 7:
		// Si aumenta o disminuye la mutacion
                if (
			(($catid == 3) && ($idcampana != '') && ($idcampana != null) && ($accion == 'subir_ratio_mutacion')) ||
			(($catid == 3) && ($idcampana != '') && ($idcampana != null) && ($accion == 'bajar_ratio_mutacion'))
			)
                {
                  $tutorial = 8;
                  $jugador_tutorial->AlterarTutorial($link_w, $tutorial, $idjugador);
                  $paso_completado = 1;
                }
                break;
          case 8:
		// Si compra algun elemento de Cesio
                $t_idobjeto = $_REQUEST['idobjeto'];
                if (
			(($catid == 4) && ($idcampana != '') && ($idcampana != null) && ($accion == 'comprar_objeto') && ($t_idobjeto == 5)) ||
			(($catid == 4) && ($idcampana != '') && ($idcampana != null) && ($accion == 'comprar_objeto') && ($t_idobjeto == 6)) ||
			(($catid == 4) && ($idcampana != '') && ($idcampana != null) && ($accion == 'comprar_objeto') && ($t_idobjeto == 7))
			)
                {
                  $tutorial = 9;
                  $jugador_tutorial->AlterarTutorial($link_w, $tutorial, $idjugador);
                  $paso_completado = 1;
                }
                break;
          case 9:
		// Si compra algun elemento de cambio de sexo
                $t_idobjeto = $_REQUEST['idobjeto'];
                if (
			(($catid == 4) && ($idcampana != '') && ($idcampana != null) && ($accion == 'comprar_objeto') && ($t_idobjeto == 1)) ||
			(($catid == 4) && ($idcampana != '') && ($idcampana != null) && ($accion == 'comprar_objeto') && ($t_idobjeto == 2)) ||
			(($catid == 4) && ($idcampana != '') && ($idcampana != null) && ($accion == 'comprar_objeto') && ($t_idobjeto == 3))
			)
                {
                  $tutorial = 10;
                  $jugador_tutorial->AlterarTutorial($link_w, $tutorial, $idjugador);
                  $paso_completado = 1;
                }
                break;
          case 10:
		// Si compra algun elemento de Cesio
                if (
			(($catid == 3) && ($idcampana != '') && ($idcampana != null) && ($accion == 'evolucionar_deme')) ||
			(($catid == 3) && ($idcampana != '') && ($idcampana != null) && ($accion == 'evolucionar_individuo'))
			)
                {
                  $tutorial = 11;
                  $jugador_tutorial->AlterarTutorial($link_w, $tutorial, $idjugador);
                  $paso_completado = 1;
                }
                break;
          case 11:
		// Si compra algun elemento de Cesio
                if (
			(($catid == 3) && ($idcampana != '') && ($idcampana != null) && ($accion == 'detalle'))
			)
                {
                  $tutorial = 12;
                  $jugador_tutorial->AlterarTutorial($link_w, $tutorial, $idjugador);
                  $paso_completado = 1;
                }
                break;
          case 12:
		// Si compra algun elemento de Cesio
                if (
			(($catid == 3) && ($idcampana != '') && ($idcampana != null) && ($accion == 'apuntar_torneo'))
			)
                {
                  $tutorial = 13;
                  $jugador_tutorial->AlterarTutorial($link_w, $tutorial, $idjugador);
                  $paso_completado = 1;
                }
                break;
          case 13:
		// Si compra algun elemento de Cesio
                if (
			(($catid == 7) && ($idcampana != '') && ($idcampana != null) && ($accion == 'leer_reporte'))
			)
                {
                  $tutorial = 14;
                  $jugador_tutorial->AlterarTutorial($link_w, $tutorial, $idjugador);
                  $paso_completado = 1;
                }
                break;
          case 14:
		// Si compra algun elemento de Cesio
                if (
			(($catid == 8) && ($idcampana != '') && ($idcampana != null) && ($accion == 'preview'))
			)
                {
                  $tutorial = 15;
                  $jugador_tutorial->AlterarTutorial($link_w, $tutorial, $idjugador);
                  $paso_completado = 1;
                }
                break;
        }

//echo $paso_completado;

        // Ahora pintamos una estructura de tabla generica
        ?>
        <div class="espacio"></div>
        <table width="100%" style="
			color: #cdbd00;
			padding: 2px;
                        border: 1px solid #000;
                        text-align: center;
			">
         <tr>
          <td>
           <table width="100%"
		style="  text-align: center;
			background-color: #000;
			padding: 7px; 
		">
            <tr><td>
             <center><b>
              <?php
                if ($lang == 'en')
                {
                  echo ("Learn to play!");
                } else {
                  echo ("&iexcl;Aprende a jugar!");
                }
              ?>
             </b></center>
             <br/>
             <?php
              echo ("<center>");
              echo ("<span style=\"color: #fe9999;\">");
              if ($lang == 'en')
              {
                if ($tutorial == 15)
                {
                  echo ("<i>Tutorial complete</i>");
                } else {
                  echo ("<i>Step ".($tutorial+1)."/15</i>");
                }
              } else {
                if ($tutorial == 15)
                {
                  echo ("<i>Tutorial completado</i>");
                } else {
                  echo ("<i>Paso ".($tutorial+1)."/15</i>");
                }
              }
              echo ("</span>");
              echo ("</center>");
              echo ("<br/>");
                if ($paso_completado == 1) {
                  echo ("<center>");
                  echo ("<span style=\"color: #fe9999;\"><b>");
                  if ($tutorial > 5)
                  {
                    $tutorial_ganar = 5;
                  } else {
                    $tutorial_ganar = $tutorial;
                  }
                  if ($lang == 'en')
                  {
                    echo ("<b>Congratulations! You've completed step ".($tutorial)."</b>.<br/><br/>");
//                    echo ("You receive ".$tutorial_ganar."<img src=\"http://www.geneoverload.com/img/goldcoin.gif\"><br/><br/>");
                    echo ("You receive ".$tutorial_ganar."<img src=\"img/goldcoin.gif\"><br/><br/>");
                  } else {
                    echo ("<b>Felicidades! Has completado el paso ".($tutorial)."</b>.<br/><br/>");
                    echo ("Recibes ".$tutorial_ganar."<img src=\"img/goldcoin.gif\"><br/><br/>");
                  }
                  echo ("</b></span>");
                  echo ("</center>");
                  // Vamos a meterle la pasta al jugador
                  $jugador_tutorial_camp = new Jugador_Campana();
		  $jugador_tutorial_camp->SumarDinero($link_w, $idjugador, $idcampana, $tutorial_ganar);
                  $dinerito = $dinerito + $tutorial_ganar;
/*
                  if ($tutorial == 14)
                  {
                    echo ("<span style=\"font-size: 11px;\" id=\"tutorial\">");
                    if ($lang == 'en')
                    {
			?>
			Now you know all the basics to become a successful genetist! You might want to understand the game in more depth and for that I recommend you the <b>"How to play"</b>
			section. 
			<?php
                    } else {
			?>
			Now you know all the basics to become a successful genetist! You might want to understand the game in more depth and for that I recommend you the "
			<?php
                    }
                    echo ("</span>");
                  }
*/
                }
              echo ("<br/>");
              switch ($tutorial)
              {
                // Para a&ntilde;adir un paso, solo hay que meterle el case aqui y arriba en la completitud del anterior.
                case 0:
                    echo ("<span style=\"font-size: 11px;\" id=\"tutorial\">");
                    if ($lang == 'en')
                    {
                      ?>
			Gene Overload looks hard to learn in the beginning. This tutorial will guide you through your first steps in the game.<br/><br/>
                        The first thing you have to do is to join a campaign. A campaign is a whole Gene Overload game with different players and maybe even slightly different rules and settings.
			To join a campaign you click on <b>"Frontpage"</b>, then <b>"Join this campaign"</b>. <br/><br/>
			Once you have joined a campaign, click on <b>"Play this campaign"</b> and reach the <u><b>Command Center</b></u>. 
			I'll see you there.
                      <?php
                    } else {
                      ?>
			Gene Overload parece dif&iacute;cil al principio. Este tutorial te guiar&aacute; a trav&eacute;s de tus primeros pasos en el juego.<br/><br/>
			Lo primero que tienes que hacer es unirte a una campa&ntilde;a. Una campa&ntilde;a es un juego completo de Gene Overload con distintos jugadores y quiz&aacute; reglas y disposiciones ligeramente distintos.
			Para unirte a una campa&ntilde;a, haz click en <b>"Portada"</b>, y luego en <b>"Apuntarte a esta campa&ntilde;a"</b>. <br/><br/>
			Una vez te hayas unido, haz click en <u><b>"Jugar a esta campa&ntilde;a"</b></u>, con lo que
			ir&aacute;s al Centro de Mando. Nos veremos all&iacute;.
                      <?php
                    }
                    echo ("<br/>");
                    echo ("<br/>");
                    echo ("</span>");
                    break;
                case 1:
                    echo ("<span style=\"font-size: 11px;\" id=\"tutorial\">");
                    if ($lang == 'en')
                    {
                      ?>
                        Now on the Command Center you will see three tables. Each of them represents a <b>"deme"</b>, which you could consider like an ecosystem, and contains several specimens
			which belong to that <b>"deme"</b>.<br/><br/>
			Your specimens have been randomly generated, and they even have names, but how do you know how good fighters they are?<br/><br/>
			You have to train them. The way you do this is clicking on <b>"Test non-evaluated samples"</b>. This will give you an idea on how good they are fighting against each other.
                      <?php
                    } else {
                      ?>
			Ahora en el Centro de Mando ver&aacute;s tres tablas. Cada una de ellas representa a un <b>"deme"</b>, que podr&iacute;as considerar como un ecosistema, y contiene varios
			espec&iacute;menes que pertenecen a ese <b>"deme"</b>.
			Tus espec&iacute;menes han sido generados aleatoriamente, e incluso tienen nombre, pero, &iquest;C&oacute;mo sabes lo buenos luchadores que son?<br/><br/>
			Tienes que entrenarlos. La manera de hacer esto es cliquear en <b>"Evaluar espec&iacute;menes sin puntuar"</b>. Esto te dar&aacute; una idea de lo buenos 
			que son luchando unos contra otros.
                      <?php
                    }
                    echo ("<br/>");
                    echo ("<br/>");
                    echo ("</span>");
                    break;
                case 2:
                    echo ("<span style=\"font-size: 11px;\" id=\"tutorial\">");
                    if ($lang == 'en')
                    {
                      ?>
			Now you'll see that under the '<b>Score</b>' column on each table, there's a number which goes from very negative and very red, to very positive and very green.<br/><br/>
			The way it works is easy: The <span style="color: #00aa00">greener</span> and higher the number, the better your specimen is when fighting against others. The
			<span style="color: #aa0000;">red</span> color means a bad score.<br/><br/>
			However, this standard free test is not very accurate. Sometimes you might want to test a specimen more specifically. To do this you have a special option called <b>"Evaluate"</b>
			you can find for each row in every table with this icon :<br/><br/>
			<img src="img/espadas1.gif">
			<br/><br/>
			For the next step on this tutorial, evaluate one of your specimens. When you're done with this, click again on the basic evaluation to go on.
                      <?php
                    } else {
                      ?>
			Ahora ver&aacute;s que bajo la columna de '<b>Puntos</b>' en cada tabla, hay un n&uacute;mero que va desde muy negativo y muy rojo, a muy positivo y muy verde.<br/><br/>
			La forma en la que funciona es sencilla: Cuanto m&aacute;s <span style="color: #00aa00">verde</span> y m&aacute;s alto sea el n&uacute;mero, mejor ser&aacute; tu especimen 
			luchando contra otros. El color <span style="color: #aa0000;">rojo</span> es una mala puntuaci&oacute;n.<br/><br/>
			Sin embargo, esta prueba est&aacute;ndar no es muy exacta. A veces puede que quieras probar m&aacute;s en detalle a un especimen. Para hacer esto tienes una opci&oacute;n
			especial llamada <b>"Evaluar"</b> que puedes encontrar con en cada fila de cada tabla con este icono:<br/><br/>
			<img src="img/espadas1.gif">
			<br/><br/>
			Para el siguiente paso en este tutorial, eval&uacute;a a uno o varios de tus espec&iacute;menes. Cuando acabes, vuelve a cliquear en la evaluaci&oacute;n b&aacute;sica para continuar.
                      <?php
                    }
                    echo ("<br/>");
                    echo ("<br/>");
                    echo ("</span>");
                    break;
                case 3:
                    echo ("<span style=\"font-size: 11px;\" id=\"tutorial\">");
                    if ($lang == 'en')
                    {
                      ?>
			Good! When your specimens are standardly tested, they just play a few fights. When you pay for a specific evaluation like you just did, they play against <b>every</b> other specimen you have.<br/><br/>
			<b>Look how its score has changed!</b><br/><br/>
			Standard evaluation is free, and when you evolve a new specimen it will be automatically tested. But this other evaluation you just did costs 1 credit. 
			Some of your specimens might have nice scores, but think those scores are against your other specimens. Probably they're pretty bad fighters, and the low score ones are even worse.
			You must evolve a lot of generations so evolution makes them competent.<br/><br/>
			Now how do you evolve them? In the upper part on the Command Center click on <b>"Evolve generation"</b>. All your current specimens will be replaced by brand new ones evolved from them.
                      <?php
                    } else {
                      ?>
			&iexcl;Bien! Cuando eval&uacute;as de manera est&aacute;ndar a tus espec&iacute;menes, apenas hacen algunas batallas entre ellos. Cuando pagas por una evaluaci&oacute;n espec&iacute;fica como acabas de hacer,
			juegan contra <b>todos</b> los dem&aacute;s espec&iacute;menes que tienes. <b>&iexcl;Mira c&oacute;mo su puntuaci&oacute;n ha cambiado!</b><br/><br/>
			La evaluaci&oacute;n est&aacute;ndar es gratis, y cuando evoluciones a un nuevo especimen, se llevar&aacute; a cabo autom&aacute;ticamente. Pero esta, la que acabas de hacer,
			cuesta 1 cr&eacute;dito.<br/><br/>
			Puede que algunos de tus espec&iacute;menes tengan buenas puntuaciones, pero piensa que esa puntuaci&oacute;n la obtienen luchando contra tus otros espec&iacute;menes.
			Prob&aacute;blemente son luchadores bastante malos, y los de puntuaciones bajas son peores todav&iacute;a.
			Debes evolucionar un mont&oacute;n de generaciones para que la evoluci&oacute;n los haga realmente competentes.<br/><br/>
			&iquest;Y c&oacute;mo los evoluciones? En la parte de arriba de la pantalla del Centro de Mando, cliquea en <b>"Evolucionar generaci&oacute;n"</b>. Todos tus espec&iacute;menes
			actuales ser&aacute;n sustitu&iacute;dos por nuevos que han evolucionado desde ellos.
                      <?php
                    }
                    echo ("<br/>");
                    echo ("<br/>");
                    echo ("</span>");
                    break;
                case 4:
                    echo ("<span style=\"font-size: 11px;\" id=\"tutorial\">");
                    if ($lang == 'en')
                    {
                      ?>
			Just look at that! All your specimens have been replaced by brand new ones. They reproduced, and their children inherited different parts from each parent.
			Also some of them might have new parts which do not come from their parents. These random modifications are called <b>"mutations"</b>, and are extremely important in Nature.<br/><br/>
			The <b>"Mutation ratio"</b> you see up the screen in your Command Center tells you the chances every child has to suffer a mutation. If its 30% then 3 out of 10 new
			specimens will have a mutation. Well, we're going to change that ratio.<br/><br/>
			To change this you must buy something in the <b>Shop</b>. Well, just click on the Shop tab and then click on the <b>Three Mile Island dust</b> to buy it.
                      <?php
                    } else {
                      ?>
			&iexcl;Mira eso! Todos tus espec&iacute;menes han sido sustitu&iacute;dos por otros nuevos. Pap&aacute; puso una semillita en mam&aacute;,
			y sus hijos heredaron partes distintas de cada progenitor.
			Tambi&eacute;n algunos de ellos podr&iacute;an tener partes nuevas que no vienen de sus padres. Estas modificaciones aleatorias se llaman <b>"mutaciones"</b> y son
			extremadamente importantes en la Naturaleza.<br/><br/>
			El <b>"Ratio de mutaci&oacute;n"</b> que ves arriba de la pantalla en tu Centro de Mando te dice las posibilidades que hay de que cada nuevo especimen sufra una mutaci&oacute;n.
			Si es de un 30%, entonces 3 de cada 10 nuevos espec&iacute;menes sufrir&aacute;n mutaciones. Bueno, vamos a cambiar ese ratio.<br/><br/>
			Para cambiarlo, antes tienes que comprar algo en la <b>Tienda</b>. Bueno, s&oacute;lo tienes que hacer click en la pesta&ntilde;a de la Tienda y entonces
			cliquear en el <b>Polvo de Three Mile Island</b> para comprarlo.
                      <?php
                    }
                    echo ("<br/>");
                    echo ("<br/>");
                    echo ("</span>");
                    break;
                case 5:
                    echo ("<span style=\"font-size: 11px;\" id=\"tutorial\">");
                    if ($lang == 'en')
                    {
                      ?>
			Hey, you just bought your first object in the shop! Well that is, if you had enough money to do so. You might want to visit the shop regularly and buy a few other
			things. Some are objects which remain in your <b>inventory</b> until you decide to use them, and actually you will see the <b>Three Mile Island dust</b> there. The objects you
			have are the top 5 (usually empty) squares you see in the top of the Shop screen.<br/><br/>
			Now to use the object you just bought, you only need to click on it in your <b>inventory</b>. To complete this step you must consume an object from your inventory. Make it
			the Three Mile Island dust and you will be able to change the mutation ratio!
                      <?php
                    } else {
                      ?>
			&iexcl;Hey, acabas de comprar tu primer objeto de la tienda! Bueno, si es que ten&iacute;as dinero para pagarlo, claro. Quiz&aacute; quieras visitar regularmente la tienda
			y comprar otras cosas. Algunos son objetos que permanecen en tu <b>inventario</b> hasta que decides usarlos, y de hecho ver&aacute;s all&iacute; el <b>Polvo de Three Mile Island</b>.
			Los objetos que tienes son los 5 cuadrados (habitualmente vac&iacute;os) que ves en la parte de arriba de la pantalla de la Tienda.<br/><br/>
			Ahora para usar el &uacute;ltimo objeto que acabas de comprar, tan s&oacute;lo tienes que cliquear en &eacute;l en tu <b>inventario</b>. Para completar este paso debes
			consumir un objeto de tu inventario. &iexcl;Haz que sea el Polvo de Three Mile Island y podr&aacute;s cambiar el ratio de mutaci&oacute;n!
                      <?php
                    }
                    echo ("<br/>");
                    echo ("<br/>");
                    echo ("</span>");
                    break;
                case 6:
                    echo ("<span style=\"font-size: 11px;\" id=\"tutorial\">");
                    if ($lang == 'en')
                    {
                      ?>
			You just consumed an object from your inventory. Everything you buy in the shop except for the <b>Deme Slot Expansion</b> and increasing the <b>Cerebral Cortex Capacity</b> will 
			go to your inventory.<br/><br/>
			These expansions are structural modifications that take place inmediately. They are increasingly expensive, but can bring important benefits. For example, the more slots you have for
			specimens, the more probabilities you have to evolve better specimens. In fact, increasing the slots as much as you can is key to obtain good results.
			<br/><br/>
			To complete this step from the tutorial, buy a deme slot expansion for any of your demes. In fact, you should buy as much as you can.
                      <?php
                    } else {
                      ?>
			Acabas de consumir un objeto de tu inventario. Todo lo que compras en la tienda a excepci&oacute;n de la <b>Expansi&oacute;n de Huecos de Deme</b> y el <b>Ampliar la capacidad
			cerebral y estructural</b> ir&aacute;n a tu inventario.<br/><br/>
			Estas expansiones son modificaciones estructurales que tienen lugar inmediatamente. Cada vez que compras una su precio aumenta, pero pueden traer grandes beneficios. Por ejemplo,
			cuantos m&aacute;s huecos tengas para los espec&iacute;menes, mayores probabilidades tendr&aacute;s de evolucionar mejores espec&iacute;menes. De hecho, aumentar los
			huecos todo lo que puedas es clave para obtener buenos resultados.
			<br/><br/>
			Para completar este paso del tutorial, compra una expansi&oacute;n del hueco para cualquiera de tus demes. De hecho, deber&iacute;as de comprar todos los que puedas.
                      <?php
                    }
                    echo ("<br/>");
                    echo ("<br/>");
                    echo ("</span>");
                    break;
                case 7:
                    echo ("<span style=\"font-size: 11px;\" id=\"tutorial\">");
                    if ($lang == 'en')
                    {
                      ?>
			You have a new slot! Go to your Command Center and check that there is one more row in the table corresponding to the deme for which you bought the slot.<br/><br/>
			Now that we are here, you'll see that near the <b>Mutation ratio</b> indicator, you now have two arrows, one <span style="color: #00aa00;">green</span> 
			and one <span style="color: #aa0000;">red</span>. Everytime you click them, you will increase
			or decrease by 1% the mutation ratio.<br/><br/>
			A ratio too low and your specimens will stagnate. A ratio too high and your specimens will lose interesting characteristics due to excessive mutation. Now try and modify it!
                      <?php
                    } else {
                      ?>
			&iexcl;Tienes un nuevo hueco! Vuelve a tu Centro de Mando y comprueba que hay una fila m&aacute;s en la tabla que corresponde al deme para el que has comprado el hueco.<br/><br/>
			Ahora que estamos aqu&iacute;, ver&aacute;s que cerca del indicador del <b>Ratio de mutaci&oacute;n</b> tienes ahora dos flechas, una <span style="color: #00aa00">verde</span>
			y otra <span style="color: #aa0000;">roja</span>. Cada vez que haces
			click sobre ellas, aumentar&aacute;s o disminuir&aacute;s un 1% el ratio de mutaci&oacute;n.<br/><br/>
			Un ratio demasiado bajo y la evoluci&oacute;n de tus espec&iacute;menes se atascar&aacute;. Un ratio demasiado alto y tus espec&iacute;menes podr&iacute;an perder
			caracter&iacute;sticas interesantes debido a una mutaci&oacute;n excesiva. &iexcl;Ahora intenta modificarlo!
                      <?php
                    }
                    echo ("<br/>");
                    echo ("<br/>");
                    echo ("</span>");
                    break;
                case 8:
                    echo ("<span style=\"font-size: 11px;\" id=\"tutorial\">");
                    if ($lang == 'en')
                    {
                      ?>
			You have changed your mutation ratio. But you can also change the mutation <b>intensity</b>. To do so, go to the Shop again and buy one of those <b>Cesium Oxide</b> items.
			When you consume them, they will change your <b>mutation intensity</b> to <b>soft</b> (Cesium 134 Oxide), <b>medium</b> (Cesium 135 Oxide) and <b>strong</b> (Cesium 137 Oxide).
			<br/><br/>
			Now go buy any of them at the shop. They will be stored in your inventory, and you will be able to change your mutation intensity when you consume it.
                      <?php
                    } else {
                      ?>
			Has cambiado tu ratio de mutaci&oacute;n. Pero tambi&eacute;n puedes cambiar la <b>intensidad</b> de la mutaci&oacute;n. Para hacerlo, vete a la Tienda de nuevo y compra
			uno de esos objetos de <b>&Oacute;xido de Cesio</b>.
			Cuando lo consumas, cambiar&aacute; tu <b>intensidad de mutaci&oacute;n</b> a <b>suave</b> (&Oacute;xido de Cesio 134), <b>media</b> (&Oacute;xido de Cesio 135) o
			<b>fuerte</b> (&Oacute;xido de Cesio 137).
			<br/><br/>
			Ahora ve y compra alguno de ellos en la tienda. Se almacenar&aacute;n en tu inventario, y podr&aacute;s cambiar la intensidad de tu mutaci&oacute;n cuando lo consumas.
                      <?php
                    }
                    echo ("<br/>");
                    echo ("<br/>");
                    echo ("</span>");
                    break;
                case 9:
                    echo ("<span style=\"font-size: 11px;\" id=\"tutorial\">");
                    if ($lang == 'en')
                    {
                      ?>
			Usually your specimens reproduce sexually. This means that every new specimen you evolve is a mixture from other two specimens. We are used to sexual reproduction, but
			in Nature we sometimes find <b>asexual</b> reproduction (in which the new specimens are exact copies from their parent, except for the mutations that might happen).
			<br/><br/>
			In Gene Overload you can also use <b>trisexual</b> reproduction, meaning that 3 specimens will be chosen as donors of genetic material to the new child.<br/><br/>
			You can change this by buying and consuming <b>Fukushima coolant</b>. They come in 3 flavors, for asexual, sexual and trisexual reproduction. 
			Now go and buy any of this from the <b>Shop</b>.
                      <?php
                    } else {
                      ?>
			Habitualmente tus espec&iacute;menes se reproducen sexualmente. Esto significa que cada nuevo especimen que evolucionas es una mezcla de otros dos espec&iacute;menes.
			Estamos habituados a la reproducci&oacute;n sexual, pero en la Naturaleza a veces encontramos reproducci&oacute;n <b>asexual</b> (donde el nuevo especimen es una copia
			exacta de su progenitor, excepto por las mutaciones que puedan suceder).<br/><br/>
			En Gene Overload tambi&eacute;n puedes utilizar reproducci&oacute;n <b>trisexual</b>, lo que significa que se elegir&aacute;n 3 espec&iacute;menes como donantes de 
			material gen&eacute;tico para en nuevo hijo.<br/><br/>
			Puedes cambiar esto comprando y consumiendo <b>Refrigerante de Fukushima</b>. Viene en 3 sabores, para reproducci&oacute;n asexual, sexual y trisexual.
			Ahora ve y compra cualquiera de estos de la <b>Tienda</b>.
                      <?php
                    }
                    echo ("<br/>");
                    echo ("<br/>");
                    echo ("</span>");
                    break;
                case 10:
                    echo ("<span style=\"font-size: 11px;\" id=\"tutorial\">");
                    if ($lang == 'en')
                    {
                      ?>
			Enough for the Shop. We've left a few items for you to explore, so feel free to buy and try.<br/><br/>
			Now back at the Command Center (yes, it is that important!), you've already tested and evolved generations, but you can be more specific when evolving.<br/><br/> Just
			imagine you have some specimen in the abyssal deme you don't want to lose. You might want to evolve only the forest or the volcano deme. Or you might even want to
			evolve just one slot, killing the specimen that is living there and replacing it for another one which comes from the specimens in that deme.<br/><br/>
			As you can see, over each deme table there is an option called <b>"Evolve deme"</b>. By clicking on it, the specimens currently in the deme will be the parents of a
			new generation, without affecting the other demes. Also for each row you can click in an option called <b>"Evolve"</b> by which that slot will be emptied to allow for
			a new specimen.<br/><br/>
			Your mission now is to evolve either a deme, either an individual.
                      <?php
                    } else {
                      ?>
			Suficiente con la Tienda. Hemos dejado algunos objetos sin mencionar para que explores, as&iacute; que si&eacute;ntete libre de comprar y probar.<br/><br/>
			Ahora de vuelta al Centro de Mando (&iexcl;s&iacute;, es as&iacute; de importante!), de momento has probado y evolucionado generaciones, pero puedes ser m&aacute;s
			espec&iacute;fico. <br/><br/>Imagina que tienes un especimen en el deme de las profundidades que no quieres perder. Podr&iacute;as querer evolucionar s&oacute;lo el deme del
			volc&aacute;n o el del bosque. O quiz&aacute; s&oacute;lo quieras evolucionar un hueco, acabando con el especimen que est&aacute; viviendo ah&iacute; y sustituy&eacute;ndolo
			por otro que proviene de los espec&iacute;menes en ese deme.<br/><br/>
			Como puedes ver, sobre la tabla de cada deme hay una opci&oacute;n llamada <b>"Evolucionar deme"</b>. Cliqueando en ella, los espec&iacute;menes que est&aacute;n ahora
			en el deme ser&aacute;n los padres de una nueva generaci&oacute;n sin afectar a los otros demes. Tambi&eacute;n en cada fila puedes cliquear en una opci&oacute;n llamada
			<b>"Evolucionar"</b> mediante la cual ese hueco se vaciar&aacute; para dejar sitio a un nuevo especimen.<br/><br/>
			Tu misi&oacute;n ahora es evolucionar bien un deme, bien un individuo.
                      <?php
                    }
                    echo ("<br/>");
                    echo ("<br/>");
                    echo ("</span>");
                    break;
                case 11:
                    echo ("<span style=\"font-size: 11px;\" id=\"tutorial\">");
                    if ($lang == 'en')
                    {
                      ?>
			Well, now you have the basics on the <b>Command Center</b>. But you might want to know more about a specific specimen. To do this, you'll see there is a column named
			<b>"Detail"</b> with a magnifying glass icon. <br/><br/>
			This is a <b>Premium</b> zone free 5 times a day for free accounts, where you can check your specimen details. There is
                        a first table with some basic information on the specimen like its age (eons) and the medals it has won, and another table in which its <b>decission tree</b> is detailed.<br/><br/>
			This decission tree thing is more complex and beyond the scope of this
			tutorial, but as you get deep into the game you'll see checking it out is a very good idea, since it is the mechanism which decides what your specimen does in combat, 
			depending on whats going on around it.
			<br/><br/>
			See the details from one of your specimens to go on with this tutorial.
                      <?php
                    } else {
                      ?>
			Bueno, ahora conoces lo b&aacute;sico sobre el <b>Centro de Mando</b>. Pero quiz&aacute; quieras saber m&aacute;s sobre alg&uacute;n especimen en particular. Para hacer
			esto, puedes ver que hay una columna llamada <b>"Detalle"</b> con un icono de una lupa.<br/><br/>
			Esta es una zona <b>Premium</b> que es gratu&iacute;ta 5 veces al d&iacute;a para
			las cuentas gratu&iacute;tas, donde puedes comprobar los detalles de tu especimen. Hay una primera tabla con algo de informaci&oacute;n b&aacute;sica sobre tu especimen
			como su edad (eones) y las medallas que ha ganado, y otra table en la que se detalla su <b>&aacute;rbol de decisi&oacute;n</b>.<br/><br/>
			Este &aacute;rbol de decisi&oacute;n es complejo y est&aacute; m&aacute;s all&aacute; del alcance de este tutorial, pero a medida que profundices en el juego ver&aacute;s que
			comprobarlo es muy buena idea, dado que es el mecanismo por el cual tu especimen toma decisiones de combate, dependiendo de lo que suceda a su alrededor.
			<br/><br/>
			Consulta los detalles de uno de tus espec&iacute;menes para seguir con este tutorial.
                      <?php
                    }
                    echo ("<br/>");
                    echo ("<br/>");
                    echo ("</span>");
                    break;
                case 12:
                    echo ("<span style=\"font-size: 11px;\" id=\"tutorial\">");
                    if ($lang == 'en')
                    {
                      ?>
			How does combat happen? There's two kinds of tournaments.<br/><br/>
			You have to do nothing for <b>deme tournaments</b>. Your best scored specimen will be automatically 
			chosen to fight against the rest of the players.<br/><br/>
			However, there is also the <b>big daily tournaments</b>. These give out good money 
			and count for the ranking. You can check out the tournament victory rankings in the Ranking tab,
			those are the first shown as soon as you click on the tab.<br/><br/>
			You must choose your best specimen for the <b>big daily tournament</b> in the Gene Arena. You'll see there's a column in the <b>Command Center</b> called <b>"Tournament"</b>. One
			row says <span style="color: #efefef">"[Selected]"</span>. The others let you click on them.<br/><br/> Well, now you should check out your specimens scores and maybe even some of their details. You might
			want to <b>evaluate</b> again separately some of the best ones to be sure they're really that good. And when you've made a decission, just click on 
			<b><span style="color: #e5a788">"[Choose]"</span></b>. This will be your
			champion (and don't worry, selecting your champion doesn't cost anything at all and you can change your mind later).
                      <?php
                    } else {
                      ?>
			&iquest;C&oacute;mo sucede el combate? Hay dos tipos de torneos.<br/><br/>
			No tienes que hacer nada para los <b>torneos de deme</b>. Se elegir&aacute; autom&aacute;ticamente a tu especimen mejor puntuado para
			luchar contra el resto de los jugadores.<br/><br/>
			Sin embargo, tambi&eacute;n est&aacute;n los <b>grandes torneos diarios</b>. Estos dan un buen dinero
			y cuentan para el ranking. Puedes comprobar el ranking de victorias de torneos en la pesta&ntilde;a de Ranking, esos son los primeros
			que se te muestran, nada m&aacute;s cliquear.<br/><br/>
			Debes elegir a tu mejor especimen para el <b>gran torneo diario</b> en la Gene Arena. Ver&aacute;s que hay una columna en el <b>Centro de Mando</b> llamada
			<b>"Torneo"</b>. Una fila dice <span style="color: #efefef">"[Seleccionado]"</span>. Las otras te dejan cliquear en ellas. <br/><br/>Bueno, pues ahora deber&iacute;as comprobar las
			puntuaciones de tus espec&iacute;menes y quiz&aacute; incluso alguno de sus detalles. Podr&iacute;as querer <b>evaluar</b> otra vez por separado algunos de tus mejores
			espec&iacute;menes para asegurarte de que realmente son as&iacute; de buenos. Y cuando hayas tomado una decisi&oacute;n, tan s&oacute;lo haz click en 
			<b><span  style="color: #e5a788">"[Seleccionar]"</span></b>.
			Este ser&aacute; tu campe&oacute;n (y no te preocupes, elegir a tu campe&oacute;n no cuesta nada en absoluto, y puedes cambiar de idea m&aacute;s tarde).
                      <?php
                    }
                    echo ("<br/>");
                    echo ("<br/>");
                    echo ("</span>");
                    break;
                case 13:
                    echo ("<span style=\"font-size: 11px;\" id=\"tutorial\">");
                    if ($lang == 'en')
                    {
                      ?>
			You've selected your champion! Remember that everytime you evolve your specimens, you'll have to choose another champion, since even though the new one occupies the
			same slot, it will be a different specimen. If you don't choose any of them, you'll see that the first position in the first deme is automatically selected. Be alert
			after you evolve... and also if any of your specimens die of old age!
			<br/><br/>
			When the next tournament is played, you will be able to see the result clicking on the <b>"Reports"</b> tab. There you have a list of reports which tell you on
			several things like tournament results, or specimens that die because they get too old. On the <b>"Reports"</b> tab you will see the number of unread reports, for
			example <b>"Reports(1)"</b>. It works the same with private Messages, which are in the <b>"Messages"</b> tab.
			<br/><br/>
			Now to complete this step, just go to <b>Reports</b> and read one of them. If you don't still have reports, you might have to wait until a tournament is played. The
			time until the next <b>big daily tournament</b> is displayed in the upper-left corner of the screen.
                      <?php
                    } else {
                      ?>
			&iexcl;Ya has apuntado a tu campe&oacute;n!. Recuerda que cada vez que evoluciones a tus espec&iacute;menes tendr&aacute;s que elegirlo de nuevo, porque aunque
			el nuevo ocupe el mismo hueco, ser&aacute; distinto. Si no eliges ninguno, ver&aacute;s que es elegida la primera posici&oacute;n del primer deme. &iexcl;Presta
			atenci&oacute;n cuando evoluciones... y tambi&eacute;n si algunos de tus espec&iacute;menes mueren de viejos!
			<br/><br/>
			Cuando se dispute el pr&oacute;ximo torneo, podr&aacute;s ver el resultado cliqueando en la pesta&ntilde;a de <b>"Informes"</b>. Ah&iacute; tienes una lista de
			informes que te dicen varias cosas como resultados de torneos, o espec&iacute;menes que mueren porque se hacen muy viejos. En la pesta&ntilde;a de <b>"Informes"</b>,
			ver&aacute;s el n&uacute;mero de informes sin leer, por ejemplo <b>"Informes(1)"</b>. Funciona igual con los mensajes privados, que est&aacute;n en la pesta&ntilde;a
			de <b>"Mensajes"</b>.
			<br/><br/>
			Ahora para completar este paso, vete a <b>Informes</b> y lee uno de ellos. Si todav&iacute;a no tienes informes, tendr&aacute;s que esperar hasta que se dispute un
			torneo. El tiempo que falta para el pr&oacute;ximo <b>gran torneo diario</b> se muestra en la esquina superior-izquierda de la pantalla.
                      <?php
                    }
                    echo ("<br/>");
                    echo ("<br/>");
                    echo ("</span>");
                    break;

                case 14:
                    echo ("<span style=\"font-size: 11px;\" id=\"tutorial\">");
                    if ($lang == 'en')
                    {
                      ?>
			Now you know all the basics to become a successful genetist! You might want to understand the game in more depth and for that I recommend you the <b>"How to play"</b>
			section.<br/><br/>
			Whatever you do, remember this: It is a good idea to spend much money in evolving lots of generations. It is a good idea to trust Nature to do what it knows to do. It
			worked in our planet Earth, it will work for your specimens. The principles in which it is based are the same in this game as in the real world. Let Nature surprise you.
			<br/><br/>
			As your final task, you'll have to figure out how to write a message to another player. Good luck with your specimens!
                      <?php
                    } else {
                      ?>
			&iexcl;Ahora conoces lo b&aacute;sico para convertirte en un genetista de &eacute;xito! Quiz&aacute; quieras entender el jeugo en mayor profundidad, y para ello te
			recomiendo la secci&oacute;n de <b>"C&oacute;mo jugar"</b>.<br/><br/>
			Hagas lo que hagas, recuerda esto: Es una buena idea gastar mucho dinero para evolucionar muchas generaciones. Es una buena idea confiar en que la Naturaleza har&aacute;
			lo que sabe hacer. Funcion&oacute; en nuestro planeta Tierra, funcionar&aacute; para tus espec&iacute;menes. Los principios en los que se basa son los mismos en este juego
			que en el mundo real. Deja que la Naturaleza te sorprenda.
			<br/><br/>
			Como &uacute;ltima tarea, tienes que averiguar c&oacute;mo escribirle un mensaje a otro jugador. &iexcl;Buena suerte con tus espec&iacute;menes!
                      <?php
                    }
                    echo ("<br/>");
                    echo ("<br/>");
                    echo ("</span>");
                    break;
              }
             ?>
            </td></tr>
           </table>
          </td>
         </tr>
        </table>
        <?php
      }
    }

//  }

?>
