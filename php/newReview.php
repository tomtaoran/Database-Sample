<!DOCTYPE html>
<?php
// Start the session
session_start();
if(isset($_SESSION['username']) && $_SESSION['username']==root) {
	//echo "Your session is running " . $_SESSION['username']; //for debug use
	//connection
	$conn = mysqli_connect('localhost', $_SESSION['username'], $_SESSION["password"]) or die(mysqli_error($conn));
	mysqli_select_db($conn, 'steamdatabase') or die(mysqli_error($conn));
}else{
	header( 'Location: /index.php' ) ;
	
}
?>

<html>
<head>
<title>Write a Review</title>
</head>
<body>

<h1>Composite Your Review</h1>

<?php
   // Only execute if we're receiving a post
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
   
  // This will be the string we collect errors in
    $errors = '';

    // Make sure the UserName field is filled
	$UserName = $_SESSION['loginname'];
    if (empty($UserName)) $errors .= '<li>UserName is required</li>';

    // Make sure the GameName field is filled
	$GameName = htmlspecialchars($_POST['GameName']);
    if (empty($GameName)) $errors .= '<li>Game Name is required</li>';

    // Make sure the Rating field is filled
	$Rating = htmlspecialchars($_POST['Rating']);
    if (empty($Rating)) $errors .= '<li>Rating is required</li>';

    // If we have any errors at this point, stop here and show them
    if (!empty($errors)) {
        echo '<ul>' . $errors . '</ul>';

    // Otherwise, begin the Review creation process
    } else {
        // First, check and see if the Review already exists
	$review_results = mysqli_query($conn, "SELECT UserID FROM Review WHERE UserID =(SELECT UserID From Users Where UserName = '" . $UserName."')  AND GameID = (SELECT GameID from Game Where Name = '" . $GameName."')")  or die("Query Failed At Duplication Checking");

    // We don't care what the result is
    // If there is one, that means the Review is taken
    if ($review_results) {
    	if (mysqli_fetch_array($review_results)) {
        	echo '<ul><li>Review already exists!</li></ul>';
	}
    // If no duplicates are found, go ahead and create the new Review
       else {
		$UserName=mysqli_real_escape_string($conn,$UserName);
		$GameName = mysqli_real_escape_string($conn,$GameName);
		$Rating = mysqli_real_escape_string($conn,$Rating);
		mysqli_query($conn,"CALL newReview('" . $UserName . "',
						 '" . $GameName . "', '" . $Rating . "')") or die(‘error_storeproc’);
		
        // Show a success message
        echo '<ul><li>Your Review is now online, thank you for your help!</li></ul>';

        // Set the name and username fields to empty strings so they don't
        // get automatically repopulated
        $UserName = '';
        $GameName = '';
		$Rating = '';
    }
}

    }
}
?>



<form action="" method="post">
    <label for="GameName">Game Name</label><br/>
    <select name="GameName"> 
	<option value="">====Select====</option> 
	<?php
    $result = mysqli_query($conn, "SELECT DISTINCT Name FROM Game") or die("Query Failed Here");
    while ($row = mysqli_fetch_array($result)) {	
	 $temp = $row['Name'];
     echo '<option value="'.$temp.'">'.$temp.'</option>';
    }
	?>
	</select><br/>

    <label for="Rating">Rating</label><br/>
    <input type="text" name="Rating"/><br/>
	
    <input type="submit" value="Register"/>
</form>

<form action="/index.php">
<input type="submit" value="Go to Main Page">
</form>

</body>
</html>