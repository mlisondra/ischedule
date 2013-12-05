<div id="header"> <!--Start Header-->
	<?php if($_SESSION['user']['logged_in'] == 'yes' && $_SESSION['user']['logged_in_id'] !=''){ ?>
		<a href="../schedule/"><h1>iSchedule247</h1></a>
	<?php }else{ ?>
		<a href="../"><h1>iSchedule247</h1></a>
	<?php } ?>
	<!--<h1 id="add_this">
		<div class="addthis_toolbox addthis_default_style" style="float:right;border:solid 0px;margin:5px 30px 0 0;">
		<span class="at300bs at15nc at15t_compact"></span>
		</div>
		<script type="text/javascript" src="https://s7.addthis.com/js/250/addthis_widget.js#pubid=xa-4d90cfe14718e9aa"></script>
	</h1>-->	
	<div id="nav">
		<a href="../about/" title="Well since you really want to know">About Us</a>
		<a href="../faqs/">FAQs</a>
		<?php if($_SESSION['user']['logged_in'] == 'yes' && $_SESSION['user']['logged_in_id'] !=''){ ?>
			<a href="###" title="View account settings" onclick="show_modal('my_account','<?php echo $_SESSION['user']['logged_in_id']; ?>');">Account Settings</a>
		<a href="../logout/" id="logout" title="Logout">Logout</a> 
		<?php } ?>
	</div>
</div> <!--End Header-->