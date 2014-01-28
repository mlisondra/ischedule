<div id="form_notification"></div><br/>
<form name="edit_contact_form" id="edit_contact_form" method="post" class="iform">
<table style="width:100%;">
<tr><td>First Name</td><td><input type="text" name="first_name" id="first_name" value="<?php print $first_name; ?>" size="30"></td></tr>
<tr><td>Last Name</td><td><input type="text" name="last_name" id="last_name" value="<?php print $last_name; ?>" size="30"></td></tr>
<tr><td>Email</td><td><input type="text" name="email" id="email" value="<?php print $email; ?>" size="30"></td></tr>
<tr><td>Phone</td><td><input type="text" name="phone" id="phone" value="<?php print $phone; ?>"></td></tr>
<tr><td>Phone Carrier</td>
    <td>
    <?php 
    echo '<select name="phone_carrier" id="phone_carrier">
	<option value="" selected>--Select One--</option>';
    foreach($phone_carriers as $carrier){
        if($phone_carrier != $carrier){
            echo '<option>'. $carrier . '</option>';
        }else{
            echo '<option selected>'. $carrier . '</option>';
        }
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
        <option value="" <?php if($reminder_type == ""){ echo 'selected';} ?>>--Select One--</option>
	<option value="SMS" <?php if($reminder_type == "SMS"){ echo 'selected';} ?>>Text</option>
	<option value="Email" <?php if($reminder_type == "Email"){ echo 'selected';} ?>>Email</option>
	<option value="SMS_Email" <?php if($reminder_type == "SMS_Email"){ echo 'selected';} ?>>Text &amp; Email</option>
	</select>
	</td>
        </tr>

        <tr>
            <td colspan="2">
                <div style="margin-top:20px;">
                    <input type="submit" name="edit_contact_button" value="Save" class="save_button" rel="edit_contact">
                    <input type="button" class="save_button_cancel" value="Cancel">
                </div>
            </td>
        </tr>
        
        <tr>
            <td colspan="2">
                <div style="margin-top:30px;">
                    <input type="button" class="delete_button" value="Delete Contact">
                    <div style="display:none;">Sure? <input type="button" class="delete_button_confirm" rel="contact" value="Yes">
                        <input type="button" class="delete_button_cancel" value="Cancel">
                    </div>
		</div>
            </td>
        </tr>
        

</table>
        <input type="hidden" name="obj_type" value="contact">
	<input type="hidden" name="id" id="id" value="<?php echo $id; ?>">

</form>