<?php

   Header('Content-type: text/xml');

   echo "<?xml version=\"1.0\"?>
                        <rss version=\"2.0\">
                        <channel>
                        <title>Gene Overload</title>
                        <link>http://www.geneoverload.com</link>
                        <description>
                        Gene Overload - MMO - Artificial Intelligence strategy game
                        </description>
                        <language>en</language>
                ";


//    session_start();




  include ("clases/obj_noticia.php");
  include ("clases/obj_log.php");
  include ("clases/obj_secure.php");
  $secure = new Secure();

  include ("config/database.php");
  include ("config/values.php");






  $num_articulos = 20;

  $noticia = new Noticia();
  $array = $noticia->BuscarNoticiasPublicadas($link_r, $num_articulos, 0); //$limit, $offset);



  if (count($array) > 20)
  {
    $cuantas = 20;
  } else {
    $cuantas = count($array);
  }

  for ($i = 1; $i <= $cuantas; $i++)
  {

      $link = "http://www.geneoverload.com/index.php?catid=53&amp;lang=es&amp;idcampana=&amp;idelemento=".$array[$i]['id']."&amp;accion=comentar";
      // Tenemos que fabricar el $link

                echo "
                        <item>
                        <title>".$array[$i]['titular']."</title>
                        <link>".$link."</link>
";

                if ($autor != null)
                {
                        echo ("<author>Gene Overload</author>");
                }

//                $entradilla = htmlentities($array[$i]['entradilla']);
//if ($i == 3)
//{
//                echo ("<description>".htmlentities(stripslashes($array[$i]['texto']))."</description>
//");
//}
                echo ("<pubDate>".$array[$i]['fecha']."</pubDate>
");
		echo ("
                        </item>");

  }

        echo "
                        </channel>
                        </rss>
                ";







?>
