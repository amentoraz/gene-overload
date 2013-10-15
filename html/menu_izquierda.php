<?php
// $menuheight = "30px";
?>

  <table class="tabla_izquierda">
  <tr><td>

   <ul class="makeMenu">
    <li>
<?php
//style="background-color: #222222; width: 150px;">
 if ($lang == 'en')
 {
//   echo ("<a href=\"index.php?\" style=\"color: #000000\">Frontpage</a>");
   echo ("<a href=\"index.php?lang=".$lang."\">Frontpage</a>");
 } else {
   echo ("<a href=\"index.php?lang=".$lang."\">Portada</a>");
 }

?>
    </li>
   <div id="espacio_menu"></div>
    <li>
<?php

 if ($lang == 'en')
 {
   echo ("<a href=\"index.php?catid=53&lang=".$lang."&idcampana=".$idcampana."\">News</a>");
 } else {
   echo ("<a href=\"index.php?catid=53&lang=".$lang."&idcampana=".$idcampana."\">Noticias</a>");
 }

?>
    </li>
   <div id="espacio_menu"></div>
    <li>
<?php


 if ($lang == 'en')
 {
   echo ("<a href=\"index.php?catid=50&lang=".$lang."&idcampana=".$idcampana."\">How to play</a>");
 } else {
   echo ("<a href=\"index.php?catid=50&lang=".$lang."&idcampana=".$idcampana."\">C&oacute;mo jugar</a>");
 }

 ?>
    </li>
   <div id="espacio_menu"></div>
    <li>
 <?php

 if ($lang == 'en')
 {
   echo ("<a href=\"index.php?catid=51&lang=".$lang."&idcampana=".$idcampana."\">Science</a>");
 } else {
   echo ("<a href=\"index.php?catid=51&lang=".$lang."&idcampana=".$idcampana."\">Ciencia</a>");
 }

 ?>
    </li>
   <div id="espacio_menu"></div>
    <li>
 <?php

 if ($lang == 'en')
 {
   echo ("<a href=\"index.php?catid=52&lang=".$lang."&idcampana=".$idcampana."\">Premium</a>");
 } else {
   echo ("<a href=\"index.php?catid=52&lang=".$lang."&idcampana=".$idcampana."\">Premium</a>");
 }

 ?>
    </li>
   <div id="espacio_menu"></div>
    <li>
 <?php

 if ($lang == 'en')
 {
   echo ("<a href=\"index.php?catid=54&lang=".$lang."&idcampana=".$idcampana."\">Credits</a>");
 } else {
   echo ("<a href=\"index.php?catid=54&lang=".$lang."&idcampana=".$idcampana."\">Cr&eacute;ditos</a>");
 }


 if ($idjugador != null)
 {
  ?>
    </li>
   <div id="espacio_menu"></div>
    <li>
  <?php

//  if ($lang == 'en')
//  {
//    echo ("<a style=\"color: #55ff00;\" href=\"index.php?catid=101&lang=".$lang."&idcampana=".$idcampana."\">Invites</a>");
//  } else {
//    echo ("<a style=\"color: #55ff00;\" href=\"index.php?catid=101&lang=".$lang."&idcampana=".$idcampana."\">Invitaciones</a>");
//  }
 }
?>
   </li>


  </ul>


<?php
  include ('tutorial.php');
  include ('encuestas.php');
?>


</td>
</tr>
</table>
