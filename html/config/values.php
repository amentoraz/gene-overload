<?php

	// HORAS con las que ajustamos la fecha del servidor
        $horasajuste = 8;

//        global $total_puntos_base = 36;
	define("TOTAL_PUNTOS_BASE", 36);


	define("PRIMER_PUESTO", 40);
	define("SEGUNDO_PUESTO", 20);
	define("TERCER_PUESTO", 10);
	define("CUARTO_PUESTO", 5);
	define("QUINTO_PUESTO", 3);
	define("SEXTO_PUESTO", 2);
	define("SEPTIMO_PUESTO", 1);


    // These are the syllables from which creature names are created

   $array_silabas_profundidades = array('gok','dod','rak','khu','kar','ush','ga','grorn',
    'u','targ','too','mog','ang','mar','gul','dur','rad','mor','kagh','kor','grukh','dar',
    'wulf','ker','ka','ne','kog','tak','ze');

   $array_silabas_bosque = array('las','ele','rie','teng','wa','elen','ril','ya','vil','nyan',
    'vie','ca','thir','dhol','van','loth','lo','quen',
    'va','nath','nost','ka','nes','ti','gwy','qua','nost');

   $array_silabas_volcan = array('gard','ku','mar','ro','rath','se','i','shi','mar','a','xa','jor',
    'tor','kren','dre','grim','trorn','ma','ra','kith','me','ril','va','nor','vin','thar','par');
   


    // Meses

	$array_months['1'] = 'January';
	$array_months['2'] = 'February';
	$array_months['3'] = 'March';
	$array_months['4'] = 'April';
	$array_months['5'] = 'May';
	$array_months['6'] = 'June';
	$array_months['7'] = 'July';
	$array_months['8'] = 'August';
	$array_months['9'] = 'September';
	$array_months['10'] = 'October';
	$array_months['11'] = 'November';
	$array_months['12'] = 'December';


	$array_meses['1'] = 'Enero';
	$array_meses['2'] = 'Febrero';
	$array_meses['3'] = 'Marzo';
	$array_meses['4'] = 'Abril';
	$array_meses['5'] = 'Mayo';
	$array_meses['6'] = 'Junio';
	$array_meses['7'] = 'Julio';
	$array_meses['8'] = 'Agosto';
	$array_meses['9'] = 'Septiembre';
	$array_meses['10'] = 'Octubre';
	$array_meses['11'] = 'Noviembre';
	$array_meses['12'] = 'Diciembre';


	$ruta_exterior = "http://www.geneoverload.com/";
        $ruta_interior = "/var/www/geneover/public_html/";
	$ruta_avatar_equipo = "img/teamlogo/";
        $ruta_fotoperfil = "img/profile/";


	$med_x_clan = 80;
	$med_y_clan = 80;
	$peq_x_clan = 30;
	$peq_y_clan = 30;

	$med_x_fotoperfil = 100;
	$med_y_fotoperfil = 100;
	$peq_x_fotoperfil = 30;
	$peq_y_fotoperfil = 30;


	$limitmensajes = 20;


?>
