<div id="form_notification"></div><br/>
<form name="add_contact_form" id="add_contact_form" method="post" class="iform">
<table>
<tr><td>First Name</td><td><input type="text" name="first_name" id="first_name" value="" size="30"></td></tr>
<tr><td>Last Name</td><td><input type="text" name="last_name" id="last_name" value="" size="30"></td></tr>
<tr><td>Email</td><td><input type="text" name="email" id="email" value="" size="30"></td></tr>
<tr><td>Phone</td><td><input type="text" name="phone" id="phone" value=""></td></tr>
<tr><td>Phone Carrier&nbsp;</td>
<td>
    <?php 
    echo '<select name="phone_carrier" id="phone_carrier">
	<option value="" selected>--Select One--</option>';
    foreach($phone_carriers as $carrier){
        echo '<option>'. $carrier . '</option>';
    } 
    echo '</select>';
    ?>
</td></tr>
<tr valign="top"><td>Calendar</td><td>
<?php print $calendars; ?>
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
<input type="submit" name="add_contact_button" value="Add" class="save_button" rel="add_contact">
<input type="button" class="save_button_cancel" value="Cancel">
</form>