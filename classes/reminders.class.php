<?php
class Reminders{

	public function get_users_by_category($cat){
		$sql = "SELECT `users`.* FROM `user_contacts` `users` LEFT JOIN `user_contacts_categories` t2 ON `users`.`id` = t2.contact WHERE t2.`category`='".$cat."'";
		//echo $sql.'<br>';
		$result = mysql_query($sql);
		if($result){
			if(mysql_num_rows($result) > 0){
				while($rec = mysql_fetch_array($result)){
					$results[] = $rec;
				}
				return $results;
			}
		}
	}
	
	public function get_events_by_list($event_ids){
		$event_ids_list = "'".implode("','",$event_ids)."'";
		$sql = "SELECT * FROM `user_events` WHERE `id` IN (".$event_ids_list.")";
		$result = mysql_query($sql);
		if($result){
			if(mysql_num_rows($result) > 0){
				while($rec = mysql_fetch_array($result)){
					$results[] = $rec;
				}
				return $results;
			}
		}		
	}
}
?>