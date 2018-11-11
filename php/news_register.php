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
<title>News Articles! (No Fake News)</title>
</head>
<body>

<h1>All your news are belong to us</h1>

<?php
   // Only execute if we're receiving a post
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
   
  // This will be the string we collect errors in
    $errors = '';

    // Make sure the name field is filled
	$NewsID = htmlspecialchars($_POST['NewsID']);
    if (empty($Name)) $errors .= '<li>NewsID is required</li>';

    // Make sure the location field is filled
	$Title = htmlspecialchars($_POST['Title']);
    if (empty($Location)) $errors .= '<li>Title is required</li>';

    // Make sure the date field is filled
	$Date = htmlspecialchars($_POST['Date']);
    if (empty($Date)) $errors .= '<li>Date is required</li>';
	
	// make sure the GameID field is filled
	$GameID = htmlspecialchars($_POST['GameID']);
    if (empty($Date)) $errors .= '<li>GameID is required</li>';
	
	// make sure the company name field is filled
	$Comp_name = htmlspecialchars($_POST['Company Name']);
    if (empty($Date)) $errors .= '<li>Company Name is required</li>';

    // If we have any errors at this point, stop here and show them
    if (!empty($errors)) {
        echo '<ul>' . $errors . '</ul>';

    // Otherwise, begin the user creation process
    } else {
        // First, check and see if the news already exists
	$news_results = mysqli_query($conn, "SELECT NewsID FROM News WHERE NewsID = '" . $NewsID."'") or die("Query Failed");

    // We don't care what the result is
    // If there is one, that means the company is taken
    if ($news_results) {
    	if (mysqli_fetch_array($news_results)) {
        	echo '<ul><li>The News already exists!</li></ul>';
	}
    // If no duplicates are found, go ahead and create the new company
       else {
		$NewsID=mysqli_real_escape_string($conn,$NewsID);
		$Title = mysqli_real_escape_string($conn,$Title);
		$Date = mysqli_real_escape_string($conn,$Date);
		$GameID = mysqli_real_escape_string($conn,$GameID);
		$Comp_name = mysqli_real_escape_string($conn,$Comp_name);
		mysqli_query($conn,"CALL registerCompany('" . $NewsID . "',
						 '" . $Title . "', '" . $Date . "', '" . $GameID . "', '" . $Comp_name . "')") or die(‘error_storeproc’);
		
        // Show a success message
        echo '<ul><li>Registration successful!</li></ul>';

        // Set the name and username fields to empty strings so they don't
        // get automatically repopulated
        $NewsID = '';
        $Title = '';
		$Date = '';
		$GameID = '';
		$Comp_name = '';
    }
}

    }
}
?>



<form action="" method="post">
    <label for="NewsID">NewsID</label><br/>
    <input type="text" name="NewsID"/><br/>

    <label for="Title">Title</label><br/>
    <input type="text" name="Title"/><br/>

    <label for="Date">Date</label><br/>
    <input type="date" name="Date"/><br/>
	
	<label for="GameID">GameID</label><br/>
    <input type="text" name="GameID"/><br/>
	
	<label for="Comp_name">Company Name</label><br/>
    <input type="text" name="Comp_name"/><br/>
	
    <input type="submit" value="Register"/>
</form>
<form action="/index.php">
<input type="submit" value="Go to Main Page">
</form>

</body>
</html>