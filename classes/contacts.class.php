<?php
class Contacts{
	
	var $contacts = 'user_contacts';
	var $contacts_categories = 'user_contacts_categories';
	var $current_datetime;
	
	/**
	* create_contact
	* @param array $args
	* @return string
	*/
	public function add_contact($args){
		extract($args);
		
		$hashed_password = md5($password);
		$this->current_datetime = mktime();
		
		$sql_query = sprintf("INSERT INTO `$this->contacts` (`user`,`first_name`,`last_name`,`email`,`phone`,`phone_carrier`,`reminder_type`) 
			VALUES ('%d','%s','%s','%s','%s','%s','$reminder_type')",
			$user,
			mysql_real_escape_string($first_name),
			mysql_real_escape_string($last_name),
			mysql_real_escape_string($email),
			mysql_real_escape_string($phone),
			mysql_real_escape_string($phone_carrier)
			);
		
		if(mysql_query($sql_query)){
			return mysql_insert_id();
		}else{
			return 0;
		}		
	}

	public function edit_contact($args){
		extract($args);
		
		$hashed_password = md5($password);
		$this->current_datetime = mktime();
		
		$sql_query = sprintf("UPDATE `$this->contacts` SET `first_name`='%s', `last_name`='%s',`email`='%s',`phone`='%s',`phone_carrier`='%s',`reminder_type`='$reminder_type' WHERE `id`='$contact'",
			mysql_real_escape_string($first_name),
			mysql_real_escape_string($last_name),
			mysql_real_escape_string($email),
			mysql_real_escape_string($phone),
			$phone_carrier
			);
		if(mysql_query($sql_query)){
			return 1;
		}else{
			return 0;
		}		
	}

	/**
	* delete_user_contact
	* Deletes the contacts for the given user id
	* Deleted contacts are also deleted from the contact-categories relational table
	* @param array $args (int $user_id; array $contacts)
	*/
	public function delete_user_contact($args){
		extract($args);
		if($user_id != ''){
			$list_of_contacts = "'" . implode("','",$args['contacts']) . "'";
			$sql = "DELETE FROM ".$this->contacts. "  WHERE `user`='".$user_id."' AND `id` IN (".$list_of_contacts.")";
			if($result = mysql_query($sql)){
				if(mysql_affected_rows() > 0){
					$sql = "DELETE FROM ".$this->contacts_categories. "  WHERE `contact` IN (".$list_of_contacts.")";
					mysql_query($sql);
					return 1;
				}else{
					return 0;
				}
			}
		}else{
			return "invalid user id";
		}
	}
	
	public function get_user_contacts($user_id){
		$sql_query = "SELECT * FROM `$this->contacts` WHERE `user`='$user_id' ORDER BY `first_name` ASC";
		if($result = mysql_query($sql_query)){
			while($row = mysql_fetch_array($result)){
				$results[] = $row;
			}
			return $results;
		}else{
			return 0;
		}			
	}
	
	public function get_user_contacts_count($user_id){
		$sql_query = "SELECT count(*) AS `count` FROM `$this->contacts` WHERE `user`='$user_id'";
		if($result = mysql_query($sql_query)){
			$info = mysql_fetch_array($result);
			$count = $info['count'];
			return $count;
		}else{
			return 0;
		}			
	}
	
	/**
	 * @param string $contact
	 */
	public function get_contact($contact){
		$sql_query = "SELECT * FROM `$this->contacts` WHERE `id`='$contact'";
		if($result = mysql_query($sql_query)){
			$row = mysql_fetch_array($result);
			return $row;
		}else{
			return 0;
		}
	}
	
	public function add_contact_category($args){
		$categories = $args['category'];
		$contact = $args['contact'];
		//remove any existing associations
		$delete_query = "DELETE FROM `$this->contacts_categories` WHERE `contact`='$contact'";
		mysql_query($delete_query);
		if(count($categories) > 0){
			foreach($categories as $category){
				$sql_query = "INSERT INTO `$this->contacts_categories` (`contact`,`category`) VALUES ('$contact','$category')";
				mysql_query($sql_query);
			}		
		}
	}
	
	/**
	* contact_categories
	* Retrieve the categories associated with given contact	
	*/
	
	public function contact_categories($contact){
		$sql_query = "SELECT `contact`,`category` FROM `$this->contacts_categories` WHERE `contact`= '$contact'";
		$result = mysql_query($sql_query);
		if($result){
			while($row = mysql_fetch_array($result)){
				$results[] = $row;
			}
			return $results;
		}else{
			return 0;
		}
	}
}
?>