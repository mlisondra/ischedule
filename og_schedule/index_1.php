<?php session_start(); ?>
<?php
if(!isset($_SESSION['user']['email'])){
	header("Location: ../login/");
	exit();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html> 
<head> 
<title>iSchedule247</title>
<?php include('../includes/js_css.php'); ?>
</head>

<body style="font-family:Arial,sans-serif;" onload="get_contacts()">

<?php include("../includes/subheader.php");?>
<div style="clear:both;"></div>
<div class="rounded_colhead">
  <div class="tl"></div><div class="tr"></div>
  	<div id="notification"></div>
  	<div style="clear:both;"></div>
  	<h2>Your Schedule</h2>
		<table border="0" width="100%">
  		<tr valign="top">
  			<td>
  			<div id="calendar" style="width:700px;">
  			<img src="../images/calendar.png">
  			
  			</div>
  			</td>
  			<td>
  				<div class="login_box_home" style="padding: 5px;margin-left:10px;">
  				Contacts<br/><br/>
  				<form method="post">
  				<div id="list_of_contacts"></div>
  				</form>
  				
	  				<form>
	  				<br/>
	  				<a href="###" onclick="toggle_display('quick_add')">Add</a><br/>
	  				<div id="quick_add" style="display:none;">Email<br/>
	  				<input type="text" id="email" name="email"><br/><br/>
					Name<br/>
	  				<input type="text" id="first_name" name="first_name"><br/><br/> 
	  				<input type="button" value="add" onclick="add_contact()"> 				
	  				</div>
					<a href="../logout/">Log Out</a><br/>
	  				</form>
  				</div>
  			</td>
  		</tr>
  		</table>  
  <div class="bl"></div><div class="br"></div>
</div>
<div style="clear:both;"></div>
<?php include("../includes/footer.php");?>
</body>
</html>