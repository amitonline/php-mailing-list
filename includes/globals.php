<?php
	
///
// Global vars and data
// Created: Nov. 2017
//
//
// db connection and path


if ($_SERVER['REMOTE_ADDR'] == '127.0.0.1' || $_SERVER['REMOTE_ADDR'] == '192.168.1.113' || $_SERVER['REMOTE_ADDR'] == '192.168.1.80' || $_SERVER['REMOTE_ADDR'] == '192.168.1.77')
{
		// db connection for all local sites
	 
  	 $g_connServer = "localhost";
	 $g_connUserid = "root";
	 $g_connPwd = "master";
	 $g_connDBName = "mailing_list";
	 $g_docRoot = "/var/www/mailing-list/";
  	 $g_serverName = "mlist.test";
	 define("DEFAULT_TIME_ZONE", "GMT");
	 $g_webRoot = "/";	
	 $g_zendPath = "";
      
}
else  {
	// this is for live site
	 $g_connServer = "localhost";
	 $g_connUserid = "some userid";
	 $g_connPwd = 'some password';
	 $g_connDBName ="some db";
	 $g_docRoot = "/path/to/webroot/";	// trailing slash is important
	 $g_serverName = "server.com";
	 
	 $g_webRoot = "/";
	 define("DEFAULT_TIME_ZONE", "GMT");

} 


/**********smtp server details*************/
$g_smtpServer = "mail.sevrer.in";
$g_smtpPort = 587;
$g_smtpUserId = "support@server.in";
$g_smtpPwd = "O^[K(+_.-HfI";

$g_fromEmailId = "support@server.in";
$g_fromName = "Mailing List Support";
/********smtp server details end********/

/**
 * Convert date into a user friendly display format
 * Parameters: dt->raw dt
 *			   nFormat->format type
 * Returns   : strDt->formatted date
 ******/

function getNiceDate($dt, $nFormat) {
	$strDt = strtotime(str_replace('/','-',$dt));
	$arrdate = getdate($strDt);
	$nYear = $arrdate[year];
	$nMonth = $arrdate[month];
	$nDay = $arrdate[mday];
	$nHour = $arrdate[hours];
	if ($nHour < 10)
		$nHour = "0" . $nHour;
	$nMin = $arrdate[minutes];
	if ($nMin < 10)
		$nMin = "0" . $nMin;
	$strWeekDay = $arrdate[weekday];
	if ($nFormat != DATE_NOWEEKDAY)
		$strDt = substr($strWeekDay,0,3);
	else
		$strDt = "";
	if ($nFormat == DATE_STANDARD) {
		$strDt = $nYear . "-" ;
		if ((int) $arrdate[mon] < 10)
			$strDt .= "0";
		$strDt .= $arrdate[mon] . "-" ;
		if ((int) $nDay < 10)
			$strDt .= "0";
		$strDt .= $nDay;
	}			
	else if ($nFormat == DATE_EXCEL) {
		$strDt = " " . $nDay . " " . substr($nMonth,0,3) . " " . $nYear ;
	}
	else
		$strDt .= " " . $nDay . " " . substr($nMonth,0,3) . " " . $nYear ;
	if ($nFormat == DATE_FULL) {
		$strDt .= " " . $nHour . ":" . $nMin;
	}		
	return $strDt;
}

function getDomain($url)
{
	$nowww = ereg_replace('www\.','',$url);
	$domain = parse_url($nowww);
	if(!empty($domain["host"]))
    {
    	 return $domain["host"];
    } else
     {
	     return $domain["path"];
     }
 
}



/**
 * Generate a random string given a set of allowed characters
 * @param string $valid_chars string of valid characters. if null then 0-9 is assumed
 * @param int $length max length of string
 * @return string $random_string generated string
 */
function get_random_string($valid_chars, $length)
{

    if ($valid_chars == null || $valid_chars == '')
	$valid_chars = "012345679";
    // start with an empty random string
    $random_string = "";

    // count the number of chars in the valid chars string so we know how many choices we have
    $num_valid_chars = strlen($valid_chars);

    // repeat the steps until we've created a string of the right length

    for ($i = 0; $i < $length; $i++)
    {
        // pick a random number from 1 up to the number of valid chars
        $random_pick = mt_rand(1, $num_valid_chars);

        // take the random character out of the string of valid chars
        // subtract 1 from $random_pick because strings are indexed starting at 0, and we started picking at 1
        $random_char = $valid_chars[$random_pick-1];

        // add the randomly-chosen char onto the end of our string so far
        $random_string .= $random_char;
    }

    // return our finished random string
    return $random_string;
}




		
function sendMail($from_emailId, $from_name, $to_emailId, $to_name, $subject, $body) {
	global $g_smtpServer, $g_smtpPort, $g_smtpUserId, $g_smtpPwd;
		
		/*echo($g_smtpServer. "," . $g_smtpPort ."," .  $g_smtpUserId ."," .  $g_smtpPwd 
 . "\n\n" . "from=" . $from_emailId . "," .  "from name=" . $from_name ."," .  "to=" . $to_emailId . "," .  "to name=" . $to_name . "," . $subject . "," .  ",userid=" . $g_smtpUserId . ", pwd=" . $g_smtpPwd . "\n". $body);*/
		$mail = new PHPMailer();
		$mail->isSMTP();
		//Enable SMTP debugging
		// 0 = off (for production use)
		// 1 = client messages
		// 2 = client and server messages
		$mail->SMTPDebug = 0;
		//Ask for HTML-friendly debug output
		$mail->Debugoutput = 'html';
		//Set the hostname of the mail server
		$mail->Host = $g_smtpServer;
		//Set the SMTP port number - likely to be 25, 465 or 587
		$mail->Port = $g_smtpPort;

		//$mail->SMTPSecure ="tls";

		//Whether to use SMTP authentication
		$mail->SMTPAuth = true;
		//Username to use for SMTP authentication
		$mail->Username =  $g_smtpUserId;
		//Password to use for SMTP authentication
		$mail->Password = $g_smtpPwd ;
		//Set who the message is to be sent from
		$mail->setFrom($from_emailId, $from_name);
		//Set an alternative reply-to address
		$mail->addReplyTo($from_emailId, $from_name);
		//Set who the message is to be sent to
		$mail->addAddress($to_emailId, $to_name);
		//Set the subject line
		$mail->Subject =  $subject;
		$mail->msgHTML($body);
		//Replace the plain text body with one created manually
		$mail->Body = $body;
		$mail->IsHTML(true); 



		//send the message, check for errors
		if (!$mail->send()) {
			$msg =  $mail->ErrorInfo;
		} else {
			$msg = "";
		}				

		return $msg;
}

?>
