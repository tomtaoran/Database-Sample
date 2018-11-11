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
<title>Gaming Enthusiasts</title>
</head>
<body>

<h1>Gaming Enthusiasts</h1>

<?php
   // Only execute if we're receiving a post
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
   
  // This will be the string we collect errors in
    $errors = '';
	// Fill or don't fill the GameType field, doesn't matter, query will run
	$GameType = htmlspecialchars($_POST['GameType']);
			
			// If we have any errors at this point, stop here and show them
			if (!empty($errors)) {
				echo '<ul><li>' . $errors . '</li></ul>';

			// Otherwise, begin the query
			} else {

			//get the game type and insert it
			$GameType=mysqli_real_escape_string($conn,$GameType);
			
			if(isset($_SESSION['username']) && $_SESSION['username']==root) {
				$result = mysqli_query($conn,"CALL getTopHours('" . $GameType."')") or die("Error_SQL Query for getTopHours Failed"); 
				
				while ($row = mysqli_fetch_array($result)) {	
				 $temp = $row['User'];
				 $temphour = $row['THours'];
				 echo '<li>'.$temp.' with Total Hours: '.$temphour. '</li>';
				}
				
				}
			
			// BUG Code :
			//$result = "test";
			//$result = mysqli_query($conn,"CALL getTopHours('" . $GameType."')") or die("Error_SQL Query for getTopHours Failed"); 
			// The Above procedure call is BUG. 
			// echo on result to be completed
			//while ($row = mysqli_fetch_array($result)){
			//echo '<p><strong>' . $row[0] . '</strong>: ' $row[1] . '</strong>: ' $row[2] . '</strong>: ' $row[3] . '</p>';
			//}
			// Set the name and username fields to empty strings so they don't
			// get automatically repopulated
			$GameType = '';
			}

		}
		?>

		<form action="" method="post">
		<label for="GameType">Game Type</label><br/>
		<select name="GameType"> 
		<option value="">====Select====</option> 
		<?php 
		mysqli_close($conn);
		$conn = mysqli_connect('localhost', $_SESSION['username'], $_SESSION["password"]) or die(mysqli_error($conn));
		mysqli_select_db($conn, 'steamdatabase') or die(mysqli_error($conn));
		?>
		<?php
		$result = mysqli_query($conn, "SELECT DISTINCT GameType FROM Game") or die("Query Failed Here");
		
		if (empty($result)){
			$errors .= '<li>Empty Result is required</li>';
			echo '<ul>' . $errors . '</ul>';
		}
		while ($row = mysqli_fetch_array($result)) {
		$temp = $row['GameType'];
		echo '<option value="'.$temp.'">'.$temp.'</option>'; 
		}
		?>
		</select><br/>
		<input type="submit" value="Get the Result"/>
		</form>
		
	<form action="/access_feature6.php">
	<input type="submit" value="Go back">
	</form>
	
	</body>
	</html>