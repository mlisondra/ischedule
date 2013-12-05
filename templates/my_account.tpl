<div id="form_notification"></div><br/>
<form name="my_account_form" id="my_account_form" method="post">
<table>
<tr><td>First Name</td><td><input type="text" name="first_name" id="first_name" value="##first_name##"></td></tr>
<tr><td>Last Name</td><td><input type="text" name="last_name" id="last_name" value="##last_name##"></td></tr>
<tr><td>Phone</td><td><input type="text" name="phone" id="phone" value="##phone##"></td></tr>
<tr><td>Phone Carrier&nbsp;</td>
<td>##phone_carrier_list##</td></tr>
<tr>
<td colspan="2">
<strong>Change Password</strong><br>
Enter Current Password<br>
<input type="text" name="current_password" id="current_password"><br>
New Password<br>
<input type="text" name="new_password" id="new_password"><br>
</td></tr>
</table>
<input type="hidden" name="action" value="edit_my_account">
</form>
