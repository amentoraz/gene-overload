<?php

  include ('clases/obj_invitacion.php');

  if ($idjugador != null)
  {

  if ($accion == null)
  {

    echo ("<p style=\"font-size: 15px; color: #c58768; \">");
    echo ("<b>");
    if ($lang == 'en')
    {
      echo ("Invite your friends to the Gene Warriors Beta");
    }
    if ($lang == 'es')
    {
      echo ("Invita a tus amigos a la Beta de Gene Warriors");
    }
    echo ("</b>");
    echo ("</p>");

    echo ("<br/>");
    echo ("<br/>");
    echo ("<br/>");

    $invitacion = new Invitacion();
    $invitacion->link_r = $link_r;
    $invitacion->link_w = $link_w;
    $cpendientes = $invitacion->Obtener_Cuantas_Invitaciones_Pendientes($idjugador);

    if ($cpendientes > 0)
    {
      echo ("<p style=\"font-size: 14px; color: #c58768; \">");
      echo ("<b>");
      if ($lang == 'en')
      {
        echo ("You still have <span style=\"color: #00ff00;\">".$cpendientes."</span> invitations left.");
      }
      if ($lang == 'es')
      {
        echo ("Todav&iacute;a te quedan <span style=\"color: #00ff00;\">".$cpendientes."</span> invitaciones.");
      }
      echo ("</b>");
      echo ("</p>");

      // Ahora la zona de invita a alguien

      ?>
      <form method="post" action="index.php">
       <input type="hidden" name="accion" value="invitar">
       <input type="hidden" name="catid" value="<?php echo $catid; ?>">
	<br/>
	<br/>
	<br/>
        <p style="font-size: 14px; color: #d59778;">
          Email a invitar :
        </p>
	<br/>
        <input type="text" name="" size="60">
        <br/>
        <br/>
        <?php
        if ($lang == 'en')
        {
        ?>
          <input type="submit" value="Sent invitation">
        <?php
        } else {
        ?>
          <input type="submit" value="Enviar invitaci&oacute;n">
        <?php
        }
        ?>
      </form>
      <?php

    } else {
      echo ("<p style=\"font-size: 14px; color: #c58768; \">");
      echo ("<b>");
      if ($lang == 'en')
      {
        echo ("You have no invitations left.");
      }
      if ($lang == 'es')
      {
        echo ("No te quedan invitaciones por usar.");
      }
      echo ("</b>");
      echo ("</p>");
    }
    echo ("<br/>");

  }




  } else {
    if ($lang == 'en')
    {
      echo ("You are not authenticated");
    } else {
      echo ("No est&aacute;s autenticado");
    }
  }

?>
