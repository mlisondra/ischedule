<form name="reset_password_form" id="reset_password_form" method="post">
	<label>Email</label><br /><div class="sign_up_error" id="email_error"></div>
	<input type="text" name="user_email" id="user_email"><br />
	<input type="submit" value="Reset My Password">&nbsp;
	<input id="cancel_reset" value="Cancel" type="button" onclick="cancel_reset_password()">
</form>