<?php
session_start();
//error_reporting(E_ALL); 
error_reporting(E_ERROR); 
ini_set("display_errors", 1);
 
// site wide
$SITE_URL = 'http://'.$_SERVER['HTTP_HOST'];

$url_array = explode("/",$_SERVER['REQUEST_URI']);
for($i=3;$i<=count($url_array);$i++){
	$url_path .= '../';
}

// db info
if($_SERVER['HTTP_HOST'] == 'localhost' || $_SERVER['HTTP_HOST'] == 'ischedule.localhost'){
	$serverName = "localhost";
	$username = "root";
	$password =  "";
	$dbToUse = "ischedule";
}else{
	$serverName = "mysql50a.ayera.com";
	$username = "i_vaqn814171";
	$password =  "nasty_staging";
	$dbToUse = "VAQN814171";
}
$dbLink = mysql_connect($serverName,$username,$password);
$dbSelected = mysql_select_db($dbToUse,$dbLink);

//Templates location
$templates = "../templates/";
$sms_reminder_template = $templates . "sms_reminder.tpl";
$email_reminder_template = $templates . "email_reminder.tpl";
?>
