<?php 
	include 'inc/header.php';
	include 'lib/user.php';
	Session::checkLogin();
?>
<?php 
	$user = new User();
	if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
		$userLog = $user->userLogin($_POST);
	}
?>
	
		<div class="panel panel-default">
			<div class="panel-heading">
				<h2>User login</h2>
			</div>
			<div class="panel-body">
				<div style="max-width: 600px;margin: 0 auto;">
<?php
	if (isset($userLog)) {
		echo $userLog;
	}
?>				
	
					<form action="" method="post">
					<div class="form-group">
						<label for="email">Email Address:</label>
						<input type="text" id="email" name="email" class="form-control" required="">
					</div>
					
					<div class="form-group">
						<label for="password">Password:</label>
						<input type="password" id="password" name="password" class="form-control" required="">
					</div>
					<button type="submit" name="login" class="btn btn-info">Login</button>
					</form>
				</div>
				
			</div>
		</div>
	

<?php include 'inc/footer.php'; ?>		