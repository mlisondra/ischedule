<?php
$serverName = "localhost";
$username = "root";
$password =  "";
$dbToUse = "ischedule";
$dbLink = mysql_connect($serverName,$username,$password);
$dbSelected = mysql_select_db($dbToUse,$dbLink);
?>