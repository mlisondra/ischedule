<?php include('config/production.php'); ?>
<?php
if($_SERVER['HTTP_HOST'] == 'ischedule.localhost'){
	header("Location: /login/");
}else{
	header("Location: http://www.ischedule247.com/login/");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html> 
<head> 
<title>iSchedule247</title>
<?php include('includes/js_css.php'); ?>
</head>
<body>
	<div id="container"> <!--Start Container-->
		<div id="header"> <!--Start Header-->
			<h1 id="welcome_screen">iSchedule247.com :: Login</h1>
		</div> <!--End Header-->
		<div id="content"> <!--Start Content-->
			<div id="left_col" style="background-color:#626262;"> <!--Start Left Col-->
				<div style="background-color:#626262;">
					<div class="mod_top" style="background-color:#626262;display:none;"></div>
					<div style="clear:both;padding-top:15px;"></div>
				</div>
			</div> <!--End Left Col-->
			<div id="center_col"> <!--Start Center Col-->
				<div id="calendar">
					<div id="calendar_top"></div>
						<div style="padding-left:20px;padding-top:20px;">
						<div id="notification"></div>
						<form name="login_form" id="login_form" method="post">
						<table style="width:300px;font-size:12px;">

						<tr style="height:40px;"><td align="left">Email</td><td align="left">&nbsp;<input type="text" id="user_email" name="user_email" value="" style="width:250px;">
							<div class="sign_up_error" id="email_error"></div>
						</td></tr>
						<tr style="height:40px;"><td align="left">Password</td><td align="left">&nbsp;<input type="password" id="password" name="password" value="" style="width:250px;">
							<div class="sign_up_error" id="password_error"></div>
						</td></tr>
						<tr style="height:40px;"><td></td><td align="left">&nbsp;<input type="submit" id="submit_btn" name="submit_btn" value="Log In" style="width:150px;">
						<br/><span style="font-size:10pt;">Problems logging in? <a href="###">Click here.</a></span>
						</td></tr>
						</table>
						<input type="hidden" name="action" value="auth_user">
						</form>
						</div>
					<div id="calendar_bottom"></div>
				</div>
			</div> <!--End Center Col-->
			<div id="right_col"> <!--Start Right Col-->
				<div class="mod" style="display:none;">
					<div class="mod_top"></div>
						<h3>Contacts</h3>
						<div id="contacts">
							<form id="contact_list_form" name="contact_list_form">
							<ul id="contacts_list"></ul>
							</form>
						</div>
					<a href="##" id="group" onclick="assign_contacts_to_category();" title="Assign Selected Contacts to Category"></a>
					<a href="##" id="add_user" onclick="add_contact();"></a>
					<a href="##" id="email" onclick="alert('Send Message to Selected Contact above give option to choose email, text, or both');"></a>
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
					<a href="##" id="email" onclick="alert('Send message to selected group');"></a>
				</div>
			</div> <!--End Right Col-->
		<div class="clear"></div>
		</div> <!--End Content-->
		<?php include('includes/footer_alt.php'); ?>
	</div> <!--End Container-->
	<div id="dialog-modal" title="" style="font-size:12px;"></div>
		
</body>