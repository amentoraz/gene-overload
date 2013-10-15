<?php

$link_r = mysql_connect("localhost", "geneover_read", "xxxxxxxx");
mysql_select_db("gene_overload",$link_r);
$link_w = mysql_connect("localhost", "geneover_root", "xxxxxxxx", true);
mysql_select_db("gene_overload",$link_w);
?>
