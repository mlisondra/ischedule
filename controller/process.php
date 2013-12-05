<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

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


$action = $_POST['action'];

switch($action){
	case "get_contacts":
		$contacts = $contacts_obj->get_user_contacts($_SESSION['user']['logged_in_id']);
		$response = "";
		if($contacts != 0){
			foreach($contacts as $key=>$contact){
				$sql = "SELECT t1.*, t2.color FROM `user_contacts_categories` t1 LEFT JOIN `user_categories` t2
						ON t1.category = t2.id  WHERE `contact` = '".$contact['id']."'";
				$result = mysql_query($sql);
				$color_list = "";
				$color_list .= '<div class="color_list"><ul>';
				while($rec = mysql_fetch_array($result)){
					$color_list .= '<li style="background-color:'.$rec[color].'">&nbsp;</li>';
				}
				$color_list .= '</ul></div>';
				$response .= "<li style=\"border-top: 1px solid #6287A7;padding-left:5px;\"><a href=\"###\" title=\"Click to edit contact\" onclick=\"show_modal('edit_contact','".$contact[id]."');\" id=\"".$contact[id]."\">".$contact[first_name] . " " . $contact[last_name]."</a>";
				$response .= $color_list ."</li>";
			}
		}else{
			$response = '<li style="border-top: 1px solid #6287A7;padding-left:10px;">You have no contacts.<br/><a href="###"  onclick="show_modal(\'add_contact\');">Add a contact now.</a></li>';
		}
		print $response;
		break;
	case "get_categories"; //calendars
		$categories = $categories_obj->get_user_categories($_SESSION['user']['logged_in_id']);
		$other_calendars = $categories_obj->get_managing_calendars($_SESSION['user']['logged_in_id']);
		if($categories !=0 ){
			foreach($categories as $key=>$category){
				$response .= '<li style="border-top: 1px solid #6287A7;background:'.$category[color].';padding-left:10px;"> <a href="###" title="view events" onclick="expand_events(\'category_'.$category[id].'\')">+&nbsp;&nbsp;</a>';
				$response .= "<a href=\"###\" onclick=\"show_modal('edit_category','".$category[id]."');\" id=\"".$category[id]."\" title=\"Click to edit Calendar\">".$category[name]."</a></li>";
				$sql = "SELECT * FROM `user_events` WHERE `category`='".$category[id]."'";
				$result = mysql_query($sql);
				$response .= '<div style="display:block;" id="category_'.$category[id].'">';
				while($event = mysql_fetch_array($result)){
					$response .= '<li style="padding-left:20px;border-top: 1px solid #6287A7;">';
					$response .= "<a href=\"###\" title=\"click to edit event\" onclick=\"show_modal('edit_event','".$event[id]."');\" id=\"".$event[id]."\">".$event[title]." (". date("n/j/Y",$event[begin_date_time]) . ")</a></li>";
				}
				$response .= '</div>';
			}
		}
		//check to see if logged in user is a calendar administrator
		if($other_calendars !=0 ){
			foreach($other_calendars as $key=>$calendar){
				$response .= '<li style="border-top: 1px solid #6287A7;background:'.$calendar[color].';padding-left:10px;"> <a href="###" title="view events" onclick="expand_events(\'category_'.$calendar[id].'\')">+&nbsp;&nbsp;</a>';
				//$response .= "<a href=\"###\" onclick=\"show_modal('edit_category','".$calendar[id]."');\" id=\"".$calendar[id]."\" title=\"Click to edit Calendar\">".$calendar[name]."</a><span style=\"padding-right:3px;float:right;\" title=\"You are an administrator for this calendar\">//</span></li>";
				$response .= $calendar['name']."<span style=\"padding-right:3px;float:right;\" title=\"You are an administrator for this calendar\">//</span></li>";
				$sql = "SELECT * FROM `user_events` WHERE `category`='".$calendar[id]."'";
				$result = mysql_query($sql);
				$response .= '<div style="display:block;" id="category_'.$calendar[id].'">';
				while($event = mysql_fetch_array($result)){
					$response .= '<li style="padding-left:20px;border-top: 1px solid #6287A7;">';
					$response .= "<a href=\"###\" title=\"click to edit event\" onclick=\"show_modal('edit_event','".$event[id]."');\" id=\"".$event[id]."\">".$event[title]." (". date("n/j/Y",$event[begin_date_time]) . ")</a></li>";
				}
				$response .= '</div>';
			}
		}
		if($categories == 0 && $other_calendars == 0){
			$response = '<li style="margin-left:10px;">You have no Calendars.</ul>';
		}
		print $response;
		break;
	case "get_user_events":	
		$events = $events_obj->get_user_events($_SESSION['user']['logged_in_id'],5);
		if($events != 0 ){
			foreach($events as $event){
				$response .= '<li style="border-top: 1px solid #6287A7;padding-left:10px;">';
				$response .= "<a href=\"###\" onclick=\"show_modal('edit_event','".$event[id]."');\" id=\"".$event[id]."\">".$event[title]."</a></li>";
			}
		}else{
			$response .= '<li style="border-top: 1px solid #6287A7;padding-left:10px;">No Events Found</li>';
		}
		print $response;
		break;
	case "get_modal_content":
		$modal_type = $_POST['modal_type'];
		switch($modal_type){
			case "add_contact":
				$modal_content_template = file_get_contents('../templates/add_contact.tpl');
				$categories = $categories_obj->get_user_categories($_SESSION['user']['logged_in_id']);
				if($categories != 0){
					foreach($categories as $category){
						$user_categories .= '<input name="category[]" type="checkbox" id="'.$category[name].'" value="'.$category[id].'"><label for="'.$category[name].'">'.$category['name'].'</label><br>';
					}
				}else{
					$user_categories = 'You don\'t have any categories';
				}
				
				$phone_carriers = get_phone_carriers();
				$phone_carrier_list = '<select name="phone_carrier" id="phone_carrier"><option value="">--Select One--</option>';
				foreach($phone_carriers as $carrier){
					$phone_carrier_list .= '<option value="'.$carrier.'">'.$carrier.'</option>';
				}
				$phone_carrier_list .= '</select>';
				$data['phone_carrier_list'] = $phone_carrier_list;
				
				$data['user_categories'] = $user_categories;
				$data_template = replace_var($data,$modal_content_template);
				$response = $data_template;
				break;
			case "edit_contact":
				$contact_id = $_POST['obj_id'];
				
				$contact_info = $contacts_obj->get_contact($contact_id);
				$modal_content_template = file_get_contents('../templates/edit_contact.tpl');
				foreach($contact_info as $key=>$value){
					$data[$key] = $value;
				}
							
				$categories = $categories_obj->get_user_categories($_SESSION['user']['logged_in_id']);
				$contact_categories = $contacts_obj->contact_categories($contact_id);
				if($contact_categories != 0){
					foreach($contact_categories as $category){
						$selected_categories[] = $category['category']; //array of associated categories
					}
				}
				if($categories != 0){
					foreach($categories as $category){
						if(count($selected_categories) > 0){
							if(in_array($category[id],$selected_categories)){
								$user_categories .= '<input name="category[]" type="checkbox" id="'.$category[name].'" value="'.$category[id].'" checked><label for="'.$category[name].'">'.$category['name'].'</label><br>';
							}else{
								$user_categories .= '<input name="category[]" type="checkbox" id="'.$category[name].'" value="'.$category[id].'"><label for="'.$category[name].'">'.$category['name'].'</label><br>';
							}
						}else{
							$user_categories .= '<input name="category[]" type="checkbox" id="'.$category[name].'" value="'.$category[id].'"><label for="'.$category[name].'">'.$category['name'].'</label><br>';
						}						
					}
				}else{
					$user_categories = 'You don\'t have any categories';
				}
				
				$phone_carriers = get_phone_carriers();
				$phone_carrier_list = '<select name="phone_carrier" id="phone_carrier">';
				if($contact_info['phone_carrier'] == ""){
					$phone_carrier_list .= '<option value="" selected>--Select One--</option>';
				}				
				foreach($phone_carriers as $carrier){
					if($contact_info['phone_carrier'] == $carrier){
						$phone_carrier_list .= '<option value="'.$carrier.'" selected>'.$carrier.'</option>';
					}else{
						$phone_carrier_list .= '<option value="'.$carrier.'">'.$carrier.'</option>';
					}
				}
				$phone_carrier_list .= '</select>';
				$data['phone_carrier_list'] = $phone_carrier_list;
				
				$data['user_categories'] = $user_categories;
				$reminder_types = get_contact_reminder_types();
				foreach($reminder_types as $key=>$type){
					if($key != $contact_info['reminder_type']){
						$reminder_type_list .= '<option value="'.$key.'">'.$type.'</option>';
					}else{
						$reminder_type_list .= '<option value="'.$key.'" selected>'.$type.'</option>';
					}
				}
				$data['reminder_type_list'] = $reminder_type_list;
				$data['contact'] = $contact_id;
				$data_template = replace_var($data,$modal_content_template);
				$response = $data_template;
				break;
			case "manage_contacts":
				$modal_content_template = file_get_contents('../templates/manage_contacts.tpl');
				$contacts = $contacts_obj->get_user_contacts($_SESSION['user']['logged_in_id']);
				if($contacts != 0){
					foreach($contacts as $contact){
						$contact_name = $contact['first_name'] . ' ' . $contact['last_name'];
						$user_contacts .= '<input name="contact[]" type="checkbox" id="'.$contact_name.'" value="'.$contact[id].'"><label for="'.$contact_name.'">'.$contact_name.'</label><br>';
					}
				}else{
					$user_contacts = 'You don\'t have any contacts';
				}
				$data['user_contacts'] = $user_contacts;
				$content = replace_var($data,$modal_content_template);
				print $content;
				break;
			case "add_category":
				//get number of calendars that belongs to user
				$categories = $categories_obj->get_user_categories($_SESSION['user']['logged_in_id']);
				if(count($categories) < $_SESSION['user']['max_calendars']){
					$modal_content_template = file_get_contents('../templates/add_category.tpl');
				}else{
					$data['feature'] = 'Calendars';
					$modal_content_template = file_get_contents('../templates/max_features.tpl');
					$modal_content_template = replace_var($data,$modal_content_template);
				}				
				$response = $modal_content_template;			
				break;
			case "edit_category":
				$category_id = $_POST['obj_id'];
				$category_info = $categories_obj->get_category_by_id($category_id);
				//check to see if there are any managers for this calendar
				$calendar_managers = $categories_obj->get_calendar_managers($category_id);
				if($calendar_managers != 0){
					$num_managers = count($calendar_managers);
					$num_available_managers = 5 - $num_managers;
					if($num_managers > 0){
						foreach($calendar_managers as $manager){
							$manager_info = $accounts_obj->get_user_by_id($manager['manager_id']);
							$manager_name = $manager_info['first_name'] . " " . $manager_info['last_name'];
							$manager_names .= '<span style="padding:0 10px 0 5px;"><a href="###" title="remove manager" onclick="remove_manager(\''.$manager['manager_id'].'\',\''.$category_id.'\')">X</a></span><span>'.$manager_name.'</span><br/>';
						}
						for($i = 1; $i <= $num_available_managers; $i++){
							$manager_inputs .= '<input type="text" class="calendar_manager" name="calendar_manager[]" size="40"><br/>';
						}
					}
					$category_info['manager_inputs'] = $manager_inputs;
				}else{
						for($i = 1; $i <= 5; $i++){
							$manager_inputs .= '<input type="text" class="calendar_manager" name="calendar_manager[]" size="40"><br/>';
						}				
				}
				$category_info['manager_names'] = $manager_names;
				$category_info['manager_inputs'] = $manager_inputs;
				$modal_content_template = file_get_contents('../templates/edit_category.tpl');
				$data_template = replace_var($category_info,$modal_content_template);
				$response = $data_template;			
				break;
			case "manage_categories":
				$modal_content_template = file_get_contents('../templates/manage_categories.tpl');
				$categories = $categories_obj->get_user_categories($_SESSION['user']['logged_in_id']);
				if($categories != 0){
					foreach($categories as $category){
						if(count($selected_categories) > 0){
							if(in_array($category[id],$selected_categories)){
								$user_categories .= '<input name="category[]" type="checkbox" id="'.$category[name].'" value="'.$category[id].'"><a href="###" onclick="show_modal(\''.edit_category.'\','.$category[id].')">'.$category['name'].'</a><br>';
							}else{
								$user_categories .= '<input name="category[]" type="checkbox" id="'.$category[name].'" value="'.$category[id].'"><a hr="###" onclick="show_modal(\''.edit_category.'\','.$category[id].')">'.$category['name'].'</a><br>';
							}
						}else{
							$user_categories .= '<input name="category[]" type="checkbox" id="'.$category[name].'" value="'.$category[id].'"><a href="###" onclick="show_modal(\''.edit_category.'\','.$category[id].')">'.$category['name'].'</a><br>';
						}						
					}
				}else{
					$user_categories = 'You don\'t have any Calendars';
				}
				$data['user_categories'] = $user_categories;
				$content = replace_var($data,$modal_content_template);
				print $content;
				break;
			case "add_event":
				//check to see that user has at least 1 calendar
				$calendars = $categories_obj->get_user_categories($_SESSION['user']['logged_in_id']);
				if(count($calendars) >= 1){
					//get number of events that user has
					$num_events = $events_obj->get_user_future_events($_SESSION['user']['logged_in_id']);
					if($num_events < $_SESSION['user']['max_events']){
						$modal_content_template = file_get_contents('../templates/add_event.tpl');
									
						$data['begin_date'] = "";
						$data['end_date'] = "";
						if(isset($_POST['begin_date'])){
							$data['begin_date'] = $_POST['begin_date'];
						}
						if(isset($_POST['end_date'])){
							$data['end_date'] = $_POST['end_date'];
						}				
						$categories = $categories_obj->get_user_categories($_SESSION['user']['logged_in_id']);
						$other_calendars = $categories_obj->get_managing_calendars($_SESSION['user']['logged_in_id']);
						if($categories != 0){
							foreach($categories as $category){
								$user_categories .= '<input name="category" type="radio" id="'.$category[name].'" value="'.$category[id].'"><label for="'.$category[name].'">'.$category['name'].'</label><br>';
							}
						}
						if($other_calendars != 0){
							foreach($other_calendars as $calendar){
								$user_categories .= '<input name="category" type="radio" id="'.$calendar[name].'" value="'.$calendar[id].'"><label for="'.$calendar[name].'">'.$calendar['name'].'</label><br>';
							}
						}						
						if($categories == 0 && $other_calendars == 0){
							$user_categories = 'You don\'t have any Calendars';
						}
						$data['user_categories'] = $user_categories;
						$reminder_notifications = get_reminder_notifications();
						foreach($reminder_notifications as $key=>$notification){
							if($key == "none"){
								$reminder_notification_list .= '<input type="checkbox" name="reminder_notification[]" id="'.$key.'" value="'.$key.'" checked><label for="'.$key.'">'.$notification.'</label><br/>';
							}else{
								$reminder_notification_list .= '<input type="checkbox" name="reminder_notification[]" id="'.$key.'" value="'.$key.'"><label for="'.$key.'">'.$notification.'</label><br/>';
							}
						}
						$reminder_notification_list .= '<input type="checkbox" name="reminder_notification_all" id="reminder_notification_all"><label for="reminder_notification_all">All</label><br/>';
						$data['reminder_notification_list'] = $reminder_notification_list;
					}else{
						$data['feature'] = 'Events';
						$modal_content_template = file_get_contents('../templates/max_features.tpl');
					}
				}else{
					$data['message'] = "Reminder<br/>"; 
					$modal_content_template = file_get_contents('../templates/create_calendar_notification.tpl');
					
				}
				$content = replace_var($data,$modal_content_template);
				print $content;
				break;
			case "edit_event":
				$modal_content_template = file_get_contents('../templates/edit_event.tpl');
				$event = $events_obj->get_event($_POST['obj_id']);
				$event['begin_date'] = date("m/d/Y",$event['begin_date_time']);
				$event['begin_time'] = date("h:i A",$event['begin_date_time']);
				$event['end_date'] = date("m/d/Y",$event['end_date_time']);	
				$event['end_time'] = date("h:i A",$event['end_date_time']);
				//check to see if the user owns the event
				if($event['user'] == $_SESSION['user']['logged_in_id']){
					$categories = $categories_obj->get_user_categories($_SESSION['user']['logged_in_id']);
					if($categories != 0){
						foreach($categories as $category){
							if($event['category'] != $category['id']){
								$user_categories .= '<input name="category" type="radio" id="'.$category[name].'" value="'.$category[id].'"><label for="'.$category[name].'">'.$category['name'].'</label><br>';
							}else{
								$user_categories .= '<input name="category" type="radio" id="'.$category[name].'" value="'.$category[id].'" checked><label for="'.$category[name].'">'.$category['name'].'</label><br>';
							}
						}
					}else{
						$user_categories = 'You don\'t have any categories';
					}
				}else{ //automatically assign event to existing calendar
					$category = $categories_obj->get_category_by_id($event['category']);
					$user_categories .= '<input name="category" type="radio" id="'.$category[name].'" value="'.$category[id].'" checked><label for="'.$category[name].'">'.$category['name'].'</label><br>';
				}
				$event['user_categories'] = $user_categories;
				$event_reminder_notifications = $events_obj->get_event_reminders($_POST['obj_id']);
				$reminder_notifications = get_reminder_notifications();
				foreach($reminder_notifications as $key=>$notification){
					if(in_array($key,$event_reminder_notifications)){
						$reminder_notification_list .= '<input type="checkbox" name="reminder_notification[]" id="'.$key.'" value="'.$key.'" checked><label for="'.$key.'">'.$notification.'</label><br/>';
					}else{
						$reminder_notification_list .= '<input type="checkbox" name="reminder_notification[]" id="'.$key.'" value="'.$key.'"><label for="'.$key.'">'.$notification.'</label><br/>';
					}
				}
				$event['reminder_notification_list'] = $reminder_notification_list;
				$content = replace_var($event,$modal_content_template);
				print $content;
				break;
			case "manage_events":
				$modal_content_template = file_get_contents('../templates/manage_events.tpl');
				$events = $events_obj->get_user_events($_SESSION['user']['logged_in_id']);
				if($events != 0){
					$user_events .= '<tr valign="top"><td width="100" align="center"><span style="font-size:10px;">mark<br/>to<br/>delete</span></td><td width="150">Event</td><td width="200">Calendar</td></tr>';
					foreach($events as $event){
						//calendar info
						$calendar = $categories_obj->get_category_by_id($event['category']);
						if(count($selected_categories) > 0){
							if(in_array($category[id],$selected_categories)){
								$user_categories .= '<tr><td><input name="category[]" type="checkbox" id="'.$category[name].'" value="'.$category[id].'" checked></td><td><a href="###" onclick="show_modal(\'edit_event\')">'.$category['name'].'</a></td></tr>';
							}else{
								$user_categories .= '<tr><td><input name="category[]" type="checkbox" id="'.$category[name].'" value="'.$category[id].'"></td><td><a href="###" onclick="show_modal(\'edit_event\',\''.$event[id].'\')" title="click to edit event">'.$category['name'].'</a></td></tr>';
							}
						}else{
							$user_events .= '<tr><td align="center"><input name="event[]" type="checkbox" id="'.$event[title].'" value="'.$event[id].'"></td><td><a href="###" onclick="show_modal(\'edit_event\',\''.$event[id].'\')" title="click to edit event">'.$event['title'].'</a></td><td>'.$calendar['name'].'</td></tr>';
						}						
					}
				}else{
					$user_events = '<tr><td>You don\'t have any events</td></tr>';
				}
				$data['user_events'] = $user_events;
				$content = replace_var($data,$modal_content_template);
				print $content;
				break;
			case "my_account":
				$user_info = $accounts_obj->get_user($_SESSION['user']['email']);
				$phone_carriers = get_phone_carriers();
				$phone_carrier_list = '<select name="phone_carrier" id="phone_carrier">';
				foreach($phone_carriers as $carrier){
					if($user_info['phone_carrier'] == $carrier){
						$phone_carrier_list .= '<option value="'.$carrier.'" selected>'.$carrier.'</option>';
					}else{
						$phone_carrier_list .= '<option value="'.$carrier.'">'.$carrier.'</option>';
					}
				}
				$phone_carrier_list .= '</select>';
				$user_info['phone_carrier_list'] = $phone_carrier_list;
				$modal_content_template = file_get_contents('../templates/my_account.tpl');
				$data_template = replace_var($user_info,$modal_content_template);
				$response = $data_template;
				break;
			case "faqs":
				$modal_content_template = file_get_contents('../templates/faqs.tpl');
				$response = $modal_content_template;
				break;
			case "terms":
				$modal_content_template = file_get_contents('../templates/terms.tpl');
				$response = $modal_content_template;				
				break;
			case "about":
				$modal_content_template = file_get_contents('../templates/about.tpl');
				$response = $modal_content_template;
				break;	
			case "bulk_add":
				$modal_content_template = file_get_contents('../templates/add_contacts.tpl');
				$response = $modal_content_template;
				break;
				
		}
		print $response;
				
		break;
	case "get_static_modal_content":
		$modal_type = $_POST['modal_type'];
		switch($modal_type){
			case "faqs":
				$modal_content_template = file_get_contents('../templates/faqs.tpl');
				$response = $modal_content_template;
				break;
			case "terms":
				$modal_content_template = file_get_contents('../templates/terms.tpl');
				$response = $modal_content_template;				
				break;
			case "about":
				$modal_content_template = file_get_contents('../templates/about.tpl');
				$response = $modal_content_template;
				break;
		}
		print $response;
		break;
	case "add_contact":
		$_POST['user'] = $_SESSION['user']['logged_in_id'];
		$result = $contacts_obj->add_contact($_POST);
		//check to see if there are any categories selected
		if(count($_POST['category']) > 0){
			$_POST['contact'] = $result;
			$contacts_obj->add_contact_category($_POST);
		}
		if($result == 0){
			print 0;
		}else{
			print 1;
		}
		break;
	case "edit_contact":
		$result = $contacts_obj->edit_contact($_POST);
		//check to see if there are any categories selected
		if($result == 0){
			print 0;
		}else{
			$contacts_obj->add_contact_category($_POST);
			print 1;
		}
		break;
	case "delete_contact":
		$args['user_id'] = $_SESSION['user']['logged_in_id'];
		$args['contacts'] = $_POST['contact'];
		
		$result = $contacts_obj->delete_user_contact($args);
		print $result;	
		break;
	case "add_category":
		$_POST['user'] = $_SESSION['user']['logged_in_id'];
		$result = $categories_obj->add_category($_POST);
		if($result == 0){
			print 0;
		}else{
			print 1;
		}
		break;
	case "edit_category":
		$result = $categories_obj->edit_category($_POST);
		if(count($_POST['manager_ids']) > 0){
			foreach($_POST['manager_ids'] as $manager){
				//get the information for contact
				$contact = $contacts_obj->get_contact($manager);
				//check to see if the user already has an account
				if($accounts_obj->check_account_exists($contact['email'])){
					//get account info
					$account = $accounts_obj->get_user($contact['email']);
					$manager = $account['id'];
				}else{
					//create new account for manager
					$contact['user_email'] = $contact['email'];
					$contact['password'] = get_random_string("abcdefABCDEF_0123456789",13);
					$manager = $accounts_obj->create_account($contact);
					//need to send email to this person
				}
				$args = array("calendar"=>$_POST['id'],"manager"=>$manager);
				$categories_obj->add_calendar_manager($args);
			}
		}
		print $result;
		break;
	case "delete_category":
		$args['user_id'] = $_SESSION['user']['logged_in_id'];
		$args['categories'] = $_POST['category'];
		$result = $categories_obj->delete_user_category($args);
		print $result;
		break;
	case "edit_my_account":
		$_POST['email'] = $_SESSION['user']['email'];
		$result = $accounts_obj->edit_account($_POST);
		print $result;
		break;
	case "add_event":
		//get the calendar owner
		$calendar = $categories_obj->get_category_by_id($_POST['category']);
		$_POST['user'] = $calendar['user'];
		$result = $events_obj->add_event($_POST);
		print 1;
		break;
	case "edit_event":
		$result = $events_obj->edit_event($_POST);
		print $result;
		break;
	case "delete_event":
		$args['user_id'] = $_SESSION['user']['logged_in_id'];
		$args['events'] = $_POST['event'];
		$result = $events_obj->delete_user_event($args);
		print $result;
		break;
	case "get_calendar_managers":
		extract($_POST);
		$calendar_managers = $categories_obj->get_calendar_managers($calendar_id);
		if($calendar_managers != 0){
			$num_managers = count($calendar_managers);
			$num_available_managers = 5 - $num_managers;
			if($num_managers > 0){
				foreach($calendar_managers as $manager){
					$manager_info = $accounts_obj->get_user_by_id($manager['manager_id']);
					$manager_name = $manager_info['first_name'] . " " . $manager_info['last_name'];
					$manager_names .= '<span style="padding:0 10px 0 5px;"><a href="###" title="remove manager" onclick="remove_manager(\''.$manager['manager_id'].'\',\''.$calendar_id.'\')">X</a></span><span>'.$manager_name.'</span><br/>';
				}
				for($i = 1; $i <= $num_available_managers; $i++){
					$manager_inputs .= '<input type="text" class="calendar_manager" name="calendar_manager[]" size="40"><br/>';
				}
			}
			$category_info['manager_inputs'] = $manager_inputs;
		}else{
				for($i = 1; $i <= 5; $i++){
					$manager_inputs .= '<input type="text" class="calendar_manager" name="calendar_manager[]" size="40"><br/>';
				}				
		}

		$response = '<div id="manager_names">'.$manager_names.'</div><div id="managers_list" style="margin-top:5px;">'.$manager_inputs.'</div>';
		print $response;
		break;
	case "remove_manager":
		$result = $categories_obj->remove_calendar_manager($_POST);
		print $result;
		break;
}

function get_phone_carriers(){
	$carriers_array = array("AT&T","Metro PCS","Sprint","T-Mobile","Verizon","Virgin Mobile");
	return $carriers_array;
}

/**
* get_reminder_types
* @return array $reminder_types
*/
function get_reminder_types(){
	$reminder_types = array("Email"=>"Email","SMS"=>"Text","Text & Email"=>"SMS_Email");
	return $reminder_types;
}
?>