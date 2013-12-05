<?php
$serverName = "mysql50a.ayera.com";
$username = "i_vaqn814171";
$password =  "nasty_staging";
$dbToUse = "VAQN814171";
$dbLink = mysql_connect($serverName,$username,$password);
$dbSelected = mysql_select_db($dbToUse,$dbLink);
?>