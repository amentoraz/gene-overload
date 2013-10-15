<?php

 // Origen de las imagenes standard de perfil_foto
 //
 //
 // 1.jpg -> gratuita 
 // 2.jpg -> http://magikstock.deviantart.com/art/steampunk-fairy-1-166634081?q=boost%3Apopular%20in%3Aresources%20steampunk&qo=28
 // 3.jpg -> http://mizzd-stock.deviantart.com/art/Steampunk-Circus-Doll-3-115669620?q=boost%3Apopular%20in%3Aresources%20steampunk&qo=4
 // 4.jpg -> http://angelus-knight.deviantart.com/art/Victorian-Steampunk-Stock-36-82664932?q=boost%3Apopular%20in%3Aresources%20steampunk&qo=162
 //    http://angelus-knight.deviantart.com/journal/9476580/
 // 5.jpg -> http://sed-rah-stock.deviantart.com/art/Gas-Mask-Scull-121454845?q=boost%3Apopular%20in%3Aresources%20gas%20mask&qo=14
 // 6.jpg -> http://jaymasee.deviantart.com/art/The-Steampunk-Bounty-Hunter-171269300?q=boost%3Apopular%20in%3Aresources%20steampunk&qo=64
 // 7.jpg -> http://bekey.deviantart.com/art/Nuclear-sign-Stock-112471634?q=boost%3Apopular%20in%3Aresources%20nuclear&qo=2
 //
 

  // ********************************************
  //      Alterar el avatar del usuario
  // ********************************************

  if ($accion == 'alterar_imagen')
  {
//   if ($miclan->fundador == 1)
   if (($es_premium == 1) || ($es_admin == 1))
   {

    $nombre = $_FILES['content']['name'];

    //Ahora vamos a abrir la cosa
    $handle = fopen($_FILES['content']['tmp_name'],"rw");
    if ($handle == null)
    {
     if ($lang == 'en')
     {
       ?>
       <p class="errorsutil"><b>Error: no file uploaded</b></p>
       <?php
     } else {
       ?>
       <p class="errorsutil"><b>Error: no se subi&oacute; ning&uacute;n fichero</b></p>
       <?php
     }
     $accion = "editar_opciones";
    } else {

     $tipo = exif_imagetype($_FILES['content']['tmp_name']);

     // El nombre en s� no debe existir
     $ext_correcta = 0;

     if ( eregi("gif$",$nombre) )
     {
       if ($tipo == 1)
       {
         $ext_correcta = 1;
       } else {
         $ext_falsa = 1;
       }
     }
     if ( eregi("jpg$",$nombre) )
     {
       if ($tipo == 2)
       {
         $ext_correcta = 1;
       } else {
         $ext_falsa = 1;
       }
     }
     if ( eregi("png$",$nombre) )
     {
       if ($tipo == 3)
       {
         $ext_correcta = 1;
       } else {
         $ext_falsa = 1;
       }
     }


     if ($ext_correcta == 0)
     {

       // Extension incorrecta!!! Borramos la entrada de la bbdd y sacamos mensaje de error

       if ($ext_falsa == 1)
       {
         if ($lang == 'en')
         {
           echo ("<p class=\"error\"><strong>Error :</strong> File extension doesn't match its true type</p>");
         } else {
           echo ("<p class=\"error\"><strong>Error :</strong> La extensi&oacute;n del fichero no se corresponde con su tipo verdadero</p>");
         }
       } else {
         if ($lang == 'en')
         {
           echo ("<p class=\"error\"><strong>Error :</strong> File needs to be .gif, .png or .jpg</p>");
         } else {
           echo ("<p class=\"error\"><strong>Error :</strong> El fichero ha de tener extensi&oacute;n .gif, .png o .jpg</p>");
         }
       }
       $accion = "fotografia_perfil";

     } else {



       $jugador_fotoperfil = new Jugador_Fotoperfil();
       if ($jugador_fotoperfil->Obtener_Imagen_Jugador($link_r, $idjugador))
       {
//         unlink ("img/profile/".$ruta_fotoperfil);
         unlink ("img/profile/".$jugador_fotoperfil->ruta);
//	echo ("img/profile/".$jugador_fotoperfil->ruta);
       }


       // Si $miclan->ruta_avatar contiene algo, hacemos su unlink
//        if (($miclan->ruta_avatar != null) &&
//		($miclan->ruta_avatar != '')
//		)
//        {
//          $borrar = $ruta_avatar_equipo.$miclan->ruta_avatar;
//          $borrar_thumb = substr($borrar, 0, strlen($borrar) - 4)."_thumb".substr($borrar, (strlen($borrar) - 4), strlen($borrar));
//	  unlink ($borrar);
//	  unlink ($borrar_thumb);
//        }



       // Ahora vamos a montar el nombre unico
//       $nombre = $idelemento.strtolower(substr($nombre, (strlen($nombre) - 4), strlen($nombre)));

       // Montamos el nombre (80x80) y el thumb(30x30) a partir del id de clan
//       $nombre = $miclan->id.strtolower(substr($nombre, (strlen($nombre) - 4), strlen($nombre)));

       $nombre = $idjugador.strtolower(substr($nombre, (strlen($nombre) - 4), strlen($nombre)));
       $nombre_thumb = substr($nombre, 0, strlen($nombre) - 4)."_thumb".substr($nombre, (strlen($nombre) - 4), strlen($nombre));


       // Todo esta bien

       $donde_grabar = $ruta_interior.$ruta_fotoperfil.$nombre;
       $handle_temp = fopen($donde_grabar,"w+");

       $donde_grabar_thumb = $ruta_interior.$ruta_fotoperfil.$nombre_thumb;
//echo $donde_grabar_thumb."-";
// /home4/geneover/public_html/img/profile/39_thumb.jpg
       $handle_temp_thumb = fopen($donde_grabar_thumb,"w+");

       $contenido = fread($handle, filesize($_FILES['content']['tmp_name']));

       // Vale, ahora en contenido tenemos TODO el contenido del fichero subido
       fwrite($handle_temp, $contenido);
       fclose($handle_temp);

       fwrite($handle_temp_thumb, $contenido);
       fclose($handle_temp_thumb);

       fclose($handle);

       // Grabamos la base que va a ser a resizear como thumbs

       $donde_t1 = $ruta_interior.$ruta_fotoperfil.$nombre;
       $handle_t1 = fopen($donde_t1,"w+");
       fwrite($handle_t1, $contenido);
       fclose($handle_t1);

       $donde_t2 = $ruta_interior.$ruta_fotoperfil.$nombre_thumb;
//echo $donde_t2;
//       $donde_t2 = $ruta_interior.$ruta_fotoperfil.$nombre;
       $handle_t2 = fopen($donde_t2,"w+");
       fwrite($handle_t2, $contenido);
       fclose($handle_t2);

       $file = $_FILES['content'];

       $imagedata = getimagesize($donde_t1);
       $imagedata2 = getimagesize($donde_t2);

       // Para la de tamanyo mediano

       $w = $imagedata[0];
       $h = $imagedata[1];

       // PAra el tama�yo mediano
       if (($w <= $med_x_fotoperfil) &&
            ($h <= $med_y_fotoperfil))
       {
         $new_w_t1 = $w;
         $new_h_t1 = $h;
       } else {
         $new_w_t1 = $med_x_fotoperfil;
         if ( (($new_w_t1 * $h)/$w) > $med_y_fotoperfil )
         {
           $new_w_t1 = (($med_y_fotoperfil * $w) / $h);
         }
         $new_h_t1 = ($new_w_t1 * $h) / $w;   // Asi nos sale equilibrada
       }
       $image_temp_t1 = ImageCreateTrueColor($new_w_t1, $new_h_t1);   // Tenemos una nueva del tama�o deseado


       // Para la de tamanyo thumbnail

//echo $imagedata2[0]."$".$imagedata2[1];
//echo $peq_x_fotoperfil."$".$peq_y_fotoperfil;

       $w = $imagedata2[0];
       $h = $imagedata2[1];

       // PAra el tama�yo mediano
       if (($w <= $peq_x_fotoperfil) &&
            ($h <= $peq_y_fotoperfil))
       {
         $new_w_t2 = $w;
         $new_h_t2 = $h;
       } else {
         $new_w_t2 = $peq_x_fotoperfil;
         if ( (($new_w_t2 * $h)/$w) > $peq_y_fotoperfil )
         {
           $new_w_t2 = (($peq_y_fotoperfil * $w) / $h);
         }
         $new_h_t2 = ($new_w_t2 * $h) / $w;   // Asi nos sale equilibrada
       }
//echo $new_w_t2."-".$new_h_t2;
       $image_temp_t2 = ImageCreateTrueColor($new_w_t2, $new_h_t2);   // Tenemos una nueva del tama�o deseado




       if (($file["type"] == "image/jpeg") || ($file["type"] == "image/pjpeg")) {
         $image_t1 = ImageCreateFromJpeg($donde_t1);
         $image_t2 = ImageCreateFromJpeg($donde_t2);
       } else if ($file["type"] == "image/gif") {
         $image_t1 = ImageCreateFromGif($donde_t1);
         $image_t2 = ImageCreateFromGif($donde_t2);
       } else if ($file["type"] == "image/png") {
         $image_t1 = ImageCreateFromPng($donde_t1);
         $image_t2 = ImageCreateFromPng($donde_t2);
      }

       imagecopyResampled ($image_temp_t1, $image_t1, 0, 0, 0, 0, $new_w_t1, $new_h_t1, $imagedata[0], $imagedata[1]);
       imagecopyResampled ($image_temp_t2, $image_t2, 0, 0, 0, 0, $new_w_t2, $new_h_t2, $imagedata2[0], $imagedata2[1]);


       if (($file["type"] == "image/jpeg") || ($file["type"] == "image/pjpeg")) {
           $resultado_t1 = imagejpeg($image_temp_t1,$donde_t1, 95);
           $resultado_t2 = imagejpeg($image_temp_t2,$donde_t2, 95);
       } else if ($file["type"] == "image/gif") {
           $resultado_t1 = imagegif($image_temp_t1,$donde_t1);
           $resultado_t2 = imagegif($image_temp_t2,$donde_t2);
       } else if ($file["type"] == "image/png") {
           $resultado_t1 = imagepng($image_temp_t1,$donde_t1);
           $resultado_t2 = imagepng($image_temp_t2,$donde_t2);
       }

       $jugador_fotoperfil->Insertar_Imagen($link_w, $nombre, 0);
       $idimagen = mysql_insert_id($link_w);
       $jugador_fotoperfil->Grabar_Imagen_Jugador_NoStandard($link_w, $idjugador, $idimagen);


       // Y ahora apuntamos alli al clan
//       $miclan->AlterarDatosLogo($miclan->id, $nombre);
       // Actualizamos los datos en $miclan
//       $miclan->ObtieneClanJugador($idjugador, $idcampana);

       echo ("<p class=\"correctosutil\">Imagen alterada</p>");
       $accion = 'fotografia_perfil';





     }


    } // del if ($handle == null)
   }
  }


 // ****************************************
 //    Central de la foto de perfil
 // ****************************************

 if ($accion == 'elegir_perfil_foto')
 {
   $idstandard = $_REQUEST['idstandard'];
   if (!is_numeric($idstandard))
   {
     die;
   }

   $jugador_fotoperfil = new Jugador_Fotoperfil();

   $jugador_fotoperfil->Grabar_Imagen_Jugador_Standard($link_w, $idjugador, $idstandard);
//   echo $idstandard;

   echo ("<p class=\"correctosutil\">Imagen de perfil alterada</p>");
   $accion = "fotografia_perfil";
 }


 // ****************************************
 //    Central de la foto de perfil
 // ****************************************

 if ($accion == 'fotografia_perfil')
 {

   ?>
    <table bgcolor="#221111" width="100%" style="padding: 10px;">
    <tr><td>
    <p style="font-size: 15px; font-weight: bold;"><?php

        if ($lang == 'en') {
          echo ("Current image");
	} else {
          echo ("Im&aacute;gen actual");
	}
        ?>
	</p>
    <br/>
   <?php

             $jugador_fotoperfil = new Jugador_Fotoperfil();
            if ($jugador_fotoperfil->Obtener_Imagen_Jugador($link_r, $idjugador))
            {
              echo ("<img src=\"img/profile/".$jugador_fotoperfil->ruta."\">");
            } else {
              // No tiene imagen de perfil elegida.
              ?>
              <img src="img/profile/picstandard.jpg" style="vertical-align: top;">
              <?php
            }
     ?>
    </td></tr>

    <tr><td>
    <br/>
    <br/>
    <p style="font-size: 15px; font-weight: bold;"><?php
           if ($lang == 'en') {
             echo ("Choose a profile picture between these images");
           } else {
             echo ("Elige una imagen de perfil entre estas imagenes");
           }
		?>
		</p>
    <br/>

    <table 
    		style="padding: 5px;
    				"
    		>

    <form method="post" action="index.php">
    <input type="hidden" name="accion" value="elegir_perfil_foto">
    <input type="hidden" name="idcampana" value="<?php echo $idcampana; ?>">
    <input type="hidden" name="catid" value="<?php echo $catid; ?>">
    <tr>
    <?php
    $array = $jugador_fotoperfil->Obtener_Standard($link_r);
    for ($i = 1; $i <= count($array); $i++)
    {
      if ((($i % 6) == 1) && ($i > 6)) { echo ("<tr>"); }

      echo ("<td style=\"text-align: center; padding:4px; \">");
      echo ("<img src=\"img/profile/".$array[$i]['ruta']."\">");
      echo ("<br/>");
      if ($array[$i]['id'] == $jugador_fotoperfil->id)
      {
        echo ("<input type=\"radio\" name=\"idstandard\" value=\"".$array[$i]['id']."\" checked>");
      } else {
        echo ("<input type=\"radio\" name=\"idstandard\" value=\"".$array[$i]['id']."\">");
      }
      echo ("</td>");

      if (($i % 6) == 0) { echo ("</tr>"); }
    }

    $colspan = (7 - ($i % 6));
    if ($colspan > 0)
    {
      echo ("<td colspan=\"".$colspan."\"></td>");
    }

    ?>
    </tr>
    </table>
    <br/>
    <?php
     if ($lang == 'en')
     {
    ?>
    <input type="submit" value="Change picture">
    <?php
     } else {
    ?>
    <input type="submit" value="Cambiar imagen">    
    <?php
     }
    ?>
    </form>

<br/><br/><br/>

    <p style="font-size: 15px; font-weight: bold;"><?php
           if ($lang == 'en') {
             echo ("Custom image");
           } else {
             echo ("Imagen personalizada");             
           }
		?>
		</p>
    <?php
    if (($es_premium == 1) || ($es_admin == 1))
    {
    	?>
        <form enctype="multipart/form-data" name="form1" method="post" action="index.php">
	     <input type="hidden" name="catid" value="<?php echo $catid; ?>">
	     <input type="hidden" name="idcampana" value="<?php echo $idcampana; ?>">
	     <input type="hidden" name="accion" value="alterar_imagen">
	     
	     	<?php
   	   if ($lang == 'en')
          {
          ?>
 	         <p><i>Select an image to upload from your hard disk </i></p>
	       <?php
          } else {
          ?>
            <p><i>Elige una imagen para subir desde tu disco duro</i></p>
	       <?php
          }
          ?>
	     <br/>
	     	 <input name="content" type="file" size="15"/>
   	  <br/>
	     <br/>
	      <?php
            if ($lang == 'en')
            {
              echo ("<input type=\"submit\" value=\"Upload\">");
            } else {
              echo ("<input type=\"submit\" value=\"Cambiar\">");
            }

	     ?>

	     
	
	     </form>
	   <?php    	
    	
    } else {
    	echo ("<br/>");
    	echo ("<p class=\"errorsutil\">");
    	if ($lang == 'en')
    	{
    	  echo ("You need to be a premium user to upload a personal profile picture.");    	  
    	} else {
        echo ("Necesitas ser usuario premium para subir una imagen personal de perfil.");
    	}
    	echo ("</p>");
    	echo ("<br/>");
      echo ("<br/>");
    }
    ?>


    </td></tr>
    </table>
    <?php


 }

?>
