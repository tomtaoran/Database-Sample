<?php
include_once('functions.php'); // Include core functions.
if ($_GET['email']) { // Is the email there?
	if (verifyemail($_GET['email'], $_GET['hash']) == TRUE) { // Does the hash match the email?
		$correct = TRUE; // So it is correct...
	}
}
?><!DOCTYPE html>
<html lang="en">
    <head>
		<meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
		<meta name="viewport" content="width=device-width, initial-scale=1.0"> 
        <title>Email Verification</title>
		<!--[if lte IE 7]><style>.main{display:none;} .support-note .note-ie{display:block;}</style><![endif]-->
		<link rel="stylesheet" href="style.css" type="text/css" />
    </head>
    <body>
        <div class="container">			
			<section class="main">
				<div class="register-form"> 
					<h1>Was Your Verification Link correct?</h1>
					<?php if ($correct == TRUE) {
					// Now... Let's start our sessions since we got Positive Verification
					session_start();
					if(isset($_SESSION['username']) && $_SESSION['username']==root) {
					//echo "Your session is running " . $_SESSION['username']; //for debug use
					$_SESSION['verified'] = true;
					}else{
					$_SESSION['username'] = "limted";
					$_SESSION['password'] = "pass";
					$_SESSION['verified'] = true;
					//echo "Session variables are set."; //for debug use
					//echo "Your session is running " . $_SESSION['username'];//for debug use
					//Connection may not be needed
					}
					
					header( 'Location: /user_register.php' ) ;
					
					?>
					<p><b>Success</b>, the hash matches the email.</p>
					<?php } else {
					?><p><b>Error</b>, the hash doesn't match the email.</p>
					<?php } ?>
					<br /><br />
					</div>
			</section>
			
        </div>
    </body>
</html>