<?php
include('../config/production.php');
include('../classes/accounts.class.php');
include("../phpmailer/class.phpmailer.php");

include("../includes/utilities.php"); //helper functions

$mail = new PHPMailer();
$accounts_obj = new Accounts();

//Get desired action
$action = $_POST['action'];

switch($action){
	case "create_account":
		$result = $accounts_obj->create_account($_POST);
		if($result != 0){
			$message_body = "Thank you for creating your iSchedule 247.com account<br/>";
			$message_body .= "Below is your account information<br/>";
			$message_body .= $_POST['user_email']."<br>";
			$message_body .= $_POST['password'];
			$mail_objects = array("from_email"=>"accounts@ischedule247.com","from_name"=>"iSchedule 247.com Accounts","to_email"=>$_POST[user_email],"subject"=>"Your iSchedule account has been created","message_body"=>$message_body);
			mail_out($mail_objects);
			$response = "success";
		}elseif($result == 'email exists'){
			$response = "email exists";
		}
		print $response;	
		break;
	case "auth_user":		
		$args = array("email"=>"$_POST[user_email]","password"=>"$_POST[password]");
		$result = $accounts_obj->authenticate_user($args);
		switch($result){
			case "1":
				//successful authentication, redirect user
				//set up session variables
				$account_info = $accounts_obj->get_user($_POST[user_email]);
				$_SESSION['user']['logged_in'] = 'yes';
				$_SESSION['user']['logged_in_id'] = $account_info['id'];
				$_SESSION['user']['first_name'] = $account_info['first_name'];
				$_SESSION['user']['last_name'] = $account_info['last_name'];
				$_SESSION['user']['email'] = $account_info['email'];
				$_SESSION['user']['subscription'] = $account_info['subscription'];
				switch($account_info['subscription']){
					case "free":
						$_SESSION['user']['max_calendars'] = 1;
						$_SESSION['user']['max_events'] = 20;
						$_SESSION['user']['max_contacts'] = 20;
						break;
					case "level1":
						$_SESSION['user']['max_calendars'] = 5;
						$_SESSION['user']['max_events'] = 150;
						$_SESSION['user']['max_contacts'] = 200;
						break;
					default:
						break;
				}
				$response = array("response"=>"success");
				break;
			case "0":
				$response = array("response"=>"fail");
				break;
			case "email does not exist":
				$response = array("response"=>$result);
				break;
			
		}
		print json_encode($response);
		break;
	case "reset_password":
		$token = get_random_string("abcdefABCDEF_0123456789",13);
		$_POST['token'] = $token;
		$result = $accounts_obj->reset_password($_POST);
		if($result == 1){
			$message_body = 'Below is your new password. We strongly suggest that you change your password the next time you login.<br>';
			$message_body .= $token;
			$mail_objects = array("from_email"=>"milder.lisondra@yahoo.com","from_name"=>"Milder Lisondra","to_email"=>$_POST[user_email],"subject"=>"Your iSchedule password has been reset","message_body"=>$message_body);
			mail_out($mail_objects);
		}
		print $result;
		break;
	default:
		break;
}

?>