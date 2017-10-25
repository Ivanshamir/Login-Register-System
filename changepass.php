<?php 
	include 'lib/User.php';
	include 'inc/header.php';
	Session::checkSession();
?>


<?php 
	if (isset($_GET['id'])) {
	 	$userid = (int)$_GET['id']; 
	 	$sesid = Session::get("id");
		if ($userid != $sesid) {
			header("Location: index.php");
		}
	 }
	 $user = new User(); 
		if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['updatepassword'])) {
		$updatepass = $user->userUpdatePassword($userid, $_POST);
		}
   
?>
		<div class="panel panel-default">
			<div class="panel-heading">
				<h2>Change Password <span class="pull-right"><a class="btn btn-primary" href="profile.php?id=<?php echo $userid; ?>">Back</a></span></h2>
			</div>
			<div class="panel-body">
				<div style="max-width: 600px;margin: 0 auto">
<?php
	if (isset($updatepass)) {
		echo $updatepass;
	}

 ?>
				<form action="" method="post">
					<div class="form-group">
						<label for="old_password">Old Password:</label>
						<input type="password" id="old_password" name="old_password" class="form-control"/>
					</div>
					<div class="form-group">
						<label for="password">New Password:</label>
						<input type="password" id="password" name="password" class="form-control" />
					</div>
					<button type="submit" name="updatepassword" class="btn btn-primary">Update</button>
					</form>
				</div>
				
			</div>
		</div>
	

<?php include 'inc/footer.php'; ?>		