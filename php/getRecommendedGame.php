<!DOCTYPE html>

<?php
// Start the session
session_start();
if(isset($_SESSION['username']) && $_SESSION['username']==root) {
	//echo "Your session is running on permission" . $_SESSION['username']; //for debug use
	//echo "Your session is running on account " . $_SESSION['loginname']; //for debug use
	//connection
	$conn = mysqli_connect('localhost', $_SESSION['username'], $_SESSION["password"]) or die(mysqli_error($conn));
	mysqli_select_db($conn, 'steamdatabase') or die(mysqli_error($conn));
}else{
	header( 'Location: /index.php' ) ;
	
}
?>

<html>
<head>
<title>Recommend A Game</title>
</head>
<body>

<h1>Recommend A Game</h1>

<?php
   // Only execute if we're receiving a post
   if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$result = mysqli_query($conn,"CALL getRecommendedGame('" . $_SESSION['loginname'] . "')") or die(‘error_storeproc’);
		$row = mysqli_fetch_array($result);
		$temp = $row[0];
		
        // Show a success message
		echo '<ul><li>Recommended Game: "'.$temp.'"</li></ul>';

        // Set the name and username fields to empty strings so they don't
        // get automatically repopulated
		
    }
?>




<form action="" method="post">

    <input type="submit" value="Get Recommended"/>
</form>

<!---Editor Note: IN ONE PHP ONLY ONE $CONN NEEDED!-->

<form action="/index.php">
<input type="submit" value="Go to Main Page">
</form>

</body>
</html>