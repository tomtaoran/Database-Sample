<?php

// IMPORTANT, This is the "salt" for our email address. You'd Better make it COMPLETELY RANDOM STRING OF CHARECTARS QWQ:
define('hashsalt', 'VaQTomf20p%GDOXSSe%HEAiBTAOgsYP:nb%C1');


// Locate email.php file, we need that file to work!!!!! -- Tom Tao:
define("url", "http://steamdatabase.csse.rose-hulman.edu/");

// IMPORTANT, This is where you want the email to sent FROM:
define("fromemail", "taor@rose-hulman.edu");

function hashEmail($string) {
	$string = cleaninput($string); // Prevent XSS 
	return hash('sha512', $string.constant("hashsalt")); // return hash
}

function cleaninput($string) {
	return htmlspecialchars($string, ENT_QUOTES, 'UTF-8'); // Convert special charectars to HTML.
}

function sendverification($email) {
	$hash = hashEmail($email); // Get hash ready to send.
	$to = cleaninput($email); // Prevent XSS
	$subject = 'Verify your account'; // Tell the receiver, it is Us, SteamDatabase that sent you the e-mail
	$message = ' 
 
Thanks for signing up! 
In order to get started you will need to activate your account.

'.constant("url").'/email.php?email='.$email.'&hash='.$hash.' 
 
'; // Our message above including the link  
	                      
	$headers = 'From:'.constant("fromemail")."\r\n"; // Set email headers  
	mail($to, $subject, $message, $headers); // Send our email
	return TRUE;
}

function verifyemail ($email, $hash) {
	$rehash = hashEmail($email); // Prepare hash for comparision.
	$hash = cleaninput($hash); // Sanitise the hash to prevent XSS.
	if ($hash == $rehash) { // Is it the same?
		return TRUE; // If it is return TRUE.
	} else { // If it isn't
		return FALSE; // then return FALSE.
	}
}