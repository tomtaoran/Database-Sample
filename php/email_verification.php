<?php
include_once('functions.php'); // Include core functions.
if($_SERVER['REQUEST_METHOD'] == 'POST') { // Has the form been submitted?
	
	if (strpos($_POST['email'], '@rose-hulman.edu')== false) {
    echo '<ul><li>' . 'You need to input a Rose-Hulman Email! ' . '</li></ul>';
	}else{
		if (strpos($_POST['email'], '@rose-hulman.edu')== true && sendverification($_POST['email']) == TRUE) { // Send verification email.
			$sent = TRUE; // Set sent variable.
		}
	}	
}
?><!DOCTYPE html>
<html lang="en">
    <head>
		<meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
		<meta name="viewport" content="width=device-width, initial-scale=1.0"> 
        <title>Email verification</title>
		<!--[if lte IE 7]><style>.main{display:none;} .support-note .note-ie{display:block;}</style><![endif]-->
		<link rel="stylesheet" href="style.css" type="text/css" />
		
		<script>
		// In case browser does not support HTML5 validation, this is a fallback.
		function validateForm()
		{
		var x=document.forms["registerform"]["email"].value;
		var atpos=x.indexOf("@");
		var dotpos=x.lastIndexOf(".");
		if (atpos<1 || dotpos<atpos+2 || dotpos+2>=x.length)
		  {
		  alert("Not a valid e-mail address");
		  return false;
		  }
		}
	</script>
    </head>
    <body>
        <div class="container">			
			<section class="main">
			<?php if ($sent == TRUE) { ?>
				<div class="register-form"> 
					<h1>Verify Through Email.</h1>
					<p>You've been sent an email with a link inside it. Go check your email to proceed.</p>
				</div>
			<?php } else { ?>
				<form class="register-form" name="registerform" onsubmit="return validateForm();" method="POST" action="email_verification.php">
				    <h1>Oh... Wait, Before You Register: </h1>
				    <p>
				        <label for="login">Please Enter An Valid Email Address. You Will need to verify through your e-mail:</label>
				        <input  type="email" name="email" placeholder="Email" required>
				    </p>

				    <p>
				        <input type="submit" name="submit" value="Continue">
				    </p>       
				</form>​
			<?php } ?>
			</section>
			
        </div>
    </body>
</html>