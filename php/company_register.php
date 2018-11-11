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

<h1>Register A Company</h1>

<?php
   // Only execute if we're receiving a post
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
   
  // This will be the string we collect errors in
    $errors = '';

    // Make sure the name field is filled
	$Name = htmlspecialchars($_POST['Name']);
    if (empty($Name)) $errors .= '<li>Name is required</li>';

    // Make sure the location field is filled
	$Location = htmlspecialchars($_POST['Location']);
    if (empty($Location)) $errors .= '<li>Location is required</li>';

    // Make sure the date field is filled
	$Date = htmlspecialchars($_POST['Date']);
    if (empty($Date)) $errors .= '<li>Date is required</li>';

    // If we have any errors at this point, stop here and show them
    if (!empty($errors)) {
        echo '<ul>' . $errors . '</ul>';

    // Otherwise, begin the user creation process
    } else {
        // First, check and see if the company already exists
	$company_results = mysqli_query($conn, "SELECT Name FROM Company WHERE Name = '" . $Name."'") or die("Query Failed");

    // We don't care what the result is
    // If there is one, that means the company is taken
    if ($company_results) {
    	if (mysqli_fetch_array($company_results)) {
        	echo '<ul><li>Company already exists!</li></ul>';
	}
    // If no duplicates are found, go ahead and create the new company
       else {
		$Name=mysqli_real_escape_string($conn,$Name);
		$Location = mysqli_real_escape_string($conn,$Location);
		$Date = mysqli_real_escape_string($conn,$Date);
		mysqli_query($conn,"CALL registerCompany('" . $Name . "',
						 '" . $Location . "', '" . $Date . "')") or die(‘error_storeproc’);
		
        // Show a success message
        echo '<ul><li>Compnay Registration successful!</li></ul>';

        // Set the name and username fields to empty strings so they don't
        // get automatically repopulated
        $Name = '';
        $Location = '';
		$Date = '';
    }
}

    }
}
?>



<form action="" method="post">
    <label for="Name">Name</label><br/>
    <input type="text" name="Name"/><br/>

    <label for="Location">Location</label><br/>
    <input type="text" name="Location"/><br/>

    <label for="Date">Date</label><br/>
    <input type="date" name="Date"/><br/>
	
    <input type="submit" value="Register"/>
</form>

<form action="/index.php">
<input type="submit" value="Go to Main Page">
</form>

</body>
</html>