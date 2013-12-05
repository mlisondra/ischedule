<?php
class Accounts{
	
	var $users = 'users';
	var $current_datetime;
	
	/**
	* create_account
	* @param array $args
	* @return string
	*/
	public function create_account($args){
		$first_name = addslashes(trim($args['first_name']));
		$last_name = addslashes(trim($args['last_name']));
		$email = addslashes(trim($args['user_email']));
		$password = addslashes(trim($args['password']));
		$hashed_password = md5($password);
		$this->current_datetime = mktime();
		//check to see if the given email address belongs to an existing account

		if($this->check_account_exists($email) == 0){
			$query = "INSERT INTO `$this->users` (`first_name`,`last_name`,`email`,`password`) VALUES ('$first_name','$last_name','$email','$hashed_password')";
			$result = mysql_query($query);
			if($result){
				//return 1;
				return mysql_insert_id();
			}else{
				return 0;
			}		
		}else{
			return 'email exists';
		}
		
	}
	
	/** 
	* edit_account
	* @param array @args
	* @return int 1 on success, 0 on failure
	* The method takes into account wether a user has chosen to change their password
	*/
	public function edit_account($args){
		extract($args);
		if($check_password != 'yes'){
			$query =  sprintf("UPDATE `$this->users` SET `first_name`='%s',`last_name`='%s',`phone`='%s',`phone_carrier`='%s' WHERE `email`='%s'",
						mysql_real_escape_string($first_name),
						mysql_real_escape_string($last_name),
						mysql_real_escape_string($phone),
						mysql_real_escape_string($phone_carrier),
						mysql_real_escape_string($email)
					);			
		}else{
			$result = $this->check_password($email,$new_password);
			if($result == "same_password"){
				return 'same password';
			}else{
				//update password
				$hashed_password = md5($new_password);
				$query =  sprintf("UPDATE `$this->users` SET `first_name`='%s',`last_name`='%s',`phone`='%s',`phone_carrier`='%s', `password`='$hashed_password' WHERE `email`='%s'",
						mysql_real_escape_string($first_name),
						mysql_real_escape_string($last_name),
						mysql_real_escape_string($phone),
						mysql_real_escape_string($phone_carrier),
						mysql_real_escape_string($email)
					);				
			}
		}
		if($result = mysql_query($query)){
			return 1;
		}else{
			return 0;
		}
	}
	
	/**
	* get_user
	* @param string $email
	*/
	public function get_user($email){
		$query = "SELECT * FROM `$this->users` WHERE `email` = '$email'";
		//echo $query;
		$result = mysql_query($query);
		if($result){
			$user_info = mysql_fetch_array($result);
			return $user_info;
		}else{
			return 0;
		}
	}


	/**
	* get_user_by_id
	* @param int $user_id
	*/
	public function get_user_by_id($user_id){
		$query = "SELECT * FROM `$this->users` WHERE `id` = '$user_id'";
		$result = mysql_query($query);
		if($result){
			$user_info = mysql_fetch_array($result);
			return $user_info;
		}else{
			return 0;
		}
	}
	
	/**
	* check_account_exists
	* @param string $email
	*/
	public function check_account_exists($email){
		$query = "SELECT COUNT(*) AS `count` FROM `$this->users` WHERE `email`='$email'";
		$result = mysql_query($query);
		if($result){
			$record = mysql_fetch_object($result);
			if($record->count == 0){
				return 0;
			}else{
				return 1;
			}
		}else{
			return 'error';
		}
	}
	
	/**
	* check_password
	* @param string $password
	* Checks to see if the given password already exists for the given user
	*/
	private function check_password($email,$password){
		$hashed_password = md5($password);
		$sql = "SELECT `password` FROM `$this->users` WHERE `email`='$email'";
		$result = mysql_query($sql);
		if($result){
			$record = mysql_fetch_array($result);
			if($record['password'] == $hashed_password){
				return 'same password';
			}else{
				return 'different password';
			}
		}
	}	
	
	/**
	* authenticate_user
	* Authenticate user given email address and password
	* Checks that given email address exists in system
	*/
	public function authenticate_user($args){
		$email = addslashes(trim($args['email']));
		$password = addslashes(trim($args['password']));
		$hashed_password = md5($password);
		//Check to see that email address exists
		$account_check = $this->check_account_exists($email);
		if($account_check == 1){
			$query = "SELECT * FROM `$this->users` WHERE `email` = '$email' AND `password` = '$hashed_password'";
			$result = mysql_query($query);
			if($result){
				//echo 'result ' . mysql_num_rows($result); die();
				if(mysql_num_rows($result) == 1){
					return 1;
				}else{
					return 0;
				}
			}
		}else{
			//email address/account does not exist
			return 'email does not exist';
		}
	}
	
	/**
	* reset_password
	*/
	public function reset_password($args){
		extract($args);
		$user_exists = $this->check_account_exists($user_email); //check if user email exists
		if($user_exists){
			$hashed_password = md5($token);
			$sql = "UPDATE `".$this->users."` SET `password`='$hashed_password' WHERE `email`='$user_email'";
			$result = mysql_query($sql);
			if($result){
				return 1;
			}else{
				return 0;
			}
		}else{
			return 'invalid user';
		}
		
	}	

}
?>