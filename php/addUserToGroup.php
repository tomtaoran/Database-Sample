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
<title>Add User To Group</title>
</head>
<body>

<h1>Add User To Group</h1>

<?php
   // Only execute if we're receiving a post
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	   
	  // This will be the string we collect errors in
		$errors = '';

		// Make sure the UserName field is filled
		$UserName = htmlspecialchars($_POST['UserName']);
		if (empty($UserName)) $errors .= '<li>UserName is required</li>';

		// Make sure the GameName field is filled
		$GroupName = htmlspecialchars($_POST['GroupName']);
		if (empty($GroupName)) $errors .= '<li>Group Name is required</li>';

		// If we have any errors at this point, stop here and show them
		if (!empty($errors)) {
			echo '<ul>' . $errors . '</ul>';

		// Otherwise, begin the user creation process
		} else {
			// First, check and see if the WishList already exists
			//$add_results = mysqli_query($conn, "SELECT UserID FROM WishList WHERE UserID =(SELECT UserID From Users Where UserName = '" . $UserName."')  AND GameID = (SELECT GameID from Game Where Name = '" . $GameName."')") or die("Query Failed At Duplication Checking");
			//$wish_results2 = mysqli_query($conn, "SELECT UserID FROM WishList WHERE UserID =(SELECT UserID From UserOwns Where UserName = '" . $UserName."')  AND GameID = (SELECT GameID from UserOwns Where Name = '" . $GameName."')") or die("Query Failed At Duplication Checking");

			// We don't care what the result is
			// If there is one, that means the WishList is taken
			//if ($wish_results) {
				//if (mysqli_fetch_array($wish_results)) {
				//	echo '<ul><li>This item is already on your Wish List.</li></ul>';
				//} 
				//else if (mysqli_fetch_array($wish_results2)) {
				//	echo '<ul><li>You already own this Game.</li></ul>';
				//}
			// If no duplicates are found, go ahead and create the new WishList
				
					$UserName=mysqli_real_escape_string($conn,$UserName);
					$GroupName = mysqli_real_escape_string($conn,$GroupName);
					mysqli_query($conn,"CALL addUserToGroup('" . $GroupName . "',
									 '" . $UserName . "', '" . $_SESSION['loginname'] . "')") or die(‘error_storeproc’);
					
					// Show a success message
					echo '<ul><li>User added!</li></ul>';

					// Set the name and username fields to empty strings so they don't
					// get automatically repopulated
					$UserName = '';
					$GameName = '';
				
			//}

		}
	}
?>



<form action="" method="post">
	<label for="GroupName">Group Name</label><br/>
			<select name="GroupName"> 
				<option value="">Select Below</option> 
				<?php
				$result = mysqli_query($conn, "SELECT DISTINCT Name FROM Groups") or die("Query Failed at GameType");
				while ($row = mysqli_fetch_array($result)) {	
				 $temp = $row['Name'];
				 echo '<option value="'.$temp.'">'.$temp.'</option>';
				}
				?>
				</select><br/>

	<label for="UserName">User Name</label><br/>
    <input type="text" name="UserName"/><br/>
	
    <input type="submit" value="Add User"/>
</form>

<form action="/index.php">
<input type="submit" value="Go to Main Page">
</form>

</body>
</html>