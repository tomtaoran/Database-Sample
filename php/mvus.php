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
<title>Most Valuable Users</title>
</head>
<body>

<h1>See the Most Valuable Users!</h1>

<?php
   // Only execute if we're receiving a post
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
   
  // This will be the string we collect errors in
    $errors = '';
	// Fill or don't fill the Wishlist field, doesn't matter, query will run
	$Wishlist = htmlspecialchars($_POST['Wishlist']);
			
			// If we have any errors at this point, stop here and show them
			if (!empty($errors)) {
				echo '<ul><li>' . $errors . '</li></ul>';

			// Otherwise, begin the query
			} else {

			//get the game type and insert it
			$Wishlist=mysqli_real_escape_string($conn,$Wishlist);
			
			if(isset($_SESSION['username']) && $_SESSION['username']==root) {
				$result = mysqli_query($conn,"CALL getMVUs()") or die("Error_SQL Query for getMVUs Failed"); 
				while ($row = mysqli_fetch_array($result)) {	
					$temp = $row['UserName'];
					$tempval = $row['Worth'];
					$temppoints = $row['TotalPoints'];
					echo '<li>' .$temp. ' is worth $' .$tempval. ' and has a total point count of ' .$temppoints. '</li>';
				}
				
			}
			
			?>
			<?php
			// Set the name and username fields to empty strings so they don't
			// get automatically repopulated
			$GameType = '';
			}

		}
		?>

		<form action="" method="post">
		
		</select><br/>
		<input type="submit" value="Get Most Valuable Users"/>
		</form>
		
	<form action="/access_feature6.php">
	<input type="submit" value="Go back">
	</form>
	
	</body>
	</html>