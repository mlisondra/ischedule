<div id="form_notification"></div>
<form name="add_event_form" id="add_event_form" method="post">
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
	<span class="form_field_header">Assign to Calendar</span><br/>##user_categories##
</td>
<td valign="top">
	<span class="form_field_header">Send Reminder Out</span><br/>
	##reminder_notification_list##
	<br/><br/>
	<span class="form_field_header">Your Message Reminder</span><br/>
	<textarea name="description" id="description" rows="5" cols="30"></textarea>
</td></tr>
</table>
<input type="hidden" name="action" value="add_event">
</form>
<script type="text/javascript">
$('#title').jqEasyCounter({'maxChars':15,'msgAppendMethod': 'insertAfter'});
$('#description').jqEasyCounter({'maxChars':50,'msgAppendMethod': 'insertAfter'});
</script>