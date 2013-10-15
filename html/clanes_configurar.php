<?php


  // ********************************************
  //      Alterar el logo del equipo
  // ********************************************

  if ($accion == 'alterar_imagen')
  {
   if ($miclan->fundador == 1)
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
       $accion = "editar_opciones";

     } else {


       // Si $miclan->ruta_avatar contiene algo, hacemos su unlink
        if (($miclan->ruta_avatar != null) &&
		($miclan->ruta_avatar != '')
		)
        {
          $borrar = $ruta_avatar_equipo.$miclan->ruta_avatar;
          $borrar_thumb = substr($borrar, 0, strlen($borrar) - 4)."_thumb".substr($borrar, (strlen($borrar) - 4), strlen($borrar));
//            echo ("<img src=\"".$ruta_avatar_equipo.$miclan->ruta_avatar."\">");
	  unlink ($borrar);
	  unlink ($borrar_thumb);
        }

       // Ahora vamos a montar el nombre unico
//       $nombre = $idelemento.strtolower(substr($nombre, (strlen($nombre) - 4), strlen($nombre)));

       // Montamos el nombre (80x80) y el thumb(30x30) a partir del id de clan
       $nombre = $miclan->id.strtolower(substr($nombre, (strlen($nombre) - 4), strlen($nombre)));
       $nombre_thumb = substr($nombre, 0, strlen($nombre) - 4)."_thumb".substr($nombre, (strlen($nombre) - 4), strlen($nombre));

       // Todo esta bien

       $donde_grabar = $ruta_interior.$ruta_avatar_equipo.$nombre;
//   echo $donde_grabar;
       $handle_temp = fopen($donde_grabar,"w+");

       $donde_grabar_thumb = $ruta_interior.$ruta_avatar_equipo.$nombre_thumb;
//echo $donde_grabar;       
       $handle_temp_thumb = fopen($donde_grabar_thumb,"w+");

       $contenido = fread($handle, filesize($_FILES['content']['tmp_name']));

       // Vale, ahora en contenido tenemos TODO el contenido del fichero subido
       fwrite($handle_temp, $contenido);
       fclose($handle_temp);

       fwrite($handle_temp_thumb, $contenido);
       fclose($handle_temp_thumb);

       fclose($handle);

       // Grabamos la base que va a ser a resizear como thumbs

       $donde_t1 = $ruta_interior.$ruta_avatar_equipo.$nombre;
//PATHBASE_interior.$PATH_img_producto.$pathcategoria.$nombre;
       $handle_t1 = fopen($donde_t1,"w+");
       fwrite($handle_t1, $contenido);
       fclose($handle_t1);

       $donde_t2 = $ruta_interior.$ruta_avatar_equipo.$nombre_thumb;
//       $donde_t2 = $PATHBASE_interior.$PATH_img_producto.$pathcategoria.$nombre_thumb;
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
       if (($w <= $med_x_clan) &&
            ($h <= $med_y_clan))
       {
         $new_w_t1 = $w;
         $new_h_t1 = $h;
       } else {
         $new_w_t1 = $med_x_clan;
         if ( (($new_w_t1 * $h)/$w) > $med_y_clan )
         {
           $new_w_t1 = (($med_y_clan * $w) / $h);
         }
         $new_h_t1 = ($new_w_t1 * $h) / $w;   // Asi nos sale equilibrada
       }
       $image_temp_t1 = ImageCreateTrueColor($new_w_t1, $new_h_t1);   // Tenemos una nueva del tama�o deseado


       // Para la de tamanyo thumbnail

       $w = $imagedata2[0];
       $h = $imagedata2[1];

       // PAra el tama�yo mediano
       if (($w <= $peq_x_clan) &&
            ($h <= $peq_y_clan))
       {
         $new_w_t2 = $w;
         $new_h_t2 = $h;
       } else {
         $new_w_t2 = $peq_x_clan;
         if ( (($new_w_t2 * $h)/$w) > $peq_y_clan )
         {
           $new_w_t2 = (($peq_y_clan * $w) / $h);
         }
         $new_h_t2 = ($new_w_t2 * $h) / $w;   // Asi nos sale equilibrada
       }
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

       // Y ahora apuntamos alli al clan

       $miclan->AlterarDatosLogo($miclan->id, $nombre);


       // Actualizamos los datos en $miclan
       $miclan->ObtieneClanJugador($idjugador, $idcampana);


       echo ("<p class=\"correctosutil\">Imagen alterada</p>");
       $accion = 'editar_opciones';





     }


    } // del if ($handle == null)
   }
  }


  // ********************************************
  //      Alterar opciones del equipo
  // ********************************************

  if ($accion == 'alterar_opciones')
  {
    if ($miclan->fundador == 1)
    {
      $miclan->identificador = $secure->Sanitizar($_REQUEST['identificador']);
      if (strlen($miclan->identificador) <= 4)
      {
        $miclan->presentacion = $secure->Sanitizar($_REQUEST['presentacion']);
        $miclan->AlterarDatos($miclan->id);
        echo ("<p class=\"correctosutil\">");
        if ($lang == 'en')
        {
          echo ("Team data changed.");
        } else {
          echo ("Datos del equipo alterados.");
        }
        echo ("</p><br/>");
      } else {
	echo ("<br/>");
        if ($lang == 'en')
        {
          echo ("<p class=\"error\">Error : Identifier can have a maximum of 4 characters length</p>");
        } else {
          echo ("<p class=\"error\">Error : El identificador puede tener un m&aacute;ximo de 4 caracteres</p>");
        }
      }
    }
    $accion = 'editar_opciones';
  }

  // ********************************************
  //      Editar opciones del equipo
  // ********************************************

  if ($accion == 'editar_opciones')
  {
    if ($miclan->fundador == 1)
    {
//      $miclan->SacarDatos($miclan->id);
      //
      ?>
	<br/>

        <form method="post" action="index.php">

	<input type="hidden" name="catid" value="<?php echo $catid; ?>">
	<input type="hidden" name="idcampana" value="<?php echo $idcampana; ?>">
	<input type="hidden" name="accion" value="alterar_opciones">

	<p>
	<?php
	  if ($lang == 'en')
          {
          ?>
 	    <p>Team introduction : </p>
	  <?php
          } else {
          ?>
            <p>Presentaci&oacute;n del equipo : </p>
	  <?php
          }
          ?>
	<br/>
        <form method="post" action="index.php">
	 <p>
          <textarea name="presentacion" cols="70" rows="12"><?php echo $miclan->presentacion; ?></textarea>
	 </p>


	<br/>
 	<?php
	  if ($lang == 'en')
          {
          ?>
 	    <p>Identifier : </p>
	  <?php
          } else {
          ?>
 	    <p>Identificador : </p>
	  <?php
          }
          ?>
	<br/>
        <form method="post" action="index.php">
	 <p>
          <input type="text" name="identificador" value="<?php echo $miclan->identificador; ?>" size="4">
	 </p>


	<br/>
	<p>
 	<?php
	  if ($lang == 'en')
          {
          ?>
	    <input type="submit" value="Alter data">
	  <?php
          } else {
          ?>
	    <input type="submit" value="Cambiar datos">
	  <?php
          }
          ?>
	</p>

	</form>

	<!-- Y ahora para subir una imagen -->

	<br/>
	<br/>


        <form enctype="multipart/form-data" name="form1" method="post" action="index.php">
	<input type="hidden" name="catid" value="<?php echo $catid; ?>">
	<input type="hidden" name="idcampana" value="<?php echo $idcampana; ?>">
	<input type="hidden" name="accion" value="alterar_imagen">



	<?php
	  if ($lang == 'en')
          {
          ?>
 	    <p>Team logo : </p>
	  <?php
          } else {
          ?>
            <p>Logo del equipo : </p>
	  <?php
          }
          ?>
	<br/>
	<table>
	<tr>
	<td style="padding-right: 15px;">
	<?php
          if (($miclan->ruta_avatar != null) &&
		($miclan->ruta_avatar != '')
		)
          {
            echo ("<img src=\"".$ruta_avatar_equipo.$miclan->ruta_avatar."\">");
	  } else {
            echo ("<img src=\"".$ruta_avatar_equipo."team_unknown.jpg\">");
	  }
	?>
	</td><td style="vertical-align: middle;">

	<?php
	if ($lang == 'en')
	{
   	  ?>
          Choose a file :
	  <?php
	} else {
	  ?>
          Elige un fichero :
	  <?php
	}
	?>
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

	</td></tr></table>


	</form>


      <?php
    }
  }



?>
