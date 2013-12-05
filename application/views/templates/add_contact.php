<div id="form_notification"></div><br/>
<form name="add_contact_form" id="add_contact_form" method="post">
<table>
<tr><td>First Name</td><td><input type="text" name="first_name" id="first_name" value="" size="30"></td></tr>
<tr><td>Last Name</td><td><input type="text" name="last_name" id="last_name" value="" size="30"></td></tr>
<tr><td>Email</td><td><input type="text" name="email" id="email" value="" size="30"></td></tr>
<tr><td>Phone</td><td><input type="text" name="phone" id="phone" value=""></td></tr>
<tr><td>Phone Carrier&nbsp;</td>
<td>##phone_carrier_list##</td></tr>
<tr valign="top"><td>Calendar</td><td>
##user_categories##
</td></tr>
<tr>
	<td>Reminder Type</td>
	<td>
	<select name="reminder_type" id="reminder_type">
	<option value="" selected>--Select One--</option>
	<option value="SMS">Text</option>
	<option value="Email">Email</option>
	<option value="SMS_Email">Text &amp; Email</option>
	</select>
	</td>
</tr>
</table>
<input type="hidden" name="action" value="add_contact">
</form>