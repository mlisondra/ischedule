<div id="form_notification"></div><br/>
<form name="add_category_form" id="add_category_form" method="post">
<table>
<tr><td>Name</td></tr>
<tr><td><input type="text" name="name" id="name" value=""></td></tr>
<tr><td>Color &nbsp;
<input type="hidden" name="color" id="color" class="color-picker" size="6" value="##color##" />
</td></tr>
<tr><td>Description</td></tr>
<tr><td>
        <textarea name="description" id="description" rows="5" cols="35"></textarea>
        <div style="margin-top:10px;">
        <input type="button" class="save_button" value="Save">
        <input type="button" class="save_button_cancel" value="Cancel"></div>
        </div>
    </td></tr>
<input type="hidden" name="action" value="add_category">
</form>
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