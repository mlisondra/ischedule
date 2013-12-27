<div id="form_notification"></div>
<form name="add_event_form" id="add_event_form" method="post" class="iform">
<table border="0" width="100%">
<tr>
<td valign="top">
	<span class="form_field_header">Title</span><br><input type="text" name="title" id="title" value="" size="30">
	<span class="form_field_header">Start Date &amp; Time</span><br>
	<input type="text" name="begin_date" id="begin_date" size="10" value="##begin_date##">&nbsp;
	<input type="text" name="begin_time" id="begin_time" value="08:00 AM" size="10" class="timepicker">
	<br><br>
	<span class="form_field_header">End Date & Time</span><br>
	<input type="text" name="end_date" id="end_date" size="10" value="##end_date##">&nbsp;
	<input type="text" name="end_time" id="end_time" value="04:00 PM" size="10" class="timepicker">
	<br/><br/>
	<span class="form_field_header">Assign to Calendar</span><br/><?php print $calendars; ?>
</td>
<td valign="top">
	<span class="form_field_header">Send Reminder Out</span><br/>
	<?php print $reminder_notification_list; ?>
	<br/><br/>
	<span class="form_field_header">Your Message Reminder</span><br/>
	<textarea name="description" id="description"></textarea>
</td></tr>
</table>
<input type="submit" name="add_event_button" value="Add" class="save_button" rel="add_event">
<input type="button" class="save_button_cancel" value="Cancel">
</form>
<script type="text/javascript">
$('#title').jqEasyCounter({'maxChars':15,'msgAppendMethod': 'insertAfter'});
$('#description').jqEasyCounter({'maxChars':50,'msgAppendMethod': 'insertAfter'});
</script>