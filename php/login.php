<!DOCTYPE html>
<?php
// Open a connection to the database
// (display an error if the connection fails
// this might be a serious security bug, since user can have root access at login page.
session_start();
$conn = mysqli_connect('localhost', 'root', 'oagach4a') or die(mysqli_error($conn));
mysqli_select_db($conn, 'steamdatabase') or die(mysqli_error($conn));
?>

<html>
<head>
<title>Login</title>
</head>
<body>

<h1>Login</h1>
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
	$user_results=mysqli_query($conn, "CALL loginUser('" . $UserName . "',
									 '" . $Password . "')" ) or die("Query Failed");
	
    //If user result exists, meaning there is a valid credential entered
	if ($user_results) {
		
    	if (mysqli_num_rows($user_results)>0) {
			$_SESSION['username'] = 'root';
			$_SESSION['password'] = 'oagach4a';
			$_SESSION['loginname'] = htmlspecialchars($_POST['UserName']);
			echo '<ul><li>Login Successful</li></ul>';
			header("Location: /index.php");
			
	} else {
			session_unset();
			$_SESSION['username'] = 'limited';
			$_SESSION['password'] = 'pass';
			//echo '<ul>' . $user_results . '</ul>';
			echo '<ul><li>Login Unsuccessful, Default Setting Applied</li></ul>';
			
       
        // Set the name and username fields to empty strings so they don't
        // get automatically repopulate
        $UserName = '';
		$Password = '';
    }
}

    }
}
?>




<form action="" method="post">
    <label for="UserName">UserName</label><br/>
    <input type="text" name="UserName"/><br/>

    <label for="Password">Password</label><br/>
    <input type="password" name="Password"/><br/>
	<input type="submit" value="Login"/>
</form>

<form action="/index.php">
<input type="submit" value="Go to Main Page">
</form>

</body>
</html>
