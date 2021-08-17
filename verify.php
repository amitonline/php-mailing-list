<?php
/**
 * 
 */
	session_start();
	error_reporting(E_ALL ^ E_WARNING ^ E_NOTICE ^ E_DEPRECATED);
	
	require_once("includes/globals.php");
	require_once($g_docRoot . "classes/emails.php");
	require_once($g_docRoot . "PHPMailer-master/PHPMailerAutoload.php");
	
	$pageTitle = "Mailing List - Verify Signup";
	$emails = new Emails($g_docRoot, $g_connServer, $g_connUserid, $g_connPwd, $g_connDBName);

	$code = $_GET["code"];

	if ($code == null || $code == "") {
		exit("Invalid verification code");
	}
	$row = $emails->getRowByVerifyCode($code);
	
	if ($row && $row["vkey"] == $code && $row["verified"] != 1) {
		$arrData = ["verified"=>1];
		$emails->update($arrData, $row["ID"]);

		// send confirmation email
		$subject = "Mailing List - Signup Confirmation";
		$content = file_get_contents($g_docRoot . "mails/confirmation.html");
		$content = str_replace("#name#",  "Subscriber", $content);
		
		sendMail($g_fromEmailId, $g_fromName, $row["email"], "Subscriber", $subject, $content);
		$error = false;
		$msg = "Congratulations. Your signup is confirmed.";
	} else {
		$error = true;
		$msg = "Sorry. This verification code has either expired or is invalid";
	}
?>

<?php require_once($g_docRoot . "components/header.php"); ?>


  <div class="container mt-3">	
	<div class="col-sm-12 text-center">
		<h2>Verify Signup</h2>
		</div>
		<div class="clearfix"></div><br>
		<div class="col-sm-12">
			<?php if ($error) { ?>
				<div class="alert alert-danger text-center">
			<?php } else { ?>
				<div class="alert alert-success text-center">
			<?php } ?>
			  <?php echo("<h6>" . $msg . "</h6>");
			 ?>
			</div>
		</div>


 </div> <!--container-->
 
<?php require_once($g_docRoot . "components/footer.php"); ?>
<?php require_once($g_docRoot . "components/scripts.php"); ?>
<script>
	var WEBROOT = "<?php echo($g_webRoot); ?>";
	
</script>
	<script src="../includes/verify-signup.js"></script>

</body>
</html>

