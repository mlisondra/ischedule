<div id="form_notification"></div>
<div style="clear:both;"></div>
<div>
	<form name="edit_category_form" id="edit_category_form" method="post" class="iform">
	<table border="0">
		<tr><td>Name</td></tr>
		<tr><td><input type="text" name="name" id="name" value="<?php print $name; ?>" size="40"></td></tr>
		<tr><td>
		Color&nbsp;
		<input type="hidden" name="color" id="color" class="color-picker" size="6" value="<?php print $color; ?>" />
		</td></tr>
	<tr><td>Description</td></tr>
	<tr><td><textarea name="description" id="description"><?php print $description; ?></textarea></td></tr>
	<tr>
		<td>
		Calendar Managers<br/>
		<div id="calendar_managers">
                    <div id="manager_names"><?php print $calendar_managers; ?></div>
                    <div id="managers_list" style="margin-top:5px;"><?php print $manager_inputs; ?></div>
		</div>
                <div id="managers"><?php print $existing_managers; ?></div>
		</td>
	</tr>
        <tr>
            <td>
                <div style="margin-top:25px;">
                <input type="submit" value="Save">
                <input type="button" value="Cancel" class="save_button_cancel">
                </div>
        </tr>
        <tr>
            <td>
                <div style="margin-top:30px;">
                    <input type="button" class="delete_button" value="Delete Calendar">
                    <div style="display:none;">Sure? (all related Events will be deleted, too) <input type="button" class="delete_button_confirm" rel="calendar" value="Yes">
                        <input type="button" class="delete_button_cancel" value="Cancel">
                    </div>
		</div>
            </td>
        </tr>
        <input type="hidden" name="obj_type" value="calendar">
	<input type="hidden" name="id" id="id" value="<?php echo $id; ?>">
	</form>
</div>
<script type="text/javascript">
        $(document).ready(function(){
            $(".color-picker").miniColors({
                    letterCase: 'lowercase',
                    change: function(hex, rgb) {
                            $(".miniColors-selector").hide();
                    }
            });
        });
</script>
