<?php
class Events{
	var $events = 'user_events';
	var $events_categories = 'user_events_categories';
	var $cdt = '';
	var $reminders = 'event_reminders';
	
	/**
	* get_event
	* @param int $id
	* @return array $record
	*/
	public function get_event($id){
		$sql = "SELECT * FROM `".$this->events."` WHERE `id`='".$id."'";
		$result = mysql_query($sql);
		if($result){
			if(mysql_num_rows($result) == 1){
				$record = mysql_fetch_array($result);
				return $record;
			}else{
				return 0;
			}
		}else{
			return 0;
		}
	}
	
	/**
	* get_user_events
	* @param int $user_id
	* @param int $limit default 0;
	* Retrieve user events with given limit $limit
	*/
	public function get_user_events($user_id,$limit = 0){
		if($limit != 0){
			$sql = "SELECT * FROM `".$this->events."` WHERE `user`='".$user_id."' LIMIT " . $limit;
		}else{
			$sql = "SELECT * FROM `".$this->events."` WHERE `user`='".$user_id."'";
		}
		
		$result = mysql_query($sql);
		if($result){
			if(mysql_num_rows($result) > 0){
				while($row = mysql_fetch_array($result)){
					$results[] = $row;
				}
				return $results;
			}else{
				return 0;
			}
		}
	}
	

	
	/**
	* get_user_events_date_range
	* @param array $args: int user, int $start, int $end
	* @return mixed array $results on Success; 0 on Failure or 0 records
	* Retrieves events for given user id within the datetime range of $start and $end
	*/
	public function get_user_events_date_range($args){
		extract($args);
		$sql = "SELECT `events`.*,`cat`.`color`,`cat`.`name` AS 'category_name' FROM `".$this->events."` `events` 
				LEFT JOIN `user_categories` `cat`
				ON `events`.category = `cat`.id				
				WHERE `events`.`user`='$user' AND `events`.`begin_date_time`>='$start' AND `end_date_time`<='$end'";

		$result = mysql_query($sql);
		if($result){
			if(mysql_num_rows($result) > 0){
				while($record = mysql_fetch_array($result)){
					$results[] = $record;
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
	* get_events_date_range_for_manager
	* Retrieves events associated to give Calendar id
	* @return array $results
	*/
	public function get_events_date_range_for_manager($args){
		extract($args);
		$calendar_ids = "'".implode("','",$calendars)."'";
		$sql = "SELECT `events`.*,`cat`.`color`,`cat`.`name` AS 'category_name' FROM `".$this->events."` `events` 
				LEFT JOIN `user_categories` `cat`
				ON `events`.category = `cat`.id				
				WHERE `cat`.`id`  IN (".$calendar_ids.") AND `events`.`begin_date_time`>='$start' AND `end_date_time`<='$end'";
		//echo $sql;
		$result = mysql_query($sql);
		if($result){
			if(mysql_num_rows($result) > 0){
				while($record = mysql_fetch_array($result)){
					$results[] = $record;
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
	* get_user_future_events
	* Get all the events in the future for given user args['user']
	*/
	public function get_user_future_events($user){
		$this->cdt = mktime();
		$sql = "SELECT COUNT(*) AS 'total_future_events' FROM `".$this->events."` WHERE `begin_date_time` >= '".$this->cdt."' AND`user`='".$user."'";
		
		$result = mysql_query($sql);
		if($result){
			$results = mysql_fetch_array($result);
			return $results['total_future_events'];
		}else{
			return -1;
		}
	}
	
	/**
	* add_event
	* @param array $args
	*
	*/
	public function add_event($args){
		extract($args);
		$this->cdt = mktime();
		
		//change date into correct mysql datetime format		
		//build begin date time
		$month = substr($begin_date,0,2);
		$day = substr($begin_date,3,2);
		$year = substr($begin_date,6,4);
		$begin_time_array = explode(":",$begin_time);
		$begin_hour = $begin_time_array[0];
		if(substr($begin_time,-2) == 'PM' && $begin_hour < 12){
			$begin_hour = $begin_hour + 12;
		}
		if(substr($end_time,-2) == 'AM' && $begin_hour == 12){
			$begin_hour = 24;
		}	
		//echo $begin_hour;
		$begin_minute = substr($begin_time_array[1],0,2);
		if($begin_minute < 10 && $begin_minute != "00"){
			$begin_minute = substr($begin_minute,1,1);
		}
		$event_begin_date_time = mktime($begin_hour,$begin_minute,0,$month,$day,$year);
		
		//build end_date_time
		$month = substr($end_date,0,2);
		$day = substr($end_date,3,2);
		$year = substr($end_date,6,4);
		$end_time_array = explode(":",$end_time);
		$end_hour = $end_time_array[0];
		if(substr($end_time,-2) == 'PM' && $end_hour < 12){
			$end_hour = $end_hour + 12;
		}
		if(substr($end_time,-2) == 'AM' && $end_hour == 12){
			$end_hour = 24;
		}
		$end_minute = substr($end_time_array[1],0,2);
		if($end_minute < 10){
			$end_minute = substr($end_minute,1,1);
		}		
		$event_end_date_time = mktime($end_hour,$end_minute,0,$month,$day,$year);
		
		//build sql
		$sql = sprintf("INSERT INTO `$this->events` (`title`,`description`,`begin_date_time`,`end_date_time`,`user`,`created`,`modified`,`reminder_type`,`category`) 
			VALUES ('%s','%s','$event_begin_date_time','$event_end_date_time','$user','$this->cdt','$this->cdt','$reminder_type','$category')",
			mysql_real_escape_string($title),
			mysql_real_escape_string($description)
			);

		if(mysql_query($sql)){
			$event_id = mysql_insert_id();
			$args['id'] = $event_id;
			$args['reminder_notification'] = $args['reminder_notification'];
			$args['event_begin_date_time'] = $event_begin_date_time;
			$this->add_reminder_send_time($args); //add reminder send times for event
			return $event_id;
		}else{
			return 0;
		}		
	}
	
	/**
	* edit_event
	* @param array $args
	*/
	public function edit_event($args){
		extract($args);
		$this->cdt = mktime();
		//change date into correct mysql datetime format
		
		//build begin date time
		$month = substr($begin_date,0,2);
		$day = substr($begin_date,3,2);
		$year = substr($begin_date,6,4);
		$begin_time_array = explode(":",$begin_time);
		$begin_hour = $begin_time_array[0];

		if(substr($begin_time,-2) == 'PM' && $begin_hour < 12){
			$begin_hour = $begin_hour + 12;
		}
		if(substr($begin_time,-2) == 'AM' && $begin_hour == 12){
			$begin_hour = 00;
		}		

		$begin_minute = $begin_time_array[1];
		$begin_date_time = mktime($begin_hour,$begin_minute,0,$month,$day,$year);
		
		//build end_date_time
		$month = substr($end_date,0,2);
		$day = substr($end_date,3,2);
		$year = substr($end_date,6,4);
		$end_time_array = explode(":",$end_time);
		$end_hour = $end_time_array[0];
		if(substr($end_time,-2) == 'PM' && $end_hour < 12){
			$end_hour = $end_hour + 12;
		}
		if(substr($end_time,-2) == 'AM' && $end_hour == 12){
			$end_hour = 00;
		}

		$end_minute = $end_time_array[1];
		$end_date_time = mktime($end_hour,$end_minute,0,$month,$day,$year);		
		
		$sql = "UPDATE `".$this->events."`
			SET `title` = '$title',
			`description` = '$description',
			`modified` = '$this->cdt',
			`category` = '$category',
			`begin_date_time` = '$begin_date_time',
			`end_date_time` = '$end_date_time'
			WHERE `id` = '$id'
		";
//echo $sql;
		$result = mysql_query($sql);
		if(mysql_affected_rows() == 1){
			$args['id'] = $id;
			$args['reminder_notification'] = $args['reminder_notification'];
			$args['event_begin_date_time'] = $begin_date_time;
			$this->add_reminder_send_time($args); //add reminder send times for event
			return 1;
		}else{
			return 0;
		}
	}
	
	/**
	* delete_user_event
	* Deletes the events for the given user id
	* Deleted events are also deleted from the categories-events relational table
	* @param array $args (int $user_id; array $events)
	*/
	public function delete_user_event($args){
		extract($args);
		if($user_id != ''){
			$list_of_events = "'" . implode("','",$args['events']) . "'";
			$sql = "DELETE FROM ".$this->events. "  WHERE `user`='".$user_id."' AND `id` IN (".$list_of_events.")";
			if($result = mysql_query($sql)){
				if(mysql_affected_rows() > 0){
					$sql = "DELETE FROM ".$this->events_categories. "  WHERE `event_id` IN (".$list_of_events.")";
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
	 * Deletes all events associated with given calendar id
	 */
	public function delete_events_by_calendar_id($calendar_id){
		$sql = "DELETE FROM `".$this->events."` WHERE `category` = '".$calendar_id."'";
		//echo $sql;
		$result = mysql_query($sql);
		if($result){
			return 1;	
		}else{
			return 0;
		}
	}
	
	/**
	*
	*/
	private function add_event_category($args){
		extract($args);
		
		foreach($categories as $category){
			$sql = "INSERT INTO `".$this->events_categories."` (`event_id`,`category`) VALUES ('$event_id','$category')";
			mysql_query($sql);
		}
	}
	
	/**
	* get_event_reminders
	*/
	public function get_event_reminders($id){
		$sql = "SELECT * FROM `".$this->reminders."` WHERE `event`='$id'";
		$result = mysql_query($sql);
		if($result){
			while($rec = mysql_fetch_array($result)){
				$reminders[] = $rec['reminder_notification'];
			}
			return $reminders;
		}else{
			return 0;
		}
	}
	
	/**
	* delete_event_reminders
	*/
	private function delete_event_reminders($id){
		$sql = "DELETE FROM `".$this->reminders."` WHERE `event`='$id'";
		mysql_query($sql);		
	}
	
	/**
	* add_reminder_send_time
	* Add a reminder time (ie: 1hourbefore,1daybefore,1weekbefore) for given event id
	* @param array $args (int => id, array => list of reminder times, int => $event_begin_date_time)
	* any existing reminders are deleted BEFORE the current set is created
	*/
	private function add_reminder_send_time($args){
		extract($args);
		if(count($reminder_notification) > 0){
			$this->delete_event_reminders($id);
			foreach($reminder_notification as $key=>$value){			
				switch($value){
					case "1hourbefore":
						$reminder_send_time = $event_begin_date_time - 3600;
						break;
					case "1daybefore":
						$reminder_send_time = $event_begin_date_time - 86400;
						break;
					case "1weekbefore":
						$reminder_send_time = $event_begin_date_time - 604800;
						break;
				}
				$sql = "INSERT INTO `".$this->reminders."` (`event`,`reminder_notification`,`reminder_send_time`) VALUES ('$id','$value','$reminder_send_time')";
				
				mysql_query($sql);
			}
		}		
	}

}
?>