<div id="form_notification"></div><br/>
<form name="edit_event_form" id="edit_event_form" method="post" class="iform">
<table border="0" width="100%">
<tr valign="top"><td>
	<span class="form_field_header">Title</span><br><input type="text" name="title" id="title" value="<?php print $title; ?>" size="30"><br>
	<span class="form_field_header">Start Date &amp; Time</span><br>
	<input type="text" name="begin_date" id="begin_date" value="<?php print $begin_date; ?>" size="10">&nbsp;
        <input type="text" name="begin_time" id="begin_time" value="<?php print $end_time; ?>" size="10">
	<br><br>
	<span class="form_field_header">End Date & Time</span><br>
	<input type="text" name="end_date" id="end_date" value="<?php print $end_date; ?>" size="10">&nbsp;
        <input type="text" name="end_time" id="end_time" value="<?php print $end_time; ?>" size="10">
	<br/><br/>
	<span class="form_field_header">Assign to Category</span><br/><?php print $calendars; ?>
</td>
<td>
	<span class="form_field_header">Send Reminder Out</span><br/>
	<?php print $reminder_notification_list; ?>
	<br/>
	<span class="form_field_header">Description</span><br/>
	<textarea name="description" id="description" rows="5" cols="30"><?php print $description; ?></textarea>
	<input type="hidden" name="action" value="edit_event">
	<input type="hidden" name="id" value="<?php print $id; ?>">
</td>
</tr>
</table>
	
</form>
<script type="text/javascript">
    $('#title').jqEasyCounter({'maxChars':15,'msgAppendMethod': 'insertAfter'});
    $('#description').jqEasyCounter({'maxChars':50,'msgAppendMethod': 'insertAfter'});
</script>