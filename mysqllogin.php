<?php
$con = mysql_connect("cs-server.usc.edu:1801", "root", "1917");
if (!$con) die('Service temporary unavailable!!');
mysql_select_db('Travel', $con);
?>