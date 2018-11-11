<?php
// Start the session
session_start();

?>

<?php
if(isset($_SESSION['username']) && $_SESSION['username']==root) {
//	echo "Your session is running on permission" . $_SESSION['username']; //for debug use
//	echo "Your session is running on account " . $_SESSION['loginname']; //for debug use
	//connection
	$conn = mysqli_connect('localhost', $_SESSION['username'], $_SESSION['password']) or die(mysqli_error($conn));
	mysqli_select_db($conn, 'steamdatabase') or die(mysqli_error($conn));
}else{
	$_SESSION['username'] = 'limted';
	$_SESSION['password'] = 'pass';
//	echo "Session variables are set."; //for debug use
//	echo "Your session is running " . $_SESSION['username']; //for debug use
	//the following few lines are for HIDING links that are irralavent to General Public
	?>
	<style type="text/css"> .user{
	display:none;
	}
	</style>
	<?php
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Welcome!</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>

<h1>Welcome to SteamDatabase!</h1>

<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="#">SteamDatabase</a>
    </div>
	<div class="user">
    <ul class="nav navbar-nav">
      <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#">About You <span class="caret"></span></a>
        <ul class="dropdown-menu">
		  <li class="user"><a href="/newReview.php">Post Your Review Here</a></li>
		  <li class="user"><a href="/newWishList.php">Create A WishList</a></li>
		  <li class="user"><a href="/news_register.php">Be A News Reporter</a></li>
		  <li class="user"><a href="/getWishList.php">View Your Wish List</a></li>
		  <li role="separator" class="divider"></li>
          <li class="user"><a href="/update_user.php">User Information Update</a></li>
        </ul>
      </li>
	  <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#">About Games <span class="caret"></span></a>
        <ul class="dropdown-menu">
          <li class="user"><a href="/newgame.php">Register A New Game</a></li>
		  <li role="separator" class="divider"></li>
		  <li class="user"><a href="/newAchievement.php">New Achievement For A Game? Here!</a></li>
		  <li class="user"><a href="/update_achievement.php">Update Achievement</a></li>
        </ul>
      </li>
	  <!--In future, when we can add people to group, make this a drop-down menu-->
	  <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#">Group<span class="caret"></span></a>
        <ul class="dropdown-menu">
			<li class="user"><a href="/newGroupPage.php">Create a Group</a></li>
			<li class="user"><a href="/addUserToGroup.php">Add User to Group</a></li>
			<li class="user"><a href="/removeFromGroup.php">Remove User from Group</a></li>
        </ul>
      </li>
	  <li class="user"><a href="/company_register.php">Register A Company</a></li>
	  <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#">Cool Features <span class="caret"></span></a>
        <ul class="dropdown-menu">
		<li class="user"><a href="/getRecommendedGame.php">Game We Recommend You To Give It A Shot</a></li>
		<li class="user"><a href="/access_feature6.php">Statistics</a></li>
	   </ul>
      </li>
    </ul>
	</div>
    <ul class="nav navbar-nav navbar-right">
	<?php
	
	if(!isset($_SESSION['loginname']) ||$_SESSION['username']==limited) {
	echo '<li><a href="/user_register.php"><span class="glyphicon glyphicon-user"></span> Sign Up</a></li>'; 
	echo '<li class="general"><a href="/login.php"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>'; 
	}
	if($_SESSION['username']==root){
	echo '<form action="" method="post">';
	echo '<button type="submit" class="btn btn-danger navbar-btn">Log-Out</button>';
    echo '</form>';
	}
	?>
	
	<?php 
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		session_unset();
		session_destroy();
		header( 'Location: /index.php' ) ;
	}
	
	?>
    </ul>
  </div>
</nav>

<ul>
	<!--old links used to go here -->
</ul>

		
<form class="user">
    <label for="Game">Top Three Most Popular Games</label><br/>
	<ul>
	<?php
	if(isset($_SESSION['username']) && $_SESSION['username']==root) {
    $result = mysqli_query($conn,"CALL getTopNumberedGames(3)") or die("Query Failed Here");
	
    while ($row = mysqli_fetch_array($result)) {	
	 $temp = $row['Name'];
     echo '<li>'.$temp.'</li>';
    }
	
	}
	?>
	</ul><br/>
	<!--I don't know WHY, BUT W.T. ___ should I RECONNECT MYSQL? Who designed this stupid feature in MYSQL develop team? -->
	<?php 
	mysqli_close($conn);
	$conn = mysqli_connect('localhost', $_SESSION['username'], $_SESSION["password"]) or die(mysqli_error($conn));
	mysqli_select_db($conn, 'steamdatabase') or die(mysqli_error($conn));
	?>
	<br/>
	<label for="Game">Top Three Most Popular Game Types</label><br/>
	<ul>
	<?php
	if(isset($_SESSION['username']) && $_SESSION['username']==root) {
    $result_2 = mysqli_query($conn,"CALL getTopNumberedGameTypes(3)") or die("Query Failed Here");
	
    while ($row = mysqli_fetch_array($result_2)) {	
	 $temp = $row['GameType'];
     echo '<li>'.$temp.'</li>';
    }
	}
	?>
	</ul><br/>
   	
</form>





</body>
</html>