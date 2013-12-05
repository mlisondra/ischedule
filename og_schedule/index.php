<?php include('../config/production.php'); ?>
<?php
if($_SESSION['user']['logged_in'] != 'yes'){
	header("Location: /login/");
}
?>
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
		<?php include("../includes/js_css.php") ;?>
		<link type="text/css" href="../css/fullcalendar.css" rel="stylesheet" />
		<script type="text/javascript" src="../js/fullcalendar.js"></script>
		<script type="text/javascript" src="../js/jquery.ui.datepicker.js"></script>
		<script type="text/javascript" src="../js/jquery.ui.timepicker-0.0.6.js"></script>
		<script type="text/javascript" src="../js/jquery.jqEasyCharCounter.min.js"></script>
		<script type="text/javascript" src="../js/init_calendar.js"></script>
		<script type="text/javascript" src="../js/jquery.miniColors.js"></script>

	</head>
<body>
	<div id="container"> <!--Start Container-->
		<?php include('../includes/header.php'); ?>
		
		<div id="content"> <!--Start Content-->
			<div id="left_col"> <!--Start Left Col-->
				<?php 
					$left_column = file_get_contents("../templates/left_column.tpl");
					print $left_column;
				?>
			</div> <!--End Left Col-->
			<div id="center_col"> <!--Start Center Col-->
				<?php
					$center_column = file_get_contents("../templates/center_column.tpl");
					print $center_column;
				?>
			</div> <!--End Center Col-->
			<div id="right_col"> <!--Start Right Col-->
				<?php 
					$right_column = file_get_contents("../templates/right_column.tpl");
					print $right_column;
				?>			
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
</body>
</html>