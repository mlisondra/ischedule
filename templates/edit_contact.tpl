<div id="form_notification"></div><br/>
<form name="edit_contact_form" id="edit_contact_form" method="post">
<table>
<tr><td>First Name</td><td><input type="text" name="first_name" id="first_name" value="##first_name##" size="30"></td></tr>
<tr><td>Last Name</td><td><input type="text" name="last_name" id="last_name" value="##last_name##" size="30"></td></tr>
<tr><td>Email</td><td><input type="text" name="email" id="email" value="##email##" size="30"></td></tr>
<tr><td>Phone</td><td><input type="text" name="phone" id="phone" value="##phone##"></td></tr>
<tr><td>Phone Carrier</td>
	<td>##phone_carrier_list##</td></tr>
<tr valign="top"><td>Calendar</td><td>
##user_categories##
</td></tr>
<tr>
	<td>Reminder Type</td>
	<td>
	<select name="reminder_type" id="reminder_type">
	
	##reminder_type_list##
	</select>
	</td>
</tr>
</table>
<input type="hidden" name="contact" value="##contact##">
<input type="hidden" name="action" value="edit_contact">
</form>