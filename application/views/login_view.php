<?php //include('../config/production.php'); ?>
<?php //if($_SERVER['HTTPS'] == "off"){ header("Location: https://www.ischedule247.com"); } ?>
<html> 
<head> 
<title>A secure on-line scheduler to send email and text reminders of events and appointments to anyone! - iSchedule247</title>
		<meta http-equiv="description" name="description" content="iSchedule247.com offers a free calendar that's beautifully simple to use to automatically send emails and text message reminders to anyone">
		<meta http-equiv="keywords" name="keywords" content="event schedule, event scheduling, automatic reminders">
		<meta name="revisit-after" content="10 days">
<link rel="stylesheet" type="text/css" href="../css/styles_alt.css" />
<link rel="stylesheet" type="text/css" href="../css/ischedule.css" />
<link type="text/css" href="../css/jquery-ui-1.8.4.custom.css" rel="stylesheet" />
<script type="text/javascript" src="../js/jquery-142.js"></script>
<script type="text/javascript" src="../js/jquery-ui-1.8.10.custom.min.js"></script>
<script type="text/javascript" src="../js/validate.js"></script>
</head>
<body>
	<div id="container"> <!--Start Container-->
	<div id="header"> <!--Start Header-->
		<a href="../schedule/"><h1>iSchedule247</h1></a>
		<div id="nav">
			<a href="../about/" title="Well since you really want to know">About Us</a>
			<a href="../faqs/">FAQs</a>
                        <a href="###" title="View account settings">Account Settings</a>
			<a href="../logout/" id="logout" title="Logout">Logout</a>		
		</div>
	</div> <!--End Header-->
		<!--End Header-->
		<div id="login_content"> <!--Start Content-->
			<div id="login_left">
				<h2 id="pagetitle">Login</h2>
				<div id="notification"></div>
				<div id="form_content">
				<form name="login_form" id="login_form" method="post">
					<label>Email</label><br /><div class="sign_up_error" id="email_error"></div>
					<input type="text" name="user_email" id="user_email"><br />
					<label>Password</label><br />
					<input type="password" name="password" id="password"><br /><div class="sign_up_error" id="password_error"></div>
					Forgot your password? <a href="###" style="color:#fff;" id="reset_password_link">Click here</a><br/>
					<input type="hidden" name="action" value="auth_user">
					<input type="submit" value="Login" id="submit">
				</form>
				
				<form name="reset_password_form" id="reset_password_form" method="post" style="display:none;">
					<label>Email</label><br /><div class="sign_up_error" id="email_error"></div>
					<input type="text" name="user_email" id="user_email"><br />
					<input type="submit" value="Reset My Password">&nbsp;
					<input id="cancel_reset" value="Cancel" type="button" onclick="cancel_reset_password()">
				</form>
				
				</div>
			</div>
			<div id="login_right">
				<a href="../signup/" id="sign_up_button" title="Click here to signup for a free account"></a>
			</div>
		<div class="clear"></div>
		<div id="login1">
			<h3>iSchedule247 is...</h3>
			<p>A really simple and focused way to schedule out automatic email and text/SMS reminders to anyone; a week or a day or an hour before an event or appointment.</p>
			<p>It's a super simple way to "set it and forget it", so to speak. It works great for <em> busy moms and coaches to schools and businesses.</em> All your contacts can be reminded of their appointments or responsibiliites. And they don't have to be subscribers or pay anything!</p>
			<p> Try it for <a href="../signup"><strong>FREE</strong></a> indefinitely! There's no software to download - you just login and when you do <a href= "../upgrade"><strong>upgrade</strong> </a>, you'll see how cool the calendar starts to look when it fills up with color...or how insanely busy you are :)</p>
			<p> View the video to the right to see a quick, easy demo of the full version. </p>
		</div>
		<div id="login2">
			<iframe width="420" height="345" src="https://www.youtube.com/embed/0k8MB_8t96I" frameborder="0" allowfullscreen></iframe>
		</div>
		<div class="clear"></div>
		</div> <!--End Content-->
		<?php //include('../includes/footer_alt.php'); ?>
		<div id="modal_container" title="general modal" class="modal"></div>
		<div id="static_modal_container" title="general modal" class="modal"></div>	
	</div> <!--End Container-->
</body>
</html>