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
<title>Register an Achievment</title>
</head>
<body>

<h1>Register</h1>

<?php
   // Only execute if we're receiving a post
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
   
  // This will be the string we collect errors in
    $errors = '';

    // Make sure the Name field is filled
	$Name = htmlspecialchars($_POST['Name']);
    if (empty($Name)) $errors .= '<li>Name is required</li>';

    // Make sure the Points field is filled
	$Points = htmlspecialchars($_POST['Points']);
    if (empty($Points)) $errors .= '<li>Points is required</li>';
	
	 // Make sure the Rarity field is filled
	$Rarity = htmlspecialchars($_POST['Rarity']);
    if (empty($Rarity)) $errors .= '<li>Rarity is required</li>';
	
	 // Make sure the GameName field is filled
	$GameName = htmlspecialchars($_POST['GameName']);
    if (empty($GameName)) $errors .= '<li>Game Name is required</li>';

    // If we have any errors at this point, stop here and show them
    if (!empty($errors)) {
        echo '<ul>' . $errors . '</ul>';

    // Otherwise, begin the user creation process
    } else {
        // First, check and see if the Achievement already exists
	$achiev_results = mysqli_query($conn, "SELECT AchievID FROM Achievment WHERE Name = '" . $Name . "'") or die("Query Failed at duplication checking");

    // We don't care what the result is
    // If there is one, that means the company is taken
    if ($achiev_results) {
    	if (mysqli_fetch_array($achiev_results)) {
        	echo '<ul><li>Achievement already exists!</li></ul>';
	}
    // If no duplicates are found, go ahead and create the new company
       else {
		$Name = mysqli_real_escape_string($conn,$Name);
		$Points = mysqli_real_escape_string($conn,$Points);
		$Rarity = mysqli_real_escape_string($conn,$Rarity);
		$GameName = mysqli_real_escape_string($conn,$GameName);
		mysqli_query($conn, "CALL newAchievment('" . $Name . "', '" . $Points . "','" . $Rarity . "','" . $GameName . "')") or die(‘error_storeproc’);
		
		
		
        // Show a success message
        echo '<ul><li>Achievement Successfully Created!</li></ul>';

        // Set the name and username fields to empty strings so they don't
        // get automatically repopulated
        $AchievID = '';
        $Name = '';
		$Points = '';
		$Rarity = '';
		$GameName = '';
    }
}

    }
}
?>



<form action="" method="post">
    <label for="Name">Name</label><br/>
    <input type="text" name="Name"/><br/>

    <label for="Points">Points</label><br/>
    <input type="text" name="Points"/><br/>
	
    <label for="Rarity">Rarity</label><br/>
    <input type="text" name="Rarity"/><br/>
	
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
	
    <input type="submit" value="Register"/>
	
</form>

<form action="/index.php">
<input type="submit" value="Go to Main Page">
</form>

</body>
</html>