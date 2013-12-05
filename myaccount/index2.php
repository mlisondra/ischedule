<?php include('../config/production.php'); ?>
<!DOCTYPE html>
<html>
	<head>
		<title>Your iSchedule247 Calendar</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<meta http-equiv="description" name="description" content="iSchedule247.com offers a free calendar that's beautifully simple that will automatically send emails and text message reminders to your friends, family, teammates, co-workers, etc.">
		<meta http-equiv="keywords" name="keywords" content="event schedule, event scheduling, automatic reminders">
		<meta name="robots" content="index,follow">
		<meta name="revisit-after" content="10 days">
		<meta http-equiv="Content-Style-Type" content="text/css" />
		<?php include("../includes/js_css.php") ;?>
		<link type="text/css" href="../css/fullcalendar.css" rel="stylesheet" />
		<link type="text/css" href="../css/jquery-ui-1.8.4.custom.css" rel="stylesheet" />
		<script type="text/javascript" src="../js/schedule.js"></script>
		<script type="text/javascript" src="../js/fullcalendar.js"></script>
		
		<script type="text/javascript">

$(document).ready(function() {
  get_contacts();
  get_categories();
 });
	</script>
	</head>
<body>
	<div id="container"> <!--Start Container-->
		<?php include('../includes/header.php'); ?>
		<div id="content"> <!--Start Content-->
			<div id="left_col"> <!--Start Left Col-->
				<div class="mod">
					<div class="mod_top"></div>
					<h3>Categories</h3>
					<form id="category_list_form" name="category_list_form">
					<ul id="category_list"></ul>
					</form>
					<!--
					<div style="clear:both;padding-top:15px;"></div>
					<a href="###" id="select_all" onclick="select_all_categories();" style="padding-left:10px">Select All</a><br/>
					<a href="###" id="select_all" onclick="deselect_all_categories();" style="padding-left:10px;">Deselect All</a><br/>//-->
					
					<a href="##" class="button" title="Add Category" onclick="show_modal('add_category');">Add Category</a>
					<a href="##" id="deselect_all">Delete Selected</a>
					<!--<a href="##" id="group" onclick="show_modal('add_category');" title="Add Category"></a>
					<a href="##" id="add_contact" onclick="show_modal('add_contact');"></a>
					<a href="##" id="email_icon" onclick="alert('Send Message to Selected Contact above');"></a>//-->
				</div>
			</div> <!--End Left Col-->
			<div id="center_col"> <!--Start Center Col-->
				<div id="" style="background-color:#FFFFFF;width:650px;"><div id="calendar_top"></div>
					<h3>My Account</h3>
				</div>
				<div style="clear:both;height:10px;background-color:#FFFFFF;width:650px;"></div>
				<div id="calendar_alt" style="width:650px;"></div>
				<div id="calendar" style="display:none;">
					<div id="calendar_top"></div>
						
						<div class="clear"></div>
						
					<div id="calendar_bottom"></div>
				</div>
			</div> <!--End Center Col-->
			<div id="right_col"> <!--Start Right Col-->
				<div class="mod">
					<div class="mod_top"></div>
						<h3>Contacts</h3>
						<div id="contacts">
							<form id="contact_list_form" name="contact_list_form">
							<ul id="contacts_list"></ul>
							</form>
							<!--<div style="clear:both;padding-top:15px;padding-left:10px;">
							<a href="###" title="Delete selected contacts" onclick="show_modal('delete_selected_contacts')">Delete Selected</a>
							</div>//-->
						</div>
					<a href="##" class="button" title="Add Contact" onclick="show_modal('add_contact');">Add Contact</a>
					<a href="##" id="deselect_all" onclick="show_modal('delete_selected_contacts')" title="Delete Selected">Delete Selected</a>
					<!--<a href="##" id="deselect_all">Deselect All</a>//-->
					<!--
					<a href="##" id="group" onclick="assign_contacts_to_category();" title="Assign Selected Contacts to Category"></a>
					<a href="##" id="add_user" onclick="show_modal('add_contact');" title="Add new contact"></a>
					<a href="##" id="email_icon" onclick="alert('Send Message to Selected Contact above give option to choose email, text, or both');"></a>
					//-->
				<div class="clear"></div>
				</div>
				<div class="mod" style="display:none;">
					<div class="mod_top"></div>
						<h3>Manual Message Launch</h3>
						<div id="message">
							<form name="" id="">
							<ul id="message_category_list">
								<li class="blue"><input type="checkbox" name="category[]" id="Storm Soccer" value="1">Storm Soccer</li>
								<li><input type="checkbox" name="category[]" id="Family" value="1">Family</li>
								<li><input type="checkbox">School Carpool</li>
								<li><input type="checkbox">Rocket Baseball</li>
								<li><input type="checkbox">Snacks</li>
								<li><input type="checkbox">Family</li>
								<li><input type="checkbox">School Carpool</li>
								<li><input type="checkbox">Rocket Baseball</li>
								<li><input type="checkbox">Snacks</li>
							</ul>
							</form>
						</div>
					<a href="##" id="group"></a>
					<a href="##" id="add_user" onclick="alert('Add new Group');"></a>
					<a href="##" id="email_icon" onclick="alert('Send message to selected group');"></a>
				</div>
			</div> <!--End Right Col-->
		<div class="clear"></div>
		</div> <!--End Content-->
		<?php include('../includes/footer_alt.php'); ?>
	</div> <!--End Container-->
	
	<div id="dialog-modal" title="" style="font-size:12px;"></div>
	
	<div id="assign_contacts_to_category" title="assign_contacts_to_category" class="modal">
	<form>
	List of selected contacts<br/><br>
	List Available Categories
	</form>
	</div>
	
	<div id="modal_container" title="general modal" class="modal"></div>	
	
</body>
</html>