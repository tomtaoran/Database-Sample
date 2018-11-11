<!DOCTYPE html>
<?php
// Open a connection to the database
// (display an error if the connection fails)
session_start();
if(isset($_SESSION['verified']) && $_SESSION['verified']==true){
if(isset($_SESSION['username']) && $_SESSION['username']==root) {
//	echo "Your session is running " . $_SESSION['username']; //for debug use
	//connection
	$_SESSION['temporary_authority'] = false;
	
}else{
	$_SESSION['username'] = "limted";
	$_SESSION['password'] = "pass";
	$_SESSION['temporary_authority'] = true;
//	echo "Session variables are set."; //for debug use
//	echo "Your session is running " . $_SESSION['username'];//for debug use
//	echo "Your session has a temporary authority: " . $_SESSION['temporary_authority'];//for debug use
	//Connection may not be needed	
}
	$conn = mysqli_connect('localhost', 'root', 'oagach4a') or die(mysqli_error($conn));
mysqli_select_db($conn, 'steamdatabase') or die(mysqli_error($conn));
}else{
	header( 'Location: /email_verification.php' ) ;
}
?>

<html>
<head>
<title>Register</title>
</head>
<body>

<h1>Register</h1>

<?php
   // Only execute if we're receiving a post
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
   
  // This will be the string we collect errors in
    $errors = '';

    // Make sure the username field is filled
	$UserName = htmlspecialchars($_POST['UserName']);
    if (empty($UserName)) $errors .= '<li>UserNameame is required</li>';

    // Make sure the password field is filled
	$Password = htmlspecialchars($_POST['Password']);
    if (empty($Password)) $errors .= '<li>Password is required</li>';

    // If we have any errors at this point, stop here and show them
    if (!empty($errors)) {
        echo '<ul>' . $errors . '</ul>';

    // Otherwise, begin the user creation process
    } else {
        // First, check for that username already being taken
	$user_results = mysqli_query($conn, "SELECT UserID FROM Users WHERE UserName = '" . $UserName."'") or die("Query Failed");

    // We don't care what the result is
    // If there is one, that means the username is taken
    if ($user_results) {
    	if (mysqli_fetch_array($user_results)) {
        	echo '<ul><li>UserName already taken</li></ul>';
	}
    // If no duplicates are found, go ahead and create the new user
       else {
		$UserName = mysqli_real_escape_string($conn,$UserName);
		$Password = mysqli_real_escape_string($conn,$Password);
		mysqli_query($conn,"CALL registerUser('" . $UserName . "', '" . $Password . "')") or die(‘error_storeproc’);
		
        // Show a success message
        echo '<ul><li>Registration successful!</li></ul>';

        // Set the name and username fields to empty strings so they don't
        // get automatically repopulated
        $UserName = '';
		$Password = '';
    }
}

    }
}
?>
<?php
// ONLY REVOKE USER RIGHTS AFTER REGISTRATION
	if ($_SESSION['temporary_authority'] == true) {
		$_SESSION['username'] = "limted";
		$_SESSION['password'] = "pass";
		$_SESSION['temporary_authority'] = false;
	}
?>


<form action="" method="post">
    <label for="UserName">UserName</label><br/>
    <input type="text" name="UserName"/><br/>

    <label for="Password">Password</label><br/>
    <input type="password" name="Password"/><br/>
	
    <input type="submit" value="Register"/>	
</form>

<form action="/index.php">
<input type="submit" value="Go to Main Page">
</form>

</body>
</html>
