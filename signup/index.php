<?php include("../includes/db.php"); ?>
<!DOCTYPE html>
<html>
	<head>
		<title>Signup for a forever-free version of iSchedule247</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<meta http-equiv="description" name="description" content="">
		<meta http-equiv="keywords" name="keywords" content="">
		<meta name="robots" content="index,follow">
		<meta name="revisit-after" content="10 days">
		<meta http-equiv="Content-Style-Type" content="text/css" />
		<?php include("../includes/js_css.php") ;?>
		<script type="text/javascript">

		$(document).ready(function() {
			$('form#signup_form').submit(
				function(form){
					form.preventDefault();
					
					//Clear previous error messages
					$('#signup_form').children("div .sign_up_error").css("display", "none");
					var first_name = $("#first_name").val();
					var last_name = $("#last_name").val();					
					var user_email = $("#user_email").val();
					var password = $("#password").val();
					var confirm_password = $("#confirm_password").val();
					var num_errors = 0;
					
					var min_password_length = /^\w{5,}$/;
					
					
					var email_domain_index = user_email.indexOf("@");
					var email_domain_name = user_email.slice(email_domain_index + 1,user_email.length);
						
					if(first_name == ""){
						$("#first_name_error").html("First Name is required");
						$("#first_name_error").show();
						num_errors++;				
					}else{
						$("#first_name_error").hide();
					}
					
					if(last_name == ""){
						$("#last_name_error").html("Last Name is required");
						$("#last_name_error").show();
						num_errors++;			
					}else{
						$("#last_name_error").hide();
					}
					
					if(user_email == ""){
						$("#email_error").html("Email is required");
						$("#email_error").show();
						num_errors++;				
					}else if(!(IsEmail(user_email))){ //check to make sure that email is valid
						$("#email_error").html("Invalid Email format");
						$("#email_error").show();
						num_errors++;
					}else if(email_domain_name == "googlegroups.com" || email_domain_name == "yahoogroups.com"){
						$("#email_error").html("Google or Yahoo Groups emails are not allowed");
						$("#email_error").show();
						num_errors++;				
					}else{
						$("#email_error").hide();
					}					

					if(password == ''){
						$("#password_error").html("Password is required");
						$('#password_error').show();
						num_errors++;
					}else if(password.search(min_password_length)==-1){
						$("#password_error").html("Password must be at least 5 characters long. <br>Only numbers, letters and the underscore (_) is allowed");
						$('#password_error').show();
						num_errors++;	
					}else{
						var password1 = true;
						$('#password_error').hide();
					}	

					if(confirm_password == ''){
						$("#confirm_password_error").html("Confirm your Password");
						$('#confirm_password_error').show();
						num_errors++;
					}else if(confirm_password.search(min_password_length)==-1){
						$("#confirm_password_error").html("Password must be at least 5 characters long. <br>Only numbers, letters and the underscore (_) is allowed");
						$('#confirm_password_error').show();
						num_errors++;
						
					}else{
						var password2 = true;
						$('#confirm_password_error').hide();
					}

					if(password != confirm_password){
							$("#password_error").html("Password do not match");
							$('#password_error').show();
							
							$("#confirm_password_error").html("Password do not match");
							$('#confirm_password_error').show();
							num_errors++;
					}
					
					if(num_errors == 0){
						var post = $('#signup_form').serialize();
						$.post('../ajax/',post,
							function(data){
								if(data == "email exists"){
									$("#email_error").html('There is already an account with the email address you provided.');
									$("#email_error").show();
								}else if(data == "success"){
									$("#signup_form").remove();
									$("#notification").html('You have successfully registered for an iSchedule247 account.<br>Click here to <a href="../login/">login</a>');
									$("#notification").show();
								}
							}
						);	
					}

			
				}
			);  	
		});

		</script>
	</head>
<body>
	<div id="container"> <!--Start Container-->
		<div id="header"> <!--Start Header-->
			<h1 id="welcome_screen"><a href= "../login">iSchedule247.com</a> : Free Account Sign Up</h1>
		</div> <!--End Header-->
		<div id="content"> <!--Start Content-->
			<div id="left_col"> <!--Start Left Col-->
				<div>
					<div class="mod_top" style="background-color:#626262;display:none;"></div>
					<div style="clear:both;padding-top:15px;"></div>
				</div>
			</div> <!--End Left Col-->
			<div id="center_col"> <!--Start Center Col-->
				<div id="signup_container">
					<div id="calendar_top"></div>
						<div style="padding-left:20px;padding-top:20px;">
						<div id="notification"></div>
						<form name="signup_form" id="signup_form" method="post">
						<table border="0" style="width:500px;">
						<tr style="height:40px;">
							<td align="left">First Name</td>
							<td align="left">&nbsp;<input type="text" id="first_name" name="first_name" value="" style="width:250px;">
							<div class="sign_up_error" id="first_name_error"></div>
							</td>
						</tr>

						<tr style="height:40px;">
							<td align="left">Last Name</td>
							<td align="left">&nbsp;<input type="text" id="last_name" name="last_name" value="" style="width:250px;">
								<div class="sign_up_error" id="last_name_error"></div>
							</td></tr>
						<tr style="height:40px;"><td align="left">Email</td><td align="left">&nbsp;<input type="text" id="user_email" name="user_email" value="" style="width:250px;">
							<div class="sign_up_error" id="email_error"></div>
						</td></tr>
						<tr style="height:40px;"><td align="left">Password</td><td align="left">&nbsp;<input type="password" id="password" name="password" value="" style="width:250px;">
							<div class="sign_up_error" id="password_error"></div>
						</td></tr>
						<tr style="height:40px;"><td align="left">Confirm Password</td><td align="left">&nbsp;<input type="password" id="confirm_password" name="confirm_password" value="" style="width:250px;">
							<div class="sign_up_error" id="confirm_password_error"></div>
						</td></tr>
						<tr style="height:40px;"><td></td><td align="left">&nbsp;<input type="submit" id="submit_btn" name="submit_btn" value="Create Account" style="width:150px;"></td></tr>
						<tr><td colspan="2" align="left"><span style="font-size:10pt;">Already have an account? <a href="../login/">Click here to login</a></span></td></tr>
						</table>
						<input type="hidden" name="action" value="create_account">
						</form>
						</div>
					<div id="calendar_bottom"></div>
				</div>
			</div> <!--End Center Col-->
			<div id="right_col"> <!--Start Right Col-->
				<div class="mod" style="display:none;">
					<div class="mod_top"></div>
						<h3>Contacts</h3>						
				<div class="clear"></div>
				</div>
				<div class="mod" style="display:none;">
					<div class="mod_top"></div>
						<h3>Manual Message Launch</h3>						
				</div>
			</div> <!--End Right Col-->
		<div class="clear"></div>
		</div> <!--End Content-->
		<?php include('../includes/footer.php'); ?>
	</div> <!--End Container-->
	<div id="modal_container" title="general modal" class="modal"></div>
		
</body>
</html>