<!DOCTYPE html>
<?php
// Open a connection to the database
// (display an error if the connection fails)
$conn = mysqli_connect('localhost', 'root', 'oagach4a') or die(mysqli_error($conn));
mysqli_select_db($conn, 'steamdatabase') or die(mysqli_error($conn));
?>

<html>
<head>
<title>See What's Popular!</title>
</head>
<body>

<ul>
	<li class="user"><a href="/playersWithMostHours.php">Players with most hours played</a></li>
	<li class="user"><a href="/topPurchasers.php">Top Purchasers</a></li>
	<li class="user"><a href="/popularGames.php">Popular Games</a></li>
	<li class="user"><a href="/mvus.php">Most Valuable Users</a></li>
</ul>

<form action="/index.php">
<input type="submit" value="Go to Main Page">
</form>
</body>
</html>