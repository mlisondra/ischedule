<?php include('../config/production.php'); ?>
<!DOCTYPE html>
<html>
	<head>
		<title>Upgrade your free iSchedule247 account to the full version!</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<title>Schedule calendar events to automatically remind your contacts in advance of events and appointments </title>
		<meta http-equiv="description" name="description" content="iSchedule247.com offers a free calendar that's beautifully simple that will automatically send emails and text message reminders to your friends, family, teammates, co-workers, etc.">
		<meta http-equiv="keywords" name="keywords" content="event schedule, event scheduling, automatic reminders">
		<meta name="robots" content="index,follow">
		<meta nameF="revisit-after" content="10 days">
		<meta http-equiv="Content-Style-Type" content="text/css" />
		<?php include("../includes/js_css.php") ;?>
		<script type="text/javascript">
			$('#paypal_dialog').dialog();
		$(document).ready(function(){
		});
		</script>
	</head>
<body>
	<div id="container"> <!--Start Container-->
		<?php include('../includes/header.php'); ?>
		<div id="content"> <!--Start Content-->
			<div id="left_col"> <!--Start Left Col-->
				<div class="mod" style="display:none;"></div>
				
				<div class="mod" style="display;none;"></div>
				
			</div> <!--End Left Col-->
			<div id="center_col"> <!--Start Center Col-->
				<div id="" style="background-color:#FFFFFF;width:650px;"><div id="calendar_top"></div>
					<div class="static_content_container">
						<?php
							$content = file_get_contents("../templates/upgrade.tpl");
							print $content;
						?>	
					</div>				
				</div>
				
			</div> <!--End Center Col-->
			<div id="right_col"> <!--Start Right Col-->
				<div class="mod" style="display;none;"></div>
				
			</div> <!--End Right Col-->
		<div class="clear"></div>
		</div> <!--End Content-->
		<?php include('../includes/footer.php'); ?>
	</div> <!--End Container-->

</body>
</html>