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
<title>Register A New Game</title>
</head>
<body>

<h1>Register A New Game</h1>

<?php
   // Only execute if we're receiving a post
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
   
  // This will be the string we collect errors in
    $errors = '';
	
    // Make sure the name field is filled
	$Name = htmlspecialchars($_POST['Name']);
    if (empty($Name)) $errors .= '<li>Name is required</li>';

    // Make sure the location field is filled
	$Price = htmlspecialchars($_POST['Price']);
    if (empty($Price)) $errors .= '<li>Price is required</li>';

	// Make sure the name field is filled
	$CreatorName = htmlspecialchars($_POST['CreatorName']);
    if (empty($CreatorName)) $errors .= '<li>Creator Name is required</li>';

	// Make sure the name field is filled
	$PublisherName = htmlspecialchars($_POST['PublisherName']);
    if (empty($PublisherName)) $errors .= '<li>Publisher Name is required</li>';

	
    // Make sure the date field is filled
	$GameType = htmlspecialchars($_POST['GameType']);
	$ReleaseDate = htmlspecialchars($_POST['ReleaseDate']);

    // If we have any errors at this point, stop here and show them
    if (!empty($errors)) {
        echo '<ul>' . $errors . '</ul>';

    // Otherwise, begin the user creation process
    } else {
        // First, check and see if the company already exists
	$game_results = mysqli_query($conn, "SELECT Name FROM Game WHERE Name= '" . $Name."'") or die("Checking duplication on Game Failed!");

    // We don't care what the result is
    // If there is one, that means the company is taken
    if ($game_results) {
    	if (mysqli_fetch_array($game_results)) {
        	echo '<ul><li>Game already exists!</li></ul>';
	}
    // If no duplicates are found, go ahead and create the new company
       else {
		$Name = mysqli_real_escape_string($conn,$Name);
		$Price = mysqli_real_escape_string($conn,$Price);
		$GameType = mysqli_real_escape_string($conn,$GameType);
		$ReleaseDate = mysqli_real_escape_string($conn,$ReleaseDate);
		$CreatorName = mysqli_real_escape_string($conn,$CreatorName);
		$PublisherName = mysqli_real_escape_string($conn,$PublisherName);
		mysqli_query($conn,"CALL newGame('" . $Name . "',
							'" . $Price . "','" . $GameType . "','" . $ReleaseDate . "','" . $CreatorName . "','" . $PublisherName . "')") or die(‘error_storeproc’);
		
        // Show a success message
        echo '<ul><li>New Game Created, thanks!</li></ul>';

        // Set the name and username fields to empty strings so they don't
        // get automatically repopulated
		$Name = '';
		$Price = '';
		$GameType = '';
		$ReleaseDate = '';
		$CreatorName = '';
		$PublisherName = '';
    }
}

    }
}
?>




<form action="" method="post">
    <label for="Name">Name</label><br/>
    <input type="text" name="Name"/><br/>
	
	<label for="Price">Price</label><br/>
    <input type="number" step=0.01 name="Price"/><br/>
	
	<label for="GameType">Game Type</label><br/>
	<select name="GameType"> 
	<option value="">====Select====</option> 
	<?php
    $result = mysqli_query($conn, "SELECT DISTINCT GameType FROM Game") or die("Query Failed Here");
    while ($row = mysqli_fetch_array($result)) {	
	 $temp = $row['GameType'];
     echo '<option value="'.$temp.'">'.$temp.'</option>';
    }
	?>
	</select><br/>
	
	<!--Due to the fact that IE11 and FireFox does not support data type: date, we are using scripts from certified website-->
	<!--Author: Jqueryui.com
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<link rel="stylesheet" href="/resources/demos/style.css">
	<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<script>  $( function() { $( "#datepicker" ).datepicker();  } ); </script>
	 This set of CODE is currently NOT OPEN as stroed Procedure result in fatal error-->
    <label for="ReleaseDate">Release Date</label><br/>
    <input type="date" name="ReleaseDate"/><br/>
	

	<label for="CreatorName">Creator Name</label><br/>
	<select name="CreatorName"> 
	<option value="">Select Below</option> 
	<?php
    $result = mysqli_query($conn, "SELECT DISTINCT CreatorName FROM Game") or die("Query Failed at Creator Name");
    while ($row = mysqli_fetch_array($result)) {	
	 $temp = $row['CreatorName'];
     echo '<option value="'.$temp.'">'.$temp.'</option>';
    }
	?>
	</select><br/>
	
	<label for="PublisherName">Publisher Name</label><br/>
    <select name="PublisherName"> 
	<option value="">Select Below</option> 
	<?php
    $result = mysqli_query($conn, "SELECT DISTINCT PublisherName FROM Game") or die("Query Failed at Publisher Name");
    while ($row = mysqli_fetch_array($result)) {	
	 $temp = $row['PublisherName'];
     echo '<option value="'.$temp.'">'.$temp.'</option>';
    }
	?>
	</select><br/>
	
    <input type="submit" value="Register"/>
</form>

<!---Editor Note: IN ONE PHP ONLY ONE $CONN NEEDED!-->

<form action="/index.php">
<input type="submit" value="Go to Main Page">
</form>

</body>
</html>