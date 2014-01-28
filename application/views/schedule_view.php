<!DOCTYPE html>
<html>
	<head>
		<title>Your iSchedule247 Calendar</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<title>Schedule calendar events to automatically remind your contacts in advance of events and appointments </title>
		<meta http-equiv="description" name="description" content="iSchedule247.com offers a free calendar that's beautifully simple that will automatically send emails and text message reminders to your friends, family, teammates, co-workers, etc.">
		<meta http-equiv="keywords" name="keywords" content="event schedule, event scheduling, automatic reminders">
		<meta name="robots" content="index,follow">
		<meta nameF="revisit-after" content="10 days">
		<meta http-equiv="Content-Style-Type" content="text/css" />
		<link rel="stylesheet" type="text/css" href="../css/styles.css" />
		<link rel="stylesheet" type="text/css" href="../css/ischedule.css" />
		<link type="text/css" rel="stylesheet" href="../css/jquery.miniColors.css" />
		<link type="text/css" href="../css/jquery-ui-1.8.4.custom.css" rel="stylesheet" />
		<link type="text/css" href="../css/jquery-ui-timepicker.css" rel="stylesheet" />
		<link type="text/css" href="../css/fullcalendar.css" rel="stylesheet" />

	</head>
<body>
	<div id="container"> <!--Start Container-->
	<div id="header"> <!--Start Header-->
		<a href="../schedule/"><h1>iSchedule247</h1></a>
		<div id="nav">
			<a href="../about/" title="Well since you really want to know">About Us</a>
			<a href="../faqs/">FAQs</a>
			<a href="###" title="View account settings" Account Settings</a>
			<a href="../logout/" id="logout" title="Logout">Logout</a>		
		</div>
	</div> <!--End Header-->
		
		<div id="content"> <!--Start Content-->
			<div id="left_col"> <!--Start Left Col-->
			<div class="mod">
				<div class="mod_top"></div>
				<h3>Calendars</h3>
				<form id="category_list_form" name="category_list_form">
				<div id="accordion"></div>
					<ul id="category_list">
					</ul>
				</form>					
				<a href="###" class="button" id="add_category" title="Add Calendar">Add Calendar</a>
				<!--<a href="###" class="button" id="manage_categories" title="Manage Calendars">Manage Calendars</a>-->
				<a href="###" class="button" id="add_event" title="Add Event">Add Event</a>
				<a href="###" class="deselect_all" id="manage_events" title="Manage Events" style="color:#fff;">Manage Events</a>
			</div>
			</div> <!--End Left Col-->
			<div id="center_col"> <!--Start Center Col-->
			<div id="calendar_months" style="background-color:#FFFFFF;width:650px;">
				<div id="calendar_top"></div>
					<table border="0">
                                            <tr>
                                                <td>
                                                    <ul>
                                                            <li><a href="###" rel="0">Jan</a></li>
                                                            <li><a href="###" rel="1">Feb</a></li>
                                                            <li><a href="###" rel="2">Mar</a></li>
                                                            <li><a href="###" rel="3">Apr</a></li>
                                                            <li><a href="###" rel="4">May</a></li>
                                                            <li><a href="###" rel="5">Jun</a></li>
                                                            <li><a href="###" rel="6">Jul</a></li>
                                                            <li><a href="###" rel="7">Aug</a></li>
                                                            <li><a href="###" rel="8">Sep</a></li>
                                                            <li><a href="###" rel="9">Oct</a></li>
                                                            <li><a href="###" rel="10">Nov</a></li>
                                                            <li><a href="###" rel="11">Dec</a></li>
                                                    </ul>
                                                </td>
                                            </tr>
					</table>				
				</div>
				<div style="clear:both;height:10px;background-color:#FFFFFF;width:650px;"></div>
				<!-- Calendar placeholder -->
				<div id="mycalendar" style="width:650px;"></div>
			</div> <!--End Center Col-->
			<div id="right_col"> <!--Start Right Col-->
				<div class="mod">
					<div class="mod_top"></div>
						<h3>Contacts</h3>
						<div id="contacts">
                                                    <form id="contact_list_form" name="contact_list_form">
                                                        <ul id="contacts_list"></ul>
                                                    </form>
						</div>
					<!--<a href="###" class="button" id="add_contact" title="Add Contact">Add Contact</a>
					<a href="###" class="button" id="bulk_add" title="Add Multiple Contacts">Add Multiple Contacts</a>-->
					<a href="###" class="deselect_all" id="add_contact" id="add_contact" title="Add Contact" style="color:#fff;">Add Contact</a>					
				<div class="clear"></div>
				</div>		
			</div> <!--End Right Col-->
		<div class="clear"></div>
		</div> <!--End Content-->
		<?php include('../includes/footer.php'); ?>
	</div> <!--End Container-->
	<div id="modal_container" title="general modal" class="modal"></div>
	
	<div class="alert_modal">
		<p></p>
		<a href="###" class="btn" id="yes">Yes</a>&nbsp;&nbsp;<a href="###" class="btn" id="no">No</a>
		<input type="hidden" name="obj_id" id="obj_id" value="">
	</div>
        
<script type="text/javascript" src="../js/jquery_142.js"></script>
<script type="text/javascript" src="../js/jquery-ui-1.8.10.custom.min.js"></script>
<script type="text/javascript" src="../js/validate.js"></script>
<script type="text/javascript" src="../js/schedule.js"></script>
<script type="text/javascript" src="../js/fullcalendar.js"></script>
<script type="text/javascript" src="../js/jquery.ui.datepicker.js"></script>
<script type="text/javascript" src="../js/jquery.ui.timepicker-0.0.6.js"></script>
<script type="text/javascript" src="../js/jquery.jqEasyCharCounter.min.js"></script>
<script type="text/javascript" src="../js/init_calendar.js"></script>
<script type="text/javascript" src="../js/jquery.miniColors.js"></script>        
</body>
</html>