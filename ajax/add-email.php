<?php
	error_reporting(E_ALL ^ E_WARNING ^ E_NOTICE ^ E_DEPRECATED);
	session_start();
	

	require_once("../includes/globals.php");
	require_once($g_docRoot . "classes/emails.php");
	require_once($g_docRoot . "PHPMailer-master/PHPMailerAutoload.php");
	
		// check for valid page referer
	$rDomain = getDomain($_SERVER["HTTP_REFERER"]);
	$thisDomain = $_SERVER['SERVER_NAME'];
	$thisDomain = str_replace("www.", "", $thisDomain);

	if (strtolower(trim($rDomain)) != strtolower(trim($thisDomain))) {
		exit("ERROR - Cross domain posting detected");
	}

	// get params
	$email = $_POST["email"];
	if ($email == null || $email == "") {
		exit("Error: Invalid email");
	}	
	$emails = new Emails($g_docRoot, $g_connServer, $g_connUserid, $g_connPwd, $g_connDBName);
	$row = $emails->getRowByEmailId($email);
	if ($row["ID"] > 0)
		exit("This emailid already exists");

	$vkey = get_random_string(null, 6);
	$link = "http://mlist.test/verify.php?code=". $vkey;

	$arrData = ["email"=>$email, "signup"=>date("Y-m-d H:i:s"), "vkey"=>$vkey];
	$emails->update($arrData, 0);
	if ($emails->mError != null && $emails->mError != "")
		exit("Error: " . $emails->mError);
	else {
		// send verification email
		$subject = "Mailing List - Signup Verification";
		$content = file_get_contents($g_docRoot . "mails/verification.html");
		$content = str_replace("#name#",  "Subscriber", $content);
		$content = str_replace("#verificationlink#", $link , $content);

		sendMail($g_fromEmailId, $g_fromName, $email,"Subscriber", $subject, $content);

	}


	
?>
