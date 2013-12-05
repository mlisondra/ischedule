<?php
class Categories{
	var $categories = 'user_categories';
	var $calendar_managers = "calendar_managers";
	
	/**
	* add_category
	* @param array $args
	*
	*/
	public function add_category($args){
		extract($args);
		$sql_query = sprintf("INSERT INTO `$this->categories` (`name`,`description`,`user`,`color`) 
			VALUES ('%s','%s','%d','%s')",
			mysql_real_escape_string($name),
			mysql_real_escape_string($description),
			$user,
			mysql_real_escape_string($color)
			);
		if(mysql_query($sql_query)){
			return mysql_insert_id();
		}else{
			return 0;
		}		
	}
	
	/**
	* edit_category
	* @param int $id
	*/
	public function edit_category($args){
		extract($args);
		$sql_query = sprintf("UPDATE `$this->categories` SET `name`='%s',`color`='%s',`description`='%s' WHERE `id`='%d'",
			mysql_real_escape_string($name),
			mysql_real_escape_string($color),
			mysql_real_escape_string($description),
			$id
		);
		$result = mysql_query($sql_query);
		if($result){
			print 1;
		}else{
			print 0;
		}
	}
	
	/**
	* get_user_categories
	* @param int $user_id
	*/
	public function get_user_categories($user_id){
		$sql_query = "SELECT * FROM `$this->categories` WHERE `user`='$user_id'";
		if($result = mysql_query($sql_query)){
			while($row = mysql_fetch_array($result)){
				$results[] = $row;
			}
			return $results;
		}else{
			return 0;
		}
	}
	
	/**
	* get_category_by_id
	* @param int $id
	*/
	public function get_category_by_id($id){
		$sql_query = "SELECT * FROM `$this->categories` WHERE `id`='$id'";
		$result = mysql_query($sql_query);
		if($result){
			$record = mysql_fetch_array($result);
			return $record;
		}else{
			return 0;
		}
	}
	
	/**
	* delete_user_category
	* Delete all categories for given user id
	* @param array $args (int $user_id; array $category)
	*/
	public function delete_user_category($args){
		extract($args);
		if($user_id != ''){
			$list_of_categories = "'" . implode("','",$args['categories']) . "'";
			$sql = "DELETE FROM ".$this->categories. "  WHERE `user`=".$user_id." AND `id` IN (".$list_of_categories.")";
			
			if($result = mysql_query($sql)){
				if(mysql_affected_rows() > 0){
					//delete all associated events
					$sql = "DELETE FROM `user_events` WHERE `category` IN (".$list_of_categories.")";
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
	
	/**
	 * Delete category with given id
	 */
	public function delete_category_by_id($category_id){
		$sql = "DELETE FROM `".$this->categories."` WHERE `id`='".$category_id."'";
		$result = mysql_query($sql);
		if($result){
			if(mysql_affected_rows() == 1){
				return 1;
			}else{
				return 0;
			}
		}else{
			return 0;
		}
	}
	
	
	
	/**
	* add_calendar_manager
	*/
	public function add_calendar_manager($args){
		extract($args);
		$sql = "INSERT INTO `".$this->calendar_managers ."` (`calendar_id`,`manager_id`) VALUES ('$calendar','$manager')";
		//echo $sql; die();
		mysql_query($sql);
	}
	
	/**
	* remove_calendar_manager
	*/
	public function remove_calendar_manager($args){
		extract($args);
		$sql = "DELETE FROM `".$this->calendar_managers."` WHERE `manager_id`='".$manager_id."' AND `calendar_id`='".$calendar_id."'";
		$result = mysql_query($sql);
		if($result){
			return 1;
		}else{
			return 0;
		}
	}
	
	/**
	* get_calendar_managers
	*/
	public function get_calendar_managers($calendar_id){
		$sql = "SELECT * FROM `".$this->calendar_managers."` WHERE `calendar_id`='".$calendar_id."'";
		$result = mysql_query($sql);
		if($result){
			if(mysql_num_rows($result) > 0){
				while($rec = mysql_fetch_array($result)){
					$results[] = $rec;
				}
				return $results;
			}else{
				return 0;
			}
		}else{
			return 0;
		}
	}
	
	/**
	* get_managing_calendars
	* Get calendars, if any, for given user id
	* @param int $user_id
	* @return array $calendars (array of calendar ids)
	*/
	public function get_managing_calendars($user_id){
		$sql = "SELECT * FROM `".$this->calendar_managers."` WHERE `manager_id`='".$user_id."'";
		//echo $sql;
		$result = mysql_query($sql);
		if($result){
			if(mysql_num_rows($result) > 0){
				while($rec = mysql_fetch_array($result)){
					$calendars[] = $this->get_category_by_id($rec['calendar_id']);
				}
				return $calendars;
			}else{
				return 0;
			}
		}else{
			return 0;
		}
	}

}
?>