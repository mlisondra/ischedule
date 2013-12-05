<?php
function replace_var($data, $string)
{
    $string1 = $string;
    foreach($data as $key=>$val){
        $string1 = str_replace("##$key##", $val, $string1);
    }
    return $string1;
}

/**
* insert_content
* @param array $data
* @param string $template
*/
function insert_content($data, $template){
    $string1 = $template;
    foreach($data as $key=>$val){
        $string1 = str_replace("##$key##", $val, $string1);
    }
    return $string1;
}

/**
* get_reminder_notifications
* @return array $reminder_notifications Array of notification times ie 1 hour before
*/
function get_reminder_notifications(){
	$reminder_notifications = array("1hourbefore"=>"1 hour before","1daybefore"=>"1 day before","1weekbefore"=>"1 week before");
	return $reminder_notifications;
}

function get_random_string($valid_chars, $length){
    // start with an empty random string
    $random_string = "";

    // count the number of chars in the valid chars string so we know how many choices we have
    $num_valid_chars = strlen($valid_chars);

    // repeat the steps until we've created a string of the right length
    for ($i = 0; $i < $length; $i++)
    {
        // pick a random number from 1 up to the number of valid chars
        $random_pick = mt_rand(1, $num_valid_chars);

        // take the random character out of the string of valid chars
        // subtract 1 from $random_pick because strings are indexed starting at 0, and we started picking at 1
        $random_char = $valid_chars[$random_pick-1];

        // add the randomly-chosen char onto the end of our string so far
        $random_string .= $random_char;
    }

    // return our finished random string
    return $random_string;
}

/**
* mail_out
* Sends HTML email message; milder.lisondra@yahoo.com and victor.aquino@sbcglobal.net are on BCC
* @param array $mail_objects
* List of array elements:
* string from_email
* string from_name
* string to_email
* string subject
* string message_body
*/
function mail_out($mail_objects){

	global $mail;
	$mail->From = $mail_objects["from_email"];
	$mail->FromName = $mail_objects["from_name"];
	$mail->AddAddress($mail_objects["to_email"]);
	$mail->AddBCC("milder.lisondra@yahoo.com");
	$mail->AddBCC("victor.aquino@sbcglobal.net");
	$mail->Subject  = $mail_objects["subject"];
	$mail->Body     = $mail_objects["message_body"];
	$mail->WordWrap = 50;
	$mail->IsHTML(true);
	$mailresponse = $mail->Send();
	$mail->ClearAllRecipients();
	
}

/**
* get_contact_reminder_types
* @return array $types_array
*/
function get_contact_reminder_types(){
	$types_array = array("SMS"=>"Text","Email"=>"Email","SMS_Email"=>"Text &amp; Email");
	return $types_array;
}

/**
* add_calendar_manager
* @param array $args (int $calendar_id, array $managers)
* For each value in array $calendar_manager, create an account, then enter account id and calendar_id 
* into table `calendar_managers`
*/
function add_calendar_manager($args){
	print_r($args);
}

/**
 * check_email_address
 */
function check_email_address($email) {
  // First, we check that there's one @ symbol, 
  // and that the lengths are right.
  if (!ereg("^[^@]{1,64}@[^@]{1,255}$", $email)) {
    // Email invalid because wrong number of characters 
    // in one section or wrong number of @ symbols.
    return false;
  }
  // Split it into sections to make life easier
  $email_array = explode("@", $email);
  $local_array = explode(".", $email_array[0]);
  for ($i = 0; $i < sizeof($local_array); $i++) {
    if
(!ereg("^(([A-Za-z0-9!#$%&'*+/=?^_`{|}~-][A-Za-z0-9!#$%&
↪'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$",
$local_array[$i])) {
      return false;
    }
  }
  // Check if domain is IP. If not, 
  // it should be valid domain name
  if (!ereg("^\[?[0-9\.]+\]?$", $email_array[1])) {
    $domain_array = explode(".", $email_array[1]);
    if (sizeof($domain_array) < 2) {
        return false; // Not enough parts to domain
    }
    for ($i = 0; $i < sizeof($domain_array); $i++) {
      if
(!ereg("^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|
↪([A-Za-z0-9]+))$",
$domain_array[$i])) {
        return false;
      }
    }
  }
  return true;
}