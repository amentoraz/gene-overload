<?php

      echo ("<div id=\"espacio\" class=\"espaciomax\">");
      echo ("</div>");

  echo ("<table width=\"100%\" class=\"tabla_final\">");
  echo ("<tr>");
  echo ("<td>");
  echo ("<center>");
  echo ("<span style=\"color: #ffbb55;
		font-size: 16px;
		font-weight: bold;
		\">");
/*
  if ($lang == 'en')
  {
    echo ("Report");
  } else {
    echo ("Denunciar");
  }
  echo ("&nbsp;");
  echo ("&nbsp;");
  echo ("&nbsp;");
  echo ("&nbsp;");
  echo ("&nbsp;");
  echo ("&nbsp;");
  echo ("&nbsp;");
*/
  echo ("<a href=\"index.php?catid=106\">");
  if ($lang == 'en')
  {
    echo ("Suggestions");
  } else {
    echo ("Sugerencias");
  }
  echo ("</a>");
  echo ("&nbsp;");
  echo ("&nbsp;");
  echo ("&nbsp;");
  echo ("&nbsp;");
  echo ("&nbsp;");
  echo ("&nbsp;");
  echo ("&nbsp;");
  echo ("<a href=\"index.php?catid=107&idcampana=".$idcampana."\">");
  if ($lang == 'en')
  {
    echo ("Help");
  } else {
    echo ("Ayuda");
  }
  echo ("</a>");

  echo ("&nbsp;");
  echo ("&nbsp;");
  echo ("&nbsp;");
  echo ("&nbsp;");
  echo ("&nbsp;");
  echo ("&nbsp;");
  echo ("&nbsp;");

  ?>
 <a href="rss_en.php" target="_blank"><img src="img/rss.gif" style="vertical-align: middle;" border="0"> Rss</a>
  <?php


//  echo ("Copyright (c) 2011 All Rites Reversed");
  echo ("</span>");
  echo ("</center>");
  echo ("</td>");
  echo ("</tr>");
  echo ("</table>");

//      echo ("<div id=\"espacio\" class=\"espacio\">");
//      echo ("</div>");



?>
