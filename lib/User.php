<?php 
include_once 'Session.php';
include 'Database.php';


class User{
	private $db;
	function __construct(){
		$this->db = new Database();
	}

	public function userRegistration($data){
		$name = $data['name'];
		$username = $data['username'];
		$email = $data['email'];
		$password = $data['password'];
		$chk_email = $this->checkemail($email);
	

	if($name == "" OR $username == "" OR $email == "" OR $password == ""){
		$msg = "<div class='alert alert-danger'><strong>Error !</strong>Field must not be empty</div>";
		return $msg;
	}

	if(strlen($username)<3){
		$msg = "<div class='alert alert-danger'><strong>Error !</strong>Username is too short</div>";
		return $msg;
	}elseif (preg_match('/[^a-z0-9_-]+/i', $username)) {
		$msg = "<div class='alert alert-danger'><strong>Error !</strong>Username only contains alphanumeric, dashes and underscores!</div>";
		
	}
	if(filter_var($email, FILTER_VALIDATE_EMAIL) === false){
		$msg = "<div class='alert alert-danger'><strong>Error !</strong>Invalid email!!</div>";
		return $msg;
	}
	if ($chk_email == true) {
		$msg = "<div class='alert alert-danger'><strong>Error !</strong>Email is already exist!!</div>";
		return $msg;
	}
	$password = md5($data['password']);
	$sql = "INSERT INTO tbl_user(name, username, email,password) VALUES(:name, :username, :email, :password)";
	$query = $this->db->pdo->prepare($sql);
	$query->bindValue(':name', $name);
	$query->bindValue(':username', $username);
	$query->bindValue(':email', $email);
	$query->bindValue(':password', $password);
	$result = $query->execute();
	if($result){
		$msg = "<div class='alert alert-success'><strong>Success!</strong>Thank You, you have been registered</div>";
		return $msg;
	}else{
		$msg = "<div class='alert alert-danger'><strong>Error !</strong>Sorry you cant registered.</div>";
		return $msg;
	}
}
	public function checkemail($email){
		$sql = "SELECT email FROM tbl_user WHERE email=:email";
		$query = $this->db->pdo->prepare($sql);
		$query->bindValue(':email', $email);
		$query->execute();
		if($query->rowCount() > 0){
			return true;
		}else{
			return false;
		}
	}

	public function getLoginuser($email, $password){
		$sql = "SELECT * FROM tbl_user WHERE email=:email AND password=:password LIMIT 1";
		$query = $this->db->pdo->prepare($sql);
		$query->bindValue(':email', $email);
		$query->bindValue(':password', $password);
		$query->execute();
		$result = $query->fetch(PDO::FETCH_OBJ);
		return $result; 
	}

	public function userLogin($data){
		
		$email = $data['email'];
		$password = md5($data['password']);
		$chk_email = $this->checkemail($email);
	

		if($email == "" OR $password == ""){
			$msg = "<div class='alert alert-danger'><strong>Error !</strong>Field must not be empty</div>";
			return $msg;
		}
		if(filter_var($email, FILTER_VALIDATE_EMAIL) === false){
		$msg = "<div class='alert alert-danger'><strong>Error !</strong>Invalid email!!</div>";
		return $msg;
		}
		if ($chk_email == false) {
			$msg = "<div class='alert alert-danger'><strong>Error !</strong>Email is not exist!!</div>";
			return $msg;
		}
		$result = $this->getLoginuser($email, $password); 
		if($result){
			Session::init();
			Session::set("login", true);
			Session::set("id", $result->id);
			Session::set("name", $result->name);
			Session::set("username", $result->username);
			Session::set("loginmsg", "<div class='alert alert-success'><strong>Success !</strong>You are logged in!</div>");
			header("Location: index.php");
		}else{
			$msg = "<div class='alert alert-danger'><strong>Error !</strong>Data not found.</div>";
			return $msg;
		}
	}

	public function getuserdata(){
		$sql = "SELECT * FROM tbl_user ORDER BY id DESC";
		$query = $this->db->pdo->prepare($sql);
		$query->execute();
		$result = $query->fetchAll();
		return $result;
	}

	public function getUserById($id){
		$sql = "SELECT * FROM tbl_user WHERE id =  :id LIMIT 1";
		$query = $this->db->pdo->prepare($sql);
		$query->bindValue(':id', $id);
		$query->execute();
		$result = $query->fetch(PDO::FETCH_OBJ);
		return $result; 
	}

	public function userUpdateData($id, $data){
		$name = $data['name'];
		$username = $data['username'];
		$email = $data['email'];

	if($name == "" OR $username == "" OR $email == ""){
		$msg = "<div class='alert alert-danger'><strong>Error !</strong>Field must not be empty</div>";
		return $msg;
	}
	$sql = "UPDATE  tbl_user SET 
				name = :name,
				username = :username,
				email = :email
				WHERE id = :id";
	$query = $this->db->pdo->prepare($sql);
	$query->bindValue(':name', $name);
	$query->bindValue(':username', $username);
	$query->bindValue(':email', $email);
	$query->bindValue(':id', $id);
	$result = $query->execute();
	if($result){
		$msg = "<div class='alert alert-success'><strong>Success!</strong>Your data have been updated</div>";
		return $msg;
	}else{
		$msg = "<div class='alert alert-danger'><strong>Error !</strong>Sorry you cant update your data.</div>";
		return $msg;
	}
	}

	private function checkpassword($id, $oldpass){
		$password = md5($oldpass);
		$sql = "SELECT password FROM tbl_user WHERE id=:id AND password=:password";
		$query = $this->db->pdo->prepare($sql);
		$query->bindValue(':id', $id);
		$query->bindValue(':password', $password);
		$query->execute();
		if($query->rowCount() > 0){
			return true;
		}else{
			return false;
		}

	}

	public function userUpdatePassword($id, $data){
		$oldpass = $data['old_password'];
		$newpass = $data['password'];
		if($oldpass == "" OR $newpass == ""){
			$msg = "<div class='alert alert-danger'><strong>Error !</strong>Password cant be empty.</div>";
			return $msg;
		}
		$chk_pass = $this->checkpassword($id, $oldpass);
		if ($chk_pass == false) {
			$msg = "<div class='alert alert-danger'><strong>Error !</strong>Password not exist.</div>";
			return $msg;
		}

		if (strlen($newpass) < 6) {
			$msg = "<div class='alert alert-danger'><strong>Error !</strong>Password is too short.</div>";
			return $msg;
		}
		$password = md5($newpass);
		$sql = "UPDATE  tbl_user SET 
				password = :password
				WHERE id = :id";
		$query = $this->db->pdo->prepare($sql);
		$query->bindValue(':id', $id);
		$query->bindValue(':password', $password);
		$result = $query->execute();
		if($result){
			$msg = "<div class='alert alert-success'><strong>Success!</strong>Your password is updated</div>";
			return $msg;
		}else{
			$msg = "<div class='alert alert-danger'><strong>Error !</strong>Sorry you cant update your password.</div>";
			return $msg;
		}
	}
}
?>
