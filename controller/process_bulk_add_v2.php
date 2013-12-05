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
$num_user_submitted_contacts = count($email_addresses);

$user = $accounts_obj->get_user($_SESSION['user']['email']);
$current_num_contacts = $contacts_obj->get_user_contacts_count($_SESSION['user']['logged_in_id']);
$subscription_level = $user['subscription'];
$max_emails = 0;
switch($subscription_level){
	case "free":
		$max_emails = 10;
		break;
	case "level1":
		$max_emails = 200;
		break;
}

$allowance = $max_emails - $current_num_contacts;
$num_added = 0;

if($allowance == 0){
	$data['message'] = 'You have reached the maximum number of contacts for your subscription type.';
	$data['status'] = "fail"; 
}else{

	if($num_user_submitted_contacts < $allowance){
		$loop_max = $num_user_submitted_contacts;
	}else{
		$loop_max = $allowance;
	}
			//while($count <= $current_contacts_allowed){
			for($i=0; $i <= $loop_max - 1; $i++){
				//$count = $index + 1;
				//echo $email_addresses[$i]."<br/>";
				
				$less_than_pos = 0;
				$contact_first_name = "";
				$contact_last_name = "";
				$phone = "";
				$phone_carrier = "";
				$reminder_type = "";
				$string_len = strlen(trim($email_addresses[$i]));
				$less_than_pos = strripos($email_addresses[$i],"<");
				$greater_than_pos = strrpos($email_addresses[$i],">");
				if($less_than_pos === 0 || $less_than_pos === "" || $less_than_pos == ""){
					$less_than_pos = 0;
				}
				
				if($less_than_pos == 0){
					$email = ltrim($email_addresses[$i]);
					$contact_first_name = $email;
				}else{
					$email_address = ltrim($email_addresses[$count]);
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
					list($username,$domain_name) = split("@", $email); //get domain part of email address
					$notwelcome_domains =  array("googlegroups.com","googlegroup.com","yahoogroups.com","yahoogroup.com"); //check to see if email address has unwanted domain
					if(!in_array($domain_name,$notwelcome_domains)){
						$result = $contacts_obj->add_contact($args);
						if($result != 0){
							$num_added++;
						}
					}
				}							
				$count++;
	
		}
	if($num_added > 0){
		$data['status'] = "success";
		$data['num_added'] = $num_added;
	}else{
		$data['status'] = "failed";
		$data['num_added'] = $num_added;
	}

}

print json_encode($data);
?>