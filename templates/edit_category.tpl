<div id="form_notification"></div>
<form name="edit_category_form" id="edit_category_form" method="post">
<table border="0">
<tr><td>Name</td></tr>
<tr><td><input type="text" name="name" id="name" value="##name##" size="40"></td></tr>
<tr><td>
Color&nbsp;
<!--<input type="color" value="##color##" data-text="hidden" style="height:20px;width:20px;" name="color" id="color"/>-->
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
	</td>
</tr>
<input type="hidden" name="action" value="edit_category">
<input type="hidden" name="id" value="##id##">
</form>
<div style="clear:both;"></div>
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
