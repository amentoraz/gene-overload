<?php

  include ('clases/obj_texto_web.php');

  $texto_web = new Texto_Web();
  $texto_web->Sacar_Datos_Cat($link_r, $catid);

  if ($lang == 'en')
  {
    ?>
   <center>
   <table width="85%" align="center"><tr><td>
    <center>
    <p style="font-size: 13px; color: #f5b798;"><i>
     "Chance alone is at the source of every innovaton, of all creation in the biosphere. Pure chance,
only chance, absolute but blind liberty is at the root of the prodigious edifice that is evolution...
It today is the sole conceivable hypothesis, the only one that squares with observed and tested fact". <br/>[Jacques Monod]
    </i></p>
    </center>
   </td></tr></table>
   </center>
    <br/>
    <?php
    echo $texto_web->texto_en;
  } else {
    ?>
   <center>
   <table width="85%" align="center"><tr><td>
    <center>
    <p style="font-size: 13px; color: #f5b798;"><i>
    "La casualidad y s&oacute;lo ella est&aacute; en la fuente de toda innovaci&oacute;n, de toda creaci&oacute;n en la biosfera. La pura
casualidad y s&oacute;lo ella, la absoluta pero ciega libertad, est&aacute; en la ra&iacute;z del prodigioso edificio que es la evoluci&oacute;n...
Esa es hoy en d&iacute;a la &uacute;nica hip&oacute;tesis concebible, la &uacute;nica que cuadra con los hechos observados y comprobados". [Jacques Monod]
    </i></p>
    </center>
   </td></tr></table>
   </center>
    <br/>
    <?php
    echo $texto_web->texto;
  }


?>
