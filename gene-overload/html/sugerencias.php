<?php


  include ('clases/obj_sugerencia.php');



  // *********************************
  //   Enviar una sugerencia
  // *********************************

  if ($accion == 'enviar_sugerencia')
  {
    $texto = $secure->Sanitizar($_REQUEST['texto']);
//    $idjugador = $_REQUEST['idjugador'];
    if (!is_numeric($idjugador))
    {
      $idjugador = 0;
    }


    $sugerencia = new Sugerencia();
    $sugerencia->texto = $texto;
    if (($idjugador == null) || ($idjugador == '')) { $idjugador = 0; }
    $sugerencia->idjugador = $idjugador;
    $sugerencia->Insertar($link_w);

    if ($lang == 'en')
    {
      echo ("Suggestion sent. Thank you very much for your cooperation!");
    } else {
      echo ("Sugerencia enviada. &iexcl;Muchas gracias por tu colaboraci&oacute;n!");
    }
    $accion = null;

  }


  // *********************************
  //   Por defecto formulario de sugerencias
  // *********************************

  if ($accion == null)
  {

     ?>


  <br/>
  <br/>

           <p>
    <span style="
                font-size: 13px;
                color: #ffff55;
                font-weight: bold;
                ">
	<?php
	     if ($lang == 'en')
	     {
	?>
		Send us your suggestion
        <?php
	     } else {
	?>
		Env&iacute;anos tu sugerencia
        <?php
	     }
	?>
    </span>
  </p>

  <br/>
  <br/>
  <br/>


       <form method="post" action="index.php">

  <table
        style="
                background-color: #111111;
                padding: 20px 20px 20px 20px;
                margin-left: 20px;
                width: 600px;
                "
        >
  <tr>
  <td>

  <p>
    <span style="
                font-size: 15px;
                color: #dddd55;
                margin-left: 15px;
                ">

         <input type="hidden" name="catid" value="<?php echo $catid; ?>">
         <input type="hidden" name="accion" value="enviar_sugerencia">
         <textarea name="texto" cols="70" rows="20"></textarea>
    </span>
  </p>


  </td>
  </tr>
  </table>

  <br/>
  <br/>


	<?php
	     if ($lang == 'en')
	     {
	?>
         <input type="submit" value="Send">
        <?php
	     } else {
	?>
         <input type="submit" value="Enviar">
        <?php
	     }
	?>


       </form>

  <br/>
  <br/>


     <?php



  }


?>
