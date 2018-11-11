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
<title>Game Achievement Information Update</title>
</head>
<body>

<h1>Game Achievement Update</h1>
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = htmlspecialchars($_POST['name']);
	if (empty($name)) $errors .= '<li>Achievement Name is required</li>';
	$points = htmlspecialchars($_POST['points']);
	if (empty($points)) $errors .= '<li>Achievement points is required</li>';
	$rarity = htmlspecialchars($_POST['rarity']);
	if (empty($rarity)) $errors .= '<li>Rarity is required</li>';
    $GameName = htmlspecialchars($_POST['GameName']);
    if (empty($GameName)) $errors .= '<li>Game Name is required</li>';


    if (empty($name)) {
        echo '<ul><li>You must include Achievement Name!</li></ul>';
    } else {
		$name=mysqli_real_escape_string($conn,$name);
		$points=mysqli_real_escape_string($conn,$points);
		$rarity=mysqli_real_escape_string($conn,$rarity);
		$GameName = mysqli_real_escape_string($conn,$GameName);
        $result = mysqli_query($conn, "CALL updateAchievement('" . $name . "',
						 '" . $points . "','" . $rarity . "','" . $GameName . "')") or die(‘error_storeproc’);;
        $row = mysqli_fetch_array($result);
        $status = $row[0];

        echo '<ul><li>' . 'Achievement Updated'. '</li></ul>';
    }
}
?>

<form action="" method="post">
    <label for="name">Achievement Name</label><br/>
    <select name="name"> 
	<option value="">====Select====</option> 
	<?php
    $result = mysqli_query($conn, "SELECT DISTINCT Name FROM Achievment") or die("Query Failed Here");
    while ($row = mysqli_fetch_array($result)) {	
	 $temp = $row['Name'];
     echo '<option value="'.$temp.'">'.$temp.'</option>';
    }
	?>
	</select><br/>
	<label for="points">Achievement Points</label><br/>
    <input type="number" name="points"/><br/>
	<label for="rarity">Achievement Rarity</label><br/>
    <input type="text" name="rarity"/><br/>
    <label for="GameName">GameName</label><br/>
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
    
    <input type="submit" value="Update"/><br/>
</form>

<form action="/index.php">
<input type="submit" value="Go to Main Page">
</form>

</body>
</html>