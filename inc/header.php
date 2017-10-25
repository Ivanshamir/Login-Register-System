<?php
	$filepath = realpath(dirname(__FILE__));
	include_once $filepath.'/../lib/Session.php';
	Session::init();
?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Learming PHP & PDO</title>
	<link rel="stylesheet" href="inc/bootstrap.min.css">
	<script src="inc/jquery.min.js"></script>
	<script src="inc/bootstrap.min.js"></script>
</head>
<?php 
if (isset($_GET['action']) && $_GET['action'] == "logout") {
	Session::destroy();
}

?>

<body>
	<div class="container">
		<nav class="navbar navbar-default">
			<div class="container-fluid">
				<div class="navbar-header">
					<a  class="navbar-brand" href="index.php">Login Register system</a>
				</div>
				<ul class="nav navbar-nav pull-right">
					<?php
						$id = Session::get("id");
						$usrlogin = Session::get("login");
						if ($usrlogin == true) {
					?>
						<li><a href="index.php">Home</a></li>
						<li><a href="profile.php?id=<?php echo $id; ?>">Profile</a></li>
						<li><a href="?action=logout">Log Out</a></li>
						<?php }else{ ?>
						<li><a href="login.php">Log In</a></li>
						<li><a href="register.php">Register</a></li>
						<?php } ?>
				</ul>
			</div>
		</nav>