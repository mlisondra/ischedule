<?php
ini_set('display_errors', 1);
error_reporting(E_ERROR);
include('../config/production.php');
include('../classes/events.class.php');
include('../classes/categories.class.php');

$events_obj = new Events();
$categories_obj = new Categories();

$args['user'] = $_SESSION['user']['logged_in_id'];
$args['start'] = $_GET['start'];
$args['end'] = $_GET['end']; 
$events = $events_obj->get_user_events_date_range($args);

$other_calendars = $categories_obj->get_managing_calendars($_SESSION['user']['logged_in_id']); //get calendars that user is an administrator for
if($other_calendars != 0){
	foreach($other_calendars as $calendar){
		$calendars[] = $calendar['id'];
	}
	$args['calendars'] = $calendars;
	$events_user_manages = $events_obj->get_events_date_range_for_manager($args);	
}

$event_data = "";
if($events != 0){
	foreach($events as $event){
		$event_data[] = array("id"=>$event['id'],"title"=>$event['title'],"start"=>$event['begin_date_time'],"end"=>$event['end_date_time'],"color"=>$event['color'],"className"=>$event['category'],"category_name"=>$event['category_name']);
	}
	if($events_user_manages != 0){
		foreach($events_user_manages as $event){ //add events that user manages to the json data
			$event_data[] = array("id"=>$event['id'],"title"=>$event['title'],"start"=>$event['begin_date_time'],"end"=>$event['end_date_time'],"color"=>$event['color'],"className"=>$event['category'],"category_name"=>$event['category_name']);
		}	
	}
	print json_encode($event_data);
}else{
	print json_encode($event_data);
}
?>