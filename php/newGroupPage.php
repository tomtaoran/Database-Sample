<!DOCTYPE html>

<?php
// Start the session
session_start();
if(isset($_SESSION['username']) && $_SESSION['username']==root) {
//	echo "Your session is running " . $_SESSION['username']; //for debug use
	//connection
	$conn = mysqli_connect('localhost', $_SESSION['username'], $_SESSION["password"]) or die(mysqli_error($conn));
	mysqli_select_db($conn, 'steamdatabase') or die(mysqli_error($conn));
}else{
	header( 'Location: /index.php' ) ;
	
}
?>

<html>
<head>
<title>Register a Company</title>
</head>
<body>

<h1>Register</h1>

<?php
   // Only execute if we're receiving a post
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
   
  // This will be the string we collect errors in
    $errors = '';

    // Make sure the name field is filled
	$Name = htmlspecialchars($_POST['Name']);
    if (empty($Name)) $errors .= '<li>Group Name is required</li>';


    // Make sure the creator field is filled
	$creator = htmlspecialchars($_POST['creator']);
    if (empty($creator)) $errors .= '<li>Creator Name is required</li>';

    // If we have any errors at this point, stop here and show them
    if (!empty($errors)) {
        echo '<ul>' . $errors . '</ul>';

    // Otherwise, begin the user creation process
    } else {
        // First, check and see if the company already exists
	$group_results = mysqli_query($conn, "SELECT Name FROM Groups WHERE Name = '" . $Name."'") or die("Query Failed");

    // We don't care what the result is
    // If there is one, that means the company is taken
    if ($group_results) {
    	if (mysqli_fetch_array($group_results)) {
        	echo '<ul><li>Group already exists!</li></ul>';
	}
    // If no duplicates are found, go ahead and create the new company
       else {
		$Name=mysqli_real_escape_string($conn,$Name);
		$creator = mysqli_real_escape_string($conn,$creator);
		mysqli_query($conn,"CALL newGroup('" . $Name . "',
						 '" . $creator . "')") or die(‘error_creating_your_group’);
		
        // Show a success message
        echo '<ul><li>Your new group is now live!</li></ul>';

        // Set the name and username fields to empty strings so they don't
        // get automatically repopulated
        $Name = '';
		$creator = '';
    }
}

    }
}
?>



<form action="" method="post">
  
	<label for="Name">Group Name</label><br/>
    <input type="text" name="Name"/><br/>

    <label for="creator">Creator</label><br/>
    <input type="text" name="creator"/><br/>
	
    <input type="submit" value="Register"/>
</form>

<form action="/index.php">
<input type="submit" value="Go to Main Page">
</form>

</body>
</html>