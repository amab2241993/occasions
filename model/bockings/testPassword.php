<?php
	session_start();
	include '../../connect.php';
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		try {
			$password = $_POST['password'];
			$hashedPass = sha1($password);
			// Check If The User Exist In Database
			$stmt = $con->prepare("SELECT id FROM users WHERE user_name = ? AND password = ? LIMIT 1");
			$stmt->execute(array('ali' , $hashedPass));
			$row = $stmt->fetch();
			echo $stmt->rowCount() == 1 ? true : false;
		} catch(PDOExecption $e) {
			print "Error!: " . $e->getMessage() . "</br>";
		}
	}
?>