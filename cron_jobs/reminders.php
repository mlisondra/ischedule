<?php
ini_set('display_errors', 1);
//error_reporting(E_ALL);
error_reporting(E_ERROR);

include('../config/production.php');
include('../classes/accounts.class.php');
include('../classes/contacts.class.php');
include('../classes/categories.class.php');
include('../classes/events.class.php');
include('../classes/reminders.class.php');
include('../includes/utilities.php');
include('../phpmailer/class.phpmailer.php');
$accounts_obj = new Accounts();
$contacts_obj = new Contacts();
$categories_obj = new Categories();
$events_obj = new Events();
$reminders_obj = new Reminders();
$mail = new PHPMailer();
$cdt = mktime();

//process 1 hour before reminders
$begin_marker = $cdt - 300; //begin marker is 5 mins before reminder time
$end_marker = $cdt + 300; //end marker is 5 mins after reminder time
$sql = "SELECT * FROM `event_reminders` WHERE `reminder_send_time` >= '$begin_marker' AND `reminder_send_time` <= '$end_marker' AND `reminder_notification`='1hourbefore' AND `reminder_send_status`='0'";
//echo $sql;
$result = mysql_query($sql);
if($result){
	if(mysql_num_rows($result) > 0){
		while($rec = mysql_fetch_array($result)){
			$reminder_id = $rec['id'];
			$event = $events_obj->get_event($rec['event']);
			if($event != 0){
				//get users based on category from event
				$contacts = $reminders_obj->get_users_by_category($event['category']);
				$category = $categories_obj->get_category_by_id($event['category']);
				$event['calendar'] = $category['name'];
				send_reminders($reminder_id,$event,$contacts); //send reminders for current event to list of contacts
			}
		}
	}
}

//end processing of 1 hour before reminders

//process 1 day before reminders
$begin_marker = $cdt - 300; //begin marker is 23 hours and 55 minutes before reminder time
$end_marker = $cdt + 300; //end marker is 24 hours and 5 mins after reminder time
$sql = "SELECT * FROM `event_reminders` WHERE `reminder_send_time` >= '$begin_marker' AND `reminder_send_time` <= '$end_marker' AND `reminder_notification`='1daybefore' AND `reminder_send_status`='0'";

$result = mysql_query($sql);
if($result){
	if(mysql_num_rows($result) > 0){
		while($rec = mysql_fetch_array($result)){
			$reminder_id = $rec['id'];
			$event = $events_obj->get_event($rec['event']);
			if($event != 0){
				//get users based on category from event
				$contacts = $reminders_obj->get_users_by_category($event['category']);
				$category = $categories_obj->get_category_by_id($event['category']);
				$event['calendar'] = $category['name'];
				send_reminders($reminder_id,$event,$contacts); //send reminders for current event to list of contacts
			}
		}
	}
}
//end processing of 1 day before reminders


//process 1 week before reminders
$begin_marker = $cdt - 300; //begin marker is 1 week and 5 mins before reminder time
$end_marker = $cdt + 300; //end marker is 1 week and 5 mins after reminder time
$sql = "SELECT * FROM `event_reminders` WHERE `reminder_send_time` >= '$begin_marker' AND `reminder_send_time` <= '$end_marker' AND `reminder_notification`='1weekbefore' AND `reminder_send_status`='0'";

$result = mysql_query($sql);
if($result){
	if(mysql_num_rows($result) > 0){
		while($rec = mysql_fetch_array($result)){
			$reminder_id = $rec['id'];
			$event = $events_obj->get_event($rec['event']);
			if($event != 0){
				//get users based on category from event
				$contacts = $reminders_obj->get_users_by_category($event['category']);
				$category = $categories_obj->get_category_by_id($event['category']);
				$event['calendar'] = $category['name'];
				send_reminders($reminder_id,$event,$contacts); //send reminders for current event to list of contacts
			}
		}
	}
}
//end processing of 1 week before reminders

function send_reminders($reminder_id,$event,$contacts){
	global $sms_reminder_template;
	global $email_reminder_template;
	
	
	$data['event_title'] = $event['title'];
	$data['event_date'] = date("m/d/Y",$event['begin_date_time']);
	$data['event_start_time'] = date("h:i A",$event['begin_date_time']);
	$data['calendar'] = $event['calendar'];
	$data['description'] = $event['description'];

	foreach($contacts as $contact){
		if($contact['reminder_type'] == 'SMS'){
			$template = file_get_contents($sms_reminder_template);
			$content = insert_content($data,$template);
			$contact['message'] = $content;
			send_sms_reminder($contact);
		}elseif($contact['reminder_type'] == 'Email'){
			$template = file_get_contents($email_reminder_template);
			$data['contact_name'] = $contact['first_name'];
			$content = insert_content($data,$template);
			$contact['message'] = $content;
			$contact['event_title'] = $data['event_title'];
			send_email_reminder($contact);
		}elseif($contact['reminder_type'] == 'SMS_Email'){
			$template = file_get_contents($sms_reminder_template);
			$content = insert_content($data,$template);
			$contact['message'] = $content;
			send_sms_reminder($contact);			
			
			$template = file_get_contents($email_reminder_template);
			$data['contact_name'] = $contact['first_name'];
			$content = insert_content($data,$template);
			$contact['message'] = $content;
			$contact['event_title'] = $data['event_title'];
			send_email_reminder($contact);	
		}
	}
	
	//update status of reminder notification record
	$sql = "UPDATE `event_reminders` SET `reminder_send_status` = '1' WHERE `id`='".$reminder_id."'";
	mysql_query($sql);
}

function send_sms_reminder($args){
	extract($args);
	//$carriers_array = array("AT&T","Metro PCS","Sprint","T-Mobile","Verizon","Virgin Mobile");
	global $mail;
	
	switch($phone_carrier){
		case "T-Mobile":
			$suffix = "@tmomail.net";
			break;
		case "Verizon":
			$suffix = "@vtext.com";
			break;
		case "AT&T":
			$suffix = "@txt.att.net";
			break;
		case "Metro PCS":
			$suffix = "@mymetropcs.com";	
			break;
		case "Sprint":
			$suffix = "@messaging.sprintpcs.com";
			break;
		//case "Virgin Mobile":
			//break;
	}
	$recipient = $phone . $suffix;
	$recipient_name = $first_name . " " . $last_name;
	$mail->From = "noreply@ischedule247.com";
	$mail->AddAddress($recipient,$recipient_name);
	$mail->Subject  = "iSchedule247 Event Reminder";
	$mail->Body = $message;
	$mail->WordWrap = 50;
	$mail->Send();
	$mail->ClearAllRecipients();

}

function send_email_reminder($args){
	global $mail;
	
	extract($args);
	$recipient_email = $email;
	$recipient_name = $first_name . " " . $last_name;
	$mail->From = "noreply@ischedule247.com";
	$mail->FromName = "iSchedule247 Reminders";
	$mail->AddAddress($recipient_email,$recipient_name);
	$mail->Subject  = "iSchedule247 Event Reminder : " . $event_title;
	$mail->Body = $message;
	$mail->WordWrap = 50;
	$mail->isHTML(true);	
	$mail->Send();
	$mail->ClearAllRecipients();
}

echo '<br/><br/>';

//process 1 day before reminders
$begin_marker = $cdt - 86100; //23 hours and 55 mins before begin of event
$end_marker = $cdt + 86700;//24 hours and 5 mins after begin of event
$sql = "SELECT * FROM `event_reminders` WHERE `reminder_send_time` >= '$begin_marker' AND `reminder_send_time` <= '$end_marker' AND `reminder_notification`='1daybefore'";
//echo $sql;
//end processing of 1 day before reminders

//when a reminder needs to be sent out
//get the event id from event_reminders and retrieve event info
//use the category id to get all the contacts associated with that category
//split the contact list into 3 groups, those to recieve via EMAIL, SMS, or BOTH
//for contacts set to SMS use sms_only_template
//for contacts set to EMAIL use email_only_template
//for contacts set to BOTH send sms only template AND email only template
?>