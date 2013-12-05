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

extract($_POST);
$email_addresses = explode(",",$email_list);
$user = $accounts_obj->get_user($_SESSION['user']['email']);
$current_num_contacts = $contacts_obj->get_user_contacts_count($_SESSION['user']['logged_in_id']);
$subscription_level = $user['subscription'];
$max_emails = 0;
switch($subscription_level){
	case "free":
		$max_emails = 20;
		break;
	case "level1":
		$max_emails = 200;
		break;
}

$max_contacts_allowed = $max_emails;
$current_contacts_allowed = $max_contacts_allowed - $current_num_contacts;
if(count($email_addresses) <= $current_contacts_allowed){ //check to see that the user does not go beyond allowed number of contacts

	foreach($email_addresses as $email_address){
		
			$less_than_pos = 0;
			$contact_first_name = "";
			$contact_last_name = "";
			$phone = "";
			$phone_carrier = "";
			$reminder_type = "";
			$string_len = strlen(trim($email_address));
			
			$less_than_pos = strripos($email_address,"<");
			$greater_than_pos = strrpos($email_address,">");
			if($less_than_pos === 0 || $less_than_pos === "" || $less_than_pos == ""){
				$less_than_pos = 0;
			}
			
			if($less_than_pos == 0){
				$email = ltrim($email_address);
				$contact_first_name = $email;
			}else{
				$email_address = ltrim($email_address);
				$temp_string_array = explode(" ",$email_address);
				$contact_first_name = $temp_string_array[0];
				$contact_last_name = $temp_string_array[1];
				
				$email = $temp_string_array[2];
				$email = substr($email,1);
				$email = substr($email,0,strlen($email) - 1);
			}
			$user = $_SESSION['user']['logged_in_id'];
			$args = array("user"=>$user,"first_name"=>$contact_first_name,"last_name"=>$contact_last_name,"email"=>$email,"phone"=>$phone,"phone_carrier"=>$phone_carrier,"reminder_type"=>$reminder_type);
			if(check_email_address($email)){
				echo $email;	
				list($username,$domain_name) = split("@", $email); //get domain part of email address
				$notwelcome_domains =  array("googlegroups.com","googlegroup.com","yahoogroups.com","yahoogroup.com"); //check to see if email address has unwanted domain
				if(!in_array($domain_name,$notwelcome_domains)){
					$contacts_obj->add_contact($args);
				}
			}
			

	}
	$response = array("status"=>"success","message"=>"");
}else{
	$message = "We could not import your contacts. <br/>Your subscription type allows for a maximum of " . $max_contacts_allowed." contacts. <br/>";
	$message .= "You are trying to add " . count($email_addresses) . " contacts";
	$message .= "<br/><br/>Note: You currently have " . $current_num_contacts . " contacts";
	$response = array("status"=>"fail","message"=>$message);
}
print json_encode($response);
?>