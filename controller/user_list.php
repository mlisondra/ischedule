<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include('../config/production.php');
include('../classes/accounts.class.php');
include('../classes/contacts.class.php');
include('../classes/categories.class.php');
include('../classes/events.class.php');
include('../includes/utilities.php');
include('../phpmailer/class.phpmailer.php');
$accounts_obj = new Accounts();
$contacts_obj = new Contacts();
$categories_obj = new Categories();
$events_obj = new Events();
$mail = new PHPMailer();

	//retrieve list of user from logged in users database
	$search_term = $_GET['term'];
	$sql = "SELECT * FROM `user_contacts` WHERE (`first_name` LIKE '$search_term%' OR `last_name` LIKE '$search_term%') AND `user`='".$_SESSION['user']['logged_in_id']."'";
	$result = mysql_query($sql);
	if($result){
		while($rec = mysql_fetch_array($result)){
			$contact_name = $rec['first_name'] . " " . $rec['last_name'];
			$contacts_array[] = array("id"=>$rec['id'], "value"=>$contact_name);
		}
	}
	print json_encode($contacts_array);

?>