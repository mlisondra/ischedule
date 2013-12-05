<?php
include('../config/production.php');
//include('../classes/accounts.class.php');
include("../phpmailer/class.phpmailer.php");
$mail = new PHPMailer();

$mail->From = "milder.lisondra@yahoo.com";
			$mail->FromName = "iSchedule Events";
			$mail->AddAddress("milder.lisondra@yahoo.com","Milder Lisondra");
			$mail->Subject  = "Test reminder";
			$message_body = '<html><body>';
			$message_body = "<h2>hello there</h2></body></html>";
			$mail->Body = $message_body;
			$mail->WordWrap = 50;
			$mail->isHTML(true);
			$mail->Send();
?>