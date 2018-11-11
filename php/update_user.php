<!DOCTYPE html>

<?php
// Start the session
session_start();
if(isset($_SESSION['username']) && $_SESSION['username']==root) {
//	echo "Your session is running " . $_SESSION['username']; //for debug use
//	echo "Your session is running on account " . $_SESSION['loginname']; //for debug use
	//connection
	$conn = mysqli_connect('localhost', $_SESSION['username'], $_SESSION["password"]) or die(mysqli_error($conn));
	mysqli_select_db($conn, 'steamdatabase') or die(mysqli_error($conn));
}else{
	header( 'Location: /index.php' ) ;
	
}
?>
<html>
<head>
<title>User Information Update</title>
</head>
<body>

<h1>User Information Update</h1>
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = htmlspecialchars($_POST['username']);
	    if (empty($username)) $errors .= '<li>User Name is required</li>';
    $password = htmlspecialchars($_POST['password']);
	    if (empty($password)) $errors .= '<li>Password is required</li>';
    


    if (empty($username)) {
        echo '<ul><li>You must include user name!</li></ul>';
    } else {
		$username=mysqli_real_escape_string($conn,$username);
		$password = mysqli_real_escape_string($conn,$password);
        $result = mysqli_query($conn, "CALL updateUser('" . $_SESSION['loginname'] . "',
						 '" . $username . "','" . $password . "')") or die(‘error_Update_Information’);;
        $row = mysqli_fetch_array($result);
        $status = $row[0];

        echo '<ul><li>' . 'User Information Successfully Updated' . '</li></ul>';
    }
}
?>

<form action="" method="post">
	<label for="username">New User Name</label><br/>
    <input type="text" name="username"/><br/>
    <label for="password">New Password</label><br/>
    <input type="text" name="password"/><br/>
    
    <input type="submit" value="Update"/><br/>
</form>

<form action="/index.php">
<input type="submit" value="Go to Main Page">
</form>

</body>
</html>