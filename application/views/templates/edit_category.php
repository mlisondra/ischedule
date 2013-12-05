<div id="form_notification"></div>
<div style="clear:both;"></div>
<div>
	<form name="edit_category_form" id="edit_category_form" method="post">
	<table border="0">
		<tr><td>Name</td></tr>
		<tr><td><input type="text" name="name" id="name" value="##name##" size="40"></td></tr>
		<tr><td>
		Color&nbsp;
		<input type="hidden" name="color" id="color" class="color-picker" size="6" value="##color##" />
		</td></tr>
	<tr><td>Description</td></tr>
	<tr><td><textarea name="description" id="description" rows="5" cols="30">##description##</textarea></td></tr>
	<tr>
		<td>
		Calendar Managers<br/>
		<div id="calendar_managers">
			<div id="manager_names">##manager_names##</div>
			<div id="managers_list" style="margin-top:5px;">##manager_inputs##</div>
		</div>
		<div id="managers"></div>
		<div style="margin-top:90px;">
			<input type="button" class="delete_button" value="Delete Calendar">
			<div style="display:none;">Sure? (all related Events will be deleted, too) <input type="button" class="delete_button_confirm" rel="calendar" value="Yes"> <input type="button" class="delete_button_cancel" value="Cancel"></div>
		</div>
		</td>
	</tr>
	<input type="hidden" id="id" value="<?php echo $id; ?>">
	</form>
</div>
		<script type="text/javascript">
			$(document).ready(function(){
				$(".color-picker").miniColors({
					letterCase: 'lowercase',
					change: function(hex, rgb) {
						//logData(hex, rgb);
						$(".miniColors-selector").hide();
					}
				});
			});
		</script>
